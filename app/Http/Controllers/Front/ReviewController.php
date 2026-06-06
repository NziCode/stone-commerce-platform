<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|min:10|max:1000',
        ]);

        // چک کن قبلاً نظر نداده باشه
        $existing = Review::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'شما قبلاً برای این محصول نظر ثبت کرده‌اید.');
        }

        Review::create([
            'product_id'       => $product->id,
            'user_id'          => auth()->id(),
            'reviewer_name'    => auth()->user()->name,
            'reviewer_email'   => auth()->user()->email,
            'reviewer_country' => auth()->user()->country,
            'reviewer_company' => auth()->user()->company,
            'rating'           => $request->rating,
            'comment'          => $request->comment,
            'status'           => 'pending',
        ]);

        return back()->with('success', 'نظر شما با موفقیت ثبت شد و پس از تأیید نمایش داده خواهد شد.');
    }
}
