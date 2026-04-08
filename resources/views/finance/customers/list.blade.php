@extends('layouts.app')
@section('title', 'Customer List All – Finance')

@push('styles')
    <style>
        :root {
            --pri: #1E3A8A;
            --blue: #2563EB;
            --acc: #4F46E5;
            --jes-bg: #F8FAFC;
        }

        /* ── Gradient text ── */
        .g-text {
            background: linear-gradient(135deg, var(--pri) 0%, var(--acc) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Glass card ── */
        .g-card {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.75);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(30, 58, 138, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: box-shadow .3s;
        }

        /* ── Tab bar ── */
        .tab-bar {
            display: flex;
            padding: 6px 6px 0;
            overflow-x: auto;
            gap: 2px;
        }

        .tab-bar::-webkit-scrollbar {
            height: 3px;
        }

        .tab-bar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .tab-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 12px 12px 0 0;
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            text-decoration: none;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
            transition: all .2s;
        }

        .tab-item:hover {
            color: #475569;
            background: #F8FAFC;
            text-decoration: none;
        }

        .tab-item.active {
            color: var(--blue);
            border-bottom-color: var(--blue);
            background: #fff;
        }

        .tab-item.disabled {
            pointer-events: none;
            opacity: .4;
        }

        /* ── Action buttons ── */
        .btn-p {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--blue), var(--acc));
            color: #fff;
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(37, 99, 235, .3);
            transition: all .22s;
            white-space: nowrap;
        }

        .btn-p:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, .4);
            color: #fff;
        }

        .btn-s {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            background: #fff;
            color: #374151;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: all .22s;
        }

        .btn-s:hover {
            background: #F8FAFC;
            transform: translateY(-1px);
            color: #374151;
        }

        /* ── Search / Filter bar ── */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 220px;
            max-width: 340px;
        }

        .search-wrap svg {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .search-wrap input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 13px;
            background: #fff;
            color: #0f172a;
            outline: none;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .05);
            transition: border-color .2s, box-shadow .2s;
        }

        .search-wrap input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .13);
        }

        .sel-wrap {
            position: relative;
        }

        .sel-wrap select {
            appearance: none;
            -webkit-appearance: none;
            padding: 10px 34px 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 13px;
            background: #fff;
            color: #374151;
            font-weight: 500;
            cursor: pointer;
            outline: none;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .05);
            transition: border-color .2s;
        }

        .sel-wrap select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .13);
        }

        .sel-wrap svg {
            position: absolute;
            right: 11px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #94a3b8;
        }

        /* ── Stats chips ── */
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
            white-space: nowrap;
        }

        .chip .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        /* ── Wide Table ── */
        .tbl-wrap {
            overflow-x: auto;
        }

        .tbl-wrap::-webkit-scrollbar {
            height: 6px;
        }

        .tbl-wrap::-webkit-scrollbar-track {
            background: #F8FAFC;
        }

        .tbl-wrap::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }

        .tbl-wrap::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        table.erp-tbl {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12.5px;
            min-width: 1800px;
        }

        table.erp-tbl thead th {
            background: #F0F4FF;
            color: #475569;
            font-weight: 700;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 12px 14px;
            border-bottom: 1px solid #E0E7FF;
            position: sticky;
            top: 0;
            z-index: 5;
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
        }

        table.erp-tbl thead th:hover {
            background: #E8EDFF;
            color: var(--blue);
        }

        table.erp-tbl thead th .sort-arr {
            display: inline-block;
            margin-left: 4px;
            opacity: .5;
        }

        table.erp-tbl thead th.sorted .sort-arr {
            opacity: 1;
            color: var(--blue);
        }

        table.erp-tbl tbody tr {
            transition: background .18s, transform .18s;
        }

        table.erp-tbl tbody tr:nth-child(even) {
            background: #FAFBFF;
        }

        table.erp-tbl tbody tr:hover {
            background: linear-gradient(90deg, #EEF2FF, #F0F4FF);
            transform: scale(1.002);
            box-shadow: inset 4px 0 0 var(--blue);
        }

        table.erp-tbl tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid #F1F5F9;
            color: #1e293b;
            vertical-align: middle;
            white-space: nowrap;
        }

        table.erp-tbl tbody td.td-num {
            font-family: monospace;
            font-weight: 700;
            text-align: right;
        }

        table.erp-tbl tbody td.td-code {
            font-family: monospace;
            font-weight: 700;
            color: var(--blue);
        }

        table.erp-tbl tbody td.td-name {
            font-weight: 600;
            color: #0f172a;
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        table.erp-tbl tbody td.td-muted {
            color: #64748b;
            font-size: 12px;
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        table.erp-tbl tbody td.td-bal-pos {
            color: #065F46;
            font-family: monospace;
            font-weight: 700;
            text-align: right;
        }

        table.erp-tbl tbody td.td-bal-neg {
            color: #991B1B;
            font-family: monospace;
            font-weight: 700;
            text-align: right;
        }

        /* ── Badge ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 700;
        }

        .badge-curr {
            background: #EEF2FF;
            color: #4338CA;
        }

        .badge-region {
            background: #F0FDF4;
            color: #065F46;
        }

        .badge-audit {
            background: #FFF7ED;
            color: #92400E;
            font-size: 10px;
        }

        /* ── Row Action ── */
        .row-action {
            display: flex;
            gap: 5px;
            justify-content: flex-end;
        }

        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            cursor: pointer;
            transition: all .18s;
            text-decoration: none;
        }

        .icon-btn:hover {
            background: #EEF2FF;
            color: var(--blue);
            border-color: var(--blue);
            transform: translateY(-1px);
        }

        .icon-btn.danger:hover {
            background: #FEF2F2;
            color: #EF4444;
            border-color: #FECACA;
        }

        /* ── Pagination ── */
        .pg-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-top: 1px solid #F1F5F9;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pg-info {
            font-size: 12.5px;
            color: #94a3b8;
            font-weight: 500;
        }

        .pg-btns {
            display: flex;
            gap: 5px;
        }

        .pg-btn {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: #374151;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .18s;
            text-decoration: none;
        }

        .pg-btn:hover {
            background: #EEF2FF;
            border-color: var(--blue);
            color: var(--blue);
        }

        .pg-btn.active {
            background: linear-gradient(135deg, var(--blue), var(--acc));
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .3);
        }

        .pg-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        /* ── Frozen first 2 cols ── */
        table.erp-tbl th:nth-child(1),
        table.erp-tbl td:nth-child(1) {
            position: sticky;
            left: 0;
            z-index: 3;
            background: inherit;
            border-right: 1px solid #e2e8f0;
        }

        table.erp-tbl th:nth-child(2),
        table.erp-tbl td:nth-child(2) {
            position: sticky;
            left: 100px;
            z-index: 3;
            background: inherit;
            border-right: 1px solid #E0E7FF;
        }

        table.erp-tbl thead th:nth-child(1),
        table.erp-tbl thead th:nth-child(2) {
            z-index: 6;
        }

        /* ── Column group separator ── */
        .col-sep {
            border-left: 2px solid #E0E7FF !important;
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
            opacity: .35;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 6px;
        }

        .empty-state p {
            font-size: 13.5px;
        }
    </style>
