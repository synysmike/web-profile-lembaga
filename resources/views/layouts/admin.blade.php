<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') — {{ $schoolName }}</title>
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
        }
        body {
            margin: 0;
            background: #eef3f8;
            color: var(--ink);
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
        }
        #admin-nav {
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
        .admin-side {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 40;
            display: flex;
            width: 16rem;
            flex-direction: column;
            background: var(--footer);
            color: white;
            transform: translateX(-100%);
            transition: transform 180ms ease;
        }
        .admin-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 30;
            background: rgb(15 23 42 / 0.45);
        }
        #admin-nav:checked ~ .admin-side { transform: none; }
        #admin-nav:checked ~ .admin-backdrop { display: block; }
        @media (min-width: 1024px) {
            .admin-shell { display: flex; min-height: 100vh; }
            .admin-side { position: sticky; top: 0; height: 100vh; transform: none; flex: none; }
            .admin-backdrop { display: none !important; }
        }
        .admin-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            border-radius: 0.8rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: rgb(255 255 255 / 0.82);
        }
        .admin-link:hover { background: rgb(255 255 255 / 0.08); color: white; }
        .admin-link.is-active { background: var(--accent); color: white; }
        @media (prefers-reduced-motion: reduce) {
            .admin-side { transition: none; }
        }
    </style>
</head>
<body>
    <div class="admin-shell">
        <input id="admin-nav" type="checkbox" class="sr-only">
        <aside class="admin-side">
            <div class="border-b border-white/10 px-4 py-5">
                <a href="{{ route('admin.home') }}" class="block">
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/60">Panel admin</p>
                    <p class="mt-1 truncate text-base font-semibold">{{ $school['name'] }}</p>
                </a>
            </div>
            <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-4">
                @foreach (\App\Support\AdminNav::groups() as $group => $links)
                    <div>
                        <p class="px-3 text-xs font-semibold uppercase tracking-wide text-white/45">{{ $group }}</p>
                        <div class="mt-1 space-y-0.5">
                            @foreach ($links as $link)
                                <a href="{{ route($link['route']) }}" class="admin-link {{ request()->routeIs(...$link['active']) ? 'is-active' : '' }}">
                                    <span>{{ $link['label'] }}</span>
                                    @if ($link['route'] === 'admin.messages' && ($unreadMessages ?? 0) > 0)
                                        <span class="rounded-full bg-amber-300 px-2 py-0.5 text-xs font-semibold text-amber-950">{{ $unreadMessages }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>
            <div class="space-y-1 border-t border-white/10 px-3 py-3">
                <a href="{{ route('home') }}" class="admin-link">Lihat situs</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="admin-link w-full text-left">Keluar</button>
                </form>
            </div>
        </aside>
        <label for="admin-nav" class="admin-backdrop"></label>

        <div class="min-w-0 flex-1">
            <header class="sticky top-0 z-20 flex items-start gap-4 border-b border-black/5 bg-white px-4 py-4 lg:px-8">
                <label for="admin-nav" class="mt-1 cursor-pointer rounded-full border px-3 py-1.5 text-sm font-semibold lg:hidden" style="border-color: var(--accent); color: var(--accent)">Menu</label>
                <div class="min-w-0 flex-1">
                    <h1 class="truncate text-2xl font-semibold">@yield('title', 'Panel')</h1>
                    @hasSection('summary')
                        <p class="mt-1 max-w-3xl text-sm" style="color: var(--muted)">@yield('summary')</p>
                    @endif
                </div>
                @hasSection('actions')
                    <div class="shrink-0">@yield('actions')</div>
                @endif
            </header>
            <div class="px-4 py-6 lg:px-8">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
