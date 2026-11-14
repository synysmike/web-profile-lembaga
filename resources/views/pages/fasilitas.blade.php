@extends('layouts.app')

@section('title', 'Fasilitas')

@section('content')
    @include('partials.page-heading', [
        'heading' => 'Fasilitas',
        'summary' => 'Ruang yang dipakai siswa setiap minggu.',
    ])
    <div class="mx-auto grid max-w-6xl gap-4 px-4 py-12 sm:grid-cols-2">
        @forelse ($facilities as $item)
            <article class="rounded-3xl bg-white p-6">
                <h2 class="text-xl font-semibold">{{ $item->title }}</h2>
                <p class="mt-2 whitespace-pre-line" style="color: var(--muted)">{{ $item->description }}</p>
            </article>
        @empty
            <p class="rounded-3xl bg-white p-6 sm:col-span-2">Belum ada fasilitas.</p>
        @endforelse
    </div>
@endsection
