<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PublikasiTerbaruSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private Collection $publications)
    {
    }

    public function collection(): Collection
    {
        return $this->publications;
    }

    public function headings(): array
    {
        return ['Dosen', 'Judul Publikasi', 'Tahun'];
    }

    public function map($publication): array
    {
        return [$publication->lecturer->full_name, $publication->title, $publication->year];
    }

    public function title(): string
    {
        return 'Publikasi Terbaru';
    }
}
