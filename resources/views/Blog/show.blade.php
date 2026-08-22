<x-public-layout>
    <x-slot name="metaTitle">{{ $post->title }} — CrePlann Blog</x-slot>
    <x-slot name="metaDescription">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 160) }}</x-slot>

    @push('head')
        @vite(['resources/css/pages/blog.css'])
    @endpush

    <div class="bl-wrap bl-wrap--article">

        {{-- ── Tombol kembali ───────────────────────────────── --}}
        <div class="bl-back-row">
            <a href="{{ route('blog.index') }}" class="bl-back-btn">
                <i class="ti ti-arrow-left" aria-hidden="true"></i>
                Kembali ke Blog
            </a>
        </div>

        {{-- ── Article header ──────────────────────────────── --}}
        <header class="bl-article-header">

            {{-- Gambar featured --}}
            @if ($post->featured_image)
                <div class="bl-article-thumb">
                    <img src="{{ asset('storage/'.$post->featured_image) }}"
                         alt="{{ $post->title }}"
                         loading="eager"
                         class="bl-article-thumb-img">
                </div>
            @endif

            <h1 class="bl-article-title">{{ $post->title }}</h1>

            {{-- Meta baris --}}
            <div class="bl-article-meta">
                <span class="bl-meta-chip">
                    <i class="ti ti-user-circle" aria-hidden="true"></i>
                    {{ $post->author->name }}
                </span>
                <time class="bl-meta-date"
                      datetime="{{ $post->published_at?->toDateString() }}">
                    <i class="ti ti-calendar-event" aria-hidden="true"></i>
                    {{ $post->published_at?->translatedFormat('d F Y') ?? '—' }}
                </time>
                <span class="bl-meta-date">
                    <i class="ti ti-clock" aria-hidden="true"></i>
                    {{ $post->readingTimeMinutes() }} menit baca
                </span>
                <span class="bl-meta-date">
                    <i class="ti ti-eye" aria-hidden="true"></i>
                    {{ number_format($post->views) }} views
                </span>
            </div>

            @if ($post->excerpt)
                <p class="bl-article-lead">{{ $post->excerpt }}</p>
            @endif

        </header>

        {{-- ── Konten artikel ──────────────────────────────── --}}
        <div class="bl-article-divider" aria-hidden="true"></div>

        <div class="bl-article-content prose" id="articleContent">
            {!! $post->content !!}
        </div>

        {{-- ── Artikel lainnya ─────────────────────────────── --}}
        @if ($related->count())
            <section class="bl-related" aria-labelledby="relatedHeading">
                <div class="bl-related-header">
                    <h2 id="relatedHeading" class="bl-related-title">
                        <i class="ti ti-articles" aria-hidden="true"></i>
                        Artikel Lainnya
                    </h2>
                    <a href="{{ route('blog.index') }}" class="bl-related-all">
                        Lihat semua
                        <i class="ti ti-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>

                <div class="bl-related-grid">
                    @foreach ($related as $item)
                        <article class="bl-related-card">
                            <a href="{{ route('blog.show', $item->slug) }}"
                               class="bl-related-thumb"
                               tabindex="-1"
                               aria-hidden="true">
                                @if ($item->featured_image)
                                    <img src="{{ asset('storage/'.$item->featured_image) }}"
                                         alt="{{ $item->title }}"
                                         loading="lazy"
                                         class="bl-thumb-img">
                                @else
                                    <div class="bl-thumb-placeholder bl-thumb-placeholder--sm" aria-hidden="true">
                                        <i class="ti ti-article"></i>
                                    </div>
                                @endif
                            </a>

                            <div class="bl-related-body">
                                <time class="bl-meta-date"
                                      datetime="{{ $item->published_at?->toDateString() }}">
                                    <i class="ti ti-calendar-event" aria-hidden="true"></i>
                                    {{ $item->published_at?->translatedFormat('d M Y') ?? '—' }}
                                </time>
                                <h3 class="bl-related-item-title">
                                    <a href="{{ route('blog.show', $item->slug) }}"
                                       class="bl-card-title-link">
                                        {{ $item->title }}
                                    </a>
                                </h3>
                                @if ($item->excerpt)
                                    <p class="bl-related-excerpt">
                                        {{ \Illuminate\Support\Str::limit($item->excerpt, 80) }}
                                    </p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ── CTA bawah ───────────────────────────────────── --}}
        <div class="bl-cta-strip">
            <div class="bl-cta-inner">
                <div class="bl-cta-text">
                    <span class="bl-cta-title">Mulai rencanakan minggu Anda</span>
                    <span class="bl-cta-sub">Gratis selamanya. Tidak perlu kartu kredit.</span>
                </div>
                @auth
                    <a href="{{ route('dashboard') }}" class="nt-btn nt-btn--solid">
                        <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="nt-btn nt-btn--solid">
                        <i class="ti ti-rocket" aria-hidden="true"></i>
                        Coba Gratis
                    </a>
                @endauth
            </div>
        </div>

    </div>

</x-public-layout>
