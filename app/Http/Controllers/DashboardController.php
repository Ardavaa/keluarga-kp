<?php

namespace App\Http\Controllers;

use App\Exports\DashboardSummaryExport;
use App\Models\Collaboration;
use App\Models\Lecturer;
use App\Models\Publication;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard', $this->dashboardData());
    }

    public function export()
    {
        $data = $this->dashboardData();

        return Excel::download(
            new DashboardSummaryExport(
                [
                    'totalLecturers' => $data['totalLecturers'],
                    'totalPublications' => $data['totalPublications'],
                    'totalAiFields' => $data['totalAiFields'],
                    'totalCollaborations' => $data['totalCollaborations'],
                ],
                $data['researchGroups'],
                $data['recentPublications'],
                $data['topCollaborations'],
            ),
            'ringkasan-dashboard.xlsx'
        );
    }

    public function exportPdf()
    {
        $pdf = Pdf::loadView('pdf.dashboard', $this->dashboardData());

        return $pdf->download('ringkasan-dashboard.pdf');
    }

    private function dashboardData(): array
    {
        $totalAiFields = Lecturer::get(['ai_categories'])
            ->pluck('ai_categories')
            ->flatten()
            ->filter()
            ->unique()
            ->count();

        $researchGroups = Lecturer::selectRaw('research_group, count(*) as count')
            ->groupBy('research_group')
            ->get()
            ->pluck('count', 'research_group')
            ->toArray();

        $recentPublications = Publication::with('lecturer')
            ->orderBy('year', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $topCollaborations = Collaboration::with(['lecturerOne', 'lecturerTwo'])
            ->orderBy('collaboration_count', 'desc')
            ->limit(3)
            ->get();

        return [
            'totalLecturers' => Lecturer::count(),
            'totalPublications' => Publication::count(),
            'totalCollaborations' => Collaboration::count(),
            'totalAiFields' => $totalAiFields,
            'researchGroups' => $researchGroups,
            'recentPublications' => $recentPublications,
            'topCollaborations' => $topCollaborations,
        ];
    }
}
