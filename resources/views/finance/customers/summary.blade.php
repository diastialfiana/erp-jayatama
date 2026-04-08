@extends('layouts.app')
@section('title', 'Summary – Customer Finance')

@push('styles')
<style>
    :root { --blue:#2563EB; --navy:#1E3A8A; --accent:#4F46E5; --bg:#F8FAFC; }

    .page-fade { animation: pgIn .4s ease both; }
    @keyframes pgIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

    .tab-link { color:#64748b; text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; font-size:13.5px; transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB; font-weight:700; border-bottom:2.5px solid #2563EB; }

    .kpi-card { background:#fff; border-radius:18px; border:1px solid #e2e8f0; padding:20px 22px; box-shadow:0 4px 18px rgba(30,58,138,.06); transition:transform .25s,box-shadow .25s; }
    .kpi-card:hover { transform:scale(1.015); box-shadow:0 12px 40px rgba(30,58,138,.13); }
    .kpi-icon { width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0; }

    /* ERP Grid */
    .sum-table { width:100%; border-collapse:collapse; min-width:820px; }
    .sum-table thead tr { background:#f8fafc; }
    .sum-table th {
        font-size:10.5px; font-weight:700; color:#64748b; letter-spacing:.07em;
        text-transform:uppercase; padding:12px 14px; border-bottom:2px solid #e2e8f0;
        border-right:1px solid #f1f5f9; cursor:pointer; white-space:nowrap;
        user-select:none; transition:background .15s; position:sticky; top:0; z-index:5;
    }
    .sum-table th:last-child { border-right:none; }
    .sum-table th:hover { background:#eff6ff; }
    .sum-table th .sort-ico { opacity:0; transition:opacity .15s; vertical-align:middle; }
    .sum-table th:hover .sort-ico { opacity:1; }
    .sum-table td { padding:11px 14px; font-size:13px; border-bottom:1px solid #f1f5f9; border-right:1px solid #f8fafc; white-space:nowrap; }
    .sum-table td:last-child { border-right:none; }
    .sum-table tbody tr { cursor:pointer; transition:background .13s; }
    .sum-table tbody tr:hover td { background:#f8fafc; }
    .sum-table tbody tr.row-active td { background:#dbeafe; }
    .sum-table tbody tr.row-active:hover td { background:#bfdbfe; }
    .sum-table tbody tr:last-child td { border-bottom:none; }
    .sum-table tfoot td { padding:12px 14px; font-size:12px; font-weight:700; background:#f0f4ff; border-top:2px solid #e2e8f0; border-right:1px solid #e2e8f0; color:#1E3A8A; }
    .sum-table tfoot td:last-child { border-right:none; }

    .scroll-body { overflow-y:auto; max-height:390px; }
    .scroll-body::-webkit-scrollbar { width:5px; }
    .scroll-body::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }

    .filter-input { display:flex;align-items:center;background:#fff;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;transition:box-shadow .2s; }
    .filter-input:focus-within { box-shadow:0 0 0 3px rgba(37,99,235,.15);border-color:#2563EB; }
    .filter-input input { border:none;outline:none;background:transparent;padding:8px 10px;font-size:13px;color:#1e293b;min-width:130px; }
    .filter-input .fi-icon { padding:8px 10px 8px 12px;color:#94a3b8; }

    .btn-search { display:flex;align-items:center;gap:7px;background:linear-gradient(135deg,#1E3A8A,#2563EB);color:#fff;font-size:13px;font-weight:600;border:none;border-radius:10px;padding:9px 20px;cursor:pointer;transition:opacity .2s,transform .15s; }
    .btn-search:hover { opacity:.92;transform:translateY(-1px); }

    .group-zone { display:flex;align-items:center;gap:10px;background:#f1f5f9;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;padding:9px 16px;min-height:40px; }

    .mono { font-family:'Courier New',monospace; }
    .badge-curr { font-size:10px;font-weight:700;background:#eff6ff;color:#2563EB;padding:2px 8px;border-radius:20px;display:inline-block; }
    .positive { color:#16a34a; }
    .negative { color:#dc2626; }
</style>
@endpush

@php
    $totBeg     = array_sum(array_column($rows,'beg'));
    $totInvoice = array_sum(array_column($rows,'invoice'));
    $totReturn  = array_sum(array_column($rows,'return'));
    $netBalance = $totBeg + $totInvoice - $totReturn;
@endphp

@section('content')

<div class="page-fade" style="background:var(--bg);min-height:calc(100vh - 62px);padding:28px 24px;">
<div style="max-width:1340px;margin:0 auto;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:14px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <a href="{{ route('finance.customers.index') }}" style="color:#94a3b8;text-decoration:none;">Finance</a>
        <span>/</span>
        <a href="{{ route('finance.customers.index') }}" style="color:#94a3b8;text-decoration:none;">Customer List</a>
        <span>/</span>
        <span style="color:#2563EB;font-weight:600;">Summary</span>
    </div>

    {{-- PAGE HEADER --}}
    <div style="margin-bottom:22px;">
        <h1 style="font-size:26px;font-weight:800;margin:0 0 3px;color:#1e293b;display:flex;align-items:center;gap:10px;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1E3A8A,#2563EB);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </span>
            Customer Summary Report
        </h1>
        <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">Transaction summary — beginning balance, invoices, and returns per period.</p>
    </div>

    {{-- TAB BAR --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.customers.detail') }}" class="tab-link">Detail View</a>
        <a href="{{ route('finance.customers.index') }}" class="tab-link">List All</a>
        <a href="{{ route('finance.customers.statistic') }}" class="tab-link">Statistic</a>
        <a href="{{ route('finance.customers.activity') }}" class="tab-link">Activity</a>
        <a href="{{ route('finance.customers.backdate') }}" class="tab-link">Backdate</a>
        <a href="{{ route('finance.customers.summary') }}" class="tab-link active">Summary</a>
    </div>

    {{-- CUSTOMER INFO + FILTER ROW --}}
    <div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:16px 22px;margin-bottom:20px;box-shadow:0 2px 12px rgba(30,58,138,.06);display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:16px;">
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
        <form method="GET" action="{{ route('finance.customers.summary') }}" style="display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;">
            <div style="display:flex;flex-direction:column;gap:6px;">
                <label style="font-size:10.5px;font-weight:700;color:#64748b;letter-spacing:.07em;text-transform:uppercase;">From Date</label>
                <div class="filter-input">
                    <span class="fi-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
                    <input type="date" name="from_date" value="{{ request('from_date','2026-03-17') }}">
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:6px;">
                <label style="font-size:10.5px;font-weight:700;color:#64748b;letter-spacing:.07em;text-transform:uppercase;">Thru Date</label>
                <div class="filter-input">
                    <span class="fi-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
                    <input type="date" name="thru_date" value="{{ request('thru_date','2026-03-17') }}">
                </div>
            </div>
            <button type="submit" class="btn-search">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Search
            </button>
        </form>
    </div>

    {{-- KPI SUMMARY CARDS + CHART --}}
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1.6fr;gap:18px;margin-bottom:22px;align-items:start;">

        {{-- Total Invoice --}}
        <div class="kpi-card">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;">
                <div>
                    <p style="font-size:10.5px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 3px;">Total Invoice</p>
                    <p style="font-size:11px;color:#64748b;margin:0;">Gross billed amount</p>
                </div>
                <div class="kpi-icon" style="background:rgba(37,99,235,.1);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
            </div>
            <p class="mono" style="font-size:19px;font-weight:800;color:#1e293b;margin:0;">{{ number_format($totInvoice,2) }}</p>
            <div style="height:3px;background:linear-gradient(90deg,#2563EB,#4F46E5);border-radius:2px;margin-top:12px;"></div>
        </div>

        {{-- Total Return --}}
        <div class="kpi-card">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;">
                <div>
                    <p style="font-size:10.5px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 3px;">Total Return</p>
                    <p style="font-size:11px;color:#64748b;margin:0;">Credited returns</p>
                </div>
                <div class="kpi-icon" style="background:rgba(220,38,38,.08);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
                </div>
            </div>
            <p class="mono" style="font-size:19px;font-weight:800;color:#dc2626;margin:0;">{{ number_format($totReturn,2) }}</p>
            <div style="height:3px;background:linear-gradient(90deg,#dc2626,#f87171);border-radius:2px;margin-top:12px;"></div>
        </div>

        {{-- Net Balance --}}
        <div class="kpi-card">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;">
                <div>
                    <p style="font-size:10.5px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 3px;">Net Balance</p>
                    <p style="font-size:11px;color:#64748b;margin:0;">Beg + Invoice − Return</p>
                </div>
                <div class="kpi-icon" style="background:rgba(30,58,138,.1);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1E3A8A" stroke-width="2.2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
            </div>
            <p class="mono" style="font-size:19px;font-weight:800;color:#1E3A8A;margin:0;">{{ number_format($netBalance,2) }}</p>
            <div style="height:3px;background:linear-gradient(90deg,#1E3A8A,#2563EB);border-radius:2px;margin-top:12px;"></div>
        </div>

        {{-- Mini Chart --}}
        <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;padding:18px 20px;box-shadow:0 4px 18px rgba(30,58,138,.06);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <div>
                    <p style="font-size:12.5px;font-weight:700;color:#1e293b;margin:0;">Invoice vs Return</p>
                    <p style="font-size:10.5px;color:#94a3b8;margin:2px 0 0;">Top 5 customers</p>
                </div>
            </div>
            <canvas id="invRetChart" height="110"></canvas>
        </div>
    </div>

    {{-- ERP DATA GRID --}}
    <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 4px 18px rgba(30,58,138,.06);">

        {{-- Card header --}}
        <div style="padding:16px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p style="font-size:13.5px;font-weight:700;color:#1e293b;margin:0;">Customer Summary Data</p>
                <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Period: {{ request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('d/m/Y') : '17/03/2026' }} – {{ request('thru_date') ? \Carbon\Carbon::parse(request('thru_date'))->format('d/m/Y') : '17/03/2026' }}</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="font-size:11px;font-weight:600;background:#eff6ff;color:#2563EB;padding:4px 12px;border-radius:20px;">{{ count($rows) }} records</span>
            </div>
        </div>

        {{-- Drag-to-group zone --}}
        <div class="group-zone">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            <span style="font-size:12px;color:#94a3b8;font-style:italic;">Drag a column header here to group by that column</span>
            <div id="groupBadges" style="display:flex;gap:6px;flex-wrap:wrap;"></div>
        </div>

        {{-- Table --}}
        <div class="scroll-body">
            <div style="overflow-x:auto;">
                <table class="sum-table" id="summaryTable">
                    <thead>
                        <tr>
                            <th draggable="true" data-col="code" onclick="sortSummary(0)" style="text-align:left;">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                                    CODE <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                </div>
                            </th>
                            <th draggable="true" data-col="name" onclick="sortSummary(1)" style="text-align:left;">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                                    CUSTOMER NAME <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                </div>
                            </th>
                            <th draggable="true" data-col="beg" onclick="sortSummary(2)" style="text-align:right;">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;">
                                    <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                    BEG. BALANCE
                                </div>
                            </th>
                            <th draggable="true" data-col="invoice" onclick="sortSummary(3)" style="text-align:right;">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;">
                                    <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                    INVOICE
                                </div>
                            </th>
                            <th draggable="true" data-col="return" onclick="sortSummary(4)" style="text-align:right;">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;">
                                    <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                    RETURN
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $i => $r)
                        <tr class="{{ $i===0 ? 'row-active' : '' }}" onclick="selectRow(this)">
                            <td style="font-weight:600;color:#1e293b;">{{ $r['code'] }}</td>
                            <td style="color:#334155;">{{ $r['name'] }}</td>
                            <td class="mono" style="text-align:right;color:#64748b;">{{ number_format($r['beg'],2) }}</td>
                            <td class="mono" style="text-align:right;font-weight:600;color:#1E3A8A;">{{ number_format($r['invoice'],2) }}</td>
                            <td class="mono" style="text-align:right;color:{{ $r['return']>0 ? '#dc2626' : '#cbd5e1' }};">
                                {{ $r['return'] > 0 ? number_format($r['return'],2) : '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="letter-spacing:.05em;font-size:10.5px;text-transform:uppercase;">Grand Total</td>
                            <td class="mono" style="text-align:right;">{{ number_format($totBeg,2) }}</td>
                            <td class="mono" style="text-align:right;">{{ number_format($totInvoice,2) }}</td>
                            <td class="mono" style="text-align:right;color:#dc2626;">{{ number_format($totReturn,2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Card footer --}}
        <div style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:10px 22px;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:11px;color:#94a3b8;">Showing {{ count($rows) }} of {{ count($rows) }} entries</span>
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="font-size:11px;color:#64748b;">Net Balance:</span>
                <span class="mono" style="font-size:13px;font-weight:800;color:#1E3A8A;">{{ number_format($netBalance,2) }}</span>
            </div>
        </div>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
// Bar chart – Invoice vs Return (top 5)
(function(){
    const labels  = @json($labels);
    const invoice = @json($invoice);
    const ret     = @json($return);

    const shortL = labels.map(n => n.length > 14 ? n.substring(0,14) + '…' : n);

    new Chart(document.getElementById('invRetChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: shortL,
            datasets: [
                { label:'Invoice', data:invoice, backgroundColor:'rgba(37,99,235,.75)', borderRadius:5, borderSkipped:false },
                { label:'Return',  data:ret,     backgroundColor:'rgba(220,38,38,.55)', borderRadius:5, borderSkipped:false }
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:true,
            plugins: {
                legend:{ position:'bottom', labels:{ boxWidth:9, font:{size:10}, padding:10 } },
                tooltip:{ callbacks:{ label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID') } }
            },
            scales: {
                x:{ grid:{display:false}, ticks:{font:{size:9}} },
                y:{ grid:{color:'#f1f5f9'}, ticks:{ font:{size:9}, callback: v => v>=1e6?(v/1e6).toFixed(0)+'M':v } }
            }
        }
    });
})();

// Row selection
function selectRow(row) {
    document.querySelectorAll('#summaryTable tbody tr').forEach(r => r.classList.remove('row-active'));
    row.classList.add('row-active');
}

// Sort
let sortDir = {};
function sortSummary(colIdx) {
    const tbody = document.querySelector('#summaryTable tbody');
    const rows  = Array.from(tbody.querySelectorAll('tr'));
    const asc   = !sortDir[colIdx];
    sortDir = {}; sortDir[colIdx] = asc;
    rows.sort((a,b) => {
        const av = a.cells[colIdx].innerText.trim().replace(/[,—]/g,'');
        const bv = b.cells[colIdx].innerText.trim().replace(/[,—]/g,'');
        const an = parseFloat(av), bn = parseFloat(bv);
        if(!isNaN(an)&&!isNaN(bn)) return asc ? an-bn : bn-an;
        return asc ? av.localeCompare(bv) : bv.localeCompare(av);
    });
    rows.forEach(r => tbody.appendChild(r));
    document.querySelectorAll('#summaryTable th').forEach((th,i) => { th.style.color = i===colIdx ? '#2563EB' : ''; });
}

// Drag-to-group
document.querySelectorAll('#summaryTable th[draggable]').forEach(th => {
    th.addEventListener('dragstart', e => { e.dataTransfer.setData('text/plain', th.dataset.col+'|'+th.innerText.trim().split('\n')[0]); th.style.opacity='.5'; });
    th.addEventListener('dragend', () => { th.style.opacity=''; });
});
const groupZone = document.querySelector('.group-zone');
groupZone.addEventListener('dragover', e => { e.preventDefault(); groupZone.style.background='#dbeafe'; });
groupZone.addEventListener('dragleave', () => { groupZone.style.background=''; });
groupZone.addEventListener('drop', e => {
    e.preventDefault(); groupZone.style.background='';
    const [col, label] = e.dataTransfer.getData('text/plain').split('|');
    if(document.querySelector('[data-group="'+col+'"]')) return;
    const b = document.createElement('span');
    b.dataset.group = col;
    b.style.cssText = 'display:inline-flex;align-items:center;gap:5px;background:#2563EB;color:#fff;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;';
    b.innerHTML = label.trim() + ' <span style="cursor:pointer;font-size:14px;line-height:1;" onclick="this.closest(\'[data-group]\').remove()">×</span>';
    document.getElementById('groupBadges').appendChild(b);
});
</script>

@endsection