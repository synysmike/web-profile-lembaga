@extends('layouts.app')

@section('title', $post->title)

@section('share_description', $post->excerpt)

@section('share_image', $post->coverUrl() ?? '')

@section('content')
    @include('partials.page-heading', [
        'heading' => $post->title,
        'summary' => \App\Support\LocalDate::long($post->published_on).' · '.$schoolName,
    ])
    <article class="mx-auto max-w-3xl space-y-4 px-4 py-12 text-lg leading-relaxed">
        @if ($post->coverUrl())
            <img src="{{ $post->coverUrl() }}" alt="" class="max-h-96 w-full rounded-3xl object-cover">
        @endif
        @foreach ($post->paragraphs() as $paragraph)
            <p>{!! nl2br(e($paragraph)) !!}</p>
        @endforeach
        <p><a href="{{ route('berita') }}" class="font-semibold" style="color: var(--accent)">Kembali ke berita</a></p>
    </article>
@endsection
