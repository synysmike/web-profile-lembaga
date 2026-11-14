<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

#[Fillable(['page_key', 'kind', 'position', 'label', 'title', 'body'])]
class SiteLine extends Model
{
    public const TRACE = 'jejak';

    public const ROLE = 'jabatan';

    public const STEP = 'tahap';

    public const PATH = 'jalur';

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    /**
     * @param  array<int|string, array<string, mixed>>  $items
     * @param  list<array<string, mixed>>  $fresh
     * @param  list<string>  $columns
     * @param  array<string, string>  $messages
     */
    public static function sync(string $pageKey, string $kind, array $items, array $fresh, array $columns, array $messages): void
    {
        $errors = [];
        $pending = [];

        foreach ($items as $id => $row) {
            $line = static::query()->where('page_key', $pageKey)->where('kind', $kind)->find($id);

            if (! $line) {
                continue;
            }

            if (filter_var($row['delete'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                $line->delete();

                continue;
            }

            $payload = ['position' => max(1, (int) ($row['position'] ?? 1))];

            foreach ($columns as $column) {
                $payload[$column] = trim((string) ($row[$column] ?? ''));

                if ($payload[$column] === '') {
                    $errors[$kind.'.'.$id.'.'.$column] = $messages[$column] ?? 'Isian ini wajib diisi.';
                }
            }

            $pending[] = [$line, $payload];
        }

        foreach (array_values($fresh) as $index => $row) {
            $payload = ['position' => max(1, (int) ($row['position'] ?? 999))];
            $blank = true;

            foreach ($columns as $column) {
                $payload[$column] = trim((string) ($row[$column] ?? ''));

                if ($payload[$column] !== '') {
                    $blank = false;
                }
            }

            if ($blank) {
                continue;
            }

            foreach ($columns as $column) {
                if ($payload[$column] === '') {
                    $errors[$kind.'.new.'.$index.'.'.$column] = $messages[$column] ?? 'Isian ini wajib diisi.';
                }
            }

            $pending[] = [null, $payload];
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        foreach ($pending as [$line, $payload]) {
            $payload['page_key'] = $pageKey;
            $payload['kind'] = $kind;

            if ($line) {
                $line->update($payload);
            } else {
                static::query()->create($payload);
            }
        }

        $position = 1;

        foreach (static::query()->where('page_key', $pageKey)->where('kind', $kind)->orderBy('position')->orderBy('id')->get() as $line) {
            if ((int) $line->position !== $position) {
                $line->update(['position' => $position]);
            }

            $position++;
        }
    }
}
