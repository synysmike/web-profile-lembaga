<?php

namespace App\Http\Controllers;

use App\Models\SiteLine;
use App\Models\SitePage;
use Illuminate\Http\Request;

class AdminSpmbController extends Controller
{
    public function edit()
    {
        return view('admin.spmb', [
            'page' => SitePage::content(SitePage::SPMB),
            'steps' => $this->lines(SiteLine::STEP),
            'paths' => $this->lines(SiteLine::PATH),
            'stepSpec' => $this->stepSpec(),
            'pathSpec' => $this->pathSpec(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'heading' => ['required', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:240'],
        ], [
            'heading.required' => 'Judul SPMB wajib diisi.',
            'heading.max' => 'Judul SPMB paling panjang 120 karakter.',
            'summary.max' => 'Ringkasan pita paling panjang 240 karakter.',
        ]);

        SitePage::content(SitePage::SPMB)->update([
            'title' => trim($data['heading']),
            'summary' => trim((string) ($data['summary'] ?? '')),
        ]);

        return redirect()->route('admin.spmb')->with('status', 'Judul SPMB sudah disimpan.');
    }

    public function storeStep(Request $request)
    {
        SiteLine::query()->create($this->payload($request, SiteLine::STEP, true));
        $this->renumber(SiteLine::STEP);

        return redirect()->route('admin.spmb')->with('status', 'Tahap sudah disimpan.');
    }

    public function updateStep(Request $request, SiteLine $line)
    {
        $line = $this->owned($line, SiteLine::STEP);
        $line->update($this->payload($request, SiteLine::STEP, true));
        $this->renumber(SiteLine::STEP);

        return redirect()->route('admin.spmb')->with('status', 'Tahap sudah disimpan.');
    }

    public function destroyStep(SiteLine $line)
    {
        $this->owned($line, SiteLine::STEP)->delete();
        $this->renumber(SiteLine::STEP);

        return redirect()->route('admin.spmb')->with('status', 'Tahap sudah dihapus.');
    }

    public function storePath(Request $request)
    {
        SiteLine::query()->create($this->payload($request, SiteLine::PATH, false));
        $this->renumber(SiteLine::PATH);

        return redirect()->route('admin.spmb')->with('status', 'Jalur sudah disimpan.');
    }

    public function updatePath(Request $request, SiteLine $line)
    {
        $line = $this->owned($line, SiteLine::PATH);
        $line->update($this->payload($request, SiteLine::PATH, false));
        $this->renumber(SiteLine::PATH);

        return redirect()->route('admin.spmb')->with('status', 'Jalur sudah disimpan.');
    }

    public function destroyPath(SiteLine $line)
    {
        $this->owned($line, SiteLine::PATH)->delete();
        $this->renumber(SiteLine::PATH);

        return redirect()->route('admin.spmb')->with('status', 'Jalur sudah dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(Request $request, string $kind, bool $step): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:2000'],
            'position' => ['required', 'integer', 'min:1', 'max:999'],
        ];
        $messages = [
            'title.required' => $step ? 'Judul tahap wajib diisi.' : 'Judul jalur wajib diisi.',
            'title.max' => 'Judul paling panjang 160 karakter.',
            'body.required' => $step ? 'Penjelasan tahap wajib diisi.' : 'Penjelasan jalur wajib diisi.',
            'body.max' => 'Penjelasan paling panjang 2000 karakter.',
            'position.required' => 'Urutan wajib diisi.',
            'position.integer' => 'Urutan harus berupa angka.',
            'position.min' => 'Urutan paling kecil 1.',
            'position.max' => 'Urutan paling besar 999.',
        ];

        if ($step) {
            $rules['label'] = ['required', 'string', 'max:160'];
            $messages['label.required'] = 'Label tanggal wajib diisi.';
            $messages['label.max'] = 'Label tanggal paling panjang 160 karakter.';
        }

        $data = $request->validate($rules, $messages);

        return [
            'page_key' => SitePage::SPMB,
            'kind' => $kind,
            'label' => $step ? trim($data['label']) : null,
            'title' => trim($data['title']),
            'body' => trim($data['body']),
            'position' => (int) $data['position'],
        ];
    }

    private function owned(SiteLine $line, string $kind): SiteLine
    {
        abort_unless($line->page_key === SitePage::SPMB && $line->kind === $kind, 404);

        return $line;
    }

    private function renumber(string $kind): void
    {
        $position = 1;

        foreach ($this->lines($kind) as $line) {
            if ((int) $line->position !== $position) {
                $line->update(['position' => $position]);
            }

            $position++;
        }
    }

    private function lines(string $kind)
    {
        return SiteLine::query()
            ->where('page_key', SitePage::SPMB)
            ->where('kind', $kind)
            ->orderBy('position')
            ->orderBy('id')
            ->get();
    }

    /**
     * @return array<string, mixed>
     */
    private function stepSpec(): array
    {
        return [
            'key' => 'steps',
            'route' => 'admin.spmb.steps',
            'heading' => 'Tahap',
            'add' => 'Tambah tahap',
            'edit' => 'Sunting tahap',
            'delete_confirm' => 'Hapus tahap ini?',
            'empty' => 'Belum ada tahap.',
            'ordered' => true,
            'visible' => false,
            'inline_add' => true,
            'alerts' => false,
            'fields' => [
                ['name' => 'label', 'label' => 'Label tanggal', 'type' => 'text', 'max' => 160, 'message' => 'Label tanggal wajib diisi.'],
                ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'max' => 160, 'message' => 'Judul tahap wajib diisi.'],
                ['name' => 'body', 'label' => 'Penjelasan', 'type' => 'textarea', 'max' => 2000, 'message' => 'Penjelasan tahap wajib diisi.'],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function pathSpec(): array
    {
        return [
            'key' => 'paths',
            'route' => 'admin.spmb.paths',
            'heading' => 'Jalur',
            'add' => 'Tambah jalur',
            'edit' => 'Sunting jalur',
            'delete_confirm' => 'Hapus jalur ini?',
            'empty' => 'Belum ada jalur.',
            'ordered' => true,
            'visible' => false,
            'inline_add' => true,
            'alerts' => false,
            'fields' => [
                ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'max' => 160, 'message' => 'Judul jalur wajib diisi.'],
                ['name' => 'body', 'label' => 'Penjelasan', 'type' => 'textarea', 'max' => 2000, 'message' => 'Penjelasan jalur wajib diisi.'],
            ],
        ];
    }
}
