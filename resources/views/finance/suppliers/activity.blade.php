@extends('layouts.app')
@section('title', 'Supplier Activity - Finance')

@push('styles')
    <style>
        :root {
            --pri: #1E3A8A;
            --blue: #2563EB;
            --acc: #4F46E5;
            --bg: #F8FAFC;
        }

        .grad-text {
            background: linear-gradient(135deg, var(--pri) 0%, var(--acc) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Glass Card ── */
        .g-card {
            background: rgba(255, 255, 255, 0.93);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(30, 58, 138, 0.05), 0 1px 3px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
        }

        /* ── Header Information Block ── */
        .stat-header-block {
            display: flex;
            align-items: center;
            gap: 24px;
            background: #f1f5f9;
            padding: 14px 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
            flex-wrap: wrap;
        }
        .stat-h-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .stat-h-label {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .stat-h-val {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
        }
        .stat-h-val.val-positive {
            color: #dc2626;
        }
        .stat-h-divider {
            width: 2px;
            height: 30px;
            background: #cbd5e1;
        }

        /* ── Filter Bar ── */
        .filter-form {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .f-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
        }
        .f-date-input {
            padding: 6px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 13px;
            color: #1e293b;
            font-weight: 500;
            outline: none;
            transition: border-color 0.2s;
            font-family: inherit;
        }
        .f-date-input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .f-btn {
            background: var(--blue);
            color: white;
            border: none;
            padding: 7px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .f-btn:hover {
            background: #1d4ed8;
        }

        /* ── Table Container (Classic ERP Grid) ── */
        .stat-table-wrapper {
            padding: 24px;
            overflow-x: auto;
        }

        table.erp-activity-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            background: #fff;
            border: 1px solid #cbd5e1;
        }
        .erp-activity-table thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .erp-activity-table th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        .erp-activity-table td {
            padding: 8px 14px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
            color: #475569;
        }
        .erp-activity-table tbody tr {
            transition: background 0.15s;
        }
        .erp-activity-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .act-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .act-create { background: #dcfce7; color: #166534; }
        .act-update { background: #dbeafe; color: #1e40af; }
        .act-delete { background: #fee2e2; color: #991b1b; }
        .act-default { background: #f1f5f9; color: #475569; }

        /* Pagination overrides */
        .pagination-container {
            margin-top: 16px;
            display: flex;
            justify-content: flex-end;
        }
        .pagination-container nav p {
            font-size: 12px;
            color: #64748b;
        }
    </style>
@endpush

@section('content')
    <div style="background:var(--bg); min-height:calc(100vh - 62px); padding:28px 24px;">
        <div style="max-width:1440px; margin:0 auto;">

            {{-- ── BREADCRUMB ── --}}
            <div style="display:flex; align-items:center; gap:6px; font-size:12px; color:#94a3b8; margin-bottom:18px;">
                <a href="{{ route('finance.suppliers.index') }}" style="color:#94a3b8; text-decoration:none; display:flex;align-items:center;gap:5px;">
                    Finance
                </a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('finance.suppliers.records-list') }}" style="color:#94a3b8; text-decoration:none;">Supplier List</a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('finance.suppliers.show', $supplier->id) }}" style="color:#94a3b8; text-decoration:none;">{{ $supplier->name }}</a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
                <span style="color:var(--blue); font-weight:600;">Activity Logs</span>
            </div>

            {{-- ── PAGE HEADER ── --}}
            <div style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; justify-content:space-between; margin-bottom:20px;">
                <div>
                    <h1 style="font-size:28px; font-weight:800; margin:0 0 4px;" class="grad-text">Supplier Activity</h1>
                    <p style="font-size:13px; color:#64748b; margin:0;">Detailed tracking & monitoring log system</p>
                </div>
            </div>

            {{-- ── TAB NAV ── --}}
            @php $sid = isset($supplier) && $supplier->exists ? $supplier->id : null; @endphp
            <div style="display:flex; gap:24px; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-bottom:24px; overflow-x:auto;">
                <a href="{{ route('finance.suppliers.show', $sid) }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.show', 'suppliers.detail') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Record Detail</a>
                <a href="{{ route('finance.suppliers.records-list') }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.records-list', 'suppliers.list') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Records List</a>
                <a href="{{ route('finance.suppliers.statistic', $sid) }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.statistic') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Statistics</a>
                <a href="{{ route('finance.suppliers.activity', $sid) }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.activity') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Activity</a>
                <a href="{{ route('finance.suppliers.backdate', $sid) }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.backdate') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Backdate</a>
                <a href="{{ route('finance.suppliers.summary', $sid) }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.summary') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Summary</a>
            </div>

            {{-- ── CENTRAL CONTENT ── --}}
            <div class="g-card stat-table-wrapper">
                
                {{-- ── ERP HEADER BLOCK + FILTER ── --}}
                <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; margin-bottom: 20px; gap:16px;">
                    
                    <div class="stat-header-block" style="margin:0;">
                        <div class="stat-h-item">
                            <span class="stat-h-label">Code</span>
                            <span class="stat-h-val">{{ $supplier->code }}</span>
                        </div>
                        <div class="stat-h-divider"></div>
                        <div class="stat-h-item">
                            <span class="stat-h-label">Currency</span>
                            <span class="stat-h-val">{{ $supplier->currency ?? 'IDR' }}</span>
                        </div>
                        <div class="stat-h-divider"></div>
                        <div class="stat-h-item">
                            <span class="stat-h-label">Supplier Name</span>
                            <span class="stat-h-val">{{ $supplier->name }}</span>
                        </div>
                        <div class="stat-h-divider"></div>
                        <div class="stat-h-item">
                            <span class="stat-h-label">Current Balance</span>
                            <span class="stat-h-val {{ $supplier->balance > 0 ? 'val-positive' : '' }}">
                                {{ number_format($supplier->balance, 2, '.', ',') }}
                            </span>
                        </div>
                    </div>

                    {{-- ── DATE RANGE FILTER ── --}}
                    <form action="{{ route('finance.suppliers.activity', $supplier->id) }}" method="GET" class="filter-form">
                        <span class="f-label">Start Date</span>
                        <input type="date" name="start_date" class="f-date-input" value="{{ $startDate }}">
                        <span style="color:#cbd5e1; font-weight:600;">—</span>
                        <span class="f-label">End Date</span>
                        <input type="date" name="end_date" class="f-date-input" value="{{ $endDate }}">
                        
                        <button type="submit" class="f-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            Search
                        </button>
                    </form>
                </div>

                {{-- ── ACTIVITY LOG GRID ── --}}
                <div style="max-height: 500px; overflow-y: auto;">
                    <table class="erp-activity-table">
                        <thead>
                            <tr>
                                <th style="width: 20%; text-align:left;">CODE</th>
                                <th style="width: 10%; text-align:center;">CURR</th>
                                <th style="width: 45%; text-align:left;">SUPPLIER NAME</th>
                                <th style="width: 25%; text-align:right;">BALANCE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $s)
                                <tr style="cursor:pointer;" onclick="if(!event.target.closest('.expand-btn')) window.location='{{ route('finance.suppliers.show', $s->id) }}'">
                                    <td style="font-weight:600; color:#0f172a;">
                                        <button type="button" class="expand-btn" data-id="{{ $s->id }}" style="background:none;border:none;cursor:pointer;font-weight:bold;margin-right:8px;color:#64748b;font-size:14px;padding:0 4px;">+</button>
                                        {{ $s->code }}
                                    </td>
                                    <td style="text-align:center; font-weight:600; color:#475569;">
                                        {{ $s->currency ?? 'IDR' }}
                                    </td>
                                    <td style="font-weight:600; color:#0f172a;">
                                        {{ $s->name }}
                                    </td>
                                    <td style="text-align:right; font-weight:700; font-variant-numeric:tabular-nums; color:{{ $s->balance > 0 ? '#ef4444' : '#0f172a' }};">
                                        {{ number_format($s->balance, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr class="detail-row" id="detail-{{ $s->id }}" style="display:none; background:#f8fafc;">
                                    <td colspan="4" style="padding:16px 24px; border-bottom:1px solid #e2e8f0;">
                                        <div style="display:flex; gap:40px; font-size:12.5px; color:#475569;">
                                            <div><strong style="color:#0f172a; text-transform:uppercase; font-size:10px; letter-spacing:0.05em; display:block; margin-bottom:4px;">Address</strong>{{ $s->address ?? '-' }}</div>
                                            <div><strong style="color:#0f172a; text-transform:uppercase; font-size:10px; letter-spacing:0.05em; display:block; margin-bottom:4px;">Phone</strong>{{ $s->phone ?? '-' }}</div>
                                            <div><strong style="color:#0f172a; text-transform:uppercase; font-size:10px; letter-spacing:0.05em; display:block; margin-bottom:4px;">Credit Limit</strong>{{ number_format($s->credit_limit, 2, '.', ',') }}</div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align:center; padding: 24px; color:#94a3b8;">
                                        No suppliers found for the selected criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.expand-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                let id = e.target.getAttribute('data-id');
                let detailRow = document.getElementById('detail-' + id);
                if (detailRow.style.display === 'none') {
                    detailRow.style.display = 'table-row';
                    e.target.innerHTML = '▼';
                } else {
                    detailRow.style.display = 'none';
                    e.target.innerHTML = '+';
                }
            });
        });
    </script>
    @endpush
@endsection
