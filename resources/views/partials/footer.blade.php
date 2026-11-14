<footer class="mt-8" style="background: var(--footer); color: white">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 py-12 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            @if ($school['logo'])
                <img src="{{ $school['logo'] }}" alt="" class="mb-3 h-12 w-12 rounded-2xl bg-white object-contain p-1">
            @endif
            <p class="text-lg font-semibold">{{ $school['name'] }}</p>
            @if ($school['tagline'])
                <p class="mt-2 text-sm text-white/80">{{ $school['tagline'] }}</p>
            @endif
            <div class="mt-4 space-y-1 text-sm text-white/80">
                @if ($school['address'])
                    <p>{{ $school['address'] }}</p>
                @endif
                @if ($school['phone'])
                    <p><a href="tel:{{ preg_replace('/[^\d+]/', '', $school['phone']) }}">{{ $school['phone'] }}</a></p>
                @endif
                @if ($school['email'])
                    <p><a href="mailto:{{ $school['email'] }}">{{ $school['email'] }}</a></p>
                @endif
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-white/70">Profil</p>
            <div class="mt-3 flex flex-col gap-2 text-sm">
                <a href="{{ route('profil.sejarah') }}">Sejarah</a>
                <a href="{{ route('profil.struktur') }}">Struktur organisasi</a>
                <a href="{{ route('profil.visi') }}">Visi dan misi</a>
                <a href="{{ route('profil.sambutan') }}">Sambutan kepala</a>
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-white/70">Informasi</p>
            <div class="mt-3 flex flex-col gap-2 text-sm">
                <a href="{{ route('berita') }}">Berita</a>
                <a href="{{ route('agenda') }}">Agenda</a>
                <a href="{{ route('pengumuman') }}">Pengumuman</a>
                <a href="{{ route('galeri') }}">Galeri</a>
                <a href="{{ route('kontak') }}">Kontak</a>
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-white/70">SPMB</p>
            <p class="mt-3 text-sm text-white/80">Penerimaan murid baru tahun ajaran 2026/2027.</p>
            <a href="{{ route('spmb') }}" class="mt-4 inline-flex rounded-full bg-white px-4 py-2 text-sm font-semibold" style="color: var(--footer)">Daftar SPMB</a>
        </div>
    </div>
    <div class="border-t border-white/15 px-4 py-4 text-center text-sm text-white/70">
        <p>Pengunjung hari ini {{ $visitorToday ?? 0 }} · Total {{ $visitorTotal ?? 0 }}</p>
        <p class="mt-1">© {{ date('Y') }} {{ $school['name'] }}</p>
    </div>
</footer>
