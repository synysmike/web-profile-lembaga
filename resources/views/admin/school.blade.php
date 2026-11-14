@extends('layouts.admin')

@section('title', 'Identitas')

@section('summary', 'Nama, logo, tagline, dan kontak yang tampil di header serta footer.')

@section('content')
    <form method="POST" action="{{ route('admin.school.update') }}" enctype="multipart/form-data" class="mx-auto max-w-3xl space-y-6">
        @csrf
        @method('PUT')

        @if (session('status'))
            <p class="rounded-2xl bg-white px-4 py-3 text-sm">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <p class="rounded-2xl bg-white px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</p>
        @endif

        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <legend class="font-semibold">Nama dan tagline</legend>
            <label class="block text-sm font-medium">Nama lembaga
                <input name="name" value="{{ old('name', $school['name']) }}" required maxlength="80" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Tagline
                <textarea name="tagline" rows="3" maxlength="180" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ old('tagline', $school['tagline']) }}</textarea>
            </label>
            <p class="text-sm" style="color: var(--muted)">Tagline tampil di bawah nama pada footer.</p>
        </fieldset>

        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <legend class="font-semibold">Logo</legend>
            <p class="text-sm" style="color: var(--muted)">Tampil di samping nama pada header, dan di atas nama pada footer.</p>
            @if ($school['logo'])
                <img src="{{ $school['logo'] }}" alt="Logo saat ini" class="h-16 w-16 rounded-2xl bg-slate-50 object-contain">
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="remove_logo" value="1">
                    Hapus logo ini
                </label>
            @endif
            <label class="block text-sm font-medium">Logo baru
                <input type="file" name="logo" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm">
            </label>
            <p class="text-sm" style="color: var(--muted)">JPG, PNG, atau WebP, paling besar 2 MB.</p>
        </fieldset>

        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <legend class="font-semibold">Kontak</legend>
            <label class="block text-sm font-medium">Alamat
                <input name="address" value="{{ old('address', $school['address']) }}" maxlength="160" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Telepon
                <input name="phone" value="{{ old('phone', $school['phone']) }}" maxlength="30" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Email
                <input type="email" name="email" value="{{ old('email', $school['email']) }}" maxlength="120" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <p class="text-sm" style="color: var(--muted)">Alamat, telepon, dan email tampil di footer serta halaman kontak.</p>
        </fieldset>

        <button type="submit" class="rounded-full bg-amber-400 px-6 py-3 font-semibold text-amber-950 ring-4 ring-amber-200">Simpan identitas</button>
    </form>
@endsection
