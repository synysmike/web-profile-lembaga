<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\GalleryItem;
use App\Models\StaffMember;
use App\Support\PublicFile;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class AdminMediaListController extends Controller
{
    public function editGallery()
    {
        return view('admin.datatable', [
            'spec' => $this->gallerySpec(),
            'items' => GalleryItem::query()->ordered()->get(),
        ]);
    }

    public function storeGallery(Request $request)
    {
        $data = $this->validateGallery($request);

        GalleryItem::query()->create([
            'title' => trim($data['title']),
            'image_path' => $request->file('image') instanceof UploadedFile ? $request->file('image')->store('halaman', 'public') : null,
            'position' => (int) $data['position'],
            'is_visible' => $request->boolean('is_visible'),
        ]);
        $this->renumber(GalleryItem::class);

        return redirect()->route('admin.gallery')->with('status', 'Galeri sudah disimpan.');
    }

    public function updateGallery(Request $request, GalleryItem $item)
    {
        $data = $this->validateGallery($request);
        $item->title = trim($data['title']);
        $item->position = (int) $data['position'];
        $item->is_visible = $request->boolean('is_visible');
        $file = $request->file('image');

        if ($file instanceof UploadedFile) {
            PublicFile::delete($item->image_path);
            $item->image_path = $file->store('halaman', 'public');
        } elseif ($request->boolean('remove_image')) {
            PublicFile::delete($item->image_path);
            $item->image_path = null;
        }

        $item->save();
        $this->renumber(GalleryItem::class);

        return redirect()->route('admin.gallery')->with('status', 'Galeri sudah disimpan.');
    }

    public function destroyGallery(GalleryItem $item)
    {
        PublicFile::delete($item->image_path);
        $item->delete();
        $this->renumber(GalleryItem::class);

        return redirect()->route('admin.gallery')->with('status', 'Foto sudah dihapus.');
    }

    public function editStaff()
    {
        return view('admin.datatable', [
            'spec' => $this->staffSpec(),
            'items' => StaffMember::query()->ordered()->get(),
        ]);
    }

    public function storeStaff(Request $request)
    {
        $data = $this->validateStaff($request);

        StaffMember::query()->create([
            'name' => trim($data['name']),
            'role' => trim($data['role']),
            'photo_path' => $request->file('photo') instanceof UploadedFile ? $request->file('photo')->store('halaman', 'public') : null,
            'position' => (int) $data['position'],
            'is_visible' => $request->boolean('is_visible'),
        ]);
        $this->renumber(StaffMember::class);

        return redirect()->route('admin.staff')->with('status', 'Guru dan staf sudah disimpan.');
    }

    public function updateStaff(Request $request, StaffMember $staff)
    {
        $data = $this->validateStaff($request);
        $staff->name = trim($data['name']);
        $staff->role = trim($data['role']);
        $staff->position = (int) $data['position'];
        $staff->is_visible = $request->boolean('is_visible');
        $photo = $request->file('photo');

        if ($photo instanceof UploadedFile) {
            PublicFile::delete($staff->photo_path);
            $staff->photo_path = $photo->store('halaman', 'public');
        } elseif ($request->boolean('remove_photo')) {
            PublicFile::delete($staff->photo_path);
            $staff->photo_path = null;
        }

        $staff->save();
        $this->renumber(StaffMember::class);

        return redirect()->route('admin.staff')->with('status', 'Guru dan staf sudah disimpan.');
    }

    public function destroyStaff(StaffMember $staff)
    {
        PublicFile::delete($staff->photo_path);
        $staff->delete();
        $this->renumber(StaffMember::class);

        return redirect()->route('admin.staff')->with('status', 'Guru atau staf sudah dihapus.');
    }

    public function editDownloads()
    {
        return view('admin.datatable', [
            'spec' => $this->downloadSpec(),
            'items' => Download::query()->ordered()->get(),
        ]);
    }

    public function storeDownload(Request $request)
    {
        $data = $this->validateDownload($request, true);

        Download::query()->create([
            'title' => trim($data['title']),
            'file_path' => $request->file('file')->store('halaman', 'public'),
            'position' => (int) $data['position'],
            'is_visible' => $request->boolean('is_visible'),
        ]);
        $this->renumber(Download::class);

        return redirect()->route('admin.downloads')->with('status', 'Unduhan sudah disimpan.');
    }

    public function updateDownload(Request $request, Download $download)
    {
        $data = $this->validateDownload($request, false);
        $download->title = trim($data['title']);
        $download->position = (int) $data['position'];
        $download->is_visible = $request->boolean('is_visible');
        $file = $request->file('file');

        if ($file instanceof UploadedFile) {
            PublicFile::delete($download->file_path);
            $download->file_path = $file->store('halaman', 'public');
        } elseif ($request->boolean('remove_file')) {
            PublicFile::delete($download->file_path);
            $download->file_path = null;
        }

        $download->save();
        $this->renumber(Download::class);

        return redirect()->route('admin.downloads')->with('status', 'Unduhan sudah disimpan.');
    }

    public function destroyDownload(Download $download)
    {
        PublicFile::delete($download->file_path);
        $download->delete();
        $this->renumber(Download::class);

        return redirect()->route('admin.downloads')->with('status', 'Unduhan sudah dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function gallerySpec(): array
    {
        return [
            'route' => 'admin.gallery',
            'heading' => 'Galeri',
            'summary' => 'Judul dan satu foto. Tanpa foto, kartu tetap memakai warna pita.',
            'add' => 'Tambah foto',
            'edit' => 'Sunting foto',
            'delete_confirm' => 'Hapus foto ini?',
            'empty' => 'Belum ada foto.',
            'ordered' => true,
            'fields' => [
                ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'max' => 160, 'message' => 'Judul wajib diisi.'],
            ],
            'file' => [
                'name' => 'image',
                'label' => 'Foto',
                'column' => 'Foto',
                'accept' => 'image/jpeg,image/png,image/webp',
                'hint' => 'JPG, PNG, atau WebP, paling besar 5 MB. Kosongkan jika kartu cukup memakai warna pita.',
                'required' => false,
                'remove' => 'remove_image',
                'remove_label' => 'Hapus foto',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function downloadSpec(): array
    {
        return [
            'route' => 'admin.downloads',
            'heading' => 'Unduhan',
            'summary' => 'Judul dan berkas PDF. Tombol unduh di situs mengirim berkas yang tersimpan.',
            'add' => 'Tambah unduhan',
            'edit' => 'Sunting unduhan',
            'delete_confirm' => 'Hapus unduhan ini?',
            'empty' => 'Belum ada unduhan.',
            'ordered' => true,
            'fields' => [
                ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'max' => 160, 'message' => 'Judul wajib diisi.'],
            ],
            'file' => [
                'name' => 'file',
                'label' => 'Berkas PDF',
                'column' => 'Berkas',
                'accept' => 'application/pdf',
                'hint' => 'PDF, paling besar 8 MB. Wajib saat menambah unduhan baru.',
                'required' => true,
                'remove' => 'remove_file',
                'remove_label' => 'Hapus berkas',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateGallery(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'position' => ['required', 'integer', 'min:1', 'max:999'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul paling panjang 160 karakter.',
            'position.required' => 'Urutan wajib diisi.',
            'position.integer' => 'Urutan harus berupa angka.',
            'position.min' => 'Urutan paling kecil 1.',
            'position.max' => 'Urutan paling besar 999.',
            'image.image' => 'Gambar harus berupa JPG, PNG, atau WebP.',
            'image.mimes' => 'Gambar harus berupa JPG, PNG, atau WebP.',
            'image.max' => 'Gambar paling besar 5 MB.',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateDownload(Request $request, bool $creating): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'position' => ['required', 'integer', 'min:1', 'max:999'],
            'file' => array_filter([
                $creating ? 'required' : 'nullable',
                'file',
                'mimes:pdf',
                'max:8192',
            ]),
        ], [
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul paling panjang 160 karakter.',
            'position.required' => 'Urutan wajib diisi.',
            'position.integer' => 'Urutan harus berupa angka.',
            'position.min' => 'Urutan paling kecil 1.',
            'position.max' => 'Urutan paling besar 999.',
            'file.required' => 'Berkas PDF wajib diunggah.',
            'file.mimes' => 'Berkas harus berupa PDF.',
            'file.max' => 'Berkas paling besar 8 MB.',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function staffSpec(): array
    {
        return [
            'route' => 'admin.staff',
            'heading' => 'Guru dan staf',
            'summary' => 'Nama, jabatan, dan foto. Tanpa foto, kartu menampilkan huruf awal nama.',
            'add' => 'Tambah orang',
            'edit' => 'Sunting orang',
            'delete_confirm' => 'Hapus orang ini?',
            'empty' => 'Belum ada guru atau staf.',
            'ordered' => true,
            'fields' => [
                ['name' => 'name', 'label' => 'Nama', 'type' => 'text', 'max' => 160, 'message' => 'Nama wajib diisi.'],
                ['name' => 'role', 'label' => 'Jabatan', 'type' => 'text', 'max' => 160, 'message' => 'Jabatan wajib diisi.'],
            ],
            'file' => [
                'name' => 'photo',
                'kind' => 'image',
                'path' => 'photo_path',
                'url' => 'photoUrl',
                'label' => 'Foto',
                'column' => 'Foto',
                'accept' => 'image/jpeg,image/png,image/webp',
                'hint' => 'JPG, PNG, atau WebP, paling besar 5 MB. Kosongkan bila kartu cukup menampilkan huruf awal.',
                'required' => false,
                'remove' => 'remove_photo',
                'remove_label' => 'Hapus foto',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateStaff(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'role' => ['required', 'string', 'max:160'],
            'position' => ['required', 'integer', 'min:1', 'max:999'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama paling panjang 160 karakter.',
            'role.required' => 'Jabatan wajib diisi.',
            'role.max' => 'Jabatan paling panjang 160 karakter.',
            'position.required' => 'Urutan wajib diisi.',
            'position.integer' => 'Urutan harus berupa angka.',
            'position.min' => 'Urutan paling kecil 1.',
            'position.max' => 'Urutan paling besar 999.',
            'photo.image' => 'Foto harus berupa JPG, PNG, atau WebP.',
            'photo.mimes' => 'Foto harus berupa JPG, PNG, atau WebP.',
            'photo.max' => 'Foto paling besar 5 MB.',
        ]);
    }

    /**
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $class
     */
    private function renumber(string $class): void
    {
        $position = 1;

        foreach ($class::query()->orderBy('position')->orderBy('id')->get() as $record) {
            if ((int) $record->position !== $position) {
                $record->update(['position' => $position]);
            }

            $position++;
        }
    }
}
