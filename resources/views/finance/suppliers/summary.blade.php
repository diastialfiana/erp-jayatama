@extends('layouts.app')
@section('title', 'Supplier Summary - Finance')

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
        }
        .f-btn {
            background: #f8fafc;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 16px;
            line-height:1;
            cursor: pointer;
            transition: background 0.2s;
        }
        .f-btn:hover { background: #e2e8f0; }

        /* ── ERP Group Bar ── */
        .erp-group-bar {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-bottom: none;
            padding: 10px 16px;
            font-size: 11px;
            color: #64748b;
            font-style: italic;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Table Adjustments ── */
        .erp-table-container {
            border: 1px solid #cbd5e1;
            border-radius: 0 0 8px 8px;
            overflow: hidden;
            background: #fff;
        }
        .erp-table {
            font-size: 11.5px !important;
            font-family: 'Inter', sans-serif;
            border: none;
        }
        .tabulator-header {
            background-color: #f1f5f9 !important;
            border-bottom: 1px solid #cbd5e1 !important;
        }
        .tabulator-col {
            background-color: transparent !important;
            font-weight: 700 !important;
            color: #334155 !important;
            border-right: 1px solid #e2e8f0 !important;
        }
        .tabulator-row {
            border-bottom: 1px solid #e2e8f0;
            min-height: 32px !important;
            transition: background 0.15s;
        }
        .tabulator-row:nth-child(even) { background-color: #f8fafc !important; }
        .tabulator-row:nth-child(odd) { background-color: #fff !important; }
        .tabulator-row:hover {
            background-color: #eff6ff !important;
        }

    </style>
    <link href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator.min.css" rel="stylesheet">
@endpush

@section('content')
    <div style="background:var(--bg); min-height:calc(100vh - 62px); padding:28px 24px;">
        <div style="max-width:1440px; margin:0 auto;">

            {{-- ── BREADCRUMB ── --}}
            <div style="display:flex; align-items:center; gap:6px; font-size:12px; color:#94a3b8; margin-bottom:18px;">
                <a href="{{ route('finance.suppliers.records-list') }}" style="color:#94a3b8; text-decoration:none;">Finance</a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('finance.suppliers.records-list') }}" style="color:#94a3b8; text-decoration:none;">Supplier List</a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('finance.suppliers.show', $supplier->id) }}" style="color:#94a3b8; text-decoration:none;">{{ $supplier->name }}</a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
                <span style="color:var(--blue); font-weight:600;">Transaction Summary</span>
            </div>

            {{-- ── PAGE HEADER ── --}}
            <div style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; justify-content:space-between; margin-bottom:20px;">
                <div>
                    <h1 style="font-size:28px; font-weight:800; margin:0 0 4px;" class="grad-text">Supplier Summary</h1>
                    <p style="font-size:13px; color:#64748b; margin:0;">Ringkasan transaksi dan mutasi saldo supplier interaktif</p>
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

            {{-- ── ERP HEADER BLOCK + FILTER ── --}}
            <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; margin-bottom: 20px; gap:16px;">
                <div class="stat-header-block" style="margin:0;">
                    <div class="stat-h-item">
                        <span class="stat-h-label">Code</span>
                        <span class="stat-h-val">{{ $supplier->code }}</span>
                    </div>
                    <div style="width:2px; height:30px; background:#cbd5e1;"></div>
                    <div class="stat-h-item">
                        <span class="stat-h-label">Currency</span>
                        <span class="stat-h-val">{{ $supplier->currency ?? 'IDR' }}</span>
                    </div>
                    <div style="width:2px; height:30px; background:#cbd5e1;"></div>
                    <div class="stat-h-item">
                        <span class="stat-h-label">Supplier Name</span>
                        <span class="stat-h-val">{{ $supplier->name }}</span>
                    </div>
                </div>

                {{-- ── DATE RANGE FILTER ── --}}
                <form action="{{ route('finance.suppliers.summary', $supplier->id) }}" method="GET" class="filter-form">
                    <input type="date" name="start_date" class="f-date-input" value="{{ $startDate }}">
                    <span style="color:#94a3b8; font-weight:600; font-size:12px; font-style:italic;">thru</span>
                    <input type="date" name="end_date" class="f-date-input" value="{{ $endDate }}">
                    <button type="submit" class="f-btn" title="Refresh Data">🔄</button>
                </form>
            </div>

            {{-- ── SUMMARY GRID ── --}}
            <div>
                <div class="erp-group-bar">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="12" r="1"></circle><circle cx="9" cy="5" r="1"></circle><circle cx="9" cy="19" r="1"></circle><circle cx="15" cy="12" r="1"></circle><circle cx="15" cy="5" r="1"></circle><circle cx="15" cy="19" r="1"></circle></svg>
                    Drag a column header here to group by that column
                </div>
                <div class="erp-table-container">
                    <div id="summary-table" class="erp-table"></div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Server data array
    const tableData = @json($suppliers);

    // Formatter logic for numbers. Using negative -> red color styling mapping natively
    const moneyFormatter = function(cell) {
        let val = parseFloat(cell.getValue() || 0);
        let formatted = val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        if (val < 0) {
            return `<span style="color: #ef4444; font-weight:600;">${formatted}</span>`;
        }
        return `<span style="color: #0f172a; font-weight:600;">${formatted}</span>`;
    };

    const table = new Tabulator("#summary-table", {
        data: tableData,
        layout: "fitDataFill", // Use horizontal scrolling if columns are crowded
        movableColumns: true,
        groupBy: false, // Default is false, we can let user group via columns if set up properly
        columnHeaderVertAlign: "bottom",
        
        columns: [
            {title:"CODE", field:"code", minWidth:100, headerSort:true, headerTooltip:"Supplier Code", cssClass:"font-weight-bold"},
            {title:"SUPPLIER NAME", field:"name", minWidth:200, headerSort:true},
            {title:"BEG. BALANCE", field:"beg_balance", width:140, hozAlign:"right", formatter:moneyFormatter, headerSort:false},
            {title:"INVOICE", field:"invoice", width:130, hozAlign:"right", formatter:moneyFormatter, headerSort:false},
            {title:"RETURN", field:"return", width:130, hozAlign:"right", formatter:moneyFormatter, headerSort:false},
            {title:"PURCHASE", field:"purchase", width:140, hozAlign:"right", formatter:moneyFormatter, headerSort:false},
            {title:"PO. RETURN", field:"po_return", width:140, hozAlign:"right", formatter:moneyFormatter, headerSort:false},
            {title:"PAYMENT", field:"payment", width:140, hozAlign:"right", formatter:moneyFormatter, headerSort:false},
            {title:"DP", field:"dp", width:120, hozAlign:"right", formatter:moneyFormatter, headerSort:false},
            {title:"BALANCE", field:"balance", width:150, hozAlign:"right", formatter:moneyFormatter, headerSort:true}
        ],
    });
});
</script>
@endpush
