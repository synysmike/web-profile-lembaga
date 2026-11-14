@extends('layouts.app')

@section('title', 'Unduhan')

@section('content')
    @include('partials.page-heading', [
        'heading' => 'Unduhan',
        'summary' => 'Berkas yang disediakan madrasah.',
    ])
    <div class="mx-auto max-w-3xl space-y-4 px-4 py-12">
        @forelse ($downloads as $item)
            <article class="flex items-center justify-between gap-4 rounded-3xl bg-white p-6">
                <div>
                    <h2 class="text-lg font-semibold">{{ $item->title }}</h2>
                    <p class="text-sm" style="color: var(--muted)">{{ $item->fileUrl() ? 'PDF' : 'Berkas belum diunggah' }}</p>
                </div>
                @if ($item->fileUrl())
                    <a href="{{ route('unduhan.file', $item) }}" class="rounded-full px-3 py-1 text-sm font-semibold text-white" style="background: var(--accent)">Unduh</a>
                @else
                    <span class="rounded-full px-3 py-1 text-sm font-semibold text-white" style="background: var(--accent)">Menunggu</span>
                @endif
            </article>
        @empty
            <p class="rounded-3xl bg-white p-6">Belum ada berkas.</p>
        @endforelse
    </div>
@endsection
