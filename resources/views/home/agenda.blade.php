<section class="mx-auto max-w-6xl px-4 py-12">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold">{{ $section->title }}</h2>
            <p class="mt-2 max-w-2xl" style="color: var(--muted)">{{ $section->excerpt }}</p>
        </div>
        <a href="{{ route('agenda') }}" class="shrink-0 text-sm font-semibold" style="color: var(--accent)">Lihat semua</a>
    </div>
    <div class="mt-6 grid gap-4 md:grid-cols-2">
        @forelse ($homeAgendas as $agenda)
            <article class="rounded-3xl bg-white p-6">
                <p class="text-sm font-semibold" style="color: var(--accent)">{{ \App\Support\LocalDate::long($agenda->happens_on) }}</p>
                <h3 class="mt-2 text-xl font-semibold">{{ $agenda->title }}</h3>
            </article>
        @empty
            <p class="rounded-3xl bg-white p-6 md:col-span-2">Belum ada agenda.</p>
        @endforelse
    </div>
</section>
