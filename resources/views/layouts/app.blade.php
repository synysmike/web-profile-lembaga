<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $documentTitle = trim($__env->yieldContent('document_title'));
        if ($documentTitle === '') {
            $documentTitle = trim($__env->yieldContent('title', 'Beranda')).' — '.$schoolName;
        }
        $shareDescription = trim(preg_replace('/\s+/', ' ', strip_tags($__env->yieldContent('share_description'))) ?? '');
        if ($shareDescription === '') {
            $shareDescription = $school['tagline'] !== '' ? $school['tagline'] : $schoolName;
        }
        if (mb_strlen($shareDescription) > 160) {
            $shareDescription = rtrim(mb_substr($shareDescription, 0, 157)).'…';
        }
        $shareImage = trim($__env->yieldContent('share_image'));
        if ($shareImage === '') {
            $shareImage = (string) ($school['logo'] ?? '');
        }
        if ($shareImage !== '' && ! str_starts_with($shareImage, 'http')) {
            $shareImage = url($shareImage);
        }
        $canonical = trim($__env->yieldContent('canonical'));
        if ($canonical === '') {
            $canonical = url()->current();
        }
        $robots = trim($__env->yieldContent('robots'));
        if ($robots === '') {
            $robots = 'index, follow';
        }
        $ogType = trim($__env->yieldContent('og_type'));
        if ($ogType === '') {
            $ogType = 'website';
        }
        $publishedTime = trim($__env->yieldContent('published_time'));
        $shareTitle = trim($__env->yieldContent('share_title'));
        if ($shareTitle === '') {
            $shareTitle = $documentTitle;
        }
    @endphp
    <title>{{ $documentTitle }}</title>
    <meta name="description" content="{{ $shareDescription }}">
    <meta name="robots" content="{{ $robots }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="{{ $schoolName }}">
    <meta property="og:title" content="{{ $shareTitle }}">
    <meta property="og:description" content="{{ $shareDescription }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $canonical }}">
    @if ($publishedTime !== '')
        <meta property="article:published_time" content="{{ $publishedTime }}">
    @endif
    @if ($shareImage !== '')
        <meta property="og:image" content="{{ $shareImage }}">
        <meta property="og:image:alt" content="{{ $shareTitle }}">
    @endif
    <meta name="twitter:card" content="{{ $shareImage !== '' ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $shareTitle }}">
    <meta name="twitter:description" content="{{ $shareDescription }}">
    @if ($shareImage !== '')
        <meta name="twitter:image" content="{{ $shareImage }}">
    @endif
    @stack('head')
    @if ($school['logo'])
        <link rel="icon" href="{{ $school['logo'] }}">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        :root {
            --page: {{ $activeTheme['page'] }};
            --hero: {{ $activeTheme['hero'] }};
            --hero-text: {{ $activeTheme['hero_text'] }};
            --accent: {{ $activeTheme['accent'] }};
            --ink: {{ $activeTheme['ink'] }};
            --muted: {{ $activeTheme['muted'] }};
            --footer: {{ $activeTheme['footer'] }};
            @if ($heroImageUrl)
                --hero-image: url('{{ str_replace(["\\", "'"], '', $heroImageUrl) }}');
            @endif
        }
        .band {
            background-color: var(--hero);
            color: var(--hero-text);
        }
        /* untuk menambahkan efek blur di background hero */
        .band.has-photo {
            background-image:
                linear-gradient(color-mix(in srgb, var(--hero) 20%, transparent), color-mix(in srgb, var(--hero) 30%, transparent)),
                var(--hero-image);
            background-size: cover;
            background-position: center;
        }
        .hero-stage { position: relative; overflow: hidden; }
        .hero-video {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }
        .hero-video::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(color-mix(in srgb, var(--hero) 20%, transparent), color-mix(in srgb, var(--hero) 30%, transparent));
        }
        .hero-video video,
        .hero-video iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            transform: translate(-50%, -50%);
            border: 0;
            object-fit: cover;
        }
        .hero-video iframe {
            width: 177.78vh;
            height: 100vh;
            min-width: 100%;
            min-height: 56.25vw;
        }
        .hero-copy { position: relative; z-index: 1; }
        body {
            background: var(--page);
            color: var(--ink);
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
        }
        summary::-webkit-details-marker { display: none; }
        summary { list-style: none; }
        summary::marker { content: ""; }
        header { transition: box-shadow 200ms ease; }
        header.is-scrolled { box-shadow: 0 10px 28px rgb(15 23 42 / 0.07); }
        header nav > a,
        header nav summary {
            transition: background-color 160ms ease, color 160ms ease;
        }
        header nav > a:not(.bg-amber-400):hover,
        header nav summary:hover {
            background: color-mix(in srgb, var(--accent) 12%, white);
        }
        .nav-caret { transition: transform 200ms ease; }
        details[open] > summary .nav-caret { transform: rotate(180deg); }
        .nav-menu[open] > .nav-panel { animation: menu-in 180ms ease; }
        .nav-panel a { transition: background-color 160ms ease; }
        @media (max-width: 767px) {
            #nav-toggle:checked ~ nav { animation: menu-in 220ms ease; }
        }
        .hero-copy > * { animation: rise 640ms ease both; }
        .hero-copy > *:nth-child(2) { animation-delay: 70ms; }
        .hero-copy > *:nth-child(3) { animation-delay: 140ms; }
        .hero-copy > *:nth-child(4) { animation-delay: 210ms; }
        .hero-copy > *:nth-child(5) { animation-delay: 280ms; }
        .page-intro > * { animation: rise 520ms ease both; }
        .page-intro > *:nth-child(2) { animation-delay: 70ms; }
        .page-intro > *:nth-child(3) { animation-delay: 140ms; }
        .spmb-cta { transition: transform 200ms ease, box-shadow 200ms ease; }
        main a.spmb-cta:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgb(245 158 11 / 0.35); }
        main a.rounded-3xl,
        main article.rounded-3xl {
            transition: transform 220ms ease, box-shadow 220ms ease;
        }
        main a.rounded-3xl:hover,
        main article.rounded-3xl:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 34px rgb(15 23 42 / 0.08);
        }
        main a.rounded-full,
        main button.rounded-full {
            transition: transform 180ms ease, box-shadow 180ms ease;
        }
        main a.rounded-full:hover,
        main button.rounded-full:hover { transform: translateY(-1px); }
        input, textarea {
            transition: border-color 160ms ease, box-shadow 160ms ease;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--accent) 18%, transparent);
        }
        @keyframes rise {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: none; }
        }
        @keyframes menu-in {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: none; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
            }
            .hero-video { display: none; }
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
</head>
<body class="min-h-screen antialiased">
    @include('partials.header')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
</body>
</html>
