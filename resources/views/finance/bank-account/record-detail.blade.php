@extends('layouts.app')
@section('title', 'Bank Account – Record Detail')

@push('styles')
<style>
    :root { --blue:#2563EB; --navy:#1E3A8A; --accent:#4F46E5; --bg:#F8FAFC; }

    .page-fade { animation: pgIn .4s ease both; }
    @keyframes pgIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

    /* Tab */
    .tab-link { color:#64748b;text-decoration:none;padding-bottom:8px;margin-bottom:-9px;white-space:nowrap;font-size:13.5px;transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB;font-weight:700;border-bottom:2.5px solid #2563EB; }

    /* Record nav */
    .rec-nav { display:flex;align-items:center;gap:4px; }
    .rec-btn {
        display:inline-flex;align-items:center;justify-content:center;
        width:30px;height:30px;border-radius:7px;border:1px solid #e2e8f0;
        background:#fff;color:#475569;font-size:13px;font-weight:700;
        cursor:pointer;transition:all .15s;text-decoration:none;
    }
    .rec-btn:hover { background:#eff6ff;border-color:#2563EB;color:#2563EB; }
    .rec-btn.disabled { opacity:.35;pointer-events:none; }
    .rec-info { font-size:12px;font-weight:600;color:#64748b;padding:0 10px;white-space:nowrap; }

    /* Form */
    .form-label { font-size:11px;font-weight:700;color:#64748b;letter-spacing:.06em;text-transform:uppercase;margin-bottom:5px;display:block; }
    .form-control {
        width:100%;padding:9px 12px;font-size:13.5px;color:#1e293b;
        background:#fff;border:1px solid #e2e8f0;border-radius:9px;
        transition:all .2s;outline:none;
    }
    .form-control:focus { border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.12); }
    select.form-control { appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:32px; }
    textarea.form-control { resize:vertical;min-height:72px; }

    .field-row { display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px; }
    .field-row.single { grid-template-columns:1fr; }

    /* Balance box */
    .balance-box {
        background:linear-gradient(135deg,#1E3A8A,#2563EB);
        border-radius:16px;padding:22px;color:#fff;
    }

    /* Checkbox default */
    .checkbox-wrap { display:flex;align-items:center;gap:8px;cursor:pointer; }
    .checkbox-wrap input[type=checkbox] { width:16px;height:16px;accent-color:#2563EB;cursor:pointer; }
    .checkbox-wrap span { font-size:13px;font-weight:600;color:#334155; }

    /* Save button */
    .btn-save {
        display:inline-flex;align-items:center;gap:7px;
        background:linear-gradient(135deg,#1E3A8A,#2563EB);
        color:#fff;font-size:13px;font-weight:600;border:none;
        border-radius:10px;padding:10px 22px;cursor:pointer;transition:opacity .2s,transform .15s;
    }
    .btn-save:hover { opacity:.9;transform:translateY(-1px); }
    .btn-new {
        display:inline-flex;align-items:center;gap:7px;
        background:#fff;color:#475569;font-size:13px;font-weight:600;
        border:1px solid #e2e8f0;border-radius:10px;padding:9px 18px;
        cursor:pointer;transition:all .15s;
    }
    .btn-new:hover { border-color:#2563EB;color:#2563EB;background:#eff6ff; }

    .divider { border:none;border-top:1px solid #f1f5f9;margin:18px 0; }
    .section-title { font-size:11px;font-weight:700;color:#94a3b8;letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px; }

    .mono { font-family:'Courier New',monospace; }
</style>
@endpush

@section('content')
<div class="page-fade" style="background:var(--bg);min-height:calc(100vh - 62px);padding:28px 24px;">
<div style="max-width:1340px;margin:0 auto;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:14px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <a href="{{ route('finance.index') }}" style="color:#94a3b8;text-decoration:none;">Finance</a>
        <span>/</span>
        <span style="color:#2563EB;font-weight:600;">Bank Account</span>
    </div>

    {{-- PAGE HEADER + RECORD NAV --}}
    <div style="display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:18px;">
        <div>
            <h1 style="font-size:26px;font-weight:800;margin:0 0 3px;color:#1e293b;display:flex;align-items:center;gap:10px;">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1E3A8A,#2563EB);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
                </span>
                Bank Account
            </h1>
            <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">Manage company bank accounts and financial records.</p>
        </div>

        {{-- RECORD NAVIGATION --}}
        <div class="rec-nav">
            @php
                $total      = $navigation['total'];
                $currentPos = $navigation['currentPos'];
                $firstId    = $navigation['first'];
                $lastId     = $navigation['last'];
                $prevId     = $navigation['prev'];
                $nextId     = $navigation['next'];
            @endphp
            <a href="{{ $firstId ? route('finance.bank-accounts.show', $firstId) : '#' }}"
               class="rec-btn {{ !$firstId || $currentPos === 1 ? 'disabled' : '' }}" title="First Record">«</a>
            <a href="{{ $prevId ? route('finance.bank-accounts.show', $prevId) : '#' }}"
               class="rec-btn {{ !$prevId ? 'disabled' : '' }}" title="Previous">‹</a>
            <span class="rec-info">
                Record <strong>{{ $total > 0 ? $currentPos : 0 }}</strong> of <strong>{{ $total }}</strong>
            </span>
            <a href="{{ $nextId ? route('finance.bank-accounts.show', $nextId) : '#' }}"
               class="rec-btn {{ !$nextId ? 'disabled' : '' }}" title="Next">›</a>
            <a href="{{ $lastId ? route('finance.bank-accounts.show', $lastId) : '#' }}"
               class="rec-btn {{ !$lastId || $currentPos === $total ? 'disabled' : '' }}" title="Last Record">»</a>
        </div>
    </div>

    {{-- TAB BAR --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.bank-accounts.record-detail') }}" class="tab-link active">Record Detail</a>
        <a href="{{ route('finance.bank-accounts.records-list') }}" class="tab-link">Records List</a>
        <a href="{{ route('finance.bank-accounts.statistics') }}" class="tab-link">Statistics</a>
        <a href="{{ route('finance.bank-accounts.activity') }}" class="tab-link">Activity</a>
        <a href="{{ route('finance.bank-accounts.backdate') }}" class="tab-link">Backdate</a>
        <a href="{{ route('finance.bank-accounts.summary') }}" class="tab-link">Summary</a>
    </div>

    @if(session('success'))
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- MAIN FORM + BALANCE --}}
    <form method="POST"
          action="{{ $current ? route('finance.bank-accounts.update', $current->id) : route('finance.bank-accounts.store') }}"
          style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">
        @csrf
        @if($current)
            @method('PUT')
        @endif

        {{-- LEFT: FORM CARD --}}
        <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;padding:26px;box-shadow:0 4px 18px rgba(30,58,138,.06);">

            {{-- Make Default Checkbox --}}
            <label class="checkbox-wrap" style="margin-bottom:18px;">
                <input type="checkbox" name="is_default" {{ $current?->is_default ? 'checked' : '' }}>
                <span>Make Default</span>
                <span style="font-size:11px;color:#94a3b8;font-weight:400;margin-left:4px;">(Set as company's primary bank account)</span>
            </label>
            <hr class="divider">

            {{-- ROW 1 --}}
            <div class="field-row">
                <div>
                    <label class="form-label">Branch</label>
                    <select name="branch_id" class="form-control">
                        <option value="">— Select Branch —</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch }}" {{ $current?->branch_id == $branch ? 'selected' : '' }}>{{ $branch }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Code <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="code" class="form-control" placeholder="00001"
                           value="{{ old('code', $current?->code) }}" required>
                </div>
            </div>

            {{-- ROW 2 --}}
            <div class="field-row">
                <div>
                    <label class="form-label">Currency <span style="color:#dc2626;">*</span></label>
                    <select name="currency" class="form-control" required>
                        @foreach($currencies as $cur)
                            <option value="{{ $cur }}" {{ old('currency', $current?->currency ?? 'IDR') === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Bank Name <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="bank_name" class="form-control" placeholder="BANK MEGA CAB. TENDEAN"
                           value="{{ old('bank_name', $current?->bank_name) }}" required>
                </div>
            </div>

            {{-- ROW 3: Description full width --}}
            <div class="field-row single">
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" placeholder="NO. REK. 01.074.0011.000660{{ chr(10) }}A/N PT. JASA SWADAYA UTAMA">{{ old('description', $current?->description) }}</textarea>
                </div>
            </div>

            <hr class="divider">
            <p class="section-title">Account Linkage</p>

            {{-- ROW 4 --}}
            <div class="field-row">
                <div>
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control">
                        <option value="">— Select —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $current?->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Bank Account</label>
                    <select name="bank_account" class="form-control">
                        <option value="">— Select Bank —</option>
                        @foreach($bankList as $bank)
                            <option value="{{ $bank }}" {{ old('bank_account', $current?->bank_account) === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ROW 5 --}}
            <div class="field-row">
                <div>
                    <label class="form-label">A/R Account</label>
                    <select name="ar_account" class="form-control">
                        <option value="">— Select A/R Account —</option>
                        @foreach($bankList as $bank)
                            <option value="{{ $bank }}" {{ old('ar_account', $current?->ar_account) === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Cost Center</label>
                    <select name="cost_center" class="form-control">
                        <option value="">— Select Cost Center —</option>
                        @foreach($costCenters as $cc)
                            <option value="{{ $cc }}" {{ old('cost_center', $current?->cost_center) === $cc ? 'selected' : '' }}>{{ $cc }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ROW 6 --}}
            <div class="field-row">
                <div>
                    <label class="form-label">Department</label>
                    <select name="department" class="form-control">
                        <option value="">— Select Department —</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ old('department', $current?->department) === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Credit Limit</label>
                    <input type="number" name="credit_limit" class="form-control mono" style="text-align:right;"
                           placeholder="0" min="0" step="0.01"
                           value="{{ old('credit_limit', $current?->credit_limit ?? 0) }}">
                </div>
            </div>

            <hr class="divider">

            {{-- ACTION BUTTONS --}}
            <div style="display:flex;align-items:center;gap:10px;">
                <button type="submit" class="btn-save">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save
                </button>
                <a href="{{ route('finance.bank-accounts.index') }}" class="btn-new">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    New
                </a>
            </div>
        </div>

        {{-- RIGHT: BALANCE SIDEBAR --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Balance Card --}}
            <div class="balance-box">
                <p style="font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;opacity:.7;margin:0 0 6px;">Balance</p>
                <p class="mono" style="font-size:26px;font-weight:800;margin:0;letter-spacing:-.5px;">
                    {{ number_format($current?->balance ?? 2158947733, 0, '.', ',') }}
                </p>
                <div style="height:1px;background:rgba(255,255,255,.2);margin:14px 0;"></div>
                <p style="font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;opacity:.7;margin:0 0 6px;">[ Balance ]</p>
                <p class="mono" style="font-size:20px;font-weight:700;margin:0;opacity:.9;">
                    {{ number_format($current?->balance ?? 2158947733, 0, '.', ',') }}
                </p>
            </div>

            {{-- Quick Info Card --}}
            <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:18px;box-shadow:0 2px 10px rgba(30,58,138,.05);">
                <p class="section-title" style="margin-bottom:14px;">Quick Info</p>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:11.5px;color:#64748b;">Status</span>
                        @if($current?->is_default)
                            <span style="font-size:10.5px;font-weight:700;color:#16a34a;background:#f0fdf4;padding:3px 10px;border-radius:20px;border:1px solid #bbf7d0;">DEFAULT</span>
                        @else
                            <span style="font-size:10.5px;font-weight:600;color:#64748b;background:#f8fafc;padding:3px 10px;border-radius:20px;border:1px solid #e2e8f0;">ACTIVE</span>
                        @endif
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:11.5px;color:#64748b;">Currency</span>
                        <span style="font-size:11.5px;font-weight:700;color:#1e293b;">{{ $current?->currency ?? 'IDR' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:11.5px;color:#64748b;">Credit Limit</span>
                        <span class="mono" style="font-size:11.5px;font-weight:600;color:#1e293b;">{{ number_format($current?->credit_limit ?? 0, 0, '.', ',') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:11.5px;color:#64748b;">Category</span>
                        <span style="font-size:11.5px;font-weight:600;color:#2563EB;">{{ $current?->category ?? '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Validation Errors --}}
            @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:14px 16px;">
                <p style="font-size:12px;font-weight:700;color:#dc2626;margin:0 0 8px;">Please fix the following errors:</p>
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li style="font-size:12px;color:#dc2626;margin-bottom:3px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

    </form>

</div>
</div>
@endsection
