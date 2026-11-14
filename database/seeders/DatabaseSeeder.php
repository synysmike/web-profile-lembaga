<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@mts.test'],
            [
                'name' => 'Admin Madrasah',
                'password' => 'password',
            ],
        );

        $this->call([
            HomepageSeeder::class,
            PageContentSeeder::class,
        ]);
    }
}
