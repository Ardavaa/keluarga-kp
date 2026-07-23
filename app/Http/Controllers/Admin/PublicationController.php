<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\Publication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function store(Request $request, Lecturer $lecturer): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:500'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
        ]);

        $lecturer->publications()->create($validated);

        return redirect()
            ->route('admin.lecturers.edit', $lecturer)
            ->with('status', 'Publikasi ditambahkan.');
    }

    public function update(Request $request, Lecturer $lecturer, Publication $publication): RedirectResponse
    {
        abort_if($publication->lecturer_id !== $lecturer->id, 404);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:500'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
        ]);

        $publication->update($validated);

        return redirect()
            ->route('admin.lecturers.edit', $lecturer)
            ->with('status', 'Publikasi diperbarui.');
    }

    public function destroy(Lecturer $lecturer, Publication $publication): RedirectResponse
    {
        abort_if($publication->lecturer_id !== $lecturer->id, 404);

        $publication->delete();

        return redirect()
            ->route('admin.lecturers.edit', $lecturer)
            ->with('status', 'Publikasi dihapus.');
    }
}
