<style>
.sidebar {
    background: #0f172a;
    width: var(--sb-w, 258px);
    height: 100vh;
    list-style: none;
    padding: 0;
    margin: 0;
    overflow-y: auto;
    overflow-x: hidden;
}

#sidebar.collapsed .sidebar {
    width: var(--sb-cw, 70px);
}

.sidebar::-webkit-scrollbar {
    width: 6px;
}
.sidebar::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 3px;
}

/* Brand Section inside sidebar to keep it self-contained */
.sb-brand {
    height: var(--nb-h, 62px);
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 0 18px;
    border-bottom: 1px solid #1e293b;
    text-decoration: none;
    flex-shrink: 0;
    overflow: hidden;
}
.sb-logo {
    width: 36px; height: 36px; flex-shrink: 0;
    background: linear-gradient(135deg, #1d4ed8, #4F46E5);
    border-radius: 10px; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.45);
}
.sb-logo svg {
    width: 18px; height: 18px; fill: none; stroke: white; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
}
.sb-brand-text {
    overflow: hidden; min-width: 0; display: flex; flex-direction: column; justify-content: center;
}
.sb-brand-name { font-size: 0.98rem; font-weight: 800; color: #fff; letter-spacing: -0.03em; white-space: nowrap; }
.sb-brand-sub { font-size: 0.58rem; color: rgba(255, 255, 255, 0.3); letter-spacing: 0.1em; text-transform: uppercase; white-space: nowrap; }

#sidebar.collapsed .sb-brand-text { opacity: 0; width: 0; }

/* Global Component Navigation Structure */
.menu {
    list-style: none;
}

.menu-toggle {
    background: #1e293b;
    color: #fff;
    width: 100%;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
    border-bottom: 1px solid rgba(0,0,0,0.1);
    cursor: pointer;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: background 0.2s ease;
}

/* Base icon sizing */
.menu-icon {
    flex-shrink: 0;
}

.menu-title {
    flex: 1;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: opacity 0.2s;
}

.menu-arrow {
    margin-left: auto;
    transition: transform 0.2s ease;
}

.menu-toggle:hover {
    background: #334155;
}

.menu.active > .menu-toggle {
    background: #334155;
}

.menu.active .menu-arrow {
    transform: rotate(180deg);
}

.submenu {
    background: #020617;
    display: none;
    list-style: none;
    padding: 0;
    margin: 0;
    transition: all 0.2s ease;
}

.menu.active .submenu {
    display: block;
}

.submenu-title {
    background: #334155;
    color: #94a3b8;
    padding: 6px 12px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.submenu-item {
    padding: 8px 16px;
    font-size: 13px;
    color: #d1d5db;
    display: block;
    text-decoration: none;
    transition: all 0.15s ease;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.submenu-item:hover {
    background: #1e3a5f;
    color: #fff;
}

.submenu-item.active {
    background: #2563eb;
    color: white;
    font-weight: 600;
}

/* Collapsed Sidebar Logic */
#sidebar.collapsed .menu-title,
#sidebar.collapsed .menu-arrow {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

#sidebar.collapsed .menu-toggle {
    justify-content: center;
    padding: 14px 12px;
}

#sidebar.collapsed .submenu-title {
    font-size: 0;
    padding: 4px;
    text-align: center;
    height: 4px;
    background: transparent;
}

#sidebar.collapsed .submenu-item {
    padding: 10px;
    font-size: 0;
    display: flex;
    justify-content: center;
}
#sidebar.collapsed .submenu-item::before {
    content: '';
    width: 6px; height: 6px;
    background: #94a3b8;
    border-radius: 50%;
}
#sidebar.collapsed .submenu-item.active::before {
    background: #fff;
}
#sidebar.collapsed .menu.active .submenu {
    /* If we want to show dots in collapsed mode, display block. Otherwise, hide standard submenu in collapsed mode to avoid clutter */
    display: none; 
}
#sidebar.collapsed .menu:hover .submenu {
    /* Optional: Show floating menu on hover - skipped to keep simple, just hiding submenu in collapsed for now */
    display: none;
}

.sb-collapse-btn {
    margin-top: auto;
    border-top: 1px solid #1e293b;
    padding: 12px;
}
.sb-collapse-btn button {
    display: flex; align-items: center; gap: 9px;
    color: #94a3b8; background: none; border: none; cursor: pointer;
    font-size: 0.75rem; font-weight: 500; width: 100%; transition: color 0.2s;
}
.sb-collapse-btn button:hover { color: #fff; }
.sb-collapse-btn button svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; transition: transform 0.3s; }
</style>

