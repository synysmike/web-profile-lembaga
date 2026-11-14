@extends('layouts.admin')

@section('title', 'Pengunjung')

@section('summary', 'Kunjungan, halaman yang dibuka, dan lokasi perkiraan. Halaman admin tidak dihitung.')

@section('content')
    <div class="space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['Hari ini', $counts['today'], $views['today']],
                ['7 hari', $counts['week'], $views['week']],
                ['30 hari', $counts['month'], $views['month']],
                ['Seluruhnya', $counts['all'], $views['all']],
            ] as [$label, $visits, $pageViews])
                <article class="rounded-3xl bg-white p-5">
                    <p class="text-sm font-semibold" style="color: var(--muted)">{{ $label }}</p>
                    <p class="mt-2 text-3xl font-semibold">{{ $visits }}</p>
                    <p class="text-sm" style="color: var(--muted)">kunjungan · {{ $pageViews }} halaman</p>
                </article>
            @endforeach
        </div>

        <section class="rounded-3xl bg-white p-6">
            <h2 class="text-xl font-semibold">14 hari terakhir</h2>
            <div class="mt-3 flex gap-4 text-sm" style="color: var(--muted)">
                <span class="inline-flex items-center gap-2"><span class="inline-block h-3 w-3 rounded-full" style="background: var(--accent)"></span> Kunjungan</span>
                <span class="inline-flex items-center gap-2"><span class="inline-block h-3 w-3 rounded-full" style="background: var(--ink)"></span> Halaman</span>
            </div>
            @php $max = max(1, collect($chart)->max(fn ($day) => max($day['visits'], $day['views']))); @endphp
            <svg viewBox="0 0 840 230" class="mt-4 w-full" role="img" aria-label="Grafik kunjungan 14 hari">
                <title>Grafik kunjungan 14 hari</title>
                <line x1="20" y1="180" x2="820" y2="180" stroke="currentColor" stroke-opacity="0.15"></line>
                @foreach ($chart as $index => $day)
                    @php
                        $x = 28 + ($index * 58);
                        $visitHeight = (int) round(($day['visits'] / $max) * 150);
                        $viewHeight = (int) round(($day['views'] / $max) * 150);
                    @endphp
                    <rect x="{{ $x }}" y="{{ 180 - $visitHeight }}" width="18" height="{{ $visitHeight }}" rx="4" style="fill: var(--accent)">
                        <title>{{ $day['label'] }}: {{ $day['visits'] }} kunjungan</title>
                    </rect>
                    <rect x="{{ $x + 22 }}" y="{{ 180 - $viewHeight }}" width="18" height="{{ $viewHeight }}" rx="4" style="fill: var(--ink)">
                        <title>{{ $day['label'] }}: {{ $day['views'] }} halaman</title>
                    </rect>
                    <text x="{{ $x + 20 }}" y="202" text-anchor="middle" font-size="11" style="fill: var(--muted)">{{ $day['label'] }}</text>
                @endforeach
            </svg>
        </section>

        <div class="flex flex-wrap gap-2">
            @foreach (['7' => '7 hari', '30' => '30 hari', 'semua' => 'Seluruhnya'] as $value => $label)
                <a href="{{ route('admin.visitors', ['rentang' => $value]) }}" class="rounded-full px-4 py-2 text-sm font-semibold" @if ($range === $value) style="background: var(--accent); color: white" @else style="background: white; color: var(--ink)" @endif>{{ $label }}</a>
            @endforeach
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <section class="rounded-3xl bg-white p-6">
                <h2 class="text-xl font-semibold">Halaman yang dibuka</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($pages as $page)
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold">{{ $page->title }}</p>
                                <p class="text-sm" style="color: var(--muted)">{{ $page->path }}</p>
                            </div>
                            <p class="font-semibold">{{ $page->views }}</p>
                        </div>
                    @empty
                        <p style="color: var(--muted)">Belum ada halaman pada rentang ini.</p>
                    @endforelse
                </div>
            </section>
            <section class="rounded-3xl bg-white p-6">
                <h2 class="text-xl font-semibold">Lokasi</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($places as $place)
                        <div class="flex items-center justify-between gap-3">
                            <p>{{ $place['label'] }}</p>
                            <p class="font-semibold">{{ $place['total'] }}</p>
                        </div>
                    @empty
                        <p style="color: var(--muted)">Belum ada lokasi pada rentang ini.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection
