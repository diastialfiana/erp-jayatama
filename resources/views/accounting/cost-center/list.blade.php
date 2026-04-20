@extends('layouts.app')
@section('title', 'List All - Cost Center')

@push('styles')
<style>
    :root { --b-pri: #1E3A8A; }
    .erp-container {
        background: #f3f3f3; font-size: 12px; min-height: calc(100vh - 62px);
        display: flex; flex-direction: column; color: #333;
    }
    .erp-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 8px 8px 0 8px; border-bottom: 1px solid #cfcfcf; background: #f3f3f3;
    }
    .erp-tabs { display: flex; gap: 2px; }
    .erp-tabs a { text-decoration: none; color: inherit; }
    .erp-tabs span, .erp-tabs a span {
        display: inline-block; padding: 6px 16px; background: #e2e8f0; color: #475569;
        font-size: 12px; font-weight: 600; text-transform: uppercase; cursor: pointer;
        border: 1px solid #cfcfcf; border-bottom: none; border-top-left-radius: 4px; border-top-right-radius: 4px; user-select: none;
    }
    .erp-tabs span.active { background: var(--b-pri); color: #fff; border-color: var(--b-pri); }
    .erp-tabs a:hover span:not(.active), .erp-tabs span:hover:not(.active) { background: #cbd5e1; }

    .erp-body { flex: 1; display: flex; flex-direction: column; overflow: hidden; padding-bottom: 10px; }

    /* Group Bar */
    .group-bar {
        background: #fff; border-bottom: 1px solid #ccc; padding: 8px 15px; font-style: italic; color: #666;
        display: flex; justify-content: space-between; align-items: center; font-size: 11px;
    }
    .group-bar .search-icon { cursor: pointer; font-style: normal; }

    /* Grid Table */
    .grid-container { flex: 1; overflow-y: auto; background: #fff; }
    .erp-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .erp-table th {
        background: #e9ecef; border: 1px solid #cfcfcf; border-top: none; padding: 6px 10px; text-align: left; font-weight: bold; position: sticky; top: 0; z-index: 2; color: #444; border-bottom: 2px solid #ccc; white-space: nowrap;
    }
    .erp-table td { border: 1px solid #e0e0e0; padding: 4px 10px; white-space: nowrap; color: #333; }
    
    .erp-table tbody tr { cursor: pointer; }
    /* Checkbox column logic & hovering */
    .erp-table tbody tr:hover td { background: #eaf3ff; }
    .erp-table tbody tr:active td { background: #dbeafe; }

    /* Footer Nav */
    .footer-nav {
        background: #f3f3f3; padding: 6px 15px; border-top: 1px solid #ccc; display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #555;
    }
    .nav-buttons { display: flex; gap: 4px; }
    .nav-buttons button {
        background: transparent; border: none; font-size: 12px; color: var(--b-pri); cursor: pointer; font-weight: bold;
    }
    .nav-buttons button:hover { text-decoration: underline; background: #e2e8f0; border-radius: 2px;}
    
    .empty-state { text-align: center; padding: 30px; font-style: italic; color: #888; border: 1px solid #e0e0e0; border-top: none; }
</style>
@endpush

@section('content')
<div class="erp-container">
    <div class="erp-header">
        <div class="erp-tabs">
            <a href="{{ route('accounting.cost-center.detail') }}"><span>DETAIL COST</span></a>
            <span class="active">LIST ALL</span>
            <a href="{{ route('accounting.cost-center.statistic') }}"><span>STATISTIC</span></a>
        </div>
    </div>

    <div class="erp-body">
        <div class="group-bar">
            <span>Drag a column header here to group by that column</span>
            <span class="search-icon">🔍</span>
        </div>

        <div class="grid-container">
            <table class="erp-table" id="costTable">
                <thead>
                    <tr>
                        <th style="width: 15%">CODE</th>
                        <th style="width: 60%">COST CENTER DESCRIPTION</th>
                        <th style="width: 25%">AUDIT</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($costs as $cost)
                    <tr onclick="window.location.href='{{ route('accounting.cost-center.detail', $cost->id) }}'">
                        <td>{{ $cost->code }}</td>
                        <td>{{ $cost->description }}</td>
                        <td>{{ $cost->audit ?? 'PASS' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="empty-state">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer-nav">
            <div class="nav-buttons">
                <button type="button">&lt;&lt; First</button>
                <button type="button">&lt; Prev</button>
            </div>
            <div>
                Record {{ $costs->isEmpty() ? '0' : '1' }} of {{ $costs->count() }}
            </div>
            <div class="nav-buttons">
                <button type="button">Next &gt;</button>
                <button type="button">Last &gt;&gt;</button>
            </div>
        </div>
    </div>
</div>
@endsection
