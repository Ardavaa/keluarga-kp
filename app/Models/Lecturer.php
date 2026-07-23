<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    public $timestamps = false;

    /** URL pakai `code` (NIP) — unique di skema, beda dengan lecturer_code yang tidak unique. */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    protected $fillable = [
        'name',
        'code',
        'lecturer_code',
        'study_program',
        'research_group',
        'academic_rank',
        'field',
        'full_name',
        'titles',
        'name_with_title',
        'email',
        'photo',
        'citation_count',
        'h_index',
        'i10_index',
        'sinta_scopus_citations',
        'sinta_scopus_h_index',
        'sinta_scopus_i10_index',
        'sinta_scholar_citations',
        'sinta_scholar_h_index',
        'sinta_scholar_i10_index',
        'sinta_wos_citations',
        'sinta_wos_h_index',
        'sinta_wos_i10_index',
        'ai_categories',
        'sinta_metrics',
    ];

    protected function casts(): array
    {
        return [
            'citation_count' => 'integer',
            'h_index' => 'integer',
            'i10_index' => 'integer',
            'sinta_scopus_citations' => 'integer',
            'sinta_scopus_h_index' => 'integer',
            'sinta_scopus_i10_index' => 'integer',
            'sinta_scholar_citations' => 'integer',
            'sinta_scholar_h_index' => 'integer',
            'sinta_scholar_i10_index' => 'integer',
            'sinta_wos_citations' => 'integer',
            'sinta_wos_h_index' => 'integer',
            'sinta_wos_i10_index' => 'integer',
            'ai_categories' => 'array',
            'sinta_metrics' => 'array',
        ];
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function keywords(): HasMany
    {
        return $this->hasMany(Keyword::class);
    }

    public function researchInterests(): HasMany
    {
        return $this->hasMany(ResearchInterest::class);
    }

    public function coauthors(): HasMany
    {
        return $this->hasMany(Coauthor::class);
    }

    /** Field yang pernah dikoreksi manual oleh Admin (docs/PRD.md §4.3) — dilindungi dari penulisan ulang scraper/import. */
    public function fieldOverrides(): HasMany
    {
        return $this->hasMany(LecturerFieldOverride::class);
    }

    public function hasOverriddenField(string $field): bool
    {
        return $this->fieldOverrides()->where('field', $field)->exists();
    }

    /** Rekomendasi kolaborasi yang diberikan UNTUK dosen ini (dia sebagai target). */
    public function recommendationsGiven(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    /** Dosen ini direkomendasikan sebagai partner kolaborasi bagi dosen lain. */
    public function recommendationsReceived(): HasMany
    {
        return $this->hasMany(Recommendation::class, 'recommended_lecturer_id');
    }

    public function collaborationsAsFirst(): HasMany
    {
        return $this->hasMany(Collaboration::class, 'lecturer_id_1');
    }

    public function collaborationsAsSecond(): HasMany
    {
        return $this->hasMany(Collaboration::class, 'lecturer_id_2');
    }

    /**
     * Semua baris collaborations yang melibatkan dosen ini, dari kedua sisi
     * (lecturer_id_1 ATAU lecturer_id_2). Bukan relasi Eloquent biasa karena
     * tabel `collaborations` cuma punya satu baris per pasangan (id_1 < id_2,
     * dijamin di sisi Python) — tidak bisa dijadikan satu belongsToMany biasa.
     */
    public function collaborations(): Collection
    {
        return $this->collaborationsAsFirst->merge($this->collaborationsAsSecond);
    }
}
