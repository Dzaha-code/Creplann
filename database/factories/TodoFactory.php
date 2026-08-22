<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Todo>
 */
class TodoFactory extends Factory
{
    protected $model = Todo::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'schedule_id' => null,
            'title' => fake()->sentence(3),
            'completed' => false,
            'due_date' => fake()->optional()->dateTimeBetween('today', '+2 weeks')?->format('Y-m-d'),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => ['user_id' => $user->id]);
    }

    public function fromSchedule(Schedule $schedule): static
    {
        return $this->state(fn () => [
            'user_id' => $schedule->user_id,
            'schedule_id' => $schedule->id,
            'title' => $schedule->title,
            'due_date' => $schedule->date->toDateString(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'completed' => true,
            'completed_at' => now(),
        ]);
    }
}
