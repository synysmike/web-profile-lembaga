<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use App\Support\ThemeCatalog;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        ThemeCatalog::seedDefault();

        foreach (HomepageSection::defaults() as $section) {
            HomepageSection::query()->updateOrCreate(
                ['key' => $section['key']],
                $section,
            );
        }
    }
}
