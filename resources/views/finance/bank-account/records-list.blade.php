@extends('layouts.app')
@section('title', 'Bank Account – Records List')

@push('styles')
<style>
    :root { --blue:#2563EB; --navy:#1E3A8A; --bg:#F8FAFC; }

    .page-fade { animation: pgIn .4s ease both; }
    @keyframes pgIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

    .tab-link { color:#64748b;text-decoration:none;padding-bottom:8px;margin-bottom:-9px;white-space:nowrap;font-size:13.5px;transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB;font-weight:700;border-bottom:2.5px solid #2563EB; }

    /* ERP Grid */
    .erp-table { width:100%;table-layout:fixed;border-collapse:collapse;min-width:1100px; }
    .erp-table thead tr { background:#f8fafc; }
    .erp-table th {
        font-size:10px;font-weight:700;color:#64748b;letter-spacing:.07em;
        text-transform:uppercase;padding:10px 12px;border-bottom:2px solid #e2e8f0;
        border-right:1px solid #f1f5f9;cursor:pointer;user-select:none;
        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        transition:background .15s;position:sticky;top:0;z-index:5;
        resize:horizontal; /* allow column resizing */
    }
    .erp-table th:last-child { border-right:none; }
    .erp-table th:hover { background:#eff6ff; }
    .erp-table th .sort-ico { opacity:0;transition:opacity .15s;vertical-align:middle;margin-left:4px; }
    .erp-table th:hover .sort-ico,
    .erp-table th.sorted .sort-ico { opacity:1; }
    .erp-table th.sorted { color:#2563EB; }

    .erp-table td {
        padding:9px 12px;font-size:12.5px;border-bottom:1px solid #f1f5f9;
        border-right:1px solid #f8fafc;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    }
    .erp-table td:last-child { border-right:none; }
    .erp-table tbody tr { cursor:pointer;transition:background .12s; }
    .erp-table tbody tr:hover td { background:#f8fafc; }
    .erp-table tbody tr.row-active td { background:#dbeafe; }

    .scroll-y { overflow-y:auto;max-height:480px; }
    .scroll-y::-webkit-scrollbar { width:5px; }
    .scroll-y::-webkit-scrollbar-thumb { background:#cbd5e1;border-radius:4px; }

    /* Group zone */
    .group-zone {
        display:flex;align-items:center;flex-wrap:wrap;gap:8px;
        background:#f1f5f9;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;
        padding:8px 16px;min-height:38px;
    }
    .group-hint { font-size:11.5px;color:#94a3b8;font-style:italic; }

    /* Search */
    .search-input {
        display:flex;align-items:center;gap:6px;background:#fff;
        border:1px solid #e2e8f0;border-radius:9px;padding:6px 12px;
        transition:box-shadow .2s;
    }
    .search-input:focus-within { box-shadow:0 0 0 3px rgba(37,99,235,.12);border-color:#2563EB; }
    .search-input input { border:none;outline:none;font-size:13px;color:#1e293b;min-width:180px;background:transparent; }

    /* Record nav */
    .rec-nav { display:flex;align-items:center;gap:4px; }
    .rec-btn { display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:6px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-size:12px;font-weight:700;cursor:pointer;transition:all .15s;text-decoration:none; }
    .rec-btn:hover { background:#eff6ff;border-color:#2563EB;color:#2563EB; }
    .rec-btn.disabled { opacity:.35;pointer-events:none; }
    .rec-info { font-size:11.5px;font-weight:600;color:#64748b;padding:0 8px; }

    /* Badges */
    .badge-curr { font-size:10px;font-weight:700;background:#eff6ff;color:#2563EB;padding:2px 7px;border-radius:20px; }
    .badge-audit { font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px; }
    .badge-pass { background:#f0fdf4;color:#16a34a; }
    .badge-other { background:#fefce8;color:#854d0e; }
    .badge-default { font-size:9.5px;font-weight:700;background:#eff6ff;color:#2563EB;padding:1px 7px;border-radius:20px;margin-left:4px;vertical-align:middle; }

    .mono { font-family:'Courier New',monospace; }
    .negative { color:#dc2626; }
    .positive { color:#16a34a; }
</style>
@endpush

@section('content')
@php
    /* Use DB data if available, otherwise fall back to mock */
    $displayBanks = $banks->count() > 0
        ? $banks->map(fn($b) => [
            'code'         => $b->code,
            'currency'     => $b->currency,
            'bank_name'    => $b->bank_name,
            'category'     => $b->category,
            'balance'      => $b->balance,
            'sys_balance'  => $b->balance,
            'account_code' => $b->account_code,
            'ar_account'   => $b->ar_account,
            'cost_center'  => $b->cost_center,
            'department'   => $b->department,
            'audit'        => $b->audit,
            'is_default'   => $b->is_default,
          ])->toArray()
        : $mockBanks;

    $totalRecords = count($displayBanks);
@endphp

<div class="page-fade" style="background:var(--bg);min-height:calc(100vh - 62px);padding:28px 24px;">
<div style="max-width:1380px;margin:0 auto;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:14px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <a href="{{ route('finance.index') }}" style="color:#94a3b8;text-decoration:none;">Finance</a>
        <span>/</span>
        <span style="color:#2563EB;font-weight:600;">Bank Account</span>
    </div>

    {{-- PAGE HEADER --}}
    <div style="display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:18px;">
        <div>
            <h1 style="font-size:26px;font-weight:800;margin:0 0 3px;color:#1e293b;display:flex;align-items:center;gap:10px;">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1E3A8A,#2563EB);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                </span>
                Bank Account
            </h1>
            <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">All registered bank accounts and account balances.</p>
        </div>
        <span style="align-self:flex-end;font-size:11.5px;font-weight:600;background:#eff6ff;color:#2563EB;padding:5px 14px;border-radius:20px;border:1px solid #bfdbfe;">
            {{ $totalRecords }} Records
        </span>
    </div>

    {{-- TAB BAR --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.bank-accounts.record-detail') }}" class="tab-link">Record Detail</a>
        <a href="{{ route('finance.bank-accounts.records-list') }}" class="tab-link active">Records List</a>
        <a href="{{ route('finance.bank-accounts.statistics') }}" class="tab-link">Statistics</a>
        <a href="{{ route('finance.bank-accounts.activity') }}" class="tab-link">Activity</a>
        <a href="{{ route('finance.bank-accounts.backdate') }}" class="tab-link">Backdate</a>
        <a href="{{ route('finance.bank-accounts.summary') }}" class="tab-link">Summary</a>
    </div>

    {{-- ERP GRID CARD --}}
    <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 4px 18px rgba(30,58,138,.07);">

        {{-- Grid Toolbar --}}
        <div style="padding:12px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;">Bank Account Grid</p>
            <div class="search-input">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" id="bankSearch" placeholder="Search code, bank name, account…" oninput="filterGrid(this.value)">
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
            <div style="overflow-x:auto;">
                <table class="erp-table" id="bankTable">
                    <thead>
                        <tr>
                            <th style="width:80px;" draggable="true" data-col="code" onclick="sortGrid(0)">
                                CODE <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:60px;" draggable="true" data-col="curr" onclick="sortGrid(1)">
                                CURR <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:200px;" draggable="true" data-col="bank_name" onclick="sortGrid(2)">
                                BANK NAME <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:100px;" draggable="true" data-col="category" onclick="sortGrid(3)">
                                CATEGORY <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:150px;text-align:right;" draggable="true" data-col="balance" onclick="sortGrid(4)">
                                BALANCE <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:150px;text-align:right;" draggable="true" data-col="sys_balance" onclick="sortGrid(5)">
                                [BALANCE] <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:110px;" draggable="true" data-col="account" onclick="sortGrid(6)">
                                ACCOUNT <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:110px;" draggable="true" data-col="receivable" onclick="sortGrid(7)">
                                RECEIVABLE <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:70px;" draggable="true" data-col="cost" onclick="sortGrid(8)">
                                COST <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:70px;" draggable="true" data-col="dept" onclick="sortGrid(9)">
                                DEPT <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:80px;" draggable="true" data-col="audit" onclick="sortGrid(10)">
                                AUDIT <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($displayBanks as $i => $bank)
                        @php
                            $balance    = $bank['balance'];
                            $sysBalance = $bank['sys_balance'];
                            $isNeg      = $balance < 0;
                            $auditLower = strtolower($bank['audit'] ?? '');
                        @endphp
                        <tr data-row="{{ $i }}" onclick="selectRow(this)">
                            <td>
                                <span style="font-weight:700;color:#1e293b;">{{ $bank['code'] }}</span>
                                @if($bank['is_default'] ?? false)
                                    <span class="badge-default">DEFAULT</span>
                                @endif
                            </td>
                            <td><span class="badge-curr">{{ $bank['currency'] }}</span></td>
                            <td style="color:#334155;font-weight:500;">{{ $bank['bank_name'] }}</td>
                            <td style="color:#64748b;font-size:11.5px;">{{ $bank['category'] ?? '—' }}</td>
                            <td class="mono" style="text-align:right;font-weight:600;color:{{ $isNeg ? '#dc2626' : '#1e293b' }};">
                                {{ number_format($balance, 2, '.', ',') }}
                            </td>
                            <td class="mono" style="text-align:right;color:{{ $sysBalance < 0 ? '#dc2626' : '#64748b' }};">
                                {{ number_format($sysBalance, 2, '.', ',') }}
                            </td>
                            <td style="color:#2563EB;font-size:12px;font-weight:600;">{{ $bank['account_code'] ?? '—' }}</td>
                            <td style="color:#4F46E5;font-size:12px;">{{ $bank['ar_account'] ?? '—' }}</td>
                            <td style="color:#64748b;text-align:center;">{{ $bank['cost_center'] ?? '—' }}</td>
                            <td style="color:#64748b;text-align:center;">{{ $bank['department'] ?? '—' }}</td>
                            <td>
                                @if($auditLower === 'pass')
                                    <span class="badge-audit badge-pass">PASS</span>
                                @else
                                    <span class="badge-audit badge-other">{{ $bank['audit'] ?? '—' }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Grid Footer: record nav + total --}}
        <div style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:10px 18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div class="rec-nav">
                <a href="#" class="rec-btn disabled" id="navFirst" title="First">«</a>
                <a href="#" class="rec-btn disabled" id="navPrev" title="Previous">‹</a>
                <span class="rec-info" id="recInfo">Record <strong>1</strong> of <strong>{{ $totalRecords }}</strong></span>
                <a href="#" class="rec-btn" id="navNext" title="Next">›</a>
                <a href="#" class="rec-btn" id="navLast" title="Last">»</a>
            </div>
            <div style="display:flex;gap:18px;align-items:center;">
                <span style="font-size:11px;color:#94a3b8;">Visible: <strong id="visibleCount">{{ $totalRecords }}</strong></span>
                <a href="{{ route('finance.bank-accounts.index') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#1E3A8A,#2563EB);color:#fff;font-size:12px;font-weight:600;border-radius:8px;padding:6px 16px;text-decoration:none;transition:opacity .2s;" class="hover:opacity-90">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    New Record
                </a>
            </div>
        </div>
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
    document.getElementById('recInfo').innerHTML = 'Record <strong>'+(idx+1)+'</strong> of <strong>'+totalRows+'</strong>';
    document.getElementById('navFirst').className = 'rec-btn' + (idx===0?' disabled':'');
    document.getElementById('navPrev').className  = 'rec-btn' + (idx===0?' disabled':'');
    document.getElementById('navNext').className  = 'rec-btn' + (idx>=totalRows-1?' disabled':'');
    document.getElementById('navLast').className  = 'rec-btn' + (idx>=totalRows-1?' disabled':'');
}
function goRow(idx) {
    const rows = document.querySelectorAll('#bankTable tbody tr:not([style*="display:none"])');
    if (rows[idx]) { selectRow(rows[idx]); rows[idx].scrollIntoView({block:'nearest'}); }
}
document.getElementById('navFirst').addEventListener('click', e=>{e.preventDefault();goRow(0);});
document.getElementById('navPrev').addEventListener ('click', e=>{e.preventDefault();if(_currentIdx>0)goRow(_currentIdx-1);});
document.getElementById('navNext').addEventListener ('click', e=>{e.preventDefault();goRow(_currentIdx+1);});
document.getElementById('navLast').addEventListener ('click', e=>{e.preventDefault();goRow(document.querySelectorAll('#bankTable tbody tr:not([style*="display:none"])').length-1);});

// ── Search / filter ───────────────────────────────────────────
function filterGrid(q) {
    q = q.toLowerCase();
    let visible = 0;
    document.querySelectorAll('#bankTable tbody tr').forEach(tr => {
        const text = tr.innerText.toLowerCase();
        const show = text.includes(q);
        tr.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('visibleCount').textContent = visible;
}

// ── Sort ──────────────────────────────────────────────────────
let _sortDir = {};
function sortGrid(colIdx) {
    const tbody = document.querySelector('#bankTable tbody');
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
    document.querySelectorAll('#bankTable th').forEach((th,i) => {
        th.classList.toggle('sorted', i===colIdx);
    });
}

// ── Drag-to-group ─────────────────────────────────────────────
document.querySelectorAll('#bankTable th[draggable]').forEach(th => {
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

// Select first row on load
document.addEventListener('DOMContentLoaded', () => {
    const firstRow = document.querySelector('#bankTable tbody tr');
    if (firstRow) selectRow(firstRow);
});
</script>
@endsection
