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

<style>
    .sidebar {
        height: calc(100vh - 62px); /* 62px is exactly standard var(--nb-h) brand height */
        overflow-y: auto !important;
        overflow-x: hidden !important;
        padding-bottom: 50px;
        scrollbar-width: thin;
    }
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    /* Override parent sub-nav constraint preventing tall menus from rendering */
    .sb-item.open > .sub-nav {
        max-height: 2000px !important;
        overflow: visible !important;
    }
    
    /* Requested UI Styles for Finance Groups */
    .finance-group-link {
        font-weight: bold;
        background: #334155 !important;
        padding: 6px 12px !important;
        border-radius: 4px;
        margin: 0 8px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .finance-group-link span {
        color: #f8fafc !important;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .finance-sub-link {
        display: block;
        padding: 6px 10px 6px 24px !important;
        font-size: 13px;
        color: #cbd5e1;
    }
    .finance-group-nav {
        display: none;
        margin-top: 4px;
        margin-bottom: 8px;
    }
    .finance-group-nav.open {
        display: block;
    }
</style>

<div class="sidebar sb-nav">
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

    <div class="sb-item has-sub {{ request()->routeIs('finance.*', 'customers.*', 'bank-account.*', 'suppliers.*') ? 'open' : '' }}">
        <div class="sb-link {{ request()->routeIs('finance.*', 'customers.*', 'bank-account.*', 'suppliers.*') ? 'active' : '' }}">
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
            <a href="{{ route('finance.index') }}" class="sub-link" style="margin-bottom:10px;">Overview Finance</a>

            <!-- GROUP: MAINTENANCE -->
            <div class="sb-item has-sub">
                <div class="sb-link finance-group-link">
                    <span>Maintenance</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#f8fafc" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>

                <div class="sub-nav finance-group-nav {{ request()->routeIs('finance.customers.*', 'finance.bank-accounts.*', 'finance.suppliers.*') ? 'open' : '' }}">
                    <a href="{{ route('finance.customers.index') }}" class="sub-link finance-sub-link {{ request()->routeIs('finance.customers.*') ? 'text-white' : '' }}">
                        Customer List
                    </a>
                    <a href="{{ route('finance.bank-accounts.index') }}" class="sub-link finance-sub-link {{ request()->routeIs('finance.bank-accounts.*') ? 'text-white' : '' }}">
                        Bank Account
                    </a>
                    <a href="{{ route('finance.suppliers.index') }}" class="sub-link finance-sub-link {{ request()->routeIs('finance.suppliers.*') ? 'text-white' : '' }}">
                        Supplier List
                    </a>
                    <a href="#" class="sub-link finance-sub-link">
                        Branch Location
                    </a>
                </div>
            </div>

            <!-- GROUP: CASH & BANK -->
            <div class="sb-item has-sub">
                <div class="sb-link finance-group-link">
                    <span>Cash and Bank</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#f8fafc" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>

                <div class="sub-nav finance-group-nav {{ request()->routeIs('finance.cash-in.*', 'finance.cash-out.*', 'finance.cash-transfer.*', 'finance.advance-report.*', 'finance.ebanking-request.*') ? 'open' : '' }}">
                    <a href="{{ route('finance.cash-in.index') }}" class="sub-link finance-sub-link {{ request()->routeIs('finance.cash-in.*') ? 'text-white' : '' }}">
                        Cash In
                    </a>
                    <a href="{{ route('finance.cash-out.index') }}" class="sub-link finance-sub-link {{ request()->routeIs('finance.cash-out.*') ? 'text-white' : '' }}">
                        Cash Out
                    </a>
                    <a href="{{ route('finance.cash-transfer.index') }}" class="sub-link finance-sub-link {{ request()->routeIs('finance.cash-transfer.*') ? 'text-white' : '' }}">
                        Cash Transfer
                    </a>
                    <a href="{{ route('finance.advance-report.index') }}" class="sub-link finance-sub-link {{ request()->routeIs('finance.advance-report.*') ? 'text-white' : '' }}">
                        Advance Report
                    </a>
                    <a href="{{ route('finance.ebanking-request.index') }}" class="sub-link finance-sub-link {{ request()->routeIs('finance.ebanking-request.*') ? 'text-white' : '' }}">
                        e-Banking Request
                    </a>
                </div>
            </div>

            <!-- GROUP: SALES -->
            <div class="sb-item has-sub">
                <div class="sb-link finance-group-link" style="gap: 6px;">
                    <span style="display:flex; align-items:center; gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        Sales
                    </span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#f8fafc" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>

                <div class="sub-nav finance-group-nav {{ request()->routeIs('finance.sales-invoice.*', 'finance.cash-receipt.*') ? 'open' : '' }}">
                    <a href="{{ route('finance.sales-invoice.index') }}" class="sub-link finance-sub-link {{ request()->routeIs('finance.sales-invoice.*') ? 'text-white' : '' }}" style="display:flex; align-items:center; gap:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                        Sales Invoice
                    </a>
                    <a href="{{ route('finance.cash-receipt.index') }}" class="sub-link finance-sub-link {{ request()->routeIs('finance.cash-receipt.*') ? 'text-white' : '' }}" style="display:flex; align-items:center; gap:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 17V7"/></svg>
                        Cash Receipt
                    </a>
                </div>
            </div>

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
</div>

<div class="sb-collapse-btn">
    <button id="collapseSbBtn">
        <svg viewBox="0 0 24 24">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        <span>Tutup Panel</span>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle specific finance sub-groups without breaking the outer sidebar structure
        document.querySelectorAll('.finance-group-link').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                item.nextElementSibling.classList.toggle('open');
            });
        });

        // Menjaga UX scroll agar selalu tertuju ke menu aktif
        setTimeout(() => {
            document.querySelector('.active')?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }, 150);
    });
</script>