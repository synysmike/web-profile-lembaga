<section class="mx-auto max-w-6xl px-4 py-12">
    <h2 class="text-2xl font-semibold">{{ $section->title }}</h2>
    <p class="mt-2 max-w-2xl" style="color: var(--muted)">{{ $section->excerpt }}</p>
    <form action="{{ route('berita') }}" method="GET" class="mt-6 flex flex-col gap-3 sm:flex-row">
        <label class="sr-only" for="q">Kata kunci</label>
        <input id="q" name="q" type="search" placeholder="Cari berita, agenda, atau pengumuman" class="w-full rounded-full border border-black/10 bg-white px-5 py-3">
        <button type="submit" class="rounded-full px-6 py-3 font-semibold text-white" style="background: var(--accent)">Cari</button>
    </form>
    <div class="mt-5 flex flex-wrap gap-2 text-sm">
        <a class="rounded-full bg-white px-4 py-2 font-medium" href="{{ route('spmb') }}">SPMB</a>
        <a class="rounded-full bg-white px-4 py-2 font-medium" href="{{ route('prestasi') }}">Prestasi</a>
        <a class="rounded-full bg-white px-4 py-2 font-medium" href="{{ route('agenda') }}">Agenda</a>
        <a class="rounded-full bg-white px-4 py-2 font-medium" href="{{ route('unduhan') }}">Unduhan</a>
    </div>
</section>
