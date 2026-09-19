<?php

if (! function_exists('cart_enabled')) {
    /**
     * Whether the shopping cart is worth showing: at least one active stone is available
     * at a fixed price. While every stone is "price on request" the sales flow is
     * inquiry → reservation with a prepayment, and a cart would only confuse visitors.
     */
    function cart_enabled(): bool
    {
        try {
            return \Illuminate\Support\Facades\Cache::remember(
                'cart.enabled',
                300,
                fn () => \App\Models\Product::query()->where('is_active', true)->purchasable()->exists()
            );
        } catch (\Throwable) {
            return false;   // no database yet (fresh install / migrations) — nothing to buy
        }
    }
}

if (! function_exists('display_phone')) {
    /**
     * Prefix a stored phone number with "+" for on-screen display only.
     * Never write the result back to the database — this is presentation-only.
     */
    function display_phone(?string $phone): ?string
    {
        $phone = trim((string) $phone);

        if ($phone === '') {
            return $phone;
        }

        return str_starts_with($phone, '+') ? $phone : '+'.$phone;
    }
}
