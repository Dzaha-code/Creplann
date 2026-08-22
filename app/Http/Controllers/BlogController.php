<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Listing semua post yang sudah dipublish, paginate 9 per halaman.
     */
    public function index(): View
    {
        $posts = Post::published()
            ->with('author:id,name')
            ->latest('published_at')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    /**
     * Halaman detail artikel berdasarkan slug.
     * Tambah view count dan tampilkan 3 artikel rekomendasi.
     */
    public function show(string $slug): View
    {
        // Hanya tampilkan post yang dipublish — 404 jika draft atau tidak ditemukan
        $post = Post::published()
            ->with('author:id,name')
            ->where('slug', $slug)
            ->firstOrFail();

        // Tambah view count (tidak perlu menunggu response)
        $post->incrementViews();

        // Rekomendasi: 3 post terbaru selain yang sedang dibaca
        $related = Post::published()
            ->with('author:id,name')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'related'));
    }
}
