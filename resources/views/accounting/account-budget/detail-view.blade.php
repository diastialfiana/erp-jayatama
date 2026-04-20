@extends('layouts.app')
@section('title', 'Detail View - Account Budget')

@push('styles')
<style>
    :root { --b-pri: #1E3A8A; }
    .erp-container {
        background: #f3f3f3;
        font-size: 12px;
        min-height: calc(100vh - 62px);
        display: flex;
        flex-direction: column;
        color: #333;
    }
    .erp-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 8px 8px 0 8px; border-bottom: 1px solid #cfcfcf; background: #f3f3f3;
    }
    .erp-tabs { display: flex; gap: 2px; }
    .erp-tabs span {
        display: inline-block; padding: 6px 16px; background: #e2e8f0; color: #475569;
        font-size: 12px; font-weight: 600; text-transform: uppercase; cursor: pointer;
        border: 1px solid #cfcfcf; border-bottom: none; border-top-left-radius: 4px;
        border-top-right-radius: 4px; user-select: none;
    }
    .erp-tabs span.active { background: var(--b-pri); color: #fff; border-color: var(--b-pri); }
    .erp-body-split {
        display: flex; flex: 1; padding: 10px; gap: 10px; flex-wrap: nowrap; overflow: hidden;
    }
    .pane-left {
        width: 35%; background: #fff; border: 1px solid #ccc; border-radius: 2px;
        display: flex; flex-direction: column; overflow: hidden;
    }
    .pane-right {
        width: 65%; background: #fff; border: 1px solid #ccc; border-radius: 2px;
        display: flex; flex-direction: column; overflow: hidden;
    }
    .pane-header {
        padding: 8px 10px; background: #e9ecef; border-bottom: 1px solid #ccc;
        font-weight: bold; font-size: 13px; color: #444; flex-shrink: 0;
    }
    .pane-content {
        flex: 1; overflow-y: auto; padding: 10px;
    }
    .erp-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .erp-table th { background: #e9ecef; border: 1px solid #ccc; padding: 6px; text-align: left; white-space: nowrap; font-weight: 600; position: sticky; top: -10px; z-index: 2; }
    .erp-table td { border: 1px solid #ddd; padding: 4px 6px; white-space: nowrap; }
    .table-right { text-align: right; }
    .table-center { text-align: center; }
    .erp-table tbody tr:hover { background: #eaf3ff; }
    .erp-table tbody tr.active-row { background: #dbeafe; font-weight: bold; }
    .erp-table tbody tr { cursor: pointer; }

    /* Budget Table specific */
    .budget-table th { text-align: right; }
    .budget-table th:first-child { text-align: left; }
    .budget-table input[type="text"] {
        width: 100%; box-sizing: border-box; text-align: right; padding: 4px; border: 1px solid #ccc; font-size: 12px;
    }
    .budget-table input[type="text"]:focus {
        border-color: var(--b-pri); outline: none; box-shadow: 0 0 0 2px rgba(30,58,138,0.1);
    }
    .budget-table .total-row td { background: #f8fafc; font-weight: bold; color: var(--b-pri); }
    
    .toolbar {
        margin-bottom: 15px; padding: 10px; background: #f8f9fa; border: 1px solid #ccc;
        display: flex; align-items: center; gap: 10px; border-radius: 2px;
    }
    .toolbar label { font-weight: 600; }
    .toolbar input { padding: 4px 8px; border: 1px solid #ccc; width: 150px; text-align: right; font-size: 12px;}
    .toolbar button { margin-left: 5px; padding: 4px 10px; cursor: pointer; border: 1px solid #ccc; background: #e2e8f0; font-size: 12px; }
    .toolbar button:hover { background: #cbd5e1; }
    .btn-save { background: var(--b-pri); color: #fff; border: 1px solid var(--b-pri); padding: 6px 16px; margin-top: 10px; font-weight: 600; width: 100%; cursor: pointer;}
    .btn-save:hover { opacity: 0.9; }

    .account-info {
        margin-bottom: 15px; padding: 10px; background: #eff6ff; border: 1px solid #bfdbfe;
        border-radius: 2px; color: #1e40af; font-weight: 600; font-size: 14px;
        display: flex; justify-content: space-between;
    }
</style>
@endpush

@section('content')
<div class="erp-container">

    <div class="erp-header">
        <div class="erp-tabs">
            <span class="active">DETAIL VIEW</span>
            <span onclick="window.location.href='{{ route('accounting.account-budget.statistics', $account->id ?? 1) }}'">STATISTICS</span>
        </div>
    </div>

    <div class="erp-body-split">
        
        <!-- PANE LEFT: BUDGET INPUT -->
        <div class="pane-left">
            <div class="pane-header">Input Budget</div>
            <div class="pane-content">
                @if($account)
                <div class="account-info">
                    <span>{{ $account->code }} - {{ $account->name }}</span>
                    <span>IDR</span>
                </div>

                <div class="toolbar">
                    <label>Auto-Fill:</label>
                    <input type="text" id="autoFillVal" class="format-number" placeholder="Enter amount...">
                    <button type="button" onclick="applyAutoFill()">Apply</button>
                </div>

                <form action="{{ route('accounting.account-budget.store', $account->id) }}" method="POST">
                    @csrf
                    <table class="erp-table budget-table">
                        <thead>
                            <tr>
                                <th>MONTH</th>
                                <th>LAST YEAR<br><small>({{ $lastYear }})</small></th>
                                <th>THIS YEAR<br><small>({{ $thisYear }})</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach([
                                1 => 'JANUARY', 2 => 'FEBRUARY', 3 => 'MARCH',
                                4 => 'APRIL', 5 => 'MAY', 6 => 'JUNE',
                                7 => 'JULY', 8 => 'AUGUST', 9 => 'SEPTEMBER',
                                10 => 'OCTOBER', 11 => 'NOVEMBER', 12 => 'DECEMBER'
                            ] as $num => $name)
                            <tr>
                                <td>{{ $name }}</td>
                                <td class="table-right" style="color: #666; background: #f9f9f9; padding-right:8px;">
                                    {{ isset($budgetData[$num]['last_year']) ? number_format($budgetData[$num]['last_year'], 2) : '0.00' }}
                                </td>
                                <td>
                                    <input type="text" 
                                           name="budgets[{{ $num }}]" 
                                           class="budget-input format-number" 
                                           value="{{ isset($budgetData[$num]['this_year']) ? number_format($budgetData[$num]['this_year'], 2) : '0.00' }}">
                                </td>
                            </tr>
                            @endforeach
                            <tr class="total-row">
                                <td>TOTAL</td>
                                <td class="table-right" style="padding-right:8px;">{{ number_format($totalLastYear ?? 0, 2) }}</td>
                                <td class="table-right" id="totalThisYear" style="padding-right:8px;">{{ number_format($totalThisYear ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="submit" class="btn-save">💾 Save Budget</button>
                </form>
                @else
                <p>No accounts available.</p>
                @endif
            </div>
        </div>

        <!-- PANE RIGHT: ACCOUNT LIST -->
        <div class="pane-right">
            <div class="pane-header">Account List</div>
            <div class="pane-content" style="padding: 0;">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th>CODE</th>
                            <th>CURR</th>
                            <th>ACCOUNT NAME</th>
                            <th>TYPE</th>
                            <th class="table-center">CTRL. ACC</th>
                            <th class="table-center">MIX. CURR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $acc)
                        <tr class="{{ $account && $account->id === $acc->id ? 'active-row' : '' }}"
                            onclick="window.location.href='{{ route('accounting.account-budget', $acc->id) }}'">
                            <td>{{ $acc->code }}</td>
                            <td>IDR</td> <!-- Assumption from requirements -->
                            <td>{{ $acc->name }}</td>
                            <td>Asset</td> <!-- Placeholder Type -->
                            <td class="table-center"><input type="checkbox" disabled {{ $acc->is_control ? 'checked' : '' }}></td>
                            <td class="table-center"><input type="checkbox" disabled></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = document.querySelectorAll('.budget-input');
        
        // Auto format and sum
        inputs.forEach(input => {
            formatInputValue(input);
            input.addEventListener('blur', function() {
                formatInputValue(this);
                calculateTotal();
            });
            input.addEventListener('focus', function() {
                this.value = this.value.replace(/,/g, '');
            });
            input.addEventListener('input', calculateTotal);
        });

        // Initialize AutoFill input formatting
        const autoFill = document.getElementById('autoFillVal');
        if(autoFill) {
            autoFill.addEventListener('blur', function() { formatInputValue(this); });
            autoFill.addEventListener('focus', function() { this.value = this.value.replace(/,/g, ''); });
        }
    });

    function formatInputValue(el) {
        let val = el.value.replace(/,/g, '');
        if (!isNaN(val) && val !== '') {
            let num = Number(val);
            el.value = num.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }

    function calculateTotal() {
        let sum = 0;
        document.querySelectorAll('.budget-input').forEach(input => {
            let val = input.value.replace(/,/g, '');
            let num = parseFloat(val);
            if(!isNaN(num)) {
                sum += num;
            }
        });
        
        let target = document.getElementById('totalThisYear');
        if (target) {
            target.innerText = sum.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }

    function applyAutoFill() {
        const valInput = document.getElementById('autoFillVal');
        if (!valInput) return;
        
        let rawVal = valInput.value.replace(/,/g, '');
        let numVal = parseFloat(rawVal);
        
        if (isNaN(numVal)) return;

        const formatted = numVal.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        document.querySelectorAll('.budget-input').forEach(input => {
            input.value = formatted;
        });

        calculateTotal();
    }
</script>
@endpush
