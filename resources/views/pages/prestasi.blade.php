@extends('layouts.app')

@section('title', 'Prestasi')

@section('content')
    @include('partials.page-heading', [
        'heading' => 'Prestasi siswa',
        'summary' => 'Hasil lomba siswa madrasah.',
    ])
    <div class="mx-auto grid max-w-6xl gap-4 px-4 py-12 md:grid-cols-3">
        @forelse ($achievements as $item)
            <article class="rounded-3xl bg-white p-6">
                <p class="text-sm font-semibold uppercase tracking-wide" style="color: var(--accent)">{{ $item->rank }}</p>
                <h2 class="mt-2 text-xl font-semibold">{{ $item->title }}</h2>
            </article>
        @empty
            <p class="rounded-3xl bg-white p-6 md:col-span-3">Belum ada prestasi.</p>
        @endforelse
    </div>
@endsection
