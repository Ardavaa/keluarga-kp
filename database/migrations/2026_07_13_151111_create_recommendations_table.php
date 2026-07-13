<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Skema mengikuti persis schema.sql di repo scraper (lihat docs/PRD.md §6).
     * Dua kolom FK sama-sama menunjuk ke lecturers — nama kolom kedua
     * (recommended_lecturer_id) tidak mengikuti konvensi tebakan otomatis
     * Laravel, jadi tabel referensinya di-set eksplisit.
     */
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->nullable()->constrained('lecturers')->cascadeOnDelete();
            $table->foreignId('recommended_lecturer_id')->nullable()->constrained('lecturers')->cascadeOnDelete();
            $table->float('score')->nullable();
            $table->json('reasons')->nullable();

            $table->index('lecturer_id', 'idx_recommendations_lecturer_id');
            $table->index('recommended_lecturer_id', 'idx_recommendations_recommended_lecturer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
