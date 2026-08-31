<?php

namespace App\Http\Controllers;

use App\Services\WeeklyGridService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Render halaman dashboard — hanya kirim data ringan ke view.
     * Data berat (grid, stats) di-fetch via JS ke endpoint api() di bawah.
     */
    public function __invoke(Request $request): View
    {
        return view('dashboard', [
            'user' => $request->user(),
        ]);
    }

    /**
     * JSON endpoint — dipanggil oleh dashboard.js via fetch().
     * Di sinilah WeeklyGridService dipanggil sekali saja.
     */
    public function api(Request $request, WeeklyGridService $weeklyGridService): JsonResponse
    {
        $user = $request->user();

        // Satu build yang di-cache 5 menit via GridCacheService
        $grid = $weeklyGridService->buildForUser($user);

        $todoOverview = $grid['todo_overview'];
        $noteOverview = $grid['note_overview'];
        $today        = now()->toDateString();
        $todayDay     = collect($grid['days'])->firstWhere('date', $today);

        // Statistik ringkas untuk bento tiles
        $stats = [
            'total_notes'     => $noteOverview['total'] ?? 0,
            'total_schedules' => $grid['summary']['total_schedules'] ?? 0,
            'total_todos'     => $todoOverview['total'] ?? 0,
            'completed_todos' => $todoOverview['completed'] ?? 0,
            'pending_todos'   => $todoOverview['pending'] ?? 0,
        ];

        // Recent activities: gabungkan todo + schedule hari ini + notes (max 5 masing2)
        $recent = [];

        foreach (array_slice($todoOverview['items'], 0, 5) as $t) {
            $recent[] = [
                'type'     => 'todo',
                'title'    => $t['title'] ?? '',
                'category' => $t['schedule_title'] ?? '',
                'time'     => $t['due_date'] ?? '',
            ];
        }

        foreach (array_slice($todayDay['schedules'] ?? [], 0, 5) as $s) {
            $recent[] = [
                'type'     => 'schedule',
                'title'    => $s['title'] ?? '',
                'category' => '',
                'time'     => $s['start_time'] ?? '',
            ];
        }

        foreach (array_slice($noteOverview['items'], 0, 5) as $n) {
            $recent[] = [
                'type'     => 'note',
                'title'    => $n['title'] ?? '',
                'category' => $n['category_name'] ?? 'Umum',
                'time'     => $n['created_at'] ?? '',
            ];
        }

        return response()->json([
            'stats'             => $stats,
            'today_schedules'   => $todayDay['schedules'] ?? [],
            'todo_overview'     => $todoOverview,
            'note_overview'     => $noteOverview,
            'recent_activities' => $recent,
        ]);
    }
}
