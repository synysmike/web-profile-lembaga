<?php

namespace App\Http\Controllers;

use App\Models\HomepageSection;
use App\Support\ThemeCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminHomeController extends Controller
{
    public function edit()
    {
        $state = ThemeCatalog::state();

        return view('admin.home', [
            'sections' => HomepageSection::query()->orderBy('position')->get(),
            'profiles' => $state['items'],
            'activeProfile' => $state['active'],
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'active_profile' => ['required', 'string'],
            'profiles' => ['required', 'array', 'min:1', 'max:'.ThemeCatalog::MAX_PROFILES],
            'profiles.*.id' => ['required', 'regex:/^[a-z0-9]{4,40}$/', 'distinct'],
            'profiles.*.name' => ['required', 'string', 'max:40'],
            'sections' => ['required', 'array'],
            'sections.*.kicker' => ['nullable', 'string', 'max:80'],
            'sections.*.title' => ['required', 'string', 'max:120'],
            'sections.*.subtitle' => ['nullable', 'string', 'max:160'],
            'sections.*.excerpt' => ['nullable', 'string', 'max:300'],
            'sections.*.position' => ['required', 'integer', 'min:1', 'max:20'],
        ];
        $messages = [
            'profiles.*.name.required' => 'Setiap profil perlu nama.',
            'profiles.*.name.max' => 'Nama profil paling panjang 40 karakter.',
            'sections.*.kicker.max' => 'Teks di atas judul paling panjang 80 karakter.',
            'profiles.max' => 'Paling banyak '.ThemeCatalog::MAX_PROFILES.' profil.',
        ];

        foreach (ThemeCatalog::FIELDS as $field => $label) {
            $rules['profiles.*.'.$field] = ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'];
            $messages['profiles.*.'.$field.'.regex'] = $label.' memakai kode warna #RRGGBB.';
        }

        $data = $request->validate($rules, $messages);

        $sections = HomepageSection::query()->get();

        foreach ($sections as $section) {
            if (! isset($data['sections'][$section->id])) {
                return back()->withErrors(['sections' => 'Semua section beranda perlu ikut tersimpan.'])->withInput();
            }
        }

        $profiles = array_values($data['profiles']);
        $active = $data['active_profile'];
        $ids = array_column($profiles, 'id');

        if (! in_array($active, $ids, true)) {
            return back()->withErrors(['active_profile' => 'Pilih profil yang akan dipakai.'])->withInput();
        }

        if ($request->filled('remove_profile')) {
            $remove = (string) $request->input('remove_profile');
            $profiles = array_values(array_filter($profiles, fn (array $profile) => $profile['id'] !== $remove));

            if ($profiles === []) {
                return back()->withErrors(['profiles' => 'Sisakan paling sedikit satu profil.'])->withInput();
            }

            if ($active === $remove) {
                $active = $profiles[0]['id'];
            }
        }

        $added = false;

        if ($request->boolean('add_profile')) {
            if (count($profiles) >= ThemeCatalog::MAX_PROFILES) {
                return back()->withErrors(['profiles' => 'Paling banyak '.ThemeCatalog::MAX_PROFILES.' profil.'])->withInput();
            }

            $source = collect($profiles)->firstWhere('id', $active) ?? $profiles[0];
            $profiles[] = ['id' => ThemeCatalog::newId(), 'name' => 'Profil baru'] + $source;
            $added = true;
        }

        DB::transaction(function () use ($data, $request, $sections, $profiles, $active) {
            ThemeCatalog::save($active, $profiles);

            $payload = $data['sections'];
            $hero = $sections->firstWhere('key', 'hero');
            $others = $sections
                ->where('key', '!=', 'hero')
                ->sortBy(fn (HomepageSection $section) => [
                    (int) $payload[$section->id]['position'],
                    $section->id,
                ]);

            if ($hero) {
                $input = $payload[$hero->id];
                $hero->update([
                    'kicker' => $input['kicker'] ?: null,
                    'title' => $input['title'],
                    'subtitle' => $input['subtitle'] ?: null,
                    'excerpt' => $input['excerpt'] ?: null,
                    'position' => 1,
                    'is_visible' => true,
                ]);
            }

            $position = 2;
            foreach ($others as $section) {
                $input = $payload[$section->id];
                $section->update([
                    'title' => $input['title'],
                    'subtitle' => $input['subtitle'] ?: null,
                    'excerpt' => $input['excerpt'] ?: null,
                    'position' => $position,
                    'is_visible' => $request->boolean('sections.'.$section->id.'.is_visible'),
                ]);
                $position++;
            }
        });

        $status = 'Section beranda dan profil warna sudah disimpan.';

        if ($request->filled('remove_profile')) {
            $status = 'Profil dihapus. Section beranda ikut tersimpan.';
        } elseif ($added) {
            $status = 'Profil baru ditambahkan. Atur warnanya, lalu simpan lagi bila perlu.';
        }

        return redirect()
            ->route('admin.home')
            ->with('status', $status);
    }
}
