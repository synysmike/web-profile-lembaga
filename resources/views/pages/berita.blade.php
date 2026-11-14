@extends('layouts.app')

@section('title', $keyword !== '' ? 'Hasil pencarian' : 'Berita')

@section('content')
    @include('partials.page-heading', [
        'heading' => $keyword !== '' ? 'Hasil pencarian' : 'Berita',
        'summary' => $keyword !== ''
            ? 'Berita, agenda, dan pengumuman yang cocok dengan kata kunci.'
            : 'Kabar terbaru untuk siswa dan orang tua.',
    ])
    <div class="mx-auto max-w-3xl space-y-8 px-4 py-12">
        @if ($keyword !== '')
            <p class="text-sm" style="color: var(--muted)">Hasil untuk “{{ $keyword }}”.</p>

            <section class="space-y-4">
                <h2 class="text-xl font-semibold">Berita</h2>
                @forelse ($posts as $post)
                    <a href="{{ route('berita.show', $post->slug) }}" class="block rounded-3xl bg-white p-6">
                        @if ($post->coverUrl())
                            <img src="{{ $post->coverUrl() }}" alt="" class="mb-4 h-44 w-full rounded-2xl object-cover">
                        @endif
                        <p class="text-sm" style="color: var(--muted)">{{ \App\Support\LocalDate::long($post->published_on) }}</p>
                        <h3 class="mt-2 text-2xl font-semibold">{{ $post->title }}</h3>
                        <p class="mt-2" style="color: var(--muted)">{{ $post->excerpt }}</p>
                    </a>
                @empty
                    <p class="rounded-3xl bg-white p-6">Tidak ada berita yang cocok.</p>
                @endforelse
            </section>

            <section class="space-y-4">
                <h2 class="text-xl font-semibold">Agenda</h2>
                @forelse ($agendas as $agenda)
                    <a href="{{ route('agenda') }}" class="block rounded-3xl bg-white p-6">
                        <p class="text-sm font-semibold" style="color: var(--accent)">{{ \App\Support\LocalDate::long($agenda->happens_on) }}</p>
                        <h3 class="mt-2 text-xl font-semibold">{{ $agenda->title }}</h3>
                        <p class="mt-1" style="color: var(--muted)">{{ $agenda->place }}</p>
                    </a>
                @empty
                    <p class="rounded-3xl bg-white p-6">Tidak ada agenda yang cocok.</p>
                @endforelse
            </section>

            <section class="space-y-4">
                <h2 class="text-xl font-semibold">Pengumuman</h2>
                @forelse ($announcements as $item)
                    <a href="{{ route('pengumuman') }}" class="block rounded-3xl bg-white p-6">
                        <h3 class="text-xl font-semibold">{{ $item->title }}</h3>
                        <p class="mt-2 line-clamp-3 whitespace-pre-line" style="color: var(--muted)">{{ $item->body }}</p>
                    </a>
                @empty
                    <p class="rounded-3xl bg-white p-6">Tidak ada pengumuman yang cocok.</p>
                @endforelse
            </section>
        @else
            @forelse ($posts as $post)
                <a href="{{ route('berita.show', $post->slug) }}" class="block rounded-3xl bg-white p-6">
                    @if ($post->coverUrl())
                        <img src="{{ $post->coverUrl() }}" alt="" class="mb-4 h-44 w-full rounded-2xl object-cover">
                    @endif
                    <p class="text-sm" style="color: var(--muted)">{{ \App\Support\LocalDate::long($post->published_on) }}</p>
                    <h2 class="mt-2 text-2xl font-semibold">{{ $post->title }}</h2>
                    <p class="mt-2" style="color: var(--muted)">{{ $post->excerpt }}</p>
                </a>
            @empty
                <p class="rounded-3xl bg-white p-6">Belum ada berita.</p>
            @endforelse
        @endif
    </div>
@endsection
