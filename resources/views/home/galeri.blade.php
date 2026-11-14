<section class="mx-auto max-w-6xl px-4 py-12">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold">{{ $section->title }}</h2>
            <p class="mt-2 max-w-2xl" style="color: var(--muted)">{{ $section->excerpt }}</p>
        </div>
        <a href="{{ route('galeri') }}" class="shrink-0 text-sm font-semibold" style="color: var(--accent)">Lihat semua</a>
    </div>
    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        @forelse ($homeGallery as $item)
            @if ($item->imageUrl())
                <a href="{{ route('galeri') }}" class="relative flex h-44 items-end overflow-hidden rounded-3xl">
                    <img src="{{ $item->imageUrl() }}" alt="" class="absolute inset-0 h-full w-full object-cover">
                    <span class="relative w-full bg-gradient-to-t from-black/70 to-transparent p-5 font-semibold text-white">{{ $item->title }}</span>
                </a>
            @else
                <a href="{{ route('galeri') }}" class="flex h-44 items-end rounded-3xl p-5 font-semibold" style="background: var(--hero); color: var(--hero-text)">{{ $item->title }}</a>
            @endif
        @empty
            <p class="rounded-3xl bg-white p-6 sm:col-span-3">Belum ada foto.</p>
        @endforelse
    </div>
</section>
