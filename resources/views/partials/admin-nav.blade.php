<nav class="mx-auto flex max-w-6xl flex-wrap gap-2 px-4 pt-8">
    @foreach (\App\Support\AdminNav::items() as $item)
        <a href="{{ route($item['route']) }}" class="rounded-full px-4 py-2 text-sm font-semibold" @if (request()->routeIs(...$item['active'])) style="background: var(--accent); color: white" @else style="background: white; color: var(--ink)" @endif>{{ $item['label'] }}</a>
    @endforeach
</nav>
