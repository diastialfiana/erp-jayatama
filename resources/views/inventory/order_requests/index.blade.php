@extends('layouts.app')

@section('title', 'Inventory Request')

@push('styles')
<style>
    :root {
        --hr-primary: #1e293b;
        --hr-border: #cbd5e1;
        --hr-accent: #2563eb;
    }

    .main-tabs { display: flex; gap: 2px; background: #e2e8f0; padding: 2px; border-radius: 8px 8px 0 0; width: fit-content; }
    .main-tab { padding: 6px 15px; font-size: 0.75rem; font-weight: normal; color: #334155; background: transparent; border: 1px solid transparent; cursor: pointer; border-radius: 0; text-transform: uppercase; }
    .main-tab.active { background: white; color: black; border: 1px solid var(--hr-border); border-bottom: none; font-weight: normal; }
    
    .tab-content { background: white; border-top: 1px solid var(--hr-border); min-height: 500px; padding: 0; display: flex; flex-direction: column; overflow: hidden; margin-top: -1px; }
    .tab-pane { display: none; flex: 1; flex-direction: column; height: 100%; min-height: calc(100vh - 150px); }
    .tab-pane.active { display: flex; }

    .bar-top { padding: 8px 10px; font-size: 0.75rem; color: #64748b; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; }
    .bar-search { padding: 4px 10px; display: flex; justify-content: flex-end; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; }

    /* Grid Styles */
    .list-grid { width: 100%; border-collapse: collapse; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; font-size: 0.75rem; }
    .list-grid th { background: white; color: #64748b; padding: 4px 6px; text-align: left; font-weight: normal; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); white-space: nowrap; text-transform: uppercase; font-size: 0.7rem; }
    .list-grid td { padding: 4px 6px; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); white-space: nowrap; color: #334155; }
    .list-grid tr:hover td { background: #f8fafc; cursor: pointer; }
    .list-grid tr.selected td { background: #e2e8f0; }
    
    .grid-footer { display: flex; align-items: center; padding: 4px; border-top: 1px solid var(--hr-border); background: #f8fafc; gap: 2px; flex-shrink: 0; }
    .pager-btn { background: white; border: 1px solid #cbd5e1; padding: 2px 6px; font-size: 0.65rem; cursor: pointer; color: #64748b; }
    .pager-btn:hover { background: #f1f5f9; }

    .detail-form-area { padding: 15px; background: white; border-bottom: 1px solid var(--hr-border); display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 4px; font-size: 0.75rem; }
    .form-label { width: 80px; text-align: right; margin-right: 10px; color: #475569; }
    .form-input { border: 1px solid var(--hr-border); padding: 4px 8px; border-radius: 0; font-size: 0.75rem; background: white; }
    .form-select { border: 1px solid var(--hr-border); padding: 3px 6px; border-radius: 0; font-size: 0.75rem; background: white; }

</style>
@endpush

@section('content')
<div x-data="orderRequestManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)" style="background: white; border: 1px solid var(--hr-border); margin: 10px;">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 10px; align-items: center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            <span style="font-weight: 500;">Inventory Request</span>
        </div>
        <div style="display: flex; gap: 15px;">
            <span style="cursor: pointer; font-size: 0.9rem;" @click="prevRecord()">◁</span>
            <span style="cursor: pointer; font-size: 0.9rem;" @click="nextRecord()">▷</span>
            <span style="cursor: pointer;">✕</span>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Main Navigation Tabs -->
    <div class="main-tabs" style="background: #f1f5f9; border-bottom: 1px solid var(--hr-border); padding-left: 10px; border-radius: 0;">
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">RECORD DETAIL</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">RECORDS LIST</button>
        <button class="main-tab" :class="activeMainTab === 'history' ? 'active' : ''" @click="activeMainTab = 'history'">HISTORY</button>
    </div>

    <div class="tab-content" style="border-top: none;">
        
        <!-- RECORDS LIST TAB -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''">
            <div class="bar-top">Drag a column header here to group by that column</div>
            <div class="bar-search">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #64748b;">
                    <circle cx="11" cy="11" r="8"></circle> <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <div style="flex: 1; overflow: auto;">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 20px;"></th>
                            <th style="width: 50px;">ACC</th>
                            <th style="width: 50px;">TYPE</th>
                            <th style="width: 100px;">REF</th>
                            <th style="width: 80px;">USERNO</th>
                            <th style="width: 90px;">DATE</th>
                            <th style="width: 60px; text-align: right;">TL.QTY</th>
                            <th style="width: 250px;">NOTE</th>
                            <th style="width: 80px; text-align: center;">PROCESS</th>
                            <th style="width: 100px;">USER ID</th>
                            <th style="width: 100px;">AUDIT</th>
                        </tr>
                    </thead>
                    <template x-for="item in requests" :key="item.id">
                        <tbody :class="selectedRequest && selectedRequest.id === item.id ? 'selected-group' : ''">
                            <tr @click="selectRequest(item.id)" :class="selectedRequest && selectedRequest.id === item.id ? 'selected' : ''" class="main-row">
                                <td style="text-align: center; font-size: 0.5rem; color: #475569;">
                                    <span x-show="selectedRequest && selectedRequest.id === item.id">▶</span>
                                </td>
                                <td style="display: flex; align-items: center; gap: 4px;">
                                    <button @click.stop="toggleExpand(item.id)" 
                                            style="width: 14px; height: 14px; border: 1px solid #cbd5e1; background: white; display: flex; align-items: center; justify-content: center; font-size: 11px; cursor: pointer; padding: 0; line-height: 1; color: #334155; flex-shrink: 0;">
                                        <span x-text="expandedIds.includes(item.id) ? '-' : '+'"></span>
                                    </button>
                                    <input type="checkbox" :checked="item.acc" disabled style="width:12px; height:12px; margin:0;">
                                </td>
                                <td x-text="item.type"></td>
                                <td style="color: #cbd5e1;">
                                    <span x-text="item.ref" style="display: inline-block;"></span>
                                    <span x-show="item.ref !== ''">...</span>
                                </td>
                                <td x-text="item.user_no" style="text-align: right; color:#cbd5e1;"></td>
                                <td x-text="formatDate(item.date)" style="color:#cbd5e1;"></td>
                                <td x-text="item.tl_qty" style="text-align: right; color:#cbd5e1;"></td>
                                <td>
                                    <span x-text="item.note"></span>
                                    <span style="color:#cbd5e1; float:right;">...</span>
                                </td>
                                <td style="text-align: center;">
                                    <input type="checkbox" :checked="item.process" disabled style="width:12px; height:12px; margin:0;">
                                </td>
                                <td x-text="item.user_id" style="color: #cbd5e1;"></td>
                                <td x-text="item.audit" style="color: #cbd5e1;"></td>
                            </tr>
                            <!-- EXPANDABLE ROW -->
                            <tr x-show="expandedIds.includes(item.id)" style="background: #f8fafc;">
                                <td colspan="11" style="padding: 10px 20px;">
                                    <div style="background: white; border: 1px solid var(--hr-border); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                                        <!-- Internal Tabs -->
                                        <div style="display: flex; gap: 2px; background: #e2e8f0; padding: 4px 4px 0 4px;">
                                            <button @click="activeSubTabs[item.id] = 'detail'" :class="(activeSubTabs[item.id] || 'detail') === 'detail' ? 'active' : ''" class="main-tab" style="padding: 5px 12px; font-size: 0.7rem;">REQUEST DETAIL</button>
                                            <button @click="activeSubTabs[item.id] = 'warehouse'" :class="activeSubTabs[item.id] === 'warehouse' ? 'active' : ''" class="main-tab" style="padding: 5px 12px; font-size: 0.7rem;">Warehouse Name</button>
                                        </div>
                                        <div style="border: 1px solid var(--hr-border); border-top: none; padding: 0;">
                                            <div x-show="(activeSubTabs[item.id] || 'detail') === 'detail'">
                                                <div style="background: #f1f5f9; padding: 4px 10px; border-bottom: 1px solid var(--hr-border); font-size: 0.65rem; color: #64748b;">
                                                    Drag a column header here to group by that column
                                                </div>
                                                <table class="list-grid" style="border: none;">
                                                    <thead>
                                                        <tr style="background: #f8fafc;">
                                                            <th style="width: 25px;"></th>
                                                            <th style="width: 40px; text-align: center;">HOLD</th>
                                                            <th style="width: 120px;">CODE</th>
                                                            <th style="width: 300px;">INVENTORY NAME</th>
                                                            <th style="width: 60px; text-align: right;">QTY</th>
                                                            <th style="width: 60px;">UNIT</th>
                                                            <th style="width: 60px; text-align: center;">OTW</th>
                                                            <th>DESCRIPTIONS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template x-for="(sub, sidx) in item.items" :key="sidx">
                                                            <tr style="background: white; border-bottom: 1px solid #e2e8f0;">
                                                                <td style="text-align: center; font-size: 0.5rem; color: #475569;">
                                                                    <span x-show="sidx === 0">▶</span>
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    <input type="checkbox" style="width: 12px; height: 12px;">
                                                                </td>
                                                                <td x-text="sub.code"></td>
                                                                <td x-text="sub.name"></td>
                                                                <td x-text="sub.qty" style="text-align: right;"></td>
                                                                <td x-text="sub.unit"></td>
                                                                <td style="text-align: center;">0</td>
                                                                <td>
                                                                    <span x-text="sub.description"></span>
                                                                    <span style="color:#cbd5e1; float:right;">...</span>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div x-show="activeSubTabs[item.id] === 'warehouse'">
                                                <div style="padding: 20px; text-align: center; color: #64748b; font-size: 0.75rem;">No warehouse info available.</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </table>
            </div>
            <div class="grid-footer" style="background: transparent; border-top: 1px solid var(--hr-border); min-height: 28px;"></div>
        </div>

        <!-- RECORD DETAIL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''">
            <div class="detail-form-area" x-show="selectedRequest">
                <div>
                    <div class="form-group">
                        <div class="form-label">Date</div>
                        <div style="display: flex; align-items: center;">
                            <input type="date" class="form-input" style="width: 110px;" x-model="selectedRequest.date" x-ref="dateInput" @change="onDateChange($event)">
                            <span style="border: 1px solid var(--hr-border); background:#f1f5f9; padding: 4px 6px; border-left:none; cursor: pointer;" @click="$refs.dateInput.showPicker()">▼</span>
                        </div>
                        
                        <div class="form-label" style="width: 50px;">Reff.</div>
                        <input type="text" class="form-input" style="background:#f8fafc; width: 150px;" x-model="selectedRequest.ref" readonly>
                    </div>
                    <div class="form-group">
                        <div class="form-label">Status</div>
                        <select class="form-select" style="width: 120px;" x-model="selectedRequest.status">
                            <option>Normal</option>
                            <option>Urgent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="form-label">Warehouse</div>
                        <select class="form-select" style="width: 220px;" x-model="selectedRequest.warehouse">
                            <option>HEAD OFFICE - CHEMICAL</option>
                            <option>HEAD OFFICE - ATK</option>
                            <option>LOGISTIK</option>
                        </select>
                    </div>
                </div>
                <!-- Right Side Info -->
                <div style="display: flex; gap: 80px; padding-right: 30px; padding-top: 10px; font-size: 0.8rem; color: #475569;">
                    <div x-text="selectedRequest.user_no"></div>
                    <div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div style="flex: 1; overflow: auto;" x-show="selectedRequest">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 25px;"></th>
                            <th style="width: 120px;">CODE</th>
                            <th style="width: 250px;">INVENTORY NAME</th>
                            <th style="width: 60px; text-align: right;">QTY</th>
                            <th style="width: 60px;">UNIT</th>
                            <th style="width: 250px;">DESCRIPTION</th>
                            <th style="width: 80px; text-align: right;">RECEIVE</th>
                            <th style="width: 60px; text-align: center;">CANCEL</th>
                            <th style="width: 80px; text-align: center;">RE-LOAD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, idx) in selectedRequest.items" :key="idx">
                            <tr>
                                <td style="text-align: center; font-size: 0.5rem; color: #475569;">
                                    <span x-show="idx === 0">▶</span>
                                </td>
                                <td x-text="item.code"></td>
                                <td style="color:#64748b;">
                                    <span x-text="item.name"></span>
                                    <span style="color:#cbd5e1; float:right;">...</span>
                                </td>
                                <td x-text="item.qty" style="text-align: right;"></td>
                                <td x-text="item.unit"></td>
                                <td>
                                    <span x-text="item.description"></span>
                                </td>
                                <td x-text="item.receive" style="text-align: right; color:#cbd5e1;"></td>
                                <td style="text-align: center;">
                                    <input type="checkbox" :checked="item.cancel" disabled style="width:12px; height:12px; margin:0;">
                                </td>
                                <td style="text-align: center; color: #64748b;">
                                    <!-- Square reload inner item -->
                                    <div style="border: 1px solid var(--hr-border); width: 14px; height: 14px; margin: 0 auto; display: flex; align-items: center; justify-content: center; background: white;">
                                        <div style="width: 6px; height: 6px; background: #94a3b8;" x-show="!item.reload"></div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Detail Footer -->
            <div class="grid-footer" style="padding: 10px; background: #f8fafc; justify-content: flex-start; gap: 20px; border-bottom: 1px solid var(--hr-border);">
                <!-- Space corresponding to code name to align with QTY -->
                <div style="width: 440px; text-align: right;">
                    <input type="text" class="form-input" style="width: 60px; text-align: right; background: white;" :value="selectedRequest ? selectedRequest.tl_qty : ''" readonly>
                </div>
            </div>
            <div class="grid-footer" style="padding: 4px; background: white;">
                <button class="pager-btn" @click="firstRecord()" :disabled="currentIndex <= 0">|◀</button>
                <button class="pager-btn" @click="firstRecord()" :disabled="currentIndex <= 0">◀◀</button>
                <button class="pager-btn" @click="prevRecord()" :disabled="currentIndex <= 0">◀</button>
                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIndex + 1"></span> of <span x-text="requests.length"></span></span>
                <button class="pager-btn" @click="nextRecord()" :disabled="currentIndex >= requests.length - 1">▶</button>
                <button class="pager-btn" @click="lastRecord()" :disabled="currentIndex >= requests.length - 1">▶▶</button>
                <button class="pager-btn" @click="lastRecord()" :disabled="currentIndex >= requests.length - 1">▶|</button>
            </div>
        </div>

        <!-- HISTORY TAB -->
        <div class="tab-pane" :class="activeMainTab === 'history' ? 'active' : ''">
            <div class="bar-top">Drag a column header here to group by that column</div>
            <div class="bar-search">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #64748b;">
                    <circle cx="11" cy="11" r="8"></circle> <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <div style="flex: 1; overflow: auto;">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 25px;"></th>
                            <th style="width: 40px;">ACC</th>
                            <th style="width: 50px;">TYPE</th>
                            <th style="width: 100px;">REF</th>
                            <th style="width: 80px;">USERNO</th>
                            <th style="width: 90px;">DATE</th>
                            <th style="width: 60px; text-align: right;">TL.QTY</th>
                            <th style="width: 240px;">NOTE</th>
                            <th style="width: 90px; text-align: center;">PROCESS</th>
                            <th style="width: 100px;">AUDIT</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Empty in screenshot -->
                    </tbody>
                </table>
            </div>
            <div class="grid-footer" style="background: transparent; border-top: 1px solid var(--hr-border); min-height: 28px;"></div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
    function orderRequestManager() {
        return {
            activeMainTab: 'list', 
            requests: @json($orderRequests),
            selectedRequest: null,

            expandedIds: [],
            activeSubTabs: {},

            get currentIndex() {
                if (!this.selectedRequest) return -1;
                return this.requests.findIndex(r => r.id === this.selectedRequest.id);
            },

            init() {
                if (this.requests && this.requests.length > 0) {
                    this.selectRequest(this.requests[0].id);
                }
            },

            selectRequest(id) {
                const req = this.requests.find(r => r.id === id);
                if (req) {
                    this.selectedRequest = { ...req };
                }
            },

            // Date formatting helper for display
            formatDate(dateStr) {
                if (!dateStr) return '';
                const parts = dateStr.split('-');
                if (parts.length !== 3) return dateStr;
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            },

            saveCurrentChanges() {
                if (this.selectedRequest) {
                    const idx = this.requests.findIndex(r => r.id === this.selectedRequest.id);
                    if (idx !== -1) {
                        this.requests[idx] = { ...this.selectedRequest };
                    }
                }
            },

            onDateChange(e) {
                const val = e.target.value;
                if (!val) return;
                
                const newDateDMY = val.split('-').reverse().join('/');
                const foundIdx = this.requests.findIndex(r => r.date === val || r.date === newDateDMY);
                
                if (foundIdx !== -1) {
                    this.saveCurrentChanges();
                    const found = this.requests[foundIdx];
                    this.selectRequest(found.id);
                } else {
                    this.selectedRequest.date = val;
                    this.saveCurrentChanges();
                }
            },

            toggleExpand(id) {
                if (this.expandedIds.includes(id)) {
                    this.expandedIds = this.expandedIds.filter(i => i !== id);
                } else {
                    this.expandedIds = [...this.expandedIds, id];
                    if (!this.activeSubTabs[id]) {
                        this.activeSubTabs = { ...this.activeSubTabs, [id]: 'detail' };
                    }
                }
            },

            // Navigation methods
            firstRecord() { this.saveCurrentChanges(); if (this.requests.length > 0) this.selectRequest(this.requests[0].id); },
            prevRecord() {
                this.saveCurrentChanges();
                const idx = this.currentIndex;
                if (idx > 0) this.selectRequest(this.requests[idx - 1].id);
            },
            nextRecord() {
                this.saveCurrentChanges();
                const idx = this.currentIndex;
                if (idx < this.requests.length - 1) this.selectRequest(this.requests[idx + 1].id);
            },
            lastRecord() { this.saveCurrentChanges(); if (this.requests.length > 0) this.selectRequest(this.requests[this.requests.length - 1].id); },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.createNew(); break;
                    case 'save': 
                        this.saveCurrentChanges(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.selectedRequest, 'InventoryRequest_' + (this.selectedRequest?.doc_no?.replace(/\//g, '-') || 'Draft') + '.json');
                        }
                        showToast('Request saved to file', 'success'); 
                        break;
                    case 'delete': this.deleteRecord(); break;
                    case 'refresh': window.location.reload(); break;
                    case 'preview': window.print(); break;
                    case 'find': this.activeMainTab = 'list'; this.$nextTick(() => { if(typeof erpFindOpen === 'function') erpFindOpen(); }); break;
                    case 'undo': this.undoChanges(); break;
                    case 'save-as': 
                        this.saveAsNew(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.selectedRequest, 'InventoryRequest_Copy.json');
                        }
                        break;
                    case 'edit': this.focusFirstField(); break;
                    case 'barcode': showToast('Generating barcode...', 'info'); break;
                    case 'resend': showToast('Re-sending document...', 'info'); break;
                }
            },

            undoChanges() {
                if (this.selectedRequest && confirm('Revert all unsaved changes for this request?')) {
                    this.selectRequest(this.selectedRequest.id);
                    showToast('Changes reverted', 'info');
                }
            },

            saveAsNew() {
                if (!this.selectedRequest) return;
                const clone = JSON.parse(JSON.stringify(this.selectedRequest));
                clone.id = this.requests.length + 1;
                clone.doc_no += ' (COPY)';
                this.requests.push(clone);
                this.selectRequest(clone.id);
                showToast('Request duplicated', 'success');
            },

            focusFirstField() {
                this.activeMainTab = 'detail';
                this.$nextTick(() => {
                    const firstInput = document.querySelector('.tab-pane.active input:not([readonly])');
                    if (firstInput) firstInput.focus();
                });
            },

            createNew() {
                const newId = this.requests.length + 1;
                const newReq = {
                    id: newId,
                    date: new Date().toISOString().split('T')[0],
                    doc_no: `INV/${new Date().getFullYear()}/${String(newId).padStart(4, '0')}`,
                    division: 'GENERAL',
                    status: 'DRAFT',
                    requester: 'SYSTEM',
                    items_count: 0,
                    total_amount: 0,
                    note: '',
                    items: []
                };
                this.requests.push(newReq);
                this.selectRequest(newId);
                this.activeMainTab = 'detail';
                showToast('New inventory request created', 'success');
            },

            deleteRecord() {
                if (!this.selectedRequest) return;
                if (confirm('Are you sure you want to delete this inventory request?')) {
                    const idx = this.requests.findIndex(r => r.id === this.selectedRequest.id);
                    if (idx !== -1) {
                        this.requests.splice(idx, 1);
                        if (this.requests.length > 0) {
                            const newIdx = Math.min(idx, this.requests.length - 1);
                            this.selectRequest(this.requests[newIdx].id);
                        } else {
                            this.selectedRequest = null;
                        }
                    }
                    showToast('Request deleted', 'success');
                }
            },

            prevRecord() {
                if (!this.requests.length) return;
                const idx = this.requests.findIndex(r => r.id === this.selectedRequest?.id);
                if (idx > 0) this.selectRequest(this.requests[idx - 1].id);
            },

            nextRecord() {
                if (!this.requests.length) return;
                const idx = this.requests.findIndex(r => r.id === this.selectedRequest?.id);
                if (idx < this.requests.length - 1) this.selectRequest(this.requests[idx + 1].id);
            },

            saveCurrentChanges() {
                showToast('Request saved', 'success');
            }
        }
    }
</script>
@endpush
