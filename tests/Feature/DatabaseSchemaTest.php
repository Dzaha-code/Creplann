<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_weekly_planner_tables_exist(): void
    {
        $this->assertTrue(\Schema::hasTable('users'));
        $this->assertTrue(\Schema::hasTable('schedules'));
        $this->assertTrue(\Schema::hasTable('todos'));
        $this->assertTrue(\Schema::hasTable('categories'));
        $this->assertTrue(\Schema::hasTable('notes'));
    }
}
