<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class KolaborasiTeraktifSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private Collection $collaborations)
    {
    }

    public function collection(): Collection
    {
        return $this->collaborations;
    }

    public function headings(): array
    {
        return ['Dosen 1', 'Dosen 2', 'Jumlah Publikasi Bersama'];
    }

    public function map($collaboration): array
    {
        return [$collaboration->lecturerOne->name, $collaboration->lecturerTwo->name, $collaboration->collaboration_count];
    }

    public function title(): string
    {
        return 'Kolaborasi Teraktif';
    }
}
