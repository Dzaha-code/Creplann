<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Index komposit untuk query yang paling sering dijalankan:
 * - schedules: user_id + rentang tanggal (weekly grid, filter minggu)
 * - todos:     user_id + due_date (filter minggu, urutkan due_date)
 * - notes:     user_id + created_at (urutkan "terbaru")
 *
 * Index tunggal pada user_id (dari FK constrained()) tidak cukup efisien
 * karena MySQL hanya memakai satu index per query untuk filtering.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->index(['user_id', 'date'], 'schedules_user_date_index');
        });

        Schema::table('todos', function (Blueprint $table) {
            $table->index(['user_id', 'due_date'], 'todos_user_due_date_index');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'notes_user_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('schedules_user_date_index');
        });

        Schema::table('todos', function (Blueprint $table) {
            $table->dropIndex('todos_user_due_date_index');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex('notes_user_created_at_index');
        });
    }
};
