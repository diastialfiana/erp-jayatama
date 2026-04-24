@extends('layouts.app')

@section('title', 'Customer Invoice')

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

    .bar-top { padding: 8px 10px; font-size: 0.75rem; color: #64748b; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; text-align: center; }

    /* Grid Styles */
    .list-grid { width: 100%; border-collapse: collapse; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; font-size: 0.75rem; }
    .list-grid th { background: white; color: #64748b; padding: 4px 6px; text-align: left; font-weight: normal; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); white-space: nowrap; text-transform: uppercase; font-size: 0.7rem; }
    .list-grid td { padding: 4px 6px; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); white-space: nowrap; color: #334155; }
    .list-grid tr:hover td { background: #f8fafc; cursor: pointer; }
    .list-grid tr.selected td { background: #e2e8f0; }
    
    .grid-footer { display: flex; align-items: center; padding: 4px; border-top: 1px solid var(--hr-border); border-bottom: 1px solid var(--hr-border); background: #e2e8f0; gap: 2px; flex-shrink: 0; }
    .pager-btn { background: white; border: 1px solid #cbd5e1; padding: 2px 6px; font-size: 0.65rem; cursor: pointer; color: #64748b; }
    .pager-btn:hover { background: #f1f5f9; }

    .detail-form-area { padding: 15px; background: #e2e8f0; border-bottom: 1px solid var(--hr-border); display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 4px; font-size: 0.75rem; }
    .form-label { width: 100px; text-align: right; margin-right: 10px; color: #475569; }
    .form-input { border: 1px solid var(--hr-border); padding: 4px 8px; border-radius: 0; font-size: 0.75rem; background: white; outline: none; }
    .form-select { border: 1px solid var(--hr-border); padding: 3px 6px; border-radius: 0; font-size: 0.75rem; background: white; outline: none; }

    .bottom-section { display: flex; flex-direction: column; flex-shrink: 0; background: #e2e8f0; }
    .note-area { display: flex; background: #e2e8f0; }
    .note-label { width: 100px; padding: 4px 10px; font-size: 0.75rem; color: #475569; }
    .custom-combobox {
        display: flex;
        align-items: center;
        background: white;
        border: 1px solid var(--hr-border);
        box-sizing: border-box;
        height: 25px;
    }
    .custom-combobox .combo-input {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 0.75rem;
        padding: 3px 6px;
        outline: none;
        min-width: 0;
        height: 100%;
    }
    .combo-btn-wrapper {
        width: 24px;
        height: 100%;
        background: #f1f5f9;
        border-left: 1px solid var(--hr-border);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .combo-btn-wrapper:hover {
        background: #e2e8f0;
    }
    .combo-btn-wrapper .combo-arrow {
        color: #475569;
        font-size: 0.6rem;
        pointer-events: none;
    }
    .combo-dropdown {
        position: absolute;
        top: 100%;
        left: -1px;
        right: -1px;
        background: white;
        border: 1px solid var(--hr-border);
        max-height: 150px;
        overflow-y: auto;
        z-index: 100;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
    }
    .combo-option {
        padding: 6px 8px;
        font-size: 0.75rem;
        cursor: pointer;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.1s;
    }
    .combo-option:last-child {
        border-bottom: none;
    }
    .combo-option.selected {
        background: #f1f5f9;
        font-weight: 600;
        color: #1e293b;
    }
    .combo-option:hover {
        background: #2563eb;
        color: white;
    }
</style>
@endpush

@section('content')
<div x-data="arInvoiceManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)" style="background: white; border: 1px solid var(--hr-border); margin: 10px; display: flex; flex-direction: column; height: calc(100vh - 80px);">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 8px; align-items: center;">
            <div style="display: flex; gap: 2px;">
                <div style="width: 6px; height: 6px; background: #ef4444;"></div>
                <div style="width: 6px; height: 6px; background: #eab308;"></div>
            </div>
            <span style="font-weight: 500; font-size: 0.8rem;">Customer Invoice</span>
        </div>
        <div style="display: flex; gap: 15px;">
            <span style="cursor: pointer; font-size: 1rem;" class="nav-btn">-</span>
            <span style="cursor: pointer; font-size: 1rem;" class="nav-btn">□</span>
            <span style="cursor: pointer; font-size: 1rem;" class="nav-btn">✕</span>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Main Navigation Tabs -->
    <div class="main-tabs" style="background: #e2e8f0; border-bottom: 1px solid var(--hr-border); padding-left: 10px; border-radius: 0; width: 100%;">
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">RECORD DETAIL</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">RECORDS LIST</button>
    </div>

    <div class="tab-content" style="border-top: none; flex: 1; min-height: 0;">
        
        <!-- RECORD DETAIL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''">
            <div class="detail-form-area" x-show="selectedInvoice">
                <div style="display: flex; gap: 20px;">
                    <div>
                        <div class="form-group">
                            <div class="form-label">Date</div>
                            <input type="date" class="form-input" style="width: 140px; height: 25px; box-sizing: border-box;" x-model="selectedInvoice.date" @change="saveCurrentChanges()">
                        </div>
                        <div class="form-group">
                            <div class="form-label">Currency</div>
                            <div class="custom-combobox" style="width: 140px; position: relative;" @click.away="currencyOpen = false">
                                <input type="text" class="combo-input" x-model="selectedInvoice.currency" @focus="currencyOpen = true" @input="currencyOpen = true; saveCurrentChanges()" @change="saveCurrentChanges()">
                                <div class="combo-btn-wrapper" @click="currencyOpen = !currencyOpen">
                                    <div class="combo-arrow">▼</div>
                                </div>
                                <div class="combo-dropdown" x-show="currencyOpen" style="display: none;">
                                    <template x-for="opt in currencies" :key="opt">
                                        <div class="combo-option" :class="(selectedInvoice && selectedInvoice.currency) === opt ? 'selected' : ''" @click="selectedInvoice.currency = opt; saveCurrentChanges(); currencyOpen = false" x-text="opt"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-label">Customer Name</div>
                            <div class="custom-combobox" style="width: 335px; position: relative;" @click.away="customerOpen = false">
                                <input type="text" class="combo-input" x-model="selectedInvoice.customer_name" @focus="customerOpen = true" @input="customerOpen = true; saveCurrentChanges()" @change="saveCurrentChanges()">
                                <div class="combo-btn-wrapper" @click="customerOpen = !customerOpen">
                                    <div class="combo-arrow">▼</div>
                                </div>
                                <div class="combo-dropdown" x-show="customerOpen" style="display: none;">
                                    <template x-for="opt in customers" :key="opt">
                                        <div class="combo-option" :class="(selectedInvoice && selectedInvoice.customer_name) === opt ? 'selected' : ''" @click="selectedInvoice.customer_name = opt; saveCurrentChanges(); customerOpen = false" x-text="opt"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <div class="form-label" style="width: 60px;">Duedate</div>
                            <input type="date" class="form-input" style="width: 140px; height: 25px; box-sizing: border-box;" x-model="selectedInvoice.duedate" @change="saveCurrentChanges()">
                        </div>
                        <div class="form-group">
                            <div class="form-label" style="width: 60px;">Rate</div>
                            <div class="custom-combobox" style="width: 140px; position: relative;" @click.away="rateOpen = false">
                                <input type="text" class="combo-input" x-model="selectedInvoice.rate" @focus="rateOpen = true" @input="rateOpen = true; saveCurrentChanges()" @change="saveCurrentChanges()">
                                <div class="combo-btn-wrapper" @click="rateOpen = !rateOpen">
                                    <div class="combo-arrow">▼</div>
                                </div>
                                <div class="combo-dropdown" x-show="rateOpen" style="display: none;">
                                    <template x-for="opt in rates" :key="opt">
                                        <div class="combo-option" :class="(selectedInvoice && selectedInvoice.rate) === opt ? 'selected' : ''" @click="selectedInvoice.rate = opt; saveCurrentChanges(); rateOpen = false" x-text="opt"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <div class="form-label" style="width: 40px; text-align: left;">Reff.</div>
                            <input type="text" class="form-input" style="width: 150px; background: #e2e8f0;" x-model="selectedInvoice.ref" readonly>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: flex-end; padding-right: 20px;">
                    <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b; text-transform: lowercase;">customer invoice</div>
                </div>
            </div>
            
            <div style="flex: 1; overflow: auto; background: white;" x-show="selectedInvoice">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 120px;">ACCOUNT</th>
                            <th style="width: 250px;">ACCOUNT DESC.</th>
                            <th style="width: 120px;">DEPT.</th>
                            <th style="width: 120px;">COST</th>
                            <th style="width: 150px; text-align: right;">AMOUNT</th>
                            <th>DESCRIPTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, idx) in selectedInvoice.items" :key="idx">
                            <tr>
                                <td><input type="text" x-model="item.account" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.account_desc" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.dept" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.cost" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="number" x-model="item.amount" class="form-input" style="width: 100%; box-sizing: border-box; text-align: right; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.description" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                            </tr>
                        </template>
                        <tr style="background: white; border-top: 1px solid #cbd5e1;">
                            <td colspan="4" style="color: transparent;">.</td>
                            <td style="padding: 2px;"><input type="number" x-model="newItem.amount" class="form-input" style="width: 100%; box-sizing: border-box; text-align: right;" placeholder="Amt..." @keydown.enter="addNewItem"></td>
                            <td style="padding: 2px;">
                                <div style="display: flex; gap: 4px;">
                                    <input type="text" x-model="newItem.description" class="form-input" style="flex: 1; box-sizing: border-box;" placeholder="Desc / Add Account..." @keydown.enter="addNewItem">
                                    <button @click="addNewItem()" style="background:#1e293b; color:white; border:none; padding: 2px 8px; cursor:pointer;" title="Add Item">Add</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Detail Footer -->
            <div class="bottom-section">
                <!-- Summary / Total Amount Bar -->
                <div style="display: flex; justify-content: flex-end; padding: 4px 0; background: #e2e8f0; border-top: 1px solid var(--hr-border);">
                    <div style="width: 75%; max-width: 800px; display: flex; padding-right: 15%;">
                        <div style="flex: 1;"></div>
                        <input type="text" class="form-input" style="width: 150px; text-align: right; background: white;" :value="formatCurrency(selectedInvoice.amount_total)" readonly>
                        <div style="flex: 1;"></div>
                    </div>
                </div>

                <!-- Navigation Bar -->
                <div class="grid-footer">
                    <button class="pager-btn" @click="firstRecord()" :disabled="currentIndex <= 0">|◀</button>
                    <button class="pager-btn" @click="prevRecord()" :disabled="currentIndex <= 0">◀</button>
                    <button class="pager-btn" @click="prevRecord()" :disabled="currentIndex <= 0">◀◀</button>
                    <span class="pager-btn" style="border:none; background:transparent; padding: 2px 10px;">Record <span x-text="currentIndex + 1"></span> of <span x-text="invoices.length"></span></span>
                    <button class="pager-btn" @click="nextRecord()" :disabled="currentIndex >= invoices.length - 1">▶▶</button>
                    <button class="pager-btn" @click="nextRecord()" :disabled="currentIndex >= invoices.length - 1">▶</button>
                    <button class="pager-btn" @click="lastRecord()" :disabled="currentIndex >= invoices.length - 1">▶|</button>
                </div>

                <!-- Note Area -->
                <div class="note-area">
                    <div class="note-label">Note</div>
                    <textarea class="note-input" x-model="selectedInvoice.note" @change="saveCurrentChanges()"></textarea>
                    <!-- Scrollbar placeholders -->
                    <div style="width: 15px; background: #cbd5e1; display:flex; flex-direction:column; justify-content: space-between;">
                        <div style="height: 12px; background: #94a3b8; font-size:8px; line-height:12px; text-align:center;">▲</div>
                        <div style="height: 12px; background: #94a3b8; font-size:8px; line-height:12px; text-align:center;">▼</div>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- RECORDS LIST TAB -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''">
            <div class="bar-top" style="text-align: left;">Drag a column header here to group by that column</div>
            <div style="flex: 1; overflow: auto;">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 25px;"></th>
                            <th style="width: 50px;">TYPE</th>
                            <th style="width: 120px;">REF</th>
                            <th style="width: 80px;">USERNO</th>
                            <th style="width: 90px;">DATE</th>
                            <th style="width: 90px;">DUEDATE</th>
                            <th style="width: 200px;">CUSTOMER NAME</th>
                            <th style="width: 60px;">CURR</th>
                            <th>NOTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, idx) in invoices" :key="item.id">
                            <tr @click="selectInvoice(item.id)" :class="selectedInvoice && selectedInvoice.id === item.id ? 'selected' : ''">
                                <td style="text-align: center; color: #475569;"><span x-show="selectedInvoice && selectedInvoice.id === item.id">▶</span></td>
                                <td x-text="item.type"></td>
                                <td x-text="item.ref"></td>
                                <td x-text="item.user_no"></td>
                                <td x-text="formatDate(item.date)"></td>
                                <td x-text="formatDate(item.duedate)"></td>
                                <td x-text="item.customer_name" style="color: #64748b;"></td>
                                <td x-text="item.currency"></td>
                                <td x-text="item.note"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="grid-footer">
                <button class="pager-btn" @click="firstRecord()">|◀</button>
                <button class="pager-btn" @click="prevRecord()">◀</button>
                <button class="pager-btn" @click="prevRecord()">◀◀</button>
                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIndex + 1"></span> of <span x-text="invoices.length"></span></span>
                <button class="pager-btn" @click="nextRecord()">▶▶</button>
                <button class="pager-btn" @click="nextRecord()">▶</button>
                <button class="pager-btn" @click="lastRecord()">▶|</button>
            </div>
        </div>
        
    </div>

    <!-- App Version Footer -->
    <div class="version-footer">
        <div>Version:</div>
        <div style="font-size: 0.8rem; cursor: pointer; display: flex; align-items: flex-end;">◢</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function arInvoiceManager() {
        return {
            activeMainTab: 'detail',
            invoices: @json($arInvoices),
            selectedInvoice: null,
            newItem: { account: '400-99', account_desc: '', dept: '', cost: '', amount: 0, description: '' },
            
            currencyOpen: false,
            customerOpen: false,
            rateOpen: false,
            
            currencies: ['IDR', 'USD', 'SGD', 'EUR'],
            customers: ['PT JAYA ABADI', 'CV MAJU TERUS', 'PT MENTARI SENTOSA', 'BINTANG MAKMUR'],
            rates: ['1', '15000', '16000'],

            get currentIndex() {
                if (!this.selectedInvoice) return -1;
                return this.invoices.findIndex(r => r.id === this.selectedInvoice.id);
            },

            init() {
                if (this.invoices && this.invoices.length > 0) {
                    this.selectInvoice(this.invoices[0].id);
                }
            },

            selectInvoice(id) {
                const inv = this.invoices.find(r => r.id === id);
                if (inv) {
                    this.selectedInvoice = JSON.parse(JSON.stringify(inv));
                    this.newItem = { account: '400-99', account_desc: '', dept: '', cost: '', amount: 0, description: '' };
                }
            },

            formatDate(dateStr) {
                if (!dateStr) return '';
                const parts = dateStr.split('-');
                if (parts.length !== 3) return dateStr;
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            },

            formatCurrency(val) {
                if(isNaN(val)) return '0,00';
                // Using European formatting for Indonesia usually, replace period with comma etc
                return Number(val).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            saveCurrentChanges() {
                if (this.selectedInvoice) {
                    // Calc total
                    if(this.selectedInvoice.items) {
                        this.selectedInvoice.amount_total = this.selectedInvoice.items.reduce((acc, it) => acc + Number(it.amount || 0), 0);
                    }
                    const idx = this.invoices.findIndex(r => r.id === this.selectedInvoice.id);
                    if (idx !== -1) {
                        this.invoices[idx] = JSON.parse(JSON.stringify(this.selectedInvoice));
                    }
                }
            },

            addNewItem() {
                if(this.newItem.amount <= 0 && this.newItem.description === '') return;
                if(!this.selectedInvoice.items) this.selectedInvoice.items = [];
                
                this.selectedInvoice.items.push({
                    account: this.newItem.account,
                    account_desc: this.newItem.description.substring(0, 15) || 'Sale',
                    dept: 'Sales',
                    cost: '-',
                    amount: Number(this.newItem.amount),
                    description: this.newItem.description
                });
                this.newItem = { account: '400-99', account_desc: '', dept: '', cost: '', amount: 0, description: '' };
                this.saveCurrentChanges();
            },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.createNew(); break;
                    case 'save': 
                        this.saveCurrentChanges(); 
                        showToast('Invoice saved', 'success'); 
                        break;
                    case 'save-as': 
                        this.saveAsNew(); 
                        break;
                    case 'delete': this.deleteRecord(); break;
                    case 'refresh': window.location.reload(); break;
                    case 'find': this.activeMainTab = 'list'; break;
                    case 'preview': window.print(); break;
                    case 'undo': this.undoChanges(); break;
                    case 'edit': 
                        this.activeMainTab = 'detail';
                        this.$nextTick(() => { document.querySelector('.form-input')?.focus(); });
                        break;
                    case 'ar-invoice': window.location.href = '{{ route('accounting.ar_invoice') }}'; break;
                    case 'ar-return': window.location.href = '{{ route('accounting.ar_return') }}'; break;
                    case 'ap-invoice': window.location.href = '{{ route('accounting.ap_invoice') }}'; break;
                    case 'ap-return': window.location.href = '{{ route('accounting.ap_return') }}'; break;
                    default: showToast('Action: ' + action, 'info');
                }
            },

            createNew() {
                const newId = this.invoices.length ? Math.max(...this.invoices.map(i => i.id)) + 1 : 1;
                const newReq = {
                    id: newId,
                    type: 'INV',
                    ref: `AR-2402-${String(newId).padStart(4, '0')}`,
                    user_no: '',
                    date: new Date().toISOString().split('T')[0],
                    duedate: new Date().toISOString().split('T')[0],
                    customer_name: '',
                    currency: 'IDR',
                    rate: 1.0,
                    note: '',
                    amount_total: 0,
                    items: []
                };
                this.invoices.push(newReq);
                this.selectInvoice(newId);
                this.activeMainTab = 'detail';
                showToast('New customer invoice created', 'success');
            },

            deleteRecord() {
                if (!this.selectedInvoice) return;
                if (confirm('Delete this invoice?')) {
                    this.invoices = this.invoices.filter(r => r.id !== this.selectedInvoice.id);
                    if (this.invoices.length > 0) {
                        this.selectInvoice(this.invoices[0].id);
                    } else {
                        this.selectedInvoice = null;
                        this.createNew(); // auto create empty
                    }
                    showToast('Invoice deleted', 'success');
                }
            },

            saveAsNew() {
                if (!this.selectedInvoice) return;
                const clone = JSON.parse(JSON.stringify(this.selectedInvoice));
                const newId = this.invoices.length ? Math.max(...this.invoices.map(i => i.id)) + 1 : 1;
                clone.id = newId;
                clone.ref += ' (COPY)';
                this.invoices.push(clone);
                this.selectInvoice(newId);
                showToast('Invoice duplicated', 'success');
            },

            undoChanges() {
                if (this.selectedInvoice && confirm('Revert all unsaved changes?')) {
                    this.selectInvoice(this.selectedInvoice.id);
                    showToast('Changes reverted', 'info');
                }
            },

            firstRecord() { if (this.invoices.length > 0) this.selectInvoice(this.invoices[0].id); },
            prevRecord() {
                const idx = this.currentIndex;
                if (idx > 0) this.selectInvoice(this.invoices[idx - 1].id);
            },
            nextRecord() {
                const idx = this.currentIndex;
                if (idx < this.invoices.length - 1) this.selectInvoice(this.invoices[idx + 1].id);
            },
            lastRecord() { if (this.invoices.length > 0) this.selectInvoice(this.invoices[this.invoices.length - 1].id); }
        }
    }
</script>
@endpush
