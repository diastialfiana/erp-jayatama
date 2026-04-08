@extends('layouts.app')
@section('title', 'Bank Account – Activity')

@push('styles')
<style>
    :root { --navy:#1E3A8A; --blue:#2563EB; --accent:#4F46E5; --bg:#F8FAFC; }

    .page-fade { animation: pgIn .4s ease both; }
    @keyframes pgIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

    .tab-link { color:#64748b;text-decoration:none;padding-bottom:8px;margin-bottom:-9px;white-space:nowrap;font-size:13.5px;transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB;font-weight:700;border-bottom:2.5px solid #2563EB; }

    /* Bank info */
    .info-field { display:flex;flex-direction:column;gap:2px;padding-right:20px;border-right:1px solid #e2e8f0;margin-right:20px; }
    .info-field:last-child { border-right:none;padding-right:0;margin-right:0; }
    .info-label { font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase; }
    .info-value { font-size:14px;font-weight:700;color:#1e293b; }

    /* Date filter */
    .date-group { display:flex;flex-direction:column;gap:3px; }
    .date-group label { font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase; }
    .date-input {
        display:flex;align-items:center;gap:5px;background:#fff;
        border:1px solid #e2e8f0;border-radius:8px;padding:6px 10px;transition:box-shadow .2s;
    }
    .date-input:focus-within { box-shadow:0 0 0 3px rgba(37,99,235,.12);border-color:#2563EB; }
    .date-input input { border:none;outline:none;font-size:12.5px;color:#1e293b;background:transparent;width:105px; }
    .btn-search { display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#1E3A8A,#2563EB);color:#fff;font-size:12px;font-weight:600;border:none;border-radius:8px;padding:8px 16px;cursor:pointer;transition:opacity .2s; }
    .btn-search:hover { opacity:.88; }
    .btn-refresh { display:inline-flex;align-items:center;gap:6px;background:#fff;color:#475569;font-size:12px;font-weight:600;border:1px solid #e2e8f0;border-radius:8px;padding:7px 14px;cursor:pointer;transition:all .15s; }
    .btn-refresh:hover { border-color:#2563EB;color:#2563EB;background:#eff6ff; }

    /* ERP Grid */
    .erp-wrap { overflow-x:auto; }
    .erp-table { width:100%;border-collapse:collapse;min-width:1300px;table-layout:fixed; }
    .erp-table thead tr { background:#f8fafc; }
    .erp-table th {
        font-size:9.5px;font-weight:700;color:#64748b;letter-spacing:.07em;text-transform:uppercase;
        padding:9px 10px;border-bottom:2px solid #e2e8f0;border-right:1px solid #f1f5f9;
        cursor:pointer;user-select:none;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        transition:background .15s;position:sticky;top:0;z-index:5;resize:horizontal;
    }
    .erp-table th:last-child { border-right:none; }
    .erp-table th:hover { background:#eff6ff; }
    .erp-table th .sort-ico { opacity:0;transition:opacity .15s;vertical-align:middle;margin-left:3px; }
    .erp-table th:hover .sort-ico, .erp-table th.sorted .sort-ico { opacity:1; }
    .erp-table th.sorted { color:#2563EB; }

    .erp-table td {
        padding:8px 10px;font-size:12px;border-bottom:1px solid #f1f5f9;
        border-right:1px solid #f8fafc;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    }
    .erp-table td:last-child { border-right:none; }
    .erp-table tbody tr { cursor:pointer;transition:background .12s; }
    .erp-table tbody tr:hover td { background:#f8fafc; }
    .erp-table tbody tr.row-active td { background:#dbeafe; }

    /* Group zone */
    .group-zone { display:flex;align-items:center;flex-wrap:wrap;gap:8px;background:#f1f5f9;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;padding:7px 14px;min-height:36px; }
    .group-hint { font-size:11px;color:#94a3b8;font-style:italic; }

    /* Search bar */
    .grid-search { display:flex;align-items:center;gap:6px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:5px 10px;transition:box-shadow .2s; }
    .grid-search:focus-within { box-shadow:0 0 0 3px rgba(37,99,235,.12);border-color:#2563EB; }
    .grid-search input { border:none;outline:none;font-size:12.5px;color:#1e293b;min-width:160px;background:transparent; }

    /* Scroll */
    .scroll-y { overflow-y:auto;max-height:460px; }
    .scroll-y::-webkit-scrollbar { width:5px; }
    .scroll-y::-webkit-scrollbar-thumb { background:#cbd5e1;border-radius:3px; }

    /* Footer totals */
    .tfoot-cell { padding:10px;font-size:11.5px;font-weight:700;text-align:right;font-family:'Courier New',monospace; }

    /* Badges */
    .badge-curr { font-size:9.5px;font-weight:700;background:#eff6ff;color:#2563EB;padding:1px 6px;border-radius:20px; }
    .chk-box { width:14px;height:14px;accent-color:#2563EB;cursor:pointer; }
    .link-icon { display:inline-flex;align-items:center;color:#94a3b8;font-size:11px;text-decoration:none;transition:color .15s; }
    .link-icon:hover { color:#2563EB; }

    .mono { font-family:'Courier New',monospace; }
    .debit-col { color:#dc2626;text-align:right; }
    .credit-col { color:#16a34a;text-align:right; }
    .balance-col { color:#1E3A8A;font-weight:600;text-align:right; }
    .zero { color:#cbd5e1; }

    /* Rec nav */
    .rec-nav { display:flex;align-items:center;gap:4px; }
    .rec-btn { display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-size:11px;font-weight:700;cursor:pointer;transition:all .15s;text-decoration:none; }
    .rec-btn:hover { background:#eff6ff;border-color:#2563EB;color:#2563EB; }
    .rec-btn.disabled { opacity:.35;pointer-events:none; }
    .rec-info { font-size:11px;font-weight:600;color:#64748b;padding:0 8px; }
</style>
@endpush

@section('content')
@php $total = $transactions->count(); @endphp

<div class="page-fade" style="background:var(--bg);min-height:calc(100vh - 62px);padding:28px 24px;">
<div style="max-width:1380px;margin:0 auto;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:14px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <a href="{{ route('finance.index') }}" style="color:#94a3b8;text-decoration:none;">Finance</a>
        <span>/</span>
        <a href="{{ route('finance.bank-accounts.index') }}" style="color:#94a3b8;text-decoration:none;">Bank Account</a>
        <span>/</span>
        <span style="color:#2563EB;font-weight:600;">Activity</span>
    </div>

    {{-- PAGE HEADER --}}
    <div style="margin-bottom:18px;">
        <h1 style="font-size:26px;font-weight:800;margin:0 0 3px;color:#1e293b;display:flex;align-items:center;gap:10px;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1E3A8A,#2563EB);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </span>
            Bank Account Activity
        </h1>
        <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">Transaction history for the selected bank account and date range.</p>
    </div>

    {{-- TABS --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.bank-accounts.record-detail') }}" class="tab-link">Record Detail</a>
        <a href="{{ route('finance.bank-accounts.records-list') }}" class="tab-link">Records List</a>
        <a href="{{ route('finance.bank-accounts.statistics') }}"  class="tab-link">Statistics</a>
        <a href="{{ route('finance.bank-accounts.activity') }}"   class="tab-link active">Activity</a>
        <a href="{{ route('finance.bank-accounts.backdate') }}"   class="tab-link">Backdate</a>
        <a href="{{ route('finance.bank-accounts.summary') }}"    class="tab-link">Summary</a>
    </div>

    {{-- FILTER BAR --}}
    <div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:14px 20px;margin-bottom:20px;box-shadow:0 2px 12px rgba(30,58,138,.06);">
        <form method="GET" action="{{ route('finance.bank-accounts.activity') }}" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:16px;">

            {{-- Bank Info --}}
            <div class="info-field">
                <span class="info-label">Code</span>
                <span class="info-value">{{ $bank?->code ?? '00002' }}</span>
            </div>
            <div class="info-field">
                <span class="info-label">Currency</span>
                <span class="info-value" style="color:#2563EB;">{{ $bank?->currency ?? 'IDR' }}</span>
            </div>
            <div class="info-field" style="min-width:160px;">
                <span class="info-label">Bank Name</span>
                <span class="info-value" style="font-size:13px;">{{ $bank?->bank_name ?? 'BANK MEGA MAXI' }}</span>
            </div>

            {{-- Date Filters --}}
            <div class="date-group">
                <label>From Date</label>
                <div class="date-input">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <input type="date" name="from_date" value="{{ $fromDate }}">
                </div>
            </div>
            <div class="date-group">
                <label>Thru Date</label>
                <div class="date-input">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <input type="date" name="thru_date" value="{{ $thruDate }}">
                </div>
            </div>

            {{-- Buttons --}}
            <div style="display:flex;gap:8px;align-items:flex-end;">
                <button type="submit" class="btn-search">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Search
                </button>
                <a href="{{ route('finance.bank-accounts.activity') }}" class="btn-refresh">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                    Refresh
                </a>
            </div>
        </form>
    </div>

    {{-- ERP GRID CARD --}}
    <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 4px 18px rgba(30,58,138,.07);">

        {{-- Toolbar --}}
        <div style="padding:10px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;">Transaction Log</p>
                <span style="font-size:11px;font-weight:600;background:#eff6ff;color:#2563EB;padding:2px 10px;border-radius:20px;border:1px solid #bfdbfe;">{{ $total }} Records</span>
            </div>
            <div class="grid-search">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" id="activitySearch" placeholder="Search date, note, reference…" oninput="filterAct(this.value)">
            </div>
        </div>

        {{-- Group Zone --}}
        <div class="group-zone" id="groupZone">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            <span class="group-hint">Drag a column header here to group by that column</span>
            <div id="groupBadges" style="display:flex;gap:6px;flex-wrap:wrap;"></div>
        </div>

        {{-- Table --}}
        <div class="scroll-y">
            <div class="erp-wrap">
                <table class="erp-table" id="actTable">
                    <thead>
                        <tr>
                            {{-- CHK --}}
                            <th style="width:38px;text-align:center;resize:none;">
                                <input type="checkbox" class="chk-box" id="chkAll" onchange="toggleAll(this)">
                            </th>
                            @php
                                $cols = [
                                    [0,'DATE','90px'],
                                    [1,'DT. VALUE','90px'],
                                    [2,'USERNO','80px'],
                                    [3,'NOTE','200px'],
                                    [4,'DEBET','120px'],
                                    [5,'CREDIT','120px'],
                                    [6,'BALANCE','130px'],
                                    [7,'REF','90px'],
                                    [8,'CURR','60px'],
                                    [9,'RATE','60px'],
                                    [10,'LINK','50px'],
                                ];
                            @endphp
                            @foreach($cols as [$ci,$label,$w])
                            <th style="width:{{ $w }};" draggable="true" data-col="{{ strtolower(str_replace('. ','_',$label)) }}" onclick="sortAct({{ $ci + 1 }})">
                                {{ $label }}
                                <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $i => $tx)
                        @php
                            $dbt = $tx['debit']  ?? 0;
                            $cdt = $tx['credit'] ?? 0;
                            $bal = $tx['balance'] ?? 0;
                        @endphp
                        <tr data-row="{{ $i }}" onclick="selectAct(this)">
                            <td style="text-align:center;"><input type="checkbox" class="chk-box row-chk"></td>
                            <td style="color:#475569;">{{ $tx['date'] }}</td>
                            <td style="color:#64748b;">{{ $tx['value_date'] }}</td>
                            <td>
                                <span style="font-size:10.5px;font-weight:700;background:#f1f5f9;color:#475569;padding:1px 8px;border-radius:4px;">{{ $tx['userno'] }}</span>
                            </td>
                            <td title="{{ $tx['note'] }}" style="color:#334155;font-size:12px;">{{ Str::limit($tx['note'], 32) }}</td>
                            <td class="mono {{ $dbt > 0 ? 'debit-col' : 'zero' }}">{{ number_format($dbt, 2, '.', ',') }}</td>
                            <td class="mono {{ $cdt > 0 ? 'credit-col' : 'zero' }}">{{ number_format($cdt, 2, '.', ',') }}</td>
                            <td class="mono balance-col">{{ number_format($bal, 2, '.', ',') }}</td>
                            <td style="color:#4F46E5;font-size:11.5px;font-weight:600;">{{ $tx['reference'] }}</td>
                            <td><span class="badge-curr">{{ $tx['currency'] }}</span></td>
                            <td class="mono" style="color:#64748b;text-align:right;">{{ number_format($tx['rate'], 2) }}</td>
                            <td style="text-align:center;">
                                @if($tx['link'] ?? '')
                                    <a href="{{ $tx['link'] }}" class="link-icon" title="Open Link">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                    </a>
                                @else
                                    <span style="color:#e2e8f0;">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" style="text-align:center;padding:40px;color:#94a3b8;font-size:13px;">
                                No transactions found for the selected period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot style="background:#f8fafc;border-top:2px solid #e2e8f0;position:sticky;bottom:0;z-index:4;">
                        <tr>
                            <td colspan="5" style="padding:10px 10px;font-size:10.5px;font-weight:700;color:#64748b;letter-spacing:.06em;text-transform:uppercase;">Total</td>
                            <td class="tfoot-cell" style="color:#dc2626;">{{ number_format($totalDebit, 2, '.', ',') }}</td>
                            <td class="tfoot-cell" style="color:#16a34a;">{{ number_format($totalCredit, 2, '.', ',') }}</td>
                            <td class="tfoot-cell" style="color:#1E3A8A;">{{ number_format($totalDebit - $totalCredit, 2, '.', ',') }}</td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Grid Footer --}}
        <div style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:9px 16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div class="rec-nav">
                <a href="#" class="rec-btn disabled" id="navFirst">«</a>
                <a href="#" class="rec-btn disabled" id="navPrev">‹</a>
                <span class="rec-info" id="recInfo">Record <strong>1</strong> of <strong>{{ $total }}</strong></span>
                <a href="#" class="rec-btn {{ $total <= 1 ? 'disabled' : '' }}" id="navNext">›</a>
                <a href="#" class="rec-btn {{ $total <= 1 ? 'disabled' : '' }}" id="navLast">»</a>
            </div>
            <div style="display:flex;gap:20px;font-size:11px;color:#64748b;">
                <span>Total Debit: <strong style="color:#dc2626;" class="mono">{{ number_format($totalDebit, 2, '.', ',') }}</strong></span>
                <span>Total Credit: <strong style="color:#16a34a;" class="mono">{{ number_format($totalCredit, 2, '.', ',') }}</strong></span>
                <span>Visible: <strong id="visCount">{{ $total }}</strong></span>
            </div>
        </div>
    </div>

</div>
</div>

<script>
// ── Row Select ─────────────────────────────────
let _sel = null;
function selectAct(tr) {
    if (_sel) _sel.classList.remove('row-active');
    tr.classList.add('row-active'); _sel = tr;
    updateNav(parseInt(tr.dataset.row));
}

// ── Record Nav ──────────────────────────────────
const totalR = {{ $total }};
let _idx = 0;
function updateNav(idx) {
    _idx = idx;
    document.getElementById('recInfo').innerHTML = 'Record <strong>'+(idx+1)+'</strong> of <strong>'+totalR+'</strong>';
    document.getElementById('navFirst').className = 'rec-btn'+(idx===0?' disabled':'');
    document.getElementById('navPrev').className  = 'rec-btn'+(idx===0?' disabled':'');
    document.getElementById('navNext').className  = 'rec-btn'+(idx>=totalR-1?' disabled':'');
    document.getElementById('navLast').className  = 'rec-btn'+(idx>=totalR-1?' disabled':'');
}
function goRow(idx) {
    const rows = document.querySelectorAll('#actTable tbody tr:not([style*="display:none"])');
    if (rows[idx]) { selectAct(rows[idx]); rows[idx].scrollIntoView({block:'nearest'}); }
}
document.getElementById('navFirst').addEventListener('click', e=>{e.preventDefault();goRow(0);});
document.getElementById('navPrev').addEventListener ('click', e=>{e.preventDefault();if(_idx>0)goRow(_idx-1);});
document.getElementById('navNext').addEventListener ('click', e=>{e.preventDefault();goRow(_idx+1);});
document.getElementById('navLast').addEventListener ('click', e=>{e.preventDefault();goRow(document.querySelectorAll('#actTable tbody tr:not([style*="display:none"])').length-1);});

// ── Toggle All Checkboxes ─────────────────────
function toggleAll(chk) {
    document.querySelectorAll('.row-chk').forEach(c => c.checked = chk.checked);
}

// ── Search ─────────────────────────────────────
function filterAct(q) {
    q = q.toLowerCase(); let vis = 0;
    document.querySelectorAll('#actTable tbody tr').forEach(tr => {
        const show = tr.innerText.toLowerCase().includes(q);
        tr.style.display = show ? '' : 'none';
        if (show) vis++;
    });
    document.getElementById('visCount').textContent = vis;
}

// ── Sort ───────────────────────────────────────
let _sortDir = {};
function sortAct(colIdx) {
    const tbody = document.querySelector('#actTable tbody');
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
    document.querySelectorAll('#actTable th').forEach((th,i) => th.classList.toggle('sorted', i===colIdx));
}

// ── Drag-to-Group ───────────────────────────────
document.querySelectorAll('#actTable th[draggable]').forEach(th => {
    th.addEventListener('dragstart', e => {
        e.dataTransfer.setData('text/plain', th.dataset.col+'|'+th.innerText.trim().split('\n')[0].trim());
        th.style.opacity = '.45';
    });
    th.addEventListener('dragend', () => th.style.opacity = '');
});
const gz = document.getElementById('groupZone');
gz.addEventListener('dragover',  e => { e.preventDefault(); gz.style.background='#dbeafe'; });
gz.addEventListener('dragleave', () => gz.style.background='');
gz.addEventListener('drop', e => {
    e.preventDefault(); gz.style.background='';
    const [col, label] = e.dataTransfer.getData('text/plain').split('|');
    if (document.querySelector('[data-group="'+col+'"]')) return;
    const badge = document.createElement('span');
    badge.dataset.group = col;
    badge.style.cssText = 'display:inline-flex;align-items:center;gap:5px;background:#2563EB;color:#fff;font-size:10px;font-weight:600;padding:2px 10px;border-radius:20px;';
    badge.innerHTML = label+' <span style="cursor:pointer;font-size:13px;" onclick="this.parentElement.remove()">×</span>';
    document.getElementById('groupBadges').appendChild(badge);
    document.querySelector('.group-hint').style.display = 'none';
});

// ── Auto-select first row ───────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const first = document.querySelector('#actTable tbody tr');
    if (first) selectAct(first);
});
</script>
@endsection
