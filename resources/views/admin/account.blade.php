@extends('layouts.admin')

@section('title', 'Akun')

@section('summary', 'Ganti kata sandi untuk masuk ke panel. Email akun tidak diubah di halaman ini.')

@section('content')
    <form method="POST" action="{{ route('admin.account.update') }}" class="mx-auto max-w-md space-y-4">
        @csrf
        @method('PUT')
        @include('partials.admin-alerts')

        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <label class="block text-sm font-medium">Kata sandi sekarang
                <input type="password" name="current_password" required autocomplete="current-password" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Kata sandi baru
                <input type="password" name="password" required autocomplete="new-password" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Ulangi kata sandi baru
                <input type="password" name="password_confirmation" required autocomplete="new-password" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
        </fieldset>

        <button type="submit" class="rounded-full bg-amber-400 px-6 py-3 font-semibold text-amber-950 ring-4 ring-amber-200">Simpan kata sandi</button>
    </form>
@endsection
