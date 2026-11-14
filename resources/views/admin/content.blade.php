@extends('layouts.admin')

@section('title', 'Konten dasar')

@section('summary', 'Gambar latar pita hero di semua halaman, dan video yang diputar di latar hero beranda.')

@section('content')
    <form method="POST" action="{{ route('admin.content.update') }}" enctype="multipart/form-data" class="mx-auto max-w-3xl space-y-6">
        @csrf
        @method('PUT')

        @if (session('status'))
            <p class="rounded-2xl bg-white px-4 py-3 text-sm">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <p class="rounded-2xl bg-white px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</p>
        @endif

        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <legend class="font-semibold">Gambar latar hero</legend>
            <p class="text-sm" style="color: var(--muted)">Dipakai pada pita hero beranda dan judul halaman lain. Warna tema tetap menjadi cadangan dan lapisan tipis di atas foto.</p>
            @if ($heroImageUrl)
                <img src="{{ $heroImageUrl }}" alt="Latar hero saat ini" class="h-40 w-full rounded-2xl object-cover">
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="remove_hero_image" value="1">
                    Hapus gambar ini
                </label>
            @endif
            <label class="block text-sm font-medium">Gambar baru
                <input type="file" name="hero_image" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm">
            </label>
            <p class="text-sm" style="color: var(--muted)">JPG, PNG, atau WebP, paling besar 5 MB.</p>
        </fieldset>

        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <legend class="font-semibold">Videotron</legend>
            <p class="text-sm" style="color: var(--muted)">Diputar senyap, berulang, sebagai latar hero beranda saja. Teks tetap di atasnya. Halaman lain memakai gambar latar atau warna tema.</p>
            @if ($videotron)
                <div class="overflow-hidden rounded-2xl bg-slate-950">
                    @if ($videotron['kind'] === 'embed')
                        <iframe class="w-full" style="aspect-ratio: 16 / 9" src="{{ $videotron['src'] }}" title="Pratinjau videotron" allowfullscreen></iframe>
                    @else
                        <video class="w-full" style="aspect-ratio: 16 / 9" controls src="{{ $videotron['src'] }}"></video>
                    @endif
                </div>
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="remove_videotron" value="1">
                    Hapus videotron
                </label>
            @endif
            <label class="block text-sm font-medium">Berkas video
                <input type="file" name="videotron" accept="video/mp4,video/webm" class="mt-2 block w-full text-sm">
            </label>
            <p class="text-sm" style="color: var(--muted)">MP4 atau WebM, paling besar 24 MB. Berkas menggantikan tautan.</p>
            <label class="block text-sm font-medium">Atau tautan
                <input type="url" name="videotron_url" value="{{ old('videotron_url', $videotronUrl) }}" placeholder="https://www.youtube.com/watch?v=..." class="mt-2 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
        </fieldset>

        <button type="submit" class="rounded-full bg-amber-400 px-6 py-3 font-semibold text-amber-950 ring-4 ring-amber-200">Simpan konten</button>
    </form>
@endsection
