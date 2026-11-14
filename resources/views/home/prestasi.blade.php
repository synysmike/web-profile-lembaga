<section class="mx-auto max-w-6xl px-4 py-12">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold">{{ $section->title }}</h2>
            <p class="mt-2 max-w-2xl" style="color: var(--muted)">{{ $section->excerpt }}</p>
        </div>
        <a href="{{ route('prestasi') }}" class="shrink-0 text-sm font-semibold" style="color: var(--accent)">Lihat semua</a>
    </div>
    <div class="mt-6 grid gap-4 md:grid-cols-3">
        @forelse ($homeAchievements as $item)
            <article class="rounded-3xl border border-black/5 bg-white p-6">
                <p class="text-sm font-semibold uppercase tracking-wide" style="color: var(--accent)">{{ $item->rank }}</p>
                <h3 class="mt-2 text-lg font-semibold">{{ $item->title }}</h3>
            </article>
        @empty
            <p class="rounded-3xl border border-black/5 bg-white p-6 md:col-span-3">Belum ada prestasi.</p>
        @endforelse
    </div>
</section>
