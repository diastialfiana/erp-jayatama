<nav class="navbar">
    <button class="hamburger" id="hamburger" title="Toggle Sidebar">
        <svg viewBox="0 0 24 24">
            <line x1="3" y1="8" x2="21" y2="8" />
            <line x1="3" y1="16" x2="21" y2="16" />
        </svg>
    </button>

    <span class="nb-page-title">@yield('title', 'Dashboard')</span>

    <div class="nb-right">
        <!-- Notification -->
        <button class="notif-btn" title="Notifikasi">
            <svg viewBox="0 0 24 24">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
            </svg>
            <span class="notif-badge"></span>
        </button>

        <!-- User Menu -->
        <div class="user-menu" id="userMenuEl">
            <button class="user-trigger" id="userTrigger">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
                </div>
                <div class="user-details">
                    <div class="user-name">{{ auth()->user()->nama_lengkap }}</div>
                    <div class="user-role">{{ auth()->user()->roles->pluck('name')->implode(', ') }}</div>
                </div>
                <svg class="user-chevron" viewBox="0 0 24 24">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </button>

            <div class="dropdown">
                <div class="dd-header">
                    <strong>{{ auth()->user()->nama_lengkap }}</strong>
                    NIP: {{ auth()->user()->nip }}
                </div>
                <a href="#" class="dd-item">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Profile Saya
                </a>
                <a href="{{ route('password.change') }}" class="dd-item">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    Ubah Password
                </a>
                <a href="#" class="dd-item">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3" />
                        <path
                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                    </svg>
                    Pengaturan
                </a>
                <div class="dd-divider"></div>
                <div class="dd-item logout-form">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">
                            <svg viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                            Keluar dari Sistem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>