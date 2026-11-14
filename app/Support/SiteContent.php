<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SiteContent
{
    public const HERO_IMAGE = 'hero_image';

    public const VIDEOTRON = 'videotron';

    public static function heroImageUrl(): ?string
    {
        $path = self::value(self::HERO_IMAGE);

        if ($path === null || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * @return array{kind: string, src: string, background?: string}|null
     */
    public static function videotron(): ?array
    {
        $raw = self::value(self::VIDEOTRON);

        if ($raw === null) {
            return null;
        }

        $stored = json_decode($raw, true);

        if (! is_array($stored)) {
            return null;
        }

        if (($stored['source'] ?? null) === 'file') {
            $path = $stored['path'] ?? '';

            if (! is_string($path) || $path === '' || ! Storage::disk('public')->exists($path)) {
                return null;
            }

            return [
                'kind' => 'file',
                'src' => Storage::disk('public')->url($path),
            ];
        }

        if (($stored['source'] ?? null) === 'url' && is_string($stored['url'] ?? null)) {
            return self::describeUrl($stored['url']);
        }

        return null;
    }

    public static function storedHeroPath(): ?string
    {
        return self::value(self::HERO_IMAGE);
    }

    public static function storedVideotronUrl(): ?string
    {
        $raw = self::value(self::VIDEOTRON);
        $stored = $raw === null ? null : json_decode($raw, true);

        if (is_array($stored) && ($stored['source'] ?? null) === 'url' && is_string($stored['url'] ?? null)) {
            return $stored['url'];
        }

        return null;
    }

    public static function hasVideotronFile(): bool
    {
        $raw = self::value(self::VIDEOTRON);
        $stored = $raw === null ? null : json_decode($raw, true);

        return is_array($stored)
            && ($stored['source'] ?? null) === 'file'
            && is_string($stored['path'] ?? null)
            && Storage::disk('public')->exists($stored['path']);
    }

    /**
     * @return array{kind: string, src: string, background?: string}|null
     */
    public static function describeUrl(string $url): ?array
    {
        $parts = parse_url(trim($url));

        if (! is_array($parts) || ($parts['scheme'] ?? '') !== 'https') {
            return null;
        }

        $host = strtolower(preg_replace('/^www\./', '', $parts['host'] ?? '') ?? '');
        $path = $parts['path'] ?? '';

        if (in_array($host, ['youtube.com', 'm.youtube.com', 'youtube-nocookie.com'], true)) {
            parse_str($parts['query'] ?? '', $query);
            $id = $query['v'] ?? null;

            if (! is_string($id) && preg_match('#/(?:embed|shorts)/([A-Za-z0-9_-]{6,})#', $path, $match)) {
                $id = $match[1];
            }

            return self::youtube($id);
        }

        if ($host === 'youtu.be') {
            return self::youtube(trim($path, '/'));
        }

        if (in_array($host, ['vimeo.com', 'player.vimeo.com'], true) && preg_match('#/(\d+)#', $path, $match)) {
            $src = 'https://player.vimeo.com/video/'.$match[1];

            return [
                'kind' => 'embed',
                'src' => $src,
                'background' => $src.'?background=1&autoplay=1&muted=1&loop=1',
            ];
        }

        if (preg_match('/\.(mp4|webm)$/i', $path)) {
            return ['kind' => 'direct', 'src' => $url];
        }

        return null;
    }

    /**
     * @return array{kind: string, src: string, background: string}|null
     */
    private static function youtube(mixed $id): ?array
    {
        if (! is_string($id) || ! preg_match('/^[A-Za-z0-9_-]{6,}$/', $id)) {
            return null;
        }

        $src = 'https://www.youtube-nocookie.com/embed/'.$id;

        return [
            'kind' => 'embed',
            'src' => $src,
            'background' => $src.'?autoplay=1&mute=1&controls=0&loop=1&playlist='.$id.'&playsinline=1&rel=0&modestbranding=1',
        ];
    }

    private static function value(string $key): ?string
    {
        try {
            $value = Setting::getValue($key);
        } catch (Throwable) {
            return null;
        }

        return is_string($value) && $value !== '' ? $value : null;
    }
}
