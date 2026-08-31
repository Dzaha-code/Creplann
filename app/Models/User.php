<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'avatar', 'google_id', 'email_verified_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (self $user): void {
            $user->ensureDefaultCategory();
        });
    }

    public function ensureDefaultCategory(): Category
    {
        return $this->categories()->firstOrCreate(
            ['name' => 'Umum'],
            ['color' => '#d9d9d9']
        );
    }

    /**
     * Kembalikan URL avatar yang siap dipakai di tag <img src>.
     * - Google OAuth avatar → URL eksternal, langsung dikembalikan
     * - Upload lokal (path relatif) → dikonversi ke URL storage
     * - Null → null (caller bertanggung jawab menampilkan fallback)
     */
    public function avatarUrl(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        // Sudah berupa URL (Google OAuth atau URL absolut lama)
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        // Path relatif hasil upload lokal
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->avatar);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
