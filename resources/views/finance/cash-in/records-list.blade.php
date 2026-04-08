@extends('layouts.app')
@section('title', 'Cash In Records - Finance')

@push('styles')
<!-- Tabulator CSS -->
<link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator_simple.min.css" rel="stylesheet">
<style>
    :root {
        --b-pri: #1E3A8A;
        --b-acc: #3b82f6; 
        --grid-border: #cbd5e1;
        --grid-bg: #f8fafc;
        --erp-blue: #0f172a;
    }
    
    /* Layout */
    .erp-box {
        background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 16px; margin-bottom: 16px; display: flex; flex-direction: column; height: calc(100vh - 180px);
    }
    
    /* Header Grouping Bar */
    .group-bar {
        background: #f1f5f9; border: 1px dashed #94a3b8; color: #64748b; font-size: 11px; padding: 8px 12px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; font-weight: 600;
    }

    .group-drop-zone {
        flex: 1; min-height: 20px; display:flex; gap:8px; align-items:center;
    }

    .group-chip {
        background: var(--b-pri); color: #fff; padding: 2px 8px; border-radius: 12px; font-size: 10px; cursor: pointer; display: flex; align-items: center; gap: 4px;
    }

    /* Tabulator Overrides for ERP Style */
    .tabulator {
        font-family: 'Inter', sans-serif; font-size: 11px; border: 1px solid var(--grid-border); background: #fff; flex: 1; width: 100%;
    }
    .tabulator .tabulator-header {
        background: #f1f5f9; color: #334155; font-weight: 700; font-size: 11px; border-bottom: 2px solid var(--grid-border);
    }
    .tabulator .tabulator-header .tabulator-col {
        background: transparent; border-right: 1px solid var(--grid-border);
    }
    .tabulator .tabulator-header .tabulator-col .tabulator-col-content {
        padding: 6px;
    }
    .tabulator .tabulator-row {
        min-height: 24px; border-bottom: 1px solid #e2e8f0; background: #fff;
    }
    .tabulator .tabulator-row:nth-child(even) { background: #f8fafc; }
    .tabulator .tabulator-row:hover { background: #e0e7ff; cursor: pointer; }
    .tabulator .tabulator-row.tabulator-selected { background: #dbeafe; color: #1e40af; border-left: 2px solid var(--b-pri); }
    .tabulator .tabulator-cell {
        padding: 4px 6px; border-right: 1px solid #e2e8f0; display: inline-flex; align-items: center;
    }
    
    /* Footer & Navigation */
    .grid-footer {
        display: flex; justify-content: space-between; align-items: center; padding-top: 8px; margin-top: 8px; border-top: 1px solid var(--grid-border); font-size: 11px; font-weight: 600; color: #475569;
    }
    
    .grid-totals {
        display: flex; gap: 32px; font-size: 12px; font-weight: 700; color: #1e3a8a; font-variant-numeric: tabular-nums;
    }

    .erp-pagination {
        display: flex; align-items: center; gap: 8px;
    }
    .erp-pagination button {
        background: #fff; border: 1px solid #cbd5e1; padding: 2px 6px; cursor: pointer; border-radius: 2px;
    }
    .erp-pagination button:hover { background: #f1f5f9; }

    .status-bar {
        background: #e2e8f0; color: #475569; font-size: 11px; padding: 8px 16px; margin-top: 24px; border-radius: 4px; display: flex; justify-content: space-between; font-weight: 600;
    }

    /* Fast filtering input */
    .quick-search {
        background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px 4px 24px; font-size: 11px; outline: none; width: 180px; transition: 0.2s; background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 24 24" fill="none" stroke="%2394a3b8" stroke-width="2" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>'); background-repeat: no-repeat; background-position: 6px center; background-size: 12px;
    }
    .quick-search:focus { border-color: var(--b-acc); }

</style>
@endpush

@section('content')
<div style="background:var(--grid-bg); min-height:calc(100vh - 62px); padding:24px;">
    <div style="max-width:1440px; margin:0 auto;">
        
        <!-- ERP Tab Navigation -->
        <div style="display:flex; gap:2px; margin-bottom:16px;">
            <a href="{{ route('finance.cash-in.index') }}" style="padding:8px 16px; background:#e2e8f0; color:#475569; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                Record Detail
            </a>
            <a href="{{ route('finance.cash-in.records-list') }}" style="padding:8px 16px; background:var(--b-pri); color:#fff; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                Record List
            </a>
        </div>

        <div class="erp-box">
            
            <div class="group-bar">
                <div class="group-drop-zone" id="groupDropZone">
                    <span style="color:#94a3b8;">Drag a column header here to group by that column</span>
                </div>
                <div>
                    <input type="text" class="quick-search" id="quickSearch" placeholder="Fast Search...">
                </div>
            </div>

            <!-- Target for Tabulator -->
            <div id="erpGrid"></div>

            <!-- Footer summary -->
            <div class="grid-footer">
                <div class="erp-pagination">
                    <button id="pFirst"><<</button>
                    <button id="pPrev"><</button>
                    <span id="pInfo">Record 0 of 0</span>
                    <button id="pNext">></button>
                    <button id="pLast">>></button>
                </div>
                <div class="grid-totals">
                    <div style="text-align:right;">
                        <div style="font-size:10px; color:#64748b;">TOTAL CASH</div>
                        <div id="totalCash">0.00</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:10px; color:#64748b;">TOTAL GIRO</div>
                        <div id="totalGiro">0.00</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="status-bar">
            <span>Version: 1.0.0</span>
            <span>BarStaticItem1 | Idle</span>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

<script>
    // Prepare Data
    var tableData = @json($cashIns);
    
    // Process map for table rendering
    var processedData = tableData.map(item => {
        return {
            id: item.id,
            ref: item.reference || '-',
            user: item.user ? item.user.name : (item.created_by || 'SYS'),
            date: item.date,
            bank: item.bank_account ? item.bank_account.name : '-',
            curr: item.currency,
            rate: parseFloat(item.rate).toFixed(2),
            cash: parseFloat(item.cash_amount || 0).toFixed(2),
            giro: parseFloat(item.giro_amount || 0).toFixed(2),
            link: '-',
            note: item.note || ''
        }
    });

    // Formatting Helpers
    var checkFormatter = function(cell, formatterParams, onRendered){
        return "<input type='checkbox' class='row-select' />";
    };

    var moneyFormatter = function(cell, formatterParams, onRendered){
        let val = parseFloat(cell.getValue());
        if(isNaN(val)) return "0.00";
        return val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    };

    // Initialize Tabulator Grid
    var table = new Tabulator("#erpGrid", {
        data: processedData,
        layout: "fitColumns",
        selectable: true, 
        movableColumns: true,
        height: "100%",
        pagination: "local",
        paginationSize: 50,
        placeholder: "No Transactions Found",
        groupBy: false, 
        keybindings: true, // Enables keyboard nav
        columns: [
            {title: "CHK", field: "chk", formatter: checkFormatter, hozAlign: "center", headerHozAlign: "center", width: 50, headerSort: false, resizable: false},
            {title: "REF.", field: "ref", hozAlign: "left"},
            {title: "USER NO", field: "user", hozAlign: "left", width: 150},
            {title: "DATE", field: "date", hozAlign: "left", width: 110},
            {title: "BANK", field: "bank", hozAlign: "left", width: 200},
            {title: "CURR", field: "curr", hozAlign: "center", width: 70},
            {title: "RATE", field: "rate", hozAlign: "right", formatter: moneyFormatter, width: 90},
            {title: "CASH", field: "cash", hozAlign: "right", formatter: moneyFormatter, width: 140, bottomCalc: "sum", bottomCalcFormatter: moneyFormatter},
            {title: "GIRO", field: "giro", hozAlign: "right", formatter: moneyFormatter, width: 140, bottomCalc: "sum", bottomCalcFormatter: moneyFormatter},
            {title: "LINK", field: "link", hozAlign: "center", width: 70},
            {title: "NOTE", field: "note", hozAlign: "left", flex: 1}
        ],
    });

    // ---------------------------------------------
    // DRAG AND DROP GROUPING LOGIC
    // ---------------------------------------------
    const dropZone = document.getElementById("groupDropZone");
    let activeGroups = [];

    // Allow dropping
    dropZone.addEventListener("dragover", function(e) {
        e.preventDefault();
        dropZone.style.background = "#e2e8f0";
    });

    dropZone.addEventListener("dragleave", function(e) {
        dropZone.style.background = "transparent";
    });

    dropZone.addEventListener("drop", function(e) {
        e.preventDefault();
        dropZone.style.background = "transparent";
        
        // Tabulator adds data-field attribute to column headers natively
        let fieldName = e.dataTransfer.getData("text/plain");
        
        if (fieldName && !activeGroups.includes(fieldName) && fieldName !== "chk") {
            // Remove placeholder text
            if(activeGroups.length === 0) dropZone.innerHTML = '';
            
            activeGroups.push(fieldName);
            table.setGroupBy(activeGroups);
            
            // Add Chip UI visually
            let chip = document.createElement("div");
            chip.className = "group-chip";
            chip.innerHTML = fieldName.toUpperCase() + ` <span style="font-weight:bold; margin-left:4px;" onclick="removeGroup('${fieldName}')">x</span>`;
            chip.id = "group-chip-" + fieldName;
            dropZone.appendChild(chip);
        }
    });

    // Hook into tabulator columns to make them draggable
    table.on("tableBuilt", function() {
        let headers = document.querySelectorAll('.tabulator-col');
        headers.forEach(h => {
            h.setAttribute('draggable', true);
            h.addEventListener('dragstart', function(e) {
                let field = h.getAttribute('tabulator-field');
                e.dataTransfer.setData("text/plain", field);
            });
        });
    });

    window.removeGroup = function(field) {
        activeGroups = activeGroups.filter(g => g !== field);
        let chip = document.getElementById("group-chip-" + field);
        if(chip) chip.remove();
        
        if(activeGroups.length === 0) {
            table.setGroupBy(false);
            dropZone.innerHTML = '<span style="color:#94a3b8;">Drag a column header here to group by that column</span>';
        } else {
            table.setGroupBy(activeGroups);
        }
    };
    // ---------------------------------------------
    
    // Listen to data processed to update our custom footer
    table.on("renderComplete", function(){
        updatePaginationInfo();
        
        let calcData = table.getCalcResults();
        if(calcData.bottom) {
            document.getElementById('totalCash').innerText = parseFloat(calcData.bottom.cash).toLocaleString('en-US', {minimumFractionDigits:2});
            document.getElementById('totalGiro').innerText = parseFloat(calcData.bottom.giro).toLocaleString('en-US', {minimumFractionDigits:2});
        }
    });

    // Quick Search
    document.getElementById("quickSearch").addEventListener("keyup", function(){
        var term = this.value.toLowerCase();
        table.setFilter(function(data){
            return String(data.ref).toLowerCase().includes(term) || 
                   String(data.user).toLowerCase().includes(term) ||
                   String(data.bank).toLowerCase().includes(term) ||
                   String(data.note).toLowerCase().includes(term);
        });
    });

    // Navigation (Double Click -> Open Form Detail style)
    table.on("rowDblClick", function(e, row){
        let id = row.getData().id;
        // The standard action per user is open detail.
        alert('Opening Detail View for Transaction ID: ' + id);
        // window.location.href = "{{ url('/finance/cash-in') }}/" + id;
    });

    // Custom Pagination Listeners
    function updatePaginationInfo() {
        let maxPages = table.getPageMax();
        let current = table.getPage();
        let totalRecords = table.getDataCount("active");
        
        if (totalRecords === 0) {
            document.getElementById('pInfo').innerText = `Record 0 of 0`;
        } else {
            let start = ((current - 1) * 50) + 1;
            let end = current * 50;
            if(end > totalRecords) end = totalRecords;
            document.getElementById('pInfo').innerText = `Record ${start} to ${end} of ${totalRecords}`;
        }
    }

    document.getElementById('pFirst').addEventListener("click", () => table.setPage(1));
    document.getElementById('pPrev').addEventListener("click", () => table.previousPage());
    document.getElementById('pNext').addEventListener("click", () => table.nextPage());
    document.getElementById('pLast').addEventListener("click", () => table.setPage(table.getPageMax()));

    table.on("pageLoaded", function(pageno){
        updatePaginationInfo();
    });
</script>
@endpush
