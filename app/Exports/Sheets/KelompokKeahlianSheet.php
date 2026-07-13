<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class KelompokKeahlianSheet implements FromArray, WithHeadings, WithTitle
{
    public function __construct(private array $researchGroups)
    {
    }

    public function array(): array
    {
        return collect($this->researchGroups)
            ->map(fn ($count, $group) => [$group, $count])
            ->values()
            ->all();
    }

    public function headings(): array
    {
        return ['Kelompok Keahlian', 'Jumlah Dosen'];
    }

    public function title(): string
    {
        return 'Kelompok Keahlian';
    }
}
