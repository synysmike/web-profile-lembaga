@php
    $tableKey = $spec['key'] ?? 'item';
    $tracksVisibility = $spec['visible'] ?? true;
    $inlineAdd = $spec['inline_add'] ?? false;
    $showAlerts = $spec['alerts'] ?? true;
    $file = $spec['file'] ?? null;
    $fileKind = is_array($file) && (($file['kind'] ?? '') === 'image' || ($file['name'] ?? '') === 'image') ? 'image' : 'file';
    $filePath = is_array($file) ? ($file['path'] ?? ($fileKind === 'image' ? 'image_path' : 'file_path')) : 'image_path';
    $fileUrl = is_array($file) ? ($file['url'] ?? ($fileKind === 'image' ? 'imageUrl' : null)) : null;
    $fileState = function ($item) use ($file, $fileKind, $filePath, $fileUrl) {
        if (! $item || ! $file) {
            return ['has' => false, 'preview' => null, 'note' => ''];
        }

        $has = filled($item->{$filePath});

        return [
            'has' => $has,
            'preview' => $fileUrl ? $item->{$fileUrl}() : null,
            'note' => $fileKind !== 'image' && $has ? 'Berkas PDF sudah tersimpan.' : '',
        ];
    };
    $ownsOld = old('table') === $tableKey;
    $editingId = $ownsOld ? old('editing') : null;
    $editingItem = is_numeric($editingId) ? $items->firstWhere('id', (int) $editingId) : null;
    $editingFile = $fileState($editingItem);
    $editingPreview = $editingFile['preview'];
    $editingHasFile = $editingFile['has'];
    $editingNote = $editingFile['note'];
    $columns = ($spec['ordered'] ? 1 : 0) + count($spec['fields']) + ($file ? 1 : 0) + ($tracksVisibility ? 1 : 0) + 1;
@endphp

