@extends('layouts.app')
@section('title', 'Sales Invoice - Finance')

@push('styles')
<style>
    :root {
        --b-pri: #1E3A8A;
        --b-acc: #3b82f6; 
        --grid-border: #cbd5e1;
        --grid-bg: #f8fafc;
        --grid-focus: rgba(59, 130, 246, 0.15);
    }
    
    /* Toolbar */
    .top-toolbar {
        display: flex; gap: 8px; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;
    }
    .btn-tool {
        background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 6px 14px;
        font-size: 12px; font-weight: 600; color: #334155; cursor: pointer; transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .btn-tool:hover { background: #f1f5f9; border-color: #94a3b8; }
    .btn-save { background: var(--b-pri); color: #fff; border: none; }
    .btn-save:hover { background: var(--b-acc); color: #fff; }

    /* Layout */
    .erp-box {
        background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 16px; margin-bottom: 16px;
    }
    
    .form-grid {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;
    }
    .form-group {
        display: flex; flex-direction: column; gap: 4px;
    }
    .form-label {
        font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;
    }
    .erp-input {
        background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 6px 10px;
        font-size: 13px; outline: none; transition: 0.2s; width: 100%;
    }
    .erp-input:focus { border-color: var(--b-acc); box-shadow: 0 0 0 2px var(--grid-focus); }

    /* Journal Grid */
    .journal-container {
        border: 1px solid var(--grid-border); border-radius: 4px; overflow-x: auto;
    }
    .journal-table {
        width: 100%; border-collapse: collapse; font-size: 12px; font-family: 'Inter', sans-serif;
    }
    .journal-table th {
        background: #f1f5f9; border-bottom: 2px solid var(--grid-border); border-right: 1px solid var(--grid-border);
        padding: 8px; text-align: left; font-weight: 700; color: #334155; font-size: 11px; letter-spacing: 0.5px;
    }
    .journal-table td {
        border-bottom: 1px solid var(--grid-border); border-right: 1px solid var(--grid-border); padding: 0;
        position: relative;
    }
    
    .cell-input {
        width: 100%; height: 100%; padding: 8px; border: none; background: transparent;
        font-size: 13px; color: #0f172a; outline: none;
    }
    .cell-input:focus { background: var(--grid-focus); }
    .cell-numeric { text-align: right; font-variant-numeric: tabular-nums; font-weight: 600; }
    
    /* Footer Section */
    .foot-section {
        display: flex; justify-content: space-between; align-items: flex-start; margin-top: 16px; gap: 24px;
    }
    .note-box {
        flex: 1;
    }
    .summary-box {
        background: #f8fafc; border: 1px solid var(--grid-border); border-radius: 4px;
        padding: 16px; width: 320px; flex-shrink: 0;
    }
    .summary-row {
        display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: #475569;
    }
    .summary-total {
        display: flex; justify-content: space-between; margin-top: 12px; padding-top: 12px;
        border-top: 2px solid #e2e8f0; font-size: 16px; font-weight: 800; color: #1e3a8a;
    }

    .status-bar {
        background: #e2e8f0; color: #475569; font-size: 11px; padding: 8px 16px; margin-top: 24px;
        border-radius: 4px; display: flex; justify-content: space-between; font-weight: 600;
    }
</style>
@endpush

@section('content')
<div style="background:var(--grid-bg); min-height:calc(100vh - 62px); padding:24px;">
    <div style="max-width:1440px; margin:0 auto;">
        
        <form id="salesInvoiceForm" method="POST" action="{{ route('finance.sales-invoice.store') }}">
            @csrf
            
            <input type="hidden" name="details" id="hiddenDetails">

            <!-- ERP Tab Navigation -->
            <div style="display:flex; gap:2px; margin-bottom:16px;">
                <a href="{{ route('finance.sales-invoice.index') }}" style="padding:8px 16px; background:var(--b-pri); color:#fff; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                    Record Detail
                </a>
                <a href="{{ route('finance.sales-invoice.records-list') }}" style="padding:8px 16px; background:#e2e8f0; color:#475569; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                    Records List
                </a>
                <a href="{{ route('finance.sales-invoice.custom-group') }}" style="padding:8px 16px; background:#e2e8f0; color:#475569; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                    Custom Group
                </a>
                <a href="{{ route('finance.sales-invoice.detail-list') }}" style="padding:8px 16px; background:#e2e8f0; color:#475569; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                    Detail Invoice List
                </a>
            </div>

            <!-- Toolbar -->
            <div class="top-toolbar">
                <button type="submit" class="btn-tool btn-save">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Save
                </button>
                <button type="button" class="btn-tool" onclick="window.location.reload()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                    New
                </button>
                <div style="width: 1px; background:#cbd5e1; margin:0 8px;"></div>
                <button type="button" class="btn-tool" onclick="alert('Print PDF Feature Coming Soon')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    Print
                </button>
                <div style="flex:1;"></div>
                <button type="button" class="btn-tool">First</button>
                <button type="button" class="btn-tool">Prev</button>
                <button type="button" class="btn-tool">Next</button>
                <button type="button" class="btn-tool">Last</button>
            </div>

            @if(session('success'))
                <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:4px; margin-bottom:16px; font-size:13px; font-weight:600;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fee2e2; color:#991b1b; padding:12px 16px; border-radius:4px; margin-bottom:16px; font-size:13px; font-weight:600;">{{ session('error') }}</div>
            @endif

            <!-- Header -->
            <div class="erp-box form-grid">
                <div class="form-group">
                    <span class="form-label">Date *</span>
                    <input type="date" name="date" class="erp-input" required value="{{ date('Y-m-d') }}" tabindex="1">
                </div>
                <div class="form-group">
                    <span class="form-label">Due Date *</span>
                    <input type="date" name="due_date" class="erp-input" required value="{{ date('Y-m-d', strtotime('+30 days')) }}" tabindex="2">
                </div>
                <div class="form-group" style="justify-content: flex-end; padding-bottom: 4px;">
                    <label style="display:flex; align-items:center; gap:8px; font-size:12px; font-weight:700; color:#475569; cursor:pointer;">
                        <input type="checkbox" name="approved" value="1" tabindex="3">
                        APPROVED
                    </label>
                </div>
                <div class="form-group">
                    <span class="form-label">Currency</span>
                    <div style="display:flex; gap:8px;">
                        <select name="currency" class="erp-input" style="flex:1;" tabindex="4">
                            <option value="IDR">IDR</option>
                            <option value="USD">USD</option>
                        </select>
                        <input type="number" name="rate" class="erp-input" style="flex:2;" value="1" step="0.01" tabindex="5">
                    </div>
                </div>
                
                <!-- Row 2 -->
                <div class="form-group" style="grid-column: span 2;">
                    <span class="form-label">Customer *</span>
                    <input type="text" id="customerSearch" class="erp-input" list="customerList" placeholder="Search Customer..." autocomplete="off" tabindex="6" required>
                    <input type="hidden" name="customer_id" id="customerId" required>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <span class="form-label">Document References</span>
                    <input type="text" name="reference" class="erp-input" placeholder="Optional reference..." tabindex="7">
                </div>
            </div>

            <!-- Detail Grid -->
            <div class="journal-container">
                <table class="journal-table" id="journalTable">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align:center;">#</th>
                            <th style="width: 15%;">PRODUCT</th>
                            <th style="width: 25%;">PRODUCT NAME</th>
                            <th style="width: 8%; text-align:right;">QTY</th>
                            <th style="width: 12%; text-align:right;">PRICE</th>
                            <th style="width: 8%; text-align:right;">% TAX</th>
                            <th style="width: 8%; text-align:right;">PPH 23</th>
                            <th style="width: 12%; text-align:right;">AMOUNT</th>
                            <th style="width: 12%;">DESCRIPTION</th>
                            <th style="width: 40px; text-align:center;">ACT</th>
                        </tr>
                    </thead>
                    <tbody id="gridBody">
                        <!-- Rows dynamically rendered via JS -->
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="foot-section">
                <div class="note-box form-group">
                    <span class="form-label">Catatan Invoice</span>
                    <textarea name="note" class="erp-input" style="height: 120px; resize:none;" tabindex="100"></textarea>
                </div>
                
                <div class="summary-box">
                    <div class="summary-row">
                        <span>Amount</span>
                        <span id="displaySubtotal">0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>% PPN</span>
                        <span id="displayPpn">0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>PPH-23</span>
                        <span id="displayPph">0.00</span>
                    </div>
                    <div class="summary-total">
                        <span>Total</span>
                        <span id="displayTotal">0.00</span>
                    </div>
                </div>
            </div>

            <div class="status-bar">
                <span>Mode: INSERT</span>
                <span>BarStaticItem1 | Ready</span>
            </div>

        </form>
    </div>
</div>

<datalist id="customerList"></datalist>
<datalist id="productList"></datalist>

@endsection

@push('scripts')
<script>
    let dListCust = document.getElementById('customerList');
    let dListProd = document.getElementById('productList');
    let customersData = [];
    let productsData = [];

    // Fetch Customers
    fetch("{{ route('finance.sales-invoice.api.customers') }}")
        .then(res => res.json())
        .then(data => {
            customersData = data;
            data.forEach(c => {
                let opt = document.createElement('option');
                opt.value = c.name;
                dListCust.appendChild(opt);
            });
        });

    // Fetch Products
    fetch("{{ route('finance.sales-invoice.api.products') }}")
        .then(res => res.json())
        .then(data => {
            productsData = data;
            data.forEach(p => {
                let opt = document.createElement('option');
                opt.value = p.code;
                opt.text = p.name;
                dListProd.appendChild(opt);
            });
        });

    // Customer Selection Logic
    document.getElementById('customerSearch').addEventListener('input', function(e) {
        let val = e.target.value;
        let c = customersData.find(c => c.name === val);
        document.getElementById('customerId').value = c ? c.id : '';
    });

    // Grid Logic
    let rows = [ { id: Date.now(), product_id: '', product_code: '', product_name: '', qty: 1, price: 0, tax_percent: 0, pph23_percent: 0, amount: 0, desc: '' } ];

    const gridBody = document.getElementById('gridBody');
    const displaySubtotal = document.getElementById('displaySubtotal');
    const displayPpn = document.getElementById('displayPpn');
    const displayPph = document.getElementById('displayPph');
    const displayTotal = document.getElementById('displayTotal');
    const hiddenDetails = document.getElementById('hiddenDetails');
    const form = document.getElementById('salesInvoiceForm');

    function calculateRow(idx) {
        let qty = parseFloat(rows[idx].qty) || 0;
        let price = parseFloat(rows[idx].price) || 0;
        rows[idx].amount = qty * price;
    }

    function calculateGrandTotal() {
        let subtotal = 0;
        let totalPpn = 0;
        let totalPph = 0;

        rows.forEach(r => {
            let amt = parseFloat(r.amount) || 0;
            subtotal += amt;
            totalPpn += amt * ((parseFloat(r.tax_percent) || 0) / 100);
            totalPph += amt * ((parseFloat(r.pph23_percent) || 0) / 100);
        });

        let total = subtotal + totalPpn - totalPph;

        displaySubtotal.innerText = subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        displayPpn.innerText = totalPpn.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        displayPph.innerText = totalPph.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        displayTotal.innerText = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function renderGrid() {
        gridBody.innerHTML = '';

        rows.forEach((r, idx) => {
            let tr = document.createElement('tr');
            
            tr.innerHTML = `
                <td style="text-align:center; color:#94a3b8; font-weight:600;">${idx+1}</td>
                <td>
                    <input type="text" class="cell-input field-product" data-idx="${idx}" value="${r.product_code}" list="productList" placeholder="Code..." autocomplete="off">
                </td>
                <td>
                    <input type="text" class="cell-input field-name" data-idx="${idx}" value="${r.product_name}" readonly style="background:#f8fafc; color:#64748b;">
                </td>
                <td>
                    <input type="number" class="cell-input cell-numeric field-qty" data-idx="${idx}" value="${r.qty}" min="0" step="0.01">
                </td>
                <td>
                    <input type="number" class="cell-input cell-numeric field-price" data-idx="${idx}" value="${r.price}" min="0" step="0.01">
                </td>
                <td>
                    <input type="number" class="cell-input cell-numeric field-tax" data-idx="${idx}" value="${r.tax_percent}" min="0" max="100" step="0.1">
                </td>
                <td>
                    <input type="number" class="cell-input cell-numeric field-pph" data-idx="${idx}" value="${r.pph23_percent}" min="0" max="100" step="0.1">
                </td>
                <td>
                    <input type="text" class="cell-input cell-numeric field-amount" data-idx="${idx}" value="${r.amount.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2})}" readonly tabindex="-1" style="background:#f8fafc; color:#1e3a8a;">
                </td>
                <td>
                    <input type="text" class="cell-input field-desc" data-idx="${idx}" value="${r.desc}" onkeydown="handleDescKey(event, ${idx})">
                </td>
                <td style="text-align:center;">
                    <button type="button" onclick="deleteRow(${idx})" style="background:none; border:none; color:#ef4444; font-weight:bold; cursor:pointer; font-size:16px;" tabindex="-1">×</button>
                </td>
            `;
            gridBody.appendChild(tr);
        });

        calculateGrandTotal();
        syncHidden();
    }

    gridBody.addEventListener('input', (e) => {
        if(e.target.classList.contains('cell-input')) {
            let idx = parseInt(e.target.getAttribute('data-idx'));
            let val = e.target.value;
            
            if(e.target.classList.contains('field-product')) {
                rows[idx].product_code = val;
                let prod = productsData.find(p => p.code === val);
                if (prod) {
                    rows[idx].product_name = prod.name;
                    rows[idx].product_id = prod.id;
                    rows[idx].price = parseFloat(prod.default_price) || 0;
                    calculateRow(idx);
                } else {
                    rows[idx].product_name = '';
                    rows[idx].product_id = '';
                }
                renderGrid(); // Full re-render to update dependent inputs correctly and keep focus robustly handled maybe? 
                // Wait, full render removes focus. We should handle it inline for input events carefully!
            }
        }
    });

    // Instead of full render on every input for qty/price, use specific event listener to avoid losing focus
    gridBody.addEventListener('change', (e) => {
        if(e.target.classList.contains('cell-input')) {
            let idx = parseInt(e.target.getAttribute('data-idx'));
            let val = e.target.value;

            if(e.target.classList.contains('field-qty')) {
                rows[idx].qty = val;
                calculateRow(idx);
                renderGrid();
            } else if(e.target.classList.contains('field-price')) {
                rows[idx].price = val;
                calculateRow(idx);
                renderGrid();
            } else if(e.target.classList.contains('field-tax')) {
                rows[idx].tax_percent = val;
                renderGrid();
            } else if(e.target.classList.contains('field-pph')) {
                rows[idx].pph23_percent = val;
                renderGrid();
            } else if(e.target.classList.contains('field-desc')) {
                rows[idx].desc = val;
                syncHidden();
            }
        }
    });

    // We do need input listener for desc so it binds without losing
    gridBody.addEventListener('input', (e) => {
        if(e.target.classList.contains('field-desc')) {
            let idx = parseInt(e.target.getAttribute('data-idx'));
            rows[idx].desc = e.target.value;
            syncHidden();
        }
    });


    function syncHidden() {
        hiddenDetails.value = JSON.stringify(rows.map(r => ({
            product_id: r.product_id || null,
            qty: parseFloat(r.qty) || 0,
            price: parseFloat(r.price) || 0,
            tax_percent: parseFloat(r.tax_percent) || 0,
            pph23_percent: parseFloat(r.pph23_percent) || 0,
            amount: parseFloat(r.amount) || 0,
            description: r.desc
        })));
    }

    function addRow() {
        rows.push({ id: Date.now(), product_id: '', product_code: '', product_name: '', qty: 1, price: 0, tax_percent: 0, pph23_percent: 0, amount: 0, desc: '' });
        renderGrid();
        let trs = gridBody.querySelectorAll('tr');
        let inputs = trs[trs.length - 1].querySelectorAll('.cell-input');
        if(inputs.length > 0) inputs[1].focus(); // Focus on product code
    }

    function deleteRow(idx) {
        if(rows.length > 1) {
            rows.splice(idx, 1);
            renderGrid();
        }
    }

    window.handleDescKey = function(e, idx) {
        if(e.key === 'Enter') {
            e.preventDefault();
            addRow();
        }
    }

    form.addEventListener('submit', (e) => {
        syncHidden();
        if(rows.length === 0 || !rows[0].product_id) {
            e.preventDefault();
            alert('Please add at least one valid product line.');
        }
    });

    renderGrid();
</script>
@endpush
