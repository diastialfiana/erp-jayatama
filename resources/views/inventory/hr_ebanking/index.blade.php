@extends('layouts.app')

@section('title', 'HR. e-Banking')

@push('styles')
<style>
    :root {
        --hr-border: #999;
    }

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

    .fa-tabs { display: flex; background: #f0f0f0; padding: 4px 4px 0 4px; border-bottom: 1px solid #999; }
    .fa-tab { padding: 4px 12px; font-size: 12px; border: 1px solid #999; border-bottom: none; background: #e0e0e0; cursor: pointer; margin-right: 2px; border-radius: 3px 3px 0 0; text-transform: uppercase; }
    .fa-tab.active { background: #fff; font-weight: bold; margin-bottom: -1px; height: calc(100% + 1px); }

    .fa-pane { display:none; flex:1; overflow:auto; background: #f0f0f0; }
    .fa-pane.active { display:flex; flex-direction:column; }

    /* Grid Styles */
    .list-grid { width: 100%; border-collapse: collapse; font-size: 11px; background: white; }
    .list-grid th { background: #e0e0e0; color: #333; padding: 3px 5px; text-align: left; font-weight: bold; border: 1px solid #999; white-space: nowrap; font-size: 11px; position: sticky; top: 0; }
    .list-grid td { padding: 3px 5px; border: 1px solid #ccc; white-space: nowrap; color: #000; font-size: 11px; }
    .list-grid tr:hover td { background: #f0f0f0; cursor: pointer; }
    .list-grid tr.selected td { background: #3a5a8f; color: white; }
    
    .grid-footer { display: flex; align-items: center; padding: 3px; border-top: 1px solid #999; background: #f0f0f0; gap: 2px; flex-shrink: 0; }
    .pager-btn { background: white; border: 1px solid #999; padding: 2px 6px; font-size: 11px; cursor: pointer; color: #333; min-width: 25px; }
    .pager-btn:hover { background: #e0e0e0; }

    /* Form Area for DETAIL tab */
    .detail-form-area { padding: 8px 12px; background: #f0f0f0; border-bottom: 1px solid #999; display: flex; gap: 40px; flex-shrink: 0; }
    .form-group { display: flex !important; flex-direction: row !important; align-items: center !important; margin-bottom: 3px !important; font-size: 11px; }
    .form-label { width: 110px; text-align: right; margin-right: 8px; font-size: 11px; color: #333; flex-shrink: 0; }
    .form-input { border: 1px solid #999; padding: 2px 4px; border-radius: 0; font-size: 12px; background: white; height: 22px; box-sizing: border-box; }
    .form-input[readonly] { background: #e0e0e0; }
    .form-select { border: 1px solid #999; padding: 2px 4px; border-radius: 0; font-size: 12px; background: white; height: 22px; }
    .main-tabs { display: flex; background: #f0f0f0; padding: 4px 4px 0 4px; border-bottom: 1px solid #999; }
    .main-tab { padding: 4px 12px; font-size: 12px; border: 1px solid #999; border-bottom: none; background: #e0e0e0; cursor: pointer; margin-right: 2px; border-radius: 3px 3px 0 0; text-transform: uppercase; }
    .main-tab.active { background: #fff; font-weight: bold; margin-bottom: -1px; height: calc(100% + 1px); }
    .tab-content { display: flex; flex-direction: column; flex: 1; overflow: hidden; }
    .tab-pane { display: none; flex: 1; flex-direction: column; overflow: auto; background: #f0f0f0; }
    .tab-pane.active { display: flex; }
</style>
@endpush

@section('content')
<div class="fa-window" x-data="hrEbankingManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 8px; align-items: center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>HR. e-Banking</span>
        </div>
        <div style="display: flex; gap: 4px;">
            <div style="width:14px;height:14px;background:#ddd;border:1px solid #999;cursor:pointer;"></div>
            <div style="width:14px;height:14px;background:#ddd;border:1px solid #999;cursor:pointer;"></div>
            <div style="width:14px;height:14px;background:#e81123;border:1px solid #999;cursor:pointer;"></div>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Main Navigation Tabs -->
    <div class="main-tabs">
        <div class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">RECORD DETAIL</div>
        <div class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">RECORDS LIST</div>
    </div>

    <div class="tab-content" style="flex: 1;">
        
        <!-- DETAIL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''">
            <div class="detail-form-area" x-show="selectedRecord">
                <div style="flex: 1; display: flex; flex-direction: column; gap: 3px;">
                    <div class="form-group">
                        <div class="form-label">Date</div>
                        <div style="display:flex;">
                            <input type="date" x-ref="datePickerDetail" x-model="selectedRecord.date" @change="onDateChange($event)"
                                   style="position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none;">
                            <input type="text" class="form-input" style="width: 100px; border:none; text-align:right; cursor:pointer;" 
                                   :value="selectedRecord.date" @click="$refs.datePickerDetail.showPicker()" readonly>
                            <span style="background: #e0e0e0; padding: 4px 6px; border-left: 1px solid var(--hr-border); color: #333; cursor:pointer;" @click="$refs.datePickerDetail.showPicker()">▼</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label">Select Data</div>
                        <div style="display:flex; align-items:center; gap: 15px; font-size: 0.75rem; color:#334155;">
                            <label style="display:flex; align-items:center; gap: 5px; cursor:pointer;">
                                <input type="radio" value="Operational" x-model="selectedRecord.select_data" name="select_data" @change="saveCurrentChanges()"> Operational
                            </label>
                            <label style="display:flex; align-items:center; gap: 5px; cursor:pointer;">
                                <input type="radio" value="Non Operational" x-model="selectedRecord.select_data" name="select_data" @change="saveCurrentChanges()"> Non Operational
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label">Invoice No</div>
                        <div style="display: flex; align-items: center; background: white; border: 1px solid var(--hr-border); width: 140px; height: 22px;">
                            <input type="text" style="flex: 1; border: none; font-size: 0.75rem; padding: 2px 4px; outline: none; height: 18px;" 
                                   x-model="selectedRecord.invoice_no" list="invoice-list" placeholder="Invoice..." @change="saveCurrentChanges()">
                            <datalist id="invoice-list">
                                <option value="INV-2026-001"></option>
                                <option value="INV-2026-002"></option>
                            </datalist>
                            <span style="border:none; border-left:1px solid var(--hr-border); background:#e0e0e0; cursor:pointer; padding: 2px 4px;">▼</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label">Account No.</div>
                        <input type="text" class="form-input" style="width: 150px;" x-model="selectedRecord.account_no" readonly>
                    </div>

                    <div class="form-group">
                        <div class="form-label">Account Name</div>
                        <input type="text" class="form-input" style="width: 250px;" x-model="selectedRecord.account_name" readonly>
                    </div>

                    <div class="form-group">
                        <div class="form-label">Bank Name</div>
                        <input type="text" class="form-input" style="width: 150px;" x-model="selectedRecord.bank_name" readonly>
                    </div>

                    <div class="form-group">
                        <div class="form-label">Total</div>
                        <div style="display:flex; border: 1px solid var(--hr-border); background:white;">
                            <input type="text" class="form-input" style="width: 120px; border:none; text-align:right;" x-model="selectedRecord.total">
                            <div style="display:flex; flex-direction:column; border-left:1px solid var(--hr-border); background:#e0e0e0;">
                                <span style="font-size:0.4rem; padding: 1px 4px; border-bottom:1px solid var(--hr-border); cursor:pointer;" @click="updateTotal(1)">▲</span>
                                <span style="font-size:0.4rem; padding: 1px 4px; cursor:pointer;" @click="updateTotal(-1)">▼</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LIST ALL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''" style="background: white;">
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
                            <th style="width: 80px;">DATE</th>
                            <th style="width: 60px;">TYPE</th>
                            <th style="width: 100px;">DATA REF</th>
                            <th style="width: 80px; text-align:right;">USER NO</th>
                            <th style="width: 150px;">ACCOUNT NO.</th>
                            <th style="width: 150px;">ACCOUNT NAME</th>
                            <th style="width: 150px;">BANK NAME</th>
                            <th style="width: 120px; text-align: right;">TOTAL</th>
                            <th style="width: 100px;">AUDIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, idx) in records" :key="item.id">
                            <tr @click="selectAndDetail(item.id, idx)" :class="selectedRecord && selectedRecord.id === item.id ? 'selected' : ''">
                                <td style="text-align: center; font-size: 0.5rem; color: #475569;">
                                    <span x-show="selectedRecord && selectedRecord.id === item.id">▶</span>
                                </td>
                                <td x-text="item.date"></td>
                                <td x-text="item.type"></td>
                                <td x-text="item.data_ref"></td>
                                <td x-text="item.user_no" style="text-align: right;"></td>
                                <td x-text="item.account_no"></td>
                                <td x-text="item.account_name"></td>
                                <td x-text="item.bank_name"></td>
                                <td x-text="item.total" style="text-align: right;"></td>
                                <td x-text="item.audit"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="grid-footer" style="padding: 4px; background: #e2e8f0; border-top: 1px solid var(--hr-border);">
                <button class="pager-btn" @click="firstRecord()" :disabled="currentIdx <= 0">|◀</button>
                <button class="pager-btn" @click="firstRecord()" :disabled="currentIdx <= 0">◀◀</button>
                <button class="pager-btn" @click="prevRecord()" :disabled="currentIdx <= 0">◀</button>
                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIdx + 1"></span> of <span x-text="records.length"></span></span>
                <button class="pager-btn" @click="nextRecord()" :disabled="currentIdx >= records.length - 1">▶</button>
                <button class="pager-btn" @click="lastRecord()" :disabled="currentIdx >= records.length - 1">▶▶</button>
                <button class="pager-btn" @click="lastRecord()" :disabled="currentIdx >= records.length - 1">▶|</button>
            </div>
        </div>
        
    </div>

</div>
@endsection

@push('scripts')
<script>
    function hrEbankingManager() {
        return {
            activeMainTab: 'detail',
            records: @json($records),
            selectedRecord: null,
            currentIdx: 0,

            init() {
                if (this.records && this.records.length > 0) {
                    this.selectRecord(this.records[0].id, 0);
                }
            },

            selectRecord(id, idx) {
                this.selectedRecord = { ...this.records.find(r => r.id === id) };
                this.currentIdx = idx;
            },

            selectAndDetail(id, idx) {
                this.selectRecord(id, idx);
                this.activeMainTab = 'detail';
            },

            saveCurrentChanges() {
                if (this.selectedRecord) {
                    const idx = this.records.findIndex(r => r.id === this.selectedRecord.id);
                    if (idx !== -1) {
                        this.records[idx] = { ...this.selectedRecord };
                    }
                }
            },

            firstRecord() {
                if (this.records.length > 0) {
                    this.selectRecord(this.records[0].id, 0);
                }
            },
            prevRecord() {
                if (this.currentIdx > 0) {
                    const idx = this.currentIdx - 1;
                    this.selectRecord(this.records[idx].id, idx);
                }
            },
            nextRecord() {
                if (this.currentIdx < this.records.length - 1) {
                    const idx = this.currentIdx + 1;
                    this.selectRecord(this.records[idx].id, idx);
                }
            },
            lastRecord() {
                if (this.records.length > 0) {
                    const idx = this.records.length - 1;
                    this.selectRecord(this.records[idx].id, idx);
                }
            },

            formatDateHTML(dateStr) {
                if (!dateStr) return '';
                const parts = dateStr.split('/');
                if (parts.length !== 3) return dateStr;
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            },

            onDateChange(e) {
                const val = e.target.value;
                if (!val) return;
                const parts = val.split('-');
                const newDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
                
                // Switch to existing record for that date
                const found = this.records.find(r => r.date === newDate);
                if (found) {
                    this.saveCurrentChanges();
                    this.selectRecord(found.id, this.records.indexOf(found));
                } else {
                    this.selectedRecord.date = newDate;
                    this.saveCurrentChanges();
                }
            },

            updateTotal(delta) {
                if (!this.selectedRecord) return;
                let val = parseFloat(this.selectedRecord.total.replace(/\./g, ''));
                if (isNaN(val)) val = 0;
                val += delta;
                this.selectedRecord.total = val.toLocaleString('id-ID');
                this.saveCurrentChanges();
            },

            firstRecord() { this.saveCurrentChanges(); if (this.records.length > 0) this.selectRecord(this.records[0].id, 0); },
            prevRecord() { this.saveCurrentChanges(); if (this.currentIdx > 0) this.selectRecord(this.records[this.currentIdx - 1].id, this.currentIdx - 1); },
            nextRecord() { this.saveCurrentChanges(); if (this.currentIdx < this.records.length - 1) this.selectRecord(this.records[this.currentIdx + 1].id, this.currentIdx + 1); },
            lastRecord() { this.saveCurrentChanges(); if (this.records.length > 0) this.selectRecord(this.records[this.records.length - 1].id, this.records.length - 1); },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.createNew(); break;
                    case 'save': 
                        this.saveCurrentChanges(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.selectedRecord, 'HReBanking_' + (this.selectedRecord?.doc_no?.replace(/\//g, '-') || 'Draft') + '.json');
                        }
                        showToast('Record saved to file', 'success'); 
                        break;
                    case 'delete': this.deleteRecord(); break;
                    case 'refresh': window.location.reload(); break;
                    case 'preview': window.print(); break;
                    case 'find': this.activeMainTab = 'list'; this.$nextTick(() => { if(typeof erpFindOpen === 'function') erpFindOpen(); }); break;
                    case 'undo': this.undoChanges(); break;
                    case 'save-as': 
                        this.saveAsNew(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.selectedRecord, 'HReBanking_Copy.json');
                        }
                        break;
                    case 'edit': this.focusFirstField(); break;
                    case 'barcode': showToast('Generating barcode...', 'info'); break;
                    case 'resend': showToast('Re-sending document...', 'info'); break;
                }
            },

            undoChanges() {
                if (this.selectedRecord && confirm('Revert all unsaved changes for this record?')) {
                    this.selectRecord(this.selectedRecord.id, this.currentIdx);
                    showToast('Changes reverted', 'info');
                }
            },

            saveAsNew() {
                if (!this.selectedRecord) return;
                const clone = JSON.parse(JSON.stringify(this.selectedRecord));
                clone.id = this.records.length + 1;
                clone.doc_no += ' (COPY)';
                this.records.push(clone);
                this.selectRecord(clone.id, this.records.length - 1);
                showToast('Record duplicated', 'success');
            },

            focusFirstField() {
                this.activeMainTab = 'detail';
                this.$nextTick(() => {
                    const firstInput = document.querySelector('.tab-pane.active input:not([readonly])');
                    if (firstInput) firstInput.focus();
                });
            },

            createNew() {
                const newId = this.records.length + 1;
                const newRec = {
                    id: newId,
                    date: new Date().toLocaleDateString('id-ID'),
                    doc_no: `EBK/${new Date().getFullYear()}/${String(newId).padStart(4, '0')}`,
                    transfer_to: 'SYSTEM',
                    bank: 'BCA',
                    account_no: '00000000',
                    total: '0',
                    status: 'DRAFT',
                    note: '',
                    items: []
                };
                this.records.push(newRec);
                this.selectRecord(newId, this.records.length - 1);
                this.activeMainTab = 'detail';
                showToast('New e-Banking record created', 'success');
            },

            deleteRecord() {
                if (!this.selectedRecord) return;
                if (confirm('Are you sure you want to delete this record?')) {
                    const idx = this.records.findIndex(r => r.id === this.selectedRecord.id);
                    if (idx !== -1) {
                        this.records.splice(idx, 1);
                        if (this.records.length > 0) {
                            const newIdx = Math.min(idx, this.records.length - 1);
                            this.selectRecord(this.records[newIdx].id, newIdx);
                        } else {
                            this.selectedRecord = null;
                        }
                    }
                    showToast('Record deleted', 'success');
                }
            }
        }
    }
</script>
@endpush
