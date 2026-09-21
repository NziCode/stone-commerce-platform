<?php

namespace App\Models;

use App\Support\WhatsApp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A visitor's request for the price of a stone. Created from the storefront (the "Request a quote" button
 * on every card and on the product page); the sales team follows it up from the admin panel.
 */
class ProductInquiry extends Model
{
    public const STATUSES = ['new', 'contacted', 'closed'];

    protected $fillable = [
        'product_id', 'user_id', 'name', 'phone_country', 'phone', 'contact_method', 'note',
        'status', 'admin_note', 'contacted_at', 'contacted_by', 'ip_address', 'user_agent',
    ];

    protected $casts = ['contacted_at' => 'datetime'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeNew($q)
    {
        return $q->where('status', 'new');
    }

    public function getFullPhoneAttribute(): string
    {
        return trim(($this->phone_country ?? '') . ' ' . $this->phone);
    }

    /** "+989123456789" — for tel: links (a typed leading 0 or "00" is handled). */
    public function getCallNumberAttribute(): ?string
    {
        $digits = WhatsApp::customerDigits($this->phone_country, $this->phone);

        return $digits ? '+' . $digits : null;
    }

    /** Chat link to the customer with a ready-to-edit opening message (Persian for Iranian numbers, English otherwise). */
    public function getWhatsappUrlAttribute(): ?string
    {
        $product = $this->product;
        $name    = $product ? (string) $product->getTranslation('name', 'fa', false) : '';
        $name    = $name !== '' ? $name : (string) ($product?->getTranslation('name', 'en', false) ?: $product?->sku);
        $code    = $product?->sku ? " ({$product->sku})" : '';
        $iran    = trim((string) $this->phone_country) === '+98';

        $message = $iran
            ? "سلام، درخواست استعلام قیمت شما برای سنگ «{$name}»{$code} در گروه تجاری EN را دریافت کردیم. لطفاً هماهنگی‌های بعدی را اینجا انجام دهیم."
            : "Hello, we received your price inquiry for “{$name}”{$code} at EN Trading Group. Let's continue the details here.";

        return WhatsApp::chatUrl($this->phone_country, $this->phone, $message);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new'       => 'جدید',
            'contacted' => 'تماس گرفته شد',
            'closed'    => 'بسته‌شده',
            default     => (string) $this->status,
        };
    }

    public function markContacted(?int $userId = null): void
    {
        $this->update(['status' => 'contacted', 'contacted_at' => now(), 'contacted_by' => $userId]);
    }
}
