<section @class(['band hero-stage flex min-h-[calc(100svh-4.5rem)] items-center', 'has-photo' => filled($heroImageUrl) && ! filled($videotron)])>
    @if ($videotron)
        <div class="hero-video" aria-hidden="true">
            @if ($videotron['kind'] === 'embed')
                <iframe src="{{ $videotron['background'] ?? $videotron['src'] }}" tabindex="-1" allow="autoplay; encrypted-media" referrerpolicy="strict-origin-when-cross-origin"></iframe>
            @else
                <video autoplay muted loop playsinline @if ($heroImageUrl) poster="{{ $heroImageUrl }}" @endif>
                    <source src="{{ $videotron['src'] }}">
                </video>
            @endif
        </div>
    @endif
    <div class="hero-copy relative mx-auto w-full max-w-6xl px-4 py-16">
        @if ($section->kicker)
            <p class="text-sm font-semibold uppercase tracking-[0.18em]">{{ $section->kicker }}</p>
        @endif
        <h1 class="mt-4 max-w-4xl text-4xl font-semibold leading-tight sm:text-6xl">{{ $section->title }}</h1>
        @if ($section->subtitle)
            <p class="mt-4 text-xl font-medium">{{ $section->subtitle }}</p>
        @endif
        @if ($section->excerpt)
            <p class="mt-3 max-w-2xl text-lg">{{ $section->excerpt }}</p>
        @endif
        <div class="mt-8 flex flex-col items-start gap-3 sm:flex-row sm:items-center">
            <a href="{{ route('spmb') }}" class="spmb-cta inline-flex items-center justify-center gap-3 rounded-full bg-amber-400 px-8 py-4 text-lg font-semibold text-amber-950 ring-4 ring-amber-200">
                <span class="rounded-full bg-amber-950 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-amber-100">Dibuka</span>
                Daftar SPMB
            </a>
            <a href="{{ route('profil.sambutan') }}" class="inline-flex items-center justify-center rounded-full border border-current px-6 py-3 text-base font-medium">
                Lihat profil
            </a>
        </div>
    </div>
</section>
