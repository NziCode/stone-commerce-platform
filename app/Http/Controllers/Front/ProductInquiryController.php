<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductInquiry;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;

/**
 * "Request a quote" on a stone: the visitor leaves a phone number, the sales team is told at once
 * (email / SMS / bot, whichever is configured) and follows the request up from the admin panel.
 */
class ProductInquiryController extends Controller
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

        // nothing to ask about a hidden or an already sold stone
        if (! $product->is_active || $product->status === 'sold') {
            return $this->fail($request, __('messages.inquiry_unavailable'), 422);
        }

        $phone = preg_replace('/\s+/', ' ', trim($data['phone']));

        // the same number asking about the same stone again within a day is one request, not a second alert
        $again = ProductInquiry::where('product_id', $product->id)
            ->where('phone_country', $data['phone_country'])
            ->where('phone', $phone)
            ->where('status', 'new')
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if (! $again) {
            $inquiry = ProductInquiry::create([
                'product_id'     => $product->id,
                'user_id'        => auth()->id(),
                'name'           => $data['name'] ?? auth()->user()?->name,
                'phone_country'  => $data['phone_country'],
                'phone'          => $phone,
                'contact_method' => $data['contact_method'],
                'note'           => $data['note'] ?? null,
                'ip_address'     => $request->ip(),
                'user_agent'     => mb_substr((string) $request->userAgent(), 0, 255),
            ]);

            $stone = $product->getTranslation('name', 'fa', false) ?: $product->getTranslation('name', 'en', false);

            AdminNotifier::dispatch('استعلام قیمت جدید', [
                'سنگ: ' . $stone . ($product->sku ? ' (' . $product->sku . ')' : ''),
                'نام: ' . ($inquiry->name ?: '—'),
                'تلفن: ' . $inquiry->full_phone . ' — ' . ($inquiry->contact_method === 'whatsapp' ? 'واتساپ' : 'تماس تلفنی'),
                $inquiry->note ? 'توضیح: ' . $inquiry->note : null,
            ], AdminNotifier::adminUrl('product-inquiries'));
        }

        $message = __('messages.inquiry_sent');

        return $request->expectsJson()
            ? response()->json(['ok' => true, 'message' => $message])
            : back()->with('success', $message);
    }

    private function fail(Request $request, string $message, int $status)
    {
        return $request->expectsJson()
            ? response()->json(['ok' => false, 'message' => $message], $status)
            : back()->with('error', $message);
    }
}
