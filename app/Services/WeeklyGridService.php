<?php

namespace App\Services;

use App\Models\Note;
use App\Models\Schedule;
use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;

class WeeklyGridService
{
    public function buildForUser(User $user, string|null $anchorDate = null): array
    {
        [$weekStart, $weekEnd, $anchor] = $this->resolveWeekRange($anchorDate);

        $schedules = $user->schedules()
            ->withCount('todos')
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $todos = $user->todos()
            ->with('schedule')
            ->whereBetween('due_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->orderBy('due_date')
            ->orderBy('created_at')
            ->get();

        $notes = $user->notes()
            ->with('category')
            ->whereBetween('created_at', [$weekStart->copy()->startOfDay(), $weekEnd->copy()->endOfDay()])
            ->latest()
            ->get();

        $scheduleMap = $schedules->groupBy(fn (Schedule $schedule) => $schedule->date->toDateString());
        $todoMap = $todos->groupBy(fn (Todo $todo) => $todo->due_date?->toDateString());

        $days = collect(range(0, 6))->map(function (int $offset) use ($weekStart, $scheduleMap, $todoMap) {
            $currentDay = $weekStart->copy()->addDays($offset);
            $dateKey = $currentDay->toDateString();
            $daySchedules = $scheduleMap->get($dateKey, collect())->values();
            $dayTodos = $todoMap->get($dateKey, collect())->values();

            return [
                'date' => $dateKey,
                'day_name' => $currentDay->translatedFormat('l'),
                'day_label' => $currentDay->translatedFormat('D, d M'),
                'is_today' => $currentDay->isToday(),
                'schedule_count' => $daySchedules->count(),
                'todo_count' => $dayTodos->count(),
                'completed_todo_count' => $dayTodos->where('completed', true)->count(),
                'schedules' => $daySchedules->map(
                    fn (Schedule $schedule) => $this->transformSchedule($schedule)
                )->all(),
            ];
        })->all();

        return [
            'week' => [
                'anchor_date' => $anchor->toDateString(),
                'start_date' => $weekStart->toDateString(),
                'end_date' => $weekEnd->toDateString(),
                'label' => $weekStart->translatedFormat('d M').' - '.$weekEnd->translatedFormat('d M Y'),
            ],
            'days' => $days,
            'todo_overview' => [
                'total' => $todos->count(),
                'completed' => $todos->where('completed', true)->count(),
                'pending' => $todos->where('completed', false)->count(),
                'items' => $todos->map(fn (Todo $todo) => $this->transformTodo($todo))->all(),
            ],
            'note_overview' => [
                'total' => $notes->count(),
                'items' => $notes->map(fn (Note $note) => $this->transformNote($note))->all(),
            ],
            'summary' => [
                'total_schedules' => $schedules->count(),
                'schedules_with_generated_todo' => $schedules->where('todos_count', '>', 0)->count(),
                'total_weekly_todos' => $todos->count(),
                'completed_weekly_todos' => $todos->where('completed', true)->count(),
                'total_weekly_notes' => $notes->count(),
            ],
        ];
    }

    public function resolveWeekRange(string|null $anchorDate = null): array
    {
        $anchor = $anchorDate
            ? Carbon::parse($anchorDate)->startOfDay()
            : now()->startOfDay();

        $weekStart = $anchor->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $anchor->copy()->endOfWeek(Carbon::SUNDAY);

        return [$weekStart, $weekEnd, $anchor];
    }

    private function transformSchedule(Schedule $schedule): array
    {
        return [
            'id' => $schedule->id,
            'title' => $schedule->title,
            'description' => $schedule->description,
            'date' => $schedule->date->toDateString(),
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'priority' => $schedule->priority,
            'color' => $schedule->color,
            'todo_generated' => $schedule->todos_count > 0,
            'todos_count' => $schedule->todos_count,
        ];
    }

    private function transformTodo(Todo $todo): array
    {
        return [
            'id' => $todo->id,
            'schedule_id' => $todo->schedule_id,
            'title' => $todo->title,
            'completed' => $todo->completed,
            'due_date' => $todo->due_date?->toDateString(),
            'schedule_title' => $todo->schedule?->title,
        ];
    }

    private function transformNote(Note $note): array
    {
        return [
            'id' => $note->id,
            'category_id' => $note->category_id,
            'category_name' => $note->category?->name,
            'title' => $note->title,
            'content' => $note->content,
            'created_at' => $note->created_at?->toISOString(),
        ];
    }
}
