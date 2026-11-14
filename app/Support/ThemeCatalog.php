<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Str;
use Throwable;

class ThemeCatalog
{
    /**
     * @var array<string, string>
     */
    public const FIELDS = [
        'page' => 'Latar halaman',
        'hero' => 'Pita hero',
        'hero_text' => 'Teks pada hero dan pita',
        'accent' => 'Aksen',
        'ink' => 'Teks utama',
        'muted' => 'Teks sekunder',
        'footer' => 'Footer',
    ];

    public const MAX_PROFILES = 6;

    /**
     * @return array{active: string, items: list<array<string, string>>}
     */
    public static function state(): array
    {
        try {
            $raw = Setting::getValue('theme_profiles');
        } catch (Throwable) {
            return self::fallbackState();
        }

        $decoded = is_string($raw) ? json_decode($raw, true) : null;

        if (is_array($decoded)) {
            $state = self::sanitize($decoded);

            if ($state !== null) {
                return $state;
            }
        }

        return self::legacyState();
    }

    /**
     * @return array{key: string, name: string, page: string, hero: string, hero_text: string, accent: string, ink: string, muted: string, footer: string}
     */
    public static function active(): array
    {
        $state = self::state();
        $current = $state['items'][0];

        foreach ($state['items'] as $item) {
            if ($item['id'] === $state['active']) {
                $current = $item;
                break;
            }
        }

        return [
            'key' => $current['id'],
            'name' => $current['name'],
            'page' => $current['page'],
            'hero' => $current['hero'],
            'hero_text' => $current['hero_text'],
            'accent' => $current['accent'],
            'ink' => $current['ink'],
            'muted' => $current['muted'],
            'footer' => $current['footer'],
        ];
    }

    /**
     * @param  list<array<string, string>>  $profiles
     */
    public static function save(string $active, array $profiles): void
    {
        $items = [];

        foreach ($profiles as $profile) {
            $item = [
                'id' => $profile['id'],
                'name' => trim($profile['name']),
            ];

            foreach (array_keys(self::FIELDS) as $field) {
                $item[$field] = strtolower($profile[$field]);
            }

            $items[] = $item;
        }

        Setting::putValue('theme_profiles', json_encode([
            'active' => $active,
            'items' => $items,
        ], JSON_UNESCAPED_UNICODE));
        Setting::forget('theme');
    }

    public static function seedDefault(): void
    {
        $profile = self::blank('utama', 'Utama');
        self::save($profile['id'], [$profile]);
    }

    /**
     * @return array<string, string>
     */
    public static function blank(string $id, string $name): array
    {
        return ['id' => $id, 'name' => $name] + self::fallbackColors();
    }

    public static function newId(): string
    {
        return Str::lower(Str::random(12));
    }

    /**
     * @return array{active: string, items: list<array<string, string>>}
     */
    private static function legacyState(): array
    {
        try {
            $key = Setting::getValue('theme', 'langit');
        } catch (Throwable) {
            $key = 'langit';
        }

        $colors = self::legacyPalettes()[$key] ?? self::fallbackColors();
        $profile = self::blank('utama', 'Utama');

        foreach ($colors as $field => $value) {
            $profile[$field] = $value;
        }

        return [
            'active' => 'utama',
            'items' => [$profile],
        ];
    }

    /**
     * @return array{active: string, items: list<array<string, string>>}
     */
    private static function fallbackState(): array
    {
        return [
            'active' => 'utama',
            'items' => [self::blank('utama', 'Utama')],
        ];
    }

    /**
     * @param  array<string, mixed>  $decoded
     * @return array{active: string, items: list<array<string, string>>}|null
     */
    private static function sanitize(array $decoded): ?array
    {
        $items = $decoded['items'] ?? null;

        if (! is_array($items) || $items === []) {
            return null;
        }

        $clean = [];

        foreach ($items as $item) {
            if (! is_array($item) || ! is_string($item['id'] ?? null) || ! preg_match('/^[a-z0-9]{4,40}$/', $item['id'])) {
                return null;
            }

            $name = trim((string) ($item['name'] ?? ''));

            if ($name === '') {
                return null;
            }

            $profile = [
                'id' => $item['id'],
                'name' => $name,
            ];

            foreach (self::fallbackColors() as $field => $fallback) {
                $profile[$field] = self::hex($item[$field] ?? null, $fallback);
            }

            $clean[] = $profile;
        }

        $active = is_string($decoded['active'] ?? null) ? $decoded['active'] : $clean[0]['id'];
        $ids = array_column($clean, 'id');

        if (! in_array($active, $ids, true)) {
            $active = $clean[0]['id'];
        }

        return ['active' => $active, 'items' => $clean];
    }

    private static function hex(mixed $value, string $fallback): string
    {
        return is_string($value) && preg_match('/^#[0-9A-Fa-f]{6}$/', $value)
            ? strtolower($value)
            : $fallback;
    }

    /**
     * @return array{page: string, hero: string, hero_text: string, accent: string, ink: string, muted: string, footer: string}
     */
    private static function fallbackColors(): array
    {
        return self::legacyPalettes()['langit'];
    }

    /**
     * @return array<string, array{page: string, hero: string, hero_text: string, accent: string, ink: string, muted: string, footer: string}>
     */
    private static function legacyPalettes(): array
    {
        return [
            'langit' => [
                'page' => '#f0f9ff',
                'hero' => '#38bdf8',
                'hero_text' => '#0c4a6e',
                'accent' => '#0284c7',
                'ink' => '#1e293b',
                'muted' => '#475569',
                'footer' => '#0c4a6e',
            ],
            'laut' => [
                'page' => '#eff6ff',
                'hero' => '#1e3a8a',
                'hero_text' => '#ffffff',
                'accent' => '#1d4ed8',
                'ink' => '#0f172a',
                'muted' => '#334155',
                'footer' => '#0f172a',
            ],
            'daun' => [
                'page' => '#f0fdf4',
                'hero' => '#86efac',
                'hero_text' => '#14532d',
                'accent' => '#15803d',
                'ink' => '#14532d',
                'muted' => '#3f6212',
                'footer' => '#14532d',
            ],
        ];
    }
}
