@extends('layouts.app')
@section('title', 'Cash In - Finance')

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
        background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 166px; margin-bottom: 16px;
    }
    /* Fixed accidental 166px padding from previous edit attempt */
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
        font-size: 13px; outline: none; transition: 0.2s;
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
        display: flex; justify-content: space-between; align-items: flex-end; margin-top: 16px;
    }
    .note-box {
        width: 50%;
    }
    .total-box {
        background: #f8fafc; border: 1px solid var(--grid-border); border-radius: 4px;
        padding: 12px 24px; text-align: right; min-width: 250px;
    }

    .status-bar {
        background: #e2e8f0; color: #475569; font-size: 11px; padding: 8px 16px; margin-top: 24px;
        border-radius: 4px; display: flex; justify-content: space-between; font-weight: 600;
    }
</style>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div style="background:var(--grid-bg); min-height:calc(100vh - 62px); padding:24px;">
    <div style="max-width:1440px; margin:0 auto;">
        
        <form id="cashInForm" method="POST" action="{{ route('finance.cash-in.store') }}">
            @csrf
            
            <!-- Hidden details -->
            <input type="hidden" name="details" id="hiddenDetails">

            <!-- ERP Tab Navigation -->
            <div style="display:flex; gap:2px; margin-bottom:16px;">
                <a href="{{ route('finance.cash-in.index') }}" style="padding:8px 16px; background:var(--b-pri); color:#fff; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                    Record Detail
                </a>
                <a href="{{ route('finance.cash-in.records-list') }}" style="padding:8px 16px; background:#e2e8f0; color:#475569; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                    Record List
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
                    <span class="form-label">Ref #</span>
                    <input type="text" name="reference" class="erp-input" placeholder="AUTO ENTRY" tabindex="2">
                </div>
                <div class="form-group">
                    <span class="form-label">Bank / Cash Account</span>
                    <select name="bank_account_id" class="erp-input" tabindex="3">
                        <option value="">- Select Bank/Cash -</option>
                        <option value="1">1001-00 - Kas Besar</option>
                        <option value="2">1011-00 - Bank BCA</option>
                    </select>
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
            </div>

            <!-- Detail Grid -->
            <div class="journal-container">
                <table class="journal-table" id="journalTable">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align:center;">#</th>
                            <th style="width: 20%;">ACCOUNT</th>
                            <th style="width: 25%;">ACCOUNT DESC</th>
                            <th style="width: 10%; text-align:center;">DEPT</th>
                            <th style="width: 10%; text-align:center;">COST</th>
                            <th style="width: 15%; text-align:right;">AMOUNT</th>
                            <th style="width: 20%;">DESCRIPTION</th>
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
                    <span class="form-label">Note / Remark</span>
                    <textarea name="note" class="erp-input" style="height: 60px; resize:none;" tabindex="100"></textarea>
                </div>
                <div class="total-box">
                    <div style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Total Amount</div>
                    <div id="displayTotal" style="font-size:28px; font-weight:800; color:#1e3a8a;">0.00</div>
                </div>
            </div>

            <div class="status-bar">
                <span>Mode: INSERT</span>
                <span>BarStaticItem1 | Ready</span>
            </div>

        </form>
    </div>
</div>

