@extends('layouts.app')

@section('title', 'Login admin')

@section('content')
    @include('partials.page-heading', [
        'heading' => 'Login admin',
        'summary' => 'Masuk untuk mengelola isi situs.',
    ])
    <form method="POST" action="{{ route('login.store') }}" class="mx-auto max-w-md space-y-4 px-4 py-12">
        @csrf
        <label class="block text-sm font-medium">Email
            <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full rounded-2xl border border-black/10 bg-white px-4 py-3">
        </label>
        <label class="block text-sm font-medium">Kata sandi
            <input type="password" name="password" required class="mt-1 w-full rounded-2xl border border-black/10 bg-white px-4 py-3">
        </label>
        @if ($errors->any())
            <p class="text-sm text-red-700">{{ $errors->first() }}</p>
        @endif
        <button type="submit" class="rounded-full px-5 py-3 font-semibold text-white" style="background: var(--accent)">Masuk</button>
    </form>
@endsection
