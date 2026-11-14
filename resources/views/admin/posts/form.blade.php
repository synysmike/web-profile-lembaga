@extends('layouts.admin')

@section('title', $post->exists ? 'Sunting berita' : 'Berita baru')

@section('summary', 'Slug menjadi alamat /berita/slug. Pisahkan paragraf dengan baris kosong.')

@section('actions')
    <a href="{{ route('admin.posts') }}" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold">Kembali</a>
@endsection

@section('content')
    <form method="POST" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" enctype="multipart/form-data" class="mx-auto max-w-3xl space-y-4">
        @csrf
        @if ($post->exists)
            @method('PUT')
        @endif
        @include('partials.admin-alerts')

        <fieldset class="space-y-4 rounded-3xl bg-white p-6">
            <label class="block text-sm font-medium">Judul
                <input name="title" value="{{ old('title', $post->title) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Slug
                <input name="slug" value="{{ old('slug', $post->slug) }}" placeholder="kosongkan untuk mengikuti judul" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="block text-sm font-medium">Tanggal
                <input type="date" name="published_on" value="{{ old('published_on', optional($post->published_on)->toDateString() ?? $post->published_on) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
            </label>
            @if ($post->coverUrl())
                <img src="{{ $post->coverUrl() }}" alt="" class="h-40 w-full rounded-2xl object-cover">
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="remove_cover" value="1" @checked(old('remove_cover'))>
                    Hapus sampul
                </label>
            @endif
            <label class="block text-sm font-medium">Sampul
                <input type="file" name="cover" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm">
            </label>
            <p class="text-sm" style="color: var(--muted)">JPG, PNG, atau WebP, paling besar 5 MB. Kosongkan bila berita cukup berupa teks.</p>
            <label class="block text-sm font-medium">Ringkasan
                <textarea name="excerpt" rows="3" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ old('excerpt', $post->excerpt) }}</textarea>
            </label>
            <label class="block text-sm font-medium">Isi
                <textarea name="body" rows="10" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ old('body', $post->body) }}</textarea>
            </label>
            @php $visible = session()->hasOldInput() ? old('is_visible') : ($post->is_visible ? '1' : null); @endphp
            <label class="flex items-center gap-2 text-sm font-medium">
                <input type="checkbox" name="is_visible" value="1" @checked($visible)>
                Tampil di situs
            </label>
        </fieldset>

        <button type="submit" class="rounded-full bg-amber-400 px-6 py-3 font-semibold text-amber-950 ring-4 ring-amber-200">Simpan berita</button>
    </form>
@endsection
