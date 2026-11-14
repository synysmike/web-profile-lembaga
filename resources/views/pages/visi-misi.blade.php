@extends('layouts.app')

@section('title', $page->title)

@section('content')
    @include('partials.page-heading', [
        'heading' => $page->title,
        'summary' => $page->summary,
    ])
    <div class="mx-auto grid max-w-6xl gap-4 px-4 py-12 lg:grid-cols-3">
        <article class="rounded-3xl bg-white p-6">
            <h2 class="text-xl font-semibold">Visi</h2>
            <div class="mt-3 space-y-3">
                @forelse ($page->paragraphs() as $paragraph)
                    <p>{!! nl2br(e($paragraph)) !!}</p>
                @empty
                    <p>Belum ada visi.</p>
                @endforelse
            </div>
        </article>
        <article class="rounded-3xl bg-white p-6">
            <h2 class="text-xl font-semibold">Misi</h2>
            @if ($page->missionLines() === [])
                <p class="mt-3">Belum ada misi.</p>
            @else
                <ul class="mt-3 list-disc space-y-2 pl-5">
                    @foreach ($page->missionLines() as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                </ul>
            @endif
        </article>
        <article class="rounded-3xl bg-white p-6">
            <h2 class="text-xl font-semibold">Tujuan</h2>
            @if ($page->goalLines() === [])
                <p class="mt-3">Belum ada tujuan.</p>
            @else
                <ul class="mt-3 list-disc space-y-2 pl-5">
                    @foreach ($page->goalLines() as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                </ul>
            @endif
        </article>
    </div>
@endsection
