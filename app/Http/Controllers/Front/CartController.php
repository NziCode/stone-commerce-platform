<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

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
        if (!$product->isAvailable()) {
            return back()->with('error', 'این محصول در حال حاضر موجود نیست.');
        }

        $cart = $this->getOrCreateCart();

        if ($cart->hasProduct($product->id)) {
            return back()->with('error', 'این محصول قبلاً به سبد خرید اضافه شده است.');
        }

        $cart->addProduct($product);
        $product->markAsReserved();

        return back()->with('success', 'محصول به سبد خرید اضافه شد.');
    }

    public function remove(Product $product)
    {
        $cart = $this->getOrCreateCart();
        $cart->removeProduct($product->id);
        $product->markAsAvailable();

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

        if (!$coupon || !$coupon->isValid()) {
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
}
