@extends('layouts.app')
@section('title', 'Bank Account – Summary')

@push('styles')
<style>
    :root { --navy:#1E3A8A; --blue:#2563EB; --bg:#F8FAFC; }

    .page-fade { animation: pgIn .4s ease both; }
    @keyframes pgIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

    .tab-link { color:#64748b;text-decoration:none;padding-bottom:8px;margin-bottom:-9px;white-space:nowrap;font-size:13.5px;transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB;font-weight:700;border-bottom:2.5px solid #2563EB; }

    /* Info bar */
    .info-field { display:flex;flex-direction:column;gap:2px;padding-right:18px;border-right:1px solid #e2e8f0;margin-right:18px; }
    .info-field:last-child { border-right:none;padding-right:0;margin-right:0; }
    .info-label { font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase; }
    .info-value { font-size:14px;font-weight:700;color:#1e293b; }

    /* Date filter */
    .date-group { display:flex;flex-direction:column;gap:3px; }
    .date-group label { font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase; }
    .date-input { display:flex;align-items:center;gap:5px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:6px 10px;transition:box-shadow .2s; }
    .date-input:focus-within { box-shadow:0 0 0 3px rgba(37,99,235,.12);border-color:#2563EB; }
    .date-input input { border:none;outline:none;font-size:12.5px;color:#1e293b;background:transparent; }

    .btn-search  { display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#1E3A8A,#2563EB);color:#fff;font-size:12px;font-weight:600;border:none;border-radius:8px;padding:8px 16px;cursor:pointer;transition:opacity .2s; }
    .btn-search:hover { opacity:.88; }
    .btn-refresh { display:inline-flex;align-items:center;gap:6px;background:#fff;color:#475569;font-size:12px;font-weight:600;border:1px solid #e2e8f0;border-radius:8px;padding:7px 14px;cursor:pointer;transition:all .15s; }
    .btn-refresh:hover { border-color:#2563EB;color:#2563EB;background:#eff6ff; }

    /* ERP Grid */
    .erp-wrap { overflow-x:auto; }
    .erp-table { width:100%;border-collapse:collapse;min-width:1600px;table-layout:fixed; }
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
        padding:9px 10px;font-size:12px;border-bottom:1px solid #f1f5f9;
        border-right:1px solid #f8fafc;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    }
    .erp-table td:last-child { border-right:none; }
    .erp-table tbody tr { cursor:pointer;transition:background .12s; }
    .erp-table tbody tr:hover td { background:#f8fafc; }
    .erp-table tbody tr.row-active td { background:#dbeafe; }

    /* Group zone */
    .group-zone { display:flex;align-items:center;flex-wrap:wrap;gap:8px;background:#f1f5f9;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;padding:7px 14px;min-height:36px; }
    .group-hint { font-size:11px;color:#94a3b8;font-style:italic; }

    /* Search */
    .grid-search { display:flex;align-items:center;gap:6px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:5px 10px;transition:box-shadow .2s; }
    .grid-search:focus-within { box-shadow:0 0 0 3px rgba(37,99,235,.12);border-color:#2563EB; }
    .grid-search input { border:none;outline:none;font-size:12px;color:#1e293b;min-width:160px;background:transparent; }

    /* Scroll */
    .scroll-y { overflow-y:auto;max-height:460px; }
    .scroll-y::-webkit-scrollbar { width:5px; }
    .scroll-y::-webkit-scrollbar-thumb { background:#cbd5e1;border-radius:3px; }

    /* col group separators */
    .sep-left { border-left:2px solid #e2e8f0 !important; }

    /* badges */
    .badge-curr { font-size:9.5px;font-weight:700;background:#eff6ff;color:#2563EB;padding:1px 6px;border-radius:20px; }
    .badge-def  { font-size:9px;font-weight:700;background:#dbeafe;color:#1E3A8A;padding:1px 6px;border-radius:20px;margin-left:3px; }
    .mono { font-family:'Courier New',monospace; }
    .zero  { color:#cbd5e1; }
    .in-col  { color:#16a34a; }
    .out-col { color:#dc2626; }
    .neg-bal { color:#dc2626; }
    .pos-bal { color:#1E3A8A; }

    /* Record nav */
    .rec-nav { display:flex;align-items:center;gap:4px; }
    .rec-btn { display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-size:11px;font-weight:700;cursor:pointer;transition:all .15s;text-decoration:none; }
    .rec-btn:hover { background:#eff6ff;border-color:#2563EB;color:#2563EB; }
    .rec-btn.disabled { opacity:.35;pointer-events:none; }
    .rec-info { font-size:11px;font-weight:600;color:#64748b;padding:0 8px; }

    /* KPI */
    .kpi-sm { background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:12px 16px;box-shadow:0 2px 8px rgba(30,58,138,.05); }
</style>
@endpush

@section('content')
@php
    $firstBank = $banks->first();
    /* Use DB data if available, otherwise fall back to mock */
    $displayRows = $banks->count() > 0 ? $mockRows : $mockRows;
    /* … when BankTransaction is ready, build $displayRows from real queries */

    $totalRecords = count($displayRows);

    // Grand totals
    $gt_beg     = array_sum(array_column($displayRows,'beg_balance'));
    $gt_cashIn  = array_sum(array_column($displayRows,'cash_in'));
    $gt_giroIn  = array_sum(array_column($displayRows,'giro_in'));
    $gt_transIn = array_sum(array_column($displayRows,'trans_in'));
    $gt_receipt = array_sum(array_column($displayRows,'receipt'));
    $gt_cashOut = array_sum(array_column($displayRows,'cash_out'));
    $gt_giroOut = array_sum(array_column($displayRows,'giro_out'));
    $gt_payment = array_sum(array_column($displayRows,'payment'));
    $gt_transOut= array_sum(array_column($displayRows,'trans_out'));
    $gt_adjust  = array_sum(array_column($displayRows,'adjust'));
    $gt_balance = array_sum(array_column($displayRows,'balance'));

    $totalInflow  = $gt_cashIn + $gt_giroIn + $gt_transIn + $gt_receipt;
    $totalOutflow = $gt_cashOut + $gt_giroOut + $gt_payment + $gt_transOut;
@endphp

<div class="page-fade" style="background:var(--bg);min-height:calc(100vh - 62px);padding:28px 24px;">
<div style="max-width:1500px;margin:0 auto;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:14px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <a href="{{ route('finance.index') }}" style="color:#94a3b8;text-decoration:none;">Finance</a>
        <span>/</span>
        <a href="{{ route('finance.bank-accounts.index') }}" style="color:#94a3b8;text-decoration:none;">Bank Account</a>
        <span>/</span>
        <span style="color:#2563EB;font-weight:600;">Summary</span>
    </div>

    {{-- PAGE HEADER --}}
    <div style="display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:18px;">
        <div>
            <h1 style="font-size:26px;font-weight:800;margin:0 0 3px;color:#1e293b;display:flex;align-items:center;gap:10px;">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1E3A8A,#2563EB);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </span>
                Bank Account Summary
            </h1>
            <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">Cash-flow summary per bank for the selected period.</p>
        </div>
        <span style="align-self:center;font-size:11.5px;font-weight:600;background:#eff6ff;color:#2563EB;padding:5px 14px;border-radius:20px;border:1px solid #bfdbfe;">{{ $totalRecords }} Bank Accounts</span>
    </div>

    {{-- TABS --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.bank-accounts.record-detail') }}" class="tab-link">Record Detail</a>
        <a href="{{ route('finance.bank-accounts.records-list') }}" class="tab-link">Records List</a>
        <a href="{{ route('finance.bank-accounts.statistics') }}"  class="tab-link">Statistics</a>
        <a href="{{ route('finance.bank-accounts.activity') }}"   class="tab-link">Activity</a>
        <a href="{{ route('finance.bank-accounts.backdate') }}"   class="tab-link">Backdate</a>
        <a href="{{ route('finance.bank-accounts.summary') }}"    class="tab-link active">Summary</a>
    </div>

    {{-- FILTER BAR --}}
    <div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:14px 20px;margin-bottom:20px;box-shadow:0 2px 12px rgba(30,58,138,.06);">
        <form method="GET" action="{{ route('finance.bank-accounts.summary') }}" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:16px;">
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
            <div style="display:flex;gap:8px;align-items:flex-end;">
                <button type="submit" class="btn-search">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Search
                </button>
                <a href="{{ route('finance.bank-accounts.summary') }}" class="btn-refresh">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                    Refresh
                </a>
            </div>
        </form>
    </div>

    {{-- CASH POSITION KPI STRIP --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
        <div class="kpi-sm">
            <p style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 4px;">Total Beg. Balance</p>
            <p class="mono" style="font-size:15px;font-weight:800;color:#1E3A8A;margin:0;">{{ number_format($gt_beg, 2, '.', ',') }}</p>
        </div>
        <div class="kpi-sm" style="border-left:3px solid #16a34a;">
            <p style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 4px;">Total Inflow</p>
            <p class="mono" style="font-size:15px;font-weight:800;color:#16a34a;margin:0;">{{ number_format($totalInflow, 2, '.', ',') }}</p>
        </div>
        <div class="kpi-sm" style="border-left:3px solid #dc2626;">
            <p style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 4px;">Total Outflow</p>
            <p class="mono" style="font-size:15px;font-weight:800;color:#dc2626;margin:0;">{{ number_format($totalOutflow, 2, '.', ',') }}</p>
        </div>
        <div class="kpi-sm" style="border-left:3px solid #2563EB;">
            <p style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 4px;">Net Cash Position</p>
            <p class="mono" style="font-size:15px;font-weight:800;color:{{ $gt_balance >= 0 ? '#1E3A8A' : '#dc2626' }};margin:0;">{{ number_format($gt_balance, 2, '.', ',') }}</p>
        </div>
    </div>

    {{-- ERP GRID --}}
    <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 4px 18px rgba(30,58,138,.07);">

        {{-- Toolbar --}}
        <div style="padding:10px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;">Cash Flow Summary Grid</p>
            <div class="grid-search">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" id="sumSearch" placeholder="Search code, bank name…" oninput="filterSum(this.value)">
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
                <table class="erp-table" id="sumTable">
                    <thead>
                        <tr>
                            <th style="width:72px;" draggable="true" data-col="code" onclick="sortSum(0)">CODE <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:190px;" draggable="true" data-col="bank_name" onclick="sortSum(1)">BANK NAME <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:130px;text-align:right;" class="sep-left" draggable="true" data-col="beg_balance" onclick="sortSum(2)">BEG. BALANCE <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:110px;text-align:right;" class="sep-left" draggable="true" data-col="cash_in" onclick="sortSum(3)">CASH IN <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:100px;text-align:right;" draggable="true" data-col="giro_in" onclick="sortSum(4)">GIRO IN <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:110px;text-align:right;" draggable="true" data-col="trans_in" onclick="sortSum(5)">TRANS. IN <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:110px;text-align:right;" draggable="true" data-col="receipt" onclick="sortSum(6)">RECEIPT <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:110px;text-align:right;" class="sep-left" draggable="true" data-col="cash_out" onclick="sortSum(7)">CASH OUT <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:100px;text-align:right;" draggable="true" data-col="giro_out" onclick="sortSum(8)">GIRO OUT <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:110px;text-align:right;" draggable="true" data-col="payment" onclick="sortSum(9)">PAYMENT <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:110px;text-align:right;" draggable="true" data-col="trans_out" onclick="sortSum(10)">TRANS. OUT <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:90px;text-align:right;" class="sep-left" draggable="true" data-col="adjust" onclick="sortSum(11)">ADJUST <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                            <th style="width:130px;text-align:right;" class="sep-left" draggable="true" data-col="balance" onclick="sortSum(12)">BALANCE <svg class="sort-ico" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($displayRows as $i => $row)
                        @php
                            $isNegBal = $row['balance'] < 0;
                            $isNegBeg = $row['beg_balance'] < 0;
                            $n = fn($v) => $v == 0
                                ? '<span class="zero mono">0.00</span>'
                                : '<span class="mono ' . ($v < 0 ? 'out-col' : '') . '">' . number_format($v,2,'.',',') . '</span>';
                        @endphp
                        <tr data-row="{{ $i }}" onclick="selectSum(this)">
                            <td>
                                <span style="font-weight:700;color:#1e293b;">{{ $row['code'] }}</span>
                                @if($row['is_default'] ?? false)<span class="badge-def">DEF</span>@endif
                            </td>
                            <td style="color:#334155;font-weight:500;">{{ $row['bank_name'] }}</td>
                            <td class="mono sep-left" style="text-align:right;font-weight:600;color:{{ $isNegBeg ? '#dc2626' : '#1E3A8A' }};">{{ number_format($row['beg_balance'],2,'.',',') }}</td>
                            {{-- INFLOWS --}}
                            <td class="sep-left" style="text-align:right;">{!! $row['cash_in'] > 0 ? '<span class="mono in-col">'.number_format($row['cash_in'],2,'.',',').'</span>' : '<span class="zero mono">0.00</span>' !!}</td>
                            <td style="text-align:right;">{!! $row['giro_in'] > 0 ? '<span class="mono in-col">'.number_format($row['giro_in'],2,'.',',').'</span>' : '<span class="zero mono">0.00</span>' !!}</td>
                            <td style="text-align:right;">{!! $row['trans_in'] > 0 ? '<span class="mono in-col">'.number_format($row['trans_in'],2,'.',',').'</span>' : '<span class="zero mono">0.00</span>' !!}</td>
                            <td style="text-align:right;">{!! $row['receipt'] > 0 ? '<span class="mono in-col">'.number_format($row['receipt'],2,'.',',').'</span>' : '<span class="zero mono">0.00</span>' !!}</td>
                            {{-- OUTFLOWS --}}
                            <td class="sep-left" style="text-align:right;">{!! $row['cash_out'] > 0 ? '<span class="mono out-col">'.number_format($row['cash_out'],2,'.',',').'</span>' : '<span class="zero mono">0.00</span>' !!}</td>
                            <td style="text-align:right;">{!! $row['giro_out'] > 0 ? '<span class="mono out-col">'.number_format($row['giro_out'],2,'.',',').'</span>' : '<span class="zero mono">0.00</span>' !!}</td>
                            <td style="text-align:right;">{!! $row['payment'] > 0 ? '<span class="mono out-col">'.number_format($row['payment'],2,'.',',').'</span>' : '<span class="zero mono">0.00</span>' !!}</td>
                            <td style="text-align:right;">{!! $row['trans_out'] > 0 ? '<span class="mono out-col">'.number_format($row['trans_out'],2,'.',',').'</span>' : '<span class="zero mono">0.00</span>' !!}</td>
                            {{-- ADJUST --}}
                            <td class="sep-left" style="text-align:right;">{!! $row['adjust'] != 0 ? '<span class="mono">'.number_format($row['adjust'],2,'.',',').'</span>' : '<span class="zero mono">0.00</span>' !!}</td>
                            {{-- FINAL BALANCE --}}
                            <td class="sep-left mono" style="text-align:right;font-weight:700;color:{{ $isNegBal ? '#dc2626' : '#1E3A8A' }};">{{ number_format($row['balance'],2,'.',',') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" style="text-align:center;padding:40px;color:#94a3b8;font-size:13px;">No data for the selected period.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    {{-- GRAND TOTAL FOOTER --}}
                    <tfoot style="background:#f0f4ff;border-top:2px solid #e2e8f0;position:sticky;bottom:0;z-index:4;">
                        <tr>
                            <td colspan="2" style="padding:9px 10px;font-size:10px;font-weight:700;color:#1E3A8A;letter-spacing:.06em;text-transform:uppercase;">Grand Total</td>
                            <td class="mono sep-left" style="padding:9px 10px;text-align:right;font-weight:800;color:{{ $gt_beg >= 0 ? '#1E3A8A' : '#dc2626' }};">{{ number_format($gt_beg,2,'.',',') }}</td>
                            <td class="mono sep-left in-col" style="padding:9px 10px;text-align:right;font-weight:700;">{{ number_format($gt_cashIn,2,'.',',') }}</td>
                            <td class="mono in-col" style="padding:9px 10px;text-align:right;font-weight:700;">{{ number_format($gt_giroIn,2,'.',',') }}</td>
                            <td class="mono in-col" style="padding:9px 10px;text-align:right;font-weight:700;">{{ number_format($gt_transIn,2,'.',',') }}</td>
                            <td class="mono in-col" style="padding:9px 10px;text-align:right;font-weight:700;">{{ number_format($gt_receipt,2,'.',',') }}</td>
                            <td class="mono sep-left out-col" style="padding:9px 10px;text-align:right;font-weight:700;">{{ number_format($gt_cashOut,2,'.',',') }}</td>
                            <td class="mono out-col" style="padding:9px 10px;text-align:right;font-weight:700;">{{ number_format($gt_giroOut,2,'.',',') }}</td>
                            <td class="mono out-col" style="padding:9px 10px;text-align:right;font-weight:700;">{{ number_format($gt_payment,2,'.',',') }}</td>
                            <td class="mono out-col" style="padding:9px 10px;text-align:right;font-weight:700;">{{ number_format($gt_transOut,2,'.',',') }}</td>
                            <td class="mono sep-left" style="padding:9px 10px;text-align:right;font-weight:700;color:#475569;">{{ number_format($gt_adjust,2,'.',',') }}</td>
                            <td class="mono sep-left" style="padding:9px 10px;text-align:right;font-weight:800;color:{{ $gt_balance >= 0 ? '#1E3A8A' : '#dc2626' }};">{{ number_format($gt_balance,2,'.',',') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Footer nav --}}
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
                Net Cash Position: <strong class="mono" style="color:{{ $gt_balance >= 0 ? '#1E3A8A' : '#dc2626' }};">{{ number_format($gt_balance,2,'.',',') }}</strong>
            </div>
        </div>
    </div>

</div>
</div>

<script>
// ── Row select ─────────────────────────────────
let _sel = null;
function selectSum(tr) {
    if (_sel) _sel.classList.remove('row-active');
    tr.classList.add('row-active'); _sel = tr;
    updateNav(parseInt(tr.dataset.row));
}
// ── Record nav ─────────────────────────────────
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
    const rows = document.querySelectorAll('#sumTable tbody tr:not([style*="display:none"])');
    if (rows[idx]) { selectSum(rows[idx]); rows[idx].scrollIntoView({block:'nearest'}); }
}
document.getElementById('navFirst').addEventListener('click', e=>{e.preventDefault();goRow(0);});
document.getElementById('navPrev').addEventListener ('click', e=>{e.preventDefault();if(_idx>0)goRow(_idx-1);});
document.getElementById('navNext').addEventListener ('click', e=>{e.preventDefault();goRow(_idx+1);});
document.getElementById('navLast').addEventListener ('click', e=>{e.preventDefault();goRow(document.querySelectorAll('#sumTable tbody tr:not([style*="display:none"])').length-1);});

// ── Search ─────────────────────────────────────
function filterSum(q) {
    q = q.toLowerCase(); let vis = 0;
    document.querySelectorAll('#sumTable tbody tr').forEach(tr => {
        const show = tr.innerText.toLowerCase().includes(q);
        tr.style.display = show ? '' : 'none';
        if (show) vis++;
    });
    document.getElementById('visCount').textContent = vis;
}
// ── Sort ───────────────────────────────────────
let _sd = {};
function sortSum(ci) {
    const tbody = document.querySelector('#sumTable tbody');
    const rows  = Array.from(tbody.querySelectorAll('tr'));
    const asc   = !_sd[ci]; _sd = {}; _sd[ci] = asc;
    rows.sort((a,b) => {
        const av = (a.cells[ci]?.innerText||'').trim().replace(/[,\s]/g,'');
        const bv = (b.cells[ci]?.innerText||'').trim().replace(/[,\s]/g,'');
        const an = parseFloat(av), bn = parseFloat(bv);
        if (!isNaN(an)&&!isNaN(bn)) return asc?an-bn:bn-an;
        return asc?av.localeCompare(bv):bv.localeCompare(av);
    });
    rows.forEach(r=>tbody.appendChild(r));
    document.querySelectorAll('#sumTable th').forEach((th,i)=>th.classList.toggle('sorted',i===ci));
}
// ── Drag-to-group ───────────────────────────────
document.querySelectorAll('#sumTable th[draggable]').forEach(th => {
    th.addEventListener('dragstart', e=>{e.dataTransfer.setData('text/plain',th.dataset.col+'|'+th.innerText.trim().split('\n')[0].trim());th.style.opacity='.45';});
    th.addEventListener('dragend', ()=>th.style.opacity='');
});
const gz = document.getElementById('groupZone');
gz.addEventListener('dragover',  e=>{e.preventDefault();gz.style.background='#dbeafe';});
gz.addEventListener('dragleave', ()=>gz.style.background='');
gz.addEventListener('drop', e=>{
    e.preventDefault();gz.style.background='';
    const [col,label] = e.dataTransfer.getData('text/plain').split('|');
    if (document.querySelector('[data-group="'+col+'"]')) return;
    const badge = document.createElement('span');
    badge.dataset.group = col;
    badge.style.cssText = 'display:inline-flex;align-items:center;gap:5px;background:#2563EB;color:#fff;font-size:10px;font-weight:600;padding:2px 10px;border-radius:20px;';
    badge.innerHTML = label+' <span style="cursor:pointer;font-size:13px;" onclick="this.parentElement.remove()">×</span>';
    document.getElementById('groupBadges').appendChild(badge);
    document.querySelector('.group-hint').style.display='none';
});
// ── Auto-select first ───────────────────────────
document.addEventListener('DOMContentLoaded', ()=>{
    const first = document.querySelector('#sumTable tbody tr');
    if (first) selectSum(first);
});
</script>
@endsection
