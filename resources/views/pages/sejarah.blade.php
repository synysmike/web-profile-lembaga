@extends('layouts.app')

@section('title', $page->title)

@section('content')
    @include('partials.page-heading', [
        'heading' => $page->title,
        'summary' => $page->summary,
    ])
    <div class="mx-auto max-w-3xl space-y-4 px-4 py-12">
        @forelse ($lines as $line)
            <article class="rounded-3xl bg-white p-6">
                <p class="text-sm font-semibold" style="color: var(--accent)">{{ $line->label }}</p>
                <p class="mt-2 text-lg">{{ $line->body }}</p>
            </article>
        @empty
            <p class="rounded-3xl bg-white p-6">Belum ada jejak sejarah.</p>
        @endforelse
    </div>
@endsection