<ul class="sidebar">
    <!-- BRAND -->
    <a href="{{ route('dashboard') }}" class="sb-brand">
        <div class="sb-logo">
            <svg viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2" /><polyline points="2 17 12 22 22 17" /><polyline points="2 12 12 17 22 12" /></svg>
        </div>
        <div class="sb-brand-text">
            <div class="sb-brand-name">JES</div>
            <div class="sb-brand-sub">Jayatama Enterprise</div>
        </div>
    </a>

    <!-- DASHBOARD -->
    <li class="menu {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}" class="menu-toggle" style="background: transparent;">
            <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            <span class="menu-title">Dashboard</span>
        </a>
    </li>

    <!-- INVENTORY & GA -->
    <li class="menu {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
        <button class="menu-toggle">
            <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            <span class="menu-title">Inventory & GA</span>
            <svg class="menu-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <ul class="submenu">
            <a href="{{ route('inventory.index') }}" class="submenu-item {{ request()->routeIs('inventory.index') ? 'active' : '' }}">Overview Inventory</a>
        </ul>
    </li>

    <!-- FINANCE -->
    <li class="menu {{ request()->routeIs('finance.*') ? 'active' : '' }}">
        <button class="menu-toggle">
            <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            <span class="menu-title">Finance</span>
            <svg class="menu-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <ul class="submenu">
            <a href="{{ route('finance.index') }}" class="submenu-item {{ request()->routeIs('finance.index') ? 'active' : '' }}">Overview Finance</a>
            
            <li class="submenu-title">MAINTENANCE</li>
            <a href="{{ route('finance.customers.index') }}" class="submenu-item {{ request()->routeIs('finance.customers.*') ? 'active' : '' }}">Customer List</a>
            <a href="{{ route('finance.bank-accounts.index') }}" class="submenu-item {{ request()->routeIs('finance.bank-accounts.*') ? 'active' : '' }}">Bank Account</a>
            <a href="{{ route('finance.suppliers.index') }}" class="submenu-item {{ request()->routeIs('finance.suppliers.*') ? 'active' : '' }}">Supplier List</a>
            <a href="#" class="submenu-item">Branch Location</a>

            <li class="submenu-title">CASH AND BANK</li>
            <a href="{{ route('finance.cash-in.index') }}" class="submenu-item {{ request()->routeIs('finance.cash-in.*') ? 'active' : '' }}">Cash In</a>
            <a href="{{ route('finance.cash-out.index') }}" class="submenu-item {{ request()->routeIs('finance.cash-out.*') ? 'active' : '' }}">Cash Out</a>
            <a href="{{ route('finance.cash-transfer.index') }}" class="submenu-item {{ request()->routeIs('finance.cash-transfer.*') ? 'active' : '' }}">Cash Transfer</a>
            <a href="{{ route('finance.advance-report.index') }}" class="submenu-item {{ request()->routeIs('finance.advance-report.*') ? 'active' : '' }}">Advance Report</a>
            <a href="{{ route('finance.ebanking-request.index') }}" class="submenu-item {{ request()->routeIs('finance.ebanking-request.*') ? 'active' : '' }}">e-Banking Request</a>

            <li class="submenu-title">SALES</li>
            <a href="{{ route('finance.sales-invoice.index') }}" class="submenu-item {{ request()->routeIs('finance.sales-invoice.*') ? 'active' : '' }}">Sales Invoice</a>
            <a href="{{ route('finance.cash-receipt.index') }}" class="submenu-item {{ request()->routeIs('finance.cash-receipt.*') ? 'active' : '' }}">Cash Receipt</a>
        </ul>
    </li>

    <!-- ACCOUNTING -->
    <li class="menu {{ request()->routeIs('accounting.*') ? 'active' : '' }}">
        <button class="menu-toggle">
            <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            <span class="menu-title">Accounting</span>
            <svg class="menu-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <ul class="submenu">
            <a href="{{ route('accounting.index') }}" class="submenu-item {{ request()->routeIs('accounting.index') ? 'active' : '' }}">Overview Accounting</a>

            <li class="submenu-title">TASK</li>
            <a href="{{ route('accounting.journal-posting') }}" class="submenu-item {{ request()->routeIs('accounting.journal-posting') ? 'active' : '' }}">Journal Posting</a>
            <a href="{{ route('accounting.fx-ass-posting') }}" class="submenu-item {{ request()->routeIs('accounting.fx-ass-posting') ? 'active' : '' }}">Fx. Ass. Posting</a>
            <a href="{{ route('accounting.closing-month') }}" class="submenu-item {{ request()->routeIs('accounting.closing-month') ? 'active' : '' }}">Closing Month</a>
            <a href="{{ route('accounting.closing-year') }}" class="submenu-item {{ request()->routeIs('accounting.closing-year') ? 'active' : '' }}">Closing Year</a>
            <a href="{{ route('accounting.change-period') }}" class="submenu-item {{ request()->routeIs('accounting.change-period') ? 'active' : '' }}">Change Period</a>

            <li class="submenu-title">MAINTENANCE</li>
            <a href="{{ route('accounting.coa') }}" class="submenu-item {{ request()->routeIs('accounting.coa') ? 'active' : '' }}">Account List</a>
            <a href="{{ route('accounting.account-budget') }}" class="submenu-item {{ request()->routeIs('accounting.account-budget') ? 'active' : '' }}">Account Budget</a>
            <a href="{{ route('accounting.dept-account.detail') }}" class="submenu-item {{ request()->routeIs('accounting.dept-account.*') ? 'active' : '' }}">Dept. Account</a>
            <a href="{{ route('accounting.cost-center.detail') }}" class="submenu-item {{ request()->routeIs('accounting.cost-center.*') ? 'active' : '' }}">Cost Center</a>

            <li class="submenu-title">RECEIVABLE & PAYABLE</li>
            <a href="{{ route('accounting.ar-invoice') }}" class="submenu-item {{ request()->routeIs('accounting.ar-invoice') ? 'active' : '' }}">A/R Invoice</a>
            <a href="{{ route('accounting.ar-return') }}" class="submenu-item {{ request()->routeIs('accounting.ar-return') ? 'active' : '' }}">A/R Return</a>
            <a href="{{ route('accounting.ap-invoice') }}" class="submenu-item {{ request()->routeIs('accounting.ap-invoice') ? 'active' : '' }}">A/P Invoice</a>
            <a href="{{ route('accounting.ap-return') }}" class="submenu-item {{ request()->routeIs('accounting.ap-return') ? 'active' : '' }}">A/P Return</a>

            <li class="submenu-title">JOURNAL</li>
            <a href="{{ route('accounting.journal') }}" class="submenu-item {{ request()->routeIs('accounting.journal') ? 'active' : '' }}">Journal Entry</a>
            <a href="{{ route('accounting.post-journal') }}" class="submenu-item {{ request()->routeIs('accounting.post-journal') ? 'active' : '' }}">Post Journal</a>
            <a href="{{ route('accounting.unpost-journal') }}" class="submenu-item {{ request()->routeIs('accounting.unpost-journal') ? 'active' : '' }}">Unpost Journal</a>
            <a href="{{ route('accounting.journal-check') }}" class="submenu-item {{ request()->routeIs('accounting.journal-check') ? 'active' : '' }}">Journal Check</a>

            <li class="submenu-title">REPORT</li>
            <a href="{{ route('accounting.financial-report') }}" class="submenu-item {{ request()->routeIs('accounting.financial-report') ? 'active' : '' }}">Financial Report</a>
            <a href="{{ route('accounting.ledger') }}" class="submenu-item {{ request()->routeIs('accounting.ledger') ? 'active' : '' }}">General Ledger</a>
            <a href="{{ route('accounting.trial-balance') }}" class="submenu-item {{ request()->routeIs('accounting.trial-balance') ? 'active' : '' }}">Trial Balance</a>
        </ul>
    </li>

    <!-- ADMINISTRATOR -->
    @role('Superadmin|Admin 1|Admin 2')
    <li class="menu {{ request()->routeIs('administrator.*') ? 'active' : '' }}">
        <button class="menu-toggle">
            <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span class="menu-title">Administrator</span>
            <svg class="menu-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <ul class="submenu">
            <a href="{{ route('administrator.index') }}" class="submenu-item {{ request()->routeIs('administrator.index') ? 'active' : '' }}">Manajemen User</a>
            <a href="{{ route('administrator.index') }}" class="submenu-item">Role &amp; Permission</a>
            <a href="{{ route('administrator.index') }}" class="submenu-item">Master Pegawai</a>
            <a href="{{ route('administrator.index') }}" class="submenu-item">Master Divisi</a>
            <a href="{{ route('administrator.index') }}" class="submenu-item">Master Jabatan</a>
        </ul>
    </li>
    @endrole

    <!-- HELP -->
    <li class="menu {{ request()->routeIs('help.index') ? 'active' : '' }}">
        <a href="{{ route('help.index') }}" class="menu-toggle" style="background: transparent;">
            <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
            <span class="menu-title">Panduan Penggunaan</span>
        </a>
    </li>

    <!-- TOGGLE BTN -->
    <div class="sb-collapse-btn">
        <button id="collapseSbBtn">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6" /></svg>
            <span class="menu-title">Tutup Panel</span>
        </button>
    </div>
</ul>
