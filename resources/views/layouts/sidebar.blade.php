<a href="{{ route('dashboard') }}" class="sb-brand">
    <div class="sb-logo">
        <svg viewBox="0 0 24 24">
            <polygon points="12 2 2 7 12 12 22 7 12 2" />
            <polyline points="2 17 12 22 22 17" />
            <polyline points="2 12 12 17 22 12" />
        </svg>
    </div>
    <div class="sb-brand-text">
        <div class="sb-brand-name">JES</div>
        <div class="sb-brand-sub">Jayatama Enterprise</div>
    </div>
</a>

<nav class="sb-nav">
    {{-- MAIN --}}
    <div class="sb-section-title">Menu Utama</div>

    <div class="sb-item">
        <a href="{{ route('dashboard') }}" class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="14" width="7" height="7" rx="1" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
            </svg>
            <span class="sb-label">Dashboard</span>
        </a>
    </div>

    <div class="sb-item has-sub">
        <div class="sb-link">
            <svg class="sb-icon" viewBox="0 0 24 24">
                <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
            </svg>
            <span class="sb-label">Inventory &amp; GA</span>
            <svg class="sb-arrow" viewBox="0 0 24 24">
                <polyline points="6 9 12 15 18 9" />
            </svg>
        </div>
        <div class="sub-nav">
            <a href="{{ route('inventory.index') }}" class="sub-link">Overview Inventory</a>
        </div>
    </div>

    <div class="sb-item has-sub">
        <div class="sb-link">
            <svg class="sb-icon" viewBox="0 0 24 24">
                <line x1="12" y1="1" x2="12" y2="23" />
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
            <span class="sb-label">Finance</span>
            <svg class="sb-arrow" viewBox="0 0 24 24">
                <polyline points="6 9 12 15 18 9" />
            </svg>
        </div>
        <div class="sub-nav">
            <a href="{{ route('finance.index') }}" class="sub-link">Overview Finance</a>
        </div>
    </div>

    <div class="sb-item has-sub">
        <div class="sb-link">
            <svg class="sb-icon" viewBox="0 0 24 24">
                <line x1="18" y1="20" x2="18" y2="10" />
                <line x1="12" y1="20" x2="12" y2="4" />
                <line x1="6" y1="20" x2="6" y2="14" />
            </svg>
            <span class="sb-label">Accounting</span>
            <svg class="sb-arrow" viewBox="0 0 24 24">
                <polyline points="6 9 12 15 18 9" />
            </svg>
        </div>
        <div class="sub-nav">
            <a href="{{ route('accounting.index') }}" class="sub-link">Overview Accounting</a>
        </div>
    </div>

    {{-- ADMIN --}}
    @role('Superadmin|Admin 1|Admin 2')
    <div class="sb-section-title" style="margin-top:6px;">Administrasi</div>

    <div class="sb-item has-sub {{ request()->routeIs('administrator.*') ? 'open' : '' }}">
        <div class="sb-link {{ request()->routeIs('administrator.*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
            </svg>
            <span class="sb-label">Administrator</span>
            <svg class="sb-arrow" viewBox="0 0 24 24">
                <polyline points="6 9 12 15 18 9" />
            </svg>
        </div>
        <div class="sub-nav">
            <a href="{{ route('administrator.index') }}" class="sub-link">Manajemen User</a>
            <a href="{{ route('administrator.index') }}" class="sub-link">Role &amp; Permission</a>
            <a href="{{ route('administrator.index') }}" class="sub-link">Master Pegawai</a>
            <a href="{{ route('administrator.index') }}" class="sub-link">Master Divisi</a>
            <a href="{{ route('administrator.index') }}" class="sub-link">Master Jabatan</a>
        </div>
    </div>
    @endrole

    {{-- HELP --}}
    <div class="sb-section-title" style="margin-top:6px;">Dukungan</div>
    <div class="sb-item">
        <a href="{{ route('help.index') }}" class="sb-link">
            <svg class="sb-icon" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
            <span class="sb-label">Panduan Penggunaan</span>
        </a>
    </div>
</nav>

<div class="sb-collapse-btn">
    <button id="collapseSbBtn">
        <svg viewBox="0 0 24 24">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        <span>Tutup Panel</span>
    </button>
</div>