<!-- Datalists for Mock Data Navigation -->
<datalist id="accountList"></datalist>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    let dList = document.getElementById('accountList');
    let accountsData = [];

    // Fetch Accounts for lookup
    fetch("{{ route('finance.cash-in.api.accounts') }}")
        .then(res => res.json())
        .then(data => {
            accountsData = data;
            data.forEach(acc => {
                let opt = document.createElement('option');
                opt.value = acc.code;
                opt.text = acc.name;
                dList.appendChild(opt);
            });
        });

    // Journal Grid Handler
    let rows = [ { id: Date.now(), account_id: '', account_code: '', account_name: '', dept: '', cost: '', amount: 0, desc: '' } ];

    const gridBody = document.getElementById('gridBody');
    const displayTotal = document.getElementById('displayTotal');
    const hiddenDetails = document.getElementById('hiddenDetails');
    const form = document.getElementById('cashInForm');

    function renderGrid() {
        gridBody.innerHTML = '';
        let total = 0;

        rows.forEach((r, idx) => {
            let tr = document.createElement('tr');
            total += parseFloat(r.amount) || 0;
            
            tr.innerHTML = `
                <td style="text-align:center; color:#94a3b8; font-weight:600;">${idx+1}</td>
                <td>
                    <input type="text" class="cell-input field-account" data-idx="${idx}" value="${r.account_code}" list="accountList" placeholder="Code..." autocomplete="off">
                </td>
                <td>
                    <input type="text" class="cell-input field-name" data-idx="${idx}" value="${r.account_name}" readonly style="background:#f8fafc; color:#64748b;">
                </td>
                <td>
                    <input type="text" class="cell-input field-dept" data-idx="${idx}" value="${r.dept}" style="text-align:center;">
                </td>
                <td>
                    <input type="text" class="cell-input field-cost" data-idx="${idx}" value="${r.cost}" style="text-align:center;">
                </td>
                <td>
                    <input type="number" class="cell-input cell-numeric field-amount" data-idx="${idx}" value="${r.amount || ''}" step="0.01">
                </td>
                <td>
                    <input type="text" class="cell-input field-desc" data-idx="${idx}" value="${r.desc}" onkeydown="handleDescKey(event, ${idx})">
                </td>
                <td style="text-align:center;">
                    <button type="button" onclick="deleteRow(${idx})" style="background:none; border:none; color:#ef4444; font-weight:bold; cursor:pointer;" tabindex="-1">×</button>
                </td>
            `;
            gridBody.appendChild(tr);
        });

        displayTotal.innerText = total.toLocaleString('en-US', {minimumFractionDigits: 2});
        
        syncHidden();
    }

    function getAccountIdByCode(code) {
        let acc = accountsData.find(a => a.code === code);
        return acc ? acc.id : null;
    }

    gridBody.addEventListener('input', (e) => {
        if(e.target.classList.contains('cell-input')) {
            let idx = e.target.getAttribute('data-idx');
            let val = e.target.value;
            
            if(e.target.classList.contains('field-account')) {
                rows[idx].account_code = val;
                let acc = accountsData.find(a => a.code === val);
                if (acc) {
                    rows[idx].account_name = acc.name;
                    rows[idx].account_id = acc.id;
                } else {
                    rows[idx].account_name = '';
                    rows[idx].account_id = '';
                }
                
                let nameInput = gridBody.querySelector(`.field-name[data-idx="${idx}"]`);
                if(nameInput) nameInput.value = rows[idx].account_name;
            } else if(e.target.classList.contains('field-dept')) {
                rows[idx].dept = val;
            } else if(e.target.classList.contains('field-cost')) {
                rows[idx].cost = val;
            } else if(e.target.classList.contains('field-amount')) {
                rows[idx].amount = val;
                let total = rows.reduce((s, r) => s + (parseFloat(r.amount) || 0), 0);
                displayTotal.innerText = total.toLocaleString('en-US', {minimumFractionDigits: 2});
            } else if(e.target.classList.contains('field-desc')) {
                rows[idx].desc = val;
            }
            
            syncHidden();
        }
    });

    function syncHidden() {
        hiddenDetails.value = JSON.stringify(rows.map(r => ({
            account_id: getAccountIdByCode(r.account_code),
            dept_id: null,
            cost_id: null,
            amount: parseFloat(r.amount) || 0,
            description: r.desc
        })));
    }

    function addRow() {
        rows.push({ id: Date.now(), account_id: '', account_code: '', account_name: '', dept: '', cost: '', amount: 0, desc: '' });
        renderGrid();
        let trs = gridBody.querySelectorAll('tr');
        let inputs = trs[trs.length - 1].querySelectorAll('.cell-input');
        if(inputs.length > 0) inputs[0].focus();
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
    });

    renderGrid();
</script>
@endpush