@endpush

@section('content')
    <div style="background:var(--jes-bg); min-height:calc(100vh - 62px); padding:28px 24px;">
        <div style="max-width:1400px; margin:0 auto;">

            {{-- ── BREADCRUMB ── --}}
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:18px;">
                <a href="{{ route('finance.customers.index') }}"
                    style="color:#94a3b8;text-decoration:none;display:flex;align-items:center;gap:5px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    Finance
                </a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
                <a href="{{ route('finance.customers.index') }}" style="color:#94a3b8;text-decoration:none;">Customer List</a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
                <span style="color:var(--blue);font-weight:600;">List All</span>
            </div>

            {{-- ── PAGE HEADER ── --}}
            <div
                style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;justify-content:space-between;margin-bottom:20px;">
                <div>
                    <h1 style="font-size:28px;font-weight:800;margin:0 0 4px;" class="g-text">Customer List</h1>
                    <p style="font-size:13px;color:#64748b;margin:0;">Manage all customer financial data and account
                        relationships</p>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                    <span class="chip"><span class="dot" style="background:#3B82F6;"></span>{{ $total }} Total
                        Records</span>
                    <a href="{{ route('finance.customers.detail') }}" class="btn-p">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        New Customer
                    </a>
                    <button class="btn-s" onclick="exportCSV()" title="Export to CSV">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            {{-- ── TAB BAR ── --}}
            <div style="display:flex; gap:24px; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-bottom:20px; overflow-x:auto;">
                <a href="{{ route('finance.customers.detail') }}" style="color:#64748b; text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition:all .2s;" onmouseover="this.style.color='#2563EB';" onmouseout="this.style.color='#64748b';">
                    Detail View
                </a>
                <a href="{{ route('finance.customers.index') }}" style="color:#2563EB; font-weight:600; border-bottom:2px solid #2563EB; text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap;">
                    List All
                </a>
                <a href="{{ route('finance.customers.statistic') }}" style="color:#64748b; text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition:all .2s;" onmouseover="this.style.color='#2563EB';" onmouseout="this.style.color='#64748b';">
                    Statistic
                </a>
                <a href="{{ route('finance.customers.activity') }}" style="color:#64748b; text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition:all .2s;" onmouseover="this.style.color='#2563EB';" onmouseout="this.style.color='#64748b';">
                    Activity
                </a>
                <a href="{{ route('finance.customers.backdate') }}" style="color:#64748b; text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition:all .2s;" onmouseover="this.style.color='#2563EB';" onmouseout="this.style.color='#64748b';">
                    Backdate
                </a>
                <a href="{{ route('finance.customers.summary') }}" style="color:#64748b; text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition:all .2s;" onmouseover="this.style.color='#2563EB';" onmouseout="this.style.color='#64748b';">
                    Summary
                </a>
            </div>

            {{-- ── FILTER & SEARCH BAR ── --}}
            <div class="g-card"
                style="border-radius:0; border-top:0; border-bottom:0; padding:14px 20px; box-shadow:none; border-color:#e2e8f0;">
                <div class="filter-bar">
                    <div class="search-wrap">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        <input type="text" id="searchInput" placeholder="Search code, name, phone, region…"
                            oninput="applyFilters()">
                    </div>

                    <div class="sel-wrap">
                        <select id="regionFilter" onchange="applyFilters()">
                            <option value="">All Regions</option>
                            @foreach(['Jawa', 'Sumatera', 'Kalimantan', 'Sulawesi', 'Bali / Nusa Tenggara', 'Maluku / Papua'] as $r)
                                <option value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                        </select>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </div>

                    <div class="sel-wrap">
                        <select id="currencyFilter" onchange="applyFilters()">
                            <option value="">All Currencies</option>
                            <option value="IDR">IDR</option>
                            <option value="USD">USD</option>
                            <option value="SGD">SGD</option>
                        </select>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </div>

                    <div class="sel-wrap">
                        <select id="perPageSel" onchange="renderPage(1)">
                            <option value="20">20 / page</option>
                            <option value="50">50 / page</option>
                            <option value="100">100 / page</option>
                        </select>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </div>

                    <span id="filterResult"
                        style="font-size:12px;color:#94a3b8;font-weight:600;margin-left:auto;white-space:nowrap;"></span>
                </div>
            </div>

            {{-- ── TABLE CARD ── --}}
            <div class="g-card" style="border-radius:0 0 20px 20px; border-top:1px solid #E0E7FF; overflow:hidden;">
                <div class="tbl-wrap">
                    <table class="erp-tbl" id="mainTable">
                        <thead>
                            <tr>
                                <th onclick="sortBy(0)" data-col="0">Code <span class="sort-arr">↕</span></th>
                                <th onclick="sortBy(1)" data-col="1">Currency <span class="sort-arr">↕</span></th>
                                <th onclick="sortBy(2)" data-col="2" class="col-sep">Customer Name <span
                                        class="sort-arr">↕</span></th>
                                <th onclick="sortBy(3)" data-col="3">Address 1 <span class="sort-arr">↕</span></th>
                                <th onclick="sortBy(4)" data-col="4">Address 2 <span class="sort-arr">↕</span></th>
                                <th onclick="sortBy(5)" data-col="5">Region <span class="sort-arr">↕</span></th>
                                <th onclick="sortBy(6)" data-col="6">Cat <span class="sort-arr">↕</span></th>
                                <th onclick="sortBy(7)" data-col="7" class="col-sep">Phone <span class="sort-arr">↕</span>
                                </th>
                                <th onclick="sortBy(8)" data-col="8">Fax <span class="sort-arr">↕</span></th>
                                <th onclick="sortBy(9)" data-col="9">Mobile <span class="sort-arr">↕</span></th>
                                <th onclick="sortBy(10)" data-col="10">Initial <span class="sort-arr">↕</span></th>
                                <th onclick="sortBy(11)" data-col="11" class="col-sep" style="text-align:right;">Balance
                                    <span class="sort-arr">↕</span></th>
                                <th onclick="sortBy(12)" data-col="12" style="text-align:right;">[Balance] <span
                                        class="sort-arr">↕</span></th>
                                <th onclick="sortBy(13)" data-col="13" style="text-align:right;">DN Payment <span
                                        class="sort-arr">↕</span></th>
                                <th onclick="sortBy(14)" data-col="14" style="text-align:right;">[DN Payment] <span
                                        class="sort-arr">↕</span></th>
                                <th onclick="sortBy(15)" data-col="15" class="col-sep">Audit <span class="sort-arr">↕</span>
                                </th>
                                <th style="text-align:right; min-width:90px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tblBody">
                            @forelse($customers as $c)
                                <tr data-code="{{ strtolower($c->code) }}" data-name="{{ strtolower($c->counter_name) }}"
                                    data-phone="{{ strtolower($c->phone ?? '') }}" data-region="{{ $c->region ?? '' }}"
                                    data-currency="{{ $c->currency ?? '' }}">
                                    <td class="td-code">{{ $c->code }}</td>
                                    <td><span class="badge badge-curr">{{ $c->currency ?? '–' }}</span></td>
                                    <td class="td-name col-sep">
                                        <div style="display:flex;align-items:center;gap:9px;">
                                            <div
                                                style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#dbeafe,#ede9fe);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <span
                                                    style="font-size:12px;font-weight:800;color:#4338CA;">{{ strtoupper(substr($c->counter_name, 0, 1)) }}</span>
                                            </div>
                                            {{ $c->counter_name }}
                                        </div>
                                    </td>
                                    <td class="td-muted">{{ $c->address ? Str::limit($c->address, 30) : '–' }}</td>
                                    <td class="td-muted">–</td>
                                    <td>
                                        @if($c->region)
                                            <span class="badge badge-region">{{ $c->region }}</span>
                                        @else
                                            <span style="color:#CBD5E1;">–</span>
                                        @endif
                                    </td>
                                    <td style="color:#64748b;font-size:12px;">
                                        @if($c->is_corporate_group)
                                            <span class="badge" style="background:#FEF3C7;color:#92400E;">Group</span>
                                        @else
                                            <span style="color:#CBD5E1;">–</span>
                                        @endif
                                    </td>
                                    <td class="td-muted col-sep">{{ $c->phone ?? '–' }}</td>
                                    <td class="td-muted">{{ $c->fax ?? '–' }}</td>
                                    <td class="td-muted">{{ $c->mobile_phone ?? '–' }}</td>
                                    <td style="font-size:12px;font-weight:600;color:#475569;font-family:monospace;">
                                        {{ $c->initial_name ?? '–' }}</td>
                                    <td class="{{ $c->balance < 0 ? 'td-bal-neg' : 'td-bal-pos' }} col-sep">
                                        {{ number_format($c->balance, 0, ',', '.') }}</td>
                                    <td class="{{ $c->balance < 0 ? 'td-bal-neg' : 'td-bal-pos' }}">
                                        {{ number_format($c->balance, 0, ',', '.') }}</td>
                                    <td class="{{ $c->down_payment < 0 ? 'td-bal-neg' : 'td-bal-pos' }}">
                                        {{ number_format($c->down_payment, 0, ',', '.') }}</td>
                                    <td class="{{ $c->down_payment < 0 ? 'td-bal-neg' : 'td-bal-pos' }}">
                                        {{ number_format($c->down_payment, 0, ',', '.') }}</td>
                                    <td class="col-sep">
                                        <span class="badge badge-audit"
                                            title="Updated: {{ $c->updated_at?->format('d M Y H:i') }}">
                                            {{ $c->updated_at?->format('d/m/y') ?? '–' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="row-action">
                                            <a href="{{ route('finance.customers.detail', ['id' => $c->id]) }}" class="icon-btn"
                                                title="View / Edit">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                </svg>
                                            </a>
                                            <button class="icon-btn danger" title="Delete" onclick="confirmDel({{ $c->id }})">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                    <polyline points="3 6 5 6 21 6" />
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="17">
                                        <div class="empty-state">
                                            <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.2" stroke-linecap="round">
                                                <rect width="18" height="18" x="3" y="3" rx="2" />
                                                <path d="M7 7h10" />
                                                <path d="M7 12h10" />
                                                <path d="M7 17h10" />
                                            </svg>
                                            <h3>No customers found</h3>
                                            <p>Start by adding your first customer record.</p>
                                            <a href="{{ route('finance.customers.detail') }}" class="btn-p"
                                                style="margin-top:18px;display:inline-flex;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                    <path d="M5 12h14" />
                                                    <path d="M12 5v14" />
                                                </svg>
                                                Add Customer
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ── PAGINATION ── --}}
                @if($total > 0)
                    <div class="pg-bar">
                        <span class="pg-info" id="pgInfo">Showing all {{ $total }} customers</span>
                        <div class="pg-btns" id="pgBtns"></div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ── Data Layer ──
        const allRows = Array.from(document.querySelectorAll('#tblBody tr[data-code]'));
        let filteredRows = [...allRows];
        let sortCol = -1, sortAsc = true;
        let currentPage = 1;

        // ── Filters ──
        function applyFilters() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            const reg = document.getElementById('regionFilter').value.toLowerCase();
            const cur = document.getElementById('currencyFilter').value.toLowerCase();

            filteredRows = allRows.filter(row => {
                const code = row.dataset.code || '';
                const name = row.dataset.name || '';
                const phone = row.dataset.phone || '';
                const region = row.dataset.region.toLowerCase();
                const curr = row.dataset.currency.toLowerCase();

                const matchQ = !q || code.includes(q) || name.includes(q) || phone.includes(q) || region.includes(q);
                const matchReg = !reg || region.includes(reg);
                const matchCur = !cur || curr === cur;
                return matchQ && matchReg && matchCur;
            });

            renderPage(1);
        }

        // ── Sort ──
        function sortBy(col) {
            if (sortCol === col) { sortAsc = !sortAsc; }
            else { sortCol = col; sortAsc = true; }

            // update header arrows
            document.querySelectorAll('#mainTable thead th').forEach((th, i) => {
                th.classList.remove('sorted');
                th.querySelector('.sort-arr') && (th.querySelector('.sort-arr').textContent = '↕');
            });
            const ths = document.querySelectorAll('#mainTable thead th');
            if (ths[col]) {
                ths[col].classList.add('sorted');
                const arr = ths[col].querySelector('.sort-arr');
                if (arr) arr.textContent = sortAsc ? ' ↑' : ' ↓';
            }

            filteredRows.sort((a, b) => {
                const aT = (a.cells[col]?.innerText || '').trim();
                const bT = (b.cells[col]?.innerText || '').trim();
                // numeric?
                const aNum = parseFloat(aT.replace(/[^0-9.-]/g, ''));
                const bNum = parseFloat(bT.replace(/[^0-9.-]/g, ''));
                if (!isNaN(aNum) && !isNaN(bNum)) return sortAsc ? aNum - bNum : bNum - aNum;
                return sortAsc ? aT.localeCompare(bT) : bT.localeCompare(aT);
            });

            renderPage(currentPage);
        }

        // ── Paginate ──
        function renderPage(page) {
            currentPage = page;
            const perPage = parseInt(document.getElementById('perPageSel').value);
            const total = filteredRows.length;
            const pages = Math.max(1, Math.ceil(total / perPage));
            if (currentPage > pages) currentPage = pages;

            const start = (currentPage - 1) * perPage;
            const end = Math.min(start + perPage, total);

            allRows.forEach(r => r.style.display = 'none');
            filteredRows.slice(start, end).forEach(r => r.style.display = '');

            // info
            const info = document.getElementById('pgInfo');
            if (info) info.textContent = total === 0 ? 'No records found'
                : `Showing ${start + 1}–${end} of ${total} customers`;

            // update filter result chip
            const chip = document.getElementById('filterResult');
            if (chip) chip.textContent = total !== allRows.length ? `${total} of ${allRows.length} shown` : '';

            // render page buttons
            const pgBtns = document.getElementById('pgBtns');
            if (!pgBtns) return;
            pgBtns.innerHTML = '';

            const addBtn = (lbl, pg, disabled = false) => {
                const b = document.createElement('button');
                b.className = 'pg-btn' + (pg === currentPage ? ' active' : '');
                b.textContent = lbl;
                if (disabled) { b.disabled = true; b.classList.add('disabled'); }
                else b.onclick = () => renderPage(pg);
                pgBtns.appendChild(b);
            };

            addBtn('‹', currentPage - 1, currentPage === 1);
            // show up to 7 page numbers
            let startP = Math.max(1, currentPage - 3);
            let endP = Math.min(pages, startP + 6);
            startP = Math.max(1, endP - 6);
            for (let i = startP; i <= endP; i++) addBtn(i, i);
            addBtn('›', currentPage + 1, currentPage === pages || pages === 0);
        }

        // ── Export CSV ──
        function exportCSV() {
            const headers = ['Code', 'Currency', 'Customer Name', 'Address', 'Region', 'Cat', 'Phone', 'Fax', 'Mobile', 'Initial', 'Balance', 'Balance Conv', 'DN Payment', 'DN Payment Conv', 'Audit'];
            const visibleRows = filteredRows;
            let csv = headers.join(',') + '\n';
            visibleRows.forEach(r => {
                const cells = r.cells;
                const row = [
                    cells[0]?.innerText.trim(),
                    cells[1]?.innerText.trim(),
                    cells[2]?.innerText.trim(),
                    cells[3]?.innerText.trim(),
                    cells[4]?.innerText.trim(),
                    cells[5]?.innerText.trim(),
                    cells[6]?.innerText.trim(),
                    cells[7]?.innerText.trim(),
                    cells[8]?.innerText.trim(),
                    cells[9]?.innerText.trim(),
                    cells[10]?.innerText.trim(),
                    cells[11]?.innerText.trim(),
                    cells[12]?.innerText.trim(),
                    cells[13]?.innerText.trim(),
                    cells[14]?.innerText.trim(),
                    cells[15]?.innerText.trim(),
                ].map(v => `"${(v || '').replace(/"/g, '""')}"`);
                csv += row.join(',') + '\n';
            });
            const blob = new Blob([csv], { type: 'text/csv' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'customer-list-all.csv';
            a.click();
        }

        // ── Delete ──
        function confirmDel(id) {
            if (confirm('Delete this customer? This action cannot be undone.')) {
                alert('Delete route is not yet implemented. Add proper DELETE route to enable this.');
            }
        }

        // ── Init ──
        document.addEventListener('DOMContentLoaded', () => renderPage(1));
    </script>
@endpush