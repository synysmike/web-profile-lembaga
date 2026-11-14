<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['token', 'ip', 'first_path', 'first_title', 'country', 'region', 'city', 'location_checked_at', 'first_seen_at', 'last_seen_at'])]
class Visitor extends Model
{
    protected function casts(): array
    {
        return [
            'location_checked_at' => 'datetime',
            'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class);
    }

    public function placeLabel(): string
    {
        $parts = array_values(array_filter([$this->city, $this->country], fn ($part) => is_string($part) && $part !== ''));

        return $parts === [] ? 'Tidak diketahui' : implode(', ', $parts);
    }
}
