<x-public-layout>
    <x-slot name="metaTitle">Hubungi Kami — CrePlann</x-slot>
    <x-slot name="metaDescription">Ada pertanyaan, masukan, atau butuh bantuan? Kirim pesan ke tim CrePlann dan kami akan segera merespons.</x-slot>

    <x-slot name="header">
        <div class="ct-page-header">
            <div class="ct-header-inner">
                <div class="ct-header-icon" aria-hidden="true">
                    <i class="ti ti-mail"></i>
                </div>
                <div>
                    <h1 class="ct-page-title">Hubungi Kami</h1>
                    <p class="ct-page-sub">Kami siap membantu, biasanya respons dalam 1×24 jam</p>
                </div>
            </div>
        </div>
    </x-slot>

    @push('head')
        @vite(['resources/css/pages/contact.css', 'resources/js/pages/contact.js'])
    @endpush

    <div class="ct-wrap">

        <div class="ct-layout">

            {{-- ── Sidebar info ─────────────────────────────── --}}
            <aside class="ct-sidebar" aria-label="Informasi kontak">

                <div class="ct-info-card">
                    <div class="ct-info-icon" aria-hidden="true">
                        <i class="ti ti-clock-hour-4"></i>
                    </div>
                    <div>
                        <div class="ct-info-label">Waktu Respons</div>
                        <div class="ct-info-value">1×24 jam</div>
                    </div>
                </div>

                <div class="ct-info-card">
                    <div class="ct-info-icon" aria-hidden="true">
                        <i class="ti ti-mail-opened"></i>
                    </div>
                    <div>
                        <div class="ct-info-label">Email Kami</div>
                        <div class="ct-info-value">
                            <a href="mailto:hello@creplann.app" class="ct-link">hello@creplann.app</a>
                        </div>
                    </div>
                </div>

                <div class="ct-info-card">
                    <div class="ct-info-icon" aria-hidden="true">
                        <i class="ti ti-question-mark"></i>
                    </div>
                    <div>
                        <div class="ct-info-label">Punya pertanyaan umum?</div>
                        <div class="ct-info-value">
                            <a href="{{ route('help.index') }}" class="ct-link">
                                Cek Pusat Bantuan
                                <i class="ti ti-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Decorative ticket-stub strip --}}
                <div class="ct-sidebar-deco" aria-hidden="true">
                    <span class="ct-deco-label">CrePlann</span>
                    <span class="ct-deco-sub">Weekly Planner</span>
                </div>

            </aside>

            {{-- ── Form area ────────────────────────────────── --}}
            <div class="ct-form-area">

                <div class="ct-form-card">
                    <div class="ct-form-card-head">
                        <h2 class="ct-form-title">Kirim Pesan</h2>
                        <p class="ct-form-lede">Isi form di bawah dan kami akan menghubungi Anda secepatnya.</p>
                    </div>

                    <form id="contactForm" novalidate aria-label="Form kontak">
                        @csrf

                        <div class="ct-field-row">
                            {{-- Nama --}}
                            <div class="ct-field" id="field_name">
                                <label class="ct-label" for="ct_name">
                                    Nama <span class="ct-required" aria-hidden="true">*</span>
                                </label>
                                <input
                                    id="ct_name"
                                    name="name"
                                    type="text"
                                    class="ct-input"
                                    placeholder="Nama lengkap Anda"
                                    maxlength="255"
                                    autocomplete="name"
                                    required
                                    aria-describedby="err_name"
                                >
                                <p class="ct-field-error" id="err_name" aria-live="polite"></p>
                            </div>

                            {{-- Email --}}
                            <div class="ct-field" id="field_email">
                                <label class="ct-label" for="ct_email">
                                    Email <span class="ct-required" aria-hidden="true">*</span>
                                </label>
                                <input
                                    id="ct_email"
                                    name="email"
                                    type="email"
                                    class="ct-input"
                                    placeholder="email@Anda.com"
                                    maxlength="255"
                                    autocomplete="email"
                                    required
                                    aria-describedby="err_email"
                                >
                                <p class="ct-field-error" id="err_email" aria-live="polite"></p>
                            </div>
                        </div>

                        {{-- Subjek --}}
                        <div class="ct-field" id="field_subject">
                            <label class="ct-label" for="ct_subject">
                                Subjek <span class="ct-required" aria-hidden="true">*</span>
                            </label>
                            <input
                                id="ct_subject"
                                name="subject"
                                type="text"
                                class="ct-input"
                                placeholder="Topik yang ingin Anda tanyakan"
                                maxlength="255"
                                required
                                aria-describedby="err_subject"
                            >
                            <p class="ct-field-error" id="err_subject" aria-live="polite"></p>
                        </div>

                        {{-- Pesan --}}
                        <div class="ct-field" id="field_message">
                            <label class="ct-label" for="ct_message">
                                Pesan <span class="ct-required" aria-hidden="true">*</span>
                            </label>
                            <textarea
                                id="ct_message"
                                name="message"
                                class="ct-input ct-textarea"
                                rows="6"
                                placeholder="Tulis pesan Anda di sini…"
                                required
                                aria-describedby="err_message"
                            ></textarea>
                            <p class="ct-field-error" id="err_message" aria-live="polite"></p>
                        </div>

                        {{-- Global error --}}
                        <div class="ct-form-global-error" id="ct_global_error" role="alert" aria-live="assertive" hidden></div>

                        <div class="ct-form-actions">
                            <button type="submit" class="ct-submit-btn" id="ct_submit">
                                <i class="ti ti-send" aria-hidden="true"></i>
                                <span id="ct_submit_label">Kirim Pesan</span>
                            </button>
                            <span class="ct-required-note">
                                <span aria-hidden="true">*</span> Wajib diisi
                            </span>
                        </div>
                    </form>

                </div>{{-- /.ct-form-card --}}
            </div>

        </div>{{-- /.ct-layout --}}
    </div>{{-- /.ct-wrap --}}

    {{-- Toast notifikasi --}}
    <div id="ct_toast" class="ct-toast" role="status" aria-live="polite"></div>

</x-public-layout>
