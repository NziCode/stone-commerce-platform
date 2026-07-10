<?php

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
