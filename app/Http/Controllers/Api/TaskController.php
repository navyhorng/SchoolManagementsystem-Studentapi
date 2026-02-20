<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    // GET /api/tasks?status=pending|completed&category=Homework&priority=High
    public function index(Request $request)
    {
        $user = $request->user();

        // only students can use these endpoints
        if (($user->role ?? 'student') !== 'student') {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $query = Task::where('user_id', $user->id);

        // Filters (optional but useful)
        if ($request->filled('status')) {
            if ($request->status === 'completed') $query->where('is_completed', true);
            if ($request->status === 'pending') $query->where('is_completed', false);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Sort soonest due date first
        $tasks = $query->orderByRaw("CASE WHEN due_date IS NULL THEN 1 ELSE 0 END")
                      ->orderBy('due_date', 'asc')
                      ->latest('id')
                      ->get();

        return response()->json([
            'status' => true,
            'data' => $tasks
        ]);
    }

    // POST /api/tasks
    public function store(Request $request)
    {
        $user = $request->user();

        if (($user->role ?? 'student') !== 'student') {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', Rule::in(['Homework', 'Exam', 'Personal'])],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', Rule::in(['Low', 'Medium', 'High'])],
        ]);

        $task = Task::create([
            'user_id' => $user->id,
            ...$data,
            'is_completed' => false,
            'completed_at' => null,
        ]);

        return response()->json(['status' => true, 'data' => $task], 201);
    }

    // GET /api/tasks/{task}
    public function show(Request $request, Task $task)
    {
        $user = $request->user();

        if ($task->user_id !== $user->id) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $task]);
    }

    // PUT/PATCH /api/tasks/{task}
    public function update(Request $request, Task $task)
    {
        $user = $request->user();

        if ($task->user_id !== $user->id) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'category' => ['sometimes', 'required', Rule::in(['Homework', 'Exam', 'Personal'])],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'priority' => ['sometimes', 'required', Rule::in(['Low', 'Medium', 'High'])],
            'is_completed' => ['sometimes', 'boolean'],
        ]);

        // If marking completed, manage completed_at automatically
        if (array_key_exists('is_completed', $data)) {
            $data['completed_at'] = $data['is_completed'] ? now() : null;
        }

        $task->update($data);

        return response()->json(['status' => true, 'data' => $task]);
    }

    // DELETE /api/tasks/{task}
    public function destroy(Request $request, Task $task)
    {
        $user = $request->user();

        if ($task->user_id !== $user->id) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        $task->delete();

        return response()->json(['status' => true, 'message' => 'Task deleted']);
    }

    // PATCH /api/tasks/{task}/toggle-complete (optional)
    public function toggleComplete(Request $request, Task $task)
    {
        $user = $request->user();

        if ($task->user_id !== $user->id) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        $task->is_completed = ! $task->is_completed;
        $task->completed_at = $task->is_completed ? now() : null;
        $task->save();

        return response()->json(['status' => true, 'data' => $task]);
    }
}
