@extends('layouts.app')
@section('title', 'Activity – Customer Finance')

@push('styles')
<style>
    :root { --blue:#2563EB; --navy:#1E3A8A; --accent:#4F46E5; --bg:#F8FAFC; }

    .page-fade { animation: pgIn .4s ease both; }
    @keyframes pgIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

    .tab-link { color:#64748b; text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; font-size:13.5px; transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB; font-weight:700; border-bottom:2.5px solid #2563EB; }

    /* Ledger table */
    .ledger-table { width:100%; border-collapse:collapse; min-width:980px; }
    .ledger-table thead tr { background:#f8fafc; position:sticky; top:0; z-index:5; }
    .ledger-table th {
        font-size:10.5px; font-weight:700; color:#64748b; letter-spacing:.07em;
        text-transform:uppercase; padding:12px 14px; border-bottom:2px solid #e2e8f0;
        cursor:pointer; white-space:nowrap; user-select:none; transition:background .15s;
    }
    .ledger-table th:hover { background:#eff6ff; }
    .ledger-table th .sort-ico { opacity:0; transition:opacity .15s; vertical-align:middle; }
    .ledger-table th:hover .sort-ico { opacity:1; }
    .ledger-table td { padding:11px 14px; font-size:13px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
    .ledger-table tbody tr { cursor:pointer; transition:background .15s; }
    .ledger-table tbody tr:hover td { background:#f8fafc; }
    .ledger-table tbody tr.row-active td { background:#dbeafe; }
    .ledger-table tbody tr.row-active:hover td { background:#bfdbfe; }
    .ledger-table tbody tr:last-child td { border-bottom:none; }

    .scroll-body { overflow-y:auto; max-height:420px; }
    .scroll-body::-webkit-scrollbar { width:5px; }
    .scroll-body::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }

    .filter-input {
        display:flex; align-items:center; gap:0;
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

    .sum-pill {
        display:flex; flex-direction:column; gap:2px;
        background:#fff; border-radius:12px; padding:12px 20px;
        border:1px solid #e2e8f0; min-width:160px;
    }

    .badge-curr { font-size:10px; font-weight:700; background:#eff6ff; color:#2563EB; padding:2px 8px; border-radius:20px; display:inline-block; }
    .badge-doc  { font-size:11.5px; font-weight:600; color:#2563EB; }
    .mono { font-family:'Courier New',monospace; }
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
        <span>/</span><span style="color:#2563EB;font-weight:600;">Activity</span>
    </div>

    {{-- PAGE HEADER --}}
    <div style="margin-bottom:22px;">
        <h1 style="font-size:26px;font-weight:800;margin:0 0 3px;color:#1e293b;display:flex;align-items:center;gap:10px;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1E3A8A,#2563EB);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </span>
            Customer Activity Ledger
        </h1>
        <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">Transaction history and running balance per customer.</p>
    </div>

    {{-- TAB BAR --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.customers.detail') }}" class="tab-link">Detail View</a>
        <a href="{{ route('finance.customers.index') }}" class="tab-link">List All</a>
        <a href="{{ route('finance.customers.statistic') }}" class="tab-link">Statistic</a>
        <a href="{{ route('finance.customers.activity') }}" class="tab-link active">Activity</a>
        <a href="{{ route('finance.customers.backdate') }}" class="tab-link">Backdate</a>
        <a href="{{ route('finance.customers.summary') }}" class="tab-link">Summary</a>
    </div>

    {{-- CUSTOMER INFO BAR --}}
    <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0;background:#fff;border-radius:16px;box-shadow:0 2px 12px rgba(30,58,138,.07);border:1px solid #e2e8f0;padding:16px 22px;margin-bottom:18px;">
        <div style="display:flex;flex-direction:column;gap:2px;padding-right:24px;border-right:1px solid #e2e8f0;margin-right:24px;">
            <span style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;">Code</span>
            <span style="font-size:15px;font-weight:700;color:#1e293b;">001</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:2px;padding-right:24px;border-right:1px solid #e2e8f0;margin-right:24px;">
            <span style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;">Currency</span>
            <span style="font-size:15px;font-weight:700;color:#1e293b;">IDR</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:2px;">
            <span style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;">Customer Name</span>
            <span style="font-size:15px;font-weight:700;color:#1E3A8A;">PT. JASA SWADAYA UTAMA</span>
        </div>
    </div>

    {{-- FILTER CARD --}}
    <div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:18px 22px;margin-bottom:18px;box-shadow:0 2px 10px rgba(30,58,138,.05);">
        <form method="GET" action="{{ route('finance.customers.activity') }}" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:16px;">
            <div style="display:flex;flex-direction:column;gap:6px;">
                <label style="font-size:10.5px;font-weight:700;color:#64748b;letter-spacing:.07em;text-transform:uppercase;">From Date</label>
                <div class="filter-input">
                    <span class="fi-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                    <input type="date" name="from_date" value="{{ request('from_date','2026-03-02') }}">
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:6px;">
                <label style="font-size:10.5px;font-weight:700;color:#64748b;letter-spacing:.07em;text-transform:uppercase;">Thru Date</label>
                <div class="filter-input">
                    <span class="fi-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                    <input type="date" name="thru_date" value="{{ request('thru_date','2026-03-16') }}">
                </div>
            </div>
            <button type="submit" class="btn-search">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Search
            </button>
        </form>
    </div>

    @php
        $activities = [
            ['date'=>'02/03/2026','userno'=>'INV-2026-0301','note'=>'Invoice penjualan barang','debit'=>5000000,'credit'=>0,'balance'=>5000000,'ref'=>'SO-001','curr'=>'IDR'],
            ['date'=>'04/03/2026','userno'=>'RCV-2026-0302','note'=>'Penerimaan pembayaran','debit'=>0,'credit'=>5000000,'balance'=>0,'ref'=>'BNK-001','curr'=>'IDR'],
            ['date'=>'06/03/2026','userno'=>'INV-2026-0303','note'=>'Invoice jasa konsultasi','debit'=>2500000,'credit'=>0,'balance'=>2500000,'ref'=>'SO-002','curr'=>'IDR'],
            ['date'=>'08/03/2026','userno'=>'CN-2026-0304','note'=>'Credit note – retur barang','debit'=>0,'credit'=>250000,'balance'=>2250000,'ref'=>'RET-001','curr'=>'IDR'],
            ['date'=>'10/03/2026','userno'=>'INV-2026-0305','note'=>'Invoice pengiriman','debit'=>750000,'credit'=>0,'balance'=>3000000,'ref'=>'SO-003','curr'=>'IDR'],
            ['date'=>'12/03/2026','userno'=>'RCV-2026-0306','note'=>'Penerimaan sebagian','debit'=>0,'credit'=>1500000,'balance'=>1500000,'ref'=>'BNK-002','curr'=>'IDR'],
            ['date'=>'14/03/2026','userno'=>'INV-2026-0307','note'=>'Invoice bulanan Maret','debit'=>8200000,'credit'=>0,'balance'=>9700000,'ref'=>'SO-004','curr'=>'IDR'],
            ['date'=>'15/03/2026','userno'=>'ADV-2026-0308','note'=>'Advance payment DP','debit'=>0,'credit'=>4000000,'balance'=>5700000,'ref'=>'ADV-001','curr'=>'IDR'],
            ['date'=>'16/03/2026','userno'=>'INV-2026-0309','note'=>'Invoice tambahan','debit'=>3300000,'credit'=>0,'balance'=>9000000,'ref'=>'SO-005','curr'=>'IDR'],
        ];
        $totalDebit  = array_sum(array_column($activities,'debit'));
        $totalCredit = array_sum(array_column($activities,'credit'));
    @endphp

    {{-- LEDGER TABLE CARD --}}
    <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 4px 18px rgba(30,58,138,.06);">

        {{-- table card header --}}
        <div style="padding:16px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p style="font-size:13.5px;font-weight:700;color:#1e293b;margin:0;">Transaction Ledger</p>
                <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Showing records for 02/03/2026 – 16/03/2026</p>
            </div>
            <span style="font-size:11px;font-weight:600;background:#eff6ff;color:#2563EB;padding:4px 12px;border-radius:20px;">
                {{ count($activities) }} records
            </span>
        </div>

        <div class="scroll-body">
            <div style="overflow-x:auto;">
                <table class="ledger-table">
                    <thead>
                        <tr>
                            <th style="text-align:left;">
                                Date <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="text-align:left;">
                                UserNo <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="text-align:left;">Note</th>
                            <th style="text-align:right;">
                                Debit <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="text-align:right;">
                                Credit <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="text-align:right;">
                                Balance <svg class="sort-ico" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </th>
                            <th style="text-align:left;">Ref</th>
                            <th style="text-align:left;">Curr</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $i => $row)
                        <tr class="{{ $i===0 ? 'row-active' : '' }}" onclick="selectActivityRow(this)">
                            <td style="color:#64748b;white-space:nowrap;">{{ $row['date'] }}</td>
                            <td><span class="badge-doc">{{ $row['userno'] }}</span></td>
                            <td style="color:#475569;">{{ $row['note'] }}</td>
                            <td class="mono" style="text-align:right;white-space:nowrap;color:{{ $row['debit']>0 ? '#1e293b' : '#cbd5e1' }};">
                                {{ $row['debit'] > 0 ? number_format($row['debit'],2) : '—' }}
                            </td>
                            <td class="mono" style="text-align:right;white-space:nowrap;color:{{ $row['credit']>0 ? '#1e293b' : '#cbd5e1' }};">
                                {{ $row['credit'] > 0 ? number_format($row['credit'],2) : '—' }}
                            </td>
                            <td class="mono" style="text-align:right;white-space:nowrap;font-weight:700;color:#1E3A8A;">{{ number_format($row['balance'],2) }}</td>
                            <td style="color:#94a3b8;white-space:nowrap;">{{ $row['ref'] }}</td>
                            <td><span class="badge-curr">{{ $row['curr'] }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="8" style="text-align:center;padding:60px;color:#94a3b8;font-size:13px;">
                            No activity found in the selected period.
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FOOTER SUMMARY --}}
        <div style="background:#f8fafc;border-top:2px solid #e2e8f0;padding:14px 22px;display:flex;flex-wrap:wrap;align-items:center;justify-content:flex-end;gap:12px;">
            <div class="sum-pill">
                <span style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;">Total Debit</span>
                <span class="mono" style="font-size:15px;font-weight:800;color:#1e293b;">{{ number_format($totalDebit,2) }}</span>
            </div>
            <div class="sum-pill">
                <span style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;">Total Credit</span>
                <span class="mono" style="font-size:15px;font-weight:800;color:#1e293b;">{{ number_format($totalCredit,2) }}</span>
            </div>
        </div>
    </div>

</div>
</div>

@push('scripts')
<script>
function selectActivityRow(row) {
    document.querySelectorAll('.ledger-table tbody tr').forEach(r => {
        r.classList.remove('row-active');
    });
    row.classList.add('row-active');
}
</script>
@endpush
@endsection