<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Note>
 */
class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => fake()->sentence(3),
            'content' => fake()->paragraph(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => ['user_id' => $user->id]);
    }

    public function inCategory(Category $category): static
    {
        return $this->state(fn () => [
            'user_id' => $category->user_id,
            'category_id' => $category->id,
        ]);
    }
}
