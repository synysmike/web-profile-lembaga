<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class LocalDate
{
    private const MONTHS = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public static function today(): string
    {
        return now('Asia/Jakarta')->toDateString();
    }

    public static function long(CarbonInterface|string $value): string
    {
        $day = $value instanceof CarbonInterface ? $value->toDateString() : substr((string) $value, 0, 10);
        $date = Carbon::createFromFormat('Y-m-d', $day, 'UTC');

        return $date->day.' '.self::MONTHS[$date->month].' '.$date->year;
    }

    public static function stamp(CarbonInterface|string $value): string
    {
        $date = $value instanceof CarbonInterface
            ? $value->copy()->timezone('Asia/Jakarta')
            : Carbon::parse($value)->timezone('Asia/Jakarta');

        return $date->day.' '.self::MONTHS[$date->month].' '.$date->year.', '.$date->format('H.i');
    }

    public static function short(CarbonInterface|string $value): string
    {
        $day = $value instanceof CarbonInterface ? $value->toDateString() : substr((string) $value, 0, 10);
        $date = Carbon::createFromFormat('Y-m-d', $day, 'UTC');

        return $date->format('j/n');
    }
}
