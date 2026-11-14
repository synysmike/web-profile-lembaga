@extends('layouts.admin')

@section('title', 'Pesan kontak')

@section('summary', 'Pesan dari formulir kontak. Situs tidak membalas lewat email.')

@section('content')
    @include('partials.admin-alerts')

    <div class="overflow-hidden rounded-2xl bg-white">
        @forelse ($messages as $message)
            <article class="border-b border-black/5 px-5 py-4 last:border-b-0">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-lg font-semibold">{{ $message->name }}</h2>
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold" @if ($message->read_at) style="background: #f1f5f9; color: var(--muted)" @else style="background: #fde68a; color: #78350f" @endif>{{ $message->read_at ? 'Sudah dibaca' : 'Belum dibaca' }}</span>
                        </div>
                        <p class="mt-1 text-sm" style="color: var(--muted)">{{ \App\Support\LocalDate::stamp($message->created_at) }} · <a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
                    </div>
                    <div class="flex gap-2">
                        @if (! $message->read_at)
                            <form method="POST" action="{{ route('admin.messages.read', $message) }}">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="rounded-full px-4 py-2 text-sm font-semibold text-white" style="background: var(--accent)">Tandai dibaca</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Hapus pesan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-full border border-black/10 px-4 py-2 text-sm font-semibold">Hapus</button>
                        </form>
                    </div>
                </div>
                <p class="mt-3 whitespace-pre-line">{{ $message->message }}</p>
            </article>
        @empty
            <p class="px-5 py-8" style="color: var(--muted)">Belum ada pesan.</p>
        @endforelse
    </div>
@endsection
