<?php

namespace App\Console\Commands;

use App\Models\Keyword;
use App\Models\Lecturer;
use App\Models\ResearchInterest;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

#[Signature('import:lecturers {path : Path lengkap ke file "Keilmuan Dosen FIF.xlsx" di komputer Anda}')]
#[Description('Import identitas & klasifikasi keahlian dosen dari spreadsheet acuan (sheet ALL + Sheet1). Trial import Fase 7 — bukan integrasi live ke DB scraper.')]
class ImportLecturersFromSpreadsheet extends Command
{
    /**
     * Bidang keilmuan yang dianggap "AI-related" — daftar ini disusun dari
     * nilai kolom Keilmuan yang BENAR-BENAR muncul di spreadsheet (bukan
     * tebakan), supaya KPI "Bidang AI" & chart Topik Dominan akurat.
     */
    private const AI_RELATED_FIELDS = [
        'Artificial Intelligence',
        'Machine Learning',
        'Natural Language Processing',
        'Computational Linguistics',
        'Computer Vision',
        'Image Processing',
        'Data Mining',
        'Text Mining & Processing',
        'Process Mining',
        'Big Data Analytics',
        'Data Science',
        'Human Computer Interaction',
        'Recommender System',
        'Personalized Recommender System',
        'Hybrid Recommender System',
        'Knowledge Based System',
        'Knowledge Representation',
        'Social Computing',
        'Social Network Analysis',
        'Sensor Data Fusion',
    ];

    public function handle(): int
    {
        $path = $this->argument('path');

        if (! is_file($path)) {
            $this->error("File tidak ditemukan: {$path}");

            return self::FAILURE;
        }

        $this->info('Membaca spreadsheet...');
        $spreadsheet = IOFactory::load($path);

        $allSheet = $spreadsheet->getSheetByName('ALL');
        $sheet1 = $spreadsheet->getSheetByName('Sheet1');

        if (! $allSheet || ! $sheet1) {
            $this->error('Sheet "ALL" dan/atau "Sheet1" tidak ditemukan di file ini.');

            return self::FAILURE;
        }

        // NIP hanya ada di Sheet1 (kolom NIP + NAMA) — join ke sheet ALL lewat nama, dinormalisasi uppercase+trim.
        $nipByName = [];
        foreach (array_slice($sheet1->toArray(null, true, true, false), 1) as $row) {
            $name = strtoupper(trim((string) ($row[2] ?? '')));
            if ($name !== '') {
                $nipByName[$name] = $row[1] ?? null;
            }
        }

        $allRows = array_filter(
            array_slice($allSheet->toArray(null, true, true, false), 1),
            fn ($row) => trim((string) ($row[1] ?? '')) !== ''
        );

        // Dedup berdasarkan KODE (data punya 1 nama yang ke-input dobel dengan baris terakhir lebih lengkap).
        $byKode = [];
        foreach ($allRows as $row) {
            $byKode[$row[2]] = $row;
        }

        $created = 0;
        $updated = 0;
        $withNip = 0;
        $withoutNip = 0;
        $aiCount = 0;
        $skippedOverrides = 0;

        foreach ($byKode as $row) {
            [, $nama, $kode, $prodi, $kelompokRaw, $jfa, $keilmuanRaw] = $row;

            $name = $this->titleCase($nama);
            $normalizedName = strtoupper(trim((string) $nama));
            $nip = $nipByName[$normalizedName] ?? null;

            if ($nip) {
                $withNip++;
            } else {
                $withoutNip++;
            }

            // Fallback ke KODE (3 huruf) kalau NIP tidak ketemu di Sheet1 (dosen baru/NJAD yang belum tercatat di sana).
            $code = $nip ?: $kode;

            $researchGroup = null;
            if ($kelompokRaw && preg_match('/\(([A-Z]+)\)/', $kelompokRaw, $m)) {
                $researchGroup = $m[1];
            }

            $keilmuan = trim((string) $keilmuanRaw);
            $field = ($keilmuan === '' || $keilmuan === '#N/A') ? null : $keilmuan;

            $isAiField = $field && in_array($field, self::AI_RELATED_FIELDS, true);
            if ($isAiField) {
                $aiCount++;
            }

            $attributes = [
                'name' => $name,
                'full_name' => $name,
                'lecturer_code' => $kode,
                'study_program' => trim((string) $prodi),
                'research_group' => $researchGroup,
                'academic_rank' => $this->titleCase($jfa),
                'field' => $field,
                'ai_categories' => $isAiField ? [$field] : [],
            ];

            $existing = Lecturer::where('code', $code)->first();

            // Field yang sudah dikoreksi manual Admin (docs/PRD.md §4.3) dilewati
            // supaya import ini tidak menimpa koreksi manual.
            if ($existing) {
                $overriddenFields = $existing->fieldOverrides()->pluck('field')->all();
                foreach ($overriddenFields as $overriddenField) {
                    unset($attributes[$overriddenField]);
                }
                if ($overriddenFields !== []) {
                    $skippedOverrides += count($overriddenFields);
                }
            }

            $lecturer = Lecturer::updateOrCreate(['code' => $code], $attributes);

            if ($lecturer->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }

            // Keyword/minat riset dari Keilmuan asli — cuma untuk dosen yang BARU dibuat,
            // supaya tidak menumpuk duplikat tiap kali command ini dijalankan ulang,
            // dan tidak menimpa data dosen sample Fase 3.4 yang sudah py relasi Faker-nya sendiri.
            if ($lecturer->wasRecentlyCreated && $field) {
                Keyword::firstOrCreate(['lecturer_id' => $lecturer->id, 'keyword' => $field]);
                ResearchInterest::firstOrCreate(['lecturer_id' => $lecturer->id, 'interest' => $field]);
            }
        }

        $this->newLine();
        $this->table(
            ['Metrik', 'Jumlah'],
            [
                ['Total baris diproses', count($byKode)],
                ['Dosen baru dibuat', $created],
                ['Dosen sudah ada (di-update)', $updated],
                ['Ketemu NIP asli (Sheet1)', $withNip],
                ['Fallback pakai KODE (tanpa NIP)', $withoutNip],
                ['Field masuk kategori AI', $aiCount],
                ['Field dilewati (sudah dikoreksi Admin)', $skippedOverrides],
            ]
        );

        $this->comment('Catatan: hanya identitas, klasifikasi, dan keyword/minat riset yang diisi dari data asli spreadsheet.');
        $this->comment('Metrik sitasi, publikasi, profil tautan, kolaborasi, dan rekomendasi TIDAK dibuat otomatis untuk dosen baru —');
        $this->comment('data itu belum tersedia sampai Fase 7.2 (integrasi nyata ke DB scraper) diputuskan & dijalankan.');

        return self::SUCCESS;
    }

    private function titleCase(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return $value;
        }

        return mb_convert_case(mb_strtolower(trim($value)), MB_CASE_TITLE, 'UTF-8');
    }
}
