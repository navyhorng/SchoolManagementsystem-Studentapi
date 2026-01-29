<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\FeePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeePaymentController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->hasRole('student')){
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }
        $student = Auth::user()?->student;
        if (!$student) {
            return response()->json(['status' => false, 'message' => 'Student not found'], 404);
        }
        $studentId = Auth::id();

        $status = $request->query('status');
        $year   = $request->query('year');
        $from   = $request->query('from');
        $to     = $request->query('to');

        $query = FeePayment::where('student_id', $studentId)
            ->select(['id', 'student_id', 'amount', 'status', 'due_date', 'payment_date', 'created_at'])
            ->orderByDesc('due_date');
        if ($status) {
            $query->where('status', $status);
        }

        // Filter by due_date range
        if ($from && $to) {
            $query->whereBetween('due_date', [$from, $to]);
        } elseif ($year) {
            $query->whereYear('due_date', (int) $year);
        }

        $perPage = min((int) $request->query('per_page', 15), 100);

        return response()->json([
            'status' => true,
            'filters' => [
                'status' => $status,
                'year' => $year ? (int)$year : null,
                'from' => $from,
                'to' => $to,
            ],
            'data' => $query->paginate($perPage),
        ]);
    }

    public function show($id)
    {
        $userId = Auth::id();

        $payment = FeePayment::where('student_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Fee payment not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $payment,
        ]);
    }
}
