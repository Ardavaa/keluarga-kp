<?php

namespace App\Http\Controllers;

use App\Exports\LecturersExport;
use App\Models\Lecturer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LecturerController extends Controller
{
    private const SORTABLE_COLUMNS = ['name', 'research_group', 'study_program'];

    public function index(Request $request)
    {
        $lecturers = $this->filteredLecturers($request);

        return view('pages.lecturers', [
            'lecturers' => $lecturers,
            'search' => trim((string) $request->query('search', '')),
            'sort' => $this->resolveSort($request),
            'prodi' => trim((string) $request->query('prodi', '')),
            'kelompok' => trim((string) $request->query('kelompok', '')),
            'bidang' => trim((string) $request->query('bidang', '')),
            'tahun' => trim((string) $request->query('tahun', '')),
            'filterOptions' => $this->filterOptions(),
        ]);
    }

    public function show(Lecturer $lecturer)
    {
        $lecturer->load([
            'publications' => fn ($query) => $query->orderByDesc('year'),
            'keywords',
            'researchInterests',
            'profiles',
            'recommendationsGiven.recommendedLecturer',
        ]);

        return view('pages.lecturer-detail', compact('lecturer'));
    }

    public function export(Request $request)
    {
        $lecturers = $this->filteredLecturers($request);

        return Excel::download(new LecturersExport($lecturers), 'profil-dosen.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $lecturers = $this->filteredLecturers($request);
        $hasActiveFilter = collect($request->only(['search', 'prodi', 'kelompok', 'bidang', 'tahun']))
            ->filter(fn ($value) => trim((string) $value) !== '')
            ->isNotEmpty();

        $pdf = Pdf::loadView('pdf.lecturers', compact('lecturers', 'hasActiveFilter'));

        return $pdf->download('profil-dosen.pdf');
    }

    private function resolveSort(Request $request): string
    {
        $sort = $request->query('sort', 'name');

        return in_array($sort, self::SORTABLE_COLUMNS, true) ? $sort : 'name';
    }

    private function filteredLecturers(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $prodi = trim((string) $request->query('prodi', ''));
        $kelompok = trim((string) $request->query('kelompok', ''));
        $bidang = trim((string) $request->query('bidang', ''));
        $tahun = trim((string) $request->query('tahun', ''));

        return Lecturer::query()
            ->when($search !== '', function ($query) use ($search) {
                $searchLower = mb_strtolower($search, 'UTF-8');
                $query->where(function ($q) use ($searchLower) {
                    $q->whereRaw('lower(name) like ?', ["%{$searchLower}%"])
                        ->orWhereRaw('lower(field) like ?', ["%{$searchLower}%"])
                        ->orWhereRaw('lower(study_program) like ?', ["%{$searchLower}%"]);
                });
            })
            ->when($prodi !== '', fn ($query) => $query->where('study_program', $prodi))
            ->when($kelompok !== '', fn ($query) => $query->where('research_group', $kelompok))
            ->when($bidang !== '', fn ($query) => $query->where('field', $bidang))
            ->when($tahun !== '', function ($query) use ($tahun) {
                $query->whereHas('publications', fn ($q) => $q->where('year', $tahun));
            })
            ->orderBy($this->resolveSort($request))
            ->get();
    }

    private function filterOptions(): array
    {
        return [
            'prodi' => Lecturer::query()->whereNotNull('study_program')->distinct()->orderBy('study_program')->pluck('study_program'),
            'kelompok' => Lecturer::query()->whereNotNull('research_group')->distinct()->orderBy('research_group')->pluck('research_group'),
            'bidang' => Lecturer::query()->whereNotNull('field')->distinct()->orderBy('field')->pluck('field'),
            'tahun' => Lecturer::query()->join('publications', 'publications.lecturer_id', '=', 'lecturers.id')
                ->whereNotNull('publications.year')
                ->distinct()
                ->orderByDesc('publications.year')
                ->pluck('publications.year'),
        ];
    }
}
