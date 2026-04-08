@extends('layouts.app')
@section('title', 'Supplier Statistics – Finance')

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

        /* ── Action Buttons ── */
        .f-select {
            padding: 8px 12px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            color: #1e293b;
            font-family: inherit;
            outline: none;
            font-weight: 600;
            transition: border-color 0.2s, box-shadow 0.2s;
            min-width: 120px;
        }
        .f-select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* ── Header Information Block ── */
        .stat-header-block {
            display: flex;
            align-items: center;
            gap: 32px;
            background: #f1f5f9;
            padding: 16px 24px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
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
        .stat-h-divider {
            width: 2px;
            height: 30px;
            background: #cbd5e1;
        }

        /* ── Table Container (Centered & Compact) ── */
        .stat-table-wrapper {
            max-width: 750px;
            margin: 0 auto;
            padding: 24px;
        }

        table.erp-stat-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            background: #fff;
            border: 1px solid #e2e8f0;
        }
        .erp-stat-table th {
            background: #f8fafc;
            color: #334155;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        .erp-stat-table td {
            padding: 6px 14px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .erp-stat-table td.label-col {
            font-weight: 600;
            color: #475569;
            background: #f8fafc;
            width: 25%;
            font-size: 12px;
        }
        .erp-stat-table td.strong-label {
            font-weight: 800;
            color: #0f172a;
            background: #f1f5f9;
            text-transform: uppercase;
            font-size: 11.5px;
            letter-spacing: 0.02em;
        }

        /* Input Number Style (Readonly) */
        .erp-num-input {
            width: 100%;
            text-align: right;
            border: 1px solid #cbd5e1;
            background: #fff;
            padding: 7px 12px;
            border-radius: 4px;
            font-size: 12.5px;
            font-weight: 600;
            color: #1e293b;
            outline: none;
            cursor: default;
            transition: all 0.2s;
            font-family: 'Inter', 'Poppins', monospace;
        }
        .erp-num-input:hover {
            border-color: #94a3b8;
        }
        .erp-num-input.highlight {
            border-color: #93c5fd;
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 800;
        }
        .erp-num-input.highlight-dp {
            border-color: #fca5a5;
            background: #fef2f2;
            color: #b91c1c;
            font-weight: 800;
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
                <a href="{{ route('finance.suppliers.index') }}" style="color:#94a3b8; text-decoration:none;">Supplier List</a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('finance.suppliers.show', $supplier->id) }}" style="color:#94a3b8; text-decoration:none;">{{ $supplier->name }}</a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
                <span style="color:var(--blue); font-weight:600;">Statistics</span>
            </div>

            {{-- ── PAGE HEADER ── --}}
            <div style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; justify-content:space-between; margin-bottom:20px;">
                <div>
                    <h1 style="font-size:28px; font-weight:800; margin:0 0 4px;" class="grad-text">Supplier Statistics</h1>
                    <p style="font-size:13px; color:#64748b; margin:0;">Financial clarity & balance monitoring per month</p>
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
                
                {{-- ── HEADER SUPPLIER INFO ── --}}
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
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
                    </div>

                    {{-- ── YEAR FILTER ── --}}
                    <form action="{{ route('finance.suppliers.statistic', $supplier->id) }}" method="GET" id="yearForm">
                        <select name="year" class="f-select" onchange="document.getElementById('yearForm').submit();">
                            @for($y = date('Y') + 1; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Year {{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                </div>

                {{-- ── TABLE STATISTICS ── --}}
                <table class="erp-stat-table">
                    <thead>
                        <tr>
                            <th style="width:25%;">LABEL</th>
                            <th style="width:37.5%;">BALANCE</th>
                            <th style="width:37.5%;">BALANCE DP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- STATIC ROWS -->
                        <tr>
                            <td class="label-col strong-label">CURRENT</td>
                            <td>
                                <input type="text" class="erp-num-input highlight" value="{{ number_format($currentBalance, 2, '.', ',') }}" readonly>
                            </td>
                            <td>
                                <input type="text" class="erp-num-input highlight-dp" value="{{ number_format($currentDp, 2, '.', ',') }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-col strong-label" style="border-bottom: 2px solid #cbd5e1;">BEGINNING BALANCE</td>
                            <td style="border-bottom: 2px solid #cbd5e1;">
                                <input type="text" class="erp-num-input highlight" value="{{ number_format($beginBalance, 2, '.', ',') }}" readonly>
                            </td>
                            <td style="border-bottom: 2px solid #cbd5e1;">
                                <input type="text" class="erp-num-input highlight-dp" value="{{ number_format($beginDp, 2, '.', ',') }}" readonly>
                            </td>
                        </tr>

                        <!-- MONTHLY ROWS -->
                        @foreach($monthlyStats as $month => $stat)
                            <tr>
                                <td class="label-col">{{ strtoupper($stat['name']) }}</td>
                                <td>
                                    <input type="text" class="erp-num-input" value="{{ number_format($stat['balance'], 2, '.', ',') }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="erp-num-input" value="{{ number_format($stat['dp'], 2, '.', ',') }}" readonly>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div style="margin-top:12px; font-size:11px; color:#94a3b8; text-align:right;">
                    * Amounts are purely derived from recorded financial transactions
                </div>
            </div>

        </div>
    </div>
@endsection
