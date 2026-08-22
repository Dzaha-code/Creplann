<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('completed');
            $table->index(['user_id', 'completed', 'completed_at'], 'todos_user_completed_completed_at_index');
        });

        // Backfill untuk data lama: anggap `updated_at` sebagai waktu selesai.
        DB::table('todos')
            ->where('completed', true)
            ->whereNull('completed_at')
            ->update(['completed_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropIndex('todos_user_completed_completed_at_index');
            $table->dropColumn('completed_at');
        });
    }
};

