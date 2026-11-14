<section class="mx-auto max-w-6xl px-4 py-12">
    <h2 class="text-2xl font-semibold">{{ $section->title }}</h2>
    <p class="mt-2 max-w-2xl" style="color: var(--muted)">{{ $section->excerpt }}</p>
    <div class="mt-6 grid gap-4 md:grid-cols-3">
        @foreach ([
            ['Siswa', 'Berita dan materi yang perlu dibaca.', 'berita'],
            ['Orang tua', 'Pengumuman dan agenda sekolah.', 'pengumuman'],
            ['Calon siswa', 'Informasi penerimaan murid baru.', 'spmb'],
        ] as [$group, $text, $routeName])
            <a href="{{ route($routeName) }}" class="rounded-3xl border border-black/5 bg-white p-6">
                <p class="text-sm font-semibold uppercase tracking-wide" style="color: var(--accent)">Untuk</p>
                <h3 class="mt-2 text-xl font-semibold">{{ $group }}</h3>
                <p class="mt-2" style="color: var(--muted)">{{ $text }}</p>
            </a>
        @endforeach
    </div>
</section>
