<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel ini TIDAK ada di skema Python sisi scraper — murni tambahan
     * dashboard untuk mekanisme override per field (docs/PRD.md §4.3):
     * saat Admin mengoreksi sebuah field, satu baris ditambahkan di sini.
     * Proses import/scraper (mis. ImportLecturersFromSpreadsheet) melewati
     * field yang punya baris di tabel ini supaya tidak menimpa koreksi manual.
     */
    public function up(): void
    {
        Schema::create('lecturer_field_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('lecturers')->cascadeOnDelete();
            $table->string('field');
            $table->timestamps();

            $table->unique(['lecturer_id', 'field']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturer_field_overrides');
    }
};
