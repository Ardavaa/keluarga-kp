<?php

namespace Tests\Feature;

use App\Models\Lecturer;
use App\Models\LecturerFieldOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

/**
 * Menguji mekanisme override per field (docs/PRD.md §4.3): koreksi Admin
 * lewat panel harus bertahan meski command import scraper dijalankan ulang.
 */
class LecturerOverrideTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_editing_a_field_creates_an_override_and_updates_value(): void
    {
        $admin = User::factory()->create();
        $lecturer = Lecturer::create([
            'full_name' => 'Dosen Uji',
            'code' => 'UJI-001',
            'field' => 'Data Mining',
            'academic_rank' => 'Asisten Ahli',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.lecturers.update', $lecturer), [
            'full_name' => $lecturer->full_name,
            'name_with_title' => '',
            'lecturer_code' => '',
            'study_program' => '',
            'research_group' => '',
            'academic_rank' => $lecturer->academic_rank,
            'field' => 'Text Mining (Koreksi Manual)',
            'citation_count' => '',
            'h_index' => '',
            'i10_index' => '',
        ]);

        $response->assertRedirect(route('admin.lecturers.edit', $lecturer));

        $lecturer->refresh();
        $this->assertSame('Text Mining (Koreksi Manual)', $lecturer->field);
        $this->assertTrue($lecturer->hasOverriddenField('field'));
        $this->assertFalse($lecturer->hasOverriddenField('academic_rank'));
    }

    public function test_import_command_skips_overridden_field_but_updates_others(): void
    {
        $lecturer = Lecturer::create([
            'full_name' => 'Dosen Uji',
            'code' => 'UJI-002',
            'lecturer_code' => 'UJI',
            'field' => 'Text Mining (Koreksi Manual)',
            'academic_rank' => 'Asisten Ahli',
        ]);

        LecturerFieldOverride::create(['lecturer_id' => $lecturer->id, 'field' => 'field']);

        $path = $this->buildTemporarySpreadsheet();

        $this->artisan('import:lecturers', ['path' => $path])->assertSuccessful();

        $lecturer->refresh();

        // Field yang di-override tetap nilai koreksi manual, tidak ditimpa "data scraper".
        $this->assertSame('Text Mining (Koreksi Manual)', $lecturer->field);

        // Field yang TIDAK di-override tetap ikut ter-update dari hasil import.
        $this->assertSame('Lektor', $lecturer->academic_rank);

        @unlink($path);
    }

    private function buildTemporarySpreadsheet(): string
    {
        $spreadsheet = new Spreadsheet();

        $allSheet = $spreadsheet->getActiveSheet();
        $allSheet->setTitle('ALL');
        $allSheet->fromArray(['NO', 'NAMA', 'KODE', 'PRODI', 'KELOMPOK', 'JFA', 'KEILMUAN'], null, 'A1');
        $allSheet->fromArray([1, 'Dosen Uji', 'UJI', 'S1 Teknik Informatika', 'Kelompok Keahlian (DSIS)', 'Lektor', 'Data Mining (dari scraper)'], null, 'A2');

        $sheet1 = $spreadsheet->createSheet();
        $sheet1->setTitle('Sheet1');
        $sheet1->fromArray(['NO', 'NIP', 'NAMA'], null, 'A1');
        $sheet1->fromArray([1, 'UJI-002', 'DOSEN UJI'], null, 'A2');

        $path = storage_path('framework/testing/lecturer-override-test.xlsx');
        (new Xlsx($spreadsheet))->save($path);

        return $path;
    }
}
