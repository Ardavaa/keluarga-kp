<?php

namespace App\Exports;

use App\Models\Collaboration;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CollaborationsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return Collaboration::with(['lecturerOne', 'lecturerTwo'])
            ->orderByDesc('collaboration_count')
            ->get();
    }

    public function headings(): array
    {
        return ['Dosen 1', 'Kelompok Keahlian 1', 'Dosen 2', 'Kelompok Keahlian 2', 'Jumlah Publikasi Bersama'];
    }

    public function map($collaboration): array
    {
        return [
            $collaboration->lecturerOne->full_name,
            $collaboration->lecturerOne->research_group,
            $collaboration->lecturerTwo->full_name,
            $collaboration->lecturerTwo->research_group,
            $collaboration->collaboration_count,
        ];
    }
}
