<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function index()
    {
        return view('front.contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:5',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        ContactMessage::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'company'    => $request->company,
            'country'    => $request->country,
            'subject'    => $request->subject,
            'message'    => $request->message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        AdminNotifier::dispatch('پیام جدید از فرم تماس', [
            'نام: ' . $request->name,
            'ایمیل: ' . $request->email,
            $request->phone ? 'تلفن: ' . $request->phone : null,
            $request->subject ? 'موضوع: ' . $request->subject : null,
            'پیام: ' . Str::limit($request->message, 300),
        ], AdminNotifier::adminUrl('contact-messages'));

        return back()->with('success', 'پیام شما با موفقیت ارسال شد. به زودی با شما تماس خواهیم گرفت.');
    }
}
