@extends('layouts.app')

@section('title', 'HR. e-Banking')

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
    
    .tab-content { background: #f1f5f9; border-top: 1px solid var(--hr-border); min-height: 500px; padding: 0; display: flex; flex-direction: column; overflow: hidden; margin-top: -1px; }
    .tab-pane { display: none; flex: 1; flex-direction: column; height: 100%; min-height: calc(100vh - 150px); }
    .tab-pane.active { display: flex; }

    .bar-top { padding: 8px 10px; font-size: 0.75rem; color: #64748b; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; }
    .bar-search { padding: 4px 10px; display: flex; justify-content: flex-end; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; }

    /* Grid Styles */
    .list-grid { width: 100%; border-collapse: collapse; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; font-size: 0.75rem; background: white;}
    .list-grid th { background: white; color: #64748b; padding: 4px 6px; text-align: left; font-weight: normal; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); white-space: nowrap; text-transform: uppercase; font-size: 0.7rem; }
    .list-grid td { padding: 4px 6px; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); white-space: nowrap; color: #334155; }
    .list-grid tr:hover td { background: #f8fafc; cursor: pointer; }
    .list-grid tr.selected td { background: #e2e8f0; }
    
    .grid-footer { display: flex; align-items: center; padding: 4px; border-top: 1px solid var(--hr-border); background: #f8fafc; gap: 2px; flex-shrink: 0; }
    .pager-btn { background: white; border: 1px solid #cbd5e1; padding: 2px 6px; font-size: 0.65rem; cursor: pointer; color: #64748b; }
    .pager-btn:hover { background: #f1f5f9; }

    /* Centered Form area for DETAIL tab */
    .centered-form { margin: 60px auto 0 auto; width: 450px; display: flex; flex-direction: column; gap: 8px; }
    .form-group-center { display: flex; align-items: center; justify-content: flex-start; }
    .form-label-center { width: 100px; text-align: right; margin-right: 10px; font-size: 0.75rem; color: #334155; }
    .form-input { border: 1px solid var(--hr-border); padding: 4px 8px; border-radius: 0; font-size: 0.75rem; background: white; width: 300px; }
    .form-input[readonly] { background: transparent; }
    .form-select { border: 1px solid var(--hr-border); padding: 3px 6px; border-radius: 0; font-size: 0.75rem; background: white; }

</style>
@endpush

@section('content')
<div x-data="hrEbankingManager()" x-init="init()" style="background: white; border: 1px solid var(--hr-border); margin: 10px;">
    <!-- Windows like Title bar -->
    <div style="background: white; padding: 0; border-bottom: 1px solid #e2e8f0;">
        <div style="background: transparent; padding: 6px 10px; color: #334155; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7" fill="#dc2626"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7" fill="#2563eb"></rect></svg>
                <span style="font-weight: 600;">HR. e-Banking</span>
            </div>
            <div style="display: flex; gap: 15px;">
                <span style="cursor: pointer; font-size: 0.9rem;">◁</span>
                <span style="cursor: pointer; font-size: 0.9rem;">▷</span>
                <span style="cursor: pointer;">✕</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Tabs -->
    <div class="main-tabs" style="background: #f1f5f9; border-bottom: 1px solid var(--hr-border); padding-left: 10px; border-radius: 0;">
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">DETAIL</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">LIST ALL</button>
    </div>

    <div class="tab-content" style="border-top: none;">
        
        <!-- DETAIL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''">
            <div class="centered-form" x-show="selectedRecord">
                <div class="form-group-center">
                    <div class="form-label-center">Date</div>
                    <div style="display:flex;">
                        <input type="date" x-ref="datePickerDetail" x-model="selectedRecord.date" @change="onDateChange($event)"
                               style="position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none;">
                        <input type="text" class="form-input" style="width: 100px; border:none; text-align:right; cursor:pointer;" 
                               :value="selectedRecord.date" @click="$refs.datePickerDetail.showPicker()" readonly>
                        <span style="background: #e2e8f0; padding: 4px 6px; border-left: 1px solid var(--hr-border); color: #64748b; cursor:pointer;" @click="$refs.datePickerDetail.showPicker()">▼</span>
                    </div>
                </div>

                <div class="form-group-center" style="margin-top: 5px;">
                    <div class="form-label-center">Select Data</div>
                    <div style="display:flex; align-items:center; gap: 15px; font-size: 0.75rem; color:#334155;">
                        <label style="display:flex; align-items:center; gap: 5px; cursor:pointer;">
                            <input type="radio" value="Operational" x-model="selectedRecord.select_data" name="select_data" @change="saveCurrentChanges()"> Operational
                        </label>
                        <label style="display:flex; align-items:center; gap: 5px; cursor:pointer;">
                            <input type="radio" value="Non Operational" x-model="selectedRecord.select_data" name="select_data" @change="saveCurrentChanges()"> Non Operational
                        </label>
                    </div>
                </div>

                <div class="form-group-center" style="margin-top: 5px;">
                    <div class="form-label-center">Invoice No</div>
                    <select class="form-select" style="width: 140px;" x-model="selectedRecord.invoice_no" @change="saveCurrentChanges()">
                        <option value=""></option>
                        <option value="INV-2026-001">INV-2026-001</option>
                        <option value="INV-2026-002">INV-2026-002</option>
                    </select>
                </div>

                <div class="form-group-center" style="margin-top: 5px;">
                    <div class="form-label-center">Account No.</div>
                    <input type="text" class="form-input" x-model="selectedRecord.account_no" readonly>
                </div>

                <div class="form-group-center" style="margin-top: 2px;">
                    <div class="form-label-center">Account Name</div>
                    <input type="text" class="form-input" x-model="selectedRecord.account_name" readonly>
                </div>

                <div class="form-group-center" style="margin-top: 2px;">
                    <div class="form-label-center">Bank Name</div>
                    <input type="text" class="form-input" x-model="selectedRecord.bank_name" readonly>
                </div>

                <div class="form-group-center" style="margin-top: 10px;">
                    <div class="form-label-center">Total</div>
                    <div style="display:flex; border: 1px solid var(--hr-border); background:white;">
                        <input type="text" class="form-input" style="width: 120px; border:none; text-align:right;" x-model="selectedRecord.total">
                        <div style="display:flex; flex-direction:column; border-left:1px solid var(--hr-border); background:#e2e8f0;">
                            <span style="font-size:0.4rem; padding: 1px 4px; border-bottom:1px solid var(--hr-border); cursor:pointer;" @click="updateTotal(1)">▲</span>
                            <span style="font-size:0.4rem; padding: 1px 4px; cursor:pointer;" @click="updateTotal(-1)">▼</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Padding spacer to push layout like the screenshot -->
            <div style="flex: 1;"></div>
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
                            <tr @click="selectRecord(item.id, idx)" :class="selectedRecord && selectedRecord.id === item.id ? 'selected' : ''">
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

            saveCurrentChanges() {
                if (this.selectedRecord) {
                    const idx = this.records.findIndex(r => r.id === this.selectedRecord.id);
                    if (idx !== -1) {
                        this.records[idx] = { ...this.selectedRecord };
                    }
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
            lastRecord() { this.saveCurrentChanges(); if (this.records.length > 0) this.selectRecord(this.records[this.records.length - 1].id, this.records.length - 1); }
        }
    }
</script>
@endpush
