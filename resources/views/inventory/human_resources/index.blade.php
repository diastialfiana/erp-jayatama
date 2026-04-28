@extends('layouts.app')

@section('title', 'Human Resources')

@push('styles')
    <style>
        :root { --hr-border: #999; --hr-primary: #1e293b; --hr-accent: #2563eb; }
        .fa-window { 
            background: #f0f0f0; 
            border: 1px solid #999; 
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 120px);
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
            color: #000;
        }
        
        .window-title-bar {
            background: linear-gradient(to bottom, #4f78b1, #3a5a8f);
            color: white;
            padding: 4px 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            font-weight: bold;
        }

        .fa-tabs {
            display: flex;
            background: #f0f0f0;
            padding: 4px 4px 0 4px;
            border-bottom: 1px solid #999;
        }
        .fa-tab {
            padding: 4px 12px;
            font-size: 11px;
            border: 1px solid #999;
            border-bottom: none;
            background: #e0e0e0;
            cursor: pointer;
            margin-right: 2px;
            border-radius: 3px 3px 0 0;
            text-transform: uppercase;
        }
        .fa-tab.active {
            background: #fff;
            font-weight: bold;
            margin-bottom: -1px;
            height: calc(100% + 1px);
        }

        /* Tab panes — note: HR uses .tab-pane not .fa-pane */
        .tab-pane { display: none; flex: 1; flex-direction: column; overflow: auto; background: #f0f0f0; }
        .tab-pane.active { display: flex; }
        .fa-pane { display:none; flex:1; overflow:auto; background: #f0f0f0; }
        .fa-pane.active { display:flex; flex-direction:column; }

        /* ── RECORD DETAIL LAYOUT ── */
        .detail-layout {
            display: flex;
            gap: 15px;
            padding: 12px;
            flex: 1;
            overflow: auto;
            background: #f0f0f0;
        }
        .detail-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .detail-right {
            width: 340px;
            display: flex;
            flex-direction: column;
            border: 1px solid #999;
            background: white;
        }

        /* ── HR FORM GRID ── */
        .hr-form-grid {
            display: grid;
            grid-template-columns: 110px 1fr;
            gap: 3px 0;
            align-items: center;
        }
        .hr-label {
            font-size: 11px;
            color: #444;
            text-align: right;
            padding-right: 8px;
            white-space: nowrap;
        }
        .hr-input {
            height: 22px;
            border: 1px solid #999;
            border-radius: 0;
            font-size: 12px;
            padding: 2px 4px;
            background: white;
            width: 100%;
            box-sizing: border-box;
        }
        .hr-input:focus { outline: 1px solid #4f78b1; }
        .hr-input[readonly] { background: #e0e0e0; }

        /* ── AVATAR BOX ── */
        .avatar-box { 
            width: 120px; height: 150px; border: 1px solid #999; background: #fff; 
            display: flex; align-items: center; justify-content: center; font-size: 11px; color: #666;
            cursor: pointer; flex-shrink: 0;
        }
        .avatar-img { width: 60px; height: 60px; color: #bbb; }

        /* ── RADIO CLOTHES SIZE ── */
        .clothes-size-group { display: flex; gap: 8px; flex-wrap: wrap; }
        .radio-input { display: flex; align-items: center; gap: 3px; font-size: 11px; cursor: pointer; }

        /* ── SUB TABS (right panel) ── */
        .sub-tabs { display: flex; background: #e0e0e0; border-bottom: 1px solid #999; }
        .sub-tab { padding: 4px 10px; font-size: 10px; border-right: 1px solid #999; cursor: pointer; background: #d0d0d0; border: none; }
        .sub-tab.active { background: #fff; font-weight: bold; }
        .sub-content { flex: 1; overflow: auto; }

        /* ── SUB TABLE ── */
        .sub-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .sub-table th { background: #e0e0e0; border: 1px solid #999; padding: 3px 5px; text-align: left; font-size: 10px; }
        .sub-table td { border: 1px solid #ccc; padding: 3px 5px; }
        .sub-table tr:hover td { background: #f0f0f0; }
        .checkbox-cell { text-align: center; width: 40px; }

        /* ── DETAIL FOOTER ── */
        .detail-footer { 
            padding: 5px 8px; background: #f0f0f0; border-top: 1px solid #999; 
            display: flex; justify-content: space-between; align-items: center;
            font-size: 11px; color: #444; margin-top: auto;
        }

        /* ── RECORD LIST ── */
        :root { --hr-border: #999; }
        .list-grid { width: 100%; border-collapse: collapse; font-size: 11px; background: white; }
        .list-grid th { background: #e0e0e0; color: #333; padding: 3px 5px; text-align: left; font-weight: bold; border: 1px solid #999; white-space: nowrap; font-size: 11px; position: sticky; top: 0; }
        .list-grid td { padding: 3px 5px; border: 1px solid #ccc; white-space: nowrap; color: #000; font-size: 11px; }
        .list-grid tr:hover td { background: #f0f0f0; cursor: pointer; }
        .list-grid tr.selected td { background: #3a5a8f; color: white; }
        .grid-cell-flex { display: flex; justify-content: space-between; align-items: center; }
        .ellipsis { color: #bbb; font-size: 10px; }

        /* ── PAGER ── */
        .pager-footer { background: #f0f0f0; border-top: 1px solid #999; padding: 4px 8px; display: flex; align-items: center; gap: 4px; font-size: 11px; margin-top: auto; }
        .pager-btn { padding: 2px 6px; border: 1px solid #999; background: #fff; cursor: pointer; min-width: 25px; font-size: 11px; }
        .pager-btn:hover { background: #eee; }
        .pager-btn:disabled { opacity: 0.5; cursor: not-allowed; }

        /* ── LOADING OVERLAY ── */
        .loading-overlay {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.7);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; color: #444; z-index: 100;
        }

        /* ── MISC ── */
        .main-content { padding: 15px; background: #f0f0f0; }
        .box-panel { border: 1px solid #999; background: #fff; margin-bottom: 10px; display: flex; flex-direction: column; flex: 1; }
        .panel-header { background: #e0e0e0; padding: 4px 8px; font-size: 11px; font-weight: bold; border-bottom: 1px solid #999; }
        .panel-body { padding: 0; flex: 1; overflow-y: auto; }
    </style>
@endpush

@section('content')
<div class="fa-window" x-data="hrManager()" x-init="init()">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 10px; align-items: center;">
            <div style="width: 28px; height: 28px; background: #eff6ff; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <span style="font-weight: 700; font-size: 13px; color: #fff;">Human Resources Management</span>
        </div>
        <div style="display: flex; gap: 8px; align-items:center;">
            <div style="display:flex; gap:2px; margin-right: 10px;">
                <button class="pager-btn" @click="prevRecord()">◁</button>
                <button class="pager-btn" @click="nextRecord()">▷</button>
            </div>
            <button class="hamburger" style="padding: 4px; color: #fff;"><svg viewBox="0 0 24 24" style="width:16px; height:16px;"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"/></svg></button>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <div class="fa-tabs">
        <div class="fa-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">RECORD DETAIL</div>
        <div class="fa-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">RECORD LIST</div>
    </div>
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
                                <tr @click="selectAndDetail({{ $emp->id }})" :class="form.id === {{ $emp->id }} ? 'selected' : ''">
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
                pob: '', // Added Place of Birth
                dob: '', // Added Date of Birth
                gender: 'Male',
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

                // Immediately populate form from local data for instant feedback
                const emp = this.employees.find(e => e.id === id);
                if (emp) this.form = { ...this.form, ...emp };

                // Switch to detail tab right away
                this.activeMainTab = 'detail';

                try {
                    const response = await fetch(`/inventory/human-resources/${id}/details`);
                    if (!response.ok) throw new Error('Failed to fetch details');
                    const data = await response.json();
                    // Merge with richer data from API
                    this.form = { ...this.form, ...data };
                } catch (error) {
                    console.error('Could not fetch full details, using local data:', error);
                } finally {
                    this.loading = false;
                }
            },

            // Navigation
            selectAndDetail(id) {
                this.selectEmployee(id);
                this.activeMainTab = 'detail';
            },
            goFirst() {
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
            goLast() {
                if (this.employees.length > 0) this.selectEmployee(this.employees[this.employees.length - 1].id);
            },
            firstRecord() { this.goFirst(); },
            lastRecord() { this.goLast(); },

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
                    pob: '', dob: '', gender: 'Male',
                    files: [], attributes: []
                };
            },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.addRecord(); break;
                    case 'save': showToast('Employee record saved', 'success'); break;
                    case 'delete': this.removeRecord(); break;
                    case 'edit': this.focusFirstField(); break;
                    case 'refresh': window.location.reload(); break;
                    case 'preview': window.print(); break;
                    case 'find': this.activeMainTab = 'list'; break;
                }
            },

            focusFirstField() {
                this.activeMainTab = 'detail';
                this.$nextTick(() => {
                    const firstInput = document.querySelector('.fa-pane.active input:not([readonly])');
                    if (firstInput) firstInput.focus();
                });
            }
        }
    }
</script>
@endpush
