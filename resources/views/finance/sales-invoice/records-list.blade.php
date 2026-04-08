@extends('layouts.app')
@section('title', 'Sales Invoice - Records List')

@push('styles')
<style>
    :root { --blue:#2563EB; --navy:#1E3A8A; --bg:#F8FAFC; }

    .page-fade { animation: pgIn .4s ease both; }
    @keyframes pgIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

    .tab-link { color:#64748b;text-decoration:none;padding-bottom:8px;margin-bottom:-9px;white-space:nowrap;font-size:13.5px;transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB;font-weight:700;border-bottom:2.5px solid #2563EB; }

    /* ERP Grid */
    .erp-table { width:100%;table-layout:fixed;border-collapse:collapse;min-width:2400px; }
    .erp-table thead tr { background:#f8fafc; }
    .erp-table th {
        font-size:10px;font-weight:700;color:#64748b;letter-spacing:.07em;
        text-transform:uppercase;padding:8px 10px;border-bottom:2px solid #e2e8f0;
        border-right:1px solid #f1f5f9;cursor:pointer;user-select:none;
        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        transition:background .15s;position:sticky;top:0;z-index:5;
        resize:horizontal;
    }
    .erp-table th:last-child { border-right:none; }
    .erp-table th:hover { background:#eff6ff; }
    .erp-table th .sort-ico { opacity:0;transition:opacity .15s;vertical-align:middle;margin-left:4px; }
    .erp-table th:hover .sort-ico,
    .erp-table th.sorted .sort-ico { opacity:1; }
    .erp-table th.sorted { color:#2563EB; }

    /* Freeze Columns */
    .col-freeze-1 { position:sticky; left:0; z-index:6; background:#f8fafc; }
    .col-freeze-2 { position:sticky; left:45px; z-index:6; background:#f8fafc; border-right:2px solid #e2e8f0 !important; }
    .erp-table th.col-freeze-1, .erp-table th.col-freeze-2 { z-index:7; } /* header sits above body frozen cell */

    .erp-table td {
        padding:7px 10px;font-size:11.5px;border-bottom:1px solid #f1f5f9;
        border-right:1px solid #f8fafc;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    }
    .erp-table td.col-freeze-1, .erp-table td.col-freeze-2 { background:#fff; }
    
    .erp-table td:last-child { border-right:none; }
    .erp-table tbody tr { cursor:pointer;transition:background .12s; }
    .erp-table tbody tr:hover td { background:#f8fafc; }
    .erp-table tbody tr:hover td.col-freeze-1, .erp-table tbody tr:hover td.col-freeze-2 { background:#f8fafc; }
    
    .erp-table tbody tr.row-active td { background:#dbeafe !important; }
    
    /* Overdue Red Flag */
    .row-overdue td { color:#b91c1c; }

    .scroll-y { overflow-y:auto;max-height:550px; }
    .scroll-y::-webkit-scrollbar { width:5px; height:5px; }
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
    .badge-paid { font-size:10px;font-weight:700;background:#dcfce7;color:#166534;padding:2px 7px;border-radius:20px; }
    .badge-unpaid { font-size:10px;font-weight:700;background:#fee2e2;color:#b91c1c;padding:2px 7px;border-radius:20px; }
    .badge-partial { font-size:10px;font-weight:700;background:#fef3c7;color:#b45309;padding:2px 7px;border-radius:20px; }

    .mono { font-variant-numeric:tabular-nums; }
    .align-r { text-align:right; }
    .align-c { text-align:center; }
</style>
@endpush

@section('content')
@php
    $totalRecords = count($invoices);
    
    $sumTlSales = 0;
    $sumTax = 0;
    $sumPph = 0;
    $sumTotal = 0;
    $sumBalance = 0;
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
                Sales Invoice Data
            </h1>
            <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">Unified Single Grid for all invoice and billing audit views.</p>
        </div>
        <span style="align-self:flex-end;font-size:11.5px;font-weight:600;background:#eff6ff;color:#2563EB;padding:5px 14px;border-radius:20px;border:1px solid #bfdbfe;">
            {{ $totalRecords }} Records
        </span>
    </div>

    {{-- TAB BAR --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.sales-invoice.index') }}" class="tab-link">Record Detail</a>
        <a href="{{ route('finance.sales-invoice.records-list') }}" class="tab-link active">Records List</a>
        <a href="{{ route('finance.sales-invoice.custom-group') }}" class="tab-link">Custom Group</a>
        <a href="{{ route('finance.sales-invoice.detail-list') }}" class="tab-link">Detail Invoice List</a>
    </div>

    {{-- ERP GRID CARD --}}
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 4px 18px rgba(30,58,138,.07);">

        {{-- Grid Toolbar --}}
        <div style="padding:12px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;">Transactions Ledger Grid</p>
            <div class="search-input">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" id="invSearch" placeholder="Global search all columns..." oninput="filterGrid(this.value)">
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
            <div>
                <table class="erp-table" id="invTable">
                    <thead>
                        <tr>
                            <!-- FROZEN COLUMNS -->
                            <th class="col-freeze-1 align-c" style="width:45px;" draggable="true" data-col="appr" onclick="sortGrid(0)">
                                APPR <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th class="col-freeze-2" style="width:90px;" draggable="true" data-col="date" onclick="sortGrid(1)">
                                DATE <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <!-- NORMAL COLUMNS -->
                            <th style="width:140px;" draggable="true" data-col="user_no" onclick="sortGrid(2)">
                                USER NO <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:140px;" draggable="true" data-col="ref" onclick="sortGrid(3)">
                                REF <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:200px;" draggable="true" data-col="customer_name" onclick="sortGrid(4)">
                                CUSTOMER NAME <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:120px;" draggable="true" data-col="bu" onclick="sortGrid(5)">
                                BUSINESS UNIT <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:80px;" draggable="true" data-col="link" onclick="sortGrid(6)">
                                LINK <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th class="align-r" style="width:130px;" draggable="true" data-col="tlsales" onclick="sortGrid(7)">
                                TL. SALES <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th class="align-r" style="width:110px;" draggable="true" data-col="tax" onclick="sortGrid(8)">
                                TAX <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th class="align-r" style="width:110px;" draggable="true" data-col="pph" onclick="sortGrid(9)">
                                PPH 23 <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th class="align-r" style="width:130px;" draggable="true" data-col="total" onclick="sortGrid(10)">
                                TOTAL <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th class="align-r" style="width:120px;" draggable="true" data-col="paid" onclick="sortGrid(11)">
                                PAID <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th class="align-r" style="width:100px;" draggable="true" data-col="disc" onclick="sortGrid(12)">
                                DISC <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th class="align-r" style="width:130px; font-weight:800; color:#1e3a8a;" draggable="true" data-col="balance" onclick="sortGrid(13)">
                                BALANCE <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:120px;" draggable="true" data-col="po" onclick="sortGrid(14)">
                                PO. CUSTOMER <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:120px;" draggable="true" data-col="quo" onclick="sortGrid(15)">
                                QUOTATION <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:130px;" draggable="true" data-col="taxno" onclick="sortGrid(16)">
                                TAX NO <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:130px;" draggable="true" data-col="recno" onclick="sortGrid(17)">
                                RECEIPT NO <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:160px;" draggable="true" data-col="note" onclick="sortGrid(18)">
                                NOTE <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="width:90px;" class="align-c" draggable="true" data-col="audit" onclick="sortGrid(19)">
                                AUDIT <svg class="sort-ico" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $i => $inv)
                        @php
                            $isOverdue = $inv->due_date && \Carbon\Carbon::parse($inv->due_date)->isPast() && $inv->balance > 0;
                            $rowClass = $isOverdue ? 'row-overdue' : '';
                            
                            $badgeHtml = '';
                            if ($inv->balance <= 0 && $inv->total > 0) {
                                $badgeHtml = '<span class="badge-paid">PAID</span>';
                            } elseif ($inv->paid > 0 && $inv->balance > 0) {
                                $badgeHtml = '<span class="badge-partial">PARTIAL</span>';
                            } elseif ($inv->total > 0 && $inv->paid == 0) {
                                $badgeHtml = '<span class="badge-unpaid">UNPAID</span>';
                            }

                            // Math
                            $sumTlSales += $inv->subtotal;
                            $sumTax += $inv->tax;
                            $sumPph += $inv->pph23;
                            $sumTotal += $inv->total;
                            $sumBalance += $inv->balance;
                        @endphp
                        <tr data-row="{{ $i }}" class="{{ $rowClass }}" onclick="selectRow(this)" ondblclick="window.location.href='{{ route('finance.sales-invoice.index') }}?id={{ $inv->id }}'">
                            <td class="col-freeze-1 align-c">
                                <input type="checkbox" disabled {!! $inv->approved ? 'checked' : '' !!}>
                            </td>
                            <td class="col-freeze-2">{{ \Carbon\Carbon::parse($inv->date)->format('d/m/Y') }}</td>
                            <td><span style="font-weight:700;">{{ $inv->invoice_number }}</span></td>
                            <td>{{ $inv->reference ?? '—' }}</td>
                            <td style="font-weight:600;color:#1e3a8a;">{{ $inv->customer ? $inv->customer->name : 'N/A' }}</td>
                            <td>{{ $inv->business_unit ?? '—' }}</td>
                            <td>—</td> <!-- Link external logic if any -->
                            
                            <!-- Amounts (Right Aligned) -->
                            <td class="mono align-r">{{ number_format($inv->subtotal, 2) }}</td>
                            <td class="mono align-r">{{ number_format($inv->tax, 2) }}</td>
                            <td class="mono align-r">{{ number_format($inv->pph23, 2) }}</td>
                            <td class="mono align-r" style="font-weight:600;">{{ number_format($inv->total, 2) }}</td>
                            <td class="mono align-r" style="color:#059669;">{{ number_format($inv->paid, 2) }}</td>
                            <td class="mono align-r">{{ number_format($inv->discount, 2) }}</td>
                            <td class="mono align-r" style="font-weight:700; color:{!! $inv->balance > 0 ? '#b91c1c' : '#1e293b' !!};">
                                {{ number_format($inv->balance, 2) }}
                            </td>
                            
                            <!-- Metadatas -->
                            <td>{{ $inv->po_customer ?? '—' }}</td>
                            <td>{{ $inv->quotation ?? '—' }}</td>
                            <td>{{ $inv->tax_no ?? '—' }}</td>
                            <td>{{ $inv->receipt_no ?? '—' }}</td>
                            <td>{{ $inv->note ? \Illuminate\Support\Str::limit($inv->note, 30) : '—' }}</td>
                            
                            <td class="align-c">{!! $badgeHtml !!} {{ $inv->audit }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="position:sticky; bottom:0; background:#f8fafc; z-index:3; font-weight:800; border-top:2px solid #cbd5e1;">
                        <tr>
                            <td colspan="7" class="align-r" style="padding:10px 12px;">GRAND TOTAL</td>
                            <td class="mono align-r">{{ number_format($sumTlSales, 2) }}</td>
                            <td class="mono align-r">{{ number_format($sumTax, 2) }}</td>
                            <td class="mono align-r">{{ number_format($sumPph, 2) }}</td>
                            <td class="mono align-r">{{ number_format($sumTotal, 2) }}</td>
                            <td class="mono align-r"></td>
                            <td class="mono align-r"></td>
                            <td class="mono align-r" style="color:#b91c1c;">{{ number_format($sumBalance, 2) }}</td>
                            <td colspan="6"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Grid Footer: record nav + info --}}
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
                <span style="font-size:11px;color:#94a3b8;">Status: <strong>Ready</strong></span>
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
    let visible = 0;
    document.querySelectorAll('#invTable tbody tr').forEach(tr => {
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

// Select first row on load
document.addEventListener('DOMContentLoaded', () => {
    const firstRow = document.querySelector('#invTable tbody tr');
    if (firstRow) selectRow(firstRow);
});
</script>
@endsection
