@extends('layouts.app')

@section('title', $page->title)

@section('content')
    @include('partials.page-heading', [
        'heading' => $page->title,
        'summary' => $page->summary,
    ])
    <div class="mx-auto grid max-w-4xl gap-4 px-4 py-12 sm:grid-cols-3">
        @forelse ($lines as $line)
            <article class="rounded-3xl bg-white p-6 text-center">
                <p class="text-sm font-semibold uppercase tracking-wide" style="color: var(--accent)">{{ $line->label }}</p>
                <h2 class="mt-3 text-xl font-semibold">{{ $line->title }}</h2>
            </article>
        @empty
            <p class="rounded-3xl bg-white p-6 sm:col-span-3">Belum ada susunan pengurus.</p>
        @endforelse
    </div>
@endsection