<div data-table="{{ $tableKey }}" class="space-y-3">
    @if ($showAlerts)
        @include('partials.admin-alerts')
    @endif

    <div class="overflow-hidden rounded-2xl bg-white">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-black/5 px-4 py-3">
            <input id="table-search-{{ $tableKey }}" type="search" placeholder="Cari di tabel" class="w-full max-w-xs rounded-full border border-black/10 px-4 py-2 text-sm">
            <div class="flex items-center gap-3">
                <p class="text-sm" style="color: var(--muted)">{{ $items->count() }} data</p>
                @if ($inlineAdd)
                    <button type="button" id="open-{{ $tableKey }}" class="inline-flex rounded-full bg-amber-400 px-4 py-2 text-sm font-semibold text-amber-950">{{ $spec['add'] }}</button>
                @endif
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[40rem] text-left text-sm">
                <thead>
                    <tr class="border-b border-black/5 text-xs uppercase tracking-wide" style="color: var(--muted)">
                        @if ($spec['ordered'])
                            <th class="px-4 py-3 font-semibold">Urutan</th>
                        @endif
                        @foreach ($spec['fields'] as $field)
                            <th class="px-4 py-3 font-semibold">{{ $field['label'] }}</th>
                        @endforeach
                        @if ($file)
                            <th class="px-4 py-3 font-semibold">{{ $file['column'] }}</th>
                        @endif
                        @if ($tracksVisibility)
                            <th class="px-4 py-3 font-semibold">Status</th>
                        @endif
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        @php
                            $state = $fileState($item);
                            $preview = $state['preview'];
                            $hasFile = $state['has'];
                            $fileNote = $state['note'];
                            $payload = [
                                'id' => $item->id,
                                'position' => $item->position,
                                'has_file' => $hasFile,
                                'preview' => $preview,
                                'file_note' => $fileNote,
                            ];
                            $search = [];
                            if ($tracksVisibility) {
                                $payload['is_visible'] = (bool) $item->is_visible;
                                $search[] = $item->is_visible ? 'Tampil' : 'Disembunyikan';
                            }
                            if ($spec['ordered']) {
                                $search[] = $item->position;
                            }
                            foreach ($spec['fields'] as $field) {
                                $current = $item->{$field['name']};
                                if ($current instanceof \Carbon\CarbonInterface) {
                                    $current = $current->toDateString();
                                }
                                $payload[$field['name']] = $current;
                                $search[] = $field['type'] === 'date' ? \App\Support\LocalDate::long($current) : $current;
                            }
                        @endphp
                        <tr data-row data-search="{{ \Illuminate\Support\Str::lower(implode(' ', $search)) }}" class="border-b border-black/5 last:border-b-0">
                            @if ($spec['ordered'])
                                <td class="px-4 py-3">{{ $item->position }}</td>
                            @endif
                            @foreach ($spec['fields'] as $field)
                                <td class="max-w-xs px-4 py-3">
                                    @if ($field['type'] === 'date')
                                        <span class="whitespace-nowrap">{{ \App\Support\LocalDate::long($item->{$field['name']}) }}</span>
                                    @elseif ($field['type'] === 'textarea')
                                        <span class="line-clamp-2">{{ $item->{$field['name']} }}</span>
                                    @else
                                        {{ $item->{$field['name']} }}
                                    @endif
                                </td>
                            @endforeach
                            @if ($file)
                                <td class="px-4 py-3">
                                    @if ($fileKind === 'image')
                                        @if ($preview)
                                            <img src="{{ $preview }}" alt="" class="h-10 w-14 rounded-lg object-cover">
                                        @else
                                            <span style="color: var(--muted)">Tanpa foto</span>
                                        @endif
                                    @else
                                        <span style="color: var(--muted)">{{ $hasFile ? 'Ada berkas' : 'Belum ada' }}</span>
                                    @endif
                                </td>
                            @endif
                            @if ($tracksVisibility)
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold" @if ($item->is_visible) style="background: color-mix(in srgb, var(--accent) 14%, white); color: var(--accent)" @else style="background: #f1f5f9; color: var(--muted)" @endif>{{ $item->is_visible ? 'Tampil' : 'Disembunyikan' }}</span>
                                </td>
                            @endif
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" class="rounded-full px-3 py-1.5 text-sm font-semibold text-white" style="background: var(--accent)" data-edit='@json($payload)' data-url="{{ route($spec['route'].'.update', $item) }}">Sunting</button>
                                    <form method="POST" action="{{ route($spec['route'].'.destroy', $item) }}" onsubmit="return confirm(@json($spec['delete_confirm']))">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full border border-black/10 px-3 py-1.5 text-sm font-semibold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $columns }}" class="px-4 py-8" style="color: var(--muted)">{{ $spec['empty'] }}</td>
                        </tr>
                    @endforelse
                    <tr id="filter-empty-{{ $tableKey }}" hidden>
                        <td colspan="{{ $columns }}" class="px-4 py-8" style="color: var(--muted)">Tidak ada data yang cocok.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <dialog id="item-modal-{{ $tableKey }}" class="admin-modal">
        <form id="item-form-{{ $tableKey }}" method="POST" enctype="multipart/form-data" action="{{ $editingItem ? route($spec['route'].'.update', $editingItem) : route($spec['route'].'.store') }}" data-store="{{ route($spec['route'].'.store') }}" data-add-label="{{ $spec['add'] }}" data-edit-label="{{ $spec['edit'] }}" data-next-position="{{ ((int) $items->max('position')) + 1 }}" data-file-required="{{ ($file['required'] ?? false) ? '1' : '0' }}" class="p-6">
            @csrf
            <input type="hidden" name="_method" value="{{ $editingItem ? 'PUT' : 'POST' }}">
            <input type="hidden" name="table" value="{{ $tableKey }}">
            <input type="hidden" name="editing" value="{{ $editingId ?: 'new' }}">
            <div class="flex items-start justify-between gap-4">
                <h2 id="modal-title-{{ $tableKey }}" class="text-xl font-semibold">{{ $editingItem ? $spec['edit'] : $spec['add'] }}</h2>
                <button type="button" data-cancel class="rounded-full px-3 py-1 text-sm font-semibold" style="color: var(--muted)">Tutup</button>
            </div>
            <div class="mt-4 space-y-4">
                @foreach ($spec['fields'] as $field)
                    @php
                        $fallback = $editingItem?->{$field['name']};
                        if ($fallback instanceof \Carbon\CarbonInterface) {
                            $fallback = $fallback->toDateString();
                        }
                        $fieldValue = $ownsOld ? old($field['name'], $fallback) : $fallback;
                    @endphp
                    <label class="block text-sm font-medium">{{ $field['label'] }}
                        @if ($field['type'] === 'textarea')
                            <textarea data-field="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="4" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ $fieldValue }}</textarea>
                        @else
                            <input data-field="{{ $field['name'] }}" type="{{ $field['type'] === 'date' ? 'date' : 'text' }}" name="{{ $field['name'] }}" value="{{ $fieldValue }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
                        @endif
                    </label>
                    @if ($ownsOld)
                        @error($field['name'])
                            <p data-error class="-mt-2 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    @endif
                @endforeach
                @if ($spec['ordered'])
                    <label class="block text-sm font-medium">Urutan
                        <input data-field="position" type="number" min="1" max="999" name="position" value="{{ $ownsOld ? old('position', $editingItem?->position ?? (((int) $items->max('position')) + 1)) : ($editingItem?->position ?? (((int) $items->max('position')) + 1)) }}" class="mt-1 w-28 rounded-2xl border border-black/10 px-4 py-3">
                    </label>
                    @if ($ownsOld)
                        @error('position')
                            <p data-error class="-mt-2 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    @endif
                @endif
                @if ($file)
                    <div id="current-file-{{ $tableKey }}" @unless ($editingHasFile) hidden @endunless>
                        @if ($fileKind === 'image')
                            <img alt="" class="mb-2 h-28 w-full rounded-2xl object-cover" @if ($editingPreview) src="{{ $editingPreview }}" @else hidden @endif>
                        @endif
                        <p data-note class="text-sm" style="color: var(--muted)" @unless ($editingNote !== '') hidden @endunless>{{ $editingNote }}</p>
                    </div>
                    <label class="block text-sm font-medium">{{ $file['label'] }}
                        <input data-file type="file" name="{{ $file['name'] }}" accept="{{ $file['accept'] }}" class="mt-2 block w-full text-sm" @if (($file['required'] ?? false) && ! $editingItem) required @endif>
                    </label>
                    <p class="text-sm" style="color: var(--muted)">{{ $file['hint'] }}</p>
                    @if ($ownsOld)
                        @error($file['name'])
                            <p data-error class="text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    @endif
                    <label id="remove-file-wrap-{{ $tableKey }}" class="flex items-center gap-2 text-sm font-medium" @unless ($editingHasFile) hidden @endunless>
                        <input type="checkbox" name="{{ $file['remove'] }}" value="1" @checked($ownsOld && old($file['remove']))>
                        {{ $file['remove_label'] }}
                    </label>
                @endif
                @if ($tracksVisibility)
                    <label class="flex items-center gap-2 text-sm font-medium">
                        <input data-field="is_visible" type="checkbox" name="is_visible" value="1" @checked($ownsOld ? old('is_visible') : ($editingItem->is_visible ?? true))>
                        Tampil di situs
                    </label>
                @endif
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" data-cancel class="rounded-full border border-black/10 px-4 py-2 text-sm font-semibold">Batal</button>
                <button type="submit" class="rounded-full bg-amber-400 px-5 py-2 text-sm font-semibold text-amber-950">Simpan</button>
            </div>
        </form>
    </dialog>
