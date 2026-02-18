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
        $user = Auth::user();

        if (!$user || !$user->hasRole('student')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }
        if (!$user->student) {
            return response()->json(['status' => false, 'message' => 'Student not found'], 404);
        }

        $validated = $request->validate([
            'status' => ['nullable', 'in:Paid,Unpaid,Partial'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $studentUserId = $user->id;

        $status = $validated['status'] ?? null;
        $year   = $validated['year'] ?? null;
        $from   = $validated['from'] ?? null;
        $to     = $validated['to'] ?? null;
        $perPage = $validated['per_page'] ?? 15;

        $query = FeePayment::query()
            ->where('student_id', $studentUserId)
            ->select(['id', 'student_id', 'amount', 'status', 'due_date', 'payment_date', 'created_at'])
            ->orderByDesc('due_date');

        if ($status) {
            $query->where('status', $status);
        }

        if ($from && $to) {
            $query->whereBetween('due_date', [$from, $to]);
        } elseif ($year) {
            $query->whereYear('due_date', $year);
        }

        return response()->json([
            'status' => true,
            'filters' => [
                'status' => $status,
                'year' => $year,
                'from' => $from,
                'to' => $to,
                'per_page' => $perPage,
            ],
            'data' => $query->paginate($perPage),
        ]);
    }


    public function show(int $id)
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('student')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $payment = FeePayment::query()
            ->where('student_id', $user->id)
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
