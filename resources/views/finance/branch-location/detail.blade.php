@extends('layouts.app')

@section('title', 'Branch Location – Record Detail')

@push('styles')
<style>
    /* ── Animations ── */
    @keyframes blUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .bl-fade   { animation: blUp .35s cubic-bezier(.4,0,.2,1) both; }
    .bl-fade-1 { animation-delay: .06s; }
    .bl-fade-2 { animation-delay: .12s; }

    /* ── Section card ── */
    .bl-card {
        background: var(--c-card);
        border: 1px solid var(--c-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .bl-card-header {
        display: flex; align-items: center; gap: 10px;
        padding: 13px 20px;
        background: #fafbfc;
        border-bottom: 1px solid var(--c-border);
    }
    .bl-card-header-icon {
        width: 30px; height: 30px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .bl-card-header-icon svg {
        width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2;
    }
    .bl-card-header-icon.blue  { background:#eff6ff; color:#2563eb; }
    .bl-card-header-icon.slate { background:#f1f5f9; color:#64748b; }
    .bl-card-header h3 {
        font-size: 12.5px; font-weight: 700; color: var(--c-text); margin: 0;
    }
    .bl-card-header span {
        font-size: 10.5px; color: var(--c-muted); margin-left: auto;
    }
    .bl-card-body { padding: 22px 24px; }

    /* ── Form grid ── */
    .bl-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px 28px; }
    .bl-col-2  { grid-column: span 2; }
    @media(max-width:700px){ .bl-grid-2 { grid-template-columns: 1fr; } .bl-col-2 { grid-column: span 1; } }

    /* ── Input styling consistent with ERP ── */
    .bl-input {
        background: #fff;
        border: 1.5px solid var(--c-border);
        border-radius: 6px;
        padding: 8px 11px;
        font-size: 12.5px;
        font-family: 'Inter', sans-serif;
        color: var(--c-text);
        width: 100%;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
    }
    .bl-input:hover  { border-color: #cbd5e1; }
    .bl-input:focus  { border-color: var(--c-accent); box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
    .bl-input::placeholder { color: var(--c-subtle); }
    .bl-input:disabled { background: #f8fafc; color: var(--c-muted); }
    .bl-input.readonly-chip { background: #f8fafc; color: var(--c-muted); font-weight: 700; letter-spacing:.03em; }
    textarea.bl-input { resize: vertical; height: auto; }

    /* ── Label ── */
    .bl-label {
        display: block;
        font-size: 10.5px; font-weight: 700;
        color: var(--c-muted);
        text-transform: uppercase; letter-spacing: .05em;
        margin-bottom: 5px;
    }
    .bl-label .req { color: var(--c-danger); margin-left: 2px; }

    /* ── Error ── */
    .bl-err { font-size: 11px; color: var(--c-danger); margin-top: 4px; display: none; }
    .bl-err.show { display: block; }
    .bl-input.has-error { border-color: var(--c-danger); }

    /* ── Select2 match ERP input ── */
    .select2-container--default .select2-selection--single {
        height: 36px !important;
        border: 1.5px solid var(--c-border) !important;
        border-radius: 6px !important;
        background: #fff !important;
        display: flex !important; align-items: center !important;
        transition: border-color .15s, box-shadow .15s !important;
    }
    .select2-container--default .select2-selection--single:hover {
        border-color: #cbd5e1 !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: var(--c-accent) !important;
        box-shadow: 0 0 0 3px rgba(59,130,246,.1) !important;
        outline: none !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-size: 12.5px !important; color: var(--c-text) !important;
        padding-left: 10px !important; line-height: 34px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px !important; right: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: var(--c-subtle) !important;
    }
    .select2-container { width: 100% !important; }

    /* ── Record navigation ── */
    .bl-nav {
        display: flex; align-items: center;
        background: #f8fafc; border: 1.5px solid var(--c-border);
        border-radius: 7px; overflow: hidden;
    }
    .bl-nav a, .bl-nav span {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 30px;
        color: var(--c-muted);
        text-decoration: none;
        border-right: 1px solid var(--c-border);
        transition: background .15s, color .15s;
        flex-shrink: 0;
    }
    .bl-nav a:hover { background: #eff6ff; color: var(--c-accent); }
    .bl-nav a:last-child, .bl-nav span:last-child { border-right: none; }
    .bl-nav svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2.5; }
    .bl-nav .nav-sep { width: 1px; height: 16px; background: var(--c-border); margin: 0 4px; border: none; }
    .bl-nav .nav-disabled { opacity: .35; pointer-events: none; }

    /* ── Breadcrumb ── */
    .bl-breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--c-muted); margin-bottom: 4px; }
    .bl-breadcrumb a { color: var(--c-muted); text-decoration: none; transition: color .15s; }
    .bl-breadcrumb a:hover { color: var(--c-accent); }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="bl-breadcrumb bl-fade">
    <a href="{{ route('finance.index') }}">Finance</a>
    <span>/</span>
    <a href="{{ route('finance.branch-locations.records-list') }}">Branch Location</a>
    <span>/</span>
    <span>{{ $branchLocation->exists ? 'Edit Record' : 'New Record' }}</span>
</div>

{{-- ── PAGE HEADER ── --}}
<div class="erp-header bl-fade bl-fade-1"
     style="background:linear-gradient(135deg,#1e293b 0%,#0f172a 100%);
            border-radius:var(--radius-lg) var(--radius-lg) 0 0;
            border:1px solid rgba(255,255,255,.06);
            margin-bottom:0;">
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:36px;height:36px;border-radius:9px;background:rgba(59,130,246,.15);
                    border:1px solid rgba(59,130,246,.25);display:flex;align-items:center;justify-content:center;">
            <svg viewBox="0 0 24 24" style="width:17px;height:17px;stroke:#60a5fa;fill:none;stroke-width:2;">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>
        <div>
            <div style="font-size:14px;font-weight:800;color:#f1f5f9;letter-spacing:-.02em;">
                {{ $branchLocation->exists ? $branchLocation->name : 'New Branch Location' }}
            </div>
            <div style="font-size:10.5px;color:#64748b;margin-top:1px;">
                {{ $branchLocation->exists ? 'Editing record · ' . $branchLocation->code : 'Creating new branch/warehouse record' }}
            </div>
        </div>
    </div>

    {{-- Record Navigation --}}
    <div class="erp-header-actions">
        @if($branchLocation->exists)
            <div class="bl-nav">
                <a href="{{ $firstId ? route('finance.branch-locations.detail', $firstId) : '#' }}"
                   class="{{ (!$firstId || $firstId == $branchLocation->id) ? 'nav-disabled' : '' }}" title="First">
                    <svg viewBox="0 0 24 24"><polyline points="11 17 6 12 11 7"/><polyline points="18 17 13 12 18 7"/></svg>
                </a>
                <a href="{{ $prevId ? route('finance.branch-locations.detail', $prevId) : '#' }}"
                   class="{{ !$prevId ? 'nav-disabled' : '' }}" title="Previous">
                    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                </a>
                <span class="nav-sep"></span>
                <a href="{{ $nextId ? route('finance.branch-locations.detail', $nextId) : '#' }}"
                   class="{{ !$nextId ? 'nav-disabled' : '' }}" title="Next">
                    <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
                <a href="{{ $lastId ? route('finance.branch-locations.detail', $lastId) : '#' }}"
                   class="{{ (!$lastId || $lastId == $branchLocation->id) ? 'nav-disabled' : '' }}" title="Last">
                    <svg viewBox="0 0 24 24"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg>
                </a>
            </div>
        @endif

        <a href="{{ route('finance.branch-locations.records-list') }}" class="btn btn-ghost btn-sm">
            <svg viewBox="0 0 24 24" style="stroke:currentColor;fill:none;stroke-width:2;width:13px;height:13px;"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            Records List
        </a>
    </div>
</div>

{{-- ── FORM ── --}}
<form id="blForm"
      action="{{ route('finance.branch-locations.store', $branchLocation->id) }}"
      method="POST">
@csrf

    {{-- ── CARD 1: BASIC INFORMATION ── --}}
    <div class="bl-card bl-fade bl-fade-1" style="border-radius:0;border-top:none;border-bottom:none;border-left:1px solid var(--c-border);border-right:1px solid var(--c-border);">
        <div class="bl-card-header">
            <div class="bl-card-header-icon blue">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <h3>Basic Information</h3>
            @if($branchLocation->exists)
                <span>ID #{{ $branchLocation->id }}</span>
            @endif
        </div>
        <div class="bl-card-body">
            <div class="bl-grid-2">
                {{-- Code --}}
                <div>
                    <label class="bl-label">Code <span class="req">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $branchLocation->code) }}"
                           class="bl-input readonly-chip" placeholder="e.g. WH-01"
                           style="text-transform:uppercase;" required>
                    <div class="bl-err" id="err-code"></div>
                </div>

                {{-- Branch Name --}}
                <div>
                    <label class="bl-label">Warehouse / Branch Name <span class="req">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $branchLocation->name) }}"
                           class="bl-input" placeholder="Enter branch name" required>
                    <div class="bl-err" id="err-name"></div>
                </div>

                {{-- Address --}}
                <div class="bl-col-2">
                    <label class="bl-label">Address</label>
                    <textarea name="address" rows="3" class="bl-input"
                              placeholder="Full address of the branch or warehouse…">{{ old('address', $branchLocation->address) }}</textarea>
                    <div class="bl-err" id="err-address"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── CARD 2: ACCOUNT RELATIONS ── --}}
    <div class="bl-card bl-fade bl-fade-2" style="border-radius:0;border-top:none;border-bottom:1px solid var(--c-border);border-left:1px solid var(--c-border);border-right:1px solid var(--c-border);">
        <div class="bl-card-header">
            <div class="bl-card-header-icon slate">
                <svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            </div>
            <h3>Account Relations</h3>
            <span>Optional – link to chart of accounts</span>
        </div>
        <div class="bl-card-body">
            <div class="bl-grid-2">
                {{-- Inventory Account --}}
                <div>
                    <label class="bl-label">Inventory Account</label>
                    <select name="inventory_account_id" id="inventory_account_id" class="select2-ajax">
                        @if($branchLocation->inventoryAccount)
                            <option value="{{ $branchLocation->inventory_account_id }}" selected>
                                {{ $branchLocation->inventoryAccount->code }} – {{ $branchLocation->inventoryAccount->name }}
                            </option>
                        @endif
                    </select>
                    <div class="bl-err" id="err-inventory_account_id"></div>
                </div>

                {{-- COGS Account --}}
                <div>
                    <label class="bl-label">COGS Account</label>
                    <select name="cogs_account_id" id="cogs_account_id" class="select2-ajax">
                        @if($branchLocation->cogsAccount)
                            <option value="{{ $branchLocation->cogs_account_id }}" selected>
                                {{ $branchLocation->cogsAccount->code }} – {{ $branchLocation->cogsAccount->name }}
                            </option>
                        @endif
                    </select>
                    <div class="bl-err" id="err-cogs_account_id"></div>
                </div>

                {{-- Cost Center --}}
                <div>
                    <label class="bl-label">Cost Center</label>
                    <select name="cost_center_id" id="cost_center_id" class="select2-costs">
                        @if($branchLocation->costCenter)
                            <option value="{{ $branchLocation->cost_center_id }}" selected>
                                {{ $branchLocation->costCenter->code }} – {{ $branchLocation->costCenter->description }}
                            </option>
                        @endif
                    </select>
                    <div class="bl-err" id="err-cost_center_id"></div>
                </div>

                {{-- Account Dept --}}
                <div>
                    <label class="bl-label">Account Dept</label>
                    <select name="department_id" id="department_id" class="select2-deps">
                        @if($branchLocation->department)
                            <option value="{{ $branchLocation->department_id }}" selected>
                                {{ $branchLocation->department->code }} – {{ $branchLocation->department->name }}
                            </option>
                        @endif
                    </select>
                    <div class="bl-err" id="err-department_id"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FOOTER ACTIONS ── --}}
    <div class="erp-footer bl-fade bl-fade-2"
         style="border:1px solid var(--c-border);border-top:none;
                border-radius:0 0 var(--radius-lg) var(--radius-lg);">
        <a href="{{ route('finance.branch-locations.records-list') }}" class="btn btn-ghost">
            <svg viewBox="0 0 24 24" style="stroke:currentColor;fill:none;stroke-width:2;width:13px;height:13px;"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to List
        </a>

        <button type="button" id="btnSave" class="btn btn-primary">
            <svg viewBox="0 0 24 24" id="saveIcon" style="stroke:currentColor;fill:none;stroke-width:2.5;width:13px;height:13px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <svg viewBox="0 0 24 24" id="saveSpinner" class="hidden" style="stroke:currentColor;fill:none;stroke-width:2.5;width:13px;height:13px;animation:spin .6s linear infinite;">
                <line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/>
                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/>
                <line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/>
                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/>
            </svg>
            <span id="saveLbl">Save Record</span>
        </button>
    </div>

