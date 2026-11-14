@extends('layouts.app')

@section('title', 'Galeri')

@section('content')
    @include('partials.page-heading', [
        'heading' => 'Galeri kegiatan',
        'summary' => 'Momen belajar, ibadah, dan kegiatan di madrasah.',
    ])
    <div class="mx-auto grid max-w-6xl gap-4 px-4 py-12 sm:grid-cols-3">
        @forelse ($items as $item)
            @if ($item->imageUrl())
                <article class="relative flex h-56 items-end overflow-hidden rounded-3xl">
                    <img src="{{ $item->imageUrl() }}" alt="" class="absolute inset-0 h-full w-full object-cover">
                    <span class="relative w-full bg-gradient-to-t from-black/70 to-transparent p-5 text-xl font-semibold text-white">{{ $item->title }}</span>
                </article>
            @else
                <article class="flex h-56 items-end rounded-3xl p-5 text-xl font-semibold" style="background: var(--hero); color: var(--hero-text)">{{ $item->title }}</article>
            @endif
        @empty
            <p class="rounded-3xl bg-white p-6 sm:col-span-3">Belum ada foto.</p>
        @endforelse
    </div>
@endsection
