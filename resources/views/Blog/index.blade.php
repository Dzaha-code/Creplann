<x-public-layout>
    <x-slot name="metaTitle">Blog & Artikel — CrePlann</x-slot>
    <x-slot name="metaDescription">Tips produktivitas, panduan weekly planning, dan artikel seputar CrePlann untuk membantu Anda bekerja lebih cerdas.</x-slot>

    <x-slot name="header">
        <div class="bl-page-header">
            <div class="bl-header-inner">
                <div class="bl-header-icon" aria-hidden="true">
                    <i class="ti ti-news"></i>
                </div>
                <div>
                    <h1 class="bl-page-title">Blog & Artikel</h1>
                    <p class="bl-page-sub">Tips produktivitas seputar CrePlann</p>
                </div>
            </div>
        </div>
    </x-slot>

    @push('head')
        @vite(['resources/css/pages/blog.css'])
    @endpush

    <div class="bl-wrap">

        {{-- ── Grid artikel ─────────────────────────────────── --}}
        @if ($posts->count())
            <div class="bl-grid" id="blogGrid">
                @foreach ($posts as $post)
                    <article class="bl-card animate-enter" style="animation-delay: {{ $loop->index * 40 }}ms">

                        {{-- Gambar / placeholder --}}
                        <a href="{{ route('blog.show', $post->slug) }}"
                           class="bl-card-thumb"
                           tabindex="-1"
                           aria-hidden="true">
                            @if ($post->featured_image)
                                <img src="{{ asset('storage/'.$post->featured_image) }}"
                                     alt="{{ $post->title }}"
                                     loading="lazy"
                                     class="bl-thumb-img">
                            @else
                                {{-- Placeholder bergradien unik per artikel --}}
                                <div class="bl-thumb-placeholder" aria-hidden="true">
                                    <i class="ti ti-article"></i>
                                </div>
                            @endif
                        </a>

                        <div class="bl-card-body">
                            {{-- Meta baris atas --}}
                            <div class="bl-card-meta">
                                <span class="bl-meta-chip">
                                    <i class="ti ti-user-circle" aria-hidden="true"></i>
                                    {{ $post->author->name }}
                                </span>
                                <time class="bl-meta-date"
                                      datetime="{{ $post->published_at?->toDateString() }}">
                                    <i class="ti ti-calendar-event" aria-hidden="true"></i>
                                    {{ $post->published_at?->translatedFormat('d M Y') ?? '—' }}
                                </time>
                            </div>

                            {{-- Judul --}}
                            <h2 class="bl-card-title">
                                <a href="{{ route('blog.show', $post->slug) }}"
                                   class="bl-card-title-link">
                                    {{ $post->title }}
                                </a>
                            </h2>

                            {{-- Excerpt --}}
                            @if ($post->excerpt)
                                <p class="bl-card-excerpt">
                                    {{ \Illuminate\Support\Str::limit($post->excerpt, 100) }}
                                </p>
                            @endif
                        </div>

                        <div class="bl-card-footer">
                            <span class="bl-read-time">
                                <i class="ti ti-clock" aria-hidden="true"></i>
                                {{ $post->readingTimeMinutes() }} menit baca
                            </span>
                            <a href="{{ route('blog.show', $post->slug) }}"
                               class="nt-btn nt-btn--solid nt-btn--sm bl-read-btn"
                               aria-label="Baca artikel: {{ $post->title }}">
                                Baca
                                <i class="ti ti-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>

                    </article>
                @endforeach
            </div>

            {{-- ── Paginasi ─────────────────────────────────── --}}
            @if ($posts->hasPages())
                <nav class="bl-pagination" aria-label="Navigasi halaman artikel">
                    {{ $posts->links('pagination::simple-bootstrap-4') }}
                </nav>
            @endif

        @else
            <div class="bl-empty" role="status">
                <i class="ti ti-news-off" aria-hidden="true"></i>
                <p class="bl-empty-title">Belum ada artikel</p>
                <p class="bl-empty-sub">Nantikan konten produktivitas terbaru dari kami.</p>
            </div>
        @endif

    </div>

</x-public-layout>
