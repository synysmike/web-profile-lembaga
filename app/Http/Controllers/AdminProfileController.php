<?php

namespace App\Http\Controllers;

use App\Models\SiteLine;
use App\Models\SitePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', [
            'sambutan' => SitePage::content(SitePage::SAMBUTAN),
            'sejarah' => SitePage::content(SitePage::SEJARAH),
            'struktur' => SitePage::content(SitePage::STRUKTUR),
            'visi' => SitePage::content(SitePage::VISI),
            'jejak' => $this->lines(SitePage::SEJARAH, SiteLine::TRACE),
            'jabatan' => $this->lines(SitePage::STRUKTUR, SiteLine::ROLE),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'sambutan.title' => ['required', 'string', 'max:120'],
            'sambutan.summary' => ['nullable', 'string', 'max:180'],
            'sambutan.body' => ['required', 'string', 'max:8000'],
            'sejarah.title' => ['required', 'string', 'max:120'],
            'sejarah.summary' => ['nullable', 'string', 'max:240'],
            'struktur.title' => ['required', 'string', 'max:120'],
            'struktur.summary' => ['nullable', 'string', 'max:240'],
            'visi.title' => ['required', 'string', 'max:120'],
            'visi.summary' => ['nullable', 'string', 'max:240'],
            'visi.body' => ['required', 'string', 'max:2000'],
            'visi.missions' => ['nullable', 'string', 'max:4000'],
            'visi.goals' => ['nullable', 'string', 'max:4000'],
        ], [
            'sambutan.title.required' => 'Judul sambutan wajib diisi.',
            'sambutan.body.required' => 'Isi sambutan wajib diisi.',
            'sejarah.title.required' => 'Judul sejarah wajib diisi.',
            'struktur.title.required' => 'Judul struktur wajib diisi.',
            'visi.title.required' => 'Judul visi dan misi wajib diisi.',
            'visi.body.required' => 'Visi wajib diisi.',
        ]);

        DB::transaction(function () use ($data, $request) {
            $this->savePage(SitePage::SAMBUTAN, $data['sambutan'], ['title', 'summary', 'body']);
            $this->savePage(SitePage::SEJARAH, $data['sejarah'], ['title', 'summary']);
            $this->savePage(SitePage::STRUKTUR, $data['struktur'], ['title', 'summary']);
            $this->savePage(SitePage::VISI, $data['visi'], ['title', 'summary', 'body', 'missions', 'goals']);

            SiteLine::sync(
                SitePage::SEJARAH,
                SiteLine::TRACE,
                $request->input('sejarah.items', []),
                $request->input('sejarah.new', []),
                ['label', 'body'],
                ['label' => 'Tahun wajib diisi.', 'body' => 'Kalimat jejak wajib diisi.'],
            );

            SiteLine::sync(
                SitePage::STRUKTUR,
                SiteLine::ROLE,
                $request->input('struktur.items', []),
                $request->input('struktur.new', []),
                ['label', 'title'],
                ['label' => 'Jabatan wajib diisi.', 'title' => 'Nama wajib diisi.'],
            );
        });

        return redirect()->route('admin.profile')->with('status', 'Profil sudah disimpan.');
    }

    private function savePage(string $key, array $input, array $fields): void
    {
        $payload = [];

        foreach ($fields as $field) {
            $payload[$field] = trim((string) ($input[$field] ?? ''));
        }

        SitePage::content($key)->update($payload);
    }

    private function lines(string $pageKey, string $kind)
    {
        return SiteLine::query()
            ->where('page_key', $pageKey)
            ->where('kind', $kind)
            ->orderBy('position')
            ->orderBy('id')
            ->get();
    }
}
