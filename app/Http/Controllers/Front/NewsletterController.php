<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'name'     => 'nullable|string|max:255',
            'language' => 'nullable|string|max:10',
        ]);

        $existing = Newsletter::where('email', $request->email)->first();

        if ($existing) {
            if (!$existing->is_active) {
                $existing->confirm();
                return back()->with('success', 'اشتراک شما مجدداً فعال شد.');
            }
            return back()->with('info', 'این ایمیل قبلاً ثبت شده است.');
        }

        Newsletter::create([
            'email'        => $request->email,
            'name'         => $request->name,
            'language'     => $request->language ?? app()->getLocale(),
            'confirmed_at' => now(),
            'is_active'    => true,
        ]);

        return back()->with('success', 'با موفقیت در خبرنامه ثبت‌نام شدید.');
    }

    public function unsubscribe(string $token)
    {
        $newsletter = Newsletter::where('token', $token)->firstOrFail();
        $newsletter->unsubscribe();

        return view('front.newsletter.unsubscribed');
    }
}
