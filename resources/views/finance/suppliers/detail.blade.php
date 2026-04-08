@extends('layouts.app')
@section('title', 'Supplier Detail – Finance')

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
        }

        .btn-outline:hover {
            background: #F8FAFC;
            transform: translateY(-1px);
        }

        .btn-new {
            background: #EEF2FF;
            color: var(--acc);
            border: 1.5px solid #C7D2FE;
        }
        .btn-new:hover {
            background: #E0E7FF;
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
            .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
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

        .main-layout {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 20px;
        }
        @media (max-width: 1024px) {
            .main-layout { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')
    <div style="background:var(--bg); min-height:calc(100vh - 62px); padding:28px 24px;">
        <div style="max-width:1280px; margin:0 auto;">

            {{-- ── BREADCRUMB ── --}}
            <div style="display:flex; align-items:center; gap:6px; font-size:12px; color:#94a3b8; margin-bottom:18px;">
                <a href="{{ route('finance.suppliers.index') }}" style="color:#94a3b8; text-decoration:none; display:flex;align-items:center;gap:5px;">
                    Finance
                </a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('finance.suppliers.index') }}" style="color:#94a3b8; text-decoration:none;">Supplier List</a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
                <span style="color:var(--blue); font-weight:600;">Record Detail</span>
            </div>

            {{-- ── PAGE HEADER + ACTIONS ── --}}
            <div style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; justify-content:space-between; margin-bottom:20px;">
                <div>
                    <h1 style="font-size:28px; font-weight:800; margin:0 0 4px;" class="grad-text">Supplier Detail</h1>
                    <p style="font-size:13px; color:#64748b; margin:0;">Manage supplier records and procurement relationships</p>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
                    @if($navigation)
                        <div class="nav-pill" id="navContainer">
                            <span class="nav-counter">
                                @if($navigation['total'] > 0) {{ $navigation['currentPosition'] }} of {{ $navigation['total'] }}
                                @else NEW @endif
                            </span>
                            <div class="nav-divider"></div>
                            <a href="{{ $navigation['first'] ? route('finance.suppliers.show', $navigation['first']) : '#' }}" class="nav-btn {{ (!$navigation['first'] || ($supplier->exists && $navigation['first'] == $supplier->id)) ? 'dim' : '' }}" title="First">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m11 17-5-5 5-5"/><path d="m18 17-5-5 5-5"/></svg>
                            </a>
                            <a href="{{ $navigation['prev'] ? route('finance.suppliers.show', $navigation['prev']) : '#' }}" class="nav-btn {{ !$navigation['prev'] ? 'dim' : '' }}" title="Previous">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m15 18-6-6 6-6"/></svg>
                            </a>
                            <a href="{{ $navigation['next'] ? route('finance.suppliers.show', $navigation['next']) : '#' }}" class="nav-btn {{ !$navigation['next'] ? 'dim' : '' }}" title="Next">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
                            </a>
                            <a href="{{ $navigation['last'] ? route('finance.suppliers.show', $navigation['last']) : '#' }}" class="nav-btn {{ (!$navigation['last'] || ($supplier->exists && $navigation['last'] == $supplier->id)) ? 'dim' : '' }}" title="Last">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m6 17 5-5-5-5"/><path d="m13 17 5-5-5-5"/></svg>
                            </a>
                        </div>
                    @endif
                    <a href="{{ route('finance.suppliers.detail') }}" class="btn-outline btn-new">New Supplier</a>
                    <a href="{{ route('finance.suppliers.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" form="supplierForm" class="btn-save">Save Changes</button>
                </div>
            </div>

            @if($errors->any())
                <div class="err-box">
                    <h4>Please fix {{ $errors->count() }} error(s):</h4>
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- ── TAB NAV ── --}}
            @php $sid = isset($supplier) && $supplier->exists ? $supplier->id : null; @endphp
            <div style="display:flex; gap:24px; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-bottom:24px; overflow-x:auto;">
                <a href="{{ route('finance.suppliers.show', $sid) }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.show', 'suppliers.detail') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Record Detail</a>
                <a href="{{ route('finance.suppliers.records-list') }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.records-list', 'suppliers.list') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Records List</a>
                <a href="{{ route('finance.suppliers.statistic', $sid) }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.statistic') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Statistics</a>
                <a href="{{ route('finance.suppliers.activity', $sid) }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.activity') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Activity</a>
                <a href="{{ route('finance.suppliers.backdate', $sid) }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.backdate') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Backdate</a>
                <a href="{{ route('finance.suppliers.summary', $sid) }}" style="text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; transition: color 0.2s; {{ request()->routeIs('finance.suppliers.summary') ? 'color:#2563EB; font-weight:700; border-bottom:2px solid #2563EB;' : 'color:#94a3b8; opacity:.7; font-weight:500;' }}">Summary</a>
            </div>

            {{-- ── MAIN FORM ── --}}
            <div class="g-card" style="border-radius:0 0 20px 20px; border-top: 0;">
                <form id="supplierForm" action="{{ $supplier->exists ? route('finance.suppliers.update', $supplier->id) : route('finance.suppliers.store') }}" method="POST">
                    @csrf
                    @if($supplier->exists) @method('PUT') @endif

                    <div class="main-layout" style="padding:24px;">
                        
                        {{-- LEFT SIDE: Supplier Info --}}
                        <div style="display:flex; flex-direction:column; gap:20px;">
                            <div class="g-card" style="margin:0;">
                                <div class="section-hd">
                                    <div class="ico" style="background:linear-gradient(135deg,#DBEAFE,#EDE9FE);">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4338CA" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    </div>
                                    <div>
                                        <p class="title">Supplier Information</p>
                                        <p class="sub">Company and contact details</p>
                                    </div>
                                </div>
                                <div class="section-body">
                                    <div class="form-grid-2" style="margin-bottom:18px;">
                                        <div class="form-field">
                                            <label class="f-label">Code</label>
                                            <input type="text" name="code" class="f-input" value="{{ old('code', $supplier->code) }}" readonly placeholder="Auto Generate">
                                        </div>
                                        <div class="form-field">
                                            <label class="f-label">Category</label>
                                            <div class="select-wrap">
                                                <select name="category" class="f-select f-input">
                                                    <option value="">Select Category</option>
                                                    <option value="Raw Materials" {{ old('category', $supplier->category) == 'Raw Materials' ? 'selected' : '' }}>Raw Materials</option>
                                                    <option value="Services" {{ old('category', $supplier->category) == 'Services' ? 'selected' : '' }}>Services</option>
                                                    <option value="Packaging" {{ old('category', $supplier->category) == 'Packaging' ? 'selected' : '' }}>Packaging</option>
                                                    <option value="General" {{ old('category', $supplier->category) == 'General' ? 'selected' : '' }}>General</option>
                                                </select>
                                                <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-field" style="margin-bottom:18px;">
                                        <label class="f-label req">Supplier Name</label>
                                        <input type="text" name="name" class="f-input" value="{{ old('name', $supplier->name) }}" required placeholder="e.g. PT. Supplier Indonesia">
                                    </div>

                                    <div class="form-field" style="margin-bottom:18px;">
                                        <label class="f-label">Address</label>
                                        <textarea name="address" class="f-textarea f-input" placeholder="Address...">{{ old('address', $supplier->address) }}</textarea>
                                    </div>

                                    <div class="form-grid-2" style="margin-bottom:18px;">
                                        <div class="form-field">
                                            <label class="f-label">City</label>
                                            <input type="text" name="city" class="f-input" value="{{ old('city', $supplier->city) }}" placeholder="e.g. Jakarta">
                                        </div>
                                        <div class="form-field">
                                            <label class="f-label">Contact Person</label>
                                            <input type="text" name="contact_person" class="f-input" value="{{ old('contact_person', $supplier->contact_person) }}" placeholder="e.g. John Doe">
                                        </div>
                                    </div>

                                    <div class="form-grid-3" style="margin-bottom:18px;">
                                        <div class="form-field">
                                            <label class="f-label req">Phone</label>
                                            <input type="text" name="phone" class="f-input" value="{{ old('phone', $supplier->phone) }}" required placeholder="e.g. (021) 123456">
                                        </div>
                                        <div class="form-field">
                                            <label class="f-label">Fax</label>
                                            <input type="text" name="fax" class="f-input" value="{{ old('fax', $supplier->fax) }}" placeholder="Fax Number">
                                        </div>
                                        <div class="form-field">
                                            <label class="f-label">Mobile Phone</label>
                                            <input type="text" name="mobile_phone" class="f-input" value="{{ old('mobile_phone', $supplier->mobile_phone) }}" placeholder="e.g. 0812...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="g-card" style="margin:0;">
                                <div class="section-hd">
                                    <div class="ico" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                                    </div>
                                    <div>
                                        <p class="title">Credit & Payment Setting</p>
                                        <p class="sub">Limits and bank detail information</p>
                                    </div>
                                </div>
                                <div class="section-body">
                                    <div class="form-grid-2" style="margin-bottom:18px;">
                                        <div class="form-field">
                                            <label class="f-label">Due Date (Days)</label>
                                            <input type="number" name="due_days" class="f-input" value="{{ old('due_days', $supplier->due_days ?? 0) }}" placeholder="e.g. 30">
                                        </div>
                                        <div class="form-field">
                                            <label class="f-label">Limit (Rp)</label>
                                            <input type="number" step="0.01" name="credit_limit" class="f-input" value="{{ old('credit_limit', $supplier->credit_limit ?? 0) }}">
                                        </div>
                                    </div>
                                    <div class="form-field" style="margin-bottom:18px;">
                                        <label class="f-label">Available Limit</label>
                                        <input type="text" class="f-input" value="Rp {{ number_format($supplier->available_limit ?? 0, 0, ',', '.') }}" readonly style="font-weight:700; color:#1E3A8A;">
                                    </div>

                                    <div class="form-grid-3">
                                        <div class="form-field">
                                            <label class="f-label">Bank Name</label>
                                            <input type="text" name="bank_name" class="f-input" value="{{ old('bank_name', $supplier->bank_name) }}" placeholder="e.g. BCA">
                                        </div>
                                        <div class="form-field">
                                            <label class="f-label">Account No</label>
                                            <input type="text" name="account_no" class="f-input" value="{{ old('account_no', $supplier->account_no) }}" placeholder="Account number">
                                        </div>
                                        <div class="form-field">
                                            <label class="f-label">Account Name</label>
                                            <input type="text" name="account_name" class="f-input" value="{{ old('account_name', $supplier->account_name) }}" placeholder="Owner's Name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT SIDE: Account Relations --}}
                        <div class="g-card" style="margin:0; height:fit-content;">
                            <div class="section-hd">
                                <div class="ico" style="background:linear-gradient(135deg,#fef08a,#fde047);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="2" stroke-linecap="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                </div>
                                <div>
                                    <p class="title">Account Relations</p>
                                    <p class="sub">Finance mapping related to Chart of Accounts</p>
                                </div>
                            </div>
                            <div class="section-body" style="display:flex; flex-direction:column; gap:16px;">
                                
                                <div class="form-field">
                                    <label class="f-label req">Payable Account (Hutang)</label>
                                    <div class="select-wrap">
                                        <select name="payable_account_id" class="f-select f-input" required>
                                            <option value="">Select Payable Account</option>
                                            {{-- DUMMY ACCOUNTS For demo --}}
                                            <option value="2110" {{ old('payable_account_id', $supplier->payable_account_id) == 2110 ? 'selected' : '' }}>2110.001 - HUTANG VENDOR</option>
                                            <option value="2120" {{ old('payable_account_id', $supplier->payable_account_id) == 2120 ? 'selected' : '' }}>2120.001 - HUTANG LAINNYA</option>
                                        </select>
                                        <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                </div>
                                
                                <div class="form-field">
                                    <label class="f-label">Prepaid Account (Uang Muka)</label>
                                    <div class="select-wrap">
                                        <select name="prepaid_account_id" class="f-select f-input">
                                            <option value="">Select Prepaid Account</option>
                                            <option value="1140" {{ old('prepaid_account_id', $supplier->prepaid_account_id) == 1140 ? 'selected' : '' }}>1140.001 - UANG MUKA PEMBELIAN</option>
                                        </select>
                                        <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                </div>

                                <div class="form-field">
                                    <label class="f-label">PPH 23 Account</label>
                                    <div class="select-wrap">
                                        <select name="pph23_account_id" class="f-select f-input">
                                            <option value="">Select PPH 23 Account</option>
                                            <option value="2230" {{ old('pph23_account_id', $supplier->pph23_account_id) == 2230 ? 'selected' : '' }}>2230.001 - HUTANG PPH 23</option>
                                        </select>
                                        <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                </div>

                                <div class="form-field">
                                    <label class="f-label">Tax Account</label>
                                    <div class="select-wrap">
                                        <select name="tax_account_id" class="f-select f-input">
                                            <option value="">Select Tax Account</option>
                                            <option value="1150" {{ old('tax_account_id', $supplier->tax_account_id) == 1150 ? 'selected' : '' }}>1150.001 - PPN MASUKAN</option>
                                        </select>
                                        <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                </div>

                                <div style="height:1px; background:#F1F5F9; margin:4px 0;"></div>

                                <div class="form-field">
                                    <label class="f-label">Cost Center</label>
                                    <div class="select-wrap">
                                        <select name="cost_center_id" class="f-select f-input">
                                            <option value="">Select Cost Center</option>
                                            <option value="1" {{ old('cost_center_id', $supplier->cost_center_id) == 1 ? 'selected' : '' }}>Head Office</option>
                                            <option value="2" {{ old('cost_center_id', $supplier->cost_center_id) == 2 ? 'selected' : '' }}>Branch 1</option>
                                        </select>
                                        <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                </div>
                                <div class="form-field">
                                    <label class="f-label">Account Department</label>
                                    <div class="select-wrap">
                                        <select name="account_dept_id" class="f-select f-input">
                                            <option value="">Select Department</option>
                                            <option value="1" {{ old('account_dept_id', $supplier->account_dept_id) == 1 ? 'selected' : '' }}>Finance</option>
                                            <option value="2" {{ old('account_dept_id', $supplier->account_dept_id) == 2 ? 'selected' : '' }}>Procurement</option>
                                        </select>
                                        <svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
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
    document.addEventListener('DOMContentLoaded', function() {
        const navContainer = document.getElementById('navContainer');
        if (!navContainer) return;
        
        navContainer.addEventListener('click', function(e) {
            const btn = e.target.closest('.nav-btn');
            if (!btn || btn.classList.contains('dim') || btn.getAttribute('href') === '#') return;
            
            e.preventDefault();
            const url = btn.getAttribute('href');
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                // Update URL without reload
                window.history.pushState({}, '', url);
                
                // Update Form Action
                document.getElementById('supplierForm').action = data.urls.update;
                
                // Populate Fields
                const s = data.supplier;
                document.querySelector('input[name="code"]').value = s.code || '';
                document.querySelector('input[name="name"]').value = s.name || '';
                document.querySelector('textarea[name="address"]').value = s.address || '';
                document.querySelector('input[name="city"]').value = s.city || '';
                document.querySelector('input[name="contact_person"]').value = s.contact_person || '';
                document.querySelector('input[name="phone"]').value = s.phone || '';
                document.querySelector('input[name="fax"]').value = s.fax || '';
                document.querySelector('input[name="mobile_phone"]').value = s.mobile_phone || '';
                document.querySelector('select[name="category"]').value = s.category || '';
                document.querySelector('input[name="due_days"]').value = s.due_days || 0;
                document.querySelector('input[name="credit_limit"]').value = s.credit_limit || 0;
                document.querySelector('input[name="bank_name"]').value = s.bank_name || '';
                document.querySelector('input[name="account_no"]').value = s.account_no || '';
                document.querySelector('input[name="account_name"]').value = s.account_name || '';
                document.querySelector('select[name="payable_account_id"]').value = s.payable_account_id || '';
                document.querySelector('select[name="prepaid_account_id"]').value = s.prepaid_account_id || '';
                document.querySelector('select[name="pph23_account_id"]').value = s.pph23_account_id || '';
                document.querySelector('select[name="tax_account_id"]').value = s.tax_account_id || '';
                document.querySelector('select[name="cost_center_id"]').value = s.cost_center_id || '';
                document.querySelector('select[name="account_dept_id"]').value = s.account_dept_id || '';
                
                // Update Navigation 
                const n = data.navigation;
                document.querySelector('.nav-counter').textContent = n.currentPosition + ' of ' + n.total;
                
                const btns = navContainer.querySelectorAll('.nav-btn');
                const baseUrl = '{{ url("/finance/suppliers/") }}';
                
                // First
                btns[0].href = n.first ? baseUrl + '/' + n.first : '#';
                if (!n.first || n.first === s.id) btns[0].classList.add('dim'); else btns[0].classList.remove('dim');
                // Prev
                btns[1].href = n.prev ? baseUrl + '/' + n.prev : '#';
                if (!n.prev) btns[1].classList.add('dim'); else btns[1].classList.remove('dim');
                // Next
                btns[2].href = n.next ? baseUrl + '/' + n.next : '#';
                if (!n.next) btns[2].classList.add('dim'); else btns[2].classList.remove('dim');
                // Last
                btns[3].href = n.last ? baseUrl + '/' + n.last : '#';
                if (!n.last || n.last === s.id) btns[3].classList.add('dim'); else btns[3].classList.remove('dim');
                
                let methodInput = document.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    document.getElementById('supplierForm').appendChild(methodInput);
                }
            })
            .catch(err => console.error(err));
        });
    });
</script>
@endpush
