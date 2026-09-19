<?php

namespace App\Support;

use App\Models\Setting;

/**
 * wa.me chat links — one place for the business number (guide pages, product page)
 * and for customer numbers typed into forms (admin "message the customer" action).
 */
class WhatsApp
{
    /**
     * Chat link to the business, with a prefilled message. Uses the "WhatsApp" social
     * setting when it is filled in (a number or a wa.me link), otherwise the site phone.
     */
    public static function siteUrl(string $message = ''): ?string
    {
        $configured = trim((string) Setting::get('social_whatsapp', ''));

        if (preg_match('#^https?://#i', $configured)) {
            $base = $configured;
        } else {
            $digits = static::digits($configured !== '' ? $configured : (string) Setting::get('site_phone', ''));

            if ($digits === '') {
                return null;
            }

            $base = "https://wa.me/{$digits}";
        }

        return static::withText($base, $message);
    }

    /**
     * Chat link to a customer whose number was typed into a form as a dial code
     * ("+98", or "other" = the number already carries its country code) plus a national number.
     */
    public static function chatUrl(?string $dialCode, ?string $phone, string $message = ''): ?string
    {
        $digits = static::customerDigits($dialCode, $phone);

        return $digits === null ? null : static::withText("https://wa.me/{$digits}", $message);
    }

    /** The customer's full international number, digits only (no "+"), or null when there is none. */
    public static function customerDigits(?string $dialCode, ?string $phone): ?string
    {
        $number = preg_replace('/\D+/', '', (string) $phone);

        if ($number === '') {
            return null;
        }

        $dial = preg_replace('/\D+/', '', (string) $dialCode);

        if ($dial === '' || str_starts_with($number, '00')) {
            // "other" country / written with 00 in front → already international
            $digits = ltrim($number, '0');
        } else {
            // national number, usually with a trunk "0" in front (0912… → 912…)
            $digits = $dial . ltrim($number, '0');
        }

        return $digits === '' ? null : $digits;
    }

    /** "+98 914 …" / "0914 …" / "98914…" → "98914…" (wa.me wants country code + number, digits only). */
    public static function digits(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        // an Iranian mobile number written the local way (09xx…) → 989xx…
        if (strlen($digits) === 11 && str_starts_with($digits, '09')) {
            $digits = '98' . substr($digits, 1);
        }

        return $digits;
    }

    /** The text parameter only means something on chat links, not on group invites. */
    private static function withText(string $base, string $message): string
    {
        if ($message === '' || ! preg_match('#(wa\.me|api\.whatsapp\.com/send|web\.whatsapp\.com/send)#i', $base)) {
            return $base;
        }

        return $base . (str_contains($base, '?') ? '&' : '?') . 'text=' . rawurlencode($message);
    }
}
