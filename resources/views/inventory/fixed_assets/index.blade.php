@extends('layouts.app')
@section('title', 'Fixed Assets')

@push('styles')
    <style>
        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .page-desc {
            font-size: 0.85rem;
            color: #64748b;
        }

        /* ── Main Content Wrapper ── */
        .main-content {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid #eef2ff;
        }

        /* ── Form Layout ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .form-group {
            margin-bottom: 14px;
            display: flex;
            align-items: center;
        }

        .form-group label {
            flex: 0 0 160px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-right: 15px;
            text-align: right;
        }

        .form-control {
            flex: 1;
            padding: 8px 12px;
            font-size: 0.85rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            color: #1e293b;
            background-color: #f8fafc;
            transition: all 0.2s;
            width: 100%;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #2563EB;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 32px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
            display: flex;
            align-items: center;
        }
        
        .form-row .form-group label {
            flex: 0 0 160px; /* Primary label in a row */
            margin-right: 15px;
        }
        
        .form-row .form-group.pl-3 label {
            flex: 0 0 95px; /* Secondary label alignment */
            margin-left: 10px;
            text-align: right;
        }

        .radio-group {
            display: flex;
            gap: 15px;
            flex: 1;
            align-items: center;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #374151;
            cursor: pointer;
        }

        input[type="radio"] {
            accent-color: #2563EB;
            width: 14px;
            height: 14px;
        }

        .textarea-control {
            resize: vertical;
            min-height: 80px;
        }

        /* ── QR Code Section ── */
        .qr-section {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
        }

        .qr-placeholder {
            text-align: center;
        }
        
        .qr-placeholder img {
            width: 120px;
            height: 120px;
            background: #fff;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .qr-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 0.05em;
        }

        /* ── Status Section ── */
        .status-grid {
            background: #f8fafc;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            margin-top: 20px;
        }

        .status-grid .form-group label {
            flex: 0 0 120px;
        }

        /* ── Buttons ── */
        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            margin-top: 20px;
        }

        .btn {
            padding: 8px 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: #2563EB;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-outline {
            background: transparent;
            border-color: #cbd5e1;
            color: #475569;
        }

        .btn-outline:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        /* ── Modal Styles ── */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            opacity: 0;
            transition: opacity 0.2s ease-out;
        }
        
        .modal-overlay.show {
            display: flex;
            opacity: 1;
        }

        .custom-modal {
            background: #f8fafc;
            border-radius: 10px;
            width: 100%;
            max-width: 750px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: scale(0.95);
            transition: transform 0.2s ease-out;
            border: 1px solid #cbd5e1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .modal-overlay.show .custom-modal {
            transform: scale(1);
        }

        .modal-header {
            background: #e2e8f0;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #cbd5e1;
        }

        .modal-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .modal-title svg {
            width: 18px; height: 18px; color: #f59e0b;
        }

        .modal-close {
            background: none; border: none; font-size: 1.25rem;
            color: #64748b; cursor: pointer; line-height: 1;
        }
        .modal-close:hover { color: #ef4444; }

        .modal-body {
            background: #f1f5f9;
            padding: 0;
            flex: 1;
        }
        
        .modal-tabs {
            display: flex;
            background: #f1f5f9;
            border-bottom: 1px solid #cbd5e1;
            padding: 10px 20px 0 20px;
        }
        
        .mod-tab {
            padding: 8px 16px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            border-bottom: none;
            border-radius: 4px 4px 0 0;
            margin-right: 4px;
            cursor: pointer;
        }
        
        .mod-tab.active {
            background: #f8fafc;
            color: #334155;
            border-bottom-color: #f8fafc;
            margin-bottom: -1px;
            z-index: 1;
        }

        .mod-content {
            display: none;
            padding: 30px;
            background: #f8fafc;
        }
        
        .mod-content.active {
            display: block;
        }

        /* ── Modal Form Content ── */
        .mod-form-group {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        .mod-form-group label {
            flex: 0 0 130px;
            text-align: right;
            margin-right: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
        }
        
        /* ── Data Table ── */
        .table-responsive {
            overflow-x: auto;
            background: #fff;
            border: 1px solid #cbd5e1;
            margin: 0;
            height: 300px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }
        .data-table th, .data-table td {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }
        .data-table th {
            background: #e2e8f0;
            color: #334155;
            font-weight: 600;
            text-transform: uppercase;
        }
        .data-table tr.active {
            background: #cbd5e1; /* Simulating selected row */
        }
        .data-table tr:hover:not(.active) {
            background: #f1f5f9;
            cursor: pointer;
        }
        
        /* ── Responsive ── */
        @media (max-width: 992px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .form-group, .mod-form-group {
                flex-direction: column;
                align-items: flex-start;
            }
            .form-group label, .mod-form-group label {
                flex: none;
                margin-bottom: 6px;
                text-align: left;
            }
            .form-row {
                flex-direction: column;
            }
        }
        /* ── Tab styles ── */
        .fa-window { background: white; border: 1px solid #cbd5e1; }
        .fa-title-bar { background: white; padding: 6px 10px; border-bottom: 1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; font-size: 0.8rem; }
        .fa-tabs { display:flex; gap:2px; background:#e2e8f0; padding:4px 10px 0; border-bottom: 1px solid #cbd5e1; }
        .fa-tab { padding:6px 15px; font-size:0.75rem; font-weight:normal; color:#334155; background:transparent; border:1px solid transparent; cursor:pointer; text-transform:uppercase; }
        .fa-tab.active { background:white; border:1px solid #cbd5e1; border-bottom-color:white; margin-bottom:-1px; z-index:1; }
        .fa-pane { display:none; }
        .fa-pane.active { display:block; }

        /* ── Records List ── */
        .rec-toolbar { padding: 6px 10px; display:flex; justify-content:space-between; align-items:center; background:white; border-bottom:1px solid #e2e8f0; font-size:0.75rem; color:#64748b; }
        .rec-table-wrap { overflow-x:auto; overflow-y:auto; max-height: calc(100vh - 240px); }
        .rec-table { width:100%; border-collapse:collapse; font-size:0.75rem; white-space:nowrap; }
        .rec-table th { background: white; color:#64748b; padding:5px 8px; text-align:left; font-weight:normal; border-bottom:1px solid #cbd5e1; border-right:1px solid #e2e8f0; text-transform:uppercase; font-size:0.7rem; position:sticky; top:0; z-index:2; }
        .rec-table td { padding:4px 8px; border-bottom:1px solid #f1f5f9; border-right:1px solid #f1f5f9; color:#334155; }
        .rec-table tr.selected td { background:#dbeafe; }
        .rec-table tr:hover:not(.selected) td { background:#f8fafc; cursor:pointer; }
        .rec-table td.red { color:#dc2626; }
        .rec-footer { display:flex; align-items:center; padding:4px 10px; border-top:1px solid #e2e8f0; background:#f8fafc; gap:2px; min-height:30px; }
        .pager-btn { background:white; border:1px solid #cbd5e1; padding:2px 7px; font-size:0.65rem; cursor:pointer; color:#64748b; }
        .pager-btn:hover { background:#f1f5f9; }
        .pager-btn:disabled { color:#cbd5e1; cursor:not-allowed; }

        /* ── Detail nav bar ── */
        .detail-nav { display:flex; align-items:center; gap:4px; padding:6px 10px; border-bottom:1px solid #e2e8f0; background:#f8fafc; }
    </style>
@endpush

@section('content')
<div x-data="fixedAssetManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)" class="fa-window">
    <!-- Title Bar -->
    <div class="window-title-bar">
        <div style="display:flex;gap:8px;align-items:center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7" fill="#dc2626"></rect><rect x="14" y="14" width="7" height="7" fill="#2563eb"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            <span style="font-weight:600;">Fixed Asset</span>
        </div>
        <div style="display:flex;gap:15px;">
            <button @click="prevRecord()" class="pager-btn" :disabled="currentIndex === 0">◁</button>
            <button @click="nextRecord()" class="pager-btn" :disabled="currentIndex === records.length - 1">▷</button>
            <span style="cursor:pointer;">✕</span>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Tabs -->
    <div class="fa-tabs">
        <button class="fa-tab" :class="activeTab === 'detail' ? 'active' : ''" @click="activeTab = 'detail'">RECORD DETAIL</button>
        <button class="fa-tab" :class="activeTab === 'list' ? 'active' : ''" @click="activeTab = 'list'">RECORDS LIST</button>
    </div>

    <!-- ══ RECORD DETAIL PANE ══ -->
    <div class="fa-pane" :class="activeTab === 'detail' ? 'active' : ''">
        <!-- Detail nav -->
        <div class="detail-nav">
            <button @click="prevRecord()" class="pager-btn" :disabled="currentIndex === 0">|◀</button>
            <button @click="prevRecord()" class="pager-btn" :disabled="currentIndex === 0">◀</button>
            <span style="font-size:0.75rem;padding:0 8px;">Record <span x-text="currentIndex + 1"></span> of <span x-text="records.length"></span></span>
            <button @click="nextRecord()" class="pager-btn" :disabled="currentIndex === records.length - 1">▶</button>
            <button @click="nextRecord()" class="pager-btn" :disabled="currentIndex === records.length - 1">▶|</button>
        </div>

    <!-- MAIN FORM WRAPPER -->
    <div class="main-content" style="border-radius:0;border:none;box-shadow:none;">
        <div class="form-grid">
            <!-- Left Column -->
            <div class="form-col-left">
                <div class="form-row">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" id="main-asset-code" class="form-control" :value="records[currentIndex].code" @input="records[currentIndex].code = $event.target.value">
                    </div>
                    <div class="form-group pl-3">
                        <label>Currency</label>
                        <select class="form-control" x-model="records[currentIndex].curr">
                            <option>IDR</option>
                            <option>USD</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Fixed Asset Name</label>
                        <input type="text" class="form-control" x-model="records[currentIndex].name">
                    </div>
                    <div class="form-group pl-3">
                        <label>Qty</label>
                        <input type="number" class="form-control" x-model="records[currentIndex].qty">
                    </div>
                </div>

                <div class="form-group">
                    <label>Initial Date</label>
                    <input type="date" class="form-control" x-model="records[currentIndex].initial_date" @change="onDateChange($event)">
                </div>

                <div class="form-group">
                    <label>Cost Center</label>
                    <input type="text" class="form-control" x-model="records[currentIndex].cost_center">
                </div>

                <div class="form-group">
                    <label>Department</label>
                    <select class="form-control" x-model="records[currentIndex].dept">
                        <option value=""></option>
                        <option value="IT">IT</option>
                        <option value="Finance">Finance</option>
                        <option value="Procurement">Procurement</option>
                        <option value="GA">GA</option>
                        <option value="HR">HR</option>
                        <option value="HC">HC</option>
                        <option value="Legal">Legal</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <div style="display:flex; flex:1; gap:6px;">
                        <input type="text" id="main_category_input" class="form-control" :value="records[currentIndex].category" readonly style="cursor: pointer; background-color: #fff;" onclick="openCategoryModal()">
                        <button type="button" class="btn btn-outline" style="padding: 0 10px;" onclick="openCategoryModal()">...</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" class="form-control" x-model="records[currentIndex].location">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Initial Cost</label>
                        <input type="text" class="form-control" x-model="records[currentIndex].initial_cost">
                    </div>
                    <div class="form-group pl-3">
                        <label>Rate</label>
                        <input type="number" class="form-control" x-model="records[currentIndex].rate">
                    </div>
                </div>

                <div class="form-group">
                    <label>Residual Values</label>
                    <input type="text" class="form-control" x-model="records[currentIndex].residual">
                </div>

                <div class="form-group">
                    <label>Deprec. Method</label>
                    <div class="radio-group">
                        <label class="radio-item"><input type="radio" name="deprec_method" value="None" :checked="records[currentIndex].deprec_method === 'None'" @change="records[currentIndex].deprec_method = 'None'"> (None)</label>
                        <label class="radio-item"><input type="radio" name="deprec_method" value="Declining" :checked="records[currentIndex].deprec_method === 'Declining'" @change="records[currentIndex].deprec_method = 'Declining'"> Declining</label>
                        <label class="radio-item"><input type="radio" name="deprec_method" value="Straight Line" :checked="records[currentIndex].deprec_method === 'Straight Line'" @change="records[currentIndex].deprec_method = 'Straight Line'"> Straight Line</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Useful Life</label>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <input type="number" class="form-control" style="width: 70px;" x-model="records[currentIndex].useful_life">
                            <span style="font-size:0.8rem; color:#64748b;">Month</span>
                        </div>
                    </div>
                    <div class="form-group pl-3">
                        <label>% Depreciation</label>
                        <input type="text" class="form-control" x-model="records[currentIndex].deprec_pct">
                    </div>
                </div>

                <div class="form-group">
                    <label>Reg. / Serial No.</label>
                    <input type="text" class="form-control" x-model="records[currentIndex].serial">
                </div>

                <div class="form-group">
                    <label>Supplier Name</label>
                    <input type="text" class="form-control" x-model="records[currentIndex].supplier">
                </div>

                <div class="form-group">
                    <label>Services provider</label>
                    <input type="text" class="form-control" x-model="records[currentIndex].services_provider">
                </div>

                <div class="form-group">
                    <label>User</label>
                    <input type="text" class="form-control" x-model="records[currentIndex].asset_user">
                </div>

                <div class="form-group">
                    <label>Valid Guaranty</label>
                    <input type="date" class="form-control" x-model="records[currentIndex].valid_guaranty">
                </div>

                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" class="form-control" x-model="records[currentIndex].brand">
                </div>

                <div class="form-group">
                    <label>Assets Type</label>
                    <input type="text" class="form-control" x-model="records[currentIndex].type">
                </div>
            </div>

            <!-- Right Column -->
            <div class="form-col-right">
                <div class="form-group">
                    <label>Accumulated Account</label>
                    <select class="form-control" id="main_accumulated_account" x-model="records[currentIndex].accumulated_account">
                        <option>AKUM PENYST PERABOT KTR UNSUR LOGAM</option>
                        <option>AKUM PENYST BANG.GEDUNG KANTOR</option>
                        <option>AKUM PENYST INSTALASI LISTRIK</option>
                        <option>AKUM PENYST KENDARAAN MOBIL</option>
                        <option>AKUM PENYST KOMPUTER (HARDWARE)</option>
                        <option>AKUM PENYST MESIN KANTOR</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Depreciation Expense</label>
                    <select class="form-control" id="main_depreciation_expense" x-model="records[currentIndex].depreciation_expense">
                        <option>BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM</option>
                        <option>BIAYA PENYUSUTAN GEDUNG</option>
                        <option>BIAYA PENYUSUTAN INSTALASI</option>
                        <option>BIAYA PENYUSUTAN KENDARAAN</option>
                        <option>BIAYA PENYUSUTAN KOMPUTER</option>
                        <option>BIAYA PENYUSUTAN MESIN</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Note</label>
                    <textarea class="form-control textarea-control" x-model="records[currentIndex].note"></textarea>
                </div>

                <!-- QR CODE SECTION -->
                <div class="qr-section">
                    <a id="qr-link" :href="'/a/' + records[currentIndex].code" target="_blank" style="text-decoration: none; color: inherit;">
                        <div class="qr-placeholder" style="cursor: pointer;" title="Klik untuk membuka detail publik">
                            <div style="position: relative; display: inline-block;">
                                <img id="qr-image" :src="'https://api.qrserver.com/v1/create-qr-code/?size=150x150&ecc=H&data=' + encodeURIComponent(window.location.origin + '/a/' + records[currentIndex].code)" alt="QR Code Asset">
                                <img src="{{ asset('images/JSU.jpg') }}" alt="JSU Logo" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 36px; height: auto; border: 2px solid white; border-radius: 4px; box-shadow: 0 0 4px rgba(0,0,0,0.1);">
                            </div>
                            <div id="qr-label-display" class="qr-label" x-text="records[currentIndex].code + ' - ' + records[currentIndex].name"></div>
                        </div>
                    </a>
                </div>

                <div class="status-grid">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Start</label>
                            <input type="date" class="form-control" x-model="records[currentIndex].depr_start">
                        </div>
                        <div class="form-group pl-3">
                            <label>End</label>
                            <input type="date" class="form-control" x-model="records[currentIndex].depr_end">
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>Life to date</label>
                        <input type="text" class="form-control" x-model="records[currentIndex].life_to_date" style="text-align: right;">
                    </div>

                    <div class="form-group">
                        <label>Year to date</label>
                        <input type="text" class="form-control" x-model="records[currentIndex].year_to_date" style="text-align: right;">
                    </div>

                    <div class="form-group">
                        <label>Monthly</label>
                        <input type="text" class="form-control" x-model="records[currentIndex].monthly" style="text-align: right;">
                    </div>

                    <div class="form-group">
                        <label>Journal Posted</label>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <input type="number" class="form-control" x-model="records[currentIndex].journal_posted" style="text-align: right; width: 80px;">
                            <span style="font-size:0.8rem; color:#64748b;">Times</span>
                        </div>
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-outline">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    </div><!-- /fa-pane detail -->

    <!-- ══ RECORDS LIST PANE ══ -->
    <div class="fa-pane" :class="activeTab === 'list' ? 'active' : ''">
        <div class="rec-toolbar">
            <span>Drag a column header here to group by that column</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </div>
        <div class="rec-table-wrap">
            <table class="rec-table">
                <thead>
                    <tr>
                        <th style="width:20px;"></th>
                        <th style="width:80px;">CODE</th>
                        <th style="width:60px;">CURR</th>
                        <th style="width:60px;">RATE</th>
                        <th style="width:60px;">BRAND</th>
                        <th style="min-width:200px;">FIXED ASSET</th>
                        <th style="width:80px;">TYPE</th>
                        <th style="width:60px;">QTY</th>
                        <th style="width:60px;">CAT.</th>
                        <th style="width:120px;">SERIAL NO.</th>
                        <th style="width:150px;">LOCATION</th>
                        <th style="width:100px;">SINCE</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(rec, idx) in records" :key="idx">
                        <tr :class="idx === currentIndex ? 'selected' : ''" @click="selectRecord(idx)">
                            <td><span x-show="idx === currentIndex">▶</span></td>
                            <td x-text="rec.code"></td>
                            <td x-text="rec.curr"></td>
                            <td x-text="rec.rate" style="text-align:right;"></td>
                            <td x-text="rec.brand"></td>
                            <td>
                                <span x-text="rec.name"></span>
                                <span style="color:#cbd5e1;margin-left:4px;">...</span>
                            </td>
                            <td x-text="rec.type"></td>
                            <td x-text="rec.qty" style="text-align:right;"></td>
                            <td x-text="rec.cat"></td>
                            <td x-text="rec.serial"></td>
                            <td x-text="rec.location"></td>
                            <td x-text="rec.since"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="rec-footer">
            <button class="pager-btn" @click="currentIndex = 0">|◀</button>
            <button class="pager-btn" @click="currentIndex = Math.max(0, currentIndex - 1)">◀◀</button>
            <button class="pager-btn" @click="prevRecord()">◀</button>
            <span style="font-size:0.75rem;padding:0 8px;">Record <span x-text="currentIndex + 1"></span> of <span x-text="records.length"></span></span>
            <button class="pager-btn" @click="nextRecord()">▶</button>
            <button class="pager-btn" @click="currentIndex = Math.min(records.length-1, currentIndex + 1)">▶▶</button>
            <button class="pager-btn" @click="currentIndex = records.length - 1">▶|</button>
        </div>
    </div><!-- /fa-pane list -->

</div><!-- /fa-window -->
    
    <!-- Alpine.js Manager -->
    <script>
        function fixedAssetManager() {
            return {
                activeTab: 'detail',
                currentIndex: 0,
                records: [
                    { code:'001101', curr:'IDR', rate:'1', brand:'', name:'lemari', type:'Perabot', qty:0, cat:'010', serial:'', location:'HEAD OFFICE', since:'18/07/2017', initial_date:'2017-07-18', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Unsur Logam', initial_cost:'1.667.000', residual:'0', deprec_method:'Straight Line', useful_life:96, deprec_pct:'0,13', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-17', depr_end:'2025-07-17', life_to_date:'0,00', year_to_date:'520.937,40', monthly:'17.364,58', journal_posted:0 },
                    { code:'001102', curr:'IDR', rate:'1', brand:'', name:'meja kerja', type:'Perabot', qty:0, cat:'009', serial:'', location:'HEAD OFFICE', since:'18/07/2017', initial_date:'2017-07-18', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Bukan Logam', initial_cost:'850.000', residual:'0', deprec_method:'Straight Line', useful_life:60, deprec_pct:'0,20', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-17', depr_end:'2022-07-17', life_to_date:'0,00', year_to_date:'170.000,00', monthly:'14.166,67', journal_posted:0 },
                    { code:'001103', curr:'IDR', rate:'1', brand:'', name:'tempat sampah', type:'Perabot', qty:0, cat:'009', serial:'', location:'HEAD OFFICE', since:'18/07/2017', initial_date:'2017-07-18', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Bukan Logam', initial_cost:'120.000', residual:'0', deprec_method:'Straight Line', useful_life:48, deprec_pct:'0,25', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-17', depr_end:'2021-07-17', life_to_date:'0,00', year_to_date:'30.000,00', monthly:'2.500,00', journal_posted:0 },
                    { code:'001104', curr:'IDR', rate:'1', brand:'CAMRY', name:'timbangan merk camry', type:'Mesin', qty:0, cat:'008', serial:'CMR-2017-001', location:'HEAD OFFICE', since:'18/07/2017', initial_date:'2017-07-18', cost_center:'OPERASIONAL', dept:'GA', category:'Mesin Kantor', initial_cost:'450.000', residual:'0', deprec_method:'Straight Line', useful_life:48, deprec_pct:'0,25', supplier:'Toko Alat Ukur', services_provider:'', asset_user:'GA Dept', valid_guaranty:'', note:'Timbangan area kantin', accumulated_account:'AKUM PENYST MESIN KANTOR', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-17', depr_end:'2021-07-17', life_to_date:'0,00', year_to_date:'112.500,00', monthly:'9.375,00', journal_posted:4 },
                    { code:'001105', curr:'IDR', rate:'1', brand:'Q&Q', name:'stopwhatch merk Q&Q', type:'Mesin', qty:0, cat:'008', serial:'QQ-SW-001', location:'HEAD OFFICE', since:'18/07/2017', initial_date:'2017-07-18', cost_center:'OPERASIONAL', dept:'Procurement', category:'Mesin Kantor', initial_cost:'185.000', residual:'0', deprec_method:'Straight Line', useful_life:36, deprec_pct:'0,33', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'', accumulated_account:'AKUM PENYST MESIN KANTOR', depreciation_expense:'BIAYA PENYUSUTAN MESIN KANTOR', depr_start:'2017-08-17', depr_end:'2020-07-17', life_to_date:'0,00', year_to_date:'61.666,00', monthly:'5.138,89', journal_posted:0 },
                    { code:'001106', curr:'IDR', rate:'1', brand:'GEA', name:'meteran type GEA bdn', type:'Mesin', qty:0, cat:'008', serial:'GEA-MT-001', location:'HEAD OFFICE', since:'18/07/2017', initial_date:'2017-07-18', cost_center:'OPERASIONAL', dept:'GA', category:'Mesin Kantor', initial_cost:'75.000', residual:'0', deprec_method:'Straight Line', useful_life:24, deprec_pct:'0,50', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'', accumulated_account:'AKUM PENYST MESIN KANTOR', depreciation_expense:'BIAYA PENYUSUTAN MESIN KANTOR', depr_start:'2017-08-17', depr_end:'2019-07-17', life_to_date:'0,00', year_to_date:'37.500,00', monthly:'3.125,00', journal_posted:0 },
                    { code:'001107', curr:'IDR', rate:'1', brand:'', name:'kursi', type:'Perabot', qty:0, cat:'010', serial:'', location:'HEAD OFFICE', since:'18/07/2017', initial_date:'2017-07-18', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Unsur Logam', initial_cost:'550.000', residual:'0', deprec_method:'Straight Line', useful_life:96, deprec_pct:'0,13', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'Kursi tamu lobby', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-17', depr_end:'2025-07-17', life_to_date:'0,00', year_to_date:'68.750,00', monthly:'5.729,17', journal_posted:0 },
                    { code:'001108', curr:'IDR', rate:'1', brand:'', name:'Kipas', type:'Mesin', qty:0, cat:'010', serial:'', location:'Head Office', since:'18/07/2017', initial_date:'2017-07-18', cost_center:'UMUM', dept:'GA', category:'Mesin Kantor', initial_cost:'320.000', residual:'0', deprec_method:'Straight Line', useful_life:48, deprec_pct:'0,25', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'Kipas angin ruang rapat', accumulated_account:'AKUM PENYST MESIN KANTOR', depreciation_expense:'BIAYA PENYUSUTAN MESIN KANTOR', depr_start:'2017-08-17', depr_end:'2021-07-17', life_to_date:'0,00', year_to_date:'80.000,00', monthly:'6.666,67', journal_posted:0 },
                    { code:'001109', curr:'IDR', rate:'1', brand:'', name:'Stage marawis', type:'Perabot', qty:0, cat:'009', serial:'', location:'HEAD OFFICE', since:'18/07/2017', initial_date:'2017-07-18', cost_center:'UMUM', dept:'HR', category:'Perabot Kantor Bukan Logam', initial_cost:'2.500.000', residual:'0', deprec_method:'Straight Line', useful_life:60, deprec_pct:'0,20', supplier:'', services_provider:'', asset_user:'Events Team', valid_guaranty:'', note:'Panggung kegiatan marawis', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-17', depr_end:'2022-07-17', life_to_date:'0,00', year_to_date:'500.000,00', monthly:'41.666,67', journal_posted:0 },
                    { code:'001110', curr:'IDR', rate:'1', brand:'', name:'Rekondisi penggantian mesin & sperpart Mobil', type:'Kendaraan', qty:0, cat:'005', serial:'', location:'HEAD OFFICE', since:'20/07/2017', initial_date:'2017-07-20', cost_center:'OPERASIONAL', dept:'GA', category:'Kendaraan Mobil', initial_cost:'8.500.000', residual:'0', deprec_method:'Declining', useful_life:60, deprec_pct:'0,40', supplier:'Bengkel Utama', services_provider:'Bengkel Utama', asset_user:'Driver Pool', valid_guaranty:'', note:'Rekondisi mesin kendaraan operasional', accumulated_account:'AKUM PENYST KENDARAAN MOBIL', depreciation_expense:'BIAYA PENYUSUTAN KENDARAAN', depr_start:'2017-08-20', depr_end:'2022-07-20', life_to_date:'0,00', year_to_date:'3.400.000,00', monthly:'283.333,33', journal_posted:12 },
                    { code:'001111', curr:'IDR', rate:'1', brand:'', name:'bendera ptk.bordr', type:'Perabot', qty:0, cat:'009', serial:'', location:'HEAD OFFICE', since:'28/07/2017', initial_date:'2017-07-28', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Bukan Logam', initial_cost:'75.000', residual:'0', deprec_method:'Straight Line', useful_life:24, deprec_pct:'0,50', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-28', depr_end:'2019-07-28', life_to_date:'0,00', year_to_date:'37.500,00', monthly:'3.125,00', journal_posted:0 },
                    { code:'001112', curr:'IDR', rate:'1', brand:'', name:'bendera merah putih', type:'Perabot', qty:0, cat:'009', serial:'', location:'HEAD OFFICE', since:'28/07/2017', initial_date:'2017-07-28', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Bukan Logam', initial_cost:'95.000', residual:'0', deprec_method:'Straight Line', useful_life:24, deprec_pct:'0,50', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-28', depr_end:'2019-07-28', life_to_date:'0,00', year_to_date:'47.500,00', monthly:'3.958,33', journal_posted:0 },
                    { code:'001113', curr:'IDR', rate:'1', brand:'', name:'garuda kayu', type:'Perabot', qty:0, cat:'009', serial:'', location:'HEAD OFFICE', since:'28/07/2017', initial_date:'2017-07-28', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Bukan Logam', initial_cost:'350.000', residual:'0', deprec_method:'Straight Line', useful_life:36, deprec_pct:'0,33', supplier:'Pengrajin Kayu', services_provider:'', asset_user:'', valid_guaranty:'', note:'Lambang garuda dinding lobby', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-28', depr_end:'2020-07-28', life_to_date:'0,00', year_to_date:'116.666,00', monthly:'9.722,22', journal_posted:0 },
                    { code:'001114', curr:'IDR', rate:'1', brand:'', name:'gbr presiden dan wakil', type:'Perabot', qty:0, cat:'009', serial:'', location:'HEAD OFFICE', since:'28/07/2017', initial_date:'2017-07-28', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Bukan Logam', initial_cost:'250.000', residual:'0', deprec_method:'Straight Line', useful_life:36, deprec_pct:'0,33', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'Foto resmi negara frame kayu', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-28', depr_end:'2020-07-28', life_to_date:'0,00', year_to_date:'83.333,00', monthly:'6.944,44', journal_posted:0 },
                    { code:'001115', curr:'IDR', rate:'1', brand:'', name:'tempat air gelas', type:'Perabot', qty:0, cat:'009', serial:'', location:'HEAD OFFICE', since:'28/07/2017', initial_date:'2017-07-28', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Bukan Logam', initial_cost:'45.000', residual:'0', deprec_method:'Straight Line', useful_life:24, deprec_pct:'0,50', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-28', depr_end:'2019-07-28', life_to_date:'0,00', year_to_date:'22.500,00', monthly:'1.875,00', journal_posted:0 },
                    { code:'001116', curr:'IDR', rate:'1', brand:'', name:'asbak besar', type:'Perabot', qty:0, cat:'009', serial:'', location:'HEAD OFFICE', since:'28/07/2017', initial_date:'2017-07-28', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Bukan Logam', initial_cost:'35.000', residual:'0', deprec_method:'Straight Line', useful_life:12, deprec_pct:'1,00', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-28', depr_end:'2018-07-28', life_to_date:'0,00', year_to_date:'35.000,00', monthly:'2.916,67', journal_posted:0 },
                    { code:'001117', curr:'IDR', rate:'1', brand:'', name:'dispenser', type:'Mesin', qty:0, cat:'008', serial:'', location:'HEAD OFFICE', since:'28/07/2017', initial_date:'2017-07-28', cost_center:'UMUM', dept:'GA', category:'Mesin Kantor', initial_cost:'380.000', residual:'0', deprec_method:'Straight Line', useful_life:48, deprec_pct:'0,25', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'Dispenser ruang kantor', accumulated_account:'AKUM PENYST MESIN KANTOR', depreciation_expense:'BIAYA PENYUSUTAN MESIN KANTOR', depr_start:'2017-08-28', depr_end:'2021-07-28', life_to_date:'0,00', year_to_date:'95.000,00', monthly:'7.916,67', journal_posted:0 },
                    { code:'001118', curr:'IDR', rate:'1', brand:'', name:'papan tulis', type:'Perabot', qty:0, cat:'009', serial:'', location:'HEAD OFFICE', since:'28/07/2017', initial_date:'2017-07-28', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Bukan Logam', initial_cost:'225.000', residual:'0', deprec_method:'Straight Line', useful_life:36, deprec_pct:'0,33', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'Papan whiteboard besar', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-28', depr_end:'2020-07-28', life_to_date:'0,00', year_to_date:'75.000,00', monthly:'6.250,00', journal_posted:0 },
                    { code:'001119', curr:'IDR', rate:'1', brand:'', name:'meja tamu', type:'Perabot', qty:0, cat:'009', serial:'', location:'HEAD OFFICE', since:'28/07/2017', initial_date:'2017-07-28', cost_center:'UMUM', dept:'GA', category:'Perabot Kantor Bukan Logam', initial_cost:'1.200.000', residual:'0', deprec_method:'Straight Line', useful_life:60, deprec_pct:'0,20', supplier:'', services_provider:'', asset_user:'', valid_guaranty:'', note:'Meja tamu lobby utama', accumulated_account:'AKUM PENYST PERABOT KTR UNSUR LOGAM', depreciation_expense:'BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM', depr_start:'2017-08-28', depr_end:'2022-07-28', life_to_date:'0,00', year_to_date:'240.000,00', monthly:'20.000,00', journal_posted:0 },
                    { code:'001120', curr:'IDR', rate:'1', brand:'ZKTeco', name:'mesin absensi face recognition', type:'Mesin', qty:0, cat:'008', serial:'ZK-FA-2017-001', location:'HEAD OFFICE', since:'01/08/2017', initial_date:'2017-08-01', cost_center:'ADMINISTRASI', dept:'IT', category:'Komputer (Hardware)', initial_cost:'4.500.000', residual:'0', deprec_method:'Straight Line', useful_life:48, deprec_pct:'0,25', supplier:'ZKTeco Indonesia', services_provider:'ZKTeco Indonesia', asset_user:'IT Dept', valid_guaranty:'2021-08-01', note:'Mesin absensi wajah karyawan', accumulated_account:'AKUM PENYST KOMPUTER (HARDWARE)', depreciation_expense:'BIAYA PENYUSUTAN KOMPUTER', depr_start:'2017-09-01', depr_end:'2021-08-01', life_to_date:'0,00', year_to_date:'1.125.000,00', monthly:'93.750,00', journal_posted:24 },
                ],

                init() {},

                saveCurrentChanges() {
                    // Alpine's x-model on records[currentIndex] already updates the object in the array,
                    // but we ensure any UI-only bindings are reflected if we were using a separate 'selectedRecord' object.
                    // Since we bind directly to records[currentIndex], 'saveCurrentChanges' is mostly a placeholder 
                    // for consistency with other modules, but we'll use it to ensure state integrity.
                },

                onDateChange(e) {
                    const val = e.target.value;
                    if (!val) return;
                    
                    // Search for record with matching initial_date
                    const foundIdx = this.records.findIndex(r => r.initial_date === val);
                    
                    if (foundIdx !== -1) {
                        this.saveCurrentChanges();
                        this.currentIndex = foundIdx;
                        this.activeTab = 'detail';
                    }
                },

                nextRecord() {
                    this.saveCurrentChanges();
                    if (this.currentIndex < this.records.length - 1) {
                        this.currentIndex++;
                        this.activeTab = 'detail';
                    }
                },

                prevRecord() {
                    this.saveCurrentChanges();
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                        this.activeTab = 'detail';
                    }
                },

                selectRecord(idx) {
                    this.saveCurrentChanges();
                    this.currentIndex = idx;
                    this.activeTab = 'detail';
                },

                handleRibbonAction(action) {
                    switch(action) {
                        case 'new': this.createNew(); break;
                        case 'save': 
                            this.saveCurrentChanges(); 
                            if(typeof exportToJSONFile === 'function') {
                                exportToJSONFile(this.records[this.currentIndex], 'FixedAsset_' + this.records[this.currentIndex].code + '.json');
                            }
                            showToast('Record saved to file folder', 'success'); 
                            break;
                        case 'delete': this.deleteRecord(); break;
                        case 'refresh': this.refreshData(); break;
                        case 'preview': window.print(); break;
                        case 'find': this.activeTab = 'list'; this.$nextTick(() => { if(typeof erpFindOpen === 'function') erpFindOpen(); }); break;
                        case 'undo': this.undoChanges(); break;
                        case 'save-as': 
                            this.saveAsNew(); 
                            if(typeof exportToJSONFile === 'function') {
                                exportToJSONFile(this.records[this.records.length-1], 'FixedAsset_' + this.records[this.records.length-1].code + '.json');
                            }
                            break;
                        case 'edit': this.focusFirstField(); break;
                        case 'barcode': showToast('Generating barcode...', 'info'); break;
                        case 'resend': showToast('Re-sending document...', 'info'); break;
                    }
                },

                undoChanges() {
                    if (confirm('Revert all unsaved changes for this record?')) {
                        // For simplicity in this mock, we reload or re-fetch (simulated)
                        showToast('Changes reverted', 'info');
                        this.refreshData();
                    }
                },

                saveAsNew() {
                    const clone = JSON.parse(JSON.stringify(this.records[this.currentIndex]));
                    clone.code = (Number(this.records[this.records.length-1]?.code || 0) + 1).toString().padStart(6, '0');
                    clone.name += ' (Copy)';
                    this.records.push(clone);
                    this.currentIndex = this.records.length - 1;
                    showToast('Record duplicated as ' + clone.code, 'success');
                },

                focusFirstField() {
                    this.activeTab = 'detail';
                    this.$nextTick(() => {
                        const firstInput = document.querySelector('.fa-pane.active input:not([readonly])');
                        if (firstInput) firstInput.focus();
                    });
                },

                createNew() {
                    const newCode = (Number(this.records[this.records.length-1]?.code || 0) + 1).toString().padStart(6, '0');
                    const newRec = {
                        code: newCode,
                        curr: 'IDR',
                        rate: '1',
                        brand: '',
                        name: 'New Asset',
                        type: '',
                        qty: 0,
                        cat: '',
                        serial: '',
                        location: '',
                        since: new Date().toLocaleDateString('id-ID'),
                        initial_date: new Date().toISOString().split('T')[0],
                        cost_center: '',
                        dept: '',
                        category: '',
                        initial_cost: '0',
                        residual: '0',
                        deprec_method: 'Straight Line',
                        useful_life: 0,
                        deprec_pct: '0',
                        supplier: '',
                        services_provider: '',
                        asset_user: '',
                        valid_guaranty: '',
                        note: '',
                        accumulated_account: '',
                        depreciation_expense: '',
                        depr_start: '',
                        depr_end: '',
                        life_to_date: '0',
                        year_to_date: '0',
                        monthly: '0',
                        journal_posted: 0
                    };
                    this.records.push(newRec);
                    this.currentIndex = this.records.length - 1;
                    this.activeTab = 'detail';
                    showToast('New asset record created', 'success');
                },

                deleteRecord() {
                    if (confirm('Are you sure you want to delete this asset?')) {
                        this.records.splice(this.currentIndex, 1);
                        if (this.currentIndex >= this.records.length) {
                            this.currentIndex = Math.max(0, this.records.length - 1);
                        }
                        showToast('Asset record deleted', 'success');
                    }
                },

                refreshData() {
                    showToast('Data refreshed', 'success');
                    // In a real app, this would fetch from server
                }
            }
        }
    </script>

    <!-- CATEGORY MODAL -->
    <div class="modal-overlay" id="categoryModal">
        <div class="custom-modal">
            <div class="modal-header">
                <div class="modal-title">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <rect x="3" y="3" width="7" height="7" fill="#f59e0b" />
                        <rect x="14" y="3" width="7" height="7" fill="#ef4444" />
                        <rect x="3" y="14" width="7" height="7" fill="#3b82f6" />
                    </svg>
                    Assets Category
                </div>
                <button class="modal-close" onclick="closeCategoryModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-tabs">
                    <div class="mod-tab active" data-modtab="detail">DETAIL CATEGORY</div>
                    <div class="mod-tab" data-modtab="list">LIST CATEGORY</div>
                </div>
                
                <div class="mod-content active" id="modtab-detail">
                    <div class="mod-form-group">
                        <label>Code</label>
                        <input type="text" class="form-control" value="002" style="max-width: 150px;">
                    </div>
                    <div class="mod-form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" value="Bangunan Gedung Kantor" style="max-width: 400px;">
                    </div>
                    
                    <div style="height: 20px;"></div>
                    
                    <div class="mod-form-group">
                        <label>Accumulated</label>
                        <select class="form-control" style="max-width: 400px;">
                            <option>AKUM PENYST BANG.GEDUNG KANTOR</option>
                        </select>
                    </div>
                    <div class="mod-form-group">
                        <label>Depreciation</label>
                        <select class="form-control" style="max-width: 400px;">
                            <option>BIAYA PENYUSUTAN GEDUNG</option>
                        </select>
                    </div>
                    
                    <div style="display: flex; justify-content: center; gap: 15px; margin-top: 30px;">
                        <button type="button" class="btn btn-primary" style="width: 100px;">Save</button>
                        <button type="button" class="btn btn-outline" style="width: 100px;" onclick="closeCategoryModal()">Cancel</button>
                    </div>
                </div>
                
                <div class="mod-content" id="modtab-list" style="padding:0;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">CODE</th>
                                    <th>CATEGORY</th>
                                    <th>ACCUMULATED ACCOUNT</th>
                                    <th>EXPENSES ACCOUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>000</td>
                                    <td>None Depreciable ...</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>001</td>
                                    <td>Standard Depreciation ...</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="active">
                                    <td>002</td>
                                    <td>Bangunan Gedung Kantor ...</td>
                                    <td>AKUM PENYST BANG.GEDUNG KANT...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>003</td>
                                    <td>Instalasi Listrik ...</td>
                                    <td>AKUM PENYST INSTALASI LISTRIK ...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>004</td>
                                    <td>Instalasi Telepon ...</td>
                                    <td>AKUM PENYST INSTALASI TELEPON ...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>005</td>
                                    <td>Kendaraan Mobil ...</td>
                                    <td>AKUM PENYST KENDARAAN MOBIL ...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>006</td>
                                    <td>Kendaraan Motor ...</td>
                                    <td>AKUM PENYST KENDARAAN MOTOR ...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>007</td>
                                    <td>Komputer (Hardware) ...</td>
                                    <td>AKUM PENYST KOMPUTER (HARDWA...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>008</td>
                                    <td>Mesin Kantor ...</td>
                                    <td>AKUM PENYST MESIN KANTOR ...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal logic
        function openCategoryModal() {
            document.getElementById('categoryModal').classList.add('show');
        }
        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.remove('show');
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            // Modal Tabs logic
            const modTabs = document.querySelectorAll('.mod-tab');
            const modContents = document.querySelectorAll('.mod-content');

            modTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    modTabs.forEach(t => t.classList.remove('active'));
                    modContents.forEach(c => c.classList.remove('active'));

                    tab.classList.add('active');
                    const target = document.getElementById('modtab-' + tab.dataset.modtab);
                    if (target) {
                        target.classList.add('active');
                    }
                });
            });
            
            // Close modal when clicking overlay background
            document.getElementById('categoryModal').addEventListener('click', function(e) {
                if(e.target === this) {
                    closeCategoryModal();
                }
            });

            // Modal Table Row Click Logic
            const tableRows = document.querySelectorAll('.data-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('click', function() {
                    // Remove active from all rows
                    tableRows.forEach(r => r.classList.remove('active'));
                    // Add active to clicked row
                    this.classList.add('active');

                    // Extract data from the row
                    const cells = this.querySelectorAll('td');
                    if (cells.length >= 4) {
                        const code = cells[0].textContent.trim();
                        // Remove the trailing '...' if present in description
                        let desc = cells[1].textContent.trim();
                        if(desc.endsWith('...')) desc = desc.slice(0, -3).trim();
                        
                        let acc = cells[2].textContent.trim();
                        let exp = cells[3].textContent.trim();

                        // Update the Detail Form in Modal
                        const modalCodeInput = document.querySelector('#modtab-detail .mod-form-group:nth-child(1) input');
                        const modalDescInput = document.querySelector('#modtab-detail .mod-form-group:nth-child(2) input');
                        const modalAccSelect = document.querySelector('#modtab-detail .mod-form-group:nth-child(4) select');
                        const modalExpSelect = document.querySelector('#modtab-detail .mod-form-group:nth-child(5) select');

                        if(modalCodeInput) modalCodeInput.value = code;
                        if(modalDescInput) modalDescInput.value = desc;
                        
                        // Update Select options text for visual effect (In real app, update by value)
                        if(modalAccSelect && modalAccSelect.options.length > 0) {
                            let textAcc = acc ? acc : 'AKUM PENYST BANG.GEDUNG KANTOR';
                            if(textAcc.endsWith('...')) textAcc = textAcc.slice(0, -3).trim();
                            modalAccSelect.options[0].text = textAcc;
                        }
                        
                        if(modalExpSelect && modalExpSelect.options.length > 0) {
                            let textExp = exp ? exp : 'BIAYA PENYUSUTAN GEDUNG';
                            if(textExp.endsWith('...')) textExp = textExp.slice(0, -3).trim();
                            modalExpSelect.options[0].text = textExp;
                        }
                        
                        // Automatically switch back to Detail Tab
                        document.querySelector('.mod-tab[data-modtab="detail"]').click();
                    }
                });
            });
            
            // Assign value to main form when Save is clicked
            const saveBtn = document.querySelector('#modtab-detail .btn-primary');
            if (saveBtn) {
                saveBtn.addEventListener('click', function() {
                    const selectedDesc = document.querySelector('#modtab-detail .mod-form-group:nth-child(2) input').value;
                    const modalAccSelect = document.querySelector('#modtab-detail .mod-form-group:nth-child(4) select');
                    const modalExpSelect = document.querySelector('#modtab-detail .mod-form-group:nth-child(5) select');

                    const mainCategoryInput = document.getElementById('main_category_input');
                    const mainAccSelect = document.getElementById('main_accumulated_account');
                    const mainExpSelect = document.getElementById('main_depreciation_expense');
                    
                    if(mainCategoryInput) {
                        mainCategoryInput.value = selectedDesc;
                    }

                    if(mainAccSelect && modalAccSelect && modalAccSelect.options.length > 0) {
                        mainAccSelect.innerHTML = `<option>${modalAccSelect.options[0].text}</option>`;
                    }

                    if(mainExpSelect && modalExpSelect && modalExpSelect.options.length > 0) {
                        mainExpSelect.innerHTML = `<option>${modalExpSelect.options[0].text}</option>`;
                    }

                    closeCategoryModal();
                });
            }


        });
    </script>
@endsection
