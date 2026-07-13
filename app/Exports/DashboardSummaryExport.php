<?php

namespace App\Exports;

use App\Exports\Sheets\KelompokKeahlianSheet;
use App\Exports\Sheets\KolaborasiTeraktifSheet;
use App\Exports\Sheets\PublikasiTerbaruSheet;
use App\Exports\Sheets\RingkasanSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DashboardSummaryExport implements WithMultipleSheets
{
    public function __construct(
        private array $stats,
        private array $researchGroups,
        private Collection $recentPublications,
        private Collection $topCollaborations,
    ) {
    }

    public function sheets(): array
    {
        return [
            new RingkasanSheet($this->stats),
            new KelompokKeahlianSheet($this->researchGroups),
            new PublikasiTerbaruSheet($this->recentPublications),
            new KolaborasiTeraktifSheet($this->topCollaborations),
        ];
    }
}
