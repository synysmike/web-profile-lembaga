<div class="space-y-3">
    @foreach ($items as $line)
        <div class="grid gap-3 rounded-2xl border border-black/5 p-4 md:grid-cols-{{ count($columns) > 2 ? '2' : count($columns) }}">
            @foreach ($columns as $column)
                <label class="block text-sm font-medium {{ $column['type'] === 'textarea' ? 'md:col-span-2' : '' }}">{{ $column['label'] }}
                    @if ($column['type'] === 'textarea')
                        <textarea name="{{ $group }}[items][{{ $line->id }}][{{ $column['name'] }}]" rows="2" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ old($group.'.items.'.$line->id.'.'.$column['name'], $line->{$column['name']}) }}</textarea>
                    @else
                        <input name="{{ $group }}[items][{{ $line->id }}][{{ $column['name'] }}]" value="{{ old($group.'.items.'.$line->id.'.'.$column['name'], $line->{$column['name']}) }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
                    @endif
                </label>
            @endforeach
            <label class="block text-sm font-medium">Urutan
                <input type="number" min="1" name="{{ $group }}[items][{{ $line->id }}][position]" value="{{ old($group.'.items.'.$line->id.'.position', $line->position) }}" class="mt-1 w-24 rounded-2xl border border-black/10 px-4 py-3">
            </label>
            <label class="flex items-center gap-2 text-sm font-medium md:self-end md:pb-3">
                <input type="checkbox" name="{{ $group }}[items][{{ $line->id }}][delete]" value="1" @checked(old($group.'.items.'.$line->id.'.delete'))>
                Hapus
            </label>
        </div>
    @endforeach
</div>
<div id="new-{{ $group }}" class="mt-3 space-y-3">
    @foreach (old($group.'.new', [[]]) as $index => $row)
        <div data-row class="grid gap-3 rounded-2xl border border-dashed border-black/10 p-4 md:grid-cols-2">
            @foreach ($columns as $column)
                <label class="block text-sm font-medium {{ $column['type'] === 'textarea' ? 'md:col-span-2' : '' }}">{{ $column['label'] }}
                    @if ($column['type'] === 'textarea')
                        <textarea name="{{ $group }}[new][{{ $index }}][{{ $column['name'] }}]" rows="2" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">{{ $row[$column['name']] ?? '' }}</textarea>
                    @else
                        <input name="{{ $group }}[new][{{ $index }}][{{ $column['name'] }}]" value="{{ $row[$column['name']] ?? '' }}" class="mt-1 w-full rounded-2xl border border-black/10 px-4 py-3">
                    @endif
                </label>
            @endforeach
            <label class="block text-sm font-medium">Urutan
                <input type="number" min="1" name="{{ $group }}[new][{{ $index }}][position]" value="{{ $row['position'] ?? ($items->count() + $index + 1) }}" class="mt-1 w-24 rounded-2xl border border-black/10 px-4 py-3">
            </label>
        </div>
    @endforeach
</div>
<button type="button" data-add="new-{{ $group }}" class="mt-3 rounded-full bg-white px-4 py-2 text-sm font-semibold" style="color: var(--accent)">{{ $addLabel }}</button>
