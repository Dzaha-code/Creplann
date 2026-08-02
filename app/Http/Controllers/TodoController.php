<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Schedule;
use App\Models\Todo;
use App\Services\WeeklyGridService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TodoController extends Controller
{
    public function index(Request $request, WeeklyGridService $weeklyGridService): JsonResponse|View
    {
        $query = $request->user()
            ->todos()
            ->with('schedule')
            ->orderBy('due_date')
            ->orderBy('created_at');

        $status = $request->string('filter', 'all')->toString();

        if ($status === 'active') {
            $query->where('completed', false);
        }

        if ($status === 'done') {
            $query->where('completed', true);
        }

        if ($request->filled('week_date')) {
            [$weekStart, $weekEnd] = $weeklyGridService->resolveWeekRange($request->string('week_date')->toString());
            $query->whereBetween('due_date', [$weekStart->toDateString(), $weekEnd->toDateString()]);
        }

        if ($request->filled('schedule_id')) {
            $query->where('schedule_id', $request->integer('schedule_id'));
        }

        $todos = $query->get();

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'data' => $todos,
                'filters' => [
                    'filter' => $status,
                    'week_date' => $request->query('week_date'),
                    'schedule_id' => $request->query('schedule_id'),
                ],
            ]);
        }

        return view('todo.index', compact('todos'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('todo.index');
    }

    public function store(StoreTodoRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $schedule = $this->resolveOwnedSchedule($request, $validated['schedule_id'] ?? null);

        if ($schedule instanceof Schedule && empty($validated['due_date'])) {
            $validated['due_date'] = $schedule->date->toDateString();
        }

        $todo = $request->user()->todos()->create([
            'schedule_id' => $schedule?->id,
            'title' => $validated['title'],
            'completed' => $validated['completed'] ?? false,
            'due_date' => $validated['due_date'] ?? null,
        ]);

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'message' => 'Todo berhasil dibuat.',
                'data' => $todo->fresh('schedule'),
            ], 201);
        }

        return redirect()->route('todo.index')->with('success', 'Todo berhasil dibuat.');
    }

    public function show(Request $request, int $todo): JsonResponse|RedirectResponse
    {
        $todoModel = $this->findOwnedTodo($request, $todo);

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'data' => $todoModel->load('schedule'),
            ]);
        }

        return redirect()->route('todo.index');
    }

    public function edit(Request $request, int $todo): JsonResponse|RedirectResponse
    {
        $todoModel = $this->findOwnedTodo($request, $todo);

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'data' => $todoModel->load('schedule'),
            ]);
        }

        return redirect()->route('todo.index');
    }

    public function update(UpdateTodoRequest $request, int $todo): JsonResponse|RedirectResponse
    {
        $todoModel = $this->findOwnedTodo($request, $todo);

        if ($request->boolean('toggle') && count($request->validated()) === 0) {
            $todoModel->update([
                'completed' => ! $todoModel->completed,
            ]);

            if ($this->isPlannerApiRequest($request)) {
                return response()->json([
                    'message' => 'Status todo berhasil diubah.',
                    'data' => $todoModel->fresh('schedule'),
                ]);
            }

            return redirect()->route('todo.index')->with('success', 'Status todo berhasil diubah.');
        }

        $validated = $request->validated();
        $schedule = array_key_exists('schedule_id', $validated)
            ? $this->resolveOwnedSchedule($request, $validated['schedule_id'])
            : $todoModel->schedule;

        if ($schedule instanceof Schedule && empty($validated['due_date']) && array_key_exists('schedule_id', $validated)) {
            $validated['due_date'] = $schedule->date->toDateString();
        }

        if (array_key_exists('schedule_id', $validated)) {
            $validated['schedule_id'] = $schedule?->id;
        }

        $todoModel->update($validated);

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'message' => 'Todo berhasil diperbarui.',
                'data' => $todoModel->fresh('schedule'),
            ]);
        }

        return redirect()->route('todo.index')->with('success', 'Todo berhasil diperbarui.');
    }

    public function destroy(Request $request, int $todo): JsonResponse|RedirectResponse
    {
        $todoModel = $this->findOwnedTodo($request, $todo);
        $todoModel->delete();

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'message' => 'Todo berhasil dihapus.',
            ]);
        }

        return redirect()->route('todo.index')->with('success', 'Todo berhasil dihapus.');
    }

    private function findOwnedTodo(Request $request, int $todoId): Todo
    {
        return $request->user()
            ->todos()
            ->with('schedule')
            ->findOrFail($todoId);
    }

    private function resolveOwnedSchedule(Request $request, int|null $scheduleId): ?Schedule
    {
        if (! $scheduleId) {
            return null;
        }

        return $request->user()->schedules()->findOrFail($scheduleId);
    }

    private function isPlannerApiRequest(Request $request): bool
    {
        return $request->is('planner-api/*') || $request->expectsJson();
    }
}
