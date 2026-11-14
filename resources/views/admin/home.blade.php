@extends('layouts.admin')

@section('title', 'Beranda')

@section('summary', 'Ubah section yang tampil di beranda dan atur profil warna situs.')

@section('content')
    <form method="POST" action="{{ route('admin.home.update') }}" class="mx-auto max-w-4xl space-y-8">
        @csrf
        @method('PUT')

        @if (session('status'))
            <p class="rounded-2xl bg-white px-4 py-3 text-sm">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <p class="rounded-2xl bg-white px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</p>
        @endif

        <fieldset class="space-y-4">
            <legend class="text-xl font-semibold">Profil warna</legend>
            <p class="text-sm" style="color: var(--muted)">Setiap profil menyimpan satu set warna. Teks hero dan pita memakai warna yang dipilih. Tombol SPMB tetap kuning.</p>
            @php
                $profileItems = old('profiles', $profiles);
                $activeProfileId = old('active_profile', $activeProfile);
            @endphp
            <div class="space-y-4">
                @foreach ($profileItems as $index => $profile)
                    <article class="profile-card space-y-4 rounded-3xl border border-black/10 bg-white p-5" data-profile>
                        <input type="hidden" name="profiles[{{ $index }}][id]" value="{{ $profile['id'] }}">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <label class="flex items-center gap-2 text-sm font-semibold">
                                <input type="radio" name="active_profile" value="{{ $profile['id'] }}" @checked((string) $activeProfileId === (string) $profile['id'])>
                                Pakai profil ini
                            </label>
                            @if (count($profileItems) > 1)
                                <button type="submit" name="remove_profile" value="{{ $profile['id'] }}" class="text-sm font-medium" style="color: var(--muted)" onclick="return confirm('Hapus profil ini?')">Hapus</button>
                            @endif
                        </div>
                        <label class="block text-sm font-medium">Nama profil
                            <input name="profiles[{{ $index }}][name]" value="{{ $profile['name'] }}" required maxlength="40" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
                        </label>
                        <div class="overflow-hidden rounded-2xl border border-black/10" data-preview>
                            <div class="px-4 py-3 text-sm font-semibold" data-band>Pita hero</div>
                            <div class="space-y-2 px-4 py-3" data-page>
                                <p class="text-sm font-semibold" data-ink>Judul halaman</p>
                                <p class="text-xs" data-muted>Kalimat sekunder</p>
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold text-white" data-accent>Aksen</span>
                            </div>
                            <div class="px-4 py-2 text-xs" data-footer>Footer</div>
                        </div>
                        <div class="grid gap-1 sm:grid-cols-2 sm:gap-x-6">
                            @foreach (\App\Support\ThemeCatalog::FIELDS as $field => $label)
                                <label class="color-row text-sm font-medium" data-color="{{ $field }}">
                                    <span>{{ $label }}</span>
                                    <span class="color-control">
                                        <input type="color" name="profiles[{{ $index }}][{{ $field }}]" value="{{ strtolower($profile[$field]) }}">
                                        <input type="text" data-hex value="{{ strtoupper($profile[$field]) }}" maxlength="7" spellcheck="false" inputmode="text" aria-label="Kode {{ $label }}">
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
            @if (count($profileItems) < \App\Support\ThemeCatalog::MAX_PROFILES)
                <button type="submit" name="add_profile" value="1" class="rounded-full border border-black/10 bg-white px-5 py-2.5 text-sm font-semibold">Tambah profil</button>
            @endif
        </fieldset>

        <div class="space-y-4">
            <h2 class="text-xl font-semibold">Section beranda</h2>
            @foreach ($sections as $section)
                @php
                    $prefix = 'sections.'.$section->id;
                @endphp
                <fieldset class="space-y-3 rounded-3xl bg-white p-5">
                    <legend class="font-semibold">{{ $section->label() }}</legend>
                    @if ($section->key === 'hero')
                        <label class="block text-sm font-medium">Teks di atas judul
                            <input name="sections[{{ $section->id }}][kicker]" value="{{ old($prefix.'.kicker', $section->kicker) }}" maxlength="80" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
                        </label>
                    @endif
                    <label class="block text-sm font-medium">Judul
                        <input name="sections[{{ $section->id }}][title]" value="{{ old($prefix.'.title', $section->title) }}" required class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
                    </label>
                    <label class="block text-sm font-medium">Subjudul
                        <input name="sections[{{ $section->id }}][subtitle]" value="{{ old($prefix.'.subtitle', $section->subtitle) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
                    </label>
                    <label class="block text-sm font-medium">Kalimat singkat
                        <textarea name="sections[{{ $section->id }}][excerpt]" rows="3" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ old($prefix.'.excerpt', $section->excerpt) }}</textarea>
                    </label>
                    <div class="flex flex-wrap items-center gap-4">
                        @if ($section->key === 'hero')
                            <input type="hidden" name="sections[{{ $section->id }}][position]" value="1">
                            <input type="hidden" name="sections[{{ $section->id }}][is_visible]" value="1">
                            <p class="text-sm" style="color: var(--muted)">Hero selalu di urutan pertama dan selalu tampil.</p>
                        @else
                            <label class="text-sm font-medium">Urutan
                                <input type="number" min="2" max="20" name="sections[{{ $section->id }}][position]" value="{{ old($prefix.'.position', $section->position) }}" class="ml-2 w-20 rounded-xl border border-black/10 px-3 py-2">
                            </label>
                            <label class="flex items-center gap-2 text-sm font-medium">
                                <input type="checkbox" name="sections[{{ $section->id }}][is_visible]" value="1" @checked(old('sections') !== null ? (bool) old($prefix.'.is_visible') : $section->is_visible)>
                                Tampil di beranda
                            </label>
                        @endif
                    </div>
                </fieldset>
            @endforeach
        </div>

        <button type="submit" class="rounded-full bg-amber-400 px-6 py-3 font-semibold text-amber-950 ring-4 ring-amber-200">Simpan perubahan</button>
    </form>
    <style>
        .profile-card:has(input[name="active_profile"]:checked) {
            box-shadow: 0 0 0 2px var(--accent);
        }
        .color-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.4rem 0;
        }
        .color-control {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }
        .color-control input[type="color"] {
            -webkit-appearance: none;
            appearance: none;
            width: 2.6rem;
            height: 2.6rem;
            padding: 0;
            border: 0;
            border-radius: 999px;
            background: none;
            cursor: pointer;
            box-shadow: 0 0 0 1px rgb(15 23 42 / 0.12), 0 8px 18px rgb(15 23 42 / 0.08);
        }
        .color-control input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        .color-control input[type="color"]::-webkit-color-swatch {
            border: none;
            border-radius: 999px;
        }
        .color-control input[type="color"]::-moz-color-swatch {
            border: none;
            border-radius: 999px;
        }
        .color-control input[data-hex] {
            width: 6.6rem;
            border-radius: 999px;
            border: 1px solid rgb(15 23 42 / 0.1);
            background: #fff;
            padding: 0.45rem 0.8rem;
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 0.8rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
    </style>
    <script>
        document.querySelectorAll('[data-profile]').forEach((card) => {
            const preview = card.querySelector('[data-preview]');
            const paint = () => {
                const color = (field) => card.querySelector('[data-color="' + field + '"] input[type="color"]').value;
                const band = preview.querySelector('[data-band]');
                const page = preview.querySelector('[data-page]');
                const ink = preview.querySelector('[data-ink]');
                const muted = preview.querySelector('[data-muted]');
                const accent = preview.querySelector('[data-accent]');
                const footer = preview.querySelector('[data-footer]');
                band.style.background = color('hero');
                band.style.color = color('hero_text');
                page.style.background = color('page');
                ink.style.color = color('ink');
                muted.style.color = color('muted');
                accent.style.background = color('accent');
                footer.style.background = color('footer');
                footer.style.color = color('page');
            };
            card.addEventListener('input', (event) => {
                const row = event.target.closest('[data-color]');
                if (!row) {
                    return;
                }
                const picker = row.querySelector('input[type="color"]');
                const hex = row.querySelector('[data-hex]');
                if (event.target === hex) {
                    const value = hex.value.trim();
                    const normalized = /^[0-9A-Fa-f]{6}$/.test(value) ? '#' + value : value;
                    if (/^#[0-9A-Fa-f]{6}$/.test(normalized)) {
                        picker.value = normalized;
                        hex.value = normalized.toUpperCase();
                    }
                } else if (event.target === picker) {
                    hex.value = picker.value.toUpperCase();
                }
                paint();
            });
            paint();
        });
    </script>
@endsection
