<section class="mx-auto max-w-6xl px-4 py-12">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold">{{ $section->title }}</h2>
            <p class="mt-2 max-w-2xl" style="color: var(--muted)">{{ $section->excerpt }}</p>
        </div>
        <a href="{{ route('berita') }}" class="shrink-0 text-sm font-semibold" style="color: var(--accent)">Lihat semua</a>
    </div>
    <div class="mt-6 grid gap-4 md:grid-cols-2">
        @forelse ($homePosts as $post)
            <a href="{{ route('berita.show', $post->slug) }}" class="overflow-hidden rounded-3xl border border-black/5 bg-white">
                @if ($post->coverUrl())
                    <img src="{{ $post->coverUrl() }}" alt="" class="h-44 w-full object-cover">
                @endif
                <div class="p-6">
                <p class="text-sm" style="color: var(--muted)">{{ \App\Support\LocalDate::long($post->published_on) }}</p>
                <h3 class="mt-2 text-xl font-semibold">{{ $post->title }}</h3>
                <p class="mt-2" style="color: var(--muted)">{{ $post->excerpt }}</p>
                </div>
            </a>
        @empty
            <p class="rounded-3xl border border-black/5 bg-white p-6 md:col-span-2">Belum ada berita.</p>
        @endforelse
    </div>
</section>
