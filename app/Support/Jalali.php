<?php

namespace App\Support;

use Carbon\CarbonInterface;

/**
 * Minimal Gregorian → Jalali (Persian calendar) conversion, used only to
 * display exhibition dates naturally to Persian readers. No dependency needed.
 */
class Jalali
{
    private const MONTHS = [
        1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور',
        7 => 'مهر', 8 => 'آبان', 9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند',
    ];

    /**
     * @return array{0:int,1:int,2:int} [year, month, day]
     */
    public static function fromGregorian(int $gy, int $gm, int $gd): array
    {
        $cumulative = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $gy2 = $gm > 2 ? $gy + 1 : $gy;

        $days = 355666 + (365 * $gy) + intdiv($gy2 + 3, 4) - intdiv($gy2 + 99, 100)
            + intdiv($gy2 + 399, 400) + $gd + $cumulative[$gm - 1];

        $jy = -1595 + (33 * intdiv($days, 12053));
        $days %= 12053;
        $jy += 4 * intdiv($days, 1461);
        $days %= 1461;

        if ($days > 365) {
            $jy += intdiv($days - 1, 365);
            $days = ($days - 1) % 365;
        }

        if ($days < 186) {
            $jm = 1 + intdiv($days, 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + intdiv($days - 186, 30);
            $jd = 1 + (($days - 186) % 30);
        }

        return [$jy, $jm, $jd];
    }

    public static function monthName(int $month): string
    {
        return self::MONTHS[$month] ?? '';
    }

    public static function toPersianDigits(string|int $value): string
    {
        // Array form: the string form of strtr() is byte-based and breaks multibyte digits.
        return strtr((string) $value, [
            '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
        ]);
    }

    public static function formatDate(CarbonInterface $date): string
    {
        [$y, $m, $d] = self::fromGregorian($date->year, $date->month, $date->day);

        return self::toPersianDigits("{$d} " . self::monthName($m) . " {$y}");
    }

    /**
     * "۱۵ تا ۱۸ مهر ۱۴۰۴", "۲۸ شهریور تا ۲ مهر ۱۴۰۵", or a single date.
     */
    public static function formatRange(CarbonInterface $start, ?CarbonInterface $end): string
    {
        if (! $end || $end->isSameDay($start)) {
            return self::formatDate($start);
        }

        [$sy, $sm, $sd] = self::fromGregorian($start->year, $start->month, $start->day);
        [$ey, $em, $ed] = self::fromGregorian($end->year, $end->month, $end->day);

        if ($sy === $ey && $sm === $em) {
            return self::toPersianDigits("{$sd} تا {$ed} " . self::monthName($sm) . " {$sy}");
        }

        if ($sy === $ey) {
            return self::toPersianDigits("{$sd} " . self::monthName($sm) . " تا {$ed} " . self::monthName($em) . " {$sy}");
        }

        return self::formatDate($start) . ' تا ' . self::formatDate($end);
    }
}