</div>

@once
    <style>
        dialog.admin-modal {
            position: fixed;
            inset: 0;
            width: min(34rem, calc(100% - 2rem));
            height: fit-content;
            max-height: calc(100% - 2rem);
            margin: auto;
            border: 0;
            border-radius: 1.25rem;
            padding: 0;
            color: var(--ink);
            background: white;
        }
        dialog.admin-modal::backdrop { background: rgb(15 23 42 / 0.45); }
        dialog.admin-modal form { max-height: calc(100vh - 2rem); overflow: auto; }
    </style>
@endonce
<script>
    (() => {
        const root = document.querySelector('[data-table="{{ $tableKey }}"]');
        const dialog = document.getElementById('item-modal-{{ $tableKey }}');
        const form = document.getElementById('item-form-{{ $tableKey }}');
        const method = form.querySelector('[name="_method"]');
        const editing = form.querySelector('[name="editing"]');
        const title = document.getElementById('modal-title-{{ $tableKey }}');

        const fill = (item) => {
            form.querySelectorAll('[data-field]').forEach((input) => {
                if (input.type === 'file') {
                    input.value = '';
                    return;
                }
                if (input.type === 'checkbox') {
                    input.checked = !!item[input.dataset.field];
                    return;
                }
                input.value = item[input.dataset.field] ?? '';
            });
            const current = document.getElementById('current-file-{{ $tableKey }}');
            if (current) {
                current.hidden = !item.has_file;
                const image = current.querySelector('img');
                const note = current.querySelector('[data-note]');
                if (image) {
                    image.hidden = !item.preview;
                    if (item.preview) image.src = item.preview;
                }
                if (note) {
                    note.hidden = !item.file_note;
                    note.textContent = item.file_note || '';
                }
            }
            const remove = document.getElementById('remove-file-wrap-{{ $tableKey }}');
            if (remove) {
                remove.hidden = !item.has_file;
                const box = remove.querySelector('input');
                if (box) box.checked = false;
            }
            const file = form.querySelector('[data-file]');
            if (file) file.required = form.dataset.fileRequired === '1' && String(item.id || '') === '';
            title.textContent = item.id ? form.dataset.editLabel : form.dataset.addLabel;
        };

        document.getElementById('open-{{ $tableKey }}').addEventListener('click', () => {
            form.querySelectorAll('[data-error]').forEach((node) => node.remove());
            form.action = form.dataset.store;
            method.value = 'POST';
            editing.value = 'new';
            fill({ is_visible: true, position: form.dataset.nextPosition, has_file: false });
            dialog.showModal();
        });

        root.querySelectorAll('[data-edit]').forEach((button) => {
            button.addEventListener('click', () => {
                form.querySelectorAll('[data-error]').forEach((node) => node.remove());
                const item = JSON.parse(button.dataset.edit);
                form.action = button.dataset.url;
                method.value = 'PUT';
                editing.value = String(item.id);
                fill(item);
                dialog.showModal();
            });
        });

        form.querySelectorAll('[data-cancel]').forEach((button) => {
            button.addEventListener('click', () => dialog.close());
        });
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) dialog.close();
        });

        const search = document.getElementById('table-search-{{ $tableKey }}');
        const rows = [...root.querySelectorAll('tbody [data-row]')];
        const empty = document.getElementById('filter-empty-{{ $tableKey }}');
        search.addEventListener('input', () => {
            const query = search.value.trim().toLowerCase();
            let shown = 0;
            rows.forEach((row) => {
                const match = row.dataset.search.includes(query);
                row.hidden = !match;
                if (match) shown += 1;
            });
            empty.hidden = shown !== 0 || rows.length === 0;
        });

        @if ($errors->any() && $ownsOld)
            dialog.showModal();
        @endif
    })();
</script>
