@extends('layouts.admin')

@section('title', 'Berita')

@section('summary', 'Judul, slug, tanggal, ringkasan, isi, dan sampul. Yang disembunyikan tidak tampil di situs.')

@section('actions')
    <a href="{{ route('admin.posts.create') }}" class="inline-flex rounded-full bg-amber-400 px-4 py-2 text-sm font-semibold text-amber-950">Tulis berita</a>
@endsection

@section('content')
    @include('partials.admin-alerts')

    <div class="overflow-hidden rounded-2xl bg-white">
        @forelse ($posts as $post)
            <article class="flex flex-wrap items-center justify-between gap-4 border-b border-black/5 px-5 py-4 last:border-b-0">
                @if ($post->coverUrl())
                    <img src="{{ $post->coverUrl() }}" alt="" class="h-14 w-20 rounded-xl object-cover">
                @endif
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-lg font-semibold">{{ $post->title }}</h2>
                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold" @if ($post->is_visible) style="background: color-mix(in srgb, var(--accent) 14%, white); color: var(--accent)" @else style="background: #f1f5f9; color: var(--muted)" @endif>{{ $post->is_visible ? 'Tampil' : 'Disembunyikan' }}</span>
                    </div>
                    <p class="mt-1 text-sm" style="color: var(--muted)">{{ \App\Support\LocalDate::long($post->published_on) }} · /berita/{{ $post->slug }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.posts.edit', $post) }}" class="rounded-full px-4 py-2 text-sm font-semibold text-white" style="background: var(--accent)">Sunting</a>
                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Hapus berita ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-full border border-black/10 px-4 py-2 text-sm font-semibold">Hapus</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="px-5 py-8" style="color: var(--muted)">Belum ada berita.</p>
        @endforelse
    </div>
@endsection
