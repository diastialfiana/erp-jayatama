@extends('layouts.app')
@section('title', 'Bank Account – Backdate')

@push('styles')
<style>
    :root { --navy:#1E3A8A; --blue:#2563EB; --bg:#F8FAFC; }

    .page-fade { animation: pgIn .4s ease both; }
    @keyframes pgIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

    .tab-link { color:#64748b;text-decoration:none;padding-bottom:8px;margin-bottom:-9px;white-space:nowrap;font-size:13.5px;transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB;font-weight:700;border-bottom:2.5px solid #2563EB; }

    /* Info bar */
    .info-field { display:flex;flex-direction:column;gap:2px;padding-right:20px;border-right:1px solid #e2e8f0;margin-right:20px; }
    .info-field:last-child { border-right:none;padding-right:0;margin-right:0; }
    .info-label { font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase; }
    .info-value { font-size:14px;font-weight:700;color:#1e293b; }

    /* Date filter */
    .date-group { display:flex;flex-direction:column;gap:3px; }
    .date-group label { font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase; }
    .date-input { display:flex;align-items:center;gap:5px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:6px 10px;transition:box-shadow .2s; }
    .date-input:focus-within { box-shadow:0 0 0 3px rgba(37,99,235,.12);border-color:#2563EB; }
    .date-input input { border:none;outline:none;font-size:12.5px;color:#1e293b;background:transparent; }

    .btn-refresh { display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#1E3A8A,#2563EB);color:#fff;font-size:12px;font-weight:600;border:none;border-radius:8px;padding:8px 16px;cursor:pointer;transition:opacity .2s; }
    .btn-refresh:hover { opacity:.88; }

    /* ERP Grid */
    .erp-wrap { overflow-x:auto; }
    .erp-table { width:100%;border-collapse:collapse;min-width:800px;table-layout:fixed; }
    .erp-table thead tr { background:#f8fafc; }
    .erp-table th {
        font-size:10px;font-weight:700;color:#64748b;letter-spacing:.07em;text-transform:uppercase;
        padding:10px 14px;border-bottom:2px solid #e2e8f0;border-right:1px solid #f1f5f9;
        cursor:pointer;user-select:none;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        transition:background .15s;position:sticky;top:0;z-index:5;resize:horizontal;
    }
    .erp-table th:last-child { border-right:none; }
    .erp-table th:hover { background:#eff6ff; }
    .erp-table th .sort-ico { opacity:0;transition:opacity .15s;vertical-align:middle;margin-left:3px; }
    .erp-table th:hover .sort-ico, .erp-table th.sorted .sort-ico { opacity:1; }
    .erp-table th.sorted { color:#2563EB; }

    .erp-table td {
        padding:10px 14px;font-size:12.5px;border-bottom:1px solid #f1f5f9;
        border-right:1px solid #f8fafc;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    }
    .erp-table td:last-child { border-right:none; }
    .erp-table tbody tr { cursor:pointer;transition:background .12s; }
    .erp-table tbody tr:hover td { background:#f8fafc; }
    .erp-table tbody tr.row-active td { background:#dbeafe; }

    /* Group zone */
    .group-zone { display:flex;align-items:center;flex-wrap:wrap;gap:8px;background:#f1f5f9;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;padding:8px 16px;min-height:38px; }
    .group-hint { font-size:11px;color:#94a3b8;font-style:italic; }

    /* Search */
    .grid-search { display:flex;align-items:center;gap:6px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:5px 10px;transition:box-shadow .2s; }
    .grid-search:focus-within { box-shadow:0 0 0 3px rgba(37,99,235,.12);border-color:#2563EB; }
    .grid-search input { border:none;outline:none;font-size:12.5px;color:#1e293b;min-width:160px;background:transparent; }

    /* Scroll */
    .scroll-y { overflow-y:auto;max-height:480px; }
    .scroll-y::-webkit-scrollbar { width:5px; }
    .scroll-y::-webkit-scrollbar-thumb { background:#cbd5e1;border-radius:3px; }

    /* Badges */
    .badge-curr { font-size:10px;font-weight:700;background:#eff6ff;color:#2563EB;padding:2px 7px;border-radius:20px; }
    .badge-default { font-size:9.5px;font-weight:700;background:#dbeafe;color:#1E3A8A;padding:1px 7px;border-radius:20px;margin-left:4px; }
    .mono { font-family:'Courier New',monospace; }
    .negative { color:#dc2626; }

    /* Rec nav */
    .rec-nav { display:flex;align-items:center;gap:4px; }
    .rec-btn { display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-size:11px;font-weight:700;cursor:pointer;transition:all .15s;text-decoration:none; }
    .rec-btn:hover { background:#eff6ff;border-color:#2563EB;color:#2563EB; }
    .rec-btn.disabled { opacity:.35;pointer-events:none; }
    .rec-info { font-size:11px;font-weight:600;color:#64748b;padding:0 8px; }

    /* Date badge */
    .date-badge { display:inline-flex;align-items:center;gap:6px;background:#eff6ff;border:1px solid #bfdbfe;color:#1E3A8A;border-radius:8px;padding:5px 12px;font-size:12px;font-weight:700; }
</style>
@endpush

@section('content')
@php
    /* Merge DB data or use mock */
    $firstBank = $banks->first();
    $displayBanks = $banks->count() > 0
        ? $banks->map(fn($b) => [
            'code'          => $b->code,
            'currency'      => $b->currency,
            'bank_name'     => $b->bank_name,
            'balance'       => $historicalBalances[$b->code]['balance']   ?? $b->balance,
            'audit_balance' => $historicalBalances[$b->code]['audit_balance'] ?? $b->balance,
            'is_default'    => $b->is_default,
          ])->toArray()
        : $mockBanks;

    $totalRecords = count($displayBanks);
    $totalBalance = array_sum(array_column($displayBanks, 'balance'));
    $displayDate  = \Carbon\Carbon::parse($backdateStr)->format('d/m/Y');
@endphp

<div class="page-fade" style="background:var(--bg);min-height:calc(100vh - 62px);padding:28px 24px;">
<div style="max-width:1200px;margin:0 auto;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:14px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <a href="{{ route('finance.index') }}" style="color:#94a3b8;text-decoration:none;">Finance</a>
        <span>/</span>
        <a href="{{ route('finance.bank-accounts.index') }}" style="color:#94a3b8;text-decoration:none;">Bank Account</a>
        <span>/</span>
        <span style="color:#2563EB;font-weight:600;">Backdate</span>
    </div>

    {{-- PAGE HEADER --}}
    <div style="display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:18px;">
        <div>
            <h1 style="font-size:26px;font-weight:800;margin:0 0 3px;color:#1e293b;display:flex;align-items:center;gap:10px;">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1E3A8A,#2563EB);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/></svg>
                </span>
                Bank Account Backdate
            </h1>
            <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">Historical balance of all bank accounts on a specific date.</p>
        </div>
        <div class="date-badge">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Balance as of {{ $displayDate }}
        </div>
    </div>

    {{-- TABS --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.bank-accounts.record-detail') }}" class="tab-link">Record Detail</a>
        <a href="{{ route('finance.bank-accounts.records-list') }}" class="tab-link">Records List</a>
        <a href="{{ route('finance.bank-accounts.statistics') }}"  class="tab-link">Statistics</a>
        <a href="{{ route('finance.bank-accounts.activity') }}"   class="tab-link">Activity</a>
        <a href="{{ route('finance.bank-accounts.backdate') }}"   class="tab-link active">Backdate</a>
        <a href="{{ route('finance.bank-accounts.summary') }}"    class="tab-link">Summary</a>
    </div>

    {{-- FILTER BAR --}}
    <div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:14px 20px;margin-bottom:20px;box-shadow:0 2px 12px rgba(30,58,138,.06);">
        <form method="GET" action="{{ route('finance.bank-accounts.backdate') }}" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:18px;">

            {{-- Bank Info --}}
            <div class="info-field">
                <span class="info-label">Code</span>
                <span class="info-value">{{ $firstBank?->code ?? '00001' }}</span>
            </div>
            <div class="info-field">
                <span class="info-label">Currency</span>
                <span class="info-value" style="color:#2563EB;">{{ $firstBank?->currency ?? 'IDR' }}</span>
            </div>
            <div class="info-field" style="min-width:160px;">
                <span class="info-label">Bank Name</span>
                <span class="info-value" style="font-size:13px;">{{ $firstBank?->bank_name ?? 'BANK MEGA CAB. TENDEAN' }}</span>
            </div>

            {{-- Backdate input --}}
            <div class="date-group">
                <label>Backdate</label>
                <div class="date-input">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <input type="date" name="date" value="{{ $backdateStr }}">
                </div>
            </div>

            <button type="submit" class="btn-refresh">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                Refresh
            </button>
        </form>
    </div>

    {{-- SUMMARY STRIP --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px;">
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:14px 18px;box-shadow:0 2px 10px rgba(30,58,138,.05);">
            <p style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 4px;">Total Banks</p>
            <p style="font-size:22px;font-weight:800;color:#1E3A8A;margin:0;">{{ $totalRecords }}</p>
        </div>
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:14px 18px;box-shadow:0 2px 10px rgba(30,58,138,.05);">
            <p style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 4px;">Total Balance</p>
            <p class="mono" style="font-size:18px;font-weight:800;color:{{ $totalBalance >= 0 ? '#1E3A8A' : '#dc2626' }};margin:0;">{{ number_format($totalBalance, 2, '.', ',') }}</p>
        </div>
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:14px 18px;box-shadow:0 2px 10px rgba(30,58,138,.05);">
            <p style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 4px;">As of Date</p>
            <p style="font-size:18px;font-weight:800;color:#475569;margin:0;">{{ $displayDate }}</p>
        </div>
    </div>

    {{-- ERP GRID --}}
    <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 4px 18px rgba(30,58,138,.07);">

        {{-- Toolbar --}}
        <div style="padding:10px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;">Backdate Balance Grid</p>
                <span style="font-size:11px;font-weight:600;background:#eff6ff;color:#2563EB;padding:2px 10px;border-radius:20px;border:1px solid #bfdbfe;">{{ $totalRecords }} Accounts</span>
            </div>
            <div class="grid-search">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" id="bdSearch" placeholder="Search code, bank name, currency…" oninput="filterBd(this.value)">
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
                <table class="erp-table" id="bdTable">
                    <thead>
                        <tr>
                            <th style="width:90px;" draggable="true" data-col="code" onclick="sortBd(0)">
                                CODE <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:65px;" draggable="true" data-col="curr" onclick="sortBd(1)">
                                CURR <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:240px;" draggable="true" data-col="bank_name" onclick="sortBd(2)">
                                BANK NAME <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:180px;text-align:right;" draggable="true" data-col="balance" onclick="sortBd(3)">
                                BALANCE <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:180px;text-align:right;" draggable="true" data-col="audit_balance" onclick="sortBd(4)">
                                [BALANCE] <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($displayBanks as $i => $bank)
                        @php $isNeg = $bank['balance'] < 0; @endphp
                        <tr data-row="{{ $i }}" onclick="selectBd(this)">
                            <td>
                                <span style="font-weight:700;color:#1e293b;">{{ $bank['code'] }}</span>
                                @if($bank['is_default'] ?? false)
                                    <span class="badge-default">DEFAULT</span>
                                @endif
                            </td>
                            <td><span class="badge-curr">{{ $bank['currency'] }}</span></td>
                            <td style="color:#334155;font-weight:500;">{{ $bank['bank_name'] }}</td>
                            <td class="mono" style="text-align:right;font-weight:600;color:{{ $isNeg ? '#dc2626' : '#1E3A8A' }};">
                                {{ number_format($bank['balance'], 2, '.', ',') }}
                            </td>
                            <td class="mono" style="text-align:right;color:{{ $bank['audit_balance'] < 0 ? '#dc2626' : '#64748b' }};">
                                {{ number_format($bank['audit_balance'], 2, '.', ',') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:40px;color:#94a3b8;font-size:13px;">
                                No data available for the selected date.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot style="background:#f0f4ff;border-top:2px solid #e2e8f0;position:sticky;bottom:0;z-index:4;">
                        <tr>
                            <td colspan="3" style="padding:10px 14px;font-size:10.5px;font-weight:700;color:#1E3A8A;letter-spacing:.06em;text-transform:uppercase;">Grand Total</td>
                            <td class="mono" style="padding:10px 14px;text-align:right;font-weight:800;color:{{ $totalBalance >= 0 ? '#1E3A8A' : '#dc2626' }};">{{ number_format($totalBalance, 2, '.', ',') }}</td>
                            <td class="mono" style="padding:10px 14px;text-align:right;font-weight:700;color:#64748b;">{{ number_format($totalBalance, 2, '.', ',') }}</td>
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
                <span class="rec-info" id="recInfo">Record <strong>1</strong> of <strong>{{ $totalRecords }}</strong></span>
                <a href="#" class="rec-btn {{ $totalRecords <= 1 ? 'disabled' : '' }}" id="navNext">›</a>
                <a href="#" class="rec-btn {{ $totalRecords <= 1 ? 'disabled' : '' }}" id="navLast">»</a>
            </div>
            <div style="font-size:11px;color:#94a3b8;">
                Visible: <strong id="visCount">{{ $totalRecords }}</strong>
                &nbsp;|&nbsp;
                Grand Total Balance: <strong class="mono" style="color:#1E3A8A;">{{ number_format($totalBalance, 2, '.', ',') }}</strong>
            </div>
        </div>
    </div>

</div>
</div>

<script>
// ── Row select ──────────────────────────────────
let _sel = null;
function selectBd(tr) {
    if (_sel) _sel.classList.remove('row-active');
    tr.classList.add('row-active'); _sel = tr;
    updateNav(parseInt(tr.dataset.row));
}

// ── Record nav ──────────────────────────────────
const totalR = {{ $totalRecords }};
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
    const rows = document.querySelectorAll('#bdTable tbody tr:not([style*="display:none"])');
    if (rows[idx]) { selectBd(rows[idx]); rows[idx].scrollIntoView({block:'nearest'}); }
}
document.getElementById('navFirst').addEventListener('click', e=>{e.preventDefault();goRow(0);});
document.getElementById('navPrev').addEventListener ('click', e=>{e.preventDefault();if(_idx>0)goRow(_idx-1);});
document.getElementById('navNext').addEventListener ('click', e=>{e.preventDefault();goRow(_idx+1);});
document.getElementById('navLast').addEventListener ('click', e=>{e.preventDefault();goRow(document.querySelectorAll('#bdTable tbody tr:not([style*="display:none"])').length-1);});

// ── Search ─────────────────────────────────────
function filterBd(q) {
    q = q.toLowerCase(); let vis = 0;
    document.querySelectorAll('#bdTable tbody tr').forEach(tr => {
        const show = tr.innerText.toLowerCase().includes(q);
        tr.style.display = show ? '' : 'none';
        if (show) vis++;
    });
    document.getElementById('visCount').textContent = vis;
}

// ── Sort ────────────────────────────────────────
let _sortDir = {};
function sortBd(colIdx) {
    const tbody = document.querySelector('#bdTable tbody');
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
    document.querySelectorAll('#bdTable th').forEach((th,i) => th.classList.toggle('sorted', i===colIdx));
}

// ── Drag-to-group ───────────────────────────────
document.querySelectorAll('#bdTable th[draggable]').forEach(th => {
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
    const first = document.querySelector('#bdTable tbody tr');
    if (first) selectBd(first);
});
</script>
@endsection
