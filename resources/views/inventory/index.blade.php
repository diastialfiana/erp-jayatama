@extends('layouts.app')
@section('title', 'Overview Inventory & GA')

@section('breadcrumb')
    <span>Inventory & GA</span>
    <span class="bc-sep">›</span>
    <span class="bc-current">Overview</span>
@endsection

@push('styles')
<style>
    .inv-overview-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1024px) { .inv-overview-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .inv-overview-grid { grid-template-columns: 1fr; } }

    .inv-stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }
    .inv-stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); border-color: #3b82f6; }

    .inv-stat-header { display: flex; align-items: center; justify-content: space-between; }
    .inv-stat-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
    }
    .inv-stat-icon svg { width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 2; }
    .inv-stat-icon.blue { background: #eff6ff; color: #3b82f6; }
    .inv-stat-icon.green { background: #f0fdf4; color: #22c55e; }
    .inv-stat-icon.purple { background: #faf5ff; color: #a855f7; }
    .inv-stat-icon.orange { background: #fff7ed; color: #f97316; }

    .inv-stat-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
    .inv-stat-value { font-size: 1.6rem; font-weight: 800; color: #1e293b; letter-spacing: -0.04em; }
    
    .inv-quick-links {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 8px;
    }
    .quick-link-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: #475569;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .quick-link-item:hover { background: #fff; border-color: #3b82f6; color: #3b82f6; }
    .quick-link-item svg { width: 18px; height: 18px; stroke-width: 2; }

    .section-title {
        font-size: 0.85rem; font-weight: 700; color: #64748b;
        text-transform: uppercase; letter-spacing: 0.05em;
        margin: 32px 0 16px;
        display: flex; align-items: center; gap: 10px;
    }
    .section-title::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
</style>
@endpush

@section('content')
<div style="animation: fadeIn 0.4s ease-out;">
    
    <div class="inv-overview-grid">
        <a href="{{ route('inventory.fixed_assets') }}" class="inv-stat-card">
            <div class="inv-stat-header">
                <div class="inv-stat-icon blue">
                    <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
                <div class="inv-stat-label">Fixed Assets</div>
            </div>
            <div class="inv-stat-value">{{ $stats['total_fixed_assets'] }}</div>
        </a>

        <a href="{{ route('inventory.product_assets') }}" class="inv-stat-card">
            <div class="inv-stat-header">
                <div class="inv-stat-icon green">
                    <svg viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
                <div class="inv-stat-label">Stock Items</div>
            </div>
            <div class="inv-stat-value">{{ $stats['total_product_assets'] }}</div>
        </a>

        <a href="{{ route('inventory.human_resources') }}" class="inv-stat-card">
            <div class="inv-stat-header">
                <div class="inv-stat-icon purple">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><circle cx="19" cy="11" r="2"/></svg>
                </div>
                <div class="inv-stat-label">Total Employees</div>
            </div>
            <div class="inv-stat-value">{{ $stats['total_employees'] }}</div>
        </a>

        <a href="{{ route('inventory.order_requests') }}" class="inv-stat-card">
            <div class="inv-stat-header">
                <div class="inv-stat-icon orange">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <div class="inv-stat-label">Active Requests</div>
            </div>
            <div class="inv-stat-value">{{ $stats['recent_requests'] }}</div>
        </a>
    </div>

    <div class="section-title">Akses Cepat Modul Inventory</div>
    <div class="inv-quick-links">
        <a href="{{ route('inventory.fixed_assets') }}" class="quick-link-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Fixed Assets
        </a>
        <a href="{{ route('inventory.product_assets') }}" class="quick-link-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Product Assets
        </a>
        <a href="{{ route('inventory.human_resources') }}" class="quick-link-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Human Resources
        </a>
        <a href="{{ route('inventory.order_requests') }}" class="quick-link-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Order Requests
        </a>
        <a href="{{ route('inventory.advance_requests') }}" class="quick-link-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Advance Requests
        </a>
        <a href="{{ route('inventory.goods_requests') }}" class="quick-link-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z"></path></svg>
            Goods Requests
        </a>
    </div>

</div>
@endsection
