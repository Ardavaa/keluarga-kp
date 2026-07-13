<?php

namespace App\Http\Controllers;

use App\Exports\CollaborationsExport;
use App\Models\Collaboration;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class CollaborationController extends Controller
{
    public function export()
    {
        return Excel::download(new CollaborationsExport, 'kolaborasi-dosen.xlsx');
    }

    public function exportPdf()
    {
        $collaborations = Collaboration::with(['lecturerOne', 'lecturerTwo'])
            ->orderByDesc('collaboration_count')
            ->get();

        $pdf = Pdf::loadView('pdf.collaborations', compact('collaborations'));

        return $pdf->download('kolaborasi-dosen.pdf');
    }

    public function index()
    {
        $collaborations = Collaboration::with(['lecturerOne', 'lecturerTwo'])
            ->orderByDesc('collaboration_count')
            ->get();

        $nodesById = [];

        foreach ($collaborations as $collaboration) {
            foreach ([$collaboration->lecturerOne, $collaboration->lecturerTwo] as $lecturer) {
                $nodesById[$lecturer->id] ??= [
                    'id' => $lecturer->id,
                    'label' => $lecturer->name,
                    'group' => $lecturer->research_group ?: 'Lainnya',
                    'value' => 0,
                ];
            }

            $nodesById[$collaboration->lecturer_id_1]['value']++;
            $nodesById[$collaboration->lecturer_id_2]['value']++;
        }

        $edges = $collaborations->map(fn (Collaboration $c) => [
            'from' => $c->lecturer_id_1,
            'to' => $c->lecturer_id_2,
            'value' => $c->collaboration_count,
            'title' => "{$c->collaboration_count} publikasi bersama",
        ])->values();

        return view('pages.collaborations', [
            'nodes' => array_values($nodesById),
            'edges' => $edges,
            'collaborations' => $collaborations,
        ]);
    }
}
