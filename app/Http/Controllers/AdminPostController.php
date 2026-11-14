<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\PublicFile;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminPostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index', [
            'posts' => Post::query()->orderByDesc('published_on')->orderByDesc('id')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.posts.form', [
            'post' => new Post([
                'published_on' => now('Asia/Jakarta')->toDateString(),
                'is_visible' => true,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $post = Post::query()->create($this->payload($request));

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Berita sudah disimpan.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $post->update($this->payload($request, $post));

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Berita sudah disimpan.');
    }

    public function destroy(Post $post)
    {
        PublicFile::delete($post->cover_path);
        $post->delete();

        return redirect()->route('admin.posts')->with('status', 'Berita sudah dihapus.');
    }

    /**
     * @return array{title: string, slug: string, published_on: string, excerpt: string, body: string, is_visible: bool, cover_path?: string|null}
     */
    private function payload(Request $request, ?Post $post = null): array
    {
        $slugInput = trim((string) $request->input('slug'));
        $request->merge([
            'slug' => Str::slug($slugInput !== '' ? $slugInput : (string) $request->input('title')),
        ]);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['required', 'string', 'max:180', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('posts', 'slug')->ignore($post?->id)],
            'published_on' => ['required', 'date'],
            'excerpt' => ['required', 'string', 'max:300'],
            'body' => ['required', 'string', 'max:20000'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul paling panjang 160 karakter.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.unique' => 'Slug itu sudah dipakai berita lain.',
            'slug.regex' => 'Slug hanya huruf kecil, angka, dan tanda hubung.',
            'published_on.required' => 'Tanggal wajib diisi.',
            'published_on.date' => 'Tanggal belum sesuai.',
            'excerpt.required' => 'Ringkasan wajib diisi.',
            'excerpt.max' => 'Ringkasan paling panjang 300 karakter.',
            'body.required' => 'Isi berita wajib diisi.',
            'cover.image' => 'Sampul harus berupa JPG, PNG, atau WebP.',
            'cover.mimes' => 'Sampul harus berupa JPG, PNG, atau WebP.',
            'cover.max' => 'Sampul paling besar 5 MB.',
        ]);

        $payload = [
            'title' => trim($data['title']),
            'slug' => $data['slug'],
            'published_on' => $data['published_on'],
            'excerpt' => trim($data['excerpt']),
            'body' => trim($data['body']),
            'is_visible' => $request->boolean('is_visible'),
        ];

        $cover = $request->file('cover');

        if ($cover instanceof UploadedFile) {
            PublicFile::delete($post?->cover_path);
            $payload['cover_path'] = $cover->store('halaman', 'public');
        } elseif ($post && $request->boolean('remove_cover')) {
            PublicFile::delete($post->cover_path);
            $payload['cover_path'] = null;
        }

        return $payload;
    }
}
