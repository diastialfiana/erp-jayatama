@extends('layouts.app')
@section('title', 'Statistics - Account List')

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

    .erp-tabs a {
        text-decoration: none;
        color: inherit;
    }

    .erp-tabs a span {
        display: inline-block;
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

    .erp-tabs a:hover span:not(.active),
    .erp-tabs span:hover:not(.active) {
        background: #cbd5e1;
    }

    /* BODY */
    .erp-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }

    /* MINI SUMMARY */
    .mini-summary {
        width: 1000px;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-bottom: 15px;
        padding: 10px 15px;
        font-size: 14px;
        font-weight: bold;
        color: var(--b-pri);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mini-summary .label {
        color: #555;
        font-size: 12px;
        font-weight: normal;
        text-transform: uppercase;
    }

    .erp-box {
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 2px;
    }

    .statistik-wrapper {
        display: flex;
        gap: 15px;
        width: 1000px;
        margin: 0 auto;
    }

    .statistik-table {
        width: 60%;
    }

    .statistik-chart {
        width: 40%;
        padding: 10px;
        height: 300px;
        background: #fff;
        border: 1px solid #ccc;
    }

    .statistik-box {
        width: 100%;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #ccc;
        padding: 15px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
    }
    
    .stat-header label {
        font-weight: bold;
        margin-right: 4px;
    }

    .stat-header input {
        padding: 5px 8px;
        border: 1px solid #ccc;
        font-size: 12px;
        background: #f8f9fa;
        color: #333;
        outline: none;
    }
    
    .stat-header input.wide {
        flex: 1;
    }

    .erp-stat-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .erp-stat-table th {
        background: #dee2e6;
        border: 1px solid #ccc;
        padding: 6px;
        text-align: center;
        white-space: nowrap;
        font-weight: bold;
    }

    .erp-stat-table td {
        border: 1px solid #ddd;
        padding: 6px;
        text-align: right;
        white-space: nowrap;
    }

    .erp-stat-table td:first-child {
        text-align: left;
        font-weight: bold;
        color: #444;
        background: #fafafa;
    }

    /* highlight penting */
    .current-row td {
        background: #f1f5f9 !important;
        font-weight: bold;
        color: var(--b-pri);
        border-bottom: 2px solid #ccc;
    }

    .erp-stat-table tr:nth-child(2) td {
        background: #f8fafc;
        font-weight: bold;
    }

    .erp-stat-table tbody tr:hover td:not(:first-child) {
        background: #eaf3ff;
    }
    
    .negative {
        color: #ef4444 !important;
    }

</style>
@endpush

@section('content')
<div class="erp-container">

    <!-- TAB -->
    <div class="erp-header">
        <div class="erp-tabs">
            <a href="{{ route('accounting.account-list.detail', $account->id) }}">
                <span>RECORD DETAIL</span>
            </a>
            <a href="{{ route('accounting.account-list.index') }}">
                <span>RECORDS LIST</span>
            </a>
            <span class="active">STATISTICS</span>
            <a href="{{ route('accounting.account-list.activity', $account->id) }}"><span>ACTIVITY</span></a>
            <a href="{{ route('accounting.account-list.backdate', $account->id) }}"><span>BACKDATE</span></a>
            <a href="{{ route('accounting.account-list.summary', $account->id) }}"><span>SUMMARY</span></a>
        </div>
    </div>

    <!-- BODY -->
    <div class="erp-body">

        <!-- MINI SUMMARY -->
        <div class="mini-summary">
            <div>
                <span class="label">Total Current Balance</span>
                <div class="{{ $account->balance < 0 ? 'negative' : '' }}">
                    IDR {{ number_format($account->balance, 2) }}
                </div>
            </div>
            <div>
                <span class="label">Total Budget Year</span>
                <div>
                    IDR 120,000,000.00
                </div>
            </div>
        </div>

        <div class="statistik-wrapper">

            <!-- LEFT: TABLE -->
            <div class="statistik-table">
                <div class="erp-form statistik-box">

            <!-- HEADER INFO -->
            <div class="stat-header">
                <label>Code</label>
                <input type="text" value="{{ $account->code }}" readonly style="width: 120px;">
                <input type="text" value="IDR" readonly style="width: 50px; text-align:center;">
                <input type="text" value="{{ $account->name }}" readonly class="wide">
            </div>

            <!-- TABLE -->
            <table class="erp-stat-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>[ BALANCE ]</th>
                        <th>BALANCE</th>
                        <th>[ LAST YEAR ]</th>
                        <th>[ BUDGET ]</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="current-row">
                        <td>CURRENT</td>
                        <td class="{{ $account->balance < 0 ? 'negative' : '' }}">{{ number_format($account->balance, 2) }}</td>
                        <td class="{{ $account->balance < 0 ? 'negative' : '' }}">{{ number_format($account->balance, 2) }}</td>
                        <td>13,183,125,282.30</td>
                        <td>0.00</td>
                    </tr>

                    <tr>
                        <td>BEG. BALANCE</td>
                        <td class="{{ $account->balance < 0 ? 'negative' : '' }}">{{ number_format($account->balance, 2) }}</td>
                        <td class="{{ $account->balance < 0 ? 'negative' : '' }}">{{ number_format($account->balance, 2) }}</td>
                        <td>13,183,125,282.30</td>
                        <td>0.00</td>
                    </tr>

                    @foreach($months as $month)
                    @php
                        $ly = rand(1000000000,9000000000);
                        $bg = 0.00;
                    @endphp
                    <tr>
                        <td>{{ strtoupper($month) }}</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>{{ number_format($ly, 2) }}</td>
                        <td>{{ number_format($bg, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

                </div>
            </div>

            <!-- RIGHT: CHART -->
            <div class="statistik-chart erp-box">
                <canvas id="balanceChart"></canvas>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('balanceChart');

    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    'Jan','Feb','Mar','Apr','May','Jun',
                    'Jul','Aug','Sep','Oct','Nov','Dec'
                ],
                datasets: [{
                    label: 'Balance Trend',
                    data: @json($monthlyData),
                    borderWidth: 2,
                    borderColor: '#1E3A8A',
                    backgroundColor: 'rgba(30, 58, 138, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
@endpush
