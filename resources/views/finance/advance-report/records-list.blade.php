@extends('layouts.app')
@section('title', 'Advance List - Finance')

@push('styles')
<!-- Tabulator CSS -->
<link href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator_simple.min.css" rel="stylesheet">
<style>
    :root {
        --b-pri: #1E3A8A;
    }
    .erp-tab-nav {
        display: flex; gap: 2px; margin-bottom: 16px;
    }
    .erp-tab {
        padding: 8px 16px; background: #e2e8f0; color: #475569; font-size: 12px; font-weight: 600; 
        text-transform: uppercase; text-decoration: none; border-top-left-radius: 4px; border-top-right-radius: 4px;
        transition: 0.2s;
    }
    .erp-tab:hover { background: #cbd5e1; }
    .erp-tab.active { background: var(--b-pri); color: #fff; }

    /* Group Bar & Toolbar */
    .top-action-bar {
        background: #f8fafc; border: 1px solid #cbd5e1; border-bottom: none;
        padding: 8px 12px; display: flex; justify-content: space-between; align-items: center;
        border-top-left-radius: 4px; border-top-right-radius: 4px;
    }
    .group-drop-msg {
        font-size: 11px; color: #64748b; font-weight: 600; letter-spacing: 0.5px;
    }
    .search-box {
        position: relative; width: 200px;
    }
    .search-input {
        width: 100%; padding: 6px 12px 6px 30px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px;
    }
    .search-icon {
        position: absolute; left: 8px; top: 7px; width: 14px; height: 14px; color: #94a3b8;
    }

    /* Tabulator Overrides */
    .tabulator {
        font-size: 11.5px; font-family: 'Inter', sans-serif; border: 1px solid #cbd5e1;
        background-color: #f1f5f9; /* abu muda */
    }
    .tabulator-col-title {
        font-weight: 700 !important; color: #1e293b;
    }
    .tabulator-row {
        min-height: 28px !important; border-bottom: 1px solid #e2e8f0;
    }
    .tabulator-row:hover { background-color: #f0f4ff !important; }

    .tab-link-btn {
        color: #2563eb; text-decoration: none; font-weight: 600;
        background: rgba(37,99,235,0.1); padding: 2px 8px; border-radius: 4px;
    }
    .tab-link-btn:hover { background: rgba(37,99,235,0.2); }
    
    .badge-over {
        background: #fef2f2; color: #b91c1c; padding: 2px 6px; border-radius: 4px; font-weight: 700; border: 1px solid #fca5a5; font-size:10px;
    }
    .badge-ok {
        background: #f0fdf4; color: #166534; padding: 2px 6px; border-radius: 4px; font-weight: 700; border: 1px solid #bbf7d0; font-size:10px;
    }
    
    .hlt-expense { color: #dc2626; font-weight: 700; }

    /* Footer / Status Bar */
    .grid-footer {
        background: #f8fafc; border: 1px solid #cbd5e1; border-top: none;
        padding: 8px 12px; display: flex; justify-content: space-between;
        border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;
    }
    .footer-totals {
        display: flex; gap: 24px; font-size: 13px; font-weight: 700; color: #334155;
    }
    .val-tot { color: #1e3a8a; }
    .val-exp { color: #dc2626; }
    .val-back { color: #059669; }

    .status-bar {
        background: #e2e8f0; color: #475569; font-size: 11px; padding: 6px 16px; margin-top: 24px;
        border-radius: 4px; display: flex; justify-content: space-between; font-weight: 600;
    }
</style>
@endpush

@section('content')
<div style="background:#fff; min-height:calc(100vh - 62px); padding:24px;">
    <div style="max-width:1440px; margin:0 auto;">

        <!-- ERP Tab Navigation -->
        <div class="erp-tab-nav">
            <a href="{{ route('finance.advance-report.index') }}" class="erp-tab">Advance Detail</a>
            <a href="{{ route('finance.advance-report.recordList') }}" class="erp-tab active">Advance List</a>
        </div>

        @if(session('success'))
            <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:4px; margin-bottom:16px; font-size:13px; font-weight:600;">{{ session('success') }}</div>
        @endif

        <div class="top-action-bar">
            <div class="group-drop-msg">Drag a column header here to group by that column</div>
            <div class="search-box">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="recordSearch" class="search-input" placeholder="Search data...">
            </div>
        </div>

        <div id="advanceGrid"></div>

        <div class="grid-footer">
            <div style="font-size:11px; color:#64748b;">
                <span id="navInfo"><< < Record 0 of 0 > >></span>
            </div>
            <div class="footer-totals">
                <div>TOTAL: <span id="fTotal" class="val-tot">0.00</span></div>
                <div>EXPENSES: <span id="fExp" class="val-exp">0.00</span></div>
                <div>CASHBACK: <span id="fBack" class="val-back">0.00</span></div>
            </div>
        </div>

        <div class="status-bar">
            <span>Mode: BROWSE</span>
            <span>Version: 1.0.0 | BarStaticItem1 | Online</span>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>
@php
    $mappedAdvances = $advances->map(function($adv) {
        $auditBadge = ($adv->total_expenses > $adv->total_advance) ? '<span class="badge-over">OVERBUDGET</span>' : '<span class="badge-ok">SETTLED</span>';
        if ($adv->total_expenses == 0) $auditBadge = '<span class="badge-ok" style="background:#f1f5f9; color:#475569; border-color:#cbd5e1;">PENDING</span>';

        return [
            'id' => $adv->id,
            'type' => 'Advance',
            'employee' => $adv->employee->name ?? 'N/A',
            'reference' => $adv->reference,
            'user' => $adv->creator->name ?? 'N/A',
            'date' => \Carbon\Carbon::parse($adv->date)->format('Y-m-d'),
            'bank' => $adv->bankAccount->code ?? 'N/A',
            'currency' => $adv->currency,
            'rate' => $adv->rate,
            'total' => $adv->total_advance,
            'expenses' => $adv->total_expenses,
            'cashback' => $adv->total_cashback,
            'note' => $adv->note,
            'audit' => $auditBadge,
            'is_over' => ($adv->total_expenses > $adv->total_advance)
        ];
    });
@endphp

<script>
    // Data Mapping from Controller
    const tableData = @json($mappedAdvances);

    // Math Formatting
    const numFmt = function(cell) {
        return parseFloat(cell.getValue() || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    };

    const expFmt = function(cell) {
        let val = parseFloat(cell.getValue() || 0);
        let str = val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        let rowData = cell.getRow().getData();
        if(rowData.is_over) {
            return `<span class="hlt-expense">${str}</span>`;
        }
        return str;
    };

    const linkFmt = function(cell, params, onRendered) {
        let id = cell.getRow().getData().id;
        // The View Detail route typically takes an ID. 
        // Fallback: reload or go to index. (To be mapped in Laravel later if specific edit route is made).
        return `<a href="/finance/advance-report" class="tab-link-btn">Open</a>`;
    };

    // Initialize Grid
    var table = new Tabulator("#advanceGrid", {
        data: tableData,
        layout: "fitColumns",
        groupBy: false, // will be handled by drag
        groupHeader: function(value, count, data, group){
            return `<span style="color:#1e3a8a; font-weight:700;">${value}</span> <span style="color:#64748b; font-size:11px;">(${count} items)</span>`;
        },
        movableColumns: true,
        height: "65vh",
        columns: [
            { title:"TYPE", field:"type", headerHozAlign:"left", hozAlign:"left", width:90 },
            { title:"EMPLOYEES NAME", field:"employee", headerHozAlign:"left", hozAlign:"left", width:160 },
            { title:"REF", field:"reference", headerHozAlign:"left", hozAlign:"left", width:130 },
            { title:"USER NO", field:"user", headerHozAlign:"left", hozAlign:"left", width:110 },
            { title:"DATE", field:"date", headerHozAlign:"left", hozAlign:"left", width:100 },
            { title:"BANK", field:"bank", headerHozAlign:"left", hozAlign:"left", width:90 },
            { title:"CURR", field:"currency", headerHozAlign:"center", hozAlign:"center", width:60 },
            { title:"RATE", field:"rate", headerHozAlign:"right", hozAlign:"right", width:80, formatter:numFmt },
            { title:"TOTAL", field:"total", headerHozAlign:"right", hozAlign:"right", width:110, formatter:numFmt },
            { title:"EXPENSES", field:"expenses", headerHozAlign:"right", hozAlign:"right", width:110, formatter:expFmt },
            { title:"CASHBACK", field:"cashback", headerHozAlign:"right", hozAlign:"right", width:100, formatter:numFmt },
            { title:"LINK", field:"id", headerHozAlign:"center", hozAlign:"center", width:70, formatter:linkFmt, headerSort:false },
            { title:"NOTE", field:"note", headerHozAlign:"left", hozAlign:"left", minWidth: 150 },
            { title:"AUDIT", field:"audit", headerHozAlign:"left", hozAlign:"left", width:110, formatter:"html" }
        ],
    });

    // Dynamic Search & Footer Logic
    function updateFooter() {
        let activeData = table.getData("active");
        let sumTot = 0, sumExp = 0, sumBack = 0;
        
        activeData.forEach(r => {
            sumTot += parseFloat(r.total) || 0;
            sumExp += parseFloat(r.expenses) || 0;
            sumBack += parseFloat(r.cashback) || 0;
        });

        document.getElementById('fTotal').innerText = sumTot.toLocaleString('en-US', {minimumFractionDigits:2});
        document.getElementById('fExp').innerText = sumExp.toLocaleString('en-US', {minimumFractionDigits:2});
        document.getElementById('fBack').innerText = sumBack.toLocaleString('en-US', {minimumFractionDigits:2});

        document.getElementById('navInfo').innerText = `<< < Record 1 to ${activeData.length} of ${tableData.length} > >>`;
    }

    table.on("dataFiltered", function(){ updateFooter(); });
    table.on("dataLoaded", function(){ updateFooter(); });

    document.getElementById("recordSearch").addEventListener("input", function(e){
        table.setFilter(customFilter, e.target.value);
    });

    function customFilter(data, filterParams) {
        let val = filterParams.toLowerCase();
        for(let key in data) {
            if(String(data[key]).toLowerCase().includes(val)) return true;
        }
        return false;
    }
</script>
@endpush
