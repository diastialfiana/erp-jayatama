@extends('layouts.app')

@section('title', 'Accounting Overview')

@push('styles')
<style>
    :root {
        --hr-primary: #1e293b;
        --hr-border: #cbd5e1;
    }
    
    .accounting-container {
        padding: 20px;
        background: white;
        border: 1px solid var(--hr-border);
        margin: 10px;
        min-height: calc(100vh - 120px);
    }
</style>
@endpush

@section('content')
<div x-data="{
    handleRibbonAction(action) {
        if (action === 'ar-invoice') {
            showToast('Opening A/R Invoice...', 'info');
        } else if (action === 'ar-return') {
            showToast('Opening A/R Return...', 'info');
        } else if (action === 'ap-invoice') {
            showToast('Opening A/P Invoice...', 'info');
        } else if (action === 'ap-return') {
            showToast('Opening A/P Return...', 'info');
        } else {
            showToast('Action: ' + action, 'default');
        }
    }
}" x-on:ribbon-action.window="handleRibbonAction($event.detail)">

    <!-- Title Bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 8px; align-items: center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="20" x2="18" y2="10" />
                <line x1="12" y1="20" x2="12" y2="4" />
                <line x1="6" y1="20" x2="6" y2="14" />
            </svg>
            <span style="font-weight: 600;">Accounting Dashboard</span>
        </div>
        <div style="display: flex; gap: 15px;">
            <span style="cursor: pointer; font-size: 0.9rem;">◁</span>
            <span style="cursor: pointer; font-size: 0.9rem;">▷</span>
            <span style="cursor: pointer;">✕</span>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <div class="accounting-container">
        <h2 style="color: #1e3a8a; margin-bottom: 10px;">Accounting Overview</h2>
        <p style="color: #475569;">Welcome to the Accounting module.</p>
        <div style="margin-top: 20px; padding: 20px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px;">
            <p>Select an action from the <strong>Receivable And Payable</strong> ribbon menu above to begin.</p>
        </div>
    </div>
</div>
@endsection
