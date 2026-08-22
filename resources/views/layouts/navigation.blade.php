<div class="navbar-wrap">
    <nav class="navbar-capsule" aria-label="Navigasi utama">

        <!-- Brand -->
        <a href="{{ route('dashboard') }}" class="nav-brand" title="{{ config('app.name', 'CrePlann') }}">
            <svg viewBox="0 0 40 40" fill="none" aria-hidden="true">
                <path d="M8 12c0-2.2 1.8-4 4-4h16c2.2 0 4 1.8 4 4v18c0 2.2-1.8 4-4 4H12c-2.2 0-4-1.8-4-4V12z" stroke="#26251e" stroke-width="1.6" stroke-linejoin="round"/>
                <path d="M13 5.5v6M27 5.5v6" stroke="#c08532" stroke-width="1.6" stroke-linecap="round"/>
                <path d="M13 21l4 4 9-9" stroke="#c08532" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="brand-name">{{ config('app.name', 'CrePlann') }}</span>
        </a>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           @if (request()->routeIs('dashboard')) aria-current="page" @endif>
            <span class="icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3.5" y="3.5" width="7.5" height="7.5" rx="1.5"/>
                    <rect x="13" y="3.5" width="7.5" height="7.5" rx="1.5"/>
                    <rect x="3.5" y="13" width="7.5" height="7.5" rx="1.5"/>
                    <rect x="13" y="13" width="7.5" height="7.5" rx="1.5"/>
                </svg>
            </span>
            <span class="label">Dashboard</span>
            <span class="indicator"></span>
        </a>

        <!-- Schedule -->
        <a href="{{ route('schedule.index') }}"
           class="nav-item {{ request()->routeIs('schedule*') ? 'active' : '' }}"
           @if (request()->routeIs('schedule*')) aria-current="page" @endif>
            <span class="icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3.5" y="4.5" width="17" height="16" rx="2"/>
                    <path d="M8 2.8v3.6M16 2.8v3.6M3.5 9.5h17"/>
                    <path d="M7.5 13.5h2.4M11.5 13.5h2.4M15.5 13.5h1.2M7.5 17h2.4M11.5 17h2.4"/>
                </svg>
            </span>
            <span class="label">Schedule</span>
            <span class="indicator"></span>
        </a>

        <!-- Todo -->
        <a href="{{ route('todo.index') }}"
           class="nav-item {{ request()->routeIs('todo*') ? 'active' : '' }}"
           @if (request()->routeIs('todo*')) aria-current="page" @endif>
            <span class="icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3.5" y="3.5" width="17" height="17" rx="2"/>
                    <path d="M7.5 12l2.6 2.6L16.5 9"/>
                </svg>
            </span>
            <span class="label">Todo</span>
            <span class="indicator"></span>
        </a>

        <!-- Notes -->
        <a href="{{ route('note.index') }}"
           class="nav-item {{ request()->routeIs('note*') ? 'active' : '' }}"
           @if (request()->routeIs('note*')) aria-current="page" @endif>
            <span class="icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 3.5h9l3.5 3.5V19a1.6 1.6 0 01-1.6 1.6H6A1.6 1.6 0 014.4 19V5.1A1.6 1.6 0 016 3.5z"/>
                    <path d="M15 3.5V7h3.5"/>
                    <path d="M8 12h8M8 15.5h5.5"/>
                </svg>
            </span>
            <span class="label">Notes</span>
            <span class="indicator"></span>
        </a>

        <!-- Profile -->
        <a href="{{ route('profile.edit') }}"
           class="nav-item nav-item--profile {{ request()->routeIs('profile*') ? 'active' : '' }}"
           @if (request()->routeIs('profile*')) aria-current="page" @endif
           title="{{ auth()->user()->name }}">
            @if (auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}" alt="" class="nav-avatar">
            @else
                <span class="nav-avatar nav-avatar--initial" aria-hidden="true">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
            @endif
            <span class="label">{{ auth()->user()->name }}</span>
            <span class="indicator"></span>
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
            @csrf
            <button type="submit" class="nav-logout" title="Keluar" aria-label="Keluar">
                <span class="icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 4H6.5A2.5 2.5 0 004 6.5v11A2.5 2.5 0 006.5 20H9"/>
                        <path d="M20 12H10.5M20 12l-3.5-3.5M20 12l-3.5 3.5"/>
                    </svg>
                </span>
            </button>
        </form>

    </nav>
</div>
