<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // A developer's .env may hold real SMS gateway credentials — a test must never reach them.
        config([
            'services.kavenegar.api_key'               => null,
            'services.kavenegar.sender'                => null,
            'services.kavenegar.review_notify_numbers' => null,
        ]);
    }
}
