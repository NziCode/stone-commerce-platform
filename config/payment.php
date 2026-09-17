<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    */
    'default' => env('PAYMENT_DRIVER', 'zarinpal'),

    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    | shetabit/multipay v2 is a plain library (no Laravel service provider,
    | no config auto-publish) — this file is required for
    | `new \Shetabit\Multipay\Payment(config('payment'))` to resolve a driver.
    */
    'drivers' => [
        'zarinpal' => [
            'apiPurchaseUrl'            => 'https://api.zarinpal.com/pg/v4/payment/request.json',
            'apiPaymentUrl'             => 'https://www.zarinpal.com/pg/StartPay/',
            'apiVerificationUrl'        => 'https://api.zarinpal.com/pg/v4/payment/verify.json',

            'sandboxApiPurchaseUrl'     => 'https://sandbox.zarinpal.com/pg/v4/payment/request.json',
            'sandboxApiPaymentUrl'      => 'https://sandbox.zarinpal.com/pg/StartPay/',
            'sandboxApiVerificationUrl' => 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json',

            'mode'        => env('ZARINPAL_SANDBOX', true) ? 'sandbox' : 'normal',
            'merchantId'  => env('ZARINPAL_MERCHANT_ID', ''),
            'callbackUrl' => env('APP_URL') . '/payment/callback/zarinpal',
            'description' => 'پرداخت آنلاین سفارش',
            'currency'    => 'T',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Driver class map
    |--------------------------------------------------------------------------
    */
    'map' => [
        'zarinpal' => \Shetabit\Multipay\Drivers\Zarinpal\Zarinpal::class,
    ],
];
