@extends('layouts.app')
@section('title', 'Statistic - Cost Center')

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

    /* Grid Table Horizontal Scroll */
    .grid-container { 
        flex: 1; overflow-y: auto; overflow-x: auto; background: #fff; 
    }
    .erp-table { 
        width: 100%; border-collapse: separate; border-spacing: 0; font-size: 12px; min-width: 1600px;
    }
    .erp-table th {
        background: #e9ecef; border: 1px solid #cfcfcf; border-top: none; padding: 6px 8px; text-align: center; font-weight: bold; position: sticky; top: 0; z-index: 2; color: #444; border-bottom: 2px solid #ccc; white-space: nowrap;
    }
    .erp-table td { border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; border-left: 1px solid #e0e0e0; padding: 4px 8px; white-space: nowrap; color: #333; }
    
    .erp-table th:nth-child(1), .erp-table th:nth-child(2) {
        text-align: left; background: #e2e8f0; z-index: 3; position: sticky; left: 0; 
    }
    .erp-table th:nth-child(2) { left: 80px; } /* Depending on width of CODE col */

    .erp-table td:nth-child(1) { text-align: left; font-weight: bold; position: sticky; left: 0; background: #fff; z-index: 1; border-right: none; }
    .erp-table td:nth-child(2) { text-align: left; font-weight: bold; position: sticky; left: 80px; background: #fff; z-index: 1; border-left: none; }

    .erp-table td.money { text-align: right; }
    
    .erp-table tbody tr:hover td { background: #eaf3ff; }

    /* Total Row */
    .erp-table tfoot td {
        background: #f1f5f9; font-weight: bold; color: var(--b-pri); border-top: 2px solid #ccc; position: sticky; bottom: 0;
    }
    .erp-table tfoot td:nth-child(1) {
        left: 0; z-index: 3;
    }
    .erp-table tfoot td:nth-child(2) {
        left: 80px; z-index: 3; text-align: right; border-left: none;
    }

    /* Footer Nav */
    .footer-nav {
        background: #f3f3f3; padding: 6px 15px; border-top: 1px solid #ccc; display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #555;
    }
    .nav-buttons { display: flex; gap: 4px; }
    .nav-buttons button {
        background: transparent; border: none; font-size: 12px; color: var(--b-pri); cursor: pointer; font-weight: bold;
    }
    .nav-buttons button:hover { text-decoration: underline; background: #e2e8f0; border-radius: 2px;}

    .negative { color: #ef4444 !important; }
</style>
@endpush

@section('content')
<div class="erp-container">
    <div class="erp-header">
        <div class="erp-tabs">
            <a href="{{ route('accounting.cost-center.detail') }}"><span>DETAIL COST</span></a>
            <a href="{{ route('accounting.cost-center.list') }}"><span>LIST ALL</span></a>
            <span class="active">STATISTIC</span>
        </div>
    </div>

    <div class="erp-body">
        <div class="group-bar">
            <span>Drag a column header here to group by that column</span>
            <span class="search-icon">🔍</span>
        </div>

        <div class="grid-container">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th style="min-width: 80px; max-width: 80px;">CODE</th>
                        <th style="min-width: 250px;">DESCRIPTION</th>
                        @foreach([
                            'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 
                            'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
                        ] as $m)
                            <th style="width: 100px;">{{ $m }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($statData as $data)
                    <tr onclick="window.location.href='{{ route('accounting.cost-center.detail', $data['id']) }}'" style="cursor: pointer;">
                        <td style="min-width: 80px; max-width: 80px;">{{ $data['code'] }}</td>
                        <td>{{ $data['description'] }}</td>
                        @for($i = 1; $i <= 12; $i++)
                            @php $val = $data['months'][$i]; @endphp
                            <td class="money {{ $val < 0 ? 'negative' : '' }}">{{ number_format($val, 2) }}</td>
                        @endfor
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" style="text-align: center; padding: 30px; font-style: italic; color: #888;">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
                @if(!empty($statData))
                <tfoot>
                    <tr>
                        <td colspan="1" style="min-width: 80px; max-width: 80px; border-right: none;"></td>
                        <td colspan="1" style="text-align: right; padding-right: 15px; border-left: none;"><strong>TOTAL</strong></td>
                        @for($i = 1; $i <= 12; $i++)
                            @php $tval = $monthlyTotals[$i]; @endphp
                            <td class="money {{ $tval < 0 ? 'negative' : '' }}">{{ number_format($tval, 2) }}</td>
                        @endfor
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <div class="footer-nav">
            <div class="nav-buttons">
                <!-- Visual pagination buttons for standard ERP Footer -->
                <button type="button">&lt;&lt; First</button>
                <button type="button">&lt; Prev</button>
            </div>
            <div>
                Record {{ empty($statData) ? '0' : '1' }} of {{ count($statData) }}
            </div>
            <div class="nav-buttons">
                <button type="button">Next &gt;</button>
                <button type="button">Last &gt;&gt;</button>
            </div>
        </div>
    </div>
</div>
@endsection
