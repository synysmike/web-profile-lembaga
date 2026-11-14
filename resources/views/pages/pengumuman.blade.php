@extends('layouts.app')

@section('title', 'Pengumuman')

@section('content')
    @include('partials.page-heading', [
        'heading' => 'Pengumuman',
        'summary' => 'Informasi singkat untuk siswa dan orang tua.',
    ])
    <div class="mx-auto max-w-3xl space-y-4 px-4 py-12">
        @forelse ($announcements as $item)
            <article class="rounded-3xl bg-white p-6">
                <h2 class="text-xl font-semibold">{{ $item->title }}</h2>
                <p class="mt-2 whitespace-pre-line" style="color: var(--muted)">{{ $item->body }}</p>
            </article>
        @empty
            <p class="rounded-3xl bg-white p-6">Belum ada pengumuman.</p>
        @endforelse
    </div>
@endsection
