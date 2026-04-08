@extends('layouts.app')
@section('title', 'Cash Receipt - Finance')

@push('styles')
<style>
    :root {
        --b-pri: #1E3A8A;
        --b-acc: #3b82f6; 
        --grid-border: #cbd5e1;
        --grid-bg: #f8fafc;
        --grid-focus: rgba(59, 130, 246, 0.15);
        --alert-danger: #fee2e2;
        --alert-danger-text: #991b1b;
        --alert-success: #dcfce7;
        --alert-success-text: #166534;
    }
    
    .top-toolbar { display: flex; gap: 8px; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; }
    .btn-tool { background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 6px 14px; font-size: 12px; font-weight: 600; color: #334155; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
    .btn-tool:hover { background: #f1f5f9; border-color: #94a3b8; }
    .btn-save { background: var(--b-pri); color: #fff; border: none; }
    .btn-save:hover { background: var(--b-acc); color: #fff; }

    .erp-box { background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 16px; margin-bottom: 16px; }
    .form-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .form-group { display: flex; flex-direction: column; gap: 4px; }
    .form-label { font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; }
    
    .erp-input, .erp-select {
        background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 6px 10px;
        font-size: 13px; outline: none; transition: 0.2s; width: 100%;
        font-family: inherit;
    }
    .erp-input:focus, .erp-select:focus { border-color: var(--b-acc); box-shadow: 0 0 0 2px var(--grid-focus); }
    
    .journal-container { border: 1px solid var(--grid-border); border-radius: 4px; overflow-x: auto; margin-bottom: 16px; }
    .journal-table { width: 100%; border-collapse: collapse; font-size: 12px; font-family: 'Inter', sans-serif; }
    .journal-table th { background: #f1f5f9; border-bottom: 2px solid var(--grid-border); border-right: 1px solid var(--grid-border); padding: 8px; text-align: left; font-weight: 700; color: #334155; font-size: 11px; letter-spacing: 0.5px; }
    .journal-table td { border-bottom: 1px solid var(--grid-border); border-right: 1px solid var(--grid-border); padding: 0; position: relative; }
    
    .cell-input { width: 100%; height: 100%; padding: 8px; border: none; background: transparent; font-size: 13px; color: #0f172a; outline: none; }
    .cell-input:focus { background: var(--grid-focus); }
    .cell-numeric { text-align: right; font-variant-numeric: tabular-nums; font-weight: 600; }
    
    .grid-header { background: #e2e8f0; padding: 8px 12px; font-weight: bold; font-size: 12px; color: #1e293b; border-bottom: 1px solid #cbd5e1; display:flex; justify-content:space-between; align-items:center;}
    
    .foot-section { display: flex; justify-content: space-between; align-items: stretch; margin-top: 16px; gap: 24px; }
    .note-box { flex: 1; display: flex; flex-direction: column; }
    
    .summary-box { background: #f8fafc; border: 1px solid var(--grid-border); border-radius: 4px; padding: 16px; width: 340px; flex-shrink: 0; display:flex; flex-direction:column; justify-content:center;}
    .summary-total { display: flex; justify-content: space-between; font-size: 18px; font-weight: 800; color: #1e3a8a; }

    .status-bar { background: #e2e8f0; color: #475569; font-size: 11px; padding: 8px 16px; margin-top: 24px; border-radius: 4px; display: flex; justify-content: space-between; font-weight: 600; }
    
    /* Utility */
    .readonly-cell { background: #f8fafc !important; color: #64748b; cursor: not-allowed; }
</style>
@endpush

@section('content')
<div style="background:var(--grid-bg); min-height:calc(100vh - 62px); padding:24px;">
    <div style="max-width:1440px; margin:0 auto;">
        
        <form id="cashReceiptForm" method="POST" action="{{ route('finance.cash-receipt.store') }}">
            @csrf
            <input type="hidden" name="details" id="hiddenDetails">
            <input type="hidden" name="journals" id="hiddenJournals">

            <!-- ERP Tab Navigation -->
            <div style="display:flex; gap:2px; margin-bottom:16px;">
                <a href="{{ route('finance.cash-receipt.index') }}" style="padding:8px 16px; background:var(--b-pri); color:#fff; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                    Receive Detail
                </a>
                <a href="{{ route('finance.cash-receipt.records-list') }}" style="padding:8px 16px; background:#e2e8f0; color:#475569; font-size:12px; font-weight:600; text-transform:uppercase; text-decoration:none; border-top-left-radius:4px; border-top-right-radius:4px;">
                    Receive List
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
                <button type="button" class="btn-tool" onclick="autoGenerateJournal()" style="background:#fef08a;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12h4l2-9 4 18 2-9h6"></path></svg>
                    Auto Generate Journal
                </button>
                <div style="flex:1;"></div>
                <button type="button" class="btn-tool">First</button>
                <button type="button" class="btn-tool">Prev</button>
                <button type="button" class="btn-tool">Next</button>
                <button type="button" class="btn-tool">Last</button>
            </div>

            @if(session('success'))
                <div style="background:var(--alert-success); color:var(--alert-success-text); padding:12px 16px; border-radius:4px; margin-bottom:16px; font-size:13px; font-weight:600;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:var(--alert-danger); color:var(--alert-danger-text); padding:12px 16px; border-radius:4px; margin-bottom:16px; font-size:13px; font-weight:600;">{{ session('error') }}</div>
            @endif

            <!-- Form Header -->
            <div class="erp-box form-grid">
                <div class="form-group">
                    <span class="form-label">Date *</span>
                    <input type="date" name="date" class="erp-input" required value="{{ date('Y-m-d') }}" tabindex="1">
                </div>
                <div class="form-group">
                    <span class="form-label">Reference</span>
                    <input type="text" name="reference" class="erp-input" placeholder="CR-..." tabindex="2">
                </div>
                <div class="form-group">
                    <span class="form-label">Currency</span>
                    <div style="display:flex; gap:8px;">
                        <select name="currency" class="erp-select" style="flex:1;" tabindex="3">
                            <option value="IDR">IDR</option>
                            <option value="USD">USD</option>
                        </select>
                        <input type="number" name="rate" class="erp-input" style="flex:2;" value="1" step="0.01" tabindex="4">
                    </div>
                </div>
                <div class="form-group">
                    <span class="form-label">Cash / Bank Account *</span>
                    <select name="bank_id" id="bankSelect" class="erp-select" required tabindex="5">
                        <option value="">-- Select Bank --</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <span class="form-label">Customer Name *</span>
                    <input type="text" id="customerSearch" class="erp-input" list="customerList" placeholder="Search Customer..." autocomplete="off" tabindex="6" required>
                    <input type="hidden" name="customer_id" id="customerId" required>
                </div>
            </div>

            <!-- Grid 1: INVOICE PAYMENT -->
            <div class="erp-box" style="padding:0; overflow:hidden;">
                <div class="grid-header">
                    <span>INVOICE PAYMENT</span>
                    <span style="font-weight:normal; font-size:11px; color:#64748b;">(Select invoice and input payment amount)</span>
                </div>
                <div class="journal-container" style="border:none; margin:0;">
                    <table class="journal-table" id="detailsTable">
                        <thead>
                            <tr>
                                <th style="width: 25%;">INVOICE SELECT</th>
                                <th style="width: 12%;">DATE</th>
                                <th style="width: 12%;">DUE DATE</th>
                                <th style="width: 12%; text-align:right;">BALANCE</th>
                                <th style="width: 12%; text-align:right;">AMOUNT (PAID)</th>
                                <th style="width: 10%; text-align:right;">DISCOUNT</th>
                                <th style="width: 10%; text-align:right;">PREPAID</th>
                                <th style="width: 12%; text-align:right;">TOTAL CALC</th>
                            </tr>
                        </thead>
                        <tbody id="detailsBody">
                            <!-- JS injected rows -->
                        </tbody>
                        <tfoot style="background:#f1f5f9; font-weight:bold;">
                            <tr>
                                <td colspan="4" style="text-align:right; padding:8px; border-right:1px solid var(--grid-border);">TOTAL (Grid 1)</td>
                                <td style="text-align:right; padding:8px; border-right:1px solid var(--grid-border);" id="ftAmount">0.00</td>
                                <td style="text-align:right; padding:8px; border-right:1px solid var(--grid-border);" id="ftDiscount">0.00</td>
                                <td style="text-align:right; padding:8px; border-right:1px solid var(--grid-border);" id="ftPrepaid">0.00</td>
                                <td style="text-align:right; padding:8px; border-right:1px solid var(--grid-border);" id="ftTotalCalc">0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div style="background:#e2e8f0; padding:4px 8px; font-size:11px; color:#475569; display:flex; justify-content:center; gap:16px;">
                    <span>&lt;&lt;</span>
                    <span>&lt;</span>
                    <span id="dtRecordCount">Record 0 of 0</span>
                    <span>&gt;</span>
                    <span>&gt;&gt;</span>
                </div>
            </div>

            <!-- Footer / Side by Side -->
            <div class="foot-section" style="margin-bottom:16px;">
                <div class="note-box">
                    <span class="form-label">Note / Remark</span>
                    <textarea name="note" class="erp-input" style="height: 100px; resize:none;" tabindex="100" placeholder="Type your note here..."></textarea>
                </div>
                <div class="summary-box">
                    <span class="form-label" style="text-align:right; margin-bottom:8px;">TOTAL CASH RECEIVE</span>
                    <div class="summary-total" style="justify-content:flex-end;">
                        <span id="grandTotalReceive">0.00</span>
                    </div>
                </div>
            </div>

            <!-- Grid 2: ACCOUNTING ENTRY -->
            <div class="erp-box" style="padding:0; overflow:hidden;">
                <div class="grid-header">
                    <span>ACCOUNTING ENTRY</span>
                    <button type="button" onclick="addJournalRow()" style="background:none; border:none; color:var(--b-pri); font-weight:bold; cursor:pointer; font-size:12px;">+ Add Line</button>
                </div>
                <div class="journal-container" style="border:none; margin:0;">
                    <table class="journal-table" id="journalTable">
                        <thead>
                            <tr>
                                <th style="width: 20%;">ACCOUNT</th>
                                <th style="width: 25%;">ACCOUNT DESC.</th>
                                <th style="width: 15%;">DEPT.</th>
                                <th style="width: 15%;">COST C.</th>
                                <th style="width: 10%; text-align:right;">DEBIT</th>
                                <th style="width: 10%; text-align:right;">CREDIT</th>
                                <th style="width: 40px; text-align:center;">ACT</th>
                            </tr>
                        </thead>
                        <tbody id="journalBody">
                            <!-- JS injected rows -->
                        </tbody>
                        <tfoot style="background:#f1f5f9; font-weight:bold;">
                            <tr>
                                <td colspan="4" style="text-align:right; padding:8px; border-right:1px solid var(--grid-border);">TOTAL (Grid 2)</td>
                                <td style="text-align:right; padding:8px; border-right:1px solid var(--grid-border);" id="jtDebit">0.00</td>
                                <td style="text-align:right; padding:8px; border-right:1px solid var(--grid-border);" id="jtCredit">0.00</td>
                                <td style="background:#f1f5f9;"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="status-bar">
                <span id="validationStatus">Mode: INSERT | Unbalanced</span>
                <span>Ready</span>
            </div>

        </form>
    </div>
</div>

<datalist id="customerList"></datalist>
<datalist id="invoiceList"></datalist>
<datalist id="accountList"></datalist>
<datalist id="deptList"></datalist>
<datalist id="costList"></datalist>

@endsection

@push('scripts')
<script>
    // Data stores
    let customersData = [];
    let bankData = [];
    let invoicesData = [];
    let accountsData = [];
    let deptsData = [];
    let costsData = [];

    // State
    let dRows = [{ id: Date.now(), invoice_id: '', inv_num: '', date: '', due_date: '', balance: 0, amount: 0, discount: 0, prepaid: 0, total: 0 }];
    let jRows = [{ id: Date.now(), account_id: '', code: '', name: '', dept_id: '', cost_id: '', debit: 0, credit: 0, desc: '' }];

    let currentCustomerId = null;
    
    // DOM Elements
    const dListCust = document.getElementById('customerList');
    const dListInv = document.getElementById('invoiceList');
    const dListAcc = document.getElementById('accountList');
    const dListDep = document.getElementById('deptList');
    const dListCost = document.getElementById('costList');
    
    const bankSelect = document.getElementById('bankSelect');
    
    // Fetch APIs
    Promise.all([
        fetch("{{ route('finance.cash-receipt.api.customers') }}").then(res => res.json()),
        fetch("{{ route('finance.cash-receipt.api.banks') }}").then(res => res.json()),
        fetch("{{ route('finance.cash-receipt.api.accounts') }}").then(res => res.json()),
        fetch("{{ route('finance.cash-receipt.api.deps') }}").then(res => res.json()),
        fetch("{{ route('finance.cash-receipt.api.costs') }}").then(res => res.json())
    ]).then(([custs, banks, accs, deps, costs]) => {
        customersData = custs;
        custs.forEach(c => dListCust.appendChild(new Option(c.name, c.name)));
        
        bankData = banks;
        banks.forEach(b => bankSelect.appendChild(new Option(b.bank_name + ' - ' + b.bank_account, b.id)));
        
        accountsData = accs;
        accs.forEach(a => dListAcc.appendChild(new Option(a.name, a.code)));
        
        deptsData = deps;
        deps.forEach(d => dListDep.appendChild(new Option(d.name, d.code)));
        
        costsData = costs;
        costs.forEach(c => dListCost.appendChild(new Option(c.name, c.code)));
        
        renderDetails();
        renderJournals();
    });

    // Customer Selection -> Fetch Invoices
    document.getElementById('customerSearch').addEventListener('input', function(e) {
        let val = e.target.value;
        let c = customersData.find(c => c.name === val);
        if (c) {
            document.getElementById('customerId').value = c.id;
            if (currentCustomerId !== c.id) {
                currentCustomerId = c.id;
                fetchInvoices(c.id);
            }
        } else {
            document.getElementById('customerId').value = '';
        }
    });

    function fetchInvoices(customerId) {
        fetch("{{ route('finance.cash-receipt.api.invoices') }}?customer_id=" + customerId)
            .then(res => res.json())
            .then(data => {
                invoicesData = data;
                dListInv.innerHTML = '';
                data.forEach(inv => {
                    dListInv.appendChild(new Option('Bal: ' + inv.current_balance, inv.invoice_number));
                });
                // Reset detail rows
                dRows = [{ id: Date.now(), invoice_id: '', inv_num: '', date: '', due_date: '', balance: 0, amount: 0, discount: 0, prepaid: 0, total: 0 }];
                renderDetails();
            });
    }

    // Grid 1 Logic
    const detailsBody = document.getElementById('detailsBody');
    function renderDetails() {
        detailsBody.innerHTML = '';
        let totAm = 0, totDisc = 0, totPrep = 0, totCalc = 0;
        
        dRows.forEach((r, idx) => {
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input type="text" class="cell-input field-inv" data-idx="${idx}" value="${r.inv_num}" list="invoiceList" placeholder="Select Invoice..." autocomplete="off"></td>
                <td><input type="text" class="cell-input readonly-cell" value="${r.date}" readonly tabindex="-1"></td>
                <td><input type="text" class="cell-input readonly-cell" value="${r.due_date}" readonly tabindex="-1"></td>
                <td><input type="text" class="cell-input cell-numeric readonly-cell" value="${Number(r.balance).toFixed(2)}" readonly tabindex="-1"></td>
                <td><input type="number" class="cell-input cell-numeric d-amount" data-idx="${idx}" value="${r.amount}" step="0.01"></td>
                <td><input type="number" class="cell-input cell-numeric d-disc" data-idx="${idx}" value="${r.discount}" step="0.01"></td>
                <td><input type="number" class="cell-input cell-numeric d-prep" data-idx="${idx}" value="${r.prepaid}" step="0.01"></td>
                <td><input type="text" class="cell-input cell-numeric readonly-cell" value="${Number(r.total).toFixed(2)}" readonly tabindex="-1"></td>
            `;
            detailsBody.appendChild(tr);
            
            totAm += parseFloat(r.amount) || 0;
            totDisc += parseFloat(r.discount) || 0;
            totPrep += parseFloat(r.prepaid) || 0;
            totCalc += parseFloat(r.total) || 0;
        });

        document.getElementById('ftAmount').innerText = totAm.toFixed(2);
        document.getElementById('ftDiscount').innerText = totDisc.toFixed(2);
        document.getElementById('ftPrepaid').innerText = totPrep.toFixed(2);
        document.getElementById('ftTotalCalc').innerText = totCalc.toFixed(2);
        document.getElementById('grandTotalReceive').innerText = totAm.toFixed(2);
        
        document.getElementById('dtRecordCount').innerText = `Record ${dRows.length} of ${dRows.length}`;
        syncHidden();
    }

    detailsBody.addEventListener('change', (e) => {
        if (!e.target.classList.contains('cell-input')) return;
        let idx = parseInt(e.target.getAttribute('data-idx'));
        let val = e.target.value;

        if (e.target.classList.contains('field-inv')) {
            let inv = invoicesData.find(i => i.invoice_number === val);
            if (inv) {
                dRows[idx].invoice_id = inv.id;
                dRows[idx].inv_num = inv.invoice_number;
                dRows[idx].date = inv.date;
                dRows[idx].due_date = inv.due_date;
                dRows[idx].balance = parseFloat(inv.current_balance) || 0;
                dRows[idx].amount = dRows[idx].balance; // default paid amount
                
                // Add blank row if this is last row
                if (idx === dRows.length - 1) {
                    dRows.push({ id: Date.now(), invoice_id: '', inv_num: '', date: '', due_date: '', balance: 0, amount: 0, discount: 0, prepaid: 0, total: 0 });
                }
            } else {
                dRows[idx].invoice_id = '';
            }
        } 
        else if (e.target.classList.contains('d-amount')) { dRows[idx].amount = parseFloat(val) || 0; }
        else if (e.target.classList.contains('d-disc')) { dRows[idx].discount = parseFloat(val) || 0; }
        else if (e.target.classList.contains('d-prep')) { dRows[idx].prepaid = parseFloat(val) || 0; }

        dRows[idx].total = (parseFloat(dRows[idx].amount) || 0) - (parseFloat(dRows[idx].discount) || 0) + (parseFloat(dRows[idx].prepaid) || 0);

        renderDetails();
    });

    // Grid 2 Logic
    const journalBody = document.getElementById('journalBody');
    function addJournalRow() {
        jRows.push({ id: Date.now(), account_id: '', code: '', name: '', dept_id: '', cost_id: '', debit: 0, credit: 0, desc: '' });
        renderJournals();
    }

    function removeJournalRow(idx) {
        if (jRows.length > 1) {
            jRows.splice(idx, 1);
            renderJournals();
        }
    }

    function renderJournals() {
        journalBody.innerHTML = '';
        let tDeb = 0, tCre = 0;

        jRows.forEach((r, idx) => {
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input type="text" class="cell-input j-acc" data-idx="${idx}" value="${r.code}" list="accountList" placeholder="Code..." autocomplete="off"></td>
                <td><input type="text" class="cell-input readonly-cell" value="${r.name}" readonly tabindex="-1"></td>
                <td><input type="text" class="cell-input j-dept" data-idx="${idx}" value="${r.dept_code || ''}" list="deptList" autocomplete="off"></td>
                <td><input type="text" class="cell-input j-cost" data-idx="${idx}" value="${r.cost_code || ''}" list="costList" autocomplete="off"></td>
                <td><input type="number" class="cell-input cell-numeric j-deb" data-idx="${idx}" value="${r.debit}" step="0.01"></td>
                <td><input type="number" class="cell-input cell-numeric j-cre" data-idx="${idx}" value="${r.credit}" step="0.01"></td>
                <td style="text-align:center;">
                    <button type="button" onclick="removeJournalRow(${idx})" style="background:none; border:none; color:#ef4444; font-weight:bold; cursor:pointer;" tabindex="-1">×</button>
                </td>
            `;
            journalBody.appendChild(tr);

            tDeb += parseFloat(r.debit) || 0;
            tCre += parseFloat(r.credit) || 0;
        });

        document.getElementById('jtDebit').innerText = tDeb.toFixed(2);
        document.getElementById('jtCredit').innerText = tCre.toFixed(2);
        
        let validElem = document.getElementById('validationStatus');
        if (tDeb.toFixed(2) === tCre.toFixed(2) && tDeb > 0) {
            validElem.innerText = "Mode: INSERT | Balanced ✓";
            validElem.style.color = "var(--alert-success-text)";
        } else {
            validElem.innerText = "Mode: INSERT | Unbalanced ✗";
            validElem.style.color = "var(--alert-danger-text)";
        }
        syncHidden();
    }

    journalBody.addEventListener('change', (e) => {
        if (!e.target.classList.contains('cell-input')) return;
        let idx = parseInt(e.target.getAttribute('data-idx'));
        let val = e.target.value;

        if (e.target.classList.contains('j-acc')) {
            let acc = accountsData.find(a => a.code === val);
            if (acc) {
                jRows[idx].account_id = acc.id;
                jRows[idx].code = acc.code;
                jRows[idx].name = acc.name;
            }
        } 
        else if (e.target.classList.contains('j-dept')) {
            let dep = deptsData.find(d => d.code === val);
            if (dep) { jRows[idx].dept_id = dep.id; jRows[idx].dept_code = dep.code; }
        }
        else if (e.target.classList.contains('j-cost')) {
            let cos = costsData.find(c => c.code === val);
            if (cos) { jRows[idx].cost_id = cos.id; jRows[idx].cost_code = cos.code; }
        }
        else if (e.target.classList.contains('j-deb')) { jRows[idx].debit = parseFloat(val) || 0; }
        else if (e.target.classList.contains('j-cre')) { jRows[idx].credit = parseFloat(val) || 0; }
        
        renderJournals();
    });

    // Auto Journal Function
    window.autoGenerateJournal = function() {
        let bankId = document.getElementById('bankSelect').value;
        if (!bankId) {
            alert('Please select a Bank Account first!');
            return;
        }

        let bank = bankData.find(b => b.id == bankId);
        let arCode = bank.ar_account; // Usually AR account associated to bank in setup, or just general AR
        
        let totalAm = 0;
        dRows.forEach(r => totalAm += (parseFloat(r.amount) || 0));
        
        if (totalAm <= 0) {
            alert('Total paid amount is 0. Please enter payment in Grid 1.');
            return;
        }

        jRows = [];
        
        // Debit Bank
        let bankAcc = accountsData.find(a => a.code === bank.code);
        if (bankAcc) {
            jRows.push({ id: Date.now(), account_id: bankAcc.id, code: bankAcc.code, name: bankAcc.name, dept_id: '', cost_id: '', debit: totalAm, credit: 0, desc: 'Auto CR' });
        } else {
            jRows.push({ id: Date.now(), account_id: '', code: bank.code, name: 'Bank Account Not Found in COA', dept_id: '', cost_id: '', debit: totalAm, credit: 0, desc: 'Auto CR' });
        }

        // Credit AR
        let tradeAcc = accountsData.find(a => a.code === arCode) || accountsData.find(a => a.name.includes('Account Receivable') || a.name.includes('Piutang'));
        if (tradeAcc) {
            jRows.push({ id: Date.now()+1, account_id: tradeAcc.id, code: tradeAcc.code, name: tradeAcc.name, dept_id: '', cost_id: '', debit: 0, credit: totalAm, desc: 'Auto CR' });
        } else {
            jRows.push({ id: Date.now()+1, account_id: '', code: arCode || '', name: 'Trade AR Account Code', dept_id: '', cost_id: '', debit: 0, credit: totalAm, desc: 'Auto CR' });
        }
        
        renderJournals();
    }

    // Sync before submit
    function syncHidden() {
        document.getElementById('hiddenDetails').value = JSON.stringify(dRows.filter(r => r.invoice_id));
        document.getElementById('hiddenJournals').value = JSON.stringify(jRows.filter(r => r.account_id && (r.debit > 0 || r.credit > 0)));
    }

    document.getElementById('cashReceiptForm').addEventListener('submit', function(e) {
        syncHidden();
        let validElem = document.getElementById('validationStatus');
        if (validElem.innerText.includes('Unbalanced')) {
            e.preventDefault();
            alert('Journal entries are NOT balanced. Total Debit must equal Total Credit!');
            return;
        }
        let jData = JSON.parse(document.getElementById('hiddenJournals').value);
        if (jData.length === 0) {
            e.preventDefault();
            alert('Please generate or enter Accounting Entry first!');
        }
    });

</script>
@endpush
