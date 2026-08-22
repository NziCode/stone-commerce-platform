<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ReservationRequest;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'           => ['nullable', 'string', 'max:150'],
            'phone_country'  => ['required', 'string', 'max:6'],
            'phone'          => ['required', 'string', 'max:30', 'regex:/^[0-9\s\-]{5,20}$/'],
            'contact_method' => ['required', 'in:call,whatsapp'],
            'note'           => ['nullable', 'string', 'max:1000'],
        ]);

        if (! $product->isAvailable()) {
            return back()->with('error', __('messages.reservation_product_unavailable'));
        }

        if ($product->hasActiveReservationRequest()) {
            return back()->with('error', __('messages.reservation_already_pending'));
        }

        ReservationRequest::create([
            'product_id'     => $product->id,
            'user_id'        => auth()->id(),
            'name'           => $data['name'] ?? auth()->user()?->name,
            'phone_country'  => $data['phone_country'],
            'phone'          => preg_replace('/\s+/', ' ', trim($data['phone'])),
            'contact_method' => $data['contact_method'],
            'note'           => $data['note'] ?? null,
            'status'         => 'pending',
        ]);

        return back()->with('success', __('messages.reservation_request_sent'));
    }
}
