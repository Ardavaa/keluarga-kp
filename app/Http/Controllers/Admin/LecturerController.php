<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\LecturerFieldOverride;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LecturerController extends Controller
{
    /**
     * Field yang boleh dikoreksi Admin lewat panel ini — identitas,
     * klasifikasi, dan metrik (docs/PRD.md §5). Publikasi dikelola
     * terpisah lewat PublicationController pada form yang sama.
     */
    private const EDITABLE_FIELDS = [
        'full_name',
        'name_with_title',
        'lecturer_code',
        'study_program',
        'research_group',
        'academic_rank',
        'field',
        'citation_count',
        'h_index',
        'i10_index',
    ];

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $searchLower = mb_strtolower($search, 'UTF-8');

        $lecturers = Lecturer::query()
            ->when($search !== '', fn ($query) => $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('lower(full_name) like ?', ["%{$searchLower}%"])
                    ->orWhereRaw('lower(code) like ?', ["%{$searchLower}%"])
                    ->orWhereRaw('lower(lecturer_code) like ?', ["%{$searchLower}%"]);
            }))
            ->orderBy('full_name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.lecturers.index', compact('lecturers', 'search'));
    }

    public function edit(Lecturer $lecturer): View
    {
        $overriddenFields = $lecturer->fieldOverrides()->pluck('field')->all();
        $publications = $lecturer->publications()->orderByRaw('year DESC NULLS LAST')->get();
        $profiles = $lecturer->profiles->keyBy('platform');

        return view('admin.lecturers.edit', compact('lecturer', 'overriddenFields', 'publications', 'profiles'));
    }

    public function update(Request $request, Lecturer $lecturer): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'name_with_title' => ['nullable', 'string', 'max:255'],
            'lecturer_code' => ['nullable', 'string', 'max:50'],
            'study_program' => ['nullable', 'string', 'max:255'],
            'research_group' => ['nullable', 'string', 'max:50'],
            'academic_rank' => ['nullable', 'string', 'max:255'],
            'field' => ['nullable', 'string', 'max:255'],
            'citation_count' => ['nullable', 'integer', 'min:0'],
            'h_index' => ['nullable', 'integer', 'min:0'],
            'i10_index' => ['nullable', 'integer', 'min:0'],
            'profiles' => ['nullable', 'array'],
            'profiles.*' => ['nullable', 'string', 'max:1000'],
        ]);

        // Kolom ini NOT NULL dengan default 0 di skema (bukan nullable) —
        // form boleh dikosongkan, tapi nilainya harus jadi 0, bukan null.
        foreach (['citation_count', 'h_index', 'i10_index'] as $numericField) {
            $validated[$numericField] = $validated[$numericField] ?? 0;
        }

        $changedFields = [];
        foreach (self::EDITABLE_FIELDS as $fieldName) {
            if ((string) $lecturer->{$fieldName} !== (string) ($validated[$fieldName] ?? '')) {
                $changedFields[] = $fieldName;
            }
        }

        $lecturer->update($validated);

        foreach ($changedFields as $fieldName) {
            LecturerFieldOverride::updateOrCreate([
                'lecturer_id' => $lecturer->id,
                'field' => $fieldName,
            ]);
        }

        $profileUpdated = false;
        if ($request->has('profiles')) {
            foreach ($request->input('profiles', []) as $platform => $url) {
                $trimmedUrl = trim((string) $url);
                if ($trimmedUrl !== '') {
                    $existing = $lecturer->profiles()->where('platform', $platform)->first();
                    if (! $existing || $existing->url !== $trimmedUrl) {
                        $profileUpdated = true;
                    }
                    $lecturer->profiles()->updateOrCreate(
                        ['platform' => $platform],
                        ['url' => $trimmedUrl]
                    );
                } else {
                    $deleted = $lecturer->profiles()->where('platform', $platform)->delete();
                    if ($deleted > 0) {
                        $profileUpdated = true;
                    }
                }
            }
        }

        if ($profileUpdated) {
            $changedFields[] = 'profil & tautan';
        }

        $message = $changedFields === []
            ? 'Tidak ada perubahan.'
            : 'Data dosen diperbarui. Field yang diubah ('.implode(', ', $changedFields).') ditandai override — tidak akan tertimpa proses import otomatis.';

        return redirect()
            ->route('admin.lecturers.edit', $lecturer)
            ->with('status', $message);
    }
}
