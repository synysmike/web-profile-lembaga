<?php

namespace App\Models;

use App\Models\Concerns\HasVisibility;
use App\Support\PublicFile;
use App\Support\TextBlocks;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'slug', 'published_on', 'excerpt', 'cover_path', 'body', 'is_visible'])]
class Post extends Model
{
    use HasVisibility;

    protected function casts(): array
    {
        return [
            'published_on' => 'date',
            'is_visible' => 'boolean',
        ];
    }

    /**
     * @return list<string>
     */
    public function paragraphs(): array
    {
        return TextBlocks::paragraphs($this->body);
    }

    public function coverUrl(): ?string
    {
        return PublicFile::url($this->cover_path);
    }
}
