<?php

namespace Database\Seeders;

use App\Models\Coauthor;
use App\Models\Collaboration;
use App\Models\Keyword;
use App\Models\Lecturer;
use App\Models\Profile;
use App\Models\Publication;
use App\Models\Recommendation;
use App\Models\ResearchInterest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LecturerSampleSeeder extends Seeder
{
    /**
     * Data identitas & klasifikasi keahlian di bawah ini diambil apa adanya
     * dari "Keilmuan Dosen FIF.xlsx" (sample 9 dosen) — itu memang sumber
     * data acuan tim untuk fase ini.
     *
     * Metrik riset (citation/h-index), publikasi, coauthor, dan rekomendasi
     * SENGAJA dibuat pakai Faker (data acak/lorem-ipsum), BUKAN metrik nyata
     * hasil scraping — supaya tidak seolah-olah mengklaim capaian riset
     * sungguhan atas nama dosen yang bersangkutan. Data asli baru masuk di
     * Fase 7 setelah tersambung ke DB hasil scraping (lihat docs/PRD.md §4).
     */
    private const AI_RELATED_FIELDS = [
        'Artificial Intelligence',
        'Natural Language Processing',
        'Human Computer Interaction',
        'Personalized Recommender System',
    ];

    public function run(): void
    {
        $faker = fake('id_ID');

        $sample = [
            ['name' => 'Ade Romadhony', 'code' => '06840042-1', 'lecturer_code' => 'ADE', 'study_program' => 'S2 Informatika', 'research_group' => 'DSIS', 'academic_rank' => 'Lektor', 'field' => 'Natural Language Processing'],
            ['name' => 'Aaz Muhammad Hafidz Azis', 'code' => '23990046-3', 'lecturer_code' => 'ZHH', 'study_program' => 'S1 Rekayasa Perangkat Lunak', 'research_group' => 'SEAL', 'academic_rank' => 'Asisten Ahli', 'field' => 'Artificial Intelligence'],
            ['name' => 'Abdullah Hanifan', 'code' => '24900005-3', 'lecturer_code' => 'ALH', 'study_program' => 'S1 PJJ Informatika', 'research_group' => 'CITI', 'academic_rank' => 'NJAD', 'field' => 'Computer Security'],
            ['name' => 'Abdullah Iskandar', 'code' => '24960014-3', 'lecturer_code' => 'ABK', 'study_program' => 'S1 Informatika', 'research_group' => 'DSIS', 'academic_rank' => 'NJAD', 'field' => 'Human Computer Interaction'],
            ['name' => 'Achmad Lukman', 'code' => '23780001-3', 'lecturer_code' => 'ACK', 'study_program' => 'S1 Teknologi Informasi', 'research_group' => 'DSIS', 'academic_rank' => 'Lektor', 'field' => 'Artificial Intelligence'],
            ['name' => 'Aditya Firman Ihsan', 'code' => '21950003-3', 'lecturer_code' => 'FMH', 'study_program' => 'S1 Informatika', 'research_group' => 'DSIS', 'academic_rank' => 'Lektor', 'field' => 'Data Modeling and Simulation'],
            ['name' => 'Adiwijaya', 'code' => '00740046-1', 'lecturer_code' => 'ADW', 'study_program' => 'S3 Informatika', 'research_group' => 'DSIS', 'academic_rank' => 'Guru Besar', 'field' => 'Matematika'],
            ['name' => 'Agung Toto Wibowo', 'code' => '06810035-1', 'lecturer_code' => 'ATW', 'study_program' => 'S3 Informatika', 'research_group' => 'DSIS', 'academic_rank' => 'Lektor', 'field' => 'Personalized Recommender System'],
            ['name' => 'Aji Gautama Putrada', 'code' => '15850084-1', 'lecturer_code' => 'AJG', 'study_program' => 'S1 Teknologi Informasi', 'research_group' => 'CITI', 'academic_rank' => 'Lektor', 'field' => 'Information Technology'],
        ];

        $lecturers = collect($sample)->map(function (array $row) use ($faker) {
            $lecturer = Lecturer::create([
                ...$row,
                'full_name' => $row['name'],
                'email' => $faker->unique()->safeEmail(),
                'citation_count' => $faker->numberBetween(0, 300),
                'h_index' => $faker->numberBetween(0, 15),
                'i10_index' => $faker->numberBetween(0, 12),
                'ai_categories' => in_array($row['field'], self::AI_RELATED_FIELDS, true) ? [$row['field']] : [],
            ]);

            Profile::create([
                'lecturer_id' => $lecturer->id,
                'platform' => 'sinta',
                'url' => "https://example.org/sinta/{$row['lecturer_code']}",
            ]);

            Profile::create([
                'lecturer_id' => $lecturer->id,
                'platform' => 'google_scholar',
                'url' => "https://example.org/scholar/{$row['lecturer_code']}",
            ]);

            Keyword::create(['lecturer_id' => $lecturer->id, 'keyword' => $row['field']]);
            ResearchInterest::create(['lecturer_id' => $lecturer->id, 'interest' => $row['field']]);

            $pubCount = $faker->numberBetween(2, 4);
            for ($i = 0; $i < $pubCount; $i++) {
                Publication::create([
                    'lecturer_id' => $lecturer->id,
                    'title' => Str::ucfirst($faker->sentence($faker->numberBetween(6, 12))),
                    'year' => $faker->numberBetween(2019, 2025),
                ]);
            }

            if ($faker->boolean(40)) {
                Coauthor::create(['lecturer_id' => $lecturer->id, 'coauthor_name' => $faker->name()]);
            }

            return $lecturer;
        });

        // Beberapa pasangan rekomendasi & kolaborasi dummy antar sample dosen di atas.
        $pairs = [
            ['ADE', 'ACK'], // sama-sama AI/NLP
            ['ZHH', 'ACK'], // sama-sama Artificial Intelligence
            ['ADW', 'ATW'], // satu research group DSIS, senior
            ['FMH', 'ABK'], // satu research group DSIS
        ];

        $reasonPool = [
            'Kesamaan bidang riset',
            'Pernah satu kelompok keahlian',
            'Topik publikasi saling melengkapi',
            'Berpotensi kolaborasi lintas prodi',
        ];

        $byCode = $lecturers->keyBy('lecturer_code');

        foreach ($pairs as [$codeA, $codeB]) {
            $a = $byCode[$codeA];
            $b = $byCode[$codeB];

            Recommendation::create([
                'lecturer_id' => $a->id,
                'recommended_lecturer_id' => $b->id,
                'score' => $faker->randomFloat(2, 0.5, 0.99),
                'reasons' => $faker->randomElements($reasonPool, $faker->numberBetween(1, 2)),
            ]);

            $sharedPublications = Publication::whereIn('lecturer_id', [$a->id, $b->id])
                ->inRandomOrder()
                ->limit(2)
                ->pluck('title')
                ->all();

            [$id1, $id2] = $a->id < $b->id ? [$a->id, $b->id] : [$b->id, $a->id];

            Collaboration::create([
                'lecturer_id_1' => $id1,
                'lecturer_id_2' => $id2,
                'collaboration_count' => $faker->numberBetween(1, 5),
                'shared_publications' => $sharedPublications,
            ]);
        }
    }
}
