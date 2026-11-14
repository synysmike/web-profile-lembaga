@extends('layouts.app')

@section('title', $post->title)

@section('share_title', $post->title)

@php
    $shareSnippet = trim((string) $post->excerpt);
    if ($shareSnippet === '') {
        $shareSnippet = trim(preg_replace('/\s+/', ' ', strip_tags((string) $post->body)) ?? '');
    }
@endphp

@section('share_description', $shareSnippet)

@section('share_image', $post->coverUrl() ?? '')

@section('canonical', route('berita.show', $post->slug))

@section('og_type', 'article')

@section('published_time', $post->published_on->toAtomString())

@php
    $articleImage = $post->coverUrl() ?: ($school['logo'] ?? null);
    $article = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $post->title,
        'description' => $post->excerpt,
        'datePublished' => $post->published_on->toDateString(),
        'dateModified' => $post->updated_at?->toAtomString(),
        'mainEntityOfPage' => route('berita.show', $post->slug),
        'image' => $articleImage ? [$articleImage] : null,
        'author' => [
            '@type' => 'Organization',
            'name' => $schoolName,
        ],
        'publisher' => array_filter([
            '@type' => 'Organization',
            'name' => $schoolName,
            'logo' => $school['logo'] ? [
                '@type' => 'ImageObject',
                'url' => $school['logo'],
            ] : null,
        ]),
    ]);
@endphp

@push('head')
    <script type="application/ld+json">{!! json_encode($article, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
@endpush

@section('content')
    @include('partials.page-heading', [
        'heading' => $post->title,
        'summary' => \App\Support\LocalDate::long($post->published_on).' · '.$schoolName,
    ])
    <article class="mx-auto max-w-3xl space-y-4 px-4 py-12 text-lg leading-relaxed">
        @if ($post->coverUrl())
            <img src="{{ $post->coverUrl() }}" alt="{{ $post->title }}" class="max-h-96 w-full rounded-3xl object-cover">
        @endif
        @foreach ($post->paragraphs() as $paragraph)
            <p>{!! nl2br(e($paragraph)) !!}</p>
        @endforeach
        <p><a href="{{ route('berita') }}" class="font-semibold" style="color: var(--accent)">Kembali ke berita</a></p>
    </article>
@endsection
