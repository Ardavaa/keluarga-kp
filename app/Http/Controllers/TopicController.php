<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;

class TopicController extends Controller
{
    public function index()
    {
        $counts = Lecturer::get(['ai_categories'])
            ->pluck('ai_categories')
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc();

        return view('pages.topics', [
            'labels' => $counts->keys()->values(),
            'counts' => $counts->values(),
            'totalLecturers' => Lecturer::count(),
        ]);
    }
}
