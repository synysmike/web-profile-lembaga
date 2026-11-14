@extends('layouts.admin')

@section('title', 'Pengunjung')

@section('summary', 'Jumlah alamat IP yang datang. Satu IP dihitung sekali, bukan setiap halaman yang dibuka.')

@section('content')
    <div class="space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['Hari ini', $counts['today']],
                ['7 hari', $counts['week']],
                ['30 hari', $counts['month']],
                ['Seluruhnya', $counts['all']],
            ] as [$label, $total])
                <article class="rounded-3xl bg-white p-5">
                    <p class="text-sm font-semibold" style="color: var(--muted)">{{ $label }}</p>
                    <p class="mt-2 text-3xl font-semibold">{{ $total }}</p>
                    <p class="text-sm" style="color: var(--muted)">alamat IP</p>
                </article>
            @endforeach
        </div>

        <section class="rounded-3xl bg-white p-6">
            <h2 class="text-xl font-semibold">14 hari terakhir</h2>
            <p class="mt-1 text-sm" style="color: var(--muted)">Satu batang adalah jumlah IP pada hari itu.</p>
            @php $max = max(1, collect($chart)->max('visits')); @endphp
            <svg viewBox="0 0 840 230" class="mt-4 w-full" role="img" aria-label="Grafik IP pengunjung 14 hari">
                <title>Grafik IP pengunjung 14 hari</title>
                <line x1="20" y1="180" x2="820" y2="180" stroke="currentColor" stroke-opacity="0.15"></line>
                @foreach ($chart as $index => $day)
                    @php
                        $x = 36 + ($index * 58);
                        $visitHeight = (int) round(($day['visits'] / $max) * 150);
                    @endphp
                    <rect x="{{ $x }}" y="{{ 180 - $visitHeight }}" width="22" height="{{ $visitHeight }}" rx="4" style="fill: var(--accent)">
                        <title>{{ $day['label'] }}: {{ $day['visits'] }} IP</title>
                    </rect>
                    <text x="{{ $x + 11 }}" y="202" text-anchor="middle" font-size="11" style="fill: var(--muted)">{{ $day['label'] }}</text>
                @endforeach
            </svg>
        </section>

        <div class="flex flex-wrap gap-2">
            @foreach (['7' => '7 hari', '30' => '30 hari', 'semua' => 'Seluruhnya'] as $value => $label)
                <a href="{{ route('admin.visitors', ['rentang' => $value]) }}" class="rounded-full px-4 py-2 text-sm font-semibold" @if ($range === $value) style="background: var(--accent); color: white" @else style="background: white; color: var(--ink)" @endif>{{ $label }}</a>
            @endforeach
        </div>

        <section class="overflow-hidden rounded-3xl bg-white">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-black/5 px-4 py-3">
                <h2 class="text-xl font-semibold">Daftar pengunjung</h2>
                <input id="visitor-search" type="search" placeholder="Cari IP, lokasi, atau halaman" class="w-full max-w-xs rounded-full border border-black/10 px-4 py-2 text-sm">
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[40rem] text-left text-sm">
                    <thead>
                        <tr class="border-b border-black/5 text-xs uppercase tracking-wide" style="color: var(--muted)">
                            <th class="px-4 py-3 font-semibold">IP</th>
                            <th class="px-4 py-3 font-semibold">Lokasi</th>
                            <th class="px-4 py-3 font-semibold">Halaman pertama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($visitors as $visitor)
                            @php
                                $page = trim((string) $visitor->first_title);
                                $path = trim((string) $visitor->first_path);
                                $search = \Illuminate\Support\Str::lower(trim(($visitor->ip ?: '').' '.$visitor->placeLabel().' '.$page.' '.$path));
                            @endphp
                            <tr data-visitor data-search="{{ $search }}" class="border-b border-black/5 last:border-b-0">
                                <td class="px-4 py-3 font-medium">{{ $visitor->ip ?: 'Belum tercatat' }}</td>
                                <td class="px-4 py-3">{{ $visitor->placeLabel() }}</td>
                                <td class="px-4 py-3">
                                    @if ($page !== '' || $path !== '')
                                        <p class="font-semibold">{{ $page !== '' ? $page : 'Halaman' }}</p>
                                        @if ($path !== '')
                                            <p style="color: var(--muted)">{{ $path }}</p>
                                        @endif
                                    @else
                                        <span style="color: var(--muted)">Belum tercatat</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8" style="color: var(--muted)">Belum ada pengunjung pada rentang ini.</td>
                            </tr>
                        @endforelse
                        <tr id="visitor-empty" hidden>
                            <td colspan="3" class="px-4 py-8" style="color: var(--muted)">Tidak ada pengunjung yang cocok.</td>
                        </tr>
                    </tbody>
                </table>
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
    <script>
        (() => {
            const search = document.getElementById('visitor-search');
            const rows = [...document.querySelectorAll('[data-visitor]')];
            const empty = document.getElementById('visitor-empty');
            search.addEventListener('input', () => {
                const query = search.value.trim().toLowerCase();
                let shown = 0;
                rows.forEach((row) => {
                    const match = row.dataset.search.includes(query);
                    row.hidden = !match;
                    if (match) shown += 1;
                });
                empty.hidden = shown !== 0 || rows.length === 0;
            });
        })();
    </script>
@endsection
