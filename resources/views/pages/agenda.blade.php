@extends('layouts.app')

@section('title', 'Agenda')

@section('content')
    @include('partials.page-heading', [
        'heading' => 'Agenda',
        'summary' => 'Jadwal kegiatan yang paling dekat ditampilkan di atas.',
    ])
    <div class="mx-auto max-w-3xl space-y-4 px-4 py-12">
        @forelse ($agendas as $agenda)
            <article class="rounded-3xl bg-white p-6">
                <p class="text-sm font-semibold" style="color: var(--accent)">{{ \App\Support\LocalDate::long($agenda->happens_on) }}</p>
                <h2 class="mt-2 text-xl font-semibold">{{ $agenda->title }}</h2>
                <p class="mt-1" style="color: var(--muted)">{{ $agenda->place }}</p>
            </article>
        @empty
            <p class="rounded-3xl bg-white p-6">Belum ada agenda.</p>
        @endforelse
    </div>
@endsection
