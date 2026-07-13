<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Skema mengikuti persis schema.sql di repo scraper (lihat docs/PRD.md §6).
     * Nama index disamakan dengan database_details.md agar mudah ditelusuri.
     */
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->nullable()->constrained('lecturers')->cascadeOnDelete();
            $table->text('title')->nullable();
            $table->integer('year')->nullable();

            $table->index('lecturer_id', 'idx_publications_lecturer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
