@extends('layouts.app')

@section('title', $page->title)

@section('content')
    @include('partials.page-heading', [
        'heading' => $page->title,
        'summary' => $page->summary,
    ])
    <article class="mx-auto max-w-3xl space-y-4 px-4 py-12 text-lg leading-relaxed">
        @forelse ($page->paragraphs() as $paragraph)
            <p>{!! nl2br(e($paragraph)) !!}</p>
        @empty
            <p>Sambutan belum diisi.</p>
        @endforelse
    </article>
@endsection
