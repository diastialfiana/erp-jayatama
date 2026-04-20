@extends('layouts.app')
@section('title', 'Backdate - Account List')

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

    /* FILTER BAR (WAJIB) */
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
    .erp-filter-bar input[readonly] {
        background: #f1f5f5;
        color: #495057;
    }
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

    .erp-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .erp-table th {
        background: #e9ecef;
        border: 1px solid #ccc;
        padding: 4px;
        text-align: left;
        white-space: nowrap;
    }
    .erp-table td {
        border: 1px solid #ddd;
        padding: 4px;
        white-space: nowrap;
    }
    .text-right { text-align: right; }
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
            <a href="{{ route('accounting.account-list.activity', $account->id) }}"><span>ACTIVITY</span></a>
            <span class="active">BACKDATE</span>
            <a href="{{ route('accounting.account-list.summary', $account->id) }}"><span>SUMMARY</span></a>
        </div>
    </div>

    <!-- FILTER BAR -->
    <form class="erp-filter-bar" method="GET" action="{{ route('accounting.account-list.backdate', $account->id) }}">
        Code
        <input type="text" value="{{ $account->code }}" readonly>

        <input type="text" value="IDR" readonly>

        <input type="text" value="{{ $account->name }}" readonly style="min-width: 260px; flex: 1;">

        <input type="date" name="date" value="{{ request('date') ?? now()->toDateString() }}">

        <button type="submit">🔄</button>
    </form>

    <!-- GROUP BAR -->
    <div class="erp-box group-bar">
        Drag a column header here to group by that column
        <div class="search-box">🔍</div>
    </div>

    <!-- GRID -->
    <div class="erp-box" style="margin: 0 10px 10px 10px;">
        <table class="erp-table">
            <thead>
                <tr>
                    <th>CODE</th>
                    <th>CURR</th>
                    <th>ACCOUNT NAME</th>
                    <th>BALANCE</th>
                    <th>[ BALANCE ]</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>{{ $account->code }}</td>
                    <td>IDR</td>
                    <td>{{ $account->name }}</td>

                    <!-- CURRENT BALANCE -->
                    <td class="text-right">
                        {{ number_format($currentBalance, 2) }}
                    </td>

                    <!-- BACKDATE BALANCE -->
                    <td class="text-right">
                        {{ number_format($backdateBalance, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
