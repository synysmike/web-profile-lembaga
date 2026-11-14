<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homepage_sections', function (Blueprint $table) {
            $table->string('kicker', 80)->nullable()->after('key');
        });

        DB::table('homepage_sections')
            ->where('key', 'hero')
            ->whereNull('kicker')
            ->update(['kicker' => 'Madrasah Tsanawiyah']);
    }

    public function down(): void
    {
        Schema::table('homepage_sections', function (Blueprint $table) {
            $table->dropColumn('kicker');
        });
    }
};
