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
        Schema::create('coauthors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->nullable()->constrained('lecturers')->cascadeOnDelete();
            $table->string('coauthor_name')->nullable();

            $table->index('lecturer_id', 'idx_coauthors_lecturer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coauthors');
    }
};
