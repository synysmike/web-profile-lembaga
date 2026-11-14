<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Visitor;
use App\Support\LocalDate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminVisitorController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->query('rentang', '7');

        if (! in_array($range, ['7', '30', 'semua'], true)) {
            $range = '7';
        }

        $today = Carbon::parse(LocalDate::today());
        $start = match ($range) {
            '30' => $today->copy()->subDays(29)->toDateString(),
            'semua' => null,
            default => $today->copy()->subDays(6)->toDateString(),
        };

        $chartStart = $today->copy()->subDays(13);
        $chart = [];

        for ($i = 0; $i < 14; $i++) {
            $day = $chartStart->copy()->addDays($i);
            $chart[] = [
                'label' => $day->format('j/n'),
                'date' => $day->toDateString(),
                'visits' => 0,
            ];
        }

        $visitCounts = Visit::query()
            ->where('visited_on', '>=', $chartStart->toDateString())
            ->selectRaw('visited_on as day, count(distinct visitor_id) as total')
            ->groupBy('visited_on')
            ->get()
            ->mapWithKeys(fn ($row) => [substr((string) $row->day, 0, 10) => (int) $row->total]);

        foreach ($chart as $index => $column) {
            $chart[$index]['visits'] = (int) ($visitCounts[$column['date']] ?? 0);
        }

        $visitorQuery = Visitor::query()->orderByDesc('last_seen_at');

        if ($start !== null) {
            $visitorQuery->whereHas('visits', fn ($query) => $query->where('visited_on', '>=', $start));
        }

        $visitors = $visitorQuery->get();
        $places = $visitors
            ->groupBy(fn (Visitor $visitor) => $visitor->placeLabel())
            ->map(fn ($group, $label) => ['label' => $label, 'total' => $group->count()])
            ->sortByDesc('total')
            ->take(10)
            ->values();

        return view('admin.visitors', [
            'range' => $range,
            'chart' => $chart,
            'places' => $places,
            'visitors' => $visitors,
            'counts' => [
                'today' => $this->ipCount($today->toDateString()),
                'week' => $this->ipCount($today->copy()->subDays(6)->toDateString()),
                'month' => $this->ipCount($today->copy()->subDays(29)->toDateString()),
                'all' => Visitor::query()->count(),
            ],
        ]);
    }

    private function ipCount(string $day): int
    {
        return (int) Visit::query()
            ->where('visited_on', '>=', $day)
            ->distinct()
            ->count('visitor_id');
    }
}
