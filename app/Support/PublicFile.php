<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class PublicFile
{
    public static function url(?string $path): ?string
    {
        if (! is_string($path) || $path === '' || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    public static function delete(?string $path): void
    {
        if (is_string($path) && $path !== '') {
            Storage::disk('public')->delete($path);
        }
    }
}
