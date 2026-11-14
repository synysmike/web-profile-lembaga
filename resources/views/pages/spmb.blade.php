@extends('layouts.app')

@section('title', $page->title)

@section('content')
    @include('partials.page-heading', [
        'heading' => $page->title,
        'summary' => $page->summary,
    ])
    <div class="mx-auto max-w-6xl px-4 py-12">
        <div class="grid gap-4 md:grid-cols-2">
            @forelse ($steps as $step)
                <article class="rounded-3xl bg-white p-6">
                    <p class="text-sm font-semibold" style="color: var(--accent)">{{ $step->label }}</p>
                    <h2 class="mt-2 text-xl font-semibold">{{ $step->title }}</h2>
                    <p class="mt-2" style="color: var(--muted)">{{ $step->body }}</p>
                </article>
            @empty
                <p class="rounded-3xl bg-white p-6 md:col-span-2">Belum ada tahap.</p>
            @endforelse
        </div>
        <h2 class="mt-10 text-2xl font-semibold">Jalur</h2>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($paths as $path)
                <article class="rounded-3xl bg-white p-5">
                    <h3 class="text-lg font-semibold">{{ $path->title }}</h3>
                    <p class="mt-2 text-sm" style="color: var(--muted)">{{ $path->body }}</p>
                </article>
            @empty
                <p class="rounded-3xl bg-white p-6 sm:col-span-2 lg:col-span-4">Belum ada jalur.</p>
            @endforelse
        </div>
    </div>
@endsection
