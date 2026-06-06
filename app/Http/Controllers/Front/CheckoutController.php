<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
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

        $order = DB::transaction(function () use ($request, $cart) {
            $order = Order::create([
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
                'coupon_code'      => $cart->coupon_code,
                'subtotal'         => $cart->subtotal,
                'discount_amount'  => $cart->discount_amount,
                'total'            => $cart->total,
                'currency'         => $cart->currency,
                'customer_notes'   => $request->customer_notes,
            ]);

            foreach ($cart->items as $item) {
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

            // پاک کردن سبد خرید
            $cart->items()->delete();
            $cart->update(['coupon_code' => null, 'discount_amount' => 0]);

            return $order;
        });

        return redirect()->route('payment.index', $order);
    }
}
