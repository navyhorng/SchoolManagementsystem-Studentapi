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
    public function summary(Request $request){
        $user = Auth::user();
        $student = $user->student;

        if(!$student){
            return response()->json([
                'status' => false,
                'message' => 'Student not found',
            ], 404);
        }
        $month = $request->query('month');
        $year = $request->query('year');

        $cacheKey = "student:{$student->id}attendance:summary:{$year}-{$month}";

        $summary = Cache::remember($cacheKey, 60*60, function() use ($month, $student, $year){
            $query = Attendance::where('student_id', $student->id)
                ->where('date', $year);

            if($month) {
                $query->whereMonth('date', $month);
            }
            return $query->selectRow("
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
}
