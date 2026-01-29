<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use function Symfony\Component\Clock\now;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()?->student;

        if (!$student) {
            return response()->json(['status' => false, 'message' => 'Student not found'], 404);
        }

        $from = $request->query('from');
        $to   = $request->query('to');

        $year  = $request->query('year');
        $month = $request->query('month');

        $classroomId = $request->query('classroom_id');

        $query = Attendance::query()
            ->where('student_id', $student->id)
            ->select(['id', 'student_id', 'classroom_id', 'date', 'status', 'created_at'])
            ->orderBy('date', 'desc');

        if ($classroomId) {
            $query->where('classroom_id', (int) $classroomId);
        }

        // Filter A: from/to (highest priority)
        if ($from && $to) {
            $query->whereBetween('date', [$from, $to]);
        } else {
            // Filter B: year + optional month
            $year = (int) ($year ?: now()->format('Y'));
            $query->whereYear('date', $year);

            if ($month !== null) {
                $month = (int) $month;
                if ($month < 1 || $month > 12) {
                    return response()->json(['status' => false, 'message' => 'Invalid month (1-12)'], 422);
                }
                $query->whereMonth('date', $month);
            }
        }

        $perPage = min((int) $request->query('per_page', 15), 100);

        return response()->json([
            'status' => true,
            'filters' => [
                'from' => $from,
                'to' => $to,
                'year' => $year ? (int) $year : null,
                'month' => $month !== null ? (int) $month : null,
                'classroom_id' => $classroomId ? (int) $classroomId : null,
            ],
            'data' => $query->paginate($perPage),
        ]);
    }
    public function summary(Request $request){
        $student = Auth::user()?->student;

        if(!$student){
            return response()->json([
                'status' => false,
                'message' => 'Student not found',
            ], 404);
        }
        $month = $request->query('month');
        $year = $request->query('year');

        $cacheKey = "student:{$student->id}:attendance:summary:{$year}-{$month}";

        $summary = Cache::remember($cacheKey, 60*60, function() use ($month, $student, $year){
            $query = Attendance::where('student_id', $student->id)
                ->whereYear('date', $year);

            if($month) {
                $query->whereMonth('date', $month);
            }
            return $query->selectRaw("
                COUNT(*) as total_days,
                SUM(status = 'Present') as present,
                SUM(status = 'Absent') as absent,
                SUM(status = 'Late') as late
            ")
            ->first();
        });
        return response()->json([
            'status' => true,
            'period' => [
                'month' => $month,
                'year' => $year,
            ],
            'summary' => $summary
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
