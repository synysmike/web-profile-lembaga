<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Throwable;

class GeoPlace
{
    /**
     * @return array{country: ?string, region: ?string, city: ?string}|null
     */
    public static function lookup(?string $ip): ?array
    {
        try {
            if (! is_string($ip) || ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return null;
            }

            $response = Http::connectTimeout(1)->timeout(1)->acceptJson()->get('http://ip-api.com/json/'.$ip, [
                'fields' => 'status,country,regionName,city',
                'lang' => 'id',
            ]);

            if (! $response->ok()) {
                return null;
            }

            $data = $response->json();

            if (! is_array($data) || ($data['status'] ?? null) !== 'success') {
                return null;
            }

            return [
                'country' => self::place($data['country'] ?? null),
                'region' => self::place($data['regionName'] ?? null),
                'city' => self::place($data['city'] ?? null),
            ];
        } catch (Throwable) {
            return null;
        }
    }

    private static function place(mixed $value): ?string
    {
        $text = trim((string) $value);

        if ($text === '') {
            return null;
        }

        return mb_substr($text, 0, 80);
    }
}
