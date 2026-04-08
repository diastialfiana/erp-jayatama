@extends('layouts.app')
@section('title', 'Advance Detail - Finance')

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
    
    .form-2col {
        display: grid; grid-template-columns: 1fr 1fr; gap: 32px;
    }
    .form-line {
        display: flex; align-items: center; margin-bottom: 8px;
    }
    .form-label {
        font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; width: 140px; flex-shrink: 0;
    }
    .erp-input {
        background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px;
        font-size: 12px; outline: none; transition: 0.2s; flex: 1; width: 100%;
    }
    .erp-input:focus { border-color: var(--b-acc); box-shadow: 0 0 0 2px var(--grid-focus); }
    .erp-readonly { background: #f1f5f9; cursor: not-allowed; font-weight: 700; color: #1e3a8a; }

    /* Journal Grid */
    .journal-container {
        border: 1px solid var(--grid-border); border-radius: 4px; overflow-x: auto; margin-bottom: 16px;
    }
    .journal-table {
        width: 100%; border-collapse: collapse; font-size: 12px; font-family: 'Inter', sans-serif;
    }
    .journal-table th {
        background: #f1f5f9; border-bottom: 1px solid var(--grid-border); border-right: 1px solid var(--grid-border);
        padding: 6px; text-align: left; font-weight: 700; color: #334155; font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase;
    }
    .journal-table td {
        border-bottom: 1px solid var(--grid-border); border-right: 1px solid var(--grid-border); padding: 0; position: relative;
    }
    
    .cell-input {
        width: 100%; height: 100%; padding: 6px 8px; border: none; background: transparent;
        font-size: 12px; color: #0f172a; outline: none;
    }
    .cell-input:focus { background: var(--grid-focus); }
    .cell-numeric { text-align: right; font-variant-numeric: tabular-nums; font-weight: 600; }
    .cell-readonly { background: #f8fafc; color: #64748b; }
    
    /* Highlight Alert */
    .highlight-alert {
        background: #fef2f2 !important;
        color: #b91c1c !important;
    }

    /* Footer Section */
    .foot-section {
        display: flex; justify-content: space-between; align-items: flex-end; margin-top: 16px;
    }
    .note-box {
        width: 50%; display: flex; flex-direction: column; gap: 4px;
    }
    .total-box {
        background: #f8fafc; border: 1px solid var(--grid-border); border-radius: 4px;
        padding: 12px 24px; text-align: right; min-width: 250px;
    }

    /* Stat Box Header */
    .stats-flex {
        display: flex; justify-content: space-between; gap: 16px; margin-bottom: 16px; width: 100%;
    }
    .stat-card {
        background: #fff; border: 1px solid #e2e8f0; padding: 12px 16px; border-radius: 4px; flex: 1; text-align:right; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .stat-card-title {
        font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 4px;
    }
    .stat-card-val {
        font-size: 16px; font-weight: 800; color: #1e3a8a;
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
        
        <form id="advanceForm" method="POST" action="{{ route('finance.advance-report.store') }}">
            @csrf
            <input type="hidden" name="advance_data" id="hiddenAdvance">
            <input type="hidden" name="expenses_data" id="hiddenExpenses">

            <!-- ERP Tab Navigation -->
            <div style="display:flex; gap:2px; margin-bottom:16px;">
                <a href="{{ route('finance.advance-report.index') }}" style="padding:8px 16px; background:var(--b-pri); color:#fff; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                    Advance Detail
                </a>
                <a href="{{ route('finance.advance-report.recordList') ?? '#' }}" style="padding:8px 16px; background:#e2e8f0; color:#475569; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                    Advance List
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

            <!-- Header Form (2 Columns) -->
            <div class="erp-box form-2col">
                <!-- Kiri -->
                <div>
                    <div class="form-line">
                        <span class="form-label">Date *</span>
                        <input type="date" name="date" id="headerDate" class="erp-input" required value="{{ date('Y-m-d') }}" tabindex="1">
                    </div>
                    <div class="form-line">
                        <span class="form-label">Currency</span>
                        <select name="currency" class="erp-input" tabindex="2">
                            <option value="IDR">IDR</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <div class="form-line">
                        <span class="form-label">Employees Name *</span>
                        <select name="employee_id" class="erp-input" required tabindex="3">
                            <option value="">- Select Employee -</option>
                            @foreach($employees ?? [] as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-line">
                        <span class="form-label">Cash / Bank *</span>
                        <select name="bank_account_id" class="erp-input" required tabindex="4">
                            <option value="">- Select Source Bank -</option>
                            @foreach($banks ?? [] as $b)
                                <option value="{{ $b->id }}">{{ $b->code }} - {{ $b->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Kanan -->
                <div>
                    <div class="form-line">
                        <span class="form-label">Ref #</span>
                        <input type="text" name="reference" id="headerRef" class="erp-input" placeholder="AUTO ENTRY" tabindex="5">
                    </div>
                    <div class="form-line">
                        <span class="form-label">Rate</span>
                        <input type="number" name="rate" class="erp-input" value="1" step="0.01" tabindex="6">
                    </div>
                </div>
            </div>

            <!-- Dashboard Style KPI -->
            <div class="stats-flex">
                <div class="stat-card">
                    <div class="stat-card-title">Total Advance</div>
                    <div id="dispAdv" class="stat-card-val">0.00</div>
                </div>
                <div class="stat-card" id="cardExp">
                    <div class="stat-card-title" id="lblExp">Total Expenses</div>
                    <div id="dispExp" class="stat-card-val">0.00</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-title">Total Cashback</div>
                    <div id="dispBack" class="stat-card-val" style="color:#059669;">0.00</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-title">Total Cash Less</div>
                    <div id="dispLess" class="stat-card-val" style="color:#dc2626;">0.00</div>
                </div>
            </div>

            <!-- GRID 1: Advance Summary -->
            <div style="font-size:12px; font-weight:700; color:#1e3a8a; margin-bottom:8px; text-transform:uppercase;">Advance Summary</div>
            <div class="journal-container" style="overflow:visible;">
                <table class="journal-table" id="advanceTable">
                    <thead>
                        <tr>
                            <th style="width: 15%;">ADVANCE NUMBER</th>
                            <th style="width: 15%;">DATE</th>
                            <th style="width: 15%; text-align:right;">ADVANCE</th>
                            <th style="width: 15%; text-align:right;">EXPENSES</th>
                            <th style="width: 15%; text-align:right;">CASHBACK</th>
                            <th style="width: 10%; text-align:right;">CASH LESS</th>
                            <th style="width: 15%; text-align:right;">[TOTAL]</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" id="advNum" class="cell-input cell-readonly" readonly></td>
                            <td><input type="text" id="advDate" class="cell-input cell-readonly" readonly></td>
                            <td><input type="number" id="advAdvance" class="cell-input cell-numeric" value="0.00" step="0.01" tabindex="7"></td>
                            <td><input type="text" id="advExpenses" class="cell-input cell-numeric cell-readonly" value="0.00" readonly></td>
                            <td><input type="text" id="advCashback" class="cell-input cell-numeric cell-readonly" value="0.00" readonly style="color:#059669;"></td>
                            <td><input type="text" id="advCashLess" class="cell-input cell-numeric cell-readonly" value="0.00" readonly style="color:#dc2626;"></td>
                            <td><input type="text" id="advTotal" class="cell-input cell-numeric cell-readonly" value="0.00" readonly></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- GRID 2: Account / Expenses -->
            <div style="font-size:12px; font-weight:700; color:#1e3a8a; margin-bottom:8px; margin-top:24px; text-transform:uppercase;">Account / Expenses Details</div>
            <div class="journal-container">
                <table class="journal-table" id="expenseTable">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align:center;">#</th>
                            <th style="width: 15%;">ACCOUNT</th>
                            <th style="width: 30%;">ACCOUNT DESC.</th>
                            <th style="width: 10%; text-align:center;">DEPT.</th>
                            <th style="width: 10%; text-align:center;">COST</th>
                            <th style="width: 15%; text-align:right;">AMOUNT</th>
                            <th style="width: 15%;">DESCRIPTION</th>
                            <th style="width: 40px; text-align:center;">ACT</th>
                        </tr>
                    </thead>
                    <tbody id="gridBody">
                        <!-- Rows rendered via JS -->
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="foot-section">
                <div class="note-box">
                    <span class="form-label" style="width:100%;">Remarks / Note</span>
                    <textarea name="note" class="erp-input" style="height: 60px; resize:none;" tabindex="100"></textarea>
                </div>
                <div class="total-box">
                    <div style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Total Expense</div>
                    <div id="footerExpenseTotal" style="font-size:28px; font-weight:800; color:#1e3a8a;">0.00</div>
                </div>
            </div>

            <div class="status-bar">
                <span>Mode: INSERT</span>
                <span>Version: 1.0.0 | BarStaticItem1 | Idle</span>
            </div>

        </form>
    </div>
</div>

<datalist id="accountList"></datalist>

@endsection

@push('scripts')
<script>
    let aList = document.getElementById('accountList');
    let accountsData = [];

    // Fetch Accounts
    fetch("{{ route('finance.advance-report.api.accounts') }}")
        .then(res => res.json())
        .then(data => {
            accountsData = data;
            data.forEach(acc => {
                let opt = document.createElement('option');
                opt.value = acc.code;
                opt.text = acc.name;
                aList.appendChild(opt);
            });
        });

    let expenseRows = [ { id: Date.now(), account_code: '', account_desc: '', dept_id: '', cost_id: '', amount: 0, description: '' } ];

    const gridBody = document.getElementById('gridBody');
    const hiddenExpenses = document.getElementById('hiddenExpenses');
    const hiddenAdvance = document.getElementById('hiddenAdvance');

    // DOM Elements for Adv Summary
    const advAdvance = document.getElementById('advAdvance');
    const advExpenses = document.getElementById('advExpenses');
    const advCashback = document.getElementById('advCashback');
    const advCashLess = document.getElementById('advCashLess');
    const advTotal = document.getElementById('advTotal');

    // UI KPIs
    const dispAdv = document.getElementById('dispAdv');
    const dispExp = document.getElementById('dispExp');
    const dispBack = document.getElementById('dispBack');
    const dispLess = document.getElementById('dispLess');
    const footerExpenseTotal = document.getElementById('footerExpenseTotal');

    // Header Links
    document.getElementById('headerDate').addEventListener('input', (e) => { document.getElementById('advDate').value = e.target.value; });
    document.getElementById('headerRef').addEventListener('input', (e) => { document.getElementById('advNum').value = e.target.value; });

    // Grid 2 Renderer
    function renderGrid() {
        gridBody.innerHTML = '';
        let totalExpense = 0;

        expenseRows.forEach((r, idx) => {
            let tr = document.createElement('tr');
            totalExpense += parseFloat(r.amount) || 0;

            tr.innerHTML = `
                <td style="text-align:center; color:#94a3b8; font-weight:600;">${idx+1}</td>
                <td><input type="text" class="cell-input field-acc" data-idx="${idx}" value="${r.account_code}" list="accountList" placeholder="Account..."></td>
                <td><input type="text" class="cell-input field-desc cell-readonly" data-idx="${idx}" value="${r.account_desc}" readonly></td>
                <td><input type="text" class="cell-input field-dept" data-idx="${idx}" value="${r.dept_id}" style="text-align:center;"></td>
                <td><input type="text" class="cell-input field-cost" data-idx="${idx}" value="${r.cost_id}" style="text-align:center;"></td>
                <td><input type="number" class="cell-input cell-numeric field-amount" data-idx="${idx}" value="${r.amount || ''}" step="0.01"></td>
                <td><input type="text" class="cell-input field-desc-user" data-idx="${idx}" value="${r.description}" onkeydown="handleDescKey(event, ${idx})"></td>
                <td style="text-align:center;"><button type="button" onclick="deleteRow(${idx})" style="background:none; border:none; color:#ef4444; font-weight:bold; cursor:pointer;" tabindex="-1">×</button></td>
            `;
            gridBody.appendChild(tr);
        });

        calculateAdvanceLogic(totalExpense);
    }

    gridBody.addEventListener('input', (e) => {
        if(e.target.classList.contains('cell-input')) {
            let idx = e.target.getAttribute('data-idx');
            let val = e.target.value;
            
            if(e.target.classList.contains('field-acc')) {
                expenseRows[idx].account_code = val;
                let acc = accountsData.find(a => a.code === val);
                if (acc) {
                    expenseRows[idx].account_desc = acc.name;
                } else {
                    expenseRows[idx].account_desc = '';
                }
                renderGrid();
                let nextInp = gridBody.querySelector(`.field-dept[data-idx="${idx}"]`);
                if (nextInp) nextInp.focus();
                return;
            } else if(e.target.classList.contains('field-dept')) {
                expenseRows[idx].dept_id = val;
            } else if(e.target.classList.contains('field-cost')) {
                expenseRows[idx].cost_id = val;
            } else if(e.target.classList.contains('field-amount')) {
                expenseRows[idx].amount = val;
                let totalEx = expenseRows.reduce((s, r) => s + (parseFloat(r.amount) || 0), 0);
                calculateAdvanceLogic(totalEx);
            } else if(e.target.classList.contains('field-desc-user')) {
                expenseRows[idx].description = val;
            }
            
            syncHidden();
        }
    });

    advAdvance.addEventListener('input', () => {
        let totalEx = expenseRows.reduce((s, r) => s + (parseFloat(r.amount) || 0), 0);
        calculateAdvanceLogic(totalEx);
        syncHidden();
    });

    function calculateAdvanceLogic(totalEx) {
        let advNum = parseFloat(advAdvance.value) || 0;
        let expNum = parseFloat(totalEx) || 0;
        
        // Logical Matrix
        let cashback = 0;
        let cashLess = 0;

        if(advNum > expNum) {
            cashback = advNum - expNum;
        } else if (expNum > advNum) {
            cashLess = expNum - advNum;
        }

        let totFinal = advNum - expNum - cashback + cashLess;

        // Visual Display (Grid 1)
        advExpenses.value = expNum.toFixed(2);
        advCashback.value = cashback.toFixed(2);
        advCashLess.value = cashLess.toFixed(2);
        advTotal.value = totFinal.toFixed(2);

        if(expNum > advNum && advNum > 0) {
            advExpenses.classList.add('highlight-alert');
            document.getElementById('cardExp').classList.add('highlight-alert');
            document.getElementById('lblExp').style.color = '#b91c1c';
        } else {
            advExpenses.classList.remove('highlight-alert');
            document.getElementById('cardExp').classList.remove('highlight-alert');
            document.getElementById('lblExp').style.color = '#64748b';
        }

        // KPIs
        dispAdv.innerText = advNum.toLocaleString('en-US', {minimumFractionDigits: 2});
        dispExp.innerText = expNum.toLocaleString('en-US', {minimumFractionDigits: 2});
        dispBack.innerText = cashback.toLocaleString('en-US', {minimumFractionDigits: 2});
        dispLess.innerText = cashLess.toLocaleString('en-US', {minimumFractionDigits: 2});
        footerExpenseTotal.innerText = expNum.toLocaleString('en-US', {minimumFractionDigits: 2});

        syncHidden();
    }

    function syncHidden() {
        hiddenExpenses.value = JSON.stringify(expenseRows);
        hiddenAdvance.value = JSON.stringify({
            advance: parseFloat(advAdvance.value) || 0,
            expenses: parseFloat(advExpenses.value) || 0,
            cashback: parseFloat(advCashback.value) || 0,
            cash_less: parseFloat(advCashLess.value) || 0,
            total: parseFloat(advTotal.value) || 0
        });
    }

    function addRow() {
        expenseRows.push({ id: Date.now(), account_code: '', account_desc: '', dept_id: '', cost_id: '', amount: 0, description: '' });
        renderGrid();
        let trs = gridBody.querySelectorAll('tr');
        let inputs = trs[trs.length - 1].querySelectorAll('.cell-input');
        if(inputs.length > 0) inputs[0].focus();
    }

    window.handleDescKey = function(e, idx) {
        if(e.key === 'Enter') {
            e.preventDefault();
            addRow();
        }
    };
    
    document.addEventListener('keydown', function(e) {
        if(e.ctrlKey && e.key === 'd') {
            e.preventDefault();
            if(document.activeElement && document.activeElement.classList.contains('cell-input') && document.activeElement.closest('#expenseTable')) {
                let idx = parseInt(document.activeElement.getAttribute('data-idx'));
                let dup = {...expenseRows[idx], id: Date.now()};
                expenseRows.splice(idx+1, 0, dup);
                renderGrid();
                let newInputs = gridBody.querySelectorAll(`input[data-idx="${idx+1}"]`);
                if(newInputs.length>0) newInputs[0].focus();
            }
        }
    });

    function deleteRow(idx) {
        if(expenseRows.length > 1) {
            expenseRows.splice(idx, 1);
            renderGrid();
        }
    }

    document.getElementById('advanceForm').addEventListener('submit', (e) => {
        syncHidden();
    });

    renderGrid();
    document.getElementById('headerDate').dispatchEvent(new Event('input'));
</script>
@endpush
