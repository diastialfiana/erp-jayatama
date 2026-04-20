@extends('layouts.app')
@section('title', 'Statistics - Account Budget')

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

    .erp-tabs { display: flex; gap: 2px; }
    .erp-tabs span {
        display: inline-block; padding: 6px 16px; background: #e2e8f0; color: #475569;
        font-size: 12px; font-weight: 600; text-transform: uppercase; cursor: pointer;
        border: 1px solid #cfcfcf; border-bottom: none; border-top-left-radius: 4px;
        border-top-right-radius: 4px; user-select: none;
    }
    .erp-tabs span.active { background: var(--b-pri); color: #fff; border-color: var(--b-pri); }
    .erp-tabs span:hover:not(.active) { background: #cbd5e1; }

    /* BODY */
    .erp-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }

    .account-selector {
        width: 1000px;
        margin-bottom: 15px;
        background: #fff;
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .account-selector select {
        padding: 4px 6px;
        font-size: 13px;
        border: 1px solid #ccc;
        outline: none;
        width: 300px;
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
        flex: 3;
    }

    .statistik-chart {
        flex: 2;
        padding: 10px;
        height: 380px;
        background: #fff;
        border: 1px solid #ccc;
    }

    .statistik-box {
        width: 100%;
        background: #fff;
        border: 1px solid #ccc;
        padding: 15px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .stat-header {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .stat-header label { font-weight: bold; color: var(--b-pri); font-size: 14px;}

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

    .erp-stat-table tr.total-row td {
        background: #f1f5f9;
        font-weight: bold;
        color: var(--b-pri);
        border-top: 2px solid #ccc;
    }

    .erp-stat-table tbody tr:hover td:not(:first-child) {
        background: #eaf3ff;
    }
    
    .negative { color: #ef4444 !important; }

</style>
@endpush

@section('content')
<div class="erp-container">

    <!-- TAB -->
    <div class="erp-header">
        <div class="erp-tabs">
            <span onclick="window.location.href='{{ route('accounting.account-budget', $account->id ?? '') }}'">DETAIL VIEW</span>
            <span class="active">STATISTICS</span>
        </div>
    </div>

    <!-- BODY -->
    <div class="erp-body">

        <!-- ACCOUNT SELECTOR -->
        <div class="account-selector">
            <strong>Current Account:</strong>
            <select onchange="window.location.href=this.value">
                @foreach($accounts as $acc)
                    <option value="{{ route('accounting.account-budget.statistics', $acc->id) }}" {{ $account && $account->id == $acc->id ? 'selected' : '' }}>
                        {{ $acc->code }} - {{ $acc->name }}
                    </option>
                @endforeach
            </select>
            <span style="margin-left: auto; color: #666;">Currency: IDR</span>
        </div>

        <div class="statistik-wrapper">

            <!-- LEFT: TABLE -->
            <div class="statistik-table">
                <div class="statistik-box">

                    <div class="stat-header">
                        <label>Account Budget & Actual Analysis ({{ $thisYear }})</label>
                    </div>

                    <table class="erp-stat-table">
                        <thead>
                            <tr>
                                <th rowspan="2">MONTH</th>
                                <th rowspan="2">BALANCE</th>
                                <th colspan="2">BUDGET</th>
                                <th rowspan="2">REALITIES</th>
                            </tr>
                            <tr>
                                <th>LAST YEAR<br><small>({{ $lastYear }})</small></th>
                                <th>THIS YEAR<br><small>({{ $thisYear }})</small></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($statData as $num => $data)
                            <tr>
                                <td>{{ $data['name'] }}</td>
                                <td class="{{ $data['balance'] < 0 ? 'negative' : '' }}">{{ number_format($data['balance'], 2) }}</td>
                                <td class="{{ $data['last_year'] < 0 ? 'negative' : '' }}">{{ number_format($data['last_year'], 2) }}</td>
                                <td class="{{ $data['this_year'] < 0 ? 'negative' : '' }}">{{ number_format($data['this_year'], 2) }}</td>
                                <td class="{{ $data['realities'] < 0 ? 'negative' : '' }}">{{ number_format($data['realities'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td>TOTAL</td>
                                <td><!-- Balance is cumulative, summing it usually doesn't make sense -->-</td>
                                <td>{{ number_format($totalLastYear, 2) }}</td>
                                <td>{{ number_format($totalThisYear, 2) }}</td>
                                <td>{{ number_format($totalRealities, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>

            <!-- RIGHT: CHART -->
            <div class="statistik-chart erp-box">
                <canvas id="budgetChart"></canvas>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('budgetChart');

    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    'Jan','Feb','Mar','Apr','May','Jun',
                    'Jul','Aug','Sep','Oct','Nov','Dec'
                ],
                datasets: [
                    {
                        label: 'Budget ({{ $thisYear }})',
                        data: {!! $chartThisYear !!},
                        borderWidth: 2,
                        borderColor: '#1E3A8A',
                        backgroundColor: 'rgba(30, 58, 138, 0.1)',
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'Realities ({{ $thisYear }})',
                        data: {!! $chartRealities !!},
                        borderWidth: 2,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'IDR' }).format(context.parsed.y).replace('IDR', '').trim();
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                if(value >= 1000000) return (value / 1000000) + 'M';
                                if(value >= 1000) return (value / 1000) + 'K';
                                return value;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
