<?php

namespace App\Http\Controllers;

use App\Services\WeeklyGridService;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}
