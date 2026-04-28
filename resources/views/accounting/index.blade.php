@extends('layouts.app')
@section('title', 'Overview Accounting')

@section('breadcrumb')
    <span>Accounting</span>
    <span class="bc-sep">›</span>
    <span class="bc-current">Overview</span>
@endsection

@push('styles')
<style>
    .acc-overview-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 840px) { .acc-overview-grid { grid-template-columns: 1fr; } }

    .acc-stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    .acc-stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); border-color: #6366f1; }

    .acc-stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .acc-stat-icon svg { width: 26px; height: 26px; stroke: currentColor; fill: none; stroke-width: 2; }
    .acc-stat-icon.indigo { background: #eef2ff; color: #6366f1; }
    .acc-stat-icon.emerald { background: #ecfdf5; color: #10b981; }
    .acc-stat-icon.amber { background: #fffbeb; color: #f59e0b; }

    .acc-stat-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
    .acc-stat-value { font-size: 1.8rem; font-weight: 800; color: #1e293b; letter-spacing: -0.04em; line-height: 1.1; }
    
    .acc-menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
        margin-top: 24px;
    }
    .acc-menu-item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s;
    }
    .acc-menu-item:hover { border-color: #6366f1; background: #f8fafc; }
    .acc-menu-item-title { font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px; font-size: 1rem; }
    .acc-menu-item-title svg { width: 20px; height: 20px; color: #6366f1; }
    .acc-menu-item-desc { font-size: 0.85rem; color: #64748b; line-height: 1.5; }

    .section-label {
        font-size: 11px; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.08em;
        margin: 40px 0 16px;
        display: flex; align-items: center; gap: 12px;
    }
    .section-label::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }
</style>
@endpush

@section('content')
<div style="animation: pageFade 0.5s ease both;">
    
    <div class="acc-overview-grid">
        <div class="acc-stat-card">
            <div class="acc-stat-icon indigo">
                <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </div>
            <div>
                <div class="acc-stat-label">Chart of Accounts</div>
                <div class="acc-stat-value">Ready</div>
            </div>
        </div>

        <div class="acc-stat-card">
            <div class="acc-stat-icon emerald">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div>
                <div class="acc-stat-label">Journal Entries</div>
                <div class="acc-stat-value">Active</div>
            </div>
        </div>

        <div class="acc-stat-card">
            <div class="acc-stat-icon amber">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <div class="acc-stat-label">Fiscal Period</div>
                <div class="acc-stat-value">April 2026</div>
            </div>
        </div>
    </div>

    <div class="section-label">Main Accounting Functions</div>
    <div class="acc-menu-grid">
        <a href="{{ route('accounting.account-list.index') }}" class="acc-menu-item">
            <div class="acc-menu-item-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3zM3 9h18M3 15h18M9 3v18M15 3v18"/></svg>
                Account List (COA)
            </div>
            <div class="acc-menu-item-desc">Kelola daftar akun, saldo normal, dan struktur laporan keuangan perusahaan.</div>
        </a>

        <a href="{{ route('accounting.journal') }}" class="acc-menu-item">
            <div class="acc-menu-item-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Journal Entry
            </div>
            <div class="acc-menu-item-desc">Pencatatan transaksi manual ke dalam jurnal umum perusahaan.</div>
        </a>

        <a href="{{ route('accounting.ar-invoice') }}" class="acc-menu-item">
            <div class="acc-menu-item-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Accounts Receivable
            </div>
            <div class="acc-menu-item-desc">Manajemen piutang usaha, invoice penjualan, dan retur penjualan.</div>
        </a>

        <a href="{{ route('accounting.ap-invoice') }}" class="acc-menu-item">
            <div class="acc-menu-item-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Accounts Payable
            </div>
            <div class="acc-menu-item-desc">Manajemen hutang usaha, invoice pembelian, dan retur pembelian.</div>
        </a>
        
        <a href="{{ route('accounting.financial-report') }}" class="acc-menu-item">
            <div class="acc-menu-item-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                Financial Reports
            </div>
            <div class="acc-menu-item-desc">Laporan Laba Rugi, Neraca, Perubahan Modal, dan Arus Kas.</div>
        </a>
    </div>

</div>

<style>
    @keyframes pageFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
