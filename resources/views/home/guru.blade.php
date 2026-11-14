<section class="mx-auto max-w-6xl px-4 py-12">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold">{{ $section->title }}</h2>
            <p class="mt-2 max-w-2xl" style="color: var(--muted)">{{ $section->excerpt }}</p>
        </div>
        <a href="{{ route('guru') }}" class="shrink-0 text-sm font-semibold" style="color: var(--accent)">Lihat semua</a>
    </div>
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($homeStaff as $person)
            <article class="rounded-3xl bg-white p-5">
                @if ($person->photoUrl())
                    <img src="{{ $person->photoUrl() }}" alt="" class="h-16 w-16 rounded-full object-cover">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-full text-lg font-semibold text-white" style="background: var(--accent)">{{ $person->initial() }}</div>
                @endif
                <h3 class="mt-4 font-semibold">{{ $person->name }}</h3>
                <p class="text-sm" style="color: var(--muted)">{{ $person->role }}</p>
            </article>
        @empty
            <p class="rounded-3xl bg-white p-6 sm:col-span-2 lg:col-span-4">Belum ada guru atau staf.</p>
        @endforelse
    </div>
</section>
