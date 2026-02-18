<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

// use function Symfony\Component\Clock\now;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('student')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $student = $user->student;
        if (!$student) {
            return response()->json(['status' => false, 'message' => 'Student not found'], 404);
        }

        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'classroom_id' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $from = $validated['from'] ?? null;
        $to = $validated['to'] ?? null;

        $year = $validated['year'] ?? (int) now()->format('Y');
        $month = array_key_exists('month', $validated) ? $validated['month'] : null;

        $classroomId = $validated['classroom_id'] ?? null;
        $perPage = $validated['per_page'] ?? 15;

        $query = Attendance::query()
            ->where('student_id', $student->id)
            ->select(['id', 'student_id', 'classroom_id', 'date', 'status', 'created_at'])
            ->orderByDesc('date');

        if ($classroomId) {
            $query->where('classroom_id', $classroomId);
        }

        if ($from && $to) {
            $query->whereBetween('date', [$from, $to]);
        } else {
            $query->whereYear('date', $year);

            if ($month !== null) {
                $query->whereMonth('date', $month);
            }
        }

        return response()->json([
            'status' => true,
            'filters' => [
                'from' => $from,
                'to' => $to,
                'year' => $year,
                'month' => $month,
                'classroom_id' => $classroomId,
                'per_page' => $perPage,
            ],
            'data' => $query->paginate($perPage),
        ]);
    }

    public function summary(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('student')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $student = $user->student;
        if (!$student) {
            return response()->json(['status' => false, 'message' => 'Student not found'], 404);
        }

        $validated = $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        $year = $validated['year'] ?? (int) now()->format('Y');
        $month = $validated['month'] ?? null;

        $monthKey = $month ? str_pad((string)$month, 2, '0', STR_PAD_LEFT) : 'all';
        $cacheKey = "student:{$student->id}:attendance:summary:{$year}-{$monthKey}";

        $summary = Cache::remember($cacheKey, 3600, function () use ($month, $student, $year) {
            $query = Attendance::query()
                ->where('student_id', $student->id)
                ->whereYear('date', $year);

            if ($month) {
                $query->whereMonth('date', $month);
            }

            return $query->selectRaw("
                COUNT(*) as total_days,
                SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late
            ")->first();
        });

        return response()->json([
            'status' => true,
            'period' => [
                'year' => $year,
                'month' => $month, // null means whole year
            ],
            'summary' => $summary,
        ]);
    }


    public function show($id){
        $student = Auth::user()?->student;
        if(!$student){
            return response()->json([
                'status' => false,
                'message' => 'Student not found'
            ], 404);
        }
        $attendance = Attendance::where('student_id', $student->id)
            ->where('id', $id)
            ->first();

        if(!$attendance){
            return response()->json([
                'status' => false,
                'message' => 'Attendance record not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $attendance
        ]);
    }

    public function downloadPdf(Request $request){
        // Placeholder for PDF generation logic
        return response()->json([
            'status' => true,
            'message' => 'PDF generation not implemented yet.'
        ]);
    }
}
