<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Skema mengikuti persis schema.sql di repo scraper (lihat docs/PRD.md §6).
     */
    public function up(): void
    {
        Schema::create('research_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->nullable()->constrained('lecturers')->cascadeOnDelete();
            $table->string('interest')->nullable();

            $table->index('lecturer_id', 'idx_research_interests_lecturer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_interests');
    }
};
