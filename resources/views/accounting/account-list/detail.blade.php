@extends('layouts.app')
@section('title', 'Record Detail - Account List')

@push('styles')
<style>
    :root {
        --b-pri: #1E3A8A;
    }

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

    /* TAB HEADER */
    .erp-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 8px 0 8px;
        border-bottom: 1px solid #cfcfcf;
        background: #f3f3f3;
    }

    .erp-tabs {
        display: flex;
        gap: 2px;
    }

    .erp-tabs span {
        padding: 6px 16px;
        background: #e2e8f0;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        cursor: pointer;
        border: 1px solid #cfcfcf;
        border-bottom: none;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
        user-select: none;
    }

    .erp-tabs span.active {
        background: var(--b-pri);
        color: #fff;
        border-color: var(--b-pri);
    }

    .erp-tabs span:hover:not(.active) {
        background: #cbd5e1;
    }

    .erp-navigation {
        font-size: 12px;
        color: #555;
        display: flex;
        gap: 8px;
        align-items: center;
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

    /* BODY */
    .erp-body {
        padding: 20px;
        flex: 1;
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
    }

    .erp-form {
        width: 600px;
        margin: auto;
    }

    /* FORM STYLE (ERP CLASSIC) */
    .erp-form-table {
        width: 100%;
        border-spacing: 6px;
    }

    .erp-form-table td:first-child {
        width: 180px;
        text-align: right;
        padding-right: 10px;
        font-weight: bold;
    }

    .erp-form-table input[type="text"],
    .erp-form-table select {
        width: 250px;
        padding: 4px 6px;
        border: 1px solid #ccc;
        font-size: 12px;
        background-color: #fff;
        outline: none;
        box-sizing: border-box;
    }

    .erp-form-table input[type="text"]:focus,
    .erp-form-table select:focus {
        border-color: var(--b-pri);
        box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.1);
    }

    .erp-form-table input[readonly] {
        background-color: #f8f9fa;
        color: #495057;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checkbox-group label {
        margin: 0;
        cursor: pointer;
    }
    
    .mix-currency-container, 
    .create-copy-container {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .short-input {
        width: 120px !important;
    }

</style>
@endpush

@section('content')
<div class="erp-container">

    <!-- TAB HEADER -->
    <div class="erp-header">
        <div class="erp-tabs">
            <span class="active" onclick="window.location.reload()">RECORD DETAIL</span>
            <span onclick="window.location.href='{{ route('accounting.account-list.index') }}'">RECORDS LIST</span>
            <span onclick="window.location.href='{{ route('accounting.account-list.statistics', $account->id ?? 1) }}'">STATISTICS</span>
            <span onclick="window.location.href='{{ route('accounting.account-list.activity', $account->id ?? 1) }}'">ACTIVITY</span>
            <span onclick="window.location.href='{{ route('accounting.account-list.backdate', $account->id ?? 1) }}'">BACKDATE</span>
            <span onclick="window.location.href='{{ route('accounting.account-list.summary', $account->id ?? 1) }}'">SUMMARY</span>
        </div>

        <div class="erp-navigation">
            <button class="nav-btn">&lt;&lt;</button>
            <button class="nav-btn">&lt;</button>
            <span>Record 1 of 302</span>
            <button class="nav-btn">&gt;</button>
            <button class="nav-btn">&gt;&gt;</button>
        </div>
    </div>

    <!-- BODY -->
    <div class="erp-body">
        <div class="erp-form">
            <table class="erp-form-table">

            <tr>
                <td>Branch / Unit</td>
                <td>
                    <select>
                        <option>HEAD OFFICE - CHEMICAL</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Code</td>
                <td><input type="text" value="{{ $account->code ?? '' }}"></td>
            </tr>

            <tr>
                <td>Currency</td>
                <td>
                    <select>
                        <option>Indonesia</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Account Name</td>
                <td><input type="text" value="{{ $account->name ?? '' }}"></td>
            </tr>

            <tr>
                <td>Account Type</td>
                <td>
                    <select>
                        <option>Asset</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Open Fixed Asset</td>
                <td>
                    <div class="checkbox-group">
                        <input type="checkbox">
                    </div>
                </td>
            </tr>

            <tr>
                <td>Control Acc</td>
                <td>
                    <div class="mix-currency-container checkbox-group">
                        <input type="checkbox" {{ isset($account->is_control) && $account->is_control ? 'checked' : '' }}>
                        <label>Mix Currency</label>
                        <input type="checkbox">
                    </div>
                </td>
            </tr>

            <tr>
                <td>Account Dept</td>
                <td>
                    <input type="text">
                </td>
            </tr>

            <tr>
                <td>Cost Center</td>
                <td>
                    <select>
                        <option>UMUM</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Create Copy</td>
                <td>
                    <div class="create-copy-container checkbox-group">
                        <input type="checkbox"> Into
                        <input type="text" class="short-input">
                    </div>
                </td>
            </tr>

            <tr>
                <td>Balance</td>
                <td>
                    <input type="text" class="format-number" value="{{ $account->balance ?? '' }}">
                </td>
            </tr>

            <tr>
                <td>[Balance]</td>
                <td>
                    <input type="text" class="format-number" value="78038410943.98">
                </td>
            </tr>

            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Auto format angka untuk input dengan class 'format-number'
        const numberInputs = document.querySelectorAll('.format-number');
        
        numberInputs.forEach(function(input) {
            // Inisialisasi awal saat load
            formatInputValue(input);
            
            // Format ulang saat user mengetik / blur
            input.addEventListener('blur', function() {
                formatInputValue(this);
            });
            
            // Menghapus koma/titik pemisah saat focus agar mudah diedit
            input.addEventListener('focus', function() {
                let val = this.value.replace(/,/g, '');
                this.value = val;
            });
        });

        function formatInputValue(el) {
            let val = el.value.replace(/,/g, '');
            if (!isNaN(val) && val !== '') {
                // Formatting to string standard ERP 
                // Using max fraction 2 for decimals
                let num = Number(val);
                el.value = num.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        }
        
        // Tab Interaction
        const tabs = document.querySelectorAll('.erp-tabs span');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });
</script>
@endpush
