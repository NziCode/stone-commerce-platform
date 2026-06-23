<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribed()
    {
        return view('front.newsletter.subscribed');
    }

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
                return redirect()->route('newsletter.subscribed')
                    ->with('success', __('messages.newsletter_reactivated') ?? 'Your subscription has been reactivated.');
            }
            return back()->with('info', __('messages.newsletter_already_subscribed') ?? 'This email is already subscribed.');
        }

        Newsletter::create([
            'email'        => $request->email,
            'name'         => $request->name,
            'language'     => $request->language ?? app()->getLocale(),
            'confirmed_at' => now(),
            'is_active'    => true,
        ]);

        return redirect()->route('newsletter.subscribed');
    }

    public function unsubscribe(string $token)
    {
        $newsletter = Newsletter::where('token', $token)->firstOrFail();
        $newsletter->unsubscribe();

        return view('front.newsletter.unsubscribed');
    }
}
