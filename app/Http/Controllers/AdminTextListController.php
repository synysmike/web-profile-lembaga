<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AdminTextListController extends Controller
{
    public function editAgenda()
    {
        return $this->form('agenda');
    }

    public function storeAgenda(Request $request)
    {
        return $this->store($request, 'agenda');
    }

    public function updateAgenda(Request $request, Agenda $agenda)
    {
        return $this->update($request, 'agenda', $agenda);
    }

    public function destroyAgenda(Agenda $agenda)
    {
        return $this->destroy('agenda', $agenda);
    }

    public function editAnnouncements()
    {
        return $this->form('pengumuman');
    }

    public function storeAnnouncement(Request $request)
    {
        return $this->store($request, 'pengumuman');
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        return $this->update($request, 'pengumuman', $announcement);
    }

    public function destroyAnnouncement(Announcement $announcement)
    {
        return $this->destroy('pengumuman', $announcement);
    }

    public function editAchievements()
    {
        return $this->form('prestasi');
    }

    public function storeAchievement(Request $request)
    {
        return $this->store($request, 'prestasi');
    }

    public function updateAchievement(Request $request, Achievement $achievement)
    {
        return $this->update($request, 'prestasi', $achievement);
    }

    public function destroyAchievement(Achievement $achievement)
    {
        return $this->destroy('prestasi', $achievement);
    }

    public function editFacilities()
    {
        return $this->form('fasilitas');
    }

    public function storeFacility(Request $request)
    {
        return $this->store($request, 'fasilitas');
    }

    public function updateFacility(Request $request, Facility $facility)
    {
        return $this->update($request, 'fasilitas', $facility);
    }

    public function destroyFacility(Facility $facility)
    {
        return $this->destroy('fasilitas', $facility);
    }

    private function form(string $key)
    {
        $spec = $this->spec($key);

        return view('admin.datatable', [
            'spec' => $spec,
            'items' => $spec['model']::query()->when(
                $spec['ordered'],
                fn ($query) => $query->ordered(),
                fn ($query) => $query->orderByDesc('happens_on')->orderByDesc('id')
            )->get(),
        ]);
    }

    private function store(Request $request, string $key)
    {
        $spec = $this->spec($key);
        $spec['model']::query()->create($this->payload($request, $spec));
        $this->renumber($spec);

        return redirect()->route($spec['route'])->with('status', $spec['saved']);
    }

    private function update(Request $request, string $key, Model $record)
    {
        $spec = $this->spec($key);
        $record->update($this->payload($request, $spec));
        $this->renumber($spec);

        return redirect()->route($spec['route'])->with('status', $spec['saved']);
    }

    private function destroy(string $key, Model $record)
    {
        $spec = $this->spec($key);
        $record->delete();
        $this->renumber($spec);

        return redirect()->route($spec['route'])->with('status', $spec['deleted']);
    }

    /**
     * @param  array<string, mixed>  $spec
     * @return array<string, mixed>
     */
    private function payload(Request $request, array $spec): array
    {
        $rules = [];
        $messages = [];

        foreach ($spec['fields'] as $field) {
            $rules[$field['name']] = $field['type'] === 'date'
                ? ['required', 'date']
                : ['required', 'string', 'max:'.$field['max']];
            $messages[$field['name'].'.required'] = $field['message'];
            $messages[$field['name'].'.date'] = 'Tanggal belum sesuai.';
            $messages[$field['name'].'.max'] = $field['label'].' terlalu panjang.';
        }

        if ($spec['ordered']) {
            $rules['position'] = ['required', 'integer', 'min:1', 'max:999'];
            $messages['position.required'] = 'Urutan wajib diisi.';
            $messages['position.integer'] = 'Urutan harus berupa angka.';
            $messages['position.min'] = 'Urutan paling kecil 1.';
            $messages['position.max'] = 'Urutan paling besar 999.';
        }

        $data = $request->validate($rules, $messages);
        $payload = ['is_visible' => $request->boolean('is_visible')];

        foreach ($spec['fields'] as $field) {
            $payload[$field['name']] = $field['type'] === 'date'
                ? $data[$field['name']]
                : trim($data[$field['name']]);
        }

        if ($spec['ordered']) {
            $payload['position'] = (int) $data['position'];
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $spec
     */
    private function renumber(array $spec): void
    {
        if (! $spec['ordered']) {
            return;
        }

        $position = 1;

        foreach ($spec['model']::query()->orderBy('position')->orderBy('id')->get() as $record) {
            if ((int) $record->position !== $position) {
                $record->update(['position' => $position]);
            }

            $position++;
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function spec(string $key): array
    {
        $specs = [
            'agenda' => [
                'model' => Agenda::class,
                'route' => 'admin.agenda',
                'heading' => 'Agenda',
                'summary' => 'Tanggal, judul, dan tempat. Halaman publik mengurutkan kegiatan yang paling dekat.',
                'saved' => 'Agenda sudah disimpan.',
                'deleted' => 'Agenda sudah dihapus.',
                'add' => 'Tambah agenda',
                'edit' => 'Sunting agenda',
                'delete_confirm' => 'Hapus agenda ini?',
                'empty' => 'Belum ada agenda.',
                'ordered' => false,
                'fields' => [
                    ['name' => 'happens_on', 'label' => 'Tanggal', 'type' => 'date', 'max' => 10, 'message' => 'Tanggal wajib diisi.'],
                    ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'max' => 160, 'message' => 'Judul wajib diisi.'],
                    ['name' => 'place', 'label' => 'Tempat', 'type' => 'text', 'max' => 160, 'message' => 'Tempat wajib diisi.'],
                ],
            ],
            'pengumuman' => [
                'model' => Announcement::class,
                'route' => 'admin.announcements',
                'heading' => 'Pengumuman',
                'summary' => 'Judul dan isi pengumuman. Urutan di sini dipakai di halaman publik.',
                'saved' => 'Pengumuman sudah disimpan.',
                'deleted' => 'Pengumuman sudah dihapus.',
                'add' => 'Tambah pengumuman',
                'edit' => 'Sunting pengumuman',
                'delete_confirm' => 'Hapus pengumuman ini?',
                'empty' => 'Belum ada pengumuman.',
                'ordered' => true,
                'fields' => [
                    ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'max' => 160, 'message' => 'Judul wajib diisi.'],
                    ['name' => 'body', 'label' => 'Isi', 'type' => 'textarea', 'max' => 2000, 'message' => 'Isi wajib diisi.'],
                ],
            ],
            'prestasi' => [
                'model' => Achievement::class,
                'route' => 'admin.achievements',
                'heading' => 'Prestasi',
                'summary' => 'Peringkat dan judul prestasi. Urutan di sini dipakai di halaman publik dan cuplikan beranda.',
                'saved' => 'Prestasi sudah disimpan.',
                'deleted' => 'Prestasi sudah dihapus.',
                'add' => 'Tambah prestasi',
                'edit' => 'Sunting prestasi',
                'delete_confirm' => 'Hapus prestasi ini?',
                'empty' => 'Belum ada prestasi.',
                'ordered' => true,
                'fields' => [
                    ['name' => 'rank', 'label' => 'Peringkat', 'type' => 'text', 'max' => 40, 'message' => 'Peringkat wajib diisi.'],
                    ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'max' => 160, 'message' => 'Judul wajib diisi.'],
                ],
            ],
            'fasilitas' => [
                'model' => Facility::class,
                'route' => 'admin.facilities',
                'heading' => 'Fasilitas',
                'summary' => 'Judul dan penjelasan fasilitas. Urutan di sini dipakai di halaman publik.',
                'saved' => 'Fasilitas sudah disimpan.',
                'deleted' => 'Fasilitas sudah dihapus.',
                'add' => 'Tambah fasilitas',
                'edit' => 'Sunting fasilitas',
                'delete_confirm' => 'Hapus fasilitas ini?',
                'empty' => 'Belum ada fasilitas.',
                'ordered' => true,
                'fields' => [
                    ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'max' => 160, 'message' => 'Judul wajib diisi.'],
                    ['name' => 'description', 'label' => 'Penjelasan', 'type' => 'textarea', 'max' => 500, 'message' => 'Penjelasan wajib diisi.'],
                ],
            ],
        ];

        return $specs[$key];
    }
}
