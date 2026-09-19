<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function getOrCreateCart(): Cart
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(
                ['user_id' => auth()->id()],
                ['currency' => 'IRR']
            );
        }

        $sessionId = session()->getId();
        return Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['currency' => 'IRR']
        );
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product.media']);

        return view('front.cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = $this->getOrCreateCart();

        if ($cart->hasProduct($product->id)) {
            return back()->with('error', 'این محصول قبلاً به سبد خرید اضافه شده است.');
        }

        if (!$product->isAvailable()) {
            return back()->with('error', 'این محصول در حال حاضر موجود نیست.');
        }

        // "Price on request" stones are not sold through the cart: a cart item needs a
        // price, and the buyer should ask for a quote and reserve with a prepayment instead.
        if (!$product->isPurchasable()) {
            return back()->with('error', __('messages.cart_price_on_request'));
        }

        // Atomic reserve — closes the race where two users both pass the
        // isAvailable() check above before either one commits the update.
        // Everything runs in one transaction so that a failure while filling
        // the cart can never leave the stone reserved with nobody holding it.
        $added = DB::transaction(function () use ($cart, $product) {
            if (!$product->tryReserve()) {
                return false;
            }

            try {
                $cart->addProduct($product);
                $cart->update(['expires_at' => now()->addMinutes(30)]);
            } catch (\Throwable $e) {
                // MyISAM tables (the production host's default engine) ignore transactions,
                // so put the stone back by hand before the error propagates
                $product->markAsAvailable();

                throw $e;
            }

            return true;
        });

        if (!$added) {
            return back()->with('error', 'این محصول همین الان توسط کاربر دیگری رزرو شد.');
        }

        return back()->with('success', 'محصول به سبد خرید اضافه شد.');
    }

    public function remove(Product $product)
    {
        $cart = $this->getOrCreateCart();
        $cart->removeProduct($product->id);
        $product->markAsAvailable();
        $this->revalidateCoupon($cart);

        return back()->with('success', 'محصول از سبد خرید حذف شد.');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();

        // محصولات رو دوباره available کن
        foreach ($cart->items as $item) {
            $item->product?->markAsAvailable();
        }

        $cart->clear();

        return back()->with('success', 'سبد خرید پاک شد.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValidForUser(auth()->id())) {
            return back()->with('error', 'کد تخفیف نامعتبر است.');
        }

        $cart     = $this->getOrCreateCart();
        $discount = $coupon->calculateDiscount($cart->subtotal);

        if ($discount <= 0) {
            return back()->with('error', 'این کد تخفیف برای سفارش شما قابل اعمال نیست.');
        }

        $cart->update([
            'coupon_code'     => $coupon->code,
            'discount_amount' => $discount,
        ]);

        return back()->with('success', 'کد تخفیف با موفقیت اعمال شد.');
    }

    /**
     * Re-derive the coupon discount from the current subtotal whenever cart
     * contents change, instead of leaving a stale absolute discount_amount
     * around (which could over-discount, or persist past min_order_amount).
     */
    private function revalidateCoupon(Cart $cart): void
    {
        if (!$cart->coupon_code) {
            return;
        }

        $coupon = Coupon::where('code', $cart->coupon_code)->first();

        if (!$coupon || !$coupon->isValidForUser(auth()->id())) {
            $cart->update(['coupon_code' => null, 'discount_amount' => 0]);
            return;
        }

        $discount = $coupon->calculateDiscount($cart->fresh('items')->subtotal);

        $cart->update([
            'coupon_code'     => $discount > 0 ? $coupon->code : null,
            'discount_amount' => $discount,
        ]);
    }
}
