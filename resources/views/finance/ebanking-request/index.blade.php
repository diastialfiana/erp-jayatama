@extends('layouts.app')
@section('title', 'e-Banking Request Detail - Finance')

@push('styles')
<style>
    :root {
        --b-pri: #1E3A8A;
        --b-acc: #3b82f6; 
        --edge: #cbd5e1;
        --bg-form: #fff;
    }
    
    /* Layout Centered */
    .req-container {
        display: flex; justify-content: center; align-items: flex-start;
        min-height: calc(100vh - 62px); padding: 40px 24px; background: #f8fafc;
    }
    .req-box {
        background: var(--bg-form); border: 1px solid var(--edge); border-radius: 4px;
        width: 100%; max-width: 600px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    
    /* ERP Tab Navigation */
    .erp-tab-nav {
        display: flex; gap: 2px; padding: 16px 16px 0 16px; border-bottom: 1px solid var(--edge);
    }
    .erp-tab {
        padding: 8px 16px; background: #e2e8f0; color: #475569; font-size: 12px; font-weight: 600; 
        text-transform: uppercase; text-decoration: none; border-top-left-radius: 4px; border-top-right-radius: 4px;
        transition: 0.2s; border: 1px solid transparent; border-bottom: none;
    }
    .erp-tab:hover { background: #cbd5e1; }
    .erp-tab.active { background: #fff; color: var(--b-pri); border-color: var(--edge); position:relative; top:1px; z-index:10; }

    .req-body {
        padding: 24px 32px;
    }

    /* Form Styles */
    .form-group {
        display: flex; align-items: center; margin-bottom: 12px;
    }
    .form-label {
        width: 140px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; flex-shrink: 0;
    }
    .erp-input {
        flex: 1; padding: 6px 10px; border: 1px solid var(--edge); border-radius: 4px;
        font-size: 13px; color: #1e293b; outline: none; transition: 0.2s;
    }
    .erp-input:focus { border-color: var(--b-acc); box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15); }
    .erp-readonly { background: #f1f5f9; cursor: not-allowed; color: #64748b; font-weight: 600; }
    
    /* Auto Complete Wrapper */
    .auto-wrap { position: relative; flex: 1; display:flex; flex-direction:column; }
    #invoiceList {
        position: absolute; top:100%; left:0; right:0; background:#fff; border:1px solid var(--edge); border-radius:4px;
        max-height:150px; overflow-y:auto; z-index:50; display:none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-top:2px;
    }
    .inv-item {
        padding: 6px 10px; font-size: 12px; cursor: pointer; border-bottom: 1px solid #f1f5f9;
        display:flex; justify-content:space-between;
    }
    .inv-item:hover { background: #f0f4ff; color: var(--b-pri); }
    .inv-title { font-weight: 600; }
    .inv-sub { color: #64748b; font-size: 10px; text-transform:uppercase; }

    /* Radio Group */
    .radio-group {
        flex: 1; display: flex; gap: 16px; align-items: center;
    }
    .radio-label {
        font-size: 12px; color: #334155; font-weight: 600; cursor: pointer; display:flex; align-items:center; gap:6px;
    }

    /* Actions */
    .req-actions {
        display: flex; justify-content: flex-end; gap: 12px; padding: 16px 32px 24px 32px; border-top: 1px solid #f1f5f9;
    }
    .btn {
        padding: 8px 16px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s;
        text-transform: uppercase;
    }
    .btn-save { background: var(--b-pri); color: #fff; border: none; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
    .btn-save:hover { background: var(--b-acc); }
    .btn-reset { background: #e2e8f0; color: #475569; border: 1px solid var(--edge); }
    .btn-reset:hover { background: #cbd5e1; }
</style>
@endpush

@section('content')
<div class="req-container">
    <div class="req-box">
        
        <div class="erp-tab-nav">
            <a href="{{ route('finance.ebanking-request.index') }}" class="erp-tab active">Detail</a>
            <a href="{{ route('finance.ebanking-request.recordList') }}" class="erp-tab">List All</a>
        </div>

        <form method="POST" action="{{ route('finance.ebanking-request.store') }}" id="ebankingForm">
            @csrf
            
            <div class="req-body">
                @if(session('success'))
                    <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:4px; margin-bottom:16px; font-size:12px; font-weight:700;">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div style="background:#fef2f2; color:#b91c1c; padding:12px 16px; border-radius:4px; margin-bottom:16px; font-size:12px; font-weight:700;">{{ session('error') }}</div>
                @endif

                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="erp-input" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Select Data</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="type" value="operational" checked onchange="toggleType()"> Operational
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="type" value="non_operational" onchange="toggleType()"> Non Operational
                        </label>
                    </div>
                </div>

                <div class="form-group" style="position:relative; z-index:20;">
                    <label class="form-label">Invoice No</label>
                    <div class="auto-wrap">
                        <input type="text" name="invoice_id" id="invoiceInput" class="erp-input" placeholder="Search invoice to Autofill..." autocomplete="off">
                        <div id="invoiceList"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Account No</label>
                    <input type="text" name="account_no" id="accountNo" class="erp-input erp-readonly" readonly required>
                </div>

                <div class="form-group">
                    <label class="form-label">Account Name</label>
                    <input type="text" name="account_name" id="accountName" class="erp-input erp-readonly" readonly required>
                </div>

                <div class="form-group">
                    <label class="form-label">Bank Name</label>
                    <input type="text" name="bank_name" id="bankName" class="erp-input erp-readonly" readonly required>
                </div>

                <div class="form-group" style="margin-top:24px; padding-top:16px; border-top:1px dashed #cbd5e1;">
                    <label class="form-label" style="color:var(--b-pri); font-size:13px;">Total Amount</label>
                    <input type="number" name="amount" id="totalAmount" class="erp-input erp-readonly" style="font-size:18px; font-weight:800; color:var(--b-pri); text-align:right;" step="0.01" value="0.00" readonly required>
                </div>

            </div>

            <div class="req-actions">
                <button type="button" class="btn btn-reset" onclick="resetForm()">Reset</button>
                <button type="submit" class="btn btn-save">Save Request</button>
            </div>
            
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let invoices = [];
    const invInput = document.getElementById('invoiceInput');
    const invList = document.getElementById('invoiceList');
    
    // Autofill targets
    const fAccNo = document.getElementById('accountNo');
    const fAccName = document.getElementById('accountName');
    const fBank = document.getElementById('bankName');
    const fAmount = document.getElementById('totalAmount');

    // Fetch mock invoices API
    fetch("{{ route('finance.ebanking-request.api.invoices') }}")
        .then(res => res.json())
        .then(data => { invoices = data; });

    // Autocomplete Logic
    invInput.addEventListener('input', function(e) {
        let val = this.value.toLowerCase();
        invList.innerHTML = '';
        if(!val) { invList.style.display = 'none'; return; }
        
        let filtered = invoices.filter(i => 
            i.id.toLowerCase().includes(val) || 
            i.account_name.toLowerCase().includes(val)
        );

        if(filtered.length > 0) {
            filtered.forEach(inv => {
                let div = document.createElement('div');
                div.className = 'inv-item';
                div.innerHTML = `
                    <div>
                        <div class="inv-title">${inv.id}</div>
                        <div class="inv-sub">${inv.account_name} &middot; ${inv.bank_name}</div>
                    </div>
                    <div style="font-weight:700; color:#3b82f6;">${parseFloat(inv.amount).toLocaleString('en-US')}</div>
                `;
                div.onclick = function() {
                    invInput.value = inv.id;
                    fAccNo.value = inv.account_no;
                    fAccName.value = inv.account_name;
                    fBank.value = inv.bank_name;
                    fAmount.value = inv.amount;
                    invList.style.display = 'none';
                };
                invList.appendChild(div);
            });
            invList.style.display = 'block';
        } else {
            invList.style.display = 'none';
        }
    });

    // Close autocomplete on click outside
    document.addEventListener('click', function(e) {
        if(!document.getElementById('invoiceList').contains(e.target) && e.target !== invInput) {
            invList.style.display = 'none';
        }
    });

    // Radio Type Toggling (Operational vs Non Operational)
    function toggleType() {
        let opCheck = document.querySelector('input[name="type"][value="operational"]');
        if(!opCheck.checked) {
            // Unlocking fields for non-operational manual entries
            invInput.value = '';
            invInput.disabled = true;
            invInput.style.backgroundColor = '#f1f5f9';
            invInput.placeholder = 'N/A';

            fAccNo.readOnly = false; fAccNo.classList.remove('erp-readonly'); fAccNo.value = '';
            fAccName.readOnly = false; fAccName.classList.remove('erp-readonly'); fAccName.value = '';
            fBank.readOnly = false; fBank.classList.remove('erp-readonly'); fBank.value = '';
            fAmount.readOnly = false; fAmount.classList.remove('erp-readonly'); fAmount.value = 0;
            fAmount.style.background = '#fff';
        } else {
            // Lock fields back for autocomplete mapping
            invInput.disabled = false;
            invInput.style.backgroundColor = '#fff';
            invInput.placeholder = 'Search invoice to Autofill...';

            fAccNo.readOnly = true; fAccNo.classList.add('erp-readonly'); fAccNo.value = '';
            fAccName.readOnly = true; fAccName.classList.add('erp-readonly'); fAccName.value = '';
            fBank.readOnly = true; fBank.classList.add('erp-readonly'); fBank.value = '';
            fAmount.readOnly = true; fAmount.classList.add('erp-readonly'); fAmount.value = 0;
            fAmount.style.background = '#f1f5f9';
        }
    }

    function resetForm() {
        document.getElementById('ebankingForm').reset();
        toggleType(); // Reset state locks
    }
</script>
@endpush
