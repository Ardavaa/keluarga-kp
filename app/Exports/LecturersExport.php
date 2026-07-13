<?php

namespace App\Exports;

use App\Models\Lecturer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LecturersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Menerima koleksi dosen yang SUDAH difilter dari controller
     * (LecturerController@index), supaya export konsisten dengan
     * filter/pencarian yang sedang aktif di halaman Profil Dosen.
     */
    public function __construct(private Collection $lecturers)
    {
    }

    public function collection(): Collection
    {
        return $this->lecturers;
    }

    public function headings(): array
    {
        return [
            'Nama', 'Kode Dosen', 'NIP', 'Program Studi', 'Kelompok Keahlian',
            'Bidang Keahlian', 'Jabatan Fungsional Akademik', 'Sitasi', 'H-Index', 'i10-Index',
        ];
    }

    public function map($lecturer): array
    {
        return [
            $lecturer->name,
            $lecturer->lecturer_code,
            $lecturer->code,
            $lecturer->study_program,
            $lecturer->research_group,
            $lecturer->field,
            $lecturer->academic_rank,
            $lecturer->citation_count,
            $lecturer->h_index,
            $lecturer->i10_index,
        ];
    }
}
