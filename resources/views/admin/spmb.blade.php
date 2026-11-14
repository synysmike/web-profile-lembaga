@extends('layouts.admin')

@section('title', 'SPMB')

@section('summary', 'Judul halaman, tahap, dan jalur penerimaan. Halaman ini tidak menerima pendaftaran daring.')

@section('content')
    @include('partials.admin-alerts')

    <form method="POST" action="{{ route('admin.spmb.update') }}" class="mb-8 max-w-3xl space-y-4">
        @csrf
        @method('PUT')
        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <legend class="font-semibold">Judul halaman</legend>
            <label class="block text-sm font-medium">Judul
                <input name="heading" value="{{ old('heading', $page->title) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Ringkasan pita
                <input name="summary" value="{{ old('summary', $page->summary) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
        </fieldset>
        <button type="submit" class="rounded-full bg-amber-400 px-6 py-3 font-semibold text-amber-950 ring-4 ring-amber-200">Simpan judul</button>
    </form>

    <section class="space-y-3">
        <h2 class="text-xl font-semibold">Tahap</h2>
        @include('admin.partials.datatable', ['spec' => $stepSpec, 'items' => $steps])
    </section>

    <section class="mt-8 space-y-3">
        <h2 class="text-xl font-semibold">Jalur</h2>
        @include('admin.partials.datatable', ['spec' => $pathSpec, 'items' => $paths])
    </section>
@endsection
