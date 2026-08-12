<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description');
            $table->text('description');
            $table->text('problem')->nullable();
            $table->text('solution')->nullable();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('project_categories')
                ->nullOnDelete();
            $table->enum('status', ['completed', 'in_development', 'planned', 'archived'])
                ->default('planned');
            $table->boolean('featured')->default(false);
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('github_url')->nullable();
            $table->string('live_url')->nullable();
            $table->text('architecture')->nullable();
            $table->text('challenges')->nullable();
            $table->text('lessons_learned')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
