<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSchoolController extends Controller
{
    public function edit()
    {
        return view('admin.school', [
            'school' => SchoolProfile::data(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'tagline' => ['nullable', 'string', 'max:180'],
            'address' => ['nullable', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama lembaga wajib diisi.',
            'name.max' => 'Nama lembaga paling panjang 80 karakter.',
            'tagline.max' => 'Tagline paling panjang 180 karakter.',
            'address.max' => 'Alamat paling panjang 160 karakter.',
            'phone.max' => 'Telepon paling panjang 30 karakter.',
            'email.email' => 'Email belum berbentuk alamat surel.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.max' => 'Logo paling besar 2 MB.',
        ]);

        SchoolProfile::save([
            'name' => trim($data['name']),
            'tagline' => trim((string) ($data['tagline'] ?? '')),
            'address' => trim((string) ($data['address'] ?? '')),
            'phone' => trim((string) ($data['phone'] ?? '')),
            'email' => trim((string) ($data['email'] ?? '')),
        ]);

        if ($request->hasFile('logo')) {
            $this->deleteLogo();
            Setting::putValue(SchoolProfile::LOGO, $request->file('logo')->store('konten', 'public'));
        } elseif ($request->boolean('remove_logo')) {
            $this->deleteLogo();
            Setting::forget(SchoolProfile::LOGO);
        }

        return redirect()
            ->route('admin.school')
            ->with('status', 'Identitas lembaga sudah disimpan.');
    }

    private function deleteLogo(): void
    {
        $path = SchoolProfile::storedLogoPath();

        if (is_string($path) && $path !== '') {
            Storage::disk('public')->delete($path);
        }
    }
}
