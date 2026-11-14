<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SchoolProfile
{
    public const KEY = 'school_profile';

    public const LOGO = 'school_logo';

    /**
     * @return array{name: string, tagline: string, address: string, phone: string, email: string, logo: ?string}
     */
    public static function data(): array
    {
        $stored = self::stored();

        return [
            'name' => self::text($stored['name'] ?? null, self::defaults()['name']),
            'tagline' => self::text($stored['tagline'] ?? null, ''),
            'address' => self::text($stored['address'] ?? null, ''),
            'phone' => self::text($stored['phone'] ?? null, ''),
            'email' => self::text($stored['email'] ?? null, ''),
            'logo' => self::logoUrl(),
        ];
    }

    /**
     * @return array{name: string, tagline: string, address: string, phone: string, email: string}
     */
    public static function defaults(): array
    {
        return [
            'name' => School::NAME,
            'tagline' => 'Beriman, Berilmu, Beramal. Template profil madrasah untuk halaman publik.',
            'address' => 'Jl. Pesantren No. 8, Kota Pelajar',
            'phone' => '031-5551234',
            'email' => 'info@mtsnurilmi.sch.id',
        ];
    }

    public static function storedLogoPath(): ?string
    {
        try {
            $path = Setting::getValue(self::LOGO);
        } catch (Throwable) {
            return null;
        }

        return is_string($path) && $path !== '' ? $path : null;
    }

    public static function logoUrl(): ?string
    {
        $path = self::storedLogoPath();

        if ($path === null || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * @param  array{name: string, tagline: string, address: string, phone: string, email: string}  $profile
     */
    public static function save(array $profile): void
    {
        Setting::putValue(self::KEY, json_encode([
            'name' => $profile['name'],
            'tagline' => $profile['tagline'],
            'address' => $profile['address'],
            'phone' => $profile['phone'],
            'email' => $profile['email'],
        ], JSON_UNESCAPED_UNICODE));
    }

    /**
     * @return array{name: string, tagline: string, address: string, phone: string, email: string}
     */
    private static function stored(): array
    {
        $defaults = self::defaults();

        try {
            $raw = Setting::getValue(self::KEY);
        } catch (Throwable) {
            return $defaults;
        }

        $decoded = is_string($raw) ? json_decode($raw, true) : null;

        if (! is_array($decoded)) {
            return $defaults;
        }

        foreach ($defaults as $field => $fallback) {
            $defaults[$field] = array_key_exists($field, $decoded)
                ? self::text($decoded[$field], '')
                : $fallback;
        }

        if ($defaults['name'] === '') {
            $defaults['name'] = self::defaults()['name'];
        }

        return $defaults;
    }

    private static function text(mixed $value, string $fallback): string
    {
        if (! is_string($value)) {
            return $fallback;
        }

        $value = trim($value);

        return $value === '' ? $fallback : $value;
    }
}
