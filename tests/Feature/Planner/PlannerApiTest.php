<?php

use App\Models\Category;
use App\Models\Note;
use App\Models\Schedule;
use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('Weekly Grid API', function () {
    it('returns weekly grid data for authenticated user', function () {
        $monday = Carbon::parse('next monday')->startOfDay();
        $wednesday = $monday->copy()->addDays(2);

        Schedule::factory()->forUser($this->user)->onDate($monday->toDateString())->create([
            'title' => 'Meeting Senin',
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);

        Schedule::factory()->forUser($this->user)->onDate($wednesday->toDateString())->create([
            'title' => 'Rapat Rabu',
        ]);

        Todo::factory()->forUser($this->user)->create([
            'title' => 'Todo Rabu',
            'due_date' => $wednesday->toDateString(),
        ]);

        $category = Category::factory()->forUser($this->user)->create();
        Note::factory()->inCategory($category)->create([
            'title' => 'Catatan Minggu',
            'created_at' => $wednesday,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/planner-api/weekly-grid?date='.$wednesday->toDateString());

        $response->assertOk()
            ->assertJsonStructure([
                'week' => ['anchor_date', 'start_date', 'end_date', 'label'],
                'days' => [
                    '*' => [
                        'date', 'day_name', 'day_label', 'is_today',
                        'schedule_count', 'todo_count', 'note_count', 'completed_todo_count',
                        'schedules', 'todos', 'notes',
                    ],
                ],
                'todo_overview' => ['total', 'completed', 'pending', 'items'],
                'note_overview' => ['total', 'items'],
                'summary',
            ]);

        expect($response->json('days'))->toHaveCount(7);
        expect($response->json('summary.total_schedules'))->toBe(2);
        expect($response->json('summary.total_weekly_todos'))->toBe(1);
        expect($response->json('summary.total_weekly_notes'))->toBe(1);
        expect(collect($response->json('days'))->firstWhere('date', $wednesday->toDateString())['note_count'])->toBe(1);
        expect(collect($response->json('days'))->firstWhere('date', $wednesday->toDateString())['notes'][0]['title'])->toBe('Catatan Minggu');
    });

    it('only shows data belonging to the authenticated user', function () {
        $otherUser = User::factory()->create();
        $date = Carbon::parse('next monday');

        Schedule::factory()->forUser($otherUser)->onDate($date->toDateString())->create();
        Schedule::factory()->forUser($this->user)->onDate($date->toDateString())->create();

        $response = $this->actingAs($this->user)
            ->getJson('/planner-api/weekly-grid?date='.$date->toDateString());

        $response->assertOk();
        expect($response->json('summary.total_schedules'))->toBe(1);
    });

    it('requires authentication', function () {
        $this->getJson('/planner-api/weekly-grid')->assertUnauthorized();
    });
});

describe('Schedule API', function () {
    it('creates a schedule with valid data', function () {
        $date = now()->addDay()->toDateString();

        $response = $this->actingAs($this->user)->postJson('/planner-api/schedules', [
            'title' => 'Belajar Laravel',
            'description' => 'Phase schedule module',
            'date' => $date,
            'start_time' => '08:00',
            'end_time' => '10:00',
            'priority' => 'high',
            'color' => '#FF5733',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Belajar Laravel')
            ->assertJsonPath('data.priority', 'high');

        $this->assertDatabaseHas('schedules', [
            'user_id' => $this->user->id,
            'title' => 'Belajar Laravel',
            'date' => $date,
        ]);
    });

    it('rejects schedule with past date', function () {
        $response = $this->actingAs($this->user)->postJson('/planner-api/schedules', [
            'title' => 'Jadwal Lama',
            'date' => now()->subDay()->toDateString(),
            'start_time' => '08:00',
            'end_time' => '09:00',
            'priority' => 'medium',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['date']);
    });

    it('rejects end time before start time', function () {
        $response = $this->actingAs($this->user)->postJson('/planner-api/schedules', [
            'title' => 'Invalid Time',
            'date' => now()->addDay()->toDateString(),
            'start_time' => '10:00',
            'end_time' => '08:00',
            'priority' => 'medium',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['end_time']);
    });

    it('updates and deletes owned schedule', function () {
        $schedule = Schedule::factory()->forUser($this->user)->create([
            'date' => now()->addDay()->toDateString(),
        ]);

        $this->actingAs($this->user)
            ->patchJson("/planner-api/schedules/{$schedule->id}", [
                'title' => 'Updated Title',
            ])
            ->assertOk()
            ->assertJsonPath('data.title', 'Updated Title');

        $this->actingAs($this->user)
            ->deleteJson("/planner-api/schedules/{$schedule->id}")
            ->assertOk();

        $this->assertDatabaseMissing('schedules', ['id' => $schedule->id]);
    });

    it('prevents accessing another users schedule', function () {
        $otherUser = User::factory()->create();
        $schedule = Schedule::factory()->forUser($otherUser)->create([
            'date' => now()->addDay()->toDateString(),
        ]);

        $this->actingAs($this->user)
            ->getJson("/planner-api/schedules/{$schedule->id}")
            ->assertNotFound();
    });

    it('generates todo from schedule without duplication', function () {
        $schedule = Schedule::factory()->forUser($this->user)->create([
            'title' => 'Presentasi',
            'date' => now()->addDay()->toDateString(),
        ]);

        $first = $this->actingAs($this->user)
            ->postJson("/planner-api/schedules/{$schedule->id}/generate-todo");

        $first->assertCreated()
            ->assertJsonPath('data.title', 'Presentasi')
            ->assertJsonPath('data.schedule_id', $schedule->id);

        $second = $this->actingAs($this->user)
            ->postJson("/planner-api/schedules/{$schedule->id}/generate-todo");

        $second->assertOk();

        expect(Todo::where('schedule_id', $schedule->id)->count())->toBe(1);
    });
});

describe('Todo API', function () {
    it('creates manual todo', function () {
        $response = $this->actingAs($this->user)->postJson('/planner-api/todos', [
            'title' => 'Beli buku',
            'due_date' => now()->addDays(2)->toDateString(),
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Beli buku');

        $this->assertDatabaseHas('todos', [
            'user_id' => $this->user->id,
            'title' => 'Beli buku',
            'schedule_id' => null,
        ]);
    });

    it('creates todo linked to schedule with auto due date', function () {
        $date = now()->addDay()->toDateString();
        $schedule = Schedule::factory()->forUser($this->user)->onDate($date)->create();

        $response = $this->actingAs($this->user)->postJson('/planner-api/todos', [
            'title' => 'Todo dari jadwal',
            'schedule_id' => $schedule->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.schedule_id', $schedule->id)
            ->assertJsonPath('data.due_date', $date);
    });

    it('toggles todo completion status', function () {
        $todo = Todo::factory()->forUser($this->user)->create(['completed' => false]);

        $this->actingAs($this->user)
            ->patchJson("/planner-api/todos/{$todo->id}?toggle=1")
            ->assertOk()
            ->assertJsonPath('data.completed', true);
    });

    it('filters todos by status and week', function () {
        $monday = Carbon::parse('next monday');

        Todo::factory()->forUser($this->user)->create([
            'title' => 'Active',
            'completed' => false,
            'due_date' => $monday->toDateString(),
        ]);

        Todo::factory()->forUser($this->user)->completed()->create([
            'title' => 'Done',
            'due_date' => $monday->copy()->addDay()->toDateString(),
        ]);

        $active = $this->actingAs($this->user)
            ->getJson('/planner-api/todos?filter=active&week_date='.$monday->toDateString());

        $active->assertOk();
        expect($active->json('data'))->toHaveCount(1);
        expect($active->json('data.0.title'))->toBe('Active');
    });

    it('prevents accessing another users todo', function () {
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->forUser($otherUser)->create();

        $this->actingAs($this->user)
            ->deleteJson("/planner-api/todos/{$todo->id}")
            ->assertNotFound();
    });
});

describe('Note & Category API', function () {
    it('creates category and note', function () {
        $category = $this->actingAs($this->user)
            ->postJson('/planner-api/categories', [
                'name' => 'Kuliah',
                'color' => '#3366FF',
            ])
            ->assertCreated()
            ->json('data');

        $response = $this->actingAs($this->user)->postJson('/planner-api/notes', [
            'category_id' => $category['id'],
            'title' => 'Catatan Algoritma',
            'content' => 'Review materi sorting.',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Catatan Algoritma');

        $this->assertDatabaseHas('notes', [
            'user_id' => $this->user->id,
            'category_id' => $category['id'],
            'title' => 'Catatan Algoritma',
        ]);
    });

    it('rejects duplicate category name for same user', function () {
        Category::factory()->forUser($this->user)->create(['name' => 'Personal']);

        $this->actingAs($this->user)
            ->postJson('/planner-api/categories', ['name' => 'Personal'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    it('filters notes by category and search', function () {
        $catA = Category::factory()->forUser($this->user)->create(['name' => 'A']);
        $catB = Category::factory()->forUser($this->user)->create(['name' => 'B']);

        Note::factory()->inCategory($catA)->create(['title' => 'Laravel Tips', 'content' => 'Eloquent']);
        Note::factory()->inCategory($catB)->create(['title' => 'Other', 'content' => 'Random']);

        $filtered = $this->actingAs($this->user)
            ->getJson('/planner-api/notes?category='.$catA->id.'&search=Laravel');

        $filtered->assertOk();
        expect($filtered->json('data'))->toHaveCount(1);
        expect($filtered->json('data.0.title'))->toBe('Laravel Tips');
    });

    it('prevents using another users category for note', function () {
        $otherUser = User::factory()->create();
        $category = Category::factory()->forUser($otherUser)->create();

        $this->actingAs($this->user)
            ->postJson('/planner-api/notes', [
                'category_id' => $category->id,
                'title' => 'Hack',
                'content' => 'Should fail',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['category_id']);
    });

    it('filters notes by week_date', function () {
        $monday = Carbon::parse('next monday')->startOfDay();
        $tuesday = $monday->copy()->addDay();
        $category = Category::factory()->forUser($this->user)->create();

        Note::factory()->inCategory($category)->create([
            'title' => 'Note Monday',
            'created_at' => $monday,
        ]);

        Note::factory()->inCategory($category)->create([
            'title' => 'Note Next Week',
            'created_at' => $monday->copy()->addWeek(),
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/planner-api/notes?week_date='.$tuesday->toDateString());

        $response->assertOk();
        expect($response->json('data'))->toHaveCount(1);
        expect($response->json('data.0.title'))->toBe('Note Monday');
    });
});
