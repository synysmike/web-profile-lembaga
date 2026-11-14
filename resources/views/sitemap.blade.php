{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
    </url>
    <url>
        <loc>{{ route('berita') }}</loc>
    </url>
    @foreach ($posts as $post)
        <url>
            <loc>{{ route('berita.show', $post->slug) }}</loc>
            <lastmod>{{ $post->published_on->toDateString() }}</lastmod>
        </url>
    @endforeach
</urlset>
