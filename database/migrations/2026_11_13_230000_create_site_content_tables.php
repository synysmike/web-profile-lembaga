<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_pages', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title');
            $table->string('summary')->nullable();
            $table->text('body')->nullable();
            $table->text('missions')->nullable();
            $table->text('goals')->nullable();
            $table->timestamps();
        });

        Schema::create('site_lines', function (Blueprint $table) {
            $table->id();
            $table->string('page_key');
            $table->string('kind');
            $table->unsignedSmallInteger('position')->default(1);
            $table->string('label')->nullable();
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->timestamps();
            $table->index(['page_key', 'kind', 'position']);
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('published_on');
            $table->string('excerpt', 300);
            $table->text('body');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->date('happens_on');
            $table->string('title');
            $table->string('place');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->unsignedSmallInteger('position')->default(1);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path')->nullable();
            $table->unsignedSmallInteger('position')->default(1);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('rank', 40);
            $table->string('title');
            $table->unsignedSmallInteger('position')->default(1);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_path')->nullable();
            $table->unsignedSmallInteger('position')->default(1);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedSmallInteger('position')->default(1);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('staff_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->string('photo_path')->nullable();
            $table->unsignedSmallInteger('position')->default(1);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('token', 40)->unique();
            $table->string('country', 80)->nullable();
            $table->string('region', 80)->nullable();
            $table->string('city', 80)->nullable();
            $table->timestamp('location_checked_at')->nullable();
            $table->timestamp('first_seen_at');
            $table->timestamp('last_seen_at');
            $table->timestamps();
        });

        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained()->cascadeOnDelete();
            $table->date('visited_on');
            $table->timestamps();
            $table->unique(['visitor_id', 'visited_on']);
        });

        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('title');
            $table->timestamp('viewed_at');
            $table->index('viewed_at');
            $table->index('path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
        Schema::dropIfExists('visits');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('staff_members');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('downloads');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('agendas');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('site_lines');
        Schema::dropIfExists('site_pages');
    }
};
