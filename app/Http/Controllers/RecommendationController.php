<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;

class RecommendationController extends Controller
{
    public function index()
    {
        $recommendations = Recommendation::with(['lecturer', 'recommendedLecturer'])
            ->orderByDesc('score')
            ->get();

        return view('pages.recommendations', [
            'topRecommendations' => $recommendations->take(3),
            'otherRecommendations' => $recommendations->slice(3)->values(),
            'totalRecommendations' => $recommendations->count(),
        ]);
    }
}
