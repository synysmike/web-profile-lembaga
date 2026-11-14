<?php

namespace App\Models;

use App\Models\Concerns\HasVisibility;
use App\Support\PublicFile;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role', 'photo_path', 'position', 'is_visible'])]
class StaffMember extends Model
{
    use HasVisibility;

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function photoUrl(): ?string
    {
        return PublicFile::url($this->photo_path);
    }

    public function initial(): string
    {
        return mb_substr($this->name, 0, 1);
    }
}
