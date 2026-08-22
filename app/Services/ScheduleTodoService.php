<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Todo;

class ScheduleTodoService
{
    public function generateFromSchedule(Schedule $schedule): array
    {
        $todo = Todo::firstOrNew([
            'user_id' => $schedule->user_id,
            'schedule_id' => $schedule->id,
        ]);

        $wasRecentlyCreated = ! $todo->exists;

        $todo->fill([
            'title' => $schedule->title,
            'due_date' => $schedule->date->toDateString(),
        ]);

        if ($wasRecentlyCreated) {
            $todo->completed = false;
        }

        $todo->save();

        return [
            'todo' => $todo->fresh('schedule'),
            'created' => $wasRecentlyCreated,
        ];
    }

    public function syncLinkedTodo(Schedule $schedule): ?Todo
    {
        $todo = Todo::query()
            ->where('user_id', $schedule->user_id)
            ->where('schedule_id', $schedule->id)
            ->first();

        if (! $todo) {
            return null;
        }

        $todo->update([
            'title' => $schedule->title,
            'due_date' => $schedule->date->toDateString(),
        ]);

        return $todo->fresh('schedule');
    }
}
