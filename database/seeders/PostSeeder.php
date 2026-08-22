<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Gunakan user pertama yang ada, atau buat baru jika belum ada
        $author = User::first() ?? User::factory()->create([
            'name'  => 'CrePlann Editorial',
            'email' => 'editorial@creplann.test',
        ]);

        // Buat 12 post published dengan author yang sama
        Post::factory()
            ->count(12)
            ->published()
            ->forAuthor($author)
            ->create();

        // 2 draft — tidak muncul di frontend
        Post::factory()
            ->count(2)
            ->draft()
            ->forAuthor($author)
            ->create();

        $this->command->info('✓ 14 posts seeded (12 published, 2 draft).');
    }
}
