<section class="mx-auto grid max-w-6xl items-center gap-8 px-4 py-12 md:grid-cols-[1.4fr_0.8fr]">
    <div>
        <h2 class="text-2xl font-semibold">{{ $section->title }}</h2>
        <p class="mt-4 text-lg leading-relaxed">{{ $section->excerpt }}</p>
        <a href="{{ route('profil.sambutan') }}" class="mt-6 inline-flex rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--accent)">Baca sambutan</a>
    </div>
    <div class="rounded-3xl bg-white p-6">
        <p class="text-sm font-semibold uppercase tracking-wide" style="color: var(--accent)">Kepala madrasah</p>
        <p class="mt-3 text-2xl font-semibold">{{ $section->subtitle }}</p>
    </div>
</section>
