@extends('layouts.admin')

@section('title', 'Profil')

@section('summary', 'Sambutan, sejarah, struktur, serta visi dan misi.')

@section('content')
    <form method="POST" action="{{ route('admin.profile.update') }}" class="mx-auto max-w-3xl space-y-6">
        @csrf
        @method('PUT')
        @include('partials.admin-alerts')

        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <legend class="font-semibold">Sambutan</legend>
            <label class="block text-sm font-medium">Judul
                <input name="sambutan[title]" value="{{ old('sambutan.title', $sambutan->title) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Nama di ringkasan pita
                <input name="sambutan[summary]" value="{{ old('sambutan.summary', $sambutan->summary) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Isi sambutan
                <textarea name="sambutan[body]" rows="8" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ old('sambutan.body', $sambutan->body) }}</textarea>
            </label>
            <p class="text-sm" style="color: var(--muted)">Pisahkan paragraf dengan baris kosong.</p>
        </fieldset>

        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <legend class="font-semibold">Sejarah</legend>
            <label class="block text-sm font-medium">Judul
                <input name="sejarah[title]" value="{{ old('sejarah.title', $sejarah->title) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Ringkasan pita
                <input name="sejarah[summary]" value="{{ old('sejarah.summary', $sejarah->summary) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            @include('admin.partials.lines', [
                'group' => 'sejarah',
                'items' => $jejak,
                'addLabel' => 'Tambah jejak',
                'columns' => [
                    ['name' => 'label', 'label' => 'Tahun', 'type' => 'text'],
                    ['name' => 'body', 'label' => 'Kalimat', 'type' => 'text'],
                ],
            ])
        </fieldset>

        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <legend class="font-semibold">Struktur</legend>
            <label class="block text-sm font-medium">Judul
                <input name="struktur[title]" value="{{ old('struktur.title', $struktur->title) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Ringkasan pita
                <input name="struktur[summary]" value="{{ old('struktur.summary', $struktur->summary) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            @include('admin.partials.lines', [
                'group' => 'struktur',
                'items' => $jabatan,
                'addLabel' => 'Tambah jabatan',
                'columns' => [
                    ['name' => 'label', 'label' => 'Jabatan', 'type' => 'text'],
                    ['name' => 'title', 'label' => 'Nama', 'type' => 'text'],
                ],
            ])
        </fieldset>

        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <legend class="font-semibold">Visi dan misi</legend>
            <label class="block text-sm font-medium">Judul
                <input name="visi[title]" value="{{ old('visi.title', $visi->title) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Ringkasan pita
                <input name="visi[summary]" value="{{ old('visi.summary', $visi->summary) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Visi
                <textarea name="visi[body]" rows="4" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ old('visi.body', $visi->body) }}</textarea>
            </label>
            <label class="block text-sm font-medium">Misi
                <textarea name="visi[missions]" rows="4" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ old('visi.missions', $visi->missions) }}</textarea>
            </label>
            <label class="block text-sm font-medium">Tujuan
                <textarea name="visi[goals]" rows="4" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ old('visi.goals', $visi->goals) }}</textarea>
            </label>
            <p class="text-sm" style="color: var(--muted)">Misi dan tujuan: satu butir satu baris.</p>
        </fieldset>

        <button type="submit" class="rounded-full bg-amber-400 px-6 py-3 font-semibold text-amber-950 ring-4 ring-amber-200">Simpan profil</button>
    </form>
    @include('partials.add-rows')
@endsection
