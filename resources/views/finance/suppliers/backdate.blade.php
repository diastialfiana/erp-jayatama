@extends('layouts.app')
@section('title', 'Supplier Backdate - Finance')

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
        .tabulator-row:nth-child(even) { background-color: #fff !important; }
        .tabulator-row:nth-child(odd) { background-color: #fff !important; }
        .tabulator-row:hover {
            background-color: #eff6ff !important;
            cursor: pointer;
        }

        .row-details {
            padding: 16px 32px;
            background: #f8fafc;
            border-bottom: 1px solid #cbd5e1;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
            animation: slideDown 0.2s ease-out;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-3px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-expand {
            color: #64748b; background: #fff; border: 1px solid #cbd5e1;
            border-radius: 4px; padding: 0px 5px; font-size: 11px;
            font-weight: 900; cursor: pointer; transition: all 0.2s;
        }
        .btn-expand:hover, .btn-expand.active {
            background: #eff6ff; color: var(--blue); border-color: #bfdbfe;
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
                <span style="color:var(--blue); font-weight:600;">Backdate Audit</span>
            </div>

            {{-- ── PAGE HEADER ── --}}
            <div style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; justify-content:space-between; margin-bottom:20px;">
                <div>
                    <h1 style="font-size:28px; font-weight:800; margin:0 0 4px;" class="grad-text">Supplier Backdate</h1>
                    <p style="font-size:13px; color:#64748b; margin:0;">Monitoring perubahan tanggal transaksi supplier</p>
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

                {{-- ── DATE FILTER ── --}}
                <form action="{{ route('finance.suppliers.backdate', $supplier->id) }}" method="GET" class="filter-form">
                    <span class="f-label">Date Filter</span>
                    <input type="date" name="date" class="f-date-input" value="{{ $filterDate }}">
                    <button type="submit" class="f-btn" title="Refresh">🔄</button>
                </form>
            </div>

            {{-- ── BACKDATE GRID ── --}}
            <div>
                <div class="erp-group-bar">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="12" r="1"></circle><circle cx="9" cy="5" r="1"></circle><circle cx="9" cy="19" r="1"></circle><circle cx="15" cy="12" r="1"></circle><circle cx="15" cy="5" r="1"></circle><circle cx="15" cy="19" r="1"></circle></svg>
                    Drag a column header here to group by that column
                </div>
                <div class="erp-table-container">
                    <div id="backdate-table" class="erp-table"></div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const tableData = @json($backdateSuppliers);

    const moneyFormatter = function(cell) {
        let val = parseFloat(cell.getValue() || 0);
        return val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    };

    const expandFormatter = function(cell) {
        return "<button class='btn-expand'>▶</button>";
    };

    const table = new Tabulator("#backdate-table", {
        data: tableData,
        layout: "fitColumns",
        movableColumns: true,
        groupBy: false, // Default no grouping, user can conceptually drag columns but Tabulator 6 requires advanced setup for visual drag banner to work. We stick to normal rendering.
        columnHeaderVertAlign: "bottom",
        placeholder: "<div style='padding:20px; color:#94a3b8; font-size:13px; text-align:center;'>Tidak ada data supplier dengan backdate.</div>",
        
        columns: [
            {formatter: expandFormatter, width: 40, hozAlign:"center", headerSort:false, cellClick:function(e, cell){
                e.stopPropagation();
                let row = cell.getRow();
                let detailsEl = row.getElement().querySelector('.row-details');
                let btn = cell.getElement().querySelector('button');
                
                if(detailsEl) {
                    detailsEl.remove();
                    btn.textContent = "▶";
                    btn.classList.remove('active');
                } else {
                    let d = row.getData();
                    let el = document.createElement("div");
                    el.classList.add("row-details");
                    
                    let txs = d.transactions || [];
                    let txHtml = txs.map(tx => {
                        let orig = tx.original_date ? tx.original_date : '-';
                        let updated = tx.transaction_date ? tx.transaction_date : '-';
                        return `<div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #e2e8f0;">
                            <div style="font-weight:600;">Transaction ID: ${tx.id} Type: <span style="text-transform:uppercase;">${tx.type}</span></div>
                            <div style="font-family:monospace; background:#f1f5f9; padding:2px 6px; border-radius:4px; border:1px solid #e2e8f0;">
                                <strong style="color:#ef4444;">${orig}</strong> &rarr; <strong style="color:#10b981;">${updated}</strong>
                            </div>
                        </div>`;
                    }).join('');
                    
                    if (txs.length === 0) {
                        txHtml = `<div style="padding:8px 0; color:#64748b;">No detail backdate transactions returned.</div>`;
                    }

                    el.innerHTML = `
                        <div style="font-size:11px; text-transform:uppercase; font-weight:800; color:#475569; margin-bottom:8px; border-bottom:2px solid #cbd5e1; padding-bottom:4px; display:inline-block;">Backdate Details</div>
                        ${txHtml}
                    `;
                    row.getElement().appendChild(el);
                    btn.textContent = "▼";
                    btn.classList.add('active');
                }
            }},
            {title:"CODE", field:"code", width:120, sorter:"string", headerTooltip:"Supplier Code", cssClass:"font-weight-bold"},
            {title:"CURR", field:"currency", width:80, hozAlign:"center", sorter:"string"},
            {title:"SUPPLIER NAME", field:"name", minWidth:250, sorter:"string"},
            {title:"BALANCE", field:"balance", width:160, hozAlign:"right", formatter:moneyFormatter, sorter:"number"},
            {title:"BALANCE DP", field:"balance_dp", width:160, hozAlign:"right", formatter:moneyFormatter, sorter:"number"}
        ],
    });

    table.on("rowClick", function(e, row){
        if(!e.target.closest('.btn-expand')){
            let id = row.getData().id;
            window.location.href = "{{ url('/finance/suppliers') }}/" + id;
        }
    });

});
</script>
@endpush
