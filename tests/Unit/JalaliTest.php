<?php

namespace Tests\Unit;

use App\Support\Jalali;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class JalaliTest extends TestCase
{
    public function test_gregorian_to_jalali_known_dates(): void
    {
        $this->assertSame([1404, 1, 1], Jalali::fromGregorian(2025, 3, 21));
        $this->assertSame([1404, 7, 15], Jalali::fromGregorian(2025, 10, 7));
        $this->assertSame([1404, 7, 18], Jalali::fromGregorian(2025, 10, 10));
        $this->assertSame([1405, 1, 1], Jalali::fromGregorian(2026, 3, 21));
        $this->assertSame([1405, 6, 28], Jalali::fromGregorian(2026, 9, 19));
        $this->assertSame([1405, 6, 31], Jalali::fromGregorian(2026, 9, 22));
        $this->assertSame([1405, 7, 1], Jalali::fromGregorian(2026, 9, 23));
    }

    public function test_range_formatting(): void
    {
        $this->assertSame(
            '۱۵ تا ۱۸ مهر ۱۴۰۴',
            Jalali::formatRange(Carbon::create(2025, 10, 7), Carbon::create(2025, 10, 10))
        );

        $this->assertSame(
            '۲۸ شهریور تا ۲ مهر ۱۴۰۵',
            Jalali::formatRange(Carbon::create(2026, 9, 19), Carbon::create(2026, 9, 24))
        );

        $this->assertSame(
            '۱۵ مهر ۱۴۰۴',
            Jalali::formatRange(Carbon::create(2025, 10, 7), null)
        );
    }
}
