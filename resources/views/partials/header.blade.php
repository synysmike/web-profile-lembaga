@php
    $item = 'block rounded-full px-3 py-2 text-sm font-medium';
    $caret = '<svg class="nav-caret" width="12" height="12" viewBox="0 0 12 12" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 4.5 6 8l3.5-3.5"/></svg>';
@endphp

<header class="sticky top-0 z-20 border-b border-black/5 bg-white/95 backdrop-blur">
    <div class="mx-auto flex max-w-6xl flex-wrap items-center gap-3 px-4 py-3">
        <a href="{{ route('home') }}" class="mr-auto inline-flex items-center gap-3 text-lg font-semibold" style="color: var(--accent)">
            @if ($school['logo'])
                <img src="{{ $school['logo'] }}" alt="" class="h-10 w-10 rounded-xl bg-white object-contain">
            @endif
            <span>{{ $school['name'] }}</span>
        </a>

        <input id="nav-toggle" type="checkbox" class="peer sr-only">
        <label for="nav-toggle" class="cursor-pointer rounded-full border px-3 py-2 text-sm font-medium md:hidden" style="border-color: var(--accent); color: var(--accent)">Menu</label>

        <a href="{{ route('spmb') }}" class="rounded-full bg-amber-400 px-3 py-2 text-sm font-semibold text-amber-950 ring-2 ring-amber-200 md:hidden">SPMB</a>

        <nav class="hidden w-full flex-col gap-1 peer-checked:flex md:flex md:w-auto md:flex-row md:items-center md:gap-1">
            <a href="{{ route('home') }}" class="{{ $item }}" @if (request()->routeIs('home')) style="background: var(--accent); color: white" @endif>Beranda</a>
            <a href="{{ route('berita') }}" class="{{ $item }}" @if (request()->routeIs('berita', 'berita.show')) style="background: var(--accent); color: white" @endif>Berita</a>

            <details class="nav-menu relative">
                <summary class="inline-flex cursor-pointer items-center gap-1 rounded-full px-3 py-2 text-sm font-medium" @if (request()->routeIs('profil.*')) style="background: var(--accent); color: white" @endif>Profil {!! $caret !!}</summary>
                <div class="nav-panel mt-1 flex flex-col rounded-2xl border border-black/5 bg-white p-2 shadow-lg shadow-slate-900/5 md:absolute md:min-w-52">
                    <a class="rounded-xl px-3 py-2 text-sm hover:bg-slate-50" href="{{ route('profil.sambutan') }}">Sambutan</a>
                    <a class="rounded-xl px-3 py-2 text-sm hover:bg-slate-50" href="{{ route('profil.sejarah') }}">Sejarah</a>
                    <a class="rounded-xl px-3 py-2 text-sm hover:bg-slate-50" href="{{ route('profil.struktur') }}">Struktur</a>
                    <a class="rounded-xl px-3 py-2 text-sm hover:bg-slate-50" href="{{ route('profil.visi') }}">Visi dan misi</a>
                </div>
            </details>

            <details class="nav-menu relative">
                <summary class="inline-flex cursor-pointer items-center gap-1 rounded-full px-3 py-2 text-sm font-medium" @if (request()->routeIs('agenda', 'unduhan', 'prestasi', 'pengumuman')) style="background: var(--accent); color: white" @endif>Informasi {!! $caret !!}</summary>
                <div class="nav-panel mt-1 flex flex-col rounded-2xl border border-black/5 bg-white p-2 shadow-lg shadow-slate-900/5 md:absolute md:min-w-52">
                    <a class="rounded-xl px-3 py-2 text-sm hover:bg-slate-50" href="{{ route('agenda') }}">Agenda</a>
                    <a class="rounded-xl px-3 py-2 text-sm hover:bg-slate-50" href="{{ route('unduhan') }}">Unduhan</a>
                    <a class="rounded-xl px-3 py-2 text-sm hover:bg-slate-50" href="{{ route('prestasi') }}">Prestasi</a>
                    <a class="rounded-xl px-3 py-2 text-sm hover:bg-slate-50" href="{{ route('pengumuman') }}">Pengumuman</a>
                </div>
            </details>

            <a href="{{ route('fasilitas') }}" class="{{ $item }}" @if (request()->routeIs('fasilitas')) style="background: var(--accent); color: white" @endif>Fasilitas</a>
            <a href="{{ route('kontak') }}" class="{{ $item }}" @if (request()->routeIs('kontak')) style="background: var(--accent); color: white" @endif>Kontak</a>

            <a href="{{ route('spmb') }}" class="mt-1 hidden items-center justify-center rounded-full bg-amber-400 px-4 py-2 text-sm font-semibold text-amber-950 ring-2 ring-amber-200 md:inline-flex">SPMB</a>

            @auth
                <a href="{{ route('admin.home') }}" class="{{ $item }}" @if (request()->routeIs('admin.*')) style="background: var(--accent); color: white" @endif>Panel</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="{{ $item }} w-full text-left">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="{{ $item }}" @if (request()->routeIs('login')) style="background: var(--accent); color: white" @endif>Login</a>
            @endauth
        </nav>
    </div>
</header>
<script>
    const siteHeader = document.querySelector('header');
    const markHeader = () => siteHeader.classList.toggle('is-scrolled', window.scrollY > 8);
    markHeader();
    window.addEventListener('scroll', markHeader, { passive: true });

    document.querySelectorAll('details.nav-menu').forEach((menu) => {
        menu.addEventListener('toggle', () => {
            if (!menu.open) {
                return;
            }

            document.querySelectorAll('details.nav-menu').forEach((other) => {
                if (other !== menu) {
                    other.open = false;
                }
            });
        });
    });
</script>
