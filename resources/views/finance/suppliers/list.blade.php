@extends('layouts.app')
@section('title', 'Supplier List – Finance')

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
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(30, 58, 138, 0.05), 0 1px 3px rgba(0, 0, 0, 0.03);
            transition: box-shadow 0.35s ease;
        }

        .g-card:hover {
            box-shadow: 0 10px 38px rgba(30, 58, 138, 0.08);
        }

        /* ── Action Buttons ── */
        .btn-save {
            background: linear-gradient(135deg, var(--blue) 0%, var(--acc) 100%);
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 8px; /* matching ERP feel */
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            transition: all 0.25s;
            cursor: pointer;
            white-space: nowrap;
            text-decoration: none;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
            color: #fff;
        }

        /* ── Action Outline Button ── */
        .btn-outline {
            background: #fff;
            color: #374151;
            border: 1px solid #e2e8f0;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.25s;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-outline:hover {
            background: #F8FAFC;
        }

        /* ── Form Inputs (Filter) ── */
        .f-input,
        .f-select {
            width: 100%;
            padding: 8px 12px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            color: #1e293b;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .f-input:focus,
        .f-select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .f-input.search-bar {
            padding-left: 36px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        /* ── ERP Style Table Adjustments ── */
        .erp-table-container {
            padding: 4px;
        }
        .erp-table {
            font-size: 12px !important;
            font-family: 'Inter', 'Poppins', sans-serif;
            border: none;
            background-color: transparent;
        }
        .tabulator-header {
            background-color: #f8fafc !important;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            border-bottom: 2px solid #e2e8f0 !important;
        }
        .tabulator-col {
            background-color: transparent !important;
            font-weight: 600 !important;
            color: #475569 !important;
            border-right: none !important;
        }
        .tabulator-col-title {
            padding: 8px 12px !important;
        }
        .tabulator-row {
            border-bottom: 1px solid #f1f5f9;
            min-height: 38px !important;
        }
        /* Zebra Optional but requested default transparent/white and highlight */
        .tabulator-row:nth-child(even) {
            background-color: #fafbfc !important;
        }
        .tabulator-row:hover {
            background-color: #eff6ff !important;
            cursor: pointer;
        }
        .val-positive { color: #ef4444; font-weight: 700; } 
        .val-zero { color: #64748b; }
        .audit-pass { 
            background: #dcfce7; color: #16a34a; font-weight: 700; 
            padding: 3px 8px; border-radius: 4px; font-size: 10.5px;
            display: inline-block; text-align: center;
        }
        .audit-other { 
            background: #f1f5f9; color: #475569; font-weight: 600; 
            padding: 3px 8px; border-radius: 4px; font-size: 10.5px;
            display: inline-block; text-align: center;
        }

        /* Nested Details */
        .row-details {
            padding: 14px 28px;
            background: #f8fafc;
            border-top: 1px dashed #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
            font-size: 11.5px;
            color: #334155;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.02);
            animation: slideDown 0.2s ease-out;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Expand Button */
        .btn-expand {
            color: #64748b;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 9px;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .btn-expand:hover {
            background: #eff6ff;
            color: var(--blue);
            border-color: #bfdbfe;
        }
        .btn-expand.active {
            background: #eff6ff;
            color: var(--blue);
            border-color: #bfdbfe;
        }

    </style>
    <link href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator.min.css" rel="stylesheet">
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
                <span style="color:var(--blue); font-weight:600;">Records List</span>
            </div>

            {{-- ── PAGE HEADER + ACTIONS ── --}}
            <div style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; justify-content:space-between; margin-bottom:20px;">
                <div>
                    <h1 style="font-size:28px; font-weight:800; margin:0 0 4px;" class="grad-text">Supplier List</h1>
                    <p style="font-size:13px; color:#64748b; margin:0;">Manage and monitor all supplier data</p>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                    <!-- Top Global Search Bar -->
                    <div style="position:relative; width: 240px;">
                        <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="globalSearchHeader" placeholder="Search supplier..." class="f-input search-bar" style="background:#fff;">
                    </div>
                    <a href="{{ route('finance.suppliers.detail') }}" class="btn-save">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Add Supplier
                    </a>
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

            {{-- ── FILTER BAR (MODERN) ── --}}
            <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-bottom:16px;">
                <div style="min-width: 140px;">
                    <select id="filterCategory" class="f-select">
                        <option value="">All Categories</option>
                        <option value="Raw Materials">Raw Materials</option>
                        <option value="Services">Services</option>
                        <option value="Packaging">Packaging</option>
                        <option value="General">General</option>
                    </select>
                </div>
                <div style="min-width: 140px;">
                    <input type="text" id="filterCity" placeholder="City..." class="f-input">
                </div>
                <div style="min-width: 140px;">
                    <select id="filterStatus" class="f-select">
                        <option value="">All Status/Audit</option>
                        <option value="PASS">PASS</option>
                        <option value="WAITING">WAITING</option>
                    </select>
                </div>
                <button type="button" id="btnResetFilters" class="btn-outline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><polyline points="3 3 3 8 8 8"></polyline></svg>
                    Reset
                </button>
            </div>

            {{-- ── TABLE CONTAINER ── --}}
            <div class="g-card erp-table-container">
                <div id="supplier-table" class="erp-table"></div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Custom Formatter for Currency
    const moneyFormatter = function(cell, formatterParams) {
        let val = parseFloat(cell.getValue());
        if(isNaN(val)) return "0.00";
        let formatted = val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        if (formatterParams.balance) {
            if (val > 0) return `<span class="val-positive">${formatted}</span>`;
            return `<span class="val-zero">${formatted}</span>`;
        }
        return formatted;
    };

    // Custom Formatter for Audit Status
    const auditFormatter = function(cell) {
        let val = cell.getValue();
        if (val === 'PASS') return `<span class="audit-pass">${val}</span>`;
        return `<span class="audit-other">${val || 'WAITING'}</span>`;
    };

    // Expand Icon Formatter
    const expandFormatter = function(cell, formatterParams, onRendered) {
        return "<button class='btn-expand'>▶</button>";
    };

    // Initialize Tabulator
    const table = new Tabulator("#supplier-table", {
        ajaxURL: "{{ route('finance.suppliers.data') }}", // API endpoint
        pagination: true,
        paginationMode: "remote", // Server side pagination
        sortMode: "remote", // Server side sorting
        filterMode: "remote", // Server side filtering
        paginationSize: 50,
        layout: "fitColumns", // fit columns to width of table
        columnHeaderVertAlign: "bottom",
        selectableRows: 1, // Allow row selection
        placeholder: "<div style='padding:20px; color:#94a3b8; font-size:13px;'>No Data Records.</div>",
        
        columns: [
            {formatter: expandFormatter, width: 40, hozAlign:"center", headerSort:false, cellClick:function(e, cell){
                e.stopPropagation(); // prevent row click redirect
                
                let row = cell.getRow();
                let detailsEl = row.getElement().querySelector('.row-details');
                let btn = cell.getElement().querySelector('button');
                
                if(detailsEl) {
                    detailsEl.remove(); // toggle off
                    btn.textContent = "▶";
                    btn.classList.remove('active');
                } else {
                    let d = row.getData();
                    let el = document.createElement("div");
                    el.classList.add("row-details");
                    
                    // Email might not exist, showing placeholder or city if necessary
                    let emailData = d.email ? d.email : '-';
                    
                    el.innerHTML = `
                        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px;">
                            <div>
                                <div style="font-size:10px; text-transform:uppercase; font-weight:700; color:#94a3b8; margin-bottom:4px; letter-spacing:0.05em;">Bank Information</div>
                                <div style="color:#334155;"><strong>Bank Name:</strong> ${d.bank_name || '-'}</div>
                                <div style="color:#334155;"><strong>Account No:</strong> ${d.account_no || '-'}</div>
                            </div>
                            <div>
                                <div style="font-size:10px; text-transform:uppercase; font-weight:700; color:#94a3b8; margin-bottom:4px; letter-spacing:0.05em;">Credit Range</div>
                                <div style="color:#334155;"><strong>Limit:</strong> Rp ${parseFloat(d.credit_limit || 0).toLocaleString('en-US', {minimumFractionDigits:2})}</div>
                            </div>
                            <div>
                                <div style="font-size:10px; text-transform:uppercase; font-weight:700; color:#94a3b8; margin-bottom:4px; letter-spacing:0.05em;">Contact & City</div>
                                <div style="color:#334155;"><strong>Email:</strong> ${emailData}</div>
                                <div style="color:#334155;"><strong>City:</strong> ${d.city || '-'}</div>
                            </div>
                        </div>
                    `;
                    row.getElement().appendChild(el);
                    btn.textContent = "▼";
                    btn.classList.add('active');
                }
            }},
            {title:"CODE", field:"code", width:90, sorter:"string", headerTooltip:"Supplier Code", tooltip:true},
            {title:"SUPPLIER NAME", field:"name", minWidth:200, sorter:"string", tooltip:true},
            {title:"CONTACT", field:"contact_person", width:140, sorter:"string", tooltip:true},
            {title:"CITY", field:"city", width:130, sorter:"string", tooltip:true},
            {title:"CATEGORY", field:"category", width:120, sorter:"string"},
            {title:"PHONE", field:"phone", width:130, sorter:"string"},
            {title:"BALANCE", field:"balance", width:140, hozAlign:"right", formatter:moneyFormatter, formatterParams:{balance:true}, sorter:"number"},
            {title:"AUDIT", field:"audit", width:100, hozAlign:"center", formatter:auditFormatter, sorter:"string"}
        ],
    });

    // Row Click Redirect Mode
    table.on("rowClick", function(e, row){
        let id = row.getData().id;
        window.location.href = "{{ url('/finance/suppliers') }}/" + id;
    });

    // Filtering Functions
    function applyFilters() {
        let filters = [];
        
        let search = document.getElementById("globalSearchHeader").value;
        if(search) {
            filters.push({field: "search", type: "like", value: search});
        }
        
        let cat = document.getElementById("filterCategory").value;
        if(cat) {
            filters.push({field: "category", type: "=", value: cat});
        }
        
        let city = document.getElementById("filterCity").value;
        if(city) {
            filters.push({field: "city", type: "like", value: city});
        }
        
        let status = document.getElementById("filterStatus").value;
        if(status) {
            filters.push({field: "audit", type: "=", value: status});
        }
        
        table.setFilter(filters);
    }

    // Global Search Throttle
    let searchTimeout;
    document.getElementById("globalSearchHeader").addEventListener("input", function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 400); 
    });

    // Filter Listeners
    document.getElementById("filterCategory").addEventListener("change", applyFilters);
    document.getElementById("filterCity").addEventListener("input", function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 400);
    });
    document.getElementById("filterStatus").addEventListener("change", applyFilters);

    // Reset Filters
    document.getElementById("btnResetFilters").addEventListener("click", function() {
        document.getElementById("globalSearchHeader").value = "";
        document.getElementById("filterCategory").value = "";
        document.getElementById("filterCity").value = "";
        document.getElementById("filterStatus").value = "";
        table.clearFilter();
    });

});
</script>
@endpush
