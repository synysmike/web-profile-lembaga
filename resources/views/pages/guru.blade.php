@extends('layouts.app')

@section('title', 'Guru dan staf')

@section('content')
    @include('partials.page-heading', [
        'heading' => 'Guru dan staf',
        'summary' => 'Orang-orang yang mendampingi siswa di madrasah.',
    ])
    <div class="mx-auto grid max-w-6xl gap-4 px-4 py-12 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($staff as $person)
            <article class="rounded-3xl bg-white p-5">
                @if ($person->photoUrl())
                    <img src="{{ $person->photoUrl() }}" alt="" class="h-16 w-16 rounded-full object-cover">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-full text-lg font-semibold text-white" style="background: var(--accent)">{{ $person->initial() }}</div>
                @endif
                <h2 class="mt-4 font-semibold">{{ $person->name }}</h2>
                <p class="text-sm" style="color: var(--muted)">{{ $person->role }}</p>
            </article>
        @empty
            <p class="rounded-3xl bg-white p-6 sm:col-span-2 lg:col-span-4">Belum ada guru atau staf.</p>
        @endforelse
    </div>
@endsection
