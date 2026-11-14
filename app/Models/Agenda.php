<?php

namespace App\Models;

use App\Models\Concerns\HasVisibility;
use App\Support\LocalDate;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

#[Fillable(['happens_on', 'title', 'place', 'is_visible'])]
class Agenda extends Model
{
    use HasVisibility;

    protected function casts(): array
    {
        return [
            'happens_on' => 'date',
            'is_visible' => 'boolean',
        ];
    }

    /**
     * @return Collection<int, self>
     */
    public static function nearest(?int $limit = null): Collection
    {
        $today = Carbon::parse(LocalDate::today())->startOfDay();

        $items = static::query()->visible()->get()->sortBy(function (self $agenda) use ($today) {
            $day = $agenda->happens_on->copy()->startOfDay();
            $distance = (int) $day->diffInDays($today);
            $past = $day->lt($today) ? 1 : 0;

            return sprintf('%08d-%d-%s', $distance, $past, $agenda->happens_on->toDateString());
        })->values();

        return $limit === null ? $items : $items->take($limit)->values();
    }
}
