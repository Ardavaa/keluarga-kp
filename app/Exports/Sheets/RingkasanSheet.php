<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RingkasanSheet implements FromArray, WithHeadings, WithTitle
{
    public function __construct(private array $stats)
    {
    }

    public function array(): array
    {
        return [
            ['Total Dosen', $this->stats['totalLecturers']],
            ['Total Publikasi', $this->stats['totalPublications']],
            ['Jumlah Bidang AI', $this->stats['totalAiFields']],
            ['Total Koneksi Kolaborasi', $this->stats['totalCollaborations']],
        ];
    }

    public function headings(): array
    {
        return ['Metrik', 'Nilai'];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }
}
