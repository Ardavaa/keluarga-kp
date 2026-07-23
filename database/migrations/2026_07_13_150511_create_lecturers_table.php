<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Skema mengikuti persis schema.sql di repo scraper (lihat docs/PRD.md §6),
     * supaya bisa langsung disambungkan ke DB Python di Fase 7. Tidak ada
     * timestamps() Laravel karena tabel asal tidak punya kolom created_at/updated_at.
     */
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('lecturer_code')->nullable();
            $table->string('study_program')->nullable();
            $table->string('research_group')->nullable();
            $table->string('academic_rank')->nullable();
            $table->string('field')->nullable();
            $table->string('full_name')->nullable();
            $table->string('titles')->nullable();
            $table->string('name_with_title')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();

            $table->integer('citation_count')->default(0);
            $table->integer('h_index')->default(0);
            $table->integer('i10_index')->default(0);

            $table->integer('sinta_scopus_citations')->default(0);
            $table->integer('sinta_scopus_h_index')->default(0);
            $table->integer('sinta_scopus_i10_index')->default(0);

            $table->integer('sinta_scholar_citations')->default(0);
            $table->integer('sinta_scholar_h_index')->default(0);
            $table->integer('sinta_scholar_i10_index')->default(0);

            $table->integer('sinta_wos_citations')->default(0);
            $table->integer('sinta_wos_h_index')->default(0);
            $table->integer('sinta_wos_i10_index')->default(0);

            $table->json('ai_categories')->default('[]');
            $table->json('sinta_metrics')->default('{}');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
