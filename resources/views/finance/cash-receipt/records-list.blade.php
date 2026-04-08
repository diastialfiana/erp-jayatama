@extends('layouts.app')
@section('title', 'Receive List - Cash Receipt')

@push('styles')
<link href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --b-pri: #1E3A8A;
        --grid-focus: rgba(59, 130, 246, 0.15);
    }

    /* 2. SAMAKAN STYLE GLOBAL */
    body {
        margin: 0;
        font-family: inherit;
    }

    .erp-container {
        background: #f3f3f3;
        font-size: 12px;
        min-height: calc(100vh - 62px);
        display: flex;
        flex-direction: column;
        color: #333;
    }

    .erp-box {
        border: 1px solid #cfcfcf;
        background: #fff;
    }

    /* 1. STRUKTUR LAYOUT */
    .erp-header {
        display: flex;
        gap: 2px;
        padding: 8px 8px 0 8px;
        border-bottom: 1px solid #cfcfcf;
        background: #f3f3f3;
    }

    .erp-tab {
        padding: 6px 16px;
        background: #e2e8f0;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        text-decoration: none;
        border: 1px solid #cfcfcf;
        border-bottom: none;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }

    .erp-tab.active {
        background: var(--b-pri);
        color: #fff;
        border-color: var(--b-pri);
    }
    
    .erp-tab:hover:not(.active) {
        background: #cbd5e1;
    }

    .erp-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 8px;
        gap: 8px;
    }

    /* 3. GROUP BAR */
    .group-bar {
        padding: 6px;
        background: #fafafa;
        display: flex;
        align-items: center;
        color: #666;
        font-size: 12px;
    }

    /* 8. SEARCH */
    .search-container {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .search-box {
        border: 1px solid #cfcfcf;
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 2px;
        outline: none;
        width: 200px;
    }

    /* 4. TABLE STYLE (Re-mapped to Tabulator) */
    .grid-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0; 
    }

    .erp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        /* Erp-table class mapping to tabulator wrapper */
        flex: 1; 
    }

    /* Tabulator CSS Overrides to match .erp-table */
    .tabulator {
        background-color: transparent !important;
        border: none !important;
        font-size: 12px !important;
    }

    .tabulator-header {
        background: #e9ecef !important;
        border-bottom: 1px solid #ccc !important;
        color: #333 !important;
        font-weight: bold !important;
    }

    .tabulator-col {
        background: transparent !important;
        border-right: 1px solid #ccc !important;
    }

    .tabulator-col-title {
        padding: 4px !important;
    }

    .tabulator-row {
        border-bottom: 1px solid #ddd !important;
        min-height: 24px !important;
    }

    .tabulator-cell {
        padding: 4px !important;
        border-right: 1px solid #ddd !important;
        vertical-align: middle;
    }

    /* Hover */
    .tabulator-row:hover {
        background: #eaf3ff !important;
        cursor: pointer;
    }
    
    .tabulator-row.high-amount { background: #fff3cd !important; }
    .tabulator-row.high-amount:hover { background: #ffeba1 !important; }

    /* 6. NAVIGATION BAR */
    .erp-footer {
        border-top: 1px solid #ccc;
        padding: 4px;
        background: #f8f8f8;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 16px;
        font-size: 12px;
    }

    .nav-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 2px 6px;
        font-size: 12px;
        color: #333;
    }
    .nav-btn:hover { background: #ddd; }

    /* 7. STATUS BAR */
    .erp-status {
        padding: 4px 8px;
        background: #e9ecef;
        border-top: 1px solid #ccc;
        font-size: 11px;
        color: #555;
        display: flex;
        justify-content: space-between;
    }

    /* Extra */
    .badge-cash { background: #28a745; color: white; padding: 2px 6px; border-radius: 2px; font-size: 10px; font-weight: bold; }
    .badge-bank { background: #007bff; color: white; padding: 2px 6px; border-radius: 2px; font-size: 10px; font-weight: bold; }
    .badge-cr { background: #6c757d; color: white; padding: 2px 6px; border-radius: 2px; font-size: 10px; font-weight: bold; }
    
    .bulk-btn {
        font-size: 11px; padding: 2px 8px; background: #fff; border: 1px solid #cfcfcf; cursor: pointer; border-radius: 2px;
    }
    .bulk-btn:hover:not(:disabled) { background: #eee; }
    .bulk-btn:disabled { opacity: 0.5; cursor: not-allowed; }

</style>
@endpush

@section('content')
<!-- 1. STRUKTUR LAYOUT (WAJIB SAMA) -->
<div class="erp-container">

    <!-- Header Tab -->
    <div class="erp-header">
        <a href="{{ route('finance.cash-receipt.index') }}" class="erp-tab">
            Receive Detail
        </a>
        <a href="{{ route('finance.cash-receipt.records-list') }}" class="erp-tab active">
            Receive List
        </a>
    </div>

    <!-- Body -->
    <div class="erp-body">
        
        <!-- 3. GROUP BAR & 8. SEARCH -->
        <div class="erp-box group-bar" id="groupBar">
            <span id="groupText">Drag a column header here to group by that column</span>
            <button id="btnClearGroup" style="display:none; margin-left:8px; font-size:11px; cursor:pointer;">
                Clear Group
            </button>
            <div class="search-container">
                <input type="text" id="searchInput" class="search-box" placeholder="Search...">
                <span title="Search" style="cursor:pointer; font-size:14px; margin-left:4px;">🔍</span>
            </div>
        </div>

        <div style="display:flex; gap: 8px;">
            <button id="btnBulkDelete" class="bulk-btn" disabled>Bulk Delete</button>
            <button id="btnExport" class="bulk-btn">Export CSV</button>
        </div>

        <!-- 5. GRID CONTAINER -->
        <div class="erp-box grid-container">
            <div id="receiptsGrid" class="erp-table"></div>
        </div>

    </div>

    <!-- 6. NAVIGATION BAR -->
    <div class="erp-footer">
        <button class="nav-btn" onclick="grid.previousPage()">&lt;&lt;</button>
        <button class="nav-btn" onclick="grid.previousPage()">&lt;</button>
        <span id="recordInfo">Record 0 of 0</span>
        <button class="nav-btn" onclick="grid.nextPage()">&gt;</button>
        <button class="nav-btn" onclick="grid.nextPage()">&gt;&gt;</button>
    </div>
    
    <!-- 7. STATUS BAR -->
    <div class="erp-status">
        <span>Version: BarStaticItem1</span>
        <span>Ready</span>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>
<script>
    const rawData = {!! json_encode($receipts) !!};
    
    const formattedData = rawData.map(r => {
        let type = 'CR';
        let typeClass = 'badge-cr';
        let bankName = r.bank_account ? (r.bank_account.bank_name || 'Bank') : 'Unknown';
        
        if (bankName.toUpperCase().includes('CASH')) {
            type = 'CASH';
            typeClass = 'badge-cash';
        } else if (bankName.toUpperCase().includes('BANK')) {
            type = 'BANK';
            typeClass = 'badge-bank';
        }

        return {
            id: r.id,
            chk: false,
            type: `<span class="${typeClass}">${type}</span>`,
            customer_name: r.customer ? r.customer.name : 'Unknown Customer',
            ref: r.reference || ('CR-' + r.id),
            user_no: r.created_by ? (r.created_by.name || r.created_by.email || r.created_by.id) : 'System',
            date: r.date ? r.date.substring(0,10) : '',
            bank: bankName,
            total: parseFloat(r.total || 0)
        };
    });

    const emptyHtml = `<div style="text-align:center; padding:40px; color:#999; font-size:12px;">No data available</div>`;

    const grid = new Tabulator("#receiptsGrid", {
        data: formattedData,
        layout: "fitColumns",
        height: "100%",
        movableColumns: true, 
        selectableRows: true, 
        resizableColumnFit: true, 
        pagination: "local",
        paginationSize: 50,
        placeholder: emptyHtml,
        rowFormatter: function(row){
            if(row.getData().total > 50000000){
                row.getElement().classList.add("high-amount");
            }
        },
        groupHeader: function(value, count, data, group){
            return `<strong style="color:#1E3A8A;">${value}</strong> <span style="color:#666;">(${count} items)</span>`;
        },
        columns: [
            { formatter: "rowSelection", titleFormatter: "rowSelection", hozAlign: "center", headerHozAlign: "center", width: 40, headerSort: false, field: "chk", frozen: true },
            { title: "TYPE", field: "type", hozAlign: "center", headerHozAlign: "center", width: 70, formatter: "html", frozen: true },
            { title: "CUSTOMER NAME", field: "customer_name", hozAlign: "left", headerHozAlign: "left", minWidth: 150, flex: 2, frozen: true,
              formatter: function(cell){
                  let val = cell.getValue();
                  return `<strong style="color:#333;">${val}</strong>`;
              }
            },
            { title: "REF", field: "ref", hozAlign: "left", headerHozAlign: "left", minWidth: 100, flex: 1, 
              formatter: function(cell) { return `<span style="font-family:monospace; color:#555;">${cell.getValue()}</span>`; }
            },
            { title: "USER NO", field: "user_no", hozAlign: "left", headerHozAlign: "left", minWidth: 100, flex: 1 },
            { title: "DATE", field: "date", hozAlign: "left", headerHozAlign: "left", width: 90 },
            { title: "BANK", field: "bank", hozAlign: "left", headerHozAlign: "left", minWidth: 120, flex: 1.5 },
        ],
    });

    // 6. NAVIGATION UPDATE
    function updateRecordInfo() {
        let activeRows = grid.getData("active").length;
        let totalRows = grid.getData().length;
        document.getElementById('recordInfo').innerText = `Record ${activeRows} of ${totalRows}`;
    }

    grid.on("dataLoaded", updateRecordInfo);
    grid.on("pageLoaded", updateRecordInfo);
    grid.on("dataFiltered", updateRecordInfo);

    // 8. SEARCH LOGIC
    document.getElementById("searchInput").addEventListener("input", function(e) {
        let keyword = e.target.value;
        grid.setFilter(function(data){
            return String(data.customer_name).toLowerCase().includes(keyword.toLowerCase()) || 
                   String(data.ref).toLowerCase().includes(keyword.toLowerCase()) ||
                   String(data.bank).toLowerCase().includes(keyword.toLowerCase()) ||
                   String(data.date).includes(keyword) ||
                   String(data.user_no).toLowerCase().includes(keyword.toLowerCase());
        });
    });

    // 9. INTERAKSI -> CLICK ROW
    grid.on("rowClick", function(e, row){
        if(e.target.closest('.tabulator-cell').getAttribute('tabulator-field') === 'chk') return;
        let id = row.getData().id;
        window.location.href = `{{ route('finance.cash-receipt.index') }}?id=${id}`;
    });

    // DRAG TO GROUP LOGIC
    let dragColField = null;
    let groupBar = document.getElementById('groupBar');
    let groupText = document.getElementById('groupText');
    let btnClearGroup = document.getElementById('btnClearGroup');

    grid.on("headerDblClick", function(e, column){
        let field = column.getField();
        if(field !== 'chk' && field !== 'type') { applyGrouping(field, column.getDefinition().title); }
    });

    document.addEventListener('dragstart', function(e){
        let cell = e.target.closest('.tabulator-col');
        if(cell) {
            dragColField = cell.getAttribute('tabulator-field');
            if(dragColField === 'chk') { dragColField = null; e.preventDefault(); }
        }
    });

    document.addEventListener('dragover', function(e){
        if(e.target.closest('.group-bar') && dragColField) {
            e.preventDefault(); 
            groupBar.style.background = '#e9ecef';
        }
    });

    document.addEventListener('dragleave', function(e){
        if(e.target.closest('.group-bar')) {
            groupBar.style.background = '#fafafa';
        }
    });

    document.addEventListener('drop', function(e){
        if(e.target.closest('.group-bar') && dragColField) {
            e.preventDefault();
            groupBar.style.background = '#fafafa';
            let col = grid.getColumn(dragColField);
            applyGrouping(dragColField, col.getDefinition().title);
        }
    });

    function applyGrouping(field, title) {
        grid.setGroupBy(field);
        groupText.innerHTML = `<strong>Grouped by:</strong> ${title}`;
        btnClearGroup.style.display = 'inline-block';
    }

    btnClearGroup.addEventListener('click', function(e){
        e.stopPropagation();
        grid.setGroupBy(null);
        groupText.innerHTML = `Drag a column header here to group by that column`;
        btnClearGroup.style.display = 'none';
        dragColField = null;
    });

    // BULK ACTIONS
    grid.on("rowSelectionChanged", function(data, rows){
        document.getElementById('btnBulkDelete').disabled = rows.length === 0;
    });

    document.getElementById('btnBulkDelete').addEventListener('click', function(){
        let selected = grid.getSelectedData();
        if(confirm(`Are you sure you want to delete ${selected.length} records?`)) {
            alert('Bulk delete triggered for ' + selected.length + ' records.');
        }
    });
    
    document.getElementById('btnExport').addEventListener('click', function(){
        grid.download("csv", "receive_list.csv");
    });
</script>
@endpush
