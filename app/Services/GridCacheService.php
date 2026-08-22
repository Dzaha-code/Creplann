<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * Query cache untuk weekly grid — versioned per user.
 *
 * Daripada menghapus semua kunci cache milik user satu per satu (Laravel
 * tidak mendukung wildcard delete), kita "bump" nomor versi user.
 * Kunci cache lama otomatis basi karena versinya tidak cocok lagi.
 */
class GridCacheService
{
    /** Masa berlaku hasil grid (detik). */
    private const TTL = 300;

    /**
     * Ambil grid dari cache bila ada, jika tidak jalankan $callback
     * lalu simpan hasilnya. Satu kunci per (user, minggu).
     */
    public static function remember(User $user, string $weekStartDate, callable $callback): mixed
    {
        $version = self::version($user->id);

        $key = sprintf('weekly-grid:v%d:u%d:%s', $version, $user->id, $weekStartDate);

        return Cache::remember($key, self::TTL, $callback);
    }

    /**
     * Invalidasi semua grid milik user (dipanggil dari model events
     * saat schedule/todo/note/category berubah).
     */
    public static function flush(int $userId): void
    {
        $key = self::versionKey($userId);

        if (! Cache::has($key)) {
            Cache::forever($key, 1);
        }

        Cache::increment($key);
    }

    private static function version(int $userId): int
    {
        return (int) Cache::get(self::versionKey($userId), 1);
    }

    private static function versionKey(int $userId): string
    {
        return sprintf('weekly-grid:version:u%d', $userId);
    }
}
