<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            // Storage-relative path (e.g. "projects/yadot/abc123.webp"), never
            // an arbitrary filesystem path. Resolved to a public URL by the
            // ProjectImageResource, so the server's real path is never exposed.
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->timestamps();

            $table->index(['project_id', 'is_cover']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_images');
    }
};
