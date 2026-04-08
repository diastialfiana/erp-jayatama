@extends('layouts.app')
@section('title', 'List All e-Banking Requests - Finance')

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
        min-height: 28px !important; border-bottom: 1px solid #e2e8f0; cursor: pointer;
    }
    .tabulator-row:hover { background-color: #f0f4ff !important; }

    /* Badges */
    .badge {
        padding: 3px 8px; border-radius: 4px; font-weight: 700; font-size: 10px; text-transform: uppercase; border: 1px solid transparent;
    }
    .badge-pending { background: #fef9c3; color: #854d0e; border-color: #fef08a; }
    .badge-approved { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .badge-rejected { background: #fef2f2; color: #991b1b; border-color: #fecACA; }
    
    .type-chip {
        background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size:10px; font-weight: 600; color: #334155; border: 1px solid #cbd5e1;
    }

    /* Footer / Status Bar */
    .grid-footer {
        background: #f8fafc; border: 1px solid #cbd5e1; border-top: none;
        padding: 8px 12px; display: flex; justify-content: space-between; align-items:center;
        border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;
    }
    .val-tot { font-weight:800; color: #1e3a8a; font-size: 13px; }

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
            <a href="{{ route('finance.ebanking-request.index') }}" class="erp-tab">Detail</a>
            <a href="{{ route('finance.ebanking-request.recordList') }}" class="erp-tab active">List All</a>
        </div>

        <div class="top-action-bar">
            <div class="group-drop-msg">Drag a column header here to group by that column</div>
            <div class="search-box">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="recordSearch" class="search-input" placeholder="Search data...">
            </div>
        </div>

        <div id="ebankingGrid"></div>

        <div class="grid-footer">
            <div style="font-size:11px; color:#64748b;">
                <span id="navInfo"><< < Record 0 of 0 > >></span>
            </div>
            <div>
                <span style="font-size:11px; font-weight:700; color:#64748b; margin-right:8px;">TOTAL FILTERED:</span>
                <span id="fTotal" class="val-tot">0.00</span>
            </div>
        </div>

        <div class="status-bar">
            <span>Mode: BROWSE</span>
            <span>Version: 1.0.0 | BarStaticItem1 | Online</span>
        </div>

    </div>
</div>
@endsection

@php
    $mapped = $requests->map(function($req) {
        $statusHtml = '';
        if($req->status === 'pending') $statusHtml = '<span class="badge badge-pending">Pending</span>';
        if($req->status === 'approved') $statusHtml = '<span class="badge badge-approved">Approved</span>';
        if($req->status === 'rejected') $statusHtml = '<span class="badge badge-rejected">Rejected</span>';

        $typeLabel = $req->type === 'operational' ? 'OP' : 'NON-OP';
        $typeHtml = '<span class="type-chip">'.$typeLabel.'</span>';

        return [
            'id' => $req->id,
            'date' => \Carbon\Carbon::parse($req->date)->format('Y-m-d'),
            'type' => $typeLabel,
            'type_html' => $typeHtml,
            'data_ref' => $req->invoice_id ?? '-',
            'user' => $req->user->name ?? 'System',
            'account_no' => $req->account_no,
            'account_name' => $req->account_name,
            'bank_name' => $req->bank_name,
            'total' => $req->amount,
            'audit' => $statusHtml
        ];
    });
@endphp

@push('scripts')
<script src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>
<script>
    const tableData = @json($mapped);

    const numFmt = function(cell) {
        return parseFloat(cell.getValue() || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    };

    var table = new Tabulator("#ebankingGrid", {
        data: tableData,
        layout: "fitColumns",
        groupBy: false, // Default flat
        groupHeader: function(value, count, data, group){
            return `<span style="color:#1e3a8a; font-weight:700;">${value}</span> <span style="color:#64748b; font-size:11px;">(${count} items)</span>`;
        },
        movableColumns: true,
        height: "65vh",
        columns: [
            { title:"DATE", field:"date", headerHozAlign:"left", hozAlign:"left", width:110 },
            { title:"TYPE", field:"type", headerHozAlign:"center", hozAlign:"center", width:90, formatter:function(c) { return c.getData().type_html; } },
            { title:"DATA REF", field:"data_ref", headerHozAlign:"left", hozAlign:"left", width:140 },
            { title:"USER NO", field:"user", headerHozAlign:"left", hozAlign:"left", width:120 },
            { title:"ACCOUNT NO.", field:"account_no", headerHozAlign:"left", hozAlign:"left", width:140 },
            { title:"ACCOUNT NAME", field:"account_name", headerHozAlign:"left", hozAlign:"left", minWidth:180 },
            { title:"BANK NAME", field:"bank_name", headerHozAlign:"left", hozAlign:"left", width:120 },
            { title:"TOTAL", field:"total", headerHozAlign:"right", hozAlign:"right", width:130, formatter:numFmt },
            { title:"AUDIT", field:"audit", headerHozAlign:"left", hozAlign:"left", width:120, formatter:"html" }
        ],
    });

    // Interaction Click Row
    table.on("rowClick", function(e, row){
        window.location.href = "{{ route('finance.ebanking-request.index') }}"; 
        // Note: Could be redirected to a dedicated #id parameter or so.
    });

    // Dynamic Search & Footer Metrics
    function updateFooter() {
        let activeData = table.getData("active");
        let sumTot = 0;
        
        activeData.forEach(r => {
            sumTot += parseFloat(r.total) || 0;
        });

        document.getElementById('fTotal').innerText = sumTot.toLocaleString('en-US', {minimumFractionDigits:2});
        
        if (activeData.length > 0) {
            document.getElementById('navInfo').innerText = `<< < Record 1 of ${activeData.length} > >>`;
        } else {
            document.getElementById('navInfo').innerText = `<< < Record 0 of 0 > >>`;
        }
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