</form>

@push('scripts')
<style>
    @keyframes spin { to { transform: rotate(360deg); } }
    .hidden { display: none !important; }
</style>
<script>
$(document).ready(function () {

    /* ── Select2 init ── */
    const s2base = { allowClear: true, dropdownParent: $('body') };

    $('.select2-ajax').select2(Object.assign({}, s2base, {
        placeholder: 'Select account…',
        ajax: { url:'{{ route("finance.branch-locations.api.accounts") }}', dataType:'json', delay:250,
                data: p=>({q:p.term}), processResults: d=>({results:d}) }
    }));

    $('.select2-costs').select2(Object.assign({}, s2base, {
        placeholder: 'Select cost center…',
        ajax: { url:'{{ route("finance.branch-locations.api.costs") }}', dataType:'json', delay:250,
                data: p=>({q:p.term}), processResults: d=>({results:d}) }
    }));

    $('.select2-deps').select2(Object.assign({}, s2base, {
        placeholder: 'Select department…',
        ajax: { url:'{{ route("finance.branch-locations.api.deps") }}', dataType:'json', delay:250,
                data: p=>({q:p.term}), processResults: d=>({results:d}) }
    }));

    /* ── Save via AJAX ── */
    $('#btnSave').on('click', function () {
        // Reset state
        $('.bl-err').removeClass('show').text('');
        $('.bl-input').removeClass('has-error');
        $('.select2-container .select2-selection--single').css('border-color','');

        // Loading state
        $(this).prop('disabled', true);
        $('#saveIcon').addClass('hidden');
        $('#saveSpinner').removeClass('hidden');
        $('#saveLbl').text('Saving…');

        $.ajax({
            url:  $('#blForm').attr('action'),
            type: 'POST',
            data: $('#blForm').serialize(),
            success: function (res) {
                resetBtn();
                if (res.success) {
                    showToast('success', res.message);
                    setTimeout(() => window.location.href = res.redirect, 800);
                }
            },
            error: function (xhr) {
                resetBtn();
                if (xhr.status === 422) {
                    const errs = xhr.responseJSON.errors;
                    for (const key in errs) {
                        $(`#err-${key}`).text(errs[key][0]).addClass('show');
                        $(`[name="${key}"]`).addClass('has-error');
                        if ($(`[name="${key}"]`).hasClass('select2-hidden-accessible')) {
                            $(`[name="${key}"]`).next('.select2-container').find('.select2-selection--single')
                                .css('border-color', 'var(--c-danger)');
                        }
                    }
                    showToast('error', 'Please fix the highlighted fields.');
                } else {
                    showToast('error', 'An unexpected error occurred.');
                }
            }
        });
    });

    function resetBtn() {
        $('#btnSave').prop('disabled', false);
        $('#saveIcon').removeClass('hidden');
        $('#saveSpinner').addClass('hidden');
        $('#saveLbl').text('Save Record');
    }
});
</script>
@endpush

@endsection
