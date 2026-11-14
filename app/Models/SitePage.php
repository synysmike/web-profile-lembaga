<?php

namespace App\Models;

use App\Support\TextBlocks;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'title', 'summary', 'body', 'missions', 'goals'])]
class SitePage extends Model
{
    public const SAMBUTAN = 'sambutan';

    public const SEJARAH = 'sejarah';

    public const STRUKTUR = 'struktur';

    public const VISI = 'visi';

    public const SPMB = 'spmb';

    public static function content(string $key): self
    {
        return static::query()->where('key', $key)->firstOrFail();
    }

    /**
     * @return list<string>
     */
    public function paragraphs(): array
    {
        return TextBlocks::paragraphs($this->body);
    }

    /**
     * @return list<string>
     */
    public function missionLines(): array
    {
        return TextBlocks::lines($this->missions);
    }

    /**
     * @return list<string>
     */
    public function goalLines(): array
    {
        return TextBlocks::lines($this->goals);
    }
}
