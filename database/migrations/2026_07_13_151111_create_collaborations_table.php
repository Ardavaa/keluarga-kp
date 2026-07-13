<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Skema mengikuti persis schema.sql di repo scraper (lihat docs/PRD.md §6).
     * lecturer_id_1 < lecturer_id_2 dijamin oleh proses sinkronisasi di sisi
     * Python (save_to_db.py), bukan constraint di level DB.
     */
    public function up(): void
    {
        Schema::create('collaborations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id_1')->nullable()->constrained('lecturers')->cascadeOnDelete();
            $table->foreignId('lecturer_id_2')->nullable()->constrained('lecturers')->cascadeOnDelete();
            $table->integer('collaboration_count')->default(1);
            $table->json('shared_publications')->nullable();

            $table->index('lecturer_id_1', 'idx_collaborations_lecturer_id_1');
            $table->index('lecturer_id_2', 'idx_collaborations_lecturer_id_2');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collaborations');
    }
};
