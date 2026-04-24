@extends('layouts.app')

@section('title', 'Human Resources')

@push('styles')
<style>
    :root {
        --hr-primary: #1e293b;
        --hr-border: #e2e8f0;
        --hr-bg-light: #f8fafc;
        --hr-accent: #2563eb;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--hr-primary);
    }

    .page-desc {
        color: #64748b;
        font-size: 0.875rem;
    }

    /* Tabs */
    .main-tabs {
        display: flex;
        gap: 2px;
        background: #e2e8f0;
        padding: 2px;
        border-radius: 8px 8px 0 0;
        width: fit-content;
    }

    .main-tab {
        padding: 8px 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        background: transparent;
        border: none;
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.2s;
        text-transform: uppercase;
    }

    .main-tab.active {
        background: white;
        color: var(--hr-accent);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .tab-content {
        background: white;
        border: 1px solid var(--hr-border);
        border-radius: 0 8px 8px 8px;
        min-height: 500px;
        padding: 0;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    /* Record Detail Layout */
    .detail-layout {
        display: flex;
        height: calc(100vh - 250px);
    }

    .detail-left {
        flex: 1.2;
        padding: 20px;
        border-right: 1px solid var(--hr-border);
        overflow-y: auto;
    }

    .detail-right {
        flex: 1;
        background: #fcfcfc;
        display: flex;
        flex-direction: column;
    }

    /* Form Styles */
    .hr-form-grid {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 10px 15px;
        align-items: center;
    }

    .hr-label {
        font-size: 0.8rem;
        color: #64748b;
        text-align: right;
    }

    .hr-input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        font-size: 0.85rem;
        color: #1e293b;
        background: #f1f5f944;
    }

    .hr-input[readonly] {
        background: #f8fafc;
        border-color: #e2e8f0;
    }

    .avatar-box {
        width: 180px;
        height: 220px;
        border: 1px solid #cbd5e1;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
    }

    .avatar-img {
        max-width: 100%;
        max-height: 100%;
        opacity: 0.5;
    }

    .clothes-size-group {
        display: flex;
        gap: 15px;
        font-size: 0.8rem;
    }

    .radio-input {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Secondary Tabs (Personal) */
    .sub-tabs {
        display: flex;
        border-bottom: 1px solid var(--hr-border);
        background: #f1f5f9;
    }

    .sub-tab {
        padding: 10px 15px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        background: none;
        border: none;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        text-transform: uppercase;
    }

    .sub-tab.active {
        color: var(--hr-accent);
        border-bottom-color: var(--hr-accent);
        background: white;
    }

    .sub-content {
        flex: 1;
        overflow-y: auto;
        padding: 0;
    }

    /* Data Table (Small) */
    .sub-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
    }

    .sub-table th {
        background: #f8fafc;
        padding: 8px 12px;
        text-align: left;
        color: #64748b;
        border-bottom: 1px solid var(--hr-border);
        font-weight: 600;
    }

    .sub-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .sub-table tr:hover {
        background: #f1f5f9;
    }

    .checkbox-cell {
        width: 40px;
        text-align: center;
    }

    /* List View Table */
    .list-table-container {
        padding: 20px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .data-table th {
        background: #f8fafc;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid var(--hr-border);
    }

    .data-table td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--hr-border);
    }

    .data-table tr:hover {
        background: #f8fafc;
        cursor: pointer;
    }

    .status-badge {
        padding: 2px 8px;
        border-radius: 99px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #fee2e2; color: #991b1b; }

    .erp-list-container { overflow-x: auto; }
    .erp-hint { font-size: 0.72rem; color: #94a3b8; padding: 6px 10px; font-style: italic; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .erp-table { width: 100%; border-collapse: collapse; font-size: 0.76rem; min-width: 900px; }
    .erp-table th { background: #dbeafe; color: #1e40af; padding: 6px 8px; text-align: left; font-weight: 700; border-bottom: 2px solid #93c5fd; border-right: 1px solid #bfdbfe; white-space: nowrap; font-size: 0.7rem; text-transform: uppercase; }
    .erp-table td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9; white-space: nowrap; color: #1e293b; font-size: 0.75rem; }
    .erp-table tr:hover td { background: #eff6ff; cursor: pointer; }
    .erp-table tr.selected td { background: #dbeafe; color: #1e40af; font-weight: 600; }
    .erp-table tr td:first-child, .erp-table tr th:first-child { text-align: center; width: 30px; }
    .erp-footer-bar { display: flex; align-items: center; gap: 6px; padding: 6px 10px; border-top: 1px solid #e2e8f0; background: #f8fafc; font-size: 0.72rem; color: #64748b; }
    .erp-footer-bar button { padding: 2px 6px; border: 1px solid #cbd5e1; border-radius: 3px; background: white; cursor: pointer; font-size: 0.72rem; }
    .erp-footer-bar button:hover { background: #e2e8f0; }
    .erp-search-bar { display: flex; justify-content: flex-end; padding: 8px 12px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; gap: 8px; align-items: center; }
    .erp-search-input { padding: 4px 8px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.78rem; width: 200px; }

    /* Footer Stats in Detail */
    .detail-footer {
        padding: 15px 20px;
        border-top: 1px solid var(--hr-border);
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        background: #f8fafc;
    }

    .loading-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    
    .list-grid {
        width: 100%;
        border-collapse: collapse;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        font-size: 0.75rem;
    }
    .list-grid th {
        background: #f1f5f9;
        color: #64748b;
        padding: 6px 8px;
        text-align: left;
        font-weight: normal;
        border-bottom: 1px solid var(--hr-border);
        border-right: 1px solid var(--hr-border);
        white-space: nowrap;
    }
    .list-grid td {
        padding: 4px 8px;
        border-bottom: 1px solid var(--hr-border);
        border-right: 1px solid var(--hr-border);
        white-space: nowrap;
        color: #334155;
    }
    .list-grid tr:hover td {
        background: #f8fafc;
        cursor: pointer;
    }
    .list-grid tr.selected td {
        background: #e2e8f0;
    }
    .grid-cell-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        height: 100%;
    }
    .ellipsis {
        color: #94a3b8;
        font-weight: bold;
        letter-spacing: 1px;
    }
    .pager-btn {
        background: white;
        border: 1px solid #cbd5e1;
        padding: 2px 6px;
        font-size: 0.65rem;
        cursor: pointer;
        color: #64748b;
    }
    .pager-btn:hover {
        background: #f1f5f9;
    }
</style>
@endpush

@section('content')
<div x-data="hrManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 8px; align-items: center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#2563eb"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            <span style="font-weight: 600;">Human Resources</span>
        </div>
        <div style="display: flex; gap: 15px;">
            <span style="cursor: pointer; font-size: 0.9rem;">◁</span>
            <span style="cursor: pointer; font-size: 0.9rem;">▷</span>
            <span style="cursor: pointer;">✕</span>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Main Navigation Tabs -->
    <div class="main-tabs">
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">RECORD DETAIL</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">RECORD LIST</button>
    </div>

    <div class="tab-content">
        <!-- TAB 1: RECORD DETAIL -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''">
            <div class="detail-layout" style="position: relative;">
                <div x-show="loading" class="loading-overlay">
                    <span>Loading details...</span>
                </div>

                <!-- Left Column: Form Info -->
                <div class="detail-left">
                    <div style="display: flex; gap: 30px; margin-bottom: 25px;">
                        <div style="flex: 1;">
                             <div class="hr-form-grid">
                                <div class="hr-label">Code</div>
                                <input type="text" class="hr-input" x-model="form.code" readonly>
                                
                                <div class="hr-label">NIP</div>
                                <input type="text" class="hr-input" x-model="form.nip" readonly>
                            </div>

                            <div style="margin-top: 25px;">
                                <div class="hr-label" style="text-align: left; margin-bottom: 5px;">Full Name</div>
                                <input type="text" class="hr-input" x-model="form.full_name" style="font-weight: 600;">
                                
                                <div class="hr-label" style="text-align: left; margin-top: 15px; margin-bottom: 5px;">Nick Name</div>
                                <input type="text" class="hr-input" x-model="form.nick_name">
                            </div>
                        </div>

                        <div class="avatar-box">
                             <svg class="avatar-img" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                             </svg>
                        </div>
                    </div>

                    <div class="hr-form-grid">
                        <div class="hr-label">Mobile</div>
                        <input type="text" class="hr-input" x-model="form.mobile">

                        <div class="hr-label">Position</div>
                        <div>
                            <input type="text" class="hr-input" x-model="form.position" list="position-list">
                            <datalist id="position-list">
                                <option value="Manager"></option>
                                <option value="Staff"></option>
                                <option value="Director"></option>
                                <option value="Supervisor"></option>
                            </datalist>
                        </div>

                        <div class="hr-label">Work at</div>
                        <div>
                            <input type="text" class="hr-input" x-model="form.work_at" list="workat-list">
                            <datalist id="workat-list">
                                <option value="Head Office"></option>
                                <option value="Branch Office"></option>
                                <option value="Remote"></option>
                            </datalist>
                        </div>

                        <div class="hr-label">Locations</div>
                        <input type="text" class="hr-input" x-model="form.location">

                        <div class="hr-label">Join Date</div>
                        <input type="date" class="hr-input" x-model="form.join_date">

                        <div class="hr-label">Clothes Size</div>
                        <div class="clothes-size-group">
                            <label class="radio-input"><input type="radio" value="S" x-model="form.clothes_size"> S</label>
                            <label class="radio-input"><input type="radio" value="M" x-model="form.clothes_size"> M</label>
                            <label class="radio-input"><input type="radio" value="L" x-model="form.clothes_size"> L</label>
                            <label class="radio-input"><input type="radio" value="XL" x-model="form.clothes_size"> XL</label>
                            <label class="radio-input"><input type="radio" value="XXL" x-model="form.clothes_size"> XXL</label>
                        </div>

                        <div class="hr-label">Size of Pants</div>
                        <input type="text" class="hr-input" style="width: 80px;" x-model="form.pants_size">

                        <div class="hr-label">Email Address</div>
                        <input type="email" class="hr-input" x-model="form.email">
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 40px; padding-left: 120px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem;">
                            <input type="checkbox" x-model="form.is_active"> Status Active
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem;">
                            <input type="checkbox" x-model="form.id_card_print"> ID Card Print
                        </label>
                    </div>
                </div>

                <!-- Right Column: Sub-tabs (Files/Attributes) -->
                <div class="detail-right">
                    <div class="sub-tabs">
                        <button class="sub-tab" :class="activeSubTab === 'files' ? 'active' : ''" @click="activeSubTab = 'files'">PERSONAL FILES</button>
                        <button class="sub-tab" :class="activeSubTab === 'attributes' ? 'active' : ''" @click="activeSubTab = 'attributes'">PERSONAL ATTRIBUTE</button>
                    </div>

                    <div class="sub-content">
                        <!-- PERSONAL FILES TAB -->
                        <div x-show="activeSubTab === 'files'">
                            <table class="sub-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">NO</th>
                                        <th>DESCRIPTION</th>
                                        <th class="checkbox-cell">CHK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(file, index) in form.files" :key="index">
                                        <tr>
                                            <td x-text="String(index + 1).padStart(2, '0')"></td>
                                            <td x-text="file.description"></td>
                                            <td class="checkbox-cell">
                                                <input type="checkbox" x-model="file.is_checked">
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="!form.files || form.files.length === 0">
                                        <td colspan="3" style="text-align: center; padding: 20px; color: #94a3b8;">No personal files recorded</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- PERSONAL ATTRIBUTE TAB -->
                        <div x-show="activeSubTab === 'attributes'">
                            <table class="sub-table">
                                <thead>
                                    <tr>
                                        <th>DATE</th>
                                        <th>USER NO</th>
                                        <th>ATTRIBUTE NAME</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="attr in form.attributes" :key="attr.id">
                                        <tr>
                                            <td x-text="attr.date"></td>
                                            <td x-text="attr.user_no"></td>
                                            <td x-text="attr.attribute_name"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="!form.attributes || form.attributes.length === 0">
                                        <td colspan="3" style="text-align: center; padding: 20px; color: #94a3b8;">No personal attributes recorded</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="detail-footer">
                        <div x-show="form.id" x-text="'Record ' + (currentIndex + 1) + ' of ' + employees.length"></div>
                        <div x-show="!form.id" style="color: #94a3b8; font-style: italic;">New Record Mode</div>
                        <div style="display: flex; gap: 8px; align-items: center;">
                             <button class="pager-btn" @click="firstRecord()" title="First Record" :disabled="currentIndex <= 0">⏮</button>
                             <button class="pager-btn" @click="prevRecord()" title="Previous Record" :disabled="currentIndex <= 0">◀</button>
                             <button class="pager-btn" @click="nextRecord()" title="Next Record" :disabled="currentIndex >= employees.length - 1">▶</button>
                             <button class="pager-btn" @click="lastRecord()" title="Last Record" :disabled="currentIndex >= employees.length - 1">⏭</button>
                             
                             <button class="pager-btn" style="margin-left: 10px; color: #059669;" @click="addRecord()" title="Add New Record">➕</button>
                             <button class="pager-btn" style="color: #dc2626;" @click="removeRecord()" title="Delete Current Record" :disabled="!form.id">➖</button>
                             <button class="pager-btn" style="color: #2563eb;" @click="editRecord()" title="Edit Record" :disabled="!form.id">🖉</button>
                             <button class="pager-btn" style="color: #475569;" @click="clearForm()" title="Clear Form / Cancel">✖</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: RECORD LIST -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''">
            <div style="display: flex; height: calc(100vh - 250px);">
                <!-- Main Grid Area -->
                <div style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
                    <div style="padding: 8px 10px; font-size: 0.75rem; color: #64748b; background: white; border-bottom: 1px solid var(--hr-border);">
                        Drag a column header here to group by that column
                    </div>
                    <div style="padding: 2px 10px; display: flex; justify-content: flex-end; background: white; border-bottom: 1px solid var(--hr-border);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #64748b;">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <div style="flex: 1; overflow: auto; background: white;">
                        <table class="list-grid">
                            <thead>
                                <tr>
                                    <th style="width: 20px;"></th>
                                    <th style="width: 50px; text-align: center;">ACTIVE</th>
                                    <th style="width: 80px;">CODE</th>
                                    <th style="width: 220px;">EMPLOYEES NAME</th>
                                    <th style="width: 150px;">NICK NAME</th>
                                    <th style="width: 100px;">NIP</th>
                                    <th style="width: 220px;">POSITION</th>
                                    <th style="width: 100px;">MOBILE</th>
                                    <th style="width: 90px;">JOIN</th>
                                    <th style="width: 80px;">CLOTHES</th>
                                    <th style="width: 80px;">PANTS</th>
                                    <th style="width: 150px;">EMAIL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $emp)
                                <tr @click="selectEmployee({{ $emp->id }})" :class="form.id === {{ $emp->id }} ? 'selected' : ''">
                                    <td style="text-align: center; color: #64748b; font-size: 0.6rem;">
                                        <div x-show="form.id === {{ $emp->id }}">▶</div>
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="checkbox" style="width: 13px; height: 13px;" {{ $emp->is_active ? 'checked' : '' }} onclick="return false;">
                                    </td>
                                    <td>{{ str_pad($emp->code, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td style="padding: 0;">
                                        <div class="grid-cell-flex" style="padding: 4px 8px;">
                                            <span>{{ strtoupper($emp->full_name) }}</span>
                                            <span class="ellipsis">...</span>
                                        </div>
                                    </td>
                                    <td>{{ strtoupper($emp->nick_name ?? '') }}</td>
                                    <td style="color: #1d4ed8; font-weight: 500;">{{ $emp->nip }}</td>
                                    <td style="padding: 0;">
                                        <div class="grid-cell-flex" style="padding: 4px 8px;">
                                            <span>{{ $emp->position }}</span>
                                            <span class="ellipsis">...</span>
                                        </div>
                                    </td>
                                    <td>{{ $emp->mobile ?? '' }}</td>
                                    <td>{{ $emp->join_date ? \Carbon\Carbon::parse($emp->join_date)->format('d/m/Y') : '' }}</td>
                                    <td>{{ $emp->clothes_size ?? '' }}</td>
                                    <td>{{ $emp->pants_size ?? '' }}</td>
                                    <td style="color: #1d4ed8;">{{ $emp->email ?? '' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pager Footer -->
                    <div style="display: flex; align-items: center; padding: 4px; border-top: 1px solid var(--hr-border); background: #f1f5f9; gap: 2px;">
                        <button class="pager-btn">|◀</button>
                        <button class="pager-btn">◀◀</button>
                        <button class="pager-btn">◀</button>
                        <span style="font-size: 0.75rem; color: #334155; margin: 0 10px;">Record 1 of {{ count($employees) ? : 10730 }}</span>
                        <button class="pager-btn">▶</button>
                        <button class="pager-btn">▶▶</button>
                        <button class="pager-btn">▶|</button>
                    </div>
                </div>

                <!-- Right Sidebar inside Grid -->
                <div style="width: 250px; border-left: 1px solid var(--hr-border); display: flex; background: white;">
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <div style="display: flex; border-bottom: 1px solid var(--hr-border);">
                            <div style="padding: 6px 10px; font-size: 0.7rem; color: #475569; border-right: 1px solid var(--hr-border); background: white; white-space: nowrap; flex: 1; border-top: 1px solid white;">PERSONAL FI...</div>
                            <div style="padding: 6px; font-size: 0.7rem; color: #475569; background: white; cursor: pointer; border-top: 1px solid white;">▶</div>
                        </div>
                        <div style="flex: 1; background: white;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 0.7rem;">
                                <thead>
                                    <tr>
                                        <th style="padding: 6px 8px; border-bottom: 1px solid var(--hr-border); border-right: 1px solid white; text-align: left; font-weight: normal; color: #64748b; background: #f1f5f9;">DATE</th>
                                        <th style="padding: 6px 8px; border-bottom: 1px solid var(--hr-border); text-align: left; font-weight: normal; color: #64748b; background: #f1f5f9;">US...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 10px; border-right: 1px solid var(--hr-border); border-bottom: 1px solid var(--hr-border);"></td>
                                        <td style="padding: 10px; border-bottom: 1px solid var(--hr-border);"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Right Button Panel -->
                    <div style="width: 28px; background: #e2e8f0; display: flex; align-items: flex-start; justify-content: center; padding-top: 10px; border-left: 1px solid var(--hr-border);">
                        <div style="writing-mode: vertical-rl; transform: rotate(180deg); font-size: 0.75rem; color: #475569; padding: 10px 0;">Button Link Menu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function hrManager() {
        return {
            activeMainTab: 'detail',
            activeSubTab: 'files',
            loading: false,
            employees: @json($employees),
            form: {
                id: null,
                code: '',
                nip: '',
                full_name: '',
                nick_name: '',
                mobile: '',
                position: '',
                work_at: '',
                location: '',
                join_date: '',
                clothes_size: '',
                pants_size: '',
                email: '',
                is_active: true,
                id_card_print: false,
                files: [],
                attributes: []
            },

            get currentIndex() {
                if (!this.form.id) return -1;
                return this.employees.findIndex(e => e.id === this.form.id);
            },

            init() {
                // Select first employee by default if exists
                if (this.employees.length > 0) {
                    this.selectEmployee(this.employees[0].id);
                }
            },

            async selectEmployee(id) {
                if (!id) return;
                this.loading = true;
                this.activeMainTab = 'detail';
                
                try {
                    const response = await fetch(`/inventory/human-resources/${id}/details`);
                    if (!response.ok) throw new Error('Failed to fetch details');
                    const data = await response.json();
                    
                    // Populate form
                    this.form = { ...data };
                } catch (error) {
                    console.error('Error fetching employee details:', error);
                } finally {
                    this.loading = false;
                }
            },

            // Navigation
            firstRecord() {
                if (this.employees.length > 0) this.selectEmployee(this.employees[0].id);
            },
            prevRecord() {
                const idx = this.currentIndex;
                if (idx > 0) this.selectEmployee(this.employees[idx - 1].id);
            },
            nextRecord() {
                const idx = this.currentIndex;
                if (idx < this.employees.length - 1) this.selectEmployee(this.employees[idx + 1].id);
            },
            lastRecord() {
                if (this.employees.length > 0) this.selectEmployee(this.employees[this.employees.length - 1].id);
            },

            // Actions
            addRecord() {
                this.clearForm();
                this.form.code = 'NEW';
            },
            removeRecord() {
                if (!this.form.id) return;
                if (confirm('Delete this employee record?')) {
                    // Mock delete logic
                    this.employees = this.employees.filter(e => e.id !== this.form.id);
                    this.nextRecord() || this.prevRecord() || this.clearForm();
                }
            },
            editRecord() {
                if (!this.form.id) return;
                alert('Edit mode activated for: ' + this.form.full_name);
            },
            clearForm() {
                this.form = {
                    id: null, code: '', nip: '', full_name: '', nick_name: '',
                    mobile: '', position: '', work_at: '', location: '',
                    join_date: '', clothes_size: '', pants_size: '',
                    email: '', is_active: true, id_card_print: false,
                    files: [], attributes: []
                };
            },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.addRecord(); showToast('New record form cleared', 'info'); break;
                    case 'save': 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.form, 'HumanResource_' + (this.form.code || 'Draft') + '.json');
                        }
                        showToast('Employee record saved to file', 'success'); 
                        break;
                    case 'delete': this.removeRecord(); break;
                    case 'edit': this.focusFirstField(); break;
                    case 'refresh': window.location.reload(); break;
                    case 'preview': window.print(); break;
                    case 'find': this.activeMainTab = 'list'; this.$nextTick(() => { if(typeof erpFindOpen === 'function') erpFindOpen(); }); break;
                    case 'undo': this.undoChanges(); break;
                    case 'save-as': 
                        this.saveAsNew(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.form, 'HumanResource_Copy.json');
                        }
                        break;
                    case 'barcode': showToast('Generating employee ID barcode...', 'info'); break;
                    case 'resend': showToast('Re-sending employee data...', 'info'); break;
                }
            },

            undoChanges() {
                if (this.form.id && confirm('Revert all unsaved changes for this employee?')) {
                    this.selectEmployee(this.form.id);
                    showToast('Changes reverted', 'info');
                }
            },

            saveAsNew() {
                if (!this.form.id) return;
                const clone = JSON.parse(JSON.stringify(this.form));
                clone.id = null;
                clone.code = 'NEW-COPY';
                clone.full_name += ' (COPY)';
                this.form = clone;
                showToast('Record ready to save as new', 'info');
            },

            focusFirstField() {
                this.activeMainTab = 'detail';
                this.$nextTick(() => {
                    const firstInput = document.querySelector('.tab-content input:not([readonly])');
                    if (firstInput) firstInput.focus();
                });
            }
        }
    }
</script>
@endpush
