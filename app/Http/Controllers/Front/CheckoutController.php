<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with(['items.product.media'])
            ->first();

        if (!$cart || $cart->isEmpty) {
            return redirect()->route('cart.index')->with('error', 'سبد خرید شما خالی است.');
        }

        return view('front.checkout.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'required|email',
            'customer_phone'   => 'nullable|string|max:30',
            'customer_company' => 'nullable|string|max:255',
            'customer_country' => 'nullable|string|max:5',
            'customer_address' => 'nullable|string',
            'payment_type'     => 'required|in:online,receipt',
            'customer_notes'   => 'nullable|string',
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->with(['items.product'])
            ->first();

        if (!$cart || $cart->isEmpty) {
            return redirect()->route('cart.index')->with('error', 'سبد خرید شما خالی است.');
        }

        // Lock the cart row so a double-submit (double-click, back-button
        // resubmit) can't run this transaction twice concurrently for the
        // same cart before the first attempt clears its items.
        try {
            $order = DB::transaction(function () use ($request, $cart) {
                $lockedCart = Cart::where('id', $cart->id)->lockForUpdate()->first();

                if (!$lockedCart || $lockedCart->items()->count() === 0) {
                    abort(422, 'سبد خرید شما خالی است.');
                }

                $items = $lockedCart->items()->with('product')->get();

                // Re-verify each product is still reserved/available for this
                // checkout — closes the window where a race elsewhere left a
                // product no longer valid by the time checkout runs.
                foreach ($items as $item) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();

                    if (!$product || $product->isSold()) {
                        abort(422, 'یکی از محصولات سبد خرید شما دیگر موجود نیست: ' . ($product?->getTranslation('name', app()->getLocale()) ?? ''));
                    }
                }

                // Recompute the coupon discount from the current subtotal —
                // never trust a stale discount_amount stored earlier.
                $subtotal = $items->sum('price');
                $discount = 0;
                $couponCode = null;

                if ($lockedCart->coupon_code) {
                    $coupon = Coupon::where('code', $lockedCart->coupon_code)->first();
                    if ($coupon && $coupon->isValidForUser(auth()->id())) {
                        $discount = $coupon->calculateDiscount($subtotal);
                        $couponCode = $discount > 0 ? $coupon->code : null;
                    }
                }

                $order = $this->createOrderWithRetry($request, $lockedCart, $items, $subtotal, $discount, $couponCode);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id'           => $order->id,
                        'product_id'         => $item->product_id,
                        'product_name'       => $item->product->getTranslation('name', app()->getLocale()),
                        'product_sku'        => $item->product->sku,
                        'product_attributes' => $item->product->attributes->toArray(),
                        'price'              => $item->price,
                        'currency'           => $item->currency,
                    ]);
                }

                if ($couponCode) {
                    $coupon->incrementUsage();
                }

                // پاک کردن سبد خرید
                $lockedCart->items()->delete();
                $lockedCart->update(['coupon_code' => null, 'discount_amount' => 0, 'expires_at' => null]);

                return $order;
            });
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        return redirect()->route('payment.index', $order);
    }

    private function createOrderWithRetry(Request $request, Cart $cart, $items, float $subtotal, float $discount, ?string $couponCode): Order
    {
        $total = max(0, $subtotal - $discount);

        for ($attempt = 0; $attempt < 3; $attempt++) {
            try {
                return Order::create([
                    'order_number'     => Order::generateOrderNumber(),
                    'user_id'          => auth()->id(),
                    'customer_name'    => $request->customer_name,
                    'customer_email'   => $request->customer_email,
                    'customer_phone'   => $request->customer_phone,
                    'customer_company' => $request->customer_company,
                    'customer_country' => $request->customer_country,
                    'customer_address' => $request->customer_address,
                    'status'           => 'pending',
                    'payment_type'     => $request->payment_type,
                    'coupon_code'      => $couponCode,
                    'subtotal'         => $subtotal,
                    'discount_amount'  => $discount,
                    'total'            => $total,
                    'currency'         => $cart->currency,
                    'customer_notes'   => $request->customer_notes,
                ]);
            } catch (QueryException $e) {
                // 23000 = integrity constraint violation (order_number unique clash) — regenerate and retry.
                if ($e->getCode() !== '23000' || $attempt === 2) {
                    throw $e;
                }
            }
        }

        throw new \RuntimeException('Failed to generate a unique order number after retries.');
    }
}
