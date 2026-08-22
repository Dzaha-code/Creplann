<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'is_published',
        'published_at',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'views'        => 'integer',
        ];
    }

    // ── Relationships ────────────────────────────────────────────

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ── Scopes ───────────────────────────────────────────────────

    /**
     * Hanya post yang sudah dipublish dan waktu publish-nya sudah lewat.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(fn (Builder $q) => $q
                ->whereNull('published_at')
                ->orWhere('published_at', '<=', now())
            );
    }

    // ── Helpers ──────────────────────────────────────────────────

    /**
     * Estimasi waktu baca (menit) berdasarkan jumlah kata konten.
     * Rata-rata pembaca memproses ~200 kata/menit.
     */
    public function readingTimeMinutes(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));

        return max(1, (int) ceil($wordCount / 200));
    }

    /**
     * Tambah view count — dilakukan di controller show().
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Auto-generate slug dari title jika belum diset.
     */
    public static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
