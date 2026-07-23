<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;

class ExpertiseMapController extends Controller
{
    private const GROUP_ORDER = ['CITI', 'DSIS', 'SEAL'];

    public function index()
    {
        $grouped = Lecturer::orderBy('full_name')
            ->get()
            ->groupBy(fn (Lecturer $lecturer) => $lecturer->research_group ?: 'Lainnya');

        // Tidak pakai ->except() di sini: groupBy() pada Eloquent Collection
        // mengembalikan collection yang masih ber-tipe Eloquent\Collection
        // walau isinya sub-collection (bukan Model), jadi except()/getDictionary()
        // ikut menganggap isinya Model dan error "getKey does not exist".
        $orderedKeys = collect(self::GROUP_ORDER)
            ->filter(fn ($group) => $grouped->has($group))
            ->concat($grouped->keys()->diff(self::GROUP_ORDER));

        $groupedLecturers = $orderedKeys->mapWithKeys(fn ($group) => [$group => $grouped->get($group)]);

        return view('pages.expertise', compact('groupedLecturers'));
    }
}
