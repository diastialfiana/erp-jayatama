@extends('layouts.app')
@section('title', 'Activity - Account List')

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

    /* TAB */
    .erp-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 8px 0 8px;
        border-bottom: 1px solid #cfcfcf;
        background: #f3f3f3;
    }
    .erp-tabs { display: flex; gap: 2px; }
    .erp-tabs a { text-decoration: none; color: inherit; }
    .erp-tabs span,
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
    .erp-tabs span.active {
        background: var(--b-pri);
        color: #fff;
        border-color: var(--b-pri);
    }
    .erp-tabs a:hover span:not(.active),
    .erp-tabs span:hover:not(.active) { background: #cbd5e1; }

    /* FILTER BAR */
    .erp-filter-bar {
        padding: 6px;
        background: #f8f8f8;
        border-bottom: 1px solid #ccc;
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .erp-filter-bar input[type="text"],
    .erp-filter-bar input[type="date"] {
        padding: 4px 6px;
        border: 1px solid #ccc;
        font-size: 12px;
        background: #fff;
        outline: none;
    }
    .erp-filter-bar input[readonly] { background: #f1f5f5; color: #495057; }
    .erp-filter-bar button {
        padding: 4px 8px;
        border: 1px solid #ccc;
        background: #e9ecef;
        cursor: pointer;
        font-size: 12px;
        line-height: 1;
    }
    .erp-filter-bar button:hover { background: #dfe3e7; }

    .erp-box { background: #fff; border: 1px solid #ccc; border-radius: 2px; }

    .group-bar {
        padding: 6px 10px;
        color: #555;
        font-style: italic;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f9f9f9;
        border-bottom: 1px solid #ccc;
        margin: 10px;
    }
    .search-box { font-style: normal; cursor: pointer; }

    .grid-container {
        margin: 0 10px 10px 10px;
        overflow: auto;
        max-height: calc(100vh - 62px - 44px - 44px - 54px); /* navbar + tabs + filter + footer approx */
    }

    .erp-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .erp-table th {
        background: #e9ecef;
        border: 1px solid #ccc;
        padding: 4px;
        text-align: left;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .erp-table td {
        border: 1px solid #ddd;
        padding: 4px;
        white-space: nowrap;
    }
    .erp-table tr:hover { background: #eaf3ff; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }

    .erp-footer {
        padding: 10px;
        background: #f3f3f3;
        border-top: 1px solid #cfcfcf;
        font-size: 12px;
        color: #555;
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }
    .footer-nav {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .footer-nav span {
        padding: 2px 6px;
        border: 1px solid transparent;
        cursor: default;
    }
</style>
@endpush

@section('content')
<div class="erp-container">

    <!-- TAB -->
    <div class="erp-header">
        <div class="erp-tabs">
            <a href="{{ route('accounting.account-list.detail', $account->id) }}"><span>RECORD DETAIL</span></a>
            <a href="{{ route('accounting.account-list.index') }}"><span>RECORDS LIST</span></a>
            <a href="{{ route('accounting.account-list.statistics', $account->id) }}"><span>STATISTICS</span></a>
            <span class="active">ACTIVITY</span>
            <a href="{{ route('accounting.account-list.backdate', $account->id) }}"><span>BACKDATE</span></a>
            <a href="{{ route('accounting.account-list.summary', $account->id) }}"><span>SUMMARY</span></a>
        </div>
    </div>

    <!-- FILTER BAR -->
    <form class="erp-filter-bar" method="GET" action="{{ route('accounting.account-list.activity', $account->id) }}">
        Code
        <input type="text" value="{{ $account->code }}" readonly>
        <input type="text" value="IDR" readonly>
        <input type="text" value="{{ $account->name }}" readonly style="min-width: 260px; flex: 1;">

        <span style="margin-left: 6px;">From</span>
        <input type="date" name="from" value="{{ $from }}">

        <span>Thru</span>
        <input type="date" name="thru" value="{{ $thru }}">

        <button type="submit">🔄</button>
    </form>

    <!-- GROUP BAR -->
    <div class="erp-box group-bar">
        Drag a column header here to group by that column
        <div class="search-box">🔍</div>
    </div>

    <!-- GRID -->
    <div class="erp-box grid-container">
        <table class="erp-table">
            <thead>
                <tr>
                    <th>DATE</th>
                    <th>USER NO</th>
                    <th>NOTE</th>
                    <th>DEBIT</th>
                    <th>CREDIT</th>
                    <th>BALANCE</th>
                    <th>REF</th>
                    <th>CURR</th>
                    <th>RATE</th>
                    <th>LINK</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $tx)
                    <tr>
                        <td>{{ optional($tx->date)->format('Y-m-d') }}</td>
                        <td>{{ $tx->user_no }}</td>
                        <td>{{ $tx->note }}</td>
                        <td class="text-right">{{ ($tx->debit ?? 0) != 0 ? number_format($tx->debit, 2) : '' }}</td>
                        <td class="text-right">{{ ($tx->credit ?? 0) != 0 ? number_format($tx->credit, 2) : '' }}</td>
                        <td class="text-right">{{ number_format($tx->running_balance ?? 0, 2) }}</td>
                        <td>{{ $tx->ref }}</td>
                        <td class="text-center">{{ $tx->currency }}</td>
                        <td class="text-right">{{ $tx->rate !== null ? number_format($tx->rate, 6) : '' }}</td>
                        <td>{{ $tx->link }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 10px;">No activity data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="erp-footer">
        <div class="footer-nav">
            <span>&lt;&lt;</span>
            <span>&lt;</span>
            <span>Record {{ $transactions->count() ? 1 : 0 }} of {{ $transactions->count() }}</span>
            <span>&gt;</span>
            <span>&gt;&gt;</span>
        </div>
    </div>

</div>
@endsection

