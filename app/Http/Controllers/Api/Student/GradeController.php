<?php

// app/Http/Controllers/Api/GradeController.php
namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('student')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $term = $request->query('term');
        $query = $user->grades()->select('id', 'score', 'letter_grade', 'term', 'created_at');

        if ($term) {
            $query->where('term', $term);
        }

        $grades = $query->orderBy('term')->orderByDesc('created_at')->get();

        // Group by term (nice for UI)
        $grouped = $grades->groupBy('term')->map(function ($items) {
            return [
                'items' => $items->values(),
                'average_score' => round((float) $items->avg('score'), 2),
            ];
        });

        return response()->json([
            'status' => true,
            'student' => [
                'user_id' => $user->id,
                'name' => $user->name,
            ],
            'filter' => ['term' => $term],
            'data' => $grouped,
        ]);
    }

    public function terms(Request $request)
    {
        $user = Auth::user();

        $terms = $user->grades()
            ->select('term')
            ->distinct()
            ->orderBy('term')
            ->pluck('term');

        return response()->json([
            'status' => true,
            'data' => $terms,
        ]);
    }
}
