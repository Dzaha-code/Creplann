{{--
    Navbar kapsul — disimpan terpisah supaya bisa di-@include dari layouts/app.blade.php
    Sesuaikan nama route di routeIs() dengan route asli project-mu jika berbeda.
--}}
<div class="navbar-wrap">
    <nav class="navbar-capsule">

        <a href="{{ url('/dashboard') }}" class="nav-brand">
            <svg viewBox="0 0 40 40" fill="none">
                <path d="M8 12c0-2.2 1.8-4 4-4h16c2.2 0 4 1.8 4 4v18c0 2.2-1.8 4-4 4H12c-2.2 0-4-1.8-4-4V12z" stroke="#241F1A" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M13 5.5v6M27 5.5v6" stroke="#E15B3F" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M13 21l4 4 9-9" stroke="#E15B3F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           @if (request()->routeIs('dashboard')) aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none">
                <rect x="3.5" y="3.5" width="7.5" height="7.5" rx="2" stroke="currentColor" stroke-width="1.7"/>
                <rect x="13" y="3.5" width="7.5" height="7.5" rx="2" stroke="currentColor" stroke-width="1.7"/>
                <rect x="3.5" y="13" width="7.5" height="7.5" rx="2" stroke="currentColor" stroke-width="1.7"/>
                <rect x="13" y="13" width="7.5" height="7.5" rx="2" stroke="currentColor" stroke-width="1.7"/>
            </svg>
            <span class="label">Dashboard</span>
        </a>

        <a href="{{ route('schedule.index') }}"
           class="nav-item {{ request()->routeIs('schedule*') ? 'active' : '' }}"
           @if (request()->routeIs('schedule*')) aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none">
                <rect x="3.5" y="4.5" width="17" height="16" rx="2.5" stroke="currentColor" stroke-width="1.7"/>
                <path d="M8 2.8v3.6M16 2.8v3.6M3.5 9.5h17" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                <path d="M7.5 13.5h2.4M11.5 13.5h2.4M15.5 13.5h1.2M7.5 17h2.4M11.5 17h2.4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
            </svg>
            <span class="label">Schedule</span>
        </a>

        <a href="{{ route('todo.index') }}"
           class="nav-item {{ request()->routeIs('todo*') ? 'active' : '' }}"
           @if (request()->routeIs('todo*')) aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none">
                <rect x="3.5" y="3.5" width="17" height="17" rx="3" stroke="currentColor" stroke-width="1.7"/>
                <path d="M7.5 12l2.6 2.6L16.5 9" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="label">Todo</span>
        </a>

        <a href="{{ route('note.index') }}"
           class="nav-item {{ request()->routeIs('note*') ? 'active' : '' }}"
           @if (request()->routeIs('note*')) aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M6 3.5h9l3.5 3.5V19a1.6 1.6 0 01-1.6 1.6H6A1.6 1.6 0 014.4 19V5.1A1.6 1.6 0 016 3.5z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                <path d="M15 3.5V7h3.5" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                <path d="M8 12h8M8 15.5h5.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
            </svg>
            <span class="label">Notes</span>
        </a>

        <a href="{{ route('profile.edit') }}"
           class="nav-item {{ request()->routeIs('profile*') ? 'active' : '' }}"
           @if (request()->routeIs('profile*')) aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="8.3" r="3.6" stroke="currentColor" stroke-width="1.7"/>
                <path d="M4.8 19.6c1.2-3.4 4-5 7.2-5s6 1.6 7.2 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
            </svg>
            <span class="label">Profile</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
            @csrf
            <button type="submit" class="nav-logout" title="Keluar" aria-label="Keluar">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M9 4H6.5A2.5 2.5 0 004 6.5v11A2.5 2.5 0 006.5 20H9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    <path d="M20 12H10.5M20 12l-3.5-3.5M20 12l-3.5 3.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </form>

    </nav>
</div>