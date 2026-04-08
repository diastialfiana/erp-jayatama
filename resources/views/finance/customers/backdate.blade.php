@extends('layouts.app')
@section('title', 'Backdate – Customer Finance')

@push('styles')
<style>
    :root { --blue:#2563EB; --navy:#1E3A8A; --accent:#4F46E5; --bg:#F8FAFC; }

    .page-fade { animation: pgIn .4s ease both; }
    @keyframes pgIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

    .tab-link { color:#64748b; text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; font-size:13.5px; transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB; font-weight:700; border-bottom:2.5px solid #2563EB; }

    /* Grid table */
    .grid-table { width:100%; border-collapse:collapse; min-width:900px; }
    .grid-table thead tr { background:#f8fafc; }
    .grid-table th {
        font-size:10.5px; font-weight:700; color:#64748b; letter-spacing:.07em;
        text-transform:uppercase; padding:12px 14px; border-bottom:2px solid #e2e8f0;
        border-right:1px solid #f1f5f9; cursor:pointer; white-space:nowrap;
        user-select:none; transition:background .15s; position:sticky; top:0; z-index:5;
    }
    .grid-table th:last-child { border-right:none; }
    .grid-table th:hover { background:#eff6ff; }
    .grid-table th .sort-ico { opacity:0; transition:opacity .15s; }
    .grid-table th:hover .sort-ico { opacity:1; }
    .grid-table td { padding:11px 14px; font-size:13px; border-bottom:1px solid #f1f5f9; border-right:1px solid #f8fafc; white-space:nowrap; }
    .grid-table td:last-child { border-right:none; }
    .grid-table tbody tr { cursor:pointer; transition:background .13s; }
    .grid-table tbody tr:hover td { background:#f8fafc; }
    .grid-table tbody tr.row-active td { background:#dbeafe; }
    .grid-table tbody tr.row-active:hover td { background:#bfdbfe; }
    .grid-table tbody tr:last-child td { border-bottom:none; }

    /* Group zone */
    .group-zone {
        display:flex; align-items:center; gap:10px;
        background:#f1f5f9; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;
        padding:9px 16px; min-height:40px;
    }

    .scroll-body { overflow-y:auto; max-height:430px; }
    .scroll-body::-webkit-scrollbar { width:5px; }
    .scroll-body::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }

    .filter-input {
        display:flex; align-items:center;
        background:#fff; border:1px solid #e2e8f0; border-radius:10px;
        overflow:hidden; transition:box-shadow .2s;
    }
    .filter-input:focus-within { box-shadow:0 0 0 3px rgba(37,99,235,.15); border-color:#2563EB; }
    .filter-input input { border:none; outline:none; background:transparent; padding:8px 10px; font-size:13px; color:#1e293b; min-width:130px; }
    .filter-input .fi-icon { padding:8px 10px 8px 12px; color:#94a3b8; }

    .btn-search {
        display:flex; align-items:center; gap:7px;
        background:linear-gradient(135deg,#1E3A8A,#2563EB);
        color:#fff; font-size:13px; font-weight:600; border:none;
        border-radius:10px; padding:9px 20px; cursor:pointer; transition:opacity .2s,transform .15s;
    }
    .btn-search:hover { opacity:.92; transform:translateY(-1px); }

    .mono { font-family:'Courier New',monospace; }
    .badge-curr { font-size:10px; font-weight:700; background:#eff6ff; color:#2563EB; padding:2px 8px; border-radius:20px; display:inline-block; }
</style>
@endpush

@section('content')
<div class="page-fade" style="background:var(--bg);min-height:calc(100vh - 62px);padding:28px 24px;">
<div style="max-width:1340px;margin:0 auto;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:14px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <a href="{{ route('finance.customers.index') }}" style="color:#94a3b8;text-decoration:none;">Finance</a>
        <span>/</span><a href="{{ route('finance.customers.index') }}" style="color:#94a3b8;text-decoration:none;">Customer List</a>
        <span>/</span><span style="color:#2563EB;font-weight:600;">Backdate</span>
    </div>

    {{-- PAGE HEADER --}}
    <div style="margin-bottom:22px;">
        <h1 style="font-size:26px;font-weight:800;margin:0 0 3px;color:#1e293b;display:flex;align-items:center;gap:10px;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1E3A8A,#2563EB);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </span>
            Customer Backdate Report
        </h1>
        <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">View customer balances as of a specific historical date.</p>
    </div>

    {{-- TAB BAR --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.customers.detail') }}" class="tab-link">Detail View</a>
        <a href="{{ route('finance.customers.index') }}" class="tab-link">List All</a>
        <a href="{{ route('finance.customers.statistic') }}" class="tab-link">Statistic</a>
        <a href="{{ route('finance.customers.activity') }}" class="tab-link">Activity</a>
        <a href="{{ route('finance.customers.backdate') }}" class="tab-link active">Backdate</a>
        <a href="{{ route('finance.customers.summary') }}" class="tab-link">Summary</a>
    </div>

    {{-- CUSTOMER INFO + DATE FILTER COMBINED BAR --}}
    <div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:16px 22px;margin-bottom:18px;box-shadow:0 2px 12px rgba(30,58,138,.06);display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:16px;">
        {{-- Customer Info --}}
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0;">
            <div style="display:flex;flex-direction:column;gap:2px;padding-right:22px;border-right:1px solid #e2e8f0;margin-right:22px;">
                <span style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;">Code</span>
                <span style="font-size:15px;font-weight:700;color:#1e293b;">001</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:2px;padding-right:22px;border-right:1px solid #e2e8f0;margin-right:22px;">
                <span style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;">Currency</span>
                <span style="font-size:15px;font-weight:700;color:#1e293b;">IDR</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:2px;">
                <span style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;">Customer Name</span>
                <span style="font-size:15px;font-weight:700;color:#1E3A8A;">PT. JASA SWADAYA UTAMA</span>
            </div>
        </div>

        {{-- Date Filter --}}
        <form method="GET" action="{{ route('finance.customers.backdate') }}" style="display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;">
            <div style="display:flex;flex-direction:column;gap:6px;">
                <label style="font-size:10.5px;font-weight:700;color:#64748b;letter-spacing:.07em;text-transform:uppercase;">Backdate</label>
                <div class="filter-input">
                    <span class="fi-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                    <input type="date" name="backdate" value="{{ request('backdate','2026-03-17') }}">
                </div>
            </div>
            <button type="submit" class="btn-search">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Load
            </button>
        </form>
    </div>

    @php
        $customers = [
            ['code'=>'001','curr'=>'IDR','name'=>'PT. JASA SWADAYA UTAMA','balance'=>'12,500,000.00','dp'=>'5,000,000.00','active'=>true],
            ['code'=>'002','curr'=>'USD','name'=>'PT. GLOBAL MANDIRI','balance'=>'4,250.00','dp'=>'0.00','active'=>false],
            ['code'=>'003','curr'=>'IDR','name'=>'CV. CIPTA KARYA','balance'=>'0.00','dp'=>'1,500,000.00','active'=>false],
            ['code'=>'004','curr'=>'IDR','name'=>'PT. SINAR JAYA ABADI','balance'=>'25,750,000.00','dp'=>'10,000,000.00','active'=>false],
            ['code'=>'005','curr'=>'EUR','name'=>'EUROTECH INDONESIA','balance'=>'1,200.00','dp'=>'0.00','active'=>false],
            ['code'=>'006','curr'=>'IDR','name'=>'UD. MAJU BERSAMA','balance'=>'6,300,000.00','dp'=>'0.00','active'=>false],
            ['code'=>'007','curr'=>'IDR','name'=>'PT. NUSA BANGSA','balance'=>'0.00','dp'=>'0.00','active'=>false],
            ['code'=>'008','curr'=>'SGD','name'=>'LION CITY TRADING','balance'=>'5,400.00','dp'=>'2,000.00','active'=>false],
            ['code'=>'009','curr'=>'IDR','name'=>'BINTANG GEMILANG','balance'=>'8,900,000.00','dp'=>'0.00','active'=>false],
            ['code'=>'010','curr'=>'IDR','name'=>'KOPERASI SEJAHTERA','balance'=>'1,150,000.00','dp'=>'500,000.00','active'=>false],
        ];
    @endphp

    {{-- DATA GRID CARD --}}
    <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 4px 18px rgba(30,58,138,.06);">

        {{-- Card header --}}
        <div style="padding:16px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p style="font-size:13.5px;font-weight:700;color:#1e293b;margin:0;">Balance Report</p>
                <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">As of: {{ request('backdate') ? \Carbon\Carbon::parse(request('backdate'))->format('d/m/Y') : '17/03/2026' }}</p>
            </div>
            <span style="font-size:11px;font-weight:600;background:#eff6ff;color:#2563EB;padding:4px 12px;border-radius:20px;">
                {{ count($customers) }} records
            </span>
        </div>

        {{-- Group zone --}}
        <div class="group-zone">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            <span style="font-size:12px;color:#94a3b8;font-style:italic;">Drag a column header here to group by that column</span>
            <div id="groupBadgeContainer" style="display:flex;gap:6px;flex-wrap:wrap;"></div>
        </div>

        {{-- Table --}}
        <div class="scroll-body">
            <div style="overflow-x:auto;">
                <table class="grid-table" id="backdateTable">
                    <thead>
                        <tr>
                            <th draggable="true" data-col="code" onclick="sortTable(0)" style="text-align:left;">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                                    CODE
                                    <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                </div>
                            </th>
                            <th draggable="true" data-col="curr" onclick="sortTable(1)" style="text-align:left;">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                                    CURR
                                    <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                </div>
                            </th>
                            <th draggable="true" data-col="name" onclick="sortTable(2)" style="text-align:left;">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                                    CUSTOMER NAME
                                    <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                </div>
                            </th>
                            <th draggable="true" data-col="balance" onclick="sortTable(3)" style="text-align:right;">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;">
                                    BALANCE
                                    <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                </div>
                            </th>
                            <th draggable="true" data-col="dp" onclick="sortTable(4)" style="text-align:right;">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;">
                                    BALANCE DP
                                    <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr class="{{ $customer['active'] ? 'row-active' : '' }}" onclick="selectRow(this)">
                            <td style="font-weight:600;color:#1e293b;">{{ $customer['code'] }}</td>
                            <td><span class="badge-curr">{{ $customer['curr'] }}</span></td>
                            <td style="color:#334155;">{{ $customer['name'] }}</td>
                            <td class="mono" style="text-align:right;font-weight:600;color:#1E3A8A;">{{ $customer['balance'] }}</td>
                            <td class="mono" style="text-align:right;color:#475569;">{{ $customer['dp'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer --}}
        <div style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:10px 22px;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:11px;color:#94a3b8;">Showing {{ count($customers) }} of {{ count($customers) }} entries</span>
        </div>
    </div>

</div>
</div>

@push('scripts')
<script>
// Row selection
function selectRow(row) {
    document.querySelectorAll('#backdateTable tbody tr').forEach(r => r.classList.remove('row-active'));
    row.classList.add('row-active');
}

// Basic column sort
let sortDir = {};
function sortTable(colIdx) {
    const table = document.getElementById('backdateTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const asc = !sortDir[colIdx];
    sortDir = {};
    sortDir[colIdx] = asc;

    rows.sort((a, b) => {
        const aVal = a.cells[colIdx].innerText.trim().replace(/[,]/g,'');
        const bVal = b.cells[colIdx].innerText.trim().replace(/[,]/g,'');
        const aNum = parseFloat(aVal);
        const bNum = parseFloat(bVal);
        if(!isNaN(aNum) && !isNaN(bNum)) return asc ? aNum - bNum : bNum - aNum;
        return asc ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
    });
    rows.forEach(r => tbody.appendChild(r));

    // Update sort icons on all headers
    document.querySelectorAll('#backdateTable th').forEach((th, i) => {
        th.style.color = i === colIdx ? '#2563EB' : '';
    });
}

// Drag-to-group (visual only)
const headers = document.querySelectorAll('#backdateTable th[draggable]');
const groupZone = document.querySelector('.group-zone');
const badgeContainer = document.getElementById('groupBadgeContainer');

headers.forEach(th => {
    th.addEventListener('dragstart', e => {
        e.dataTransfer.setData('text/plain', th.dataset.col + '|' + th.innerText.trim().replace(/\n.*/,''));
        th.style.opacity = '.5';
    });
    th.addEventListener('dragend', () => { th.style.opacity = ''; });
});

groupZone.addEventListener('dragover', e => { e.preventDefault(); groupZone.style.background = '#dbeafe'; });
groupZone.addEventListener('dragleave', () => { groupZone.style.background = ''; });
groupZone.addEventListener('drop', e => {
    e.preventDefault();
    groupZone.style.background = '';
    const [col, label] = e.dataTransfer.getData('text/plain').split('|');
    // Avoid duplicates
    if(document.querySelector('[data-group="'+col+'"]')) return;
    const badge = document.createElement('span');
    badge.dataset.group = col;
    badge.style.cssText = 'display:inline-flex;align-items:center;gap:6px;background:#2563EB;color:#fff;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;';
    badge.innerHTML = label + ' <span style="cursor:pointer;font-size:13px;line-height:1;" onclick="removeGroup(this,\''+col+'\')">×</span>';
    badgeContainer.appendChild(badge);
});

function removeGroup(el, col) {
    el.closest('[data-group]').remove();
}
</script>
@endpush
@endsection