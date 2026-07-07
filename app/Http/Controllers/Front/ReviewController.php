<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Services\SmsService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product, SmsService $sms)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // چک کن قبلاً نظر نداده باشه
        $existing = Review::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', __('messages.review_already_submitted') ?? 'You have already reviewed this product.');
        }

        $review = Review::create([
            'product_id'       => $product->id,
            'user_id'          => auth()->id(),
            'reviewer_name'    => auth()->user()->name,
            'reviewer_email'   => auth()->user()->email,
            'reviewer_country' => auth()->user()->country ?? null,
            'reviewer_company' => auth()->user()->company ?? null,
            'rating'           => (int) $request->rating,
            'comment'          => $request->comment ?: null,
            'status'           => 'pending',
        ]);

        // ارسال پیامک به مدیر در صورت ثبت نظر جدید
        try {
            $sms->notifyNewReview(
                productName: $product->getTranslation('name', app()->getLocale()),
                reviewerName: $review->reviewer_name,
                rating: $review->rating,
            );
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('success', __('messages.review_submitted') ?? 'Your review has been submitted and is pending approval.');
    }
}
