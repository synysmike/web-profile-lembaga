<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('ip', 45)->nullable()->unique()->after('token');
            $table->string('first_path')->nullable()->after('ip');
            $table->string('first_title')->nullable()->after('first_path');
        });

        $firstPages = DB::table('page_views')
            ->orderBy('viewed_at')
            ->orderBy('id')
            ->get(['visitor_id', 'path', 'title'])
            ->unique('visitor_id');

        foreach ($firstPages as $page) {
            DB::table('visitors')->where('id', $page->visitor_id)->update([
                'first_path' => $page->path,
                'first_title' => $page->title,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropUnique(['ip']);
            $table->dropColumn(['ip', 'first_path', 'first_title']);
        });
    }
};
