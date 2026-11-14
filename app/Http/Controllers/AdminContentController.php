<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminContentController extends Controller
{
    public function edit()
    {
        return view('admin.content', [
            'heroImageUrl' => SiteContent::heroImageUrl(),
            'videotron' => SiteContent::videotron(),
            'videotronUrl' => SiteContent::storedVideotronUrl(),
            'hasVideotronFile' => SiteContent::hasVideotronFile(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'videotron' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:24576'],
            'videotron_url' => ['nullable', 'string', 'max:300'],
        ], [
            'hero_image.image' => 'Latar hero harus berupa gambar.',
            'hero_image.max' => 'Gambar latar paling besar 5 MB.',
            'videotron.mimetypes' => 'Videotron memakai berkas mp4 atau webm.',
            'videotron.max' => 'Video paling besar 24 MB.',
        ]);

        $url = trim((string) ($data['videotron_url'] ?? ''));

        if ($url !== '' && ! $request->hasFile('videotron') && SiteContent::describeUrl($url) === null) {
            return back()
                ->withErrors(['videotron_url' => 'Gunakan tautan https YouTube, Vimeo, atau berkas mp4/webm.'])
                ->withInput();
        }

        if ($request->hasFile('hero_image')) {
            $this->deletePublic(SiteContent::storedHeroPath());
            Setting::putValue(SiteContent::HERO_IMAGE, $request->file('hero_image')->store('konten', 'public'));
        } elseif ($request->boolean('remove_hero_image')) {
            $this->deletePublic(SiteContent::storedHeroPath());
            Setting::forget(SiteContent::HERO_IMAGE);
        }

        $videotron = $this->storedVideotron();

        if ($request->hasFile('videotron')) {
            $this->deleteStoredVideo($videotron);
            Setting::putValue(SiteContent::VIDEOTRON, json_encode([
                'source' => 'file',
                'path' => $request->file('videotron')->store('konten', 'public'),
            ], JSON_UNESCAPED_SLASHES));
        } elseif ($request->boolean('remove_videotron')) {
            $this->deleteStoredVideo($videotron);
            Setting::forget(SiteContent::VIDEOTRON);
        } elseif ($url !== '') {
            $this->deleteStoredVideo($videotron);
            Setting::putValue(SiteContent::VIDEOTRON, json_encode([
                'source' => 'url',
                'url' => $url,
            ], JSON_UNESCAPED_SLASHES));
        }

        return redirect()
            ->route('admin.content')
            ->with('status', 'Konten dasar sudah disimpan.');
    }

    private function storedVideotron(): ?array
    {
        $raw = Setting::getValue(SiteContent::VIDEOTRON);
        $stored = is_string($raw) ? json_decode($raw, true) : null;

        return is_array($stored) ? $stored : null;
    }

    private function deleteStoredVideo(?array $stored): void
    {
        if (is_array($stored) && ($stored['source'] ?? null) === 'file') {
            $this->deletePublic($stored['path'] ?? null);
        }
    }

    private function deletePublic(mixed $path): void
    {
        if (is_string($path) && $path !== '') {
            Storage::disk('public')->delete($path);
        }
    }
}
