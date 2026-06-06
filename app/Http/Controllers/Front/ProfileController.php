<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user   = auth()->user();
        $orders = $user->orders()->latest()->limit(5)->get();

        return view('front.profile.index', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'company' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:5',
        ]);

        $user->update($request->only('name', 'phone', 'company', 'country'));

        return back()->with('success', 'پروفایل با موفقیت بروزرسانی شد.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'رمز عبور با موفقیت تغییر کرد.');
    }
}
