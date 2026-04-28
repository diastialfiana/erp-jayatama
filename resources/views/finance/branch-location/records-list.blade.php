@extends('layouts.app')

@section('title', 'Branch Location – Record List')

@push('styles')
<style>
    /* ── Page animation ── */
    @keyframes blFadeUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .bl-fade { animation: blFadeUp 0.35s cubic-bezier(0.4,0,0.2,1) both; }
    .bl-fade-1 { animation-delay: 0.05s; }
    .bl-fade-2 { animation-delay: 0.10s; }

    /* ── Record list specific ── */
    .row-no-result td { background: #fff; }

    /* ── Selected row ── */
    .erp-table tbody tr.row-selected {
        background: #eff6ff !important;
        outline: 1px solid #93c5fd;
        outline-offset: -1px;
    }

    /* ── Search box ── */
    .search-box {
        position: relative;
        width: 260px;
    }
    .search-box svg {
        position: absolute; left: 10px; top: 50%;
        transform: translateY(-50%);
        width: 14px; height: 14px;
        stroke: var(--c-subtle); fill: none; stroke-width: 2;
        pointer-events: none;
    }
    .search-box input {
        width: 100%;
        padding: 6px 32px 6px 32px;
        border: 1.5px solid var(--c-border);
        border-radius: 6px;
        font-size: 12px;
        font-family: 'Inter', sans-serif;
        color: var(--c-text);
        background: #fff;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
    }
    .search-box input:focus {
        border-color: var(--c-accent);
        box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    }
    .search-box .search-clear {
        position: absolute; right: 10px; top: 50%;
        transform: translateY(-50%);
        background: none; border: none; cursor: pointer;
        color: var(--c-subtle); padding: 0;
        display: none;
    }
    .search-box .search-clear svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2; }
    .search-box input:not(:placeholder-shown) + svg + .search-clear { display: block; }

    /* ── Breadcrumb ── */
    .bl-breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--c-muted); margin-bottom: 4px; }
    .bl-breadcrumb a { color: var(--c-muted); text-decoration: none; transition: color .15s; }
    .bl-breadcrumb a:hover { color: var(--c-accent); }
    .bl-breadcrumb .sep { color: var(--c-subtle); }

    /* ── Code chip ── */
    .code-chip {
        display: inline-block;
        padding: 2px 7px;
        background: #f8fafc;
        border: 1px solid var(--c-border);
        border-radius: 4px;
        font-size: 10.5px;
        font-weight: 700;
        color: var(--c-muted);
        letter-spacing: .04em;
        font-family: 'Inter', monospace;
    }

    /* ── Pagination ── */
    .bl-pager { display: flex; align-items: center; gap: 4px; }
    .bl-pager a, .bl-pager span {
        display: inline-flex; align-items: center; justify-content: center;
        width: 30px; height: 30px;
        border-radius: 6px;
        font-size: 11.5px; font-weight: 600;
        text-decoration: none;
        border: 1.5px solid transparent;
        transition: all .15s;
    }
    .bl-pager a { color: var(--c-text); border-color: var(--c-border); background: #fff; }
    .bl-pager a:hover { border-color: var(--c-accent); color: var(--c-accent); background: #eff6ff; }
    .bl-pager .pager-active { background: var(--c-accent); color: #fff; border-color: var(--c-accent); }
    .bl-pager .pager-dots  { color: var(--c-subtle); border-color: transparent; background: none; cursor: default; }
    .bl-pager .pager-nav   { width: auto; padding: 0 10px; gap: 5px; }
    .bl-pager .pager-nav svg { width: 12px; height: 12px; stroke: currentColor; fill: none; stroke-width: 2.5; flex-shrink: 0; }
    .bl-pager .pager-disabled { opacity: .4; pointer-events: none; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="bl-breadcrumb bl-fade">
    <a href="{{ route('finance.index') }}">Finance</a>
    <span class="sep">/</span>
    <span>Branch Location</span>
</div>

{{-- ── Page Header ── --}}
<div class="erp-header mb-0 rounded-t-[var(--radius-lg)] border border-b-0 border-[var(--c-border)] bl-fade bl-fade-1"
     style="background: linear-gradient(135deg,#1e293b 0%,#0f172a 100%); border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:36px;height:36px;border-radius:9px;background:rgba(59,130,246,.15);
                    border:1px solid rgba(59,130,246,.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg viewBox="0 0 24 24" style="width:17px;height:17px;stroke:#60a5fa;fill:none;stroke-width:2;">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>
        <div>
            <div style="font-size:14px;font-weight:800;color:#f1f5f9;letter-spacing:-.02em;">Branch Location</div>
            <div style="font-size:10.5px;color:#64748b;margin-top:1px;">Warehouse & branch master data</div>
        </div>
    </div>

    <div class="erp-header-actions">
        {{-- Search --}}
        <form action="{{ route('finance.branch-locations.records-list') }}" method="GET" id="searchForm">
            <div class="search-box">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari warehouse…" id="searchInput" autocomplete="off">
                <button type="button" class="search-clear" id="searchClear">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        </form>

        {{-- Divider --}}
        <div style="width:1px;height:22px;background:rgba(255,255,255,.1);margin:0 4px;"></div>

        {{-- Action buttons --}}
        <button id="btnEdit" class="btn btn-ghost btn-sm" disabled>
            <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit
        </button>

        <button id="btnDelete" class="btn btn-danger btn-sm" disabled>
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            Delete
        </button>

        <a href="{{ route('finance.branch-locations.detail') }}" class="btn btn-primary btn-sm">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add New
        </a>
    </div>
</div>

{{-- ── Main Grid Card ── --}}
<div class="erp-container bl-fade bl-fade-2" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg); border-top: none;">

    {{-- Table --}}
    <div class="erp-table-wrap" style="border:none;border-radius:0;max-height:calc(100vh - 260px);overflow-y:auto;">
        <table class="erp-table" id="branchTable">
            <thead>
                <tr>
                    <th style="width:120px;">Code</th>
                    <th>Warehouse / Branch</th>
                    <th style="width:160px;">Address</th>
                    <th style="width:130px;text-align:center;">Audit</th>
                    <th style="width:80px;text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branchLocations as $index => $location)
                    @php
                        $auditMap = [
                            0 => ['label'=>'EKO',    'class'=>'badge-blue'],
                            1 => ['label'=>'DENY',   'class'=>'badge-red'],
                            2 => ['label'=>'YUNITA', 'class'=>'badge-green'],
                            3 => ['label'=>'SYSTEM', 'class'=>'badge-gray'],
                        ];
                        $audit = $auditMap[$location->id % 4];
                    @endphp
                    <tr class="data-row"
                        data-id="{{ $location->id }}"
                        data-url="{{ route('finance.branch-locations.detail', $location->id) }}"
                        style="cursor:pointer;">

                        {{-- Code --}}
                        <td>
                            <span class="code-chip">{{ $location->code }}</span>
                        </td>

                        {{-- Warehouse --}}
                        <td>
                            <span style="font-weight:600;color:var(--c-text);">{{ $location->name }}</span>
                        </td>

                        {{-- Address --}}
                        <td style="color:var(--c-muted);max-width:200px;">
                            <span style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ $location->address ?: '—' }}
                            </span>
                        </td>

                        {{-- Audit badge --}}
                        <td style="text-align:center;">
                            <span class="badge {{ $audit['class'] }}">{{ $audit['label'] }}</span>
                        </td>

                        {{-- Action --}}
                        <td style="text-align:center;">
                            <a href="{{ route('finance.branch-locations.detail', $location->id) }}"
                               class="btn btn-ghost btn-sm btn-icon"
                               title="Edit record"
                               onclick="event.stopPropagation();">
                                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr class="row-no-result">
                        <td colspan="5">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                <p style="font-weight:600;margin-bottom:4px;">Belum ada data</p>
                                <p>Tidak ada branch location yang ditemukan.
                                    @if($search)
                                        Coba hapus filter pencarian.
                                    @else
                                        Tambahkan data baru dengan tombol <strong>Add New</strong>.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Footer: info + pagination ── --}}
    <div class="erp-footer" style="justify-content:space-between;">
        <span style="font-size:11.5px;color:var(--c-muted);">
            @if($branchLocations->total() > 0)
                Showing <strong>{{ $branchLocations->firstItem() }}</strong>–<strong>{{ $branchLocations->lastItem() }}</strong>
                of <strong>{{ $branchLocations->total() }}</strong> records
            @else
                No records found
            @endif
        </span>

        @if($branchLocations->hasPages())
            <nav class="bl-pager">
                {{-- Prev --}}
                @if($branchLocations->onFirstPage())
                    <span class="pager-nav pager-disabled">
                        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg> Prev
                    </span>
                @else
                    <a href="{{ $branchLocations->previousPageUrl() }}" class="pager-nav">
                        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg> Prev
                    </a>
                @endif

                {{-- Pages --}}
                @foreach($branchLocations->getUrlRange(1, $branchLocations->lastPage()) as $page => $url)
                    @if(abs($page - $branchLocations->currentPage()) <= 2 || $page == 1 || $page == $branchLocations->lastPage())
                        @if($page == $branchLocations->currentPage())
                            <span class="pager-active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @elseif(abs($page - $branchLocations->currentPage()) == 3)
                        <span class="pager-dots">…</span>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($branchLocations->hasMorePages())
                    <a href="{{ $branchLocations->nextPageUrl() }}" class="pager-nav">
                        Next <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                @else
                    <span class="pager-nav pager-disabled">
                        Next <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                @endif
            </nav>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows    = document.querySelectorAll('.data-row');
    const btnEdit = document.getElementById('btnEdit');
    const btnDel  = document.getElementById('btnDelete');
    let   selUrl  = null;

    /* ─── Row selection ─── */
    rows.forEach(row => {
        row.addEventListener('click', () => {
            rows.forEach(r => r.classList.remove('row-selected'));
            row.classList.add('row-selected');
            selUrl = row.dataset.url;

            btnEdit.removeAttribute('disabled');
            btnDel.removeAttribute('disabled');
        });

        row.addEventListener('dblclick', () => {
            if (row.dataset.url) window.location.href = row.dataset.url;
        });
    });

    /* ─── Toolbar buttons ─── */
    btnEdit.addEventListener('click', () => { if (selUrl) window.location.href = selUrl; });
    btnDel.addEventListener('click', () => {
        if (!selUrl) return;
        if (confirm('Are you sure you want to delete this branch location?')) {
            // Implement delete via form POST if needed
            alert('Delete functionality to be wired up.');
        }
    });

    /* ─── Search: debounce + auto-submit ─── */
    const searchForm  = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const searchClear = document.getElementById('searchClear');
    let   timer;

    searchInput.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => searchForm.submit(), 500);
    });

    searchClear.addEventListener('click', () => {
        searchInput.value = '';
        searchForm.submit();
    });

    /* show/hide clear button */
    function toggleClear() {
        searchClear.style.display = searchInput.value ? 'block' : 'none';
    }
    toggleClear();
    searchInput.addEventListener('input', toggleClear);

    /* ─── Keyboard navigation (↑ ↓ Enter) ─── */
    let focusIdx = -1;
    const rowArr = Array.from(rows);

    document.addEventListener('keydown', e => {
        if (document.activeElement === searchInput) return;
        if (!rowArr.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            focusIdx = Math.min(focusIdx + 1, rowArr.length - 1);
            rowArr[focusIdx]?.click();
            rowArr[focusIdx]?.scrollIntoView({ block: 'nearest' });
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            focusIdx = Math.max(focusIdx - 1, 0);
            rowArr[focusIdx]?.click();
            rowArr[focusIdx]?.scrollIntoView({ block: 'nearest' });
        } else if (e.key === 'Enter' && focusIdx >= 0) {
            const url = rowArr[focusIdx]?.dataset.url;
            if (url) window.location.href = url;
        }
    });
});
</script>
@endpush

@endsection
