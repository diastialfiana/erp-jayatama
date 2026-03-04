@extends('layouts.app')
@section('title', 'Customer List – Finance')

@push('styles')
    <style>
        :root {
            --jes-primary: #1E3A8A;
            --jes-blue: #2563EB;
            --jes-accent: #4F46E5;
            --jes-bg: #F8FAFC;
        }

        .page-bg {
            background: var(--jes-bg);
        }

        /* ── Gradient Heading ── */
        .grad-text {
            background: linear-gradient(135deg, var(--jes-primary) 0%, var(--jes-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Glass Card ── */
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.7);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(30, 58, 138, 0.06), 0 1px 4px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .glass-card:hover {
            box-shadow: 0 12px 40px rgba(30, 58, 138, 0.1);
        }

        /* ── Action Buttons ── */
        .btn-primary {
            background: linear-gradient(135deg, var(--jes-blue) 0%, var(--jes-accent) 100%);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
            transition: all 0.25s ease;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.45);
            color: #fff;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: #fff;
            color: #374151;
            border: 1px solid #e2e8f0;
            padding: 9px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
            transition: all 0.25s ease;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-secondary:hover {
            background: #f1f5f9;
            transform: translateY(-1px);
            color: #374151;
        }

        /* ── Search Box ── */
        .search-box {
            position: relative;
            flex: 1;
            max-width: 340px;
        }

        .search-box svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .search-box input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 13px;
            background: #fff;
            color: #1e293b;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-box input:focus {
            border-color: var(--jes-blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        /* ── Data Table ── */
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13.5px;
        }

        .data-table thead th {
            background: #F1F5FB;
            color: #64748b;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 13px 18px;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 2;
            white-space: nowrap;
        }

        .data-table thead th.sortable {
            cursor: pointer;
            user-select: none;
        }

        .data-table thead th.sortable:hover {
            color: var(--jes-blue);
            background: #EEF2FF;
        }

        .data-table tbody tr {
            transition: background 0.18s, transform 0.18s;
            cursor: pointer;
        }

        .data-table tbody tr:nth-child(even) {
            background: #FAFBFF;
        }

        .data-table tbody tr:hover {
            background: linear-gradient(90deg, #EEF2FF 0%, #F0F4FF 100%);
            transform: scale(1.003);
            box-shadow: 0 2px 12px rgba(37, 99, 235, 0.08);
        }

        .data-table tbody td {
            padding: 13px 18px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            vertical-align: middle;
        }

        /* ── Status Badge ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-active {
            background: #ECFDF5;
            color: #065F46;
        }

        .badge-inactive {
            background: #FEF2F2;
            color: #991B1B;
        }

        .badge-currency {
            background: #EEF2FF;
            color: #4338CA;
        }

        /* ── Action Dropdown ── */
        .action-cell {
            position: relative;
            text-align: right;
        }

        .action-dropdown {
            position: absolute;
            right: 16px;
            top: calc(100% + 4px);
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.12);
            min-width: 150px;
            z-index: 50;
            overflow: hidden;
            display: none;
            animation: popIn 0.15s ease forwards;
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-4px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .action-dropdown.open {
            display: block;
        }

        .action-dropdown a,
        .action-dropdown button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            text-decoration: none;
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: background 0.15s;
        }

        .action-dropdown a:hover,
        .action-dropdown button:hover {
            background: #F8FAFC;
        }

        .action-dropdown .action-delete {
            color: #EF4444;
        }

        .action-dropdown .action-delete:hover {
            background: #FEF2F2;
        }

        .action-separator {
            height: 1px;
            background: #f1f5f9;
            margin: 4px 0;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 10px;
            border-radius: 9px;
            background: none;
            border: 1px solid #e2e8f0;
            color: #64748b;
            cursor: pointer;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .action-btn:hover {
            background: #F1F5FB;
            color: #1e293b;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        /* ── Pagination ── */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-top: 1px solid #f1f5f9;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pg-info {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
        }

        .pg-btns {
            display: flex;
            gap: 5px;
        }

        .pg-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #374151;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.18s;
            text-decoration: none;
        }

        .pg-btn:hover {
            background: #EEF2FF;
            border-color: var(--jes-blue);
            color: var(--jes-blue);
        }

        .pg-btn.active {
            background: linear-gradient(135deg, var(--jes-blue), var(--jes-accent));
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 72px 24px;
            color: #94a3b8;
        }

        .empty-state svg {
            margin: 0 auto 16px;
            display: block;
            opacity: 0.4;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 13.5px;
        }

        /* ── Stats Strip ── */
        .stat-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .stat-chip .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
    </style>
@endpush

@section('content')
    <div style="background:var(--jes-bg); min-height:calc(100vh - 62px); padding: 28px 24px;">
        <div style="max-width:1280px; margin:0 auto;">

            {{-- ── BREADCRUMB ── --}}
            <div style="display:flex; align-items:center; gap:6px; font-size:12px; color:#94a3b8; margin-bottom:20px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                <span>Finance</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
                <span style="color:#2563EB; font-weight:600;">Customer List</span>
            </div>

            {{-- ── PAGE HEADER ── --}}
            <div
                style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; justify-content:space-between; margin-bottom:24px;">
                <div>
                    <h1 style="font-size:28px; font-weight:800; margin:0 0 4px;" class="grad-text">Customer List</h1>
                    <p style="font-size:13.5px; color:#64748b; margin:0;">Manage all customer financial data and account
                        relationships</p>
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                    <div class="stat-chip">
                        <span class="dot" style="background:#3B82F6;"></span>
                        {{ $customers->count() }} Total Records
                    </div>
                    <a href="{{ route('finance.customers.detail.create') }}" class="btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        New Customer
                    </a>
                </div>
            </div>

            {{-- ── ACTION BAR ── --}}
            <div class="glass-card"
                style="padding:16px 20px; margin-bottom:16px; display:flex; flex-wrap:wrap; gap:10px; align-items:center; justify-content:space-between;">
                <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; flex:1;">
                    <div class="search-box">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        <input type="text" id="searchInput" placeholder="Search by code, name, phone..."
                            oninput="filterTable()">
                    </div>
                    <div style="position:relative;">
                        <select id="regionFilter" onchange="filterTable()"
                            style="appearance:none; padding:10px 36px 10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:13px; background:#fff; color:#374151; font-weight:500; cursor:pointer; outline:none; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
                            <option value="">All Regions</option>
                            <option value="Jawa">Jawa</option>
                            <option value="Sumatera">Sumatera</option>
                            <option value="Kalimantan">Kalimantan</option>
                            <option value="Sulawesi">Sulawesi</option>
                            <option value="Bali / Nusa Tenggara">Bali / NTT</option>
                            <option value="Maluku / Papua">Maluku / Papua</option>
                        </select>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"
                            stroke-linecap="round"
                            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);pointer-events:none;">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </div>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <button class="btn-secondary" onclick="exportCSV()" title="Export Data">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            {{-- ── DATA TABLE CARD ── --}}
            <div class="glass-card" style="overflow:hidden;">
                <div style="overflow-x:auto;">
                    <table class="data-table" id="customerTable">
                        <thead>
                            <tr>
                                <th class="sortable" onclick="sortTable(0)" style="min-width:100px;">
                                    Code <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" style="vertical-align:middle;margin-left:4px;">
                                        <path d="m7 15 5 5 5-5" />
                                        <path d="m7 9 5-5 5 5" />
                                    </svg>
                                </th>
                                <th class="sortable" onclick="sortTable(1)" style="min-width:200px;">
                                    Customer Name <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.5"
                                        style="vertical-align:middle;margin-left:4px;">
                                        <path d="m7 15 5 5 5-5" />
                                        <path d="m7 9 5-5 5 5" />
                                    </svg>
                                </th>
                                <th style="min-width:100px;">Currency</th>
                                <th style="min-width:120px;">Region</th>
                                <th style="min-width:120px;">Phone</th>
                                <th style="min-width:140px; text-align:right;">Balance (IDR)</th>
                                <th style="text-align:center; min-width:80px;">Status</th>
                                <th style="text-align:right; min-width:100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customerTbody">
                            @forelse($customers as $c)
                                <tr data-code="{{ strtolower($c->code) }}" data-name="{{ strtolower($c->counter_name) }}"
                                    data-phone="{{ strtolower($c->phone ?? '') }}" data-region="{{ $c->region ?? '' }}">
                                    <td>
                                        <span
                                            style="font-family:monospace; font-weight:700; color:#2563EB; font-size:13px;">{{ $c->code }}</span>
                                    </td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <div
                                                style="width:34px; height:34px; border-radius:10px; background:linear-gradient(135deg,#dbeafe,#ede9fe); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                <span
                                                    style="font-size:13px; font-weight:800; color:#4338CA;">{{ strtoupper(substr($c->counter_name, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <div style="font-weight:700; color:#0f172a; font-size:13.5px;">
                                                    {{ $c->counter_name }}</div>
                                                @if($c->initial_name)
                                                    <div style="font-size:11.5px; color:#94a3b8;">{{ $c->initial_name }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-currency">{{ $c->currency ?? '–' }}</span>
                                    </td>
                                    <td style="font-size:13px; color:#475569;">{{ $c->region ?? '–' }}</td>
                                    <td style="font-size:13px; color:#475569;">{{ $c->phone ?? '–' }}</td>
                                    <td
                                        style="text-align:right; font-family:monospace; font-weight:700; color:#065F46; font-size:13.5px;">
                                        {{ number_format($c->balance, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align:center;">
                                        @if($c->balance >= 0)
                                            <span class="badge badge-active">
                                                <svg width="7" height="7" viewBox="0 0 10 10">
                                                    <circle cx="5" cy="5" r="5" fill="#10B981" />
                                                </svg>
                                                Active
                                            </span>
                                        @else
                                            <span class="badge badge-inactive">
                                                <svg width="7" height="7" viewBox="0 0 10 10">
                                                    <circle cx="5" cy="5" r="5" fill="#EF4444" />
                                                </svg>
                                                Overdue
                                            </span>
                                        @endif
                                    </td>
                                    <td class="action-cell">
                                        <button class="action-btn" onclick="toggleAction(event, {{ $c->id }})">
                                            Actions
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round">
                                                <polyline points="6 9 12 15 18 9" />
                                            </svg>
                                        </button>
                                        <div class="action-dropdown" id="action-{{ $c->id }}">
                                            <a href="{{ route('finance.customers.detail.show', $c->id) }}">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                                View Detail
                                            </a>
                                            <a href="{{ route('finance.customers.detail.show', $c->id) }}">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <div class="action-separator"></div>
                                            <button class="action-delete" onclick="confirmDelete({{ $c->id }})">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                    <polyline points="3 6 5 6 21 6" />
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.2" stroke-linecap="round">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            </svg>
                                            <h3>No Customers Yet</h3>
                                            <p>Create your first customer record to get started.</p>
                                            <a href="{{ route('finance.customers.detail.create') }}" class="btn-primary"
                                                style="margin-top:18px; display:inline-flex;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                    <path d="M5 12h14" />
                                                    <path d="M12 5v14" />
                                                </svg>
                                                New Customer
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Info --}}
                @if($customers->count() > 0)
                    <div class="pagination">
                        <span class="pg-info" id="paginationInfo">Showing {{ $customers->count() }} records</span>
                        <div class="pg-btns" id="pgBtns"></div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Delete Form (hidden) --}}
    {{-- Add real delete route when needed --}}
@endsection

@push('scripts')
    <script>
        let sortDir = {};
        let currentPage = 1;
        const rowsPerPage = 15;

        // ── Filter & Search
        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const region = document.getElementById('regionFilter').value.toLowerCase();
            const rows = document.querySelectorAll('#customerTbody tr[data-code]');
            let visible = 0;
            rows.forEach(row => {
                const code = row.dataset.code || '';
                const name = row.dataset.name || '';
                const phone = row.dataset.phone || '';
                const reg = row.dataset.region.toLowerCase();
                const matchQ = !q || code.includes(q) || name.includes(q) || phone.includes(q);
                const matchR = !region || reg.includes(region);
                const show = matchQ && matchR;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            document.getElementById('paginationInfo')?.setAttribute('data-count', visible);
        }

        // ── Sort
        function sortTable(colIdx) {
            const tbody = document.getElementById('customerTbody');
            const rows = Array.from(tbody.querySelectorAll('tr[data-code]'));
            sortDir[colIdx] = !sortDir[colIdx];
            rows.sort((a, b) => {
                const aT = a.cells[colIdx]?.innerText.trim() || '';
                const bT = b.cells[colIdx]?.innerText.trim() || '';
                return sortDir[colIdx] ? aT.localeCompare(bT) : bT.localeCompare(aT);
            });
            rows.forEach(r => tbody.appendChild(r));
        }

        // ── Action Dropdown
        function toggleAction(e, id) {
            e.stopPropagation();
            document.querySelectorAll('.action-dropdown').forEach(d => {
                if (d.id !== 'action-' + id) d.classList.remove('open');
            });
            document.getElementById('action-' + id).classList.toggle('open');
        }
        document.addEventListener('click', () => document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('open')));

        // ── Delete confirm
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
                // Add real delete logic here
                alert('Delete functionality will be connected to DELETE route.');
            }
        }

        // ── Export CSV
        function exportCSV() {
            const rows = document.querySelectorAll('#customerTbody tr[data-code]');
            let csv = 'Code,Customer Name,Currency,Region,Phone,Balance\n';
            rows.forEach(r => {
                if (r.style.display === 'none') return;
                const cells = r.cells;
                csv += [cells[0].innerText.trim(), cells[1].innerText.trim(), cells[2].innerText.trim(), cells[3].innerText.trim(), cells[4].innerText.trim(), cells[5].innerText.trim()].join(',') + '\n';
            });
            const blob = new Blob([csv], { type: 'text/csv' });
            const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = 'customers.csv'; a.click();
        }
    </script>
@endpush