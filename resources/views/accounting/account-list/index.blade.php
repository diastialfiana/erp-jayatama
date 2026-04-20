@extends('layouts.app')
@section('title', 'Records List - Account List')

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

    .erp-tabs a:hover span:not(.active) {
        background: #cbd5e1;
    }
    
    .erp-tabs span:hover:not(.active) {
        background: #cbd5e1;
    }

    /* BODY */
    .erp-body {
        padding: 10px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .erp-box {
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 2px;
    }

    .group-bar {
        padding: 6px 10px;
        color: #555;
        font-style: italic;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        background: #f9f9f9;
        border-bottom: 1px solid #ccc;
    }

    .search-box {
        font-style: normal;
        cursor: pointer;
    }

    /* GRID STYLES */
    .grid-container {
        overflow: auto;
        max-height: 500px;
    }

    .erp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

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

    .erp-table tr:hover {
        background: #eaf3ff;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .text-success {
        color: green;
    }

    .negative {
        color: red;
    }

    /* FOOTER */
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

    .footer-nav a, .footer-nav span {
        text-decoration: none;
        color: inherit;
        padding: 2px 6px;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .footer-nav a:hover {
        background: #e2e8f0;
        border: 1px solid #ccc;
    }
</style>
@endpush

@section('content')
<div class="erp-container">

    <!-- TAB -->
    <div class="erp-header">
        <div class="erp-tabs">
            <a href="{{ route('accounting.account-list.detail', 1) }}">
                <span>RECORD DETAIL</span>
            </a>
            <span class="active">RECORDS LIST</span>
            <a href="{{ route('accounting.account-list.statistics', 1) }}">
                <span>STATISTICS</span>
            </a>
            <a href="{{ route('accounting.account-list.activity', 1) }}"><span>ACTIVITY</span></a>
            <a href="{{ route('accounting.account-list.backdate', 1) }}"><span>BACKDATE</span></a>
            <span>SUMMARY</span>
        </div>
    </div>

    <!-- BODY -->
    <div class="erp-body">

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
                        <th>BRANCH</th>
                        <th>CODE</th>
                        <th>CURR</th>
                        <th>ACCOUNT NAME</th>
                        <th>[ BALANCE ]</th>
                        <th>ACC. TYPE</th>
                        <th>MIX CURR.</th>
                        <th>CTRL. ACC.</th>
                        <th>BALANCE</th>
                        <th>AUDIT</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($accounts as $acc)
                    <tr>
                        <td>001</td>
                        <td>{{ $acc->code }}</td>
                        <td>IDR</td>
                        <td>{{ $acc->name }}</td>
                        <td class="text-right {{ $acc->balance < 0 ? 'negative' : '' }}">
                            {{ number_format($acc->balance, 2) }}
                        </td>
                        <td>{{ ucfirst($acc->type ?? '') }}</td>
                        <td class="text-center"><input type="checkbox" disabled></td>
                        <td class="text-center"><input type="checkbox" disabled {{ $acc->is_control ? 'checked' : '' }}></td>
                        <td class="text-right {{ $acc->balance < 0 ? 'negative' : '' }}">
                            {{ number_format($acc->balance, 2) }}
                        </td>
                        <td class="text-success text-center">PASS</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 10px;">No data available</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="erp-footer">
        <div class="footer-nav">
            @if ($accounts->onFirstPage())
                <span>&lt;&lt;</span>
                <span>&lt;</span>
            @else
                <a href="{{ $accounts->url(1) }}">&lt;&lt;</a>
                <a href="{{ $accounts->previousPageUrl() }}">&lt;</a>
            @endif
            
            <span>Record {{ $accounts->firstItem() ?? 0 }} of {{ $accounts->total() }}</span>
            
            @if ($accounts->hasMorePages())
                <a href="{{ $accounts->nextPageUrl() }}">&gt;</a>
                <a href="{{ $accounts->url($accounts->lastPage()) }}">&gt;&gt;</a>
            @else
                <span>&gt;</span>
                <span>&gt;&gt;</span>
            @endif
        </div>
    </div>

</div>
@endsection
