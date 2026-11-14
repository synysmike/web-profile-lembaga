@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
    @include('partials.page-heading', [
        'heading' => 'Hubungi kami',
        'summary' => 'Alamat, telepon, dan pesan untuk '.$school['name'].'.',
    ])
    <div class="mx-auto grid max-w-6xl gap-6 px-4 py-12 lg:grid-cols-2">
        <div class="rounded-3xl bg-white p-6">
            <h2 class="text-xl font-semibold">Alamat</h2>
            @if ($school['address'])
                <p class="mt-3">{{ $school['address'] }}</p>
            @endif
            @if ($school['phone'])
                <p class="mt-2"><a href="tel:{{ preg_replace('/[^\d+]/', '', $school['phone']) }}">{{ $school['phone'] }}</a></p>
            @endif
            @if ($school['email'])
                <p class="mt-2"><a href="mailto:{{ $school['email'] }}">{{ $school['email'] }}</a></p>
            @endif
        </div>
        <form method="POST" action="{{ route('kontak.store') }}" class="space-y-4 rounded-3xl bg-white p-6">
            @csrf
            @if (session('status'))
                <p class="rounded-2xl px-4 py-3 text-sm" style="background: var(--page)">{{ session('status') }}</p>
            @endif
            <label class="block text-sm font-medium">Nama
                <input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Email
                <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Pesan
                <textarea name="message" rows="4" required class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ old('message') }}</textarea>
            </label>
            @if ($errors->any())
                <p class="text-sm text-red-700">{{ $errors->first() }}</p>
            @endif
            <button type="submit" class="rounded-full px-5 py-3 font-semibold text-white" style="background: var(--accent)">Kirim pesan</button>
        </form>
    </div>
@endsection
