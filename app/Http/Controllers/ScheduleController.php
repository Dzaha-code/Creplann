<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use App\Services\ScheduleTodoService;
use App\Services\WeeklyGridService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class ScheduleController extends Controller
{
    public function index(): View
    {
        return view('schedule.index');
    }

    public function list(Request $request, WeeklyGridService $weeklyGridService): JsonResponse
    {
        $query = $request->user()
            ->schedules()
            ->withCount('todos')
            ->orderBy('date')
            ->orderBy('start_time');

        if ($request->filled('week_date')) {
            [$weekStart, $weekEnd] = $weeklyGridService->resolveWeekRange($request->string('week_date')->toString());
            $query->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()]);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->string('date')->toString());
        }

        return response()->json([
            'data' => $query->get(),
        ]);
    }

    public function weeklyGrid(Request $request, WeeklyGridService $weeklyGridService): JsonResponse
    {
        return response()->json(
            $weeklyGridService->buildForUser(
                $request->user(),
                $request->query('date')
            )
        );
    }

    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $schedule = $request->user()->schedules()->create($request->validated());

        return response()->json([
            'message' => 'Schedule berhasil dibuat.',
            'data' => $schedule->fresh()->loadCount('todos'),
        ], 201);
    }

    public function show(Request $request, int $schedule): JsonResponse
    {
        return response()->json([
            'data' => $this->findOwnedSchedule($request, $schedule)->load('todos'),
        ]);
    }

    public function update(UpdateScheduleRequest $request, int $schedule, ScheduleTodoService $scheduleTodoService): JsonResponse
    {
        $scheduleModel = $this->findOwnedSchedule($request, $schedule);
        $scheduleModel->update($request->validated());
        $scheduleTodoService->syncLinkedTodo($scheduleModel);

        return response()->json([
            'message' => 'Schedule berhasil diperbarui.',
            'data' => $scheduleModel->fresh()->loadCount('todos'),
        ]);
    }

    public function destroy(Request $request, int $schedule): JsonResponse
    {
        $scheduleModel = $this->findOwnedSchedule($request, $schedule);
        $scheduleModel->delete();

        return response()->json([
            'message' => 'Schedule berhasil dihapus.',
        ]);
    }

    public function generateTodo(
        Request $request,
        int $schedule,
        ScheduleTodoService $scheduleTodoService
    ): JsonResponse {
        $scheduleModel = $this->findOwnedSchedule($request, $schedule);
        $result = $scheduleTodoService->generateFromSchedule($scheduleModel);

        return response()->json([
            'message' => $result['created']
                ? 'Todo berhasil dibuat dari schedule.'
                : 'Todo dari schedule sudah ada dan disinkronkan ulang.',
            'data' => $result['todo'],
        ], $result['created'] ? 201 : 200);
    }

    private function findOwnedSchedule(Request $request, int $scheduleId): Schedule
    {
        return $request->user()
            ->schedules()
            ->withCount('todos')
            ->findOrFail($scheduleId);
    }
}
