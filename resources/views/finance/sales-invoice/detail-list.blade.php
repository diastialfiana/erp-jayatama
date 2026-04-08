@extends('layouts.app')
@section('title', 'Sales Invoice - Detail Invoice List')

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
    
    .search-input {
        display:flex;align-items:center;gap:6px;background:#fff;
        border:1px solid #e2e8f0;border-radius:9px;padding:6px 12px;
        transition:box-shadow .2s;
    }
    .search-input:focus-within { box-shadow:0 0 0 3px rgba(37,99,235,.12);border-color:#2563EB; }
    .search-input input { border:none;outline:none;font-size:13px;color:#1e293b;min-width:180px;background:transparent; }

    /* Group zone */
    .group-zone {
        display:flex;align-items:center;flex-wrap:wrap;gap:8px;
        background:#f1f5f9;border-bottom:1px solid #e2e8f0;
        padding:8px 16px;min-height:38px;
    }
    .group-hint { font-size:11.5px;color:#94a3b8;font-style:italic; }

    /* Table */
    .erp-table { width:100%; table-layout:auto; border-collapse:collapse; min-width:1800px; }
    .erp-table th { 
        font-size:10px; font-weight:700; color:#64748b; letter-spacing:0.05em; 
        text-transform:uppercase; padding:8px 10px; border-bottom:2px solid #e2e8f0; border-right:1px solid #f1f5f9;
        text-align:left; background:#f8fafc; position:sticky; top:0; z-index:10; cursor:pointer;
    }
    .erp-table th:last-child { border-right:none; }
    .erp-table th:hover { background:#eff6ff; }
    .erp-table th .sort-ico { opacity:0;transition:opacity .15s;vertical-align:middle;margin-left:4px; }
    .erp-table th:hover .sort-ico,
    .erp-table th.sorted .sort-ico { opacity:1; }
    .erp-table th.sorted { color:#2563EB; }
    
    /* Data Row */
    .erp-table td { padding:7px 10px; font-size:11.5px; border-bottom:1px solid #f1f5f9; border-right:1px solid #f1f5f9; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:#334155; }
    .erp-table td:last-child { border-right:none; }
    .erp-table tbody tr { cursor:pointer; transition:background .12s; }
    .erp-table tbody tr:hover td { background:#f8fafc; }
    .erp-table tbody tr.row-active td { background:#dbeafe !important; }

    .row-unpaid td { color:#b91c1c; } /* Red highlight for unpaid invoices */

    .align-r { text-align:right !important; }
    .align-c { text-align:center !important; }
    .mono { font-variant-numeric:tabular-nums; }

    .scroll-y { overflow-y:auto; max-height:500px; }
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

    /* Record nav */
    .rec-nav { display:flex;align-items:center;gap:4px; }
    .rec-btn { display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:6px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-size:12px;font-weight:700;cursor:pointer;transition:all .15s;text-decoration:none; }
    .rec-btn:hover { background:#eff6ff;border-color:#2563EB;color:#2563EB; }
    .rec-btn.disabled { opacity:.35;pointer-events:none; }
    .rec-info { font-size:11.5px;font-weight:600;color:#64748b;padding:0 8px; }
</style>
@endpush

@section('content')
@php
    $totalRecords = count($invoices);
    
    $sumFm = 0;
    $sumPpn = 0;
    $sumTotal = 0;
    $sumPaid = 0;
    $sumDisc = 0;
    $sumBalance = 0;
    $sumPph = 0;
@endphp

<div class="page-fade" style="background:var(--bg);min-height:calc(100vh - 62px);padding:24px;">
<div style="width:100%; max-width:1600px; margin:0 auto;">

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
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </span>
                Detail Invoice List
            </h1>
            <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">Detail monitoring invoice + payment status and analysis per customer.</p>
        </div>
        <span style="align-self:flex-end;font-size:11.5px;font-weight:600;background:#eff6ff;color:#2563EB;padding:5px 14px;border-radius:20px;border:1px solid #bfdbfe;">
            {{ $totalRecords }} Detailed Records
        </span>
    </div>

    {{-- TAB BAR --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.sales-invoice.index') }}" class="tab-link">Record Detail</a>
        <a href="{{ route('finance.sales-invoice.records-list') }}" class="tab-link">Records List</a>
        <a href="{{ route('finance.sales-invoice.custom-group') }}" class="tab-link">Custom Group</a>
        <a href="{{ route('finance.sales-invoice.detail-list') }}" class="tab-link active">Detail Invoice List</a>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="erp-box">
        
        <form method="GET" action="{{ route('finance.sales-invoice.detail-list') }}" id="filterForm">
            {{-- Toolbar Top --}}
            <div class="filter-toolbar">
                <div style="font-size:13px; font-weight:700; color:#1e293b;">
                    Master Detail Status
                </div>
                <div class="search-input">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input type="text" id="invSearch" placeholder="Global search detail..." oninput="filterGrid(this.value)">
                </div>
            </div>

            {{-- Drag-to-Group Zone --}}
            <div class="group-zone" id="groupZone">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                <span class="group-hint">Drag a column header here to group by that column</span>
                <div id="groupBadges" style="display:flex;gap:6px;flex-wrap:wrap;"></div>
            </div>

            {{-- Table --}}
            <div class="scroll-y">
                <table class="erp-table" id="invTable">
                    <thead>
                        <tr>
                            <th style="width:100px;" draggable="true" data-col="date" onclick="sortGrid(0)">
                                DATE <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:250px;" draggable="true" data-col="customer" onclick="sortGrid(1)">
                                CUSTOMER <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:120px;" draggable="true" data-col="user" onclick="sortGrid(2)">
                                USER NO <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:180px;" draggable="true" data-col="invoice" onclick="sortGrid(3)">
                                INVOICE <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:130px;" class="align-r" draggable="true" data-col="fm" onclick="sortGrid(4)">
                                FM <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:130px;" class="align-r" draggable="true" data-col="ppn" onclick="sortGrid(5)">
                                PPN <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:140px;" class="align-r" draggable="true" data-col="total" onclick="sortGrid(6)">
                                TOTAL <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:130px;" class="align-r" draggable="true" data-col="paid" onclick="sortGrid(7)">
                                PAID <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:120px;" class="align-r" draggable="true" data-col="disc" onclick="sortGrid(8)">
                                DISC <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:140px;" class="align-r" draggable="true" data-col="balance" onclick="sortGrid(9)">
                                BALANCE <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:120px;" class="align-r" draggable="true" data-col="pph23" onclick="sortGrid(10)">
                                PPH23 <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:250px;" draggable="true" data-col="remark" onclick="sortGrid(11)">
                                REMARK <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($invoices as $i => $inv)
                        @php
                            // Determine statuses
                            $isUnpaid = $inv->balance > 0;
                            $rowClass = $isUnpaid ? 'row-unpaid' : '';
                            
                            $sumFm += $inv->subtotal;
                            $sumPpn += $inv->tax;
                            $sumTotal += $inv->total;
                            $sumPaid += $inv->paid;
                            $sumDisc += $inv->discount;
                            $sumBalance += $inv->balance;
                            $sumPph += $inv->pph23;
                        @endphp
                        <tr data-row="{{ $i }}" class="{{ $rowClass }}" onclick="selectRow(this)" ondblclick="window.location.href='{{ route('finance.sales-invoice.index') }}?id={{ $inv->id }}'">
                            <td style="color:#64748b;">{{ \Carbon\Carbon::parse($inv->date)->format('d/m/Y') }}</td>
                            <td style="font-weight:600;">{{ $inv->customer ? $inv->customer->name : 'N/A' }}</td>
                            <td>{{ $inv->user ? $inv->user->name : 'System' }}</td>
                            <td style="font-weight:700; color:#1e3a8a;">{{ $inv->invoice_number }}</td>
                            <td class="align-r mono">{{ number_format($inv->subtotal, 2) }}</td>
                            <td class="align-r mono">{{ number_format($inv->tax, 2) }}</td>
                            <td class="align-r mono" style="font-weight:700;">{{ number_format($inv->total, 2) }}</td>
                            <td class="align-r mono" style="color:#059669;">{{ number_format($inv->paid, 2) }}</td>
                            <td class="align-r mono">{{ number_format($inv->discount, 2) }}</td>
                            <td class="align-r mono" style="font-weight:700;">{{ number_format($inv->balance, 2) }}</td>
                            <td class="align-r mono">{{ number_format($inv->pph23, 2) }}</td>
                            <td>{{ $inv->note ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot style="position:sticky; bottom:0; background:#f8fafc; z-index:3; font-weight:800; border-top:2px solid #cbd5e1;">
                        <tr>
                            <td colspan="4" class="align-r" style="padding:10px 12px;">GRAND TOTAL</td>
                            <td class="mono align-r">{{ number_format($sumFm, 2) }}</td>
                            <td class="mono align-r">{{ number_format($sumPpn, 2) }}</td>
                            <td class="mono align-r">{{ number_format($sumTotal, 2) }}</td>
                            <td class="mono align-r">{{ number_format($sumPaid, 2) }}</td>
                            <td class="mono align-r">{{ number_format($sumDisc, 2) }}</td>
                            <td class="mono align-r" style="color:#b91c1c;">{{ number_format($sumBalance, 2) }}</td>
                            <td class="mono align-r">{{ number_format($sumPph, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
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
                    <button type="button" class="btn-export" onclick="alert('Export to Excel initialized')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg> Export Excel</button>
                </div>
                
                <div class="rec-nav">
                    <a href="#" class="rec-btn disabled" id="navFirst" title="First">«</a>
                    <a href="#" class="rec-btn disabled" id="navPrev" title="Previous">‹</a>
                    <span class="rec-info" id="recInfo">Record <strong>1</strong> of <strong>{{ $totalRecords }}</strong></span>
                    <a href="#" class="rec-btn" id="navNext" title="Next">›</a>
                    <a href="#" class="rec-btn" id="navLast" title="Last">»</a>
                </div>
            </div>

        </form>
    </div>

</div>
</div>

<script>
// ── Row selection ──────────────────────────────────────────────
let _selectedRow = null;
function selectRow(tr) {
    if (_selectedRow) _selectedRow.classList.remove('row-active');
    tr.classList.add('row-active');
    _selectedRow = tr;
    updateNav(parseInt(tr.dataset.row));
}

// ── Record navigation ─────────────────────────────────────────
const totalRows = {{ $totalRecords }};
let _currentIdx = 0;
function updateNav(idx) {
    _currentIdx = idx;
    if(totalRows === 0) return;
    document.getElementById('recInfo').innerHTML = 'Record <strong>'+(idx+1)+'</strong> of <strong>'+totalRows+'</strong>';
    document.getElementById('navFirst').className = 'rec-btn' + (idx===0?' disabled':'');
    document.getElementById('navPrev').className  = 'rec-btn' + (idx===0?' disabled':'');
    document.getElementById('navNext').className  = 'rec-btn' + (idx>=totalRows-1?' disabled':'');
    document.getElementById('navLast').className  = 'rec-btn' + (idx>=totalRows-1?' disabled':'');
}
function goRow(idx) {
    const rows = document.querySelectorAll('#invTable tbody tr:not([style*="display:none"])');
    if (rows[idx]) { selectRow(rows[idx]); rows[idx].scrollIntoView({block:'nearest'}); }
}
document.getElementById('navFirst').addEventListener('click', e=>{e.preventDefault();goRow(0);});
document.getElementById('navPrev').addEventListener ('click', e=>{e.preventDefault();if(_currentIdx>0)goRow(_currentIdx-1);});
document.getElementById('navNext').addEventListener ('click', e=>{e.preventDefault();goRow(_currentIdx+1);});
document.getElementById('navLast').addEventListener ('click', e=>{e.preventDefault();goRow(document.querySelectorAll('#invTable tbody tr:not([style*="display:none"])').length-1);});

// ── Search / filter ───────────────────────────────────────────
function filterGrid(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#invTable tbody tr').forEach(tr => {
        const text = tr.innerText.toLowerCase();
        tr.style.display = text.includes(q) ? '' : 'none';
    });
}

// ── Sort ──────────────────────────────────────────────────────
let _sortDir = {};
function sortGrid(colIdx) {
    const tbody = document.querySelector('#invTable tbody');
    const rows  = Array.from(tbody.querySelectorAll('tr'));
    const asc   = !_sortDir[colIdx];
    _sortDir = {}; _sortDir[colIdx] = asc;
    rows.sort((a, b) => {
        const av = (a.cells[colIdx]?.innerText||'').trim().replace(/[,\s]/g,'');
        const bv = (b.cells[colIdx]?.innerText||'').trim().replace(/[,\s]/g,'');
        const an = parseFloat(av), bn = parseFloat(bv);
        if (!isNaN(an) && !isNaN(bn)) return asc ? an-bn : bn-an;
        return asc ? av.localeCompare(bv) : bv.localeCompare(av);
    });
    rows.forEach(r => tbody.appendChild(r));
    document.querySelectorAll('#invTable th').forEach((th,i) => {
        th.classList.toggle('sorted', i===colIdx);
    });
}

// ── Drag-to-group ─────────────────────────────────────────────
document.querySelectorAll('#invTable th[draggable]').forEach(th => {
    th.addEventListener('dragstart', e => {
        e.dataTransfer.setData('text/plain', th.dataset.col + '|' + th.innerText.trim().split('\n')[0].trim());
        th.style.opacity = '.45';
    });
    th.addEventListener('dragend', () => { th.style.opacity = ''; });
});
const gz = document.getElementById('groupZone');
gz.addEventListener('dragover',  e => { e.preventDefault(); gz.style.background = '#dbeafe'; });
gz.addEventListener('dragleave', () => { gz.style.background = ''; });
gz.addEventListener('drop',      e => {
    e.preventDefault(); gz.style.background = '';
    const [col, label] = e.dataTransfer.getData('text/plain').split('|');
    if (document.querySelector('[data-group="' + col + '"]')) return;
    const badge = document.createElement('span');
    badge.dataset.group = col;
    badge.style.cssText = 'display:inline-flex;align-items:center;gap:5px;background:#2563EB;color:#fff;font-size:10.5px;font-weight:600;padding:3px 10px;border-radius:20px;cursor:default;';
    badge.innerHTML = label + ' <span style="cursor:pointer;font-size:14px;" onclick="this.parentElement.remove()">×</span>';
    document.getElementById('groupBadges').appendChild(badge);
    document.querySelector('.group-hint').style.display = 'none';
});
gz.addEventListener('dragover', () => { if (document.getElementById('groupBadges').children.length > 0) document.querySelector('.group-hint').style.display='none'; });

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    const firstRow = document.querySelector('#invTable tbody tr');
    if (firstRow) selectRow(firstRow);
});
</script>
@endsection
