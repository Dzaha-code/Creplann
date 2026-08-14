<?php

namespace App\Http\Controllers;

use App\Services\WeeklyGridService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __invoke(Request $request, WeeklyGridService $weeklyGridService): View
    {
        $user = $request->user();
        $today = now()->toDateString();
        $grid = $weeklyGridService->buildForUser($user);

        $todayDay = collect($grid['days'])->firstWhere('date', $today);
        $todoOverview = $grid['todo_overview'];
        $totalTodos = $todoOverview['total'];
        $completedTodos = $todoOverview['completed'];

        return view('dashboard', [
            'user' => $user,
            'weekDays' => $grid['days'],
            'weekLabel' => $grid['week']['label'],
            'todaySchedules' => $todayDay['schedules'] ?? [],
            'todayTodos' => collect($todayDay['todos'] ?? []),
            'recentNotes' => $user->notes()->with('category')->latest()->limit(3)->get(),
            'todoProgress' => [
                'total' => $totalTodos,
                'completed' => $completedTodos,
                'pending' => $todoOverview['pending'],
                'percent' => $totalTodos > 0 ? (int) round(($completedTodos / $totalTodos) * 100) : 0,
            ],
            'summary' => $grid['summary'],
        ]);
    }

    public function api(Request $request, WeeklyGridService $weeklyGridService): JsonResponse
    {
        $user = $request->user();
        $grid = $weeklyGridService->buildForUser($user);

        $todoOverview = $grid['todo_overview'];
        $noteOverview = $grid['note_overview'];

        $stats = [
            'total_notes' => $noteOverview['total'] ?? 0,
            'total_schedules' => $grid['summary']['total_schedules'] ?? 0,
            'total_todos' => $todoOverview['total'] ?? 0,
            'completed_todos' => $todoOverview['completed'] ?? 0,
            'pending_todos' => $todoOverview['pending'] ?? 0,
        ];

        // today's schedules
        $today = now()->toDateString();
        $todayDay = collect($grid['days'])->firstWhere('date', $today);
        $todaySchedules = $todayDay['schedules'] ?? [];

        // recent activities: combine latest todos, schedules, notes
        $recent = [];
        // recent todos
        foreach (array_slice($todoOverview['items'], 0, 5) as $t) {
            $recent[] = [
                'type' => 'todo',
                'title' => $t['title'] ?? '',
                'category' => $t['schedule_title'] ?? '',
                'time' => $t['due_date'] ?? '',
            ];
        }
        // recent schedules
        foreach (array_slice($grid['days'][0]['schedules'] ?? [], 0, 5) as $s) {
            $recent[] = [
                'type' => 'schedule',
                'title' => $s['title'] ?? '',
                'category' => '',
                'time' => $s['start_time'] ?? '',
            ];
        }
        // recent notes
        foreach (array_slice($noteOverview['items'], 0, 5) as $n) {
            $recent[] = [
                'type' => 'note',
                'title' => $n['title'] ?? '',
                'category' => $n['category_name'] ?? 'Umum',
                'time' => $n['created_at'] ?? '',
            ];
        }

        // ensure recent items sorted by time roughly — leave as-is for simplicity

        return response()->json([
            'stats' => $stats,
            'today_schedules' => $todaySchedules,
            'todo_overview' => $todoOverview,
            'note_overview' => $noteOverview,
            'recent_activities' => $recent,
        ]);
    }
}
