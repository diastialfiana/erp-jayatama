@extends('layouts.app')
@section('title', 'Customer Detail – Finance')

@push('styles')
    <style>
        :root {
            --pri: #1E3A8A;
            --blue: #2563EB;
            --acc: #4F46E5;
            --bg: #F8FAFC;
        }

        .grad-text {
            background: linear-gradient(135deg, var(--pri) 0%, var(--acc) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Glass Card ── */
        .g-card {
            background: rgba(255, 255, 255, 0.93);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(30, 58, 138, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.35s ease, transform 0.35s ease;
        }

        .g-card:hover {
            box-shadow: 0 10px 38px rgba(30, 58, 138, 0.09);
        }

        /* ── Form Fields ── */
        .f-label {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: #64748b;
            margin-bottom: 6px;
        }

        .f-label.req::after {
            content: ' *';
            color: #EF4444;
        }

        .f-input,
        .f-select,
        .f-textarea {
            width: 100%;
            padding: 10px 14px;
            background: #F8FAFC;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 13.5px;
            color: #0f172a;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .f-input:focus,
        .f-select:focus,
        .f-textarea:focus {
            background: #fff;
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
        }

        .f-input[readonly],
        .f-select[disabled] {
            background: #F1F5F9;
            color: #94a3b8;
            cursor: not-allowed;
            border-color: #e8edf4;
            box-shadow: none;
        }

        .f-select {
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
        }

        .f-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .select-wrap {
            position: relative;
        }

        .select-wrap svg.arr {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #94a3b8;
        }

        /* ── Custom Radio ── */
        .radio-pill {
            display: flex;
            gap: 0;
            background: #F1F5F9;
            border-radius: 12px;
            padding: 4px;
            width: fit-content;
        }

        .radio-pill label {
            position: relative;
            padding: 8px 18px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
        }

        .radio-pill input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
        }

        .radio-pill input[type="radio"]:checked+span {
            /* handled via JS class */
        }

        .radio-pill label.selected {
            background: #fff;
            color: var(--blue);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.14);
        }

        /* ── Toggle Checkbox ── */
        .toggle-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toggle-checkbox {
            width: 44px;
            height: 24px;
            background: #e2e8f0;
            border-radius: 50px;
            position: relative;
            cursor: pointer;
            transition: background 0.25s;
            flex-shrink: 0;
            border: none;
            outline: none;
        }

        .toggle-checkbox::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
            transition: left 0.25s;
        }

        .toggle-checkbox.on {
            background: var(--blue);
        }

        .toggle-checkbox.on::after {
            left: 23px;
        }

        /* ── Action Buttons ── */
        .btn-save {
            background: linear-gradient(135deg, var(--blue) 0%, var(--acc) 100%);
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.35);
            transition: all 0.25s;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(37, 99, 235, 0.45);
        }

        .btn-save:active {
            transform: translateY(0);
        }

        .btn-outline {
            background: #fff;
            color: #374151;
            border: 1.5px solid #e2e8f0;
            padding: 9px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
            transition: all 0.25s;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            color: #374151;
        }

        .btn-outline:hover {
            background: #F8FAFC;
            transform: translateY(-1px);
            color: #374151;
        }

        .btn-new {
            background: #EEF2FF;
            color: var(--acc);
            border: 1.5px solid #C7D2FE;
        }

        .btn-new:hover {
            background: #E0E7FF;
            color: var(--acc);
        }

        /* ── Navigation Buttons ── */
        .nav-pill {
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 4px;
            gap: 2px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .nav-btn {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: none;
            background: none;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.18s;
            text-decoration: none;
        }

        .nav-btn:hover {
            background: #EEF2FF;
            color: var(--blue);
        }

        .nav-btn.dim {
            opacity: 0.35;
            pointer-events: none;
        }

        .nav-divider {
            width: 1px;
            height: 22px;
            background: #e2e8f0;
            margin: 0 4px;
        }

        .nav-counter {
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            padding: 0 10px;
            white-space: nowrap;
        }

        /* ── Tabs ── */
        .tab-bar {
            display: flex;
            padding: 6px 6px 0;
            overflow-x: auto;
            gap: 2px;
        }

        .tab-bar::-webkit-scrollbar {
            height: 3px;
        }

        .tab-bar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .tab-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 12px 12px 0 0;
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            text-decoration: none;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .tab-item:hover {
            color: #475569;
            background: #F8FAFC;
            text-decoration: none;
        }

        .tab-item.active {
            color: var(--blue);
            border-bottom-color: var(--blue);
            background: #fff;
        }

        .tab-item.disabled {
            pointer-events: none;
            opacity: 0.4;
        }

        /* ── Section Card Header ── */
        .section-hd {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 22px 26px 18px;
            border-bottom: 1px solid #F1F5F9;
        }

        .section-hd .ico {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .section-hd .title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .section-hd .sub {
            font-size: 12px;
            color: #94a3b8;
            margin: 2px 0 0;
        }

        .section-body {
            padding: 24px 26px;
        }

        /* ── Form Grid ── */
        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 18px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 800px) {
            .form-grid-2 {
                grid-template-columns: 1fr;
            }

            .form-grid-3 {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 540px) {
            .form-grid-3 {
                grid-template-columns: 1fr;
            }
        }

        /* ── Financial Summary Cards ── */
        .fin-card {
            background: linear-gradient(135deg, #fff 0%, #F8FAFC 100%);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px 22px;
            position: relative;
            overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .fin-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .fin-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            border-radius: 0 0 0 80px;
            opacity: 0.06;
        }

        .fin-card.green::before {
            background: #10B981;
        }

        .fin-card.blue::before {
            background: #3B82F6;
        }

        .fin-card.purple::before {
            background: #8B5CF6;
        }

        .fin-card.orange::before {
            background: #F59E0B;
        }

        .fin-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .fin-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .fin-value {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            font-variant-numeric: tabular-nums;
        }

        .fin-currency {
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            margin-left: 3px;
        }

        .fin-sub {
            font-size: 11.5px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* ── Error Box ── */
        .err-box {
            background: rgba(254, 242, 242, 0.9);
            border: 1px solid #FECACA;
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }

        .err-box h4 {
            font-size: 13px;
            font-weight: 700;
            color: #991B1B;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 6px;
        }

        .err-box ul {
            margin: 0;
            padding-left: 18px;
            font-size: 12.5px;
            color: #B91C1C;
        }

        /* ── divider ── */
        .coa-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 6px 0;
        }

        .coa-divider span {
            font-size: 10.5px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .09em;
            white-space: nowrap;
        }

        .coa-divider div {
            flex: 1;
            height: 1px;
            background: #F1F5F9;
        }

        .main-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 1024px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div style="background:var(--bg); min-height:calc(100vh - 62px); padding:28px 24px;">
        <div style="max-width:1280px; margin:0 auto;">

            {{-- ── BREADCRUMB ── --}}
            <div style="display:flex; align-items:center; gap:6px; font-size:12px; color:#94a3b8; margin-bottom:18px;">
                <a href="{{ route('finance.customers.index') }}"
                    style="color:#94a3b8; text-decoration:none; display:flex;align-items:center;gap:5px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    Finance
                </a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
                <a href="{{ route('finance.customers.index') }}" style="color:#94a3b8; text-decoration:none;">Customer
                    List</a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
                <span style="color:var(--blue); font-weight:600;">Detail View</span>
            </div>

            {{-- ── PAGE HEADER + ACTIONS ── --}}
            <div
                style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; justify-content:space-between; margin-bottom:20px;">
                <div>
                    <h1 style="font-size:28px; font-weight:800; margin:0 0 4px;" class="grad-text">Customer Detail</h1>
                    <p style="font-size:13px; color:#64748b; margin:0;">Configure financial relationship and account mapping
                    </p>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
                    @if($navigation)
                        <div class="nav-pill">
                            <span class="nav-counter">
                                @if($navigation['total'] > 0) {{ $navigation['currentPosition'] }} of {{ $navigation['total'] }}
                                @else NEW @endif
                            </span>
                            <div class="nav-divider"></div>
                            <a href="{{ $navigation['first'] ? route('finance.customers.detail.show', $navigation['first']) : '#' }}"
                                class="nav-btn {{ (!$navigation['first'] || ($customer->exists && $navigation['first'] == $customer->id)) ? 'dim' : '' }}"
                                title="First">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round">
                                    <path d="m11 17-5-5 5-5" />
                                    <path d="m18 17-5-5 5-5" />
                                </svg>
                            </a>
                            <a href="{{ $navigation['prev'] ? route('finance.customers.detail.show', $navigation['prev']) : '#' }}"
                                class="nav-btn {{ !$navigation['prev'] ? 'dim' : '' }}" title="Previous">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                            </a>
                            <a href="{{ $navigation['next'] ? route('finance.customers.detail.show', $navigation['next']) : '#' }}"
                                class="nav-btn {{ !$navigation['next'] ? 'dim' : '' }}" title="Next">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </a>
                            <a href="{{ $navigation['last'] ? route('finance.customers.detail.show', $navigation['last']) : '#' }}"
                                class="nav-btn {{ (!$navigation['last'] || ($customer->exists && $navigation['last'] == $customer->id)) ? 'dim' : '' }}"
                                title="Last">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round">
                                    <path d="m6 17 5-5-5-5" />
                                    <path d="m13 17 5-5-5-5" />
                                </svg>
                            </a>
                        </div>
                    @endif
                    <a href="{{ route('finance.customers.detail.create') }}" class="btn-outline btn-new">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        New Customer
                    </a>
                    <a href="{{ route('finance.customers.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" form="customerForm" class="btn-save">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>

            {{-- ── VALIDATION ERRORS ── --}}
            @if($errors->any())
                <div class="err-box">
                    <h4>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        Please fix {{ $errors->count() }} error(s):
                    </h4>
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- ── TAB BAR ── --}}
            <div class="g-card"
                style="border-radius:20px 20px 0 0; border-bottom:0; box-shadow:none; border-color:#e2e8f0;">
                <div class="tab-bar">
                    <a href="{{ $customer->exists ? route('finance.customers.detail.show', $customer->id) : route('finance.customers.detail.create') }}"
                        class="tab-item active">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                        </svg>
                        Detail View
                    </a>
                    @if($customer->exists)
                        <a href="{{ route('finance.customers.list', $customer->id) }}" class="tab-item">List All</a>
                        <a href="{{ route('finance.customers.statistic', $customer->id) }}" class="tab-item">Statistic</a>
                        <a href="{{ route('finance.customers.activity', $customer->id) }}" class="tab-item">Activity</a>
                        <a href="{{ route('finance.customers.backdate', $customer->id) }}" class="tab-item">Backdate</a>
                        <a href="{{ route('finance.customers.summary', $customer->id) }}" class="tab-item">Summary</a>
                    @else
                        <span class="tab-item disabled">List All</span>
                        <span class="tab-item disabled">Statistic</span>
                        <span class="tab-item disabled">Activity</span>
                        <span class="tab-item disabled">Backdate</span>
                        <span class="tab-item disabled">Summary</span>
                    @endif
                </div>
            </div>

            {{-- ── MAIN FORM ── --}}
            <div class="g-card" style="border-radius:0 0 20px 20px; border-top: 0;">
                <form id="customerForm"
                    action="{{ $customer->exists ? route('finance.customers.update', $customer->id) : route('finance.customers.store') }}"
                    method="POST">
                    @csrf
                    @if($customer->exists) @method('PUT') @endif

                    {{-- ── ROW 1: 2 COLUMNS ── --}}
                    <div class="main-layout" style="padding:24px;">

                        {{-- CARD 1: Customer Information --}}
                        <div class="g-card" style="margin:0;">
                            <div class="section-hd">
                                <div class="ico" style="background:linear-gradient(135deg,#DBEAFE,#EDE9FE);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4338CA"
                                        stroke-width="2" stroke-linecap="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="title">Customer Information</p>
                                    <p class="sub">Basic identification and contact details</p>
                                </div>
                            </div>
                            <div class="section-body">
                                {{-- Code + Currency --}}
                                <div class="form-grid-2" style="margin-bottom:18px;">
                                    <div class="form-field">
                                        <label class="f-label req">Code</label>
                                        <input type="text" name="code" class="f-input"
                                            value="{{ old('code', $customer->code) }}" required placeholder="e.g. CST-0001">
                                    </div>
                                    <div class="form-field">
                                        <label class="f-label req">Currency</label>
                                        <div class="select-wrap">
                                            <select name="currency" class="f-select f-input" required>
                                                <option value="">Select currency…</option>
                                                <option value="IDR" {{ old('currency', $customer->currency) == 'IDR' ? 'selected' : '' }}>IDR – Indonesian Rupiah
                                                </option>
                                                <option value="USD" {{ old('currency', $customer->currency) == 'USD' ? 'selected' : '' }}>USD – US Dollar</option>
                                                <option value="SGD" {{ old('currency', $customer->currency) == 'SGD' ? 'selected' : '' }}>SGD – Singapore Dollar
                                                </option>
                                            </select>
                                            <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                <polyline points="6 9 12 15 18 9" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Counter Name + Initial --}}
                                <div class="form-grid-2" style="margin-bottom:18px; grid-template-columns: 2fr 1fr;">
                                    <div class="form-field">
                                        <label class="f-label req">Counter Name</label>
                                        <input type="text" name="counter_name" class="f-input"
                                            value="{{ old('counter_name', $customer->counter_name) }}" required
                                            placeholder="PT. Jaya Abadi Makmur">
                                    </div>
                                    <div class="form-field">
                                        <label class="f-label">Initial Name</label>
                                        <input type="text" name="initial_name" class="f-input"
                                            value="{{ old('initial_name', $customer->initial_name) }}"
                                            placeholder="e.g. JAM">
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div class="form-field" style="margin-bottom:18px;">
                                    <label class="f-label">Address</label>
                                    <textarea name="address" class="f-textarea f-input"
                                        placeholder="Street, District, City, Province, Postal Code">{{ old('address', $customer->address) }}</textarea>
                                </div>

                                {{-- Phone, Fax, Mobile --}}
                                <div class="form-grid-3" style="margin-bottom:18px;">
                                    <div class="form-field">
                                        <label class="f-label">Phone</label>
                                        <input type="text" name="phone" class="f-input"
                                            value="{{ old('phone', $customer->phone) }}" placeholder="(021) 123456">
                                    </div>
                                    <div class="form-field">
                                        <label class="f-label">Fax</label>
                                        <input type="text" name="fax" class="f-input"
                                            value="{{ old('fax', $customer->fax) }}" placeholder="(021) 123457">
                                    </div>
                                    <div class="form-field">
                                        <label class="f-label">Mobile Phone</label>
                                        <input type="text" name="mobile_phone" class="f-input"
                                            value="{{ old('mobile_phone', $customer->mobile_phone) }}"
                                            placeholder="08xx-xxxx-xxxx">
                                    </div>
                                </div>

                                {{-- Region + Default Bank --}}
                                <div class="form-grid-2" style="margin-bottom:18px;">
                                    <div class="form-field">
                                        <label class="f-label">Region</label>
                                        <div class="select-wrap">
                                            <select name="region" class="f-select f-input">
                                                <option value="">Select region…</option>
                                                @foreach(['Jawa', 'Sumatera', 'Kalimantan', 'Sulawesi', 'Bali / Nusa Tenggara', 'Maluku / Papua'] as $r)
                                                    <option value="{{ $r }}" {{ old('region', $customer->region) == $r ? 'selected' : '' }}>{{ $r }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                <polyline points="6 9 12 15 18 9" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="form-field">
                                        <label class="f-label">Default Bank Account</label>
                                        <div class="select-wrap">
                                            <select name="default_bank_account_id" class="f-select f-input">
                                                <option value="">Select bank account…</option>
                                                <option value="1" {{ old('default_bank_account_id', $customer->default_bank_account_id) == '1' ? 'selected' : '' }}>BCA –
                                                    123.456.78</option>
                                                <option value="2" {{ old('default_bank_account_id', $customer->default_bank_account_id) == '2' ? 'selected' : '' }}>Mandiri –
                                                    987.654.32</option>
                                                <option value="3" {{ old('default_bank_account_id', $customer->default_bank_account_id) == '3' ? 'selected' : '' }}>BRI –
                                                    112.233.44</option>
                                            </select>
                                            <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                <polyline points="6 9 12 15 18 9" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Cost Center + Dept --}}
                                <div class="form-grid-2" style="margin-bottom:22px;">
                                    <div class="form-field">
                                        <label class="f-label">Cost Center</label>
                                        <div class="select-wrap">
                                            <select name="cost_center_id" class="f-select f-input">
                                                <option value="">Select cost center…</option>
                                                <option value="1" {{ old('cost_center_id', $customer->cost_center_id) == '1' ? 'selected' : '' }}>CC-01 – Marketing
                                                </option>
                                                <option value="2" {{ old('cost_center_id', $customer->cost_center_id) == '2' ? 'selected' : '' }}>CC-02 – Operations
                                                </option>
                                            </select>
                                            <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                <polyline points="6 9 12 15 18 9" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="form-field">
                                        <label class="f-label">Account Dept</label>
                                        <div class="select-wrap">
                                            <select name="account_dept_id" class="f-select f-input">
                                                <option value="">Select department…</option>
                                                <option value="1" {{ old('account_dept_id', $customer->account_dept_id) == '1' ? 'selected' : '' }}>Dept-01 – Sales
                                                </option>
                                                <option value="2" {{ old('account_dept_id', $customer->account_dept_id) == '2' ? 'selected' : '' }}>Dept-02 – Finance
                                                </option>
                                            </select>
                                            <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                <polyline points="6 9 12 15 18 9" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Invoice Layout --}}
                                <div class="form-field" style="margin-bottom:22px;">
                                    <label class="f-label" style="margin-bottom:10px;">Invoice Layout</label>
                                    <div class="radio-pill" id="invoicePill">
                                        <label id="lbl-detail"
                                            class="{{ old('invoice_layout', $customer->invoice_layout ?? 'detail') == 'detail' ? 'selected' : '' }}">
                                            <input type="radio" name="invoice_layout" value="detail" {{ old('invoice_layout', $customer->invoice_layout ?? 'detail') == 'detail' ? 'checked' : '' }}>
                                            <span>By Detail</span>
                                        </label>
                                        <label id="lbl-project"
                                            class="{{ old('invoice_layout', $customer->invoice_layout) == 'project' ? 'selected' : '' }}">
                                            <input type="radio" name="invoice_layout" value="project" {{ old('invoice_layout', $customer->invoice_layout) == 'project' ? 'checked' : '' }}>
                                            <span>By Project</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Corporate Group Toggle --}}
                                <div
                                    style="background:#F8FAFF; border:1.5px solid #E0E7FF; border-radius:16px; padding:18px 20px;">
                                    <div class="toggle-wrap" style="margin-bottom:3px;">
                                        <button type="button"
                                            class="toggle-checkbox {{ old('is_corporate_group', $customer->is_corporate_group) ? 'on' : '' }}"
                                            id="corpToggle"></button>
                                        <input type="hidden" name="is_corporate_group" id="corpHidden"
                                            value="{{ old('is_corporate_group', $customer->is_corporate_group) ? '1' : '0' }}">
                                        <div>
                                            <div style="font-size:13.5px; font-weight:700; color:#3730A3;">Is Corporate
                                                Group</div>
                                            <div style="font-size:11.5px; color:#818CF8; margin-top:1px;">Customer belongs
                                                to a parent corporate hierarchy</div>
                                        </div>
                                    </div>
                                    <div id="groupsWrap"
                                        style="margin-top:14px; {{ old('is_corporate_group', $customer->is_corporate_group) ? '' : 'display:none;' }}">
                                        <label class="f-label" style="color:#4338CA;">As Groups</label>
                                        <div class="select-wrap">
                                            <select name="group_id" class="f-select f-input" style="border-color:#C7D2FE;"
                                                id="groupSelect">
                                                <option value="">Select parent group…</option>
                                                <option value="1" {{ old('group_id', $customer->group_id) == '1' ? 'selected' : '' }}>Group A – Holding Corp</option>
                                                <option value="2" {{ old('group_id', $customer->group_id) == '2' ? 'selected' : '' }}>Group B – Subsidiary LLC</option>
                                            </select>
                                            <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                <polyline points="6 9 12 15 18 9" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CARD 2: Account Relations --}}
                        <div class="g-card" style="margin:0;">
                            <div class="section-hd">
                                <div class="ico" style="background:linear-gradient(135deg,#EDE9FE,#FCE7F3);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7C3AED"
                                        stroke-width="2" stroke-linecap="round">
                                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20" />
                                        <path d="m9 9.5 2 2 4-4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="title">Account Relations</p>
                                    <p class="sub">Chart of Account (COA) mapping</p>
                                </div>
                            </div>
                            <div class="section-body">

                                <div class="coa-divider">
                                    <div></div><span>Receivables & Tax</span>
                                    <div></div>
                                </div>

                                <div class="form-field" style="margin-bottom:16px;">
                                    <label class="f-label req" style="color:#6D28D9;">Receivable Account</label>
                                    <div class="select-wrap">
                                        <select name="receivable_account_id" class="f-select f-input" required
                                            style="border-color:#DDD6FE;">
                                            <option value="">Select receivable account…</option>
                                            <option value="11" {{ old('receivable_account_id', $customer->receivable_account_id) == '11' ? 'selected' : '' }}>110.10.10 – Trade
                                                Receivables (IDR)</option>
                                            <option value="12" {{ old('receivable_account_id', $customer->receivable_account_id) == '12' ? 'selected' : '' }}>110.10.20 – Trade
                                                Receivables (USD)</option>
                                        </select>
                                        <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                            <polyline points="6 9 12 15 18 9" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="form-field" style="margin-bottom:16px;">
                                    <label class="f-label" style="color:#6D28D9;">Prepaid Account</label>
                                    <div class="select-wrap">
                                        <select name="prepaid_account_id" class="f-select f-input">
                                            <option value="">Select prepaid account…</option>
                                            <option value="21" {{ old('prepaid_account_id', $customer->prepaid_account_id) == '21' ? 'selected' : '' }}>130.10.10 – Prepaid
                                                Tax</option>
                                            <option value="22" {{ old('prepaid_account_id', $customer->prepaid_account_id) == '22' ? 'selected' : '' }}>130.20.10 – Prepaid
                                                Expenses</option>
                                        </select>
                                        <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                            <polyline points="6 9 12 15 18 9" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="form-grid-2" style="margin-bottom:20px;">
                                    <div class="form-field">
                                        <label class="f-label" style="color:#6D28D9;">PPH 23 Account</label>
                                        <div class="select-wrap">
                                            <select name="pph23_account_id" class="f-select f-input">
                                                <option value="">Select PPh 23…</option>
                                                <option value="31" {{ old('pph23_account_id', $customer->pph23_account_id) == '31' ? 'selected' : '' }}>210.10.10 – Accrued
                                                    PPh 23</option>
                                            </select>
                                            <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                <polyline points="6 9 12 15 18 9" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="form-field">
                                        <label class="f-label" style="color:#6D28D9;">Tax Account</label>
                                        <div class="select-wrap">
                                            <select name="tax_account_id" class="f-select f-input">
                                                <option value="">Select tax account…</option>
                                                <option value="41" {{ old('tax_account_id', $customer->tax_account_id) == '41' ? 'selected' : '' }}>220.10.10 – VAT Output
                                                </option>
                                            </select>
                                            <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                <polyline points="6 9 12 15 18 9" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="coa-divider">
                                    <div></div><span>Sales & Revenue</span>
                                    <div></div>
                                </div>

                                <div class="form-field" style="margin:16px 0;">
                                    <label class="f-label" style="color:#065F46;">Sales Account</label>
                                    <div class="select-wrap">
                                        <select name="sales_account_id" class="f-select f-input"
                                            style="border-color:#A7F3D0;">
                                            <option value="">Select sales account…</option>
                                            <option value="51" {{ old('sales_account_id', $customer->sales_account_id) == '51' ? 'selected' : '' }}>410.10.10 – Sales
                                                Domestic</option>
                                            <option value="52" {{ old('sales_account_id', $customer->sales_account_id) == '52' ? 'selected' : '' }}>410.10.20 – Sales Export
                                            </option>
                                        </select>
                                        <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                            <polyline points="6 9 12 15 18 9" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="form-field">
                                    <label class="f-label" style="color:#991B1B;">Sales Return Account</label>
                                    <div class="select-wrap">
                                        <select name="sales_return_account_id" class="f-select f-input"
                                            style="border-color:#FECACA;">
                                            <option value="">Select return account…</option>
                                            <option value="61" {{ old('sales_return_account_id', $customer->sales_return_account_id) == '61' ? 'selected' : '' }}>420.10.10 – Sales
                                                Return Domestic</option>
                                            <option value="62" {{ old('sales_return_account_id', $customer->sales_return_account_id) == '62' ? 'selected' : '' }}>420.10.20 – Sales
                                                Return Export</option>
                                        </select>
                                        <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                            <polyline points="6 9 12 15 18 9" />
                                        </svg>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ── ROW 2: FINANCIAL SUMMARY ── --}}
                    <div style="padding: 0 24px 24px;">
                        <div class="g-card"
                            style="margin:0; background:linear-gradient(135deg,#F0F9FF 0%, #EEF2FF 100%); border-color:#BFDBFE;">
                            <div class="section-hd" style="border-color:#DBEAFE;">
                                <div class="ico" style="background:linear-gradient(135deg,#DCFCE7,#DBEAFE);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669"
                                        stroke-width="2" stroke-linecap="round">
                                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="title" style="color:#065F46;">Financial Summary</p>
                                    <p class="sub">Auto-calculated balances – read only</p>
                                </div>
                            </div>
                            <div class="section-body">
                                <div
                                    style="display:grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap:16px;">

                                    <div class="fin-card green">
                                        <div class="fin-icon" style="background:#D1FAE5;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669"
                                                stroke-width="2" stroke-linecap="round">
                                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                            </svg>
                                        </div>
                                        <div class="fin-label">Balance</div>
                                        <div class="fin-value">{{ number_format($customer->balance, 0, ',', '.') }}<span
                                                class="fin-currency">{{ $customer->currency ?? 'IDR' }}</span></div>
                                        <div class="fin-sub">Current receivable outstanding</div>
                                    </div>

                                    <div class="fin-card blue">
                                        <div class="fin-icon" style="background:#DBEAFE;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB"
                                                stroke-width="2" stroke-linecap="round">
                                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                            </svg>
                                        </div>
                                        <div class="fin-label">Balance (Converted)</div>
                                        <div class="fin-value">{{ number_format($customer->balance, 0, ',', '.') }}<span
                                                class="fin-currency">IDR</span></div>
                                        <div class="fin-sub">Converted to base currency</div>
                                    </div>

                                    <div class="fin-card purple">
                                        <div class="fin-icon" style="background:#EDE9FE;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7C3AED"
                                                stroke-width="2" stroke-linecap="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                <polyline points="7 10 12 15 17 10" />
                                                <line x1="12" y1="15" x2="12" y2="3" />
                                            </svg>
                                        </div>
                                        <div class="fin-label">Down Payment</div>
                                        <div class="fin-value">
                                            {{ number_format($customer->down_payment, 0, ',', '.') }}<span
                                                class="fin-currency">{{ $customer->currency ?? 'IDR' }}</span></div>
                                        <div class="fin-sub">Total advance payment received</div>
                                    </div>

                                    <div class="fin-card orange">
                                        <div class="fin-icon" style="background:#FEF3C7;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D97706"
                                                stroke-width="2" stroke-linecap="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                <polyline points="7 10 12 15 17 10" />
                                                <line x1="12" y1="15" x2="12" y2="3" />
                                            </svg>
                                        </div>
                                        <div class="fin-label">DP (Converted)</div>
                                        <div class="fin-value">
                                            {{ number_format($customer->down_payment, 0, ',', '.') }}<span
                                                class="fin-currency">IDR</span></div>
                                        <div class="fin-sub">Converted to base currency</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // ── Radio Pill
            document.querySelectorAll('#invoicePill label').forEach(lbl => {
                lbl.addEventListener('click', () => {
                    document.querySelectorAll('#invoicePill label').forEach(l => l.classList.remove('selected'));
                    lbl.classList.add('selected');
                });
            });

            // ── Corporate Group Toggle
            const toggle = document.getElementById('corpToggle');
            const hidden = document.getElementById('corpHidden');
            const wrap = document.getElementById('groupsWrap');
            const sel = document.getElementById('groupSelect');

            toggle.addEventListener('click', () => {
                const isOn = toggle.classList.toggle('on');
                hidden.value = isOn ? '1' : '0';
                if (isOn) {
                    wrap.style.display = 'block';
                    wrap.style.animation = 'slideIn .3s ease';
                } else {
                    wrap.style.display = 'none';
                    sel.value = '';
                }
            });
        });
    </script>
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush