<?php

namespace App\Models;

use App\Models\Concerns\HasVisibility;
use App\Support\PublicFile;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'file_path', 'position', 'is_visible'])]
class Download extends Model
{
    use HasVisibility;

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function fileUrl(): ?string
    {
        return PublicFile::url($this->file_path);
    }
}
