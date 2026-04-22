@extends('layouts.app')
@section('title', 'Overview Finance')

@section('breadcrumb')
    <span>Finance</span>
    <span class="bc-sep">›</span>
    <span class="bc-current">Overview</span>
@endsection

@push('styles')
<style>
    .fi-overview-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 840px) { .fi-overview-grid { grid-template-columns: 1fr; } }

    .fi-stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        border: 1px solid #e2e8f0;
        border-left: 4px solid transparent;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
        animation: cardIn 0.4s ease both;
    }
    .fi-stat-card:nth-child(1) { animation-delay: 0.05s; border-left-color: #3b82f6; }
    .fi-stat-card:nth-child(2) { animation-delay: 0.10s; border-left-color: #22c55e; }
    .fi-stat-card:nth-child(3) { animation-delay: 0.15s; border-left-color: #ef4444; }
    .fi-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
    @keyframes cardIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .fi-stat-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .fi-stat-icon svg { width: 22px; height: 22px; stroke: currentColor; fill: none; stroke-width: 2; }
    .fi-stat-icon.blue   { background: #eff6ff; color: #3b82f6; }
    .fi-stat-icon.green  { background: #f0fdf4; color: #22c55e; }
    .fi-stat-icon.red    { background: #fef2f2; color: #ef4444; }

    .fi-stat-label { font-size: 10.5px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
    .fi-stat-value { font-size: 1.4rem; font-weight: 800; color: #1e293b; letter-spacing: -0.04em; line-height: 1.1; }
    .fi-stat-sub   { font-size: 10px; color: #94a3b8; margin-top: 3px; }

    .fi-section-label {
        font-size: 11px; font-weight: 700;
        color: #64748b; text-transform: uppercase; letter-spacing: 0.06em;
        margin-bottom: 12px;
        display: flex; align-items: center; gap: 8px;
    }
    .fi-section-label::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

    .balance-positive { color: #16a34a; font-weight: 700; }
    .balance-negative { color: #dc2626; font-weight: 700; }
    .balance-zero     { color: #94a3b8; }
</style>
@endpush

@section('content')
<div style="animation: pageLoad 0.3s ease;">

    {{-- Summary Cards --}}
    <div class="fi-overview-grid">
        <div class="fi-stat-card">
            <div class="fi-stat-icon blue">
                <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            </div>
            <div>
                <div class="fi-stat-label">Total Saldo Bank</div>
                <div class="fi-stat-value">Rp {{ number_format($totalBankBalance, 0, ',', '.') }}</div>
                <div class="fi-stat-sub">Saldo aktual dari semua bank</div>
            </div>
        </div>

        <div class="fi-stat-card">
            <div class="fi-stat-icon green">
                <svg viewBox="0 0 24 24"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
            </div>
            <div>
                <div class="fi-stat-label">Total Cash In & Receipt</div>
                <div class="fi-stat-value">Rp {{ number_format($totalUangMasuk, 0, ',', '.') }}</div>
                <div class="fi-stat-sub">Seluruh penerimaan kas</div>
            </div>
        </div>

        <div class="fi-stat-card">
            <div class="fi-stat-icon red">
                <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
            </div>
            <div>
                <div class="fi-stat-label">Total Cash Out</div>
                <div class="fi-stat-value">Rp {{ number_format($totalCashOut, 0, ',', '.') }}</div>
                <div class="fi-stat-sub">Seluruh pengeluaran kas</div>
            </div>
        </div>
    </div>

    {{-- Bank Account Table --}}
    <div class="fi-section-label">Status Saldo Bank</div>

    <div class="erp-table-wrap">
        <table class="erp-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Bank</th>
                    <th>No. Rekening</th>
                    <th>Kategori</th>
                    <th class="text-center">Mata Uang</th>
                    <th class="text-right">Saldo Aktual</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banks as $b)
                <tr onclick="window.location='{{ route('finance.bank-accounts.index') }}'">
                    <td><span class="badge badge-gray">{{ $b->code }}</span></td>
                    <td class="font-bold">{{ $b->bank_name }}</td>
                    <td style="font-variant-numeric: tabular-nums; font-size: 11px; color: #64748b;">{{ $b->bank_account }}</td>
                    <td>{{ $b->category ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge badge-blue">{{ $b->currency }}</span>
                    </td>
                    <td class="text-right font-mono">
                        @php $bal = floatval($b->balance); @endphp
                        <span class="{{ $bal > 0 ? 'balance-positive' : ($bal < 0 ? 'balance-negative' : 'balance-zero') }}">
                            {{ number_format($bal, 2, ',', '.') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            <p>Belum ada bank account yang terdaftar.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($banks->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right font-bold">TOTAL SALDO</td>
                    <td class="text-right font-mono font-bold">
                        @php $total = $banks->sum(fn($b) => floatval($b->balance)); @endphp
                        <span class="{{ $total >= 0 ? 'balance-positive' : 'balance-negative' }}">
                            {{ number_format($total, 2, ',', '.') }}
                        </span>
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
