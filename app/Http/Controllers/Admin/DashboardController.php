<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Models\FeePayment;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\AdminController as BackpackAdminController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class DashboardController extends BackpackAdminController
{
    public function dashboard()
    {
        $this->data['title'] = 'Admin Dashboard';

        return view('admin.dashboard', $this->data);
    }

    public function redirect(): RedirectResponse
    {
        return redirect(backpack_url('dashboard'));
    }

    public function stats(): JsonResponse
    {
        $today = now()->toDateString();
        $trend = $this->buildTrendData(7);

        return response()->json([
            'generated_at' => now()->toDateTimeString(),
            'totals' => [
                'students' => Student::count(),
                'active_students' => Student::where('is_active', true)->count(),
                'teachers' => Teacher::count(),
                'users' => User::count(),
                'tasks' => Task::count(),
                'completed_tasks' => Task::where('is_completed', true)->count(),
                'attendance_today' => Attendance::whereDate('date', $today)->count(),
                'payments' => FeePayment::count(),
                'paid_payments' => FeePayment::where('status', 'Paid')->count(),
                'revenue_collected' => (float) FeePayment::where('status', 'Paid')->sum('amount'),
            ],
            'charts' => $trend,
            'recent_users' => $this->recentUsers(),
            'recent_payments' => $this->recentPayments(),
            'latest_activity' => $this->latestActivity(),
        ]);
    }

    protected function buildTrendData(int $days): array
    {
        $labels = [];
        $students = [];
        $payments = [];
        $tasksCompleted = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateString = $date->toDateString();

            $labels[] = $date->format('M d');
            $students[] = Student::whereDate('created_at', $dateString)->count();
            $payments[] = (float) FeePayment::whereDate('created_at', $dateString)->sum('amount');
            $tasksCompleted[] = Task::where('is_completed', true)
                ->whereDate('completed_at', $dateString)
                ->count();
        }

        return [
            'labels' => $labels,
            'students' => $students,
            'payments' => $payments,
            'tasks_completed' => $tasksCompleted,
        ];
    }

    protected function recentUsers(): array
    {
        return User::query()
            ->latest()
            ->take(8)
            ->get(['id', 'name', 'email', 'created_at'])
            ->map(fn (User $user): array => [
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => optional($user->created_at)->toDateTimeString(),
            ])
            ->all();
    }

    protected function recentPayments(): array
    {
        return FeePayment::query()
            ->with('student:id,name,email')
            ->latest()
            ->take(8)
            ->get(['id', 'student_id', 'amount', 'status', 'due_date', 'payment_date', 'created_at'])
            ->map(fn (FeePayment $payment): array => [
                'student' => optional($payment->student)->name ?: optional($payment->student)->email ?: 'Unknown',
                'amount' => (float) $payment->amount,
                'status' => $payment->status,
                'due_date' => $payment->due_date,
                'payment_date' => $payment->payment_date,
                'created_at' => optional($payment->created_at)->toDateTimeString(),
            ])
            ->all();
    }

    protected function latestActivity(): array
    {
        $userEvents = User::query()
            ->latest()
            ->take(5)
            ->get(['name', 'email', 'created_at'])
            ->map(fn (User $user): array => [
                'type' => 'User',
                'message' => 'New user: '.($user->name ?: $user->email),
                'time' => optional($user->created_at)->toDateTimeString(),
                'sort_time' => optional($user->created_at)?->timestamp ?? 0,
            ]);

        $studentEvents = Student::query()
            ->with('user:id,name,email')
            ->latest()
            ->take(5)
            ->get(['student_code', 'user_id', 'created_at'])
            ->map(fn (Student $student): array => [
                'type' => 'Student',
                'message' => 'Student created: '.$student->student_code.' - '.(optional($student->user)->name ?: optional($student->user)->email ?: 'Unknown'),
                'time' => optional($student->created_at)->toDateTimeString(),
                'sort_time' => optional($student->created_at)?->timestamp ?? 0,
            ]);

        $paymentEvents = FeePayment::query()
            ->with('student:id,name,email')
            ->latest()
            ->take(5)
            ->get(['student_id', 'amount', 'status', 'created_at'])
            ->map(fn (FeePayment $payment): array => [
                'type' => 'Payment',
                'message' => 'Payment '.$payment->status.': $'.number_format((float) $payment->amount, 2).' ('.(optional($payment->student)->name ?: optional($payment->student)->email ?: 'Unknown').')',
                'time' => optional($payment->created_at)->toDateTimeString(),
                'sort_time' => optional($payment->created_at)?->timestamp ?? 0,
            ]);

        /** @var Collection<int, array> $all */
        $all = $userEvents->concat($studentEvents)->concat($paymentEvents);

        return $all->sortByDesc('sort_time')
            ->take(10)
            ->map(function (array $event): array {
                unset($event['sort_time']);

                return $event;
            })
            ->values()
            ->all();
    }
}
