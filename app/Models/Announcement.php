<?php

namespace App\Models;

use App\Models\Concerns\HasVisibility;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'body', 'position', 'is_visible'])]
class Announcement extends Model
{
    use HasVisibility;

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'is_visible' => 'boolean',
        ];
    }
}
