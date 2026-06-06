<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with('product.media')
            ->latest()
            ->get();

        return view('front.wishlist.index', compact('wishlists'));
    }

    public function toggle(Product $product)
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'محصول از علاقه‌مندی‌ها حذف شد.';
            $status  = false;
        } else {
            Wishlist::create([
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
            ]);
            $message = 'محصول به علاقه‌مندی‌ها اضافه شد.';
            $status  = true;
        }

        if (request()->wantsJson()) {
            return response()->json(['status' => $status, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
