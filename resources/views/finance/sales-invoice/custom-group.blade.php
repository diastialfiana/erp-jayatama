@extends('layouts.app')
@section('title', 'Sales Invoice - Custom Group')

@push('styles')
<style>
    :root { --blue:#2563EB; --navy:#1E3A8A; --bg:#F8FAFC; }

    .page-fade { animation: pgIn .4s ease both; }
    @keyframes pgIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

    .tab-link { color:#64748b;text-decoration:none;padding-bottom:8px;margin-bottom:-9px;white-space:nowrap;font-size:13.5px;transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB;font-weight:700;border-bottom:2.5px solid #2563EB; }

    /* ERP Box */
    .erp-box { background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 4px 18px rgba(30,58,138,.07); margin-bottom: 20px;}
    
    /* Toolbar Top */
    .filter-toolbar { display:flex; justify-content:space-between; align-items:center; padding:12px 18px; border-bottom:1px solid #f1f5f9; background:#fff;}
    .erp-select { background:#fff; border:1px solid #cbd5e1; border-radius:6px; padding:6px 36px 6px 12px; font-size:13px; font-weight:600; color:#1e293b; outline:none; appearance:none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 10px center; background-size: 14px; cursor:pointer;}
    .erp-select:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(37,99,235,.12); }
    .search-btn { background:transparent; border:none; color:#64748b; cursor:pointer; display:flex; align-items:center; padding:6px; border-radius:6px; transition:0.2s;}
    .search-btn:hover { background:#f1f5f9; color:#1e293b; }

    /* Table */
    .erp-table { width:100%; table-layout:auto; border-collapse:collapse; min-width:1000px; }
    .erp-table th { font-size:10px; font-weight:700; color:#64748b; letter-spacing:0.05em; text-transform:uppercase; padding:8px 12px; border-bottom:2px solid #e2e8f0; text-align:left; background:#f8fafc; position:sticky; top:0; z-index:10;}
    
    /* Group Header Row */
    .group-header { background:#f1f5f9; cursor:pointer; transition:0.2s; user-select:none; }
    .group-header:hover { background:#e2e8f0; }
    .group-header td { padding:8px 12px; font-size:12.5px; font-weight:700; color:#1e293b; border-bottom:1px solid #cbd5e1; border-top:1px solid #cbd5e1;}
    .group-icon { display:inline-flex; width:20px; height:20px; align-items:center; justify-content:center; background:#fff; border-radius:4px; border:1px solid #cbd5e1; margin-right:8px; font-size:14px; line-height:1; transition:transform 0.2s;}
    .group-collapsed .group-icon { transform: rotate(-90deg); }
    
    /* Data Row */
    .data-row td { padding:7px 12px; font-size:11.5px; border-bottom:1px solid #f1f5f9; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:#334155; }
    .data-row:hover td { background:#f8fafc; }

    .align-r { text-align:right !important; }
    .mono { font-variant-numeric:tabular-nums; }

    .scroll-y { overflow-y:auto; max-height:550px; }
    .scroll-y::-webkit-scrollbar { width:6px; height:6px; }
    .scroll-y::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }

    /* Bottom Action Bar */
    .bottom-bar { background:#f8fafc; border-top:1px solid #e2e8f0; padding:12px 18px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;}
    .date-filter { display:flex; align-items:center; gap:8px; font-size:12.5px; font-weight:600; color:#475569; }
    .date-input { border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; font-size:12.5px; color:#1e293b; outline:none; }
    .date-input:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(37,99,235,.12); }
    .btn-search { background:#1e293b; color:#fff; border:none; padding:7px 16px; border-radius:6px; font-size:12.5px; font-weight:600; cursor:pointer; transition:0.2s; }
    .btn-search:hover { background:var(--blue); }
    .btn-export { background:#10b981; color:#fff; border:none; padding:7px 16px; border-radius:6px; font-size:12.5px; font-weight:600; cursor:pointer; transition:0.2s; display:flex; align-items:center; gap:6px;}
    .btn-export:hover { background:#059669; }

    /* Totals Box */
    .summary-box { display:flex; gap:24px; align-items:center; }
    .summary-item { text-align:right;}
    .summary-val { font-size:16px; font-weight:800; color:#1e3a8a; }
    .summary-lbl { font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; }
</style>
@endpush

@section('content')
@php
    $totalRecords = 0;
    $grandTotalAmt = 0;
@endphp

<div class="page-fade" style="background:var(--bg);min-height:calc(100vh - 62px);padding:24px;">
<div style="width:100%; max-width:1400px; margin:0 auto;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:14px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <a href="{{ route('finance.index') }}" style="color:#94a3b8;text-decoration:none;">Finance</a>
        <span>/</span>
        <span style="color:#2563EB;font-weight:600;">Sales Invoice</span>
    </div>

    {{-- PAGE HEADER --}}
    <div style="display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:18px;">
        <div>
            <h1 style="font-size:26px;font-weight:800;margin:0 0 3px;color:#1e293b;display:flex;align-items:center;gap:10px;">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1E3A8A,#2563EB);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                </span>
                Custom Group
            </h1>
            <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">View detailed invoice data analytically filtered and grouped.</p>
        </div>
    </div>

    {{-- TAB BAR --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.sales-invoice.index') }}" class="tab-link">Record Detail</a>
        <a href="{{ route('finance.sales-invoice.records-list') }}" class="tab-link">Records List</a>
        <a href="{{ route('finance.sales-invoice.custom-group') }}" class="tab-link active">Custom Group</a>
        <a href="{{ route('finance.sales-invoice.detail-list') }}" class="tab-link">Detail Invoice List</a>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="erp-box">
        
        <form method="GET" action="{{ route('finance.sales-invoice.custom-group') }}" id="filterForm">
            {{-- Toolbar Top --}}
            <div class="filter-toolbar">
                <div>
                    <select name="group_by" class="erp-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="product" {{ $groupBy == 'product' ? 'selected' : '' }}>PRODUCT NAME</option>
                        <option value="customer" {{ $groupBy == 'customer' ? 'selected' : '' }}>CUSTOMER NAME</option>
                        <option value="business_unit" {{ $groupBy == 'business_unit' ? 'selected' : '' }}>BUSINESS UNIT</option>
                    </select>
                    <button type="button" class="btn-export" style="display:inline-flex; margin-left:12px; font-size:11.5px; padding:6px 12px;" onclick="expandAll()">Expand All</button>
                    <button type="button" class="btn-search" style="display:inline-flex; margin-left:4px; font-size:11.5px; padding:6px 12px; background:#64748b" onclick="collapseAll()">Collapse All</button>
                </div>
                <div>
                    <button type="button" class="search-btn" title="Quick Search">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="scroll-y">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th style="width:120px;">DATE</th>
                            <th style="width:250px;">CUSTOMER NAME</th>
                            <th style="width:150px;">BUSINESS UNIT</th>
                            <th style="width:auto;">DESCRIPTION</th>
                            <th style="width:150px;">INVOICE</th>
                            <th style="width:140px;" class="align-r">AMOUNT</th>
                        </tr>
                    </thead>
                    
                    @foreach($grouped as $groupName => $items)
                        @php
                            $groupCount = $items->count();
                            $groupTotal = $items->sum('amount');
                            
                            $totalRecords += $groupCount;
                            $grandTotalAmt += $groupTotal;
                            
                            $groupId = 'grp_' . md5($groupName);
                        @endphp
                        {{-- Group Header --}}
                        <tbody>
                            <tr class="group-header" onclick="toggleGroup('{{ $groupId }}', this)">
                                <td colspan="6">
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <div style="display:flex; align-items:center;">
                                            <span class="group-icon">−</span>
                                            <span style="color:#64748b; margin-right:8px;">{{ strtoupper(str_replace('_', ' ', $groupBy)) }}:</span>
                                            <span style="color:#2563EB;">{{ $groupName }}</span>
                                            <span style="margin-left:8px; font-size:11px; font-weight:600; color:#94a3b8; background:#e2e8f0; padding:2px 6px; border-radius:12px;">{{ $groupCount }} items</span>
                                        </div>
                                        <div class="mono" style="color:#0f172a;">
                                            Rp {{ number_format($groupTotal, 2) }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        
                        {{-- Group Details --}}
                        <tbody id="{{ $groupId }}" class="group-content">
                            @foreach($items as $row)
                            <tr class="data-row">
                                <td>{{ $row->salesInvoice && $row->salesInvoice->date ? \Carbon\Carbon::parse($row->salesInvoice->date)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $row->salesInvoice && $row->salesInvoice->customer ? $row->salesInvoice->customer->name : '-' }}</td>
                                <td>{{ $row->salesInvoice && $row->salesInvoice->business_unit ? $row->salesInvoice->business_unit : '-' }}</td>
                                <td>{{ $row->description ?: '-' }}</td>
                                <td style="font-weight:600; color:#475569;">{{ $row->salesInvoice ? $row->salesInvoice->invoice_number : '-' }}</td>
                                <td class="align-r mono">{{ number_format($row->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    @endforeach

                    @if(count($grouped) === 0)
                    <tbody>
                        <tr>
                            <td colspan="6" style="text-align:center; padding:40px; color:#94a3b8; font-size:13px;">No data found for the selected date range.</td>
                        </tr>
                    </tbody>
                    @endif
                </table>
            </div>

            {{-- Bottom Toolbar --}}
            <div class="bottom-bar">
                <div class="date-filter">
                    <span>Select Date</span>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="date-input">
                    <span>Thru</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="date-input">
                    <button type="submit" class="btn-search">Search</button>
                    <button type="button" class="btn-export" onclick="alert('Export processing...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg> Excel</button>
                </div>
                
                <div class="summary-box">
                    <div class="summary-item">
                        <div class="summary-lbl">Records</div>
                        <div class="summary-val mono">{{ number_format($totalRecords) }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-lbl">Total Amount</div>
                        <div class="summary-val mono">Rp {{ number_format($grandTotalAmt, 2) }}</div>
                    </div>
                </div>
            </div>
            
            {{-- Navigation Bar Fake Mimic --}}
            <div style="background:#f1f5f9; padding:6px 18px; border-top:1px solid #e2e8f0;">
                <div style="display:flex;align-items:center;gap:4px; font-size:11.5px; color:#64748b; font-weight:600;">
                    <span>«</span> <span>‹</span>
                    <span style="margin:0 12px;">Record 1 of {{ $totalRecords > 0 ? 1 : 0 }}</span>
                    <span>›</span> <span>»</span>
                </div>
            </div>

        </form>
    </div>

</div>
</div>

<script>
    function toggleGroup(groupId, headerEl) {
        const content = document.getElementById(groupId);
        if (content.style.display === 'none') {
            content.style.display = '';
            headerEl.classList.remove('group-collapsed');
            headerEl.querySelector('.group-icon').innerText = '−';
        } else {
            content.style.display = 'none';
            headerEl.classList.add('group-collapsed');
            headerEl.querySelector('.group-icon').innerText = '+';
        }
    }

    function collapseAll() {
        document.querySelectorAll('.group-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.group-header').forEach(el => {
            el.classList.add('group-collapsed');
            el.querySelector('.group-icon').innerText = '+';
        });
    }

    function expandAll() {
        document.querySelectorAll('.group-content').forEach(el => el.style.display = '');
        document.querySelectorAll('.group-header').forEach(el => {
            el.classList.remove('group-collapsed');
            el.querySelector('.group-icon').innerText = '−';
        });
    }
</script>
@endsection
