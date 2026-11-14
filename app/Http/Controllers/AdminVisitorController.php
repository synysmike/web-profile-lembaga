<?php

namespace App\Http\Controllers;

use App\Models\PageView;
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
                'views' => 0,
            ];
        }

        $visitCounts = Visit::query()
            ->where('visited_on', '>=', $chartStart->toDateString())
            ->selectRaw('visited_on as day, count(*) as total')
            ->groupBy('visited_on')
            ->get()
            ->mapWithKeys(fn ($row) => [substr((string) $row->day, 0, 10) => (int) $row->total]);

        $viewFrom = $chartStart->copy()->timezone('Asia/Jakarta')->startOfDay()->utc();
        $viewCounts = PageView::query()
            ->where('viewed_at', '>=', $viewFrom)
            ->selectRaw('date(date_add(viewed_at, interval 7 hour)) as day, count(*) as total')
            ->groupByRaw('date(date_add(viewed_at, interval 7 hour))')
            ->get()
            ->mapWithKeys(fn ($row) => [substr((string) $row->day, 0, 10) => (int) $row->total]);

        foreach ($chart as $index => $column) {
            $chart[$index]['visits'] = (int) ($visitCounts[$column['date']] ?? 0);
            $chart[$index]['views'] = (int) ($viewCounts[$column['date']] ?? 0);
        }

        $pageQuery = PageView::query();
        $visitorQuery = PageView::query();

        if ($start !== null) {
            $from = Carbon::parse($start, 'Asia/Jakarta')->startOfDay()->utc();
            $pageQuery->where('viewed_at', '>=', $from);
            $visitorQuery->where('viewed_at', '>=', $from);
        }

        $pages = $pageQuery
            ->selectRaw('path, max(title) as title, count(*) as views')
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        $visitorIds = $visitorQuery->distinct()->pluck('visitor_id');
        $places = Visitor::query()
            ->whereIn('id', $visitorIds)
            ->get()
            ->groupBy(fn (Visitor $visitor) => $visitor->placeLabel())
            ->map(fn ($group, $label) => ['label' => $label, 'total' => $group->count()])
            ->sortByDesc('total')
            ->take(10)
            ->values();

        return view('admin.visitors', [
            'range' => $range,
            'chart' => $chart,
            'pages' => $pages,
            'places' => $places,
            'counts' => [
                'today' => Visit::query()->whereDate('visited_on', $today->toDateString())->count(),
                'week' => Visit::query()->where('visited_on', '>=', $today->copy()->subDays(6)->toDateString())->count(),
                'month' => Visit::query()->where('visited_on', '>=', $today->copy()->subDays(29)->toDateString())->count(),
                'all' => Visit::query()->count(),
            ],
            'views' => [
                'today' => $this->viewsSince($today->toDateString()),
                'week' => $this->viewsSince($today->copy()->subDays(6)->toDateString()),
                'month' => $this->viewsSince($today->copy()->subDays(29)->toDateString()),
                'all' => PageView::query()->count(),
            ],
        ]);
    }

    private function viewsSince(string $day): int
    {
        return PageView::query()
            ->where('viewed_at', '>=', Carbon::parse($day, 'Asia/Jakarta')->startOfDay()->utc())
            ->count();
    }
}
