@extends('layouts.app')

@section('title', 'Customer Return')

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

    .bar-top { padding: 8px 10px; font-size: 0.75rem; color: #64748b; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; text-align: left; }

    /* Grid Styles */
    .list-grid { width: 100%; border-collapse: collapse; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; font-size: 0.75rem; }
    .list-grid th { background: white; color: #64748b; padding: 4px 6px; text-align: left; font-weight: normal; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); white-space: nowrap; text-transform: uppercase; font-size: 0.7rem; }
    .list-grid td { padding: 4px 6px; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); white-space: nowrap; color: #334155; }
    .list-grid tr.row-data:hover td { background: #f8fafc; cursor: pointer; }
    .list-grid tr.selected td { background: #e2e8f0; }
    
    .grid-footer { display: flex; align-items: center; justify-content: space-between; padding: 4px; border-top: 1px solid var(--hr-border); border-bottom: 1px solid var(--hr-border); background: #e2e8f0; flex-shrink: 0; }
    .pager-group { display: flex; gap: 2px; }
    .pager-btn { background: white; border: 1px solid #cbd5e1; padding: 2px 6px; font-size: 0.65rem; cursor: pointer; color: #64748b; }
    .pager-btn:hover { background: #f1f5f9; }

    .detail-form-area { padding: 15px; background: #e2e8f0; border-bottom: 1px solid var(--hr-border); display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 4px; font-size: 0.75rem; }
    .form-label { width: 100px; text-align: right; margin-right: 10px; color: #475569; }
    .form-input { border: 1px solid var(--hr-border); padding: 4px 8px; border-radius: 0; font-size: 0.75rem; background: white; outline: none; }
    
    .bottom-section { display: flex; flex-direction: row; justify-content: space-between; flex-shrink: 0; background: #e2e8f0; border-top: 1px solid var(--hr-border); padding: 10px; }
    .note-area { display: flex; background: #e2e8f0; flex: 1; border: 1px solid #cbd5e1; padding: 0; align-items: flex-start;}
    .note-label { width: 40px; font-size: 0.75rem; color: black; font-weight: bold; background: #d1d5db; padding: 4px 8px; text-align: left; }
    .note-input { flex: 1; height: 60px; border: none; padding: 4px; font-size: 0.75rem; background: white; resize: none; margin: 0; outline: none; border-top: 1px solid #cbd5e1; border-right: 1px solid #cbd5e1;}
    .summary-cols { display: flex; flex-direction: column; width: 250px; font-size: 0.75rem; }
    .summary-row { display: flex; align-items: center; justify-content: flex-end; margin-bottom: 2px; }
    .summary-row label { width: 60px; text-align: right; margin-right: 8px; color: black; font-weight: normal;}
    .summary-row label.bold { font-weight: bold; }
    .summary-row input { width: 120px; text-align: right; background: white; }

    /* Custom Combobox */
    .custom-combobox { display: flex; align-items: center; background: white; border: 1px solid var(--hr-border); box-sizing: border-box; height: 25px; position: relative; }
    .custom-combobox .combo-input { flex: 1; border: none; background: transparent; font-size: 0.75rem; padding: 3px 6px; outline: none; min-width: 0; height: 100%; }
    .combo-btn-wrapper { width: 24px; height: 100%; background: #f1f5f9; border-left: 1px solid var(--hr-border); display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .combo-btn-wrapper:hover { background: #e2e8f0; }
    .combo-btn-wrapper .combo-arrow { color: #475569; font-size: 0.6rem; pointer-events: none; }
    .combo-dropdown { position: absolute; top: 100%; left: -1px; right: -1px; background: white; border: 1px solid var(--hr-border); max-height: 150px; overflow-y: auto; z-index: 100; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); }
    .combo-option { padding: 6px 8px; font-size: 0.75rem; cursor: pointer; color: #334155; border-bottom: 1px solid #f1f5f9; transition: background-color 0.1s; }
    .combo-option:last-child { border-bottom: none; }
    .combo-option.selected { background: #f1f5f9; font-weight: 600; color: #1e293b; }
    .combo-option:hover { background: #2563eb; color: white; }

</style>
@endpush

@section('content')
<div x-data="arReturnManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)" style="background: white; border: 1px solid var(--hr-border); margin: 10px; display: flex; flex-direction: column; height: calc(100vh - 80px);">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 8px; align-items: center;">
            <div style="display: flex; gap: 2px;">
                <img src="https://img.icons8.com/color/16/000000/return.png" alt="icon"/>
            </div>
            <span style="font-weight: 500; font-size: 0.8rem;">Customer Return</span>
        </div>
        <div style="display: flex; gap: 15px;">
            <span style="cursor: pointer; font-size: 1rem;" class="nav-btn">-</span>
            <span style="cursor: pointer; font-size: 1rem;" class="nav-btn">□</span>
            <span style="cursor: pointer; font-size: 1rem;" class="nav-btn">✕</span>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Main Navigation Tabs -->
    <div class="main-tabs" style="border-bottom: 1px solid var(--hr-border); padding-left: 10px; border-radius: 0; width: 100%;">
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">RECORD DETAIL</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">RECORDS LIST</button>
    </div>

    <div class="tab-content" style="border-top: none; flex: 1; min-height: 0;">
        
        <!-- =======================
             RECORD DETAIL TAB
             ======================= -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''">
            <div class="detail-form-area" x-show="selectedReturn">
                <div style="display: flex; gap: 20px; flex: 1;">
                    <div>
                        <div class="form-group">
                            <div class="form-label" style="width: 80px; text-align: right;">Date</div>
                            <div class="custom-combobox" style="width: 140px; position: relative;">
                                <input type="date" class="combo-input" x-model="selectedReturn.date" @change="saveCurrentChanges()">
                                <div class="combo-btn-wrapper">
                                    <div class="combo-arrow">▼</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-label" style="width: 80px; text-align: right;">Currency</div>
                            <div class="custom-combobox" style="width: 140px; position: relative;" @click.away="currencyOpen = false">
                                <input type="text" class="combo-input" x-model="selectedReturn.currency" @focus="currencyOpen = true" @input="currencyOpen = true; saveCurrentChanges()" @change="saveCurrentChanges()">
                                <div class="combo-btn-wrapper" @click="currencyOpen = !currencyOpen">
                                    <div class="combo-arrow">▼</div>
                                </div>
                                <div class="combo-dropdown" x-show="currencyOpen" style="display: none;">
                                    <template x-for="opt in currencies" :key="opt">
                                        <div class="combo-option" :class="selectedReturn.currency === opt ? 'selected' : ''" @click="selectedReturn.currency = opt; saveCurrentChanges(); currencyOpen = false" x-text="opt"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-label" style="width: 80px; text-align: right;">Customer name</div>
                            <div class="custom-combobox" style="width: 250px; position: relative;" @click.away="customerOpen = false">
                                <input type="text" class="combo-input" x-model="selectedReturn.customer_name" @focus="customerOpen = true" @input="customerOpen = true; saveCurrentChanges()" @change="saveCurrentChanges()">
                                <div class="combo-btn-wrapper" @click="customerOpen = !customerOpen">
                                    <div class="combo-arrow">▼</div>
                                </div>
                                <div class="combo-dropdown" x-show="customerOpen" style="display: none;">
                                    <template x-for="opt in customers" :key="opt">
                                        <div class="combo-option" :class="selectedReturn.customer_name === opt ? 'selected' : ''" @click="selectedReturn.customer_name = opt; saveCurrentChanges(); customerOpen = false" x-text="opt"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <div class="form-label" style="width: 40px; text-align: right;">Reff.</div>
                            <input type="text" class="form-input" style="width: 140px; height: 25px; box-sizing: border-box;" x-model="selectedReturn.ref" @change="saveCurrentChanges()">
                        </div>
                        <div class="form-group">
                            <div class="form-label" style="width: 40px; text-align: right;">Rate</div>
                            <div class="custom-combobox" style="width: 140px; position: relative;" @click.away="rateOpen = false">
                                <input type="text" class="combo-input" x-model="selectedReturn.rate" @focus="rateOpen = true" @input="rateOpen = true; saveCurrentChanges()" @change="saveCurrentChanges()">
                                <div class="combo-btn-wrapper" @click="rateOpen = !rateOpen">
                                    <div class="combo-arrow">▼</div>
                                </div>
                                <div class="combo-dropdown" x-show="rateOpen" style="display: none;">
                                    <template x-for="opt in rates" :key="opt">
                                        <div class="combo-option" :class="selectedReturn.rate == opt ? 'selected' : ''" @click="selectedReturn.rate = opt; saveCurrentChanges(); rateOpen = false" x-text="opt"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: flex-end; padding-right: 20px;">
                    <div style="font-size: 1.5rem; font-weight: 800; color: #475569; text-transform: lowercase;">customer return</div>
                </div>
            </div>
            
            <div style="flex: 1; overflow: auto; background: white;" x-show="selectedReturn">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 20px;"></th>
                            <th style="width: 150px;">ACCOUNT</th>
                            <th style="width: 250px;">ACCOUNT DESC.</th>
                            <th style="width: 80px;">DEPT.</th>
                            <th style="width: 80px;">COST</th>
                            <th style="width: 150px; text-align: right;">AMOUNT</th>
                            <th>DESCRIPTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, idx) in selectedReturn.items" :key="idx">
                            <tr class="row-data">
                                <td style="text-align: center; color: transparent;">.</td>
                                <td><input type="text" x-model="item.account" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.account_desc" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.dept" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.cost" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="number" x-model="item.amount" class="form-input" style="width: 100%; box-sizing: border-box; text-align: right; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.description" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                            </tr>
                        </template>
                        <!-- Add New Item Row -->
                        <tr style="background: white; border-top: 1px solid #cbd5e1;">
                            <td colspan="5" style="color: transparent;">.</td>
                            <td style="padding: 2px;"><input type="text" x-model="newItem.amount" class="form-input" style="width: 100%; box-sizing: border-box; text-align: right;" placeholder="0,00" @keydown.enter="addNewItem"></td>
                            <td style="padding: 2px;">
                                <div style="display: flex; gap: 4px;">
                                    <input type="text" x-model="newItem.description" class="form-input" style="flex: 1; box-sizing: border-box;" placeholder="Add description..." @keydown.enter="addNewItem">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Detail Footer -->
            
            <div class="grid-footer" style="background: white; border-top: 1px solid #cbd5e1;">
                <div class="pager-group">
                    <button class="pager-btn" @click="firstRecord()" :disabled="currentIndex <= 0">|◀</button>
                    <button class="pager-btn" @click="prevRecord()" :disabled="currentIndex <= 0">◀</button>
                    <button class="pager-btn" @click="prevRecord()" :disabled="currentIndex <= 0">◀◀</button>
                    <span class="pager-btn" style="border:none; background:transparent; padding: 2px 10px;">Record <span x-text="currentIndex + 1"></span> of <span x-text="returns.length"></span></span>
                    <button class="pager-btn" @click="nextRecord()" :disabled="currentIndex >= returns.length - 1">▶▶</button>
                    <button class="pager-btn" @click="nextRecord()" :disabled="currentIndex >= returns.length - 1">▶</button>
                    <button class="pager-btn" @click="lastRecord()" :disabled="currentIndex >= returns.length - 1">▶|</button>
                </div>
                <div></div>
            </div>

            <div class="bottom-section" x-show="selectedReturn">
                <div style="display: flex; flex: 1; flex-direction: column;">
                     <div class="note-area" style="width: 300px;">
                        <div style="display: flex; flex-direction: column; width: 100%; height: 100%;">
                            <div class="note-label">Note</div>
                            <div style="display: flex; width: 100%;">
                                <textarea class="note-input" x-model="selectedReturn.note" @change="saveCurrentChanges()"></textarea>
                                <div style="width: 15px; background: #cbd5e1; display:flex; flex-direction:column; justify-content: space-between; height: 60px; border-top: 1px solid #cbd5e1;">
                                    <button style="height: 12px; background: #94a3b8; font-size:8px; line-height:12px; text-align:center; border:none; cursor:pointer; color:white;">▲</button>
                                    <button style="height: 12px; background: #94a3b8; font-size:8px; line-height:12px; text-align:center; border:none; cursor:pointer; color:white;">▼</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Totals -->
                <div class="summary-cols">
                    <div class="summary-row">
                        <label>Amount</label>
                        <input type="text" class="form-input" :value="formatCurrency(selectedReturn.amount_total)" readonly>
                    </div>
                    <div class="summary-row">
                        <label class="bold">Prepaid</label>
                        <input type="text" class="form-input" :value="formatCurrency(selectedReturn.prepaid)" readonly>
                    </div>
                    <div class="summary-row">
                        <label>Total</label>
                        <input type="text" class="form-input" :value="formatCurrency(selectedReturn.total)" readonly>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- =======================
             RECORDS LIST TAB
             ======================= -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''">
            <div class="bar-top">Drag a column header here to group by that column <span style="float: right; margin-right: 10px; cursor: pointer; font-size: 1rem;">⌕</span></div>
            <div style="flex: 1; overflow: auto;">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 25px;"></th>
                            <th style="width: 50px;">TYPE</th>
                            <th style="width: 120px;">REF</th>
                            <th style="width: 80px;">BRANCH</th>
                            <th style="width: 70px;">USERNO</th>
                            <th style="width: 80px;">DATE</th>
                            <th style="width: 250px;">CUSTOMER</th>
                            <th style="width: 50px;">CURR</th>
                            <th style="width: 50px;">RATE</th>
                            <th style="width: 60px;">LINK</th>
                            <th style="width: 100px; text-align: right;">AMOUNT</th>
                            <th style="width: 100px; text-align: right;">PREPAID</th>
                            <th style="width: 100px; text-align: right;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody style="border-bottom: none;">
                        <template x-for="(item, idx) in returns" :key="item.id">
                            <tr class="row-data" @click="selectReturn(item.id)" :class="selectedReturn && selectedReturn.id === item.id ? 'selected' : ''" :style="selectedReturn && selectedReturn.id === item.id ? 'background:#e0b0ff; color:indigo;' : ''">
                                <td style="text-align: center; color: transparent;">.</td>
                                <td x-text="item.type"></td>
                                <td x-text="item.ref" style="color:#d946ef;"></td>
                                <td x-text="item.branch"></td>
                                <td x-text="item.user_no" style="color:#d946ef;"></td>
                                <td x-text="formatDate(item.date)" style="color:#d946ef;"></td>
                                <td x-text="item.customer_name" style="color:#d946ef;"></td>
                                <td x-text="item.currency" style="color:#3b82f6;"></td>
                                <td x-text="item.rate" style="color:#ef4444;"></td>
                                <td x-text="item.link" style="color:#3b82f6;"></td>
                                <td x-text="formatCurrency(item.amount_total)" style="text-align: right; color:#ef4444;"></td>
                                <td x-text="formatCurrency(item.prepaid)" style="text-align: right; color:#ef4444;"></td>
                                <td x-text="formatCurrency(item.total)" style="text-align: right; color:#ef4444;"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <div class="grid-footer">
                <div class="pager-group">
                    <button class="pager-btn" @click="firstRecord()">|◀</button>
                    <button class="pager-btn" @click="prevRecord()">◀</button>
                    <button class="pager-btn" @click="prevRecord()">◀◀</button>
                    <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIndex + 1"></span> of <span x-text="returns.length"></span></span>
                    <button class="pager-btn" @click="nextRecord()">▶▶</button>
                    <button class="pager-btn" @click="nextRecord()">▶</button>
                    <button class="pager-btn" @click="lastRecord()">▶|</button>
                </div>
                <div></div>
            </div>
        </div>
        
    </div>

</div>
@endsection

@push('scripts')
<script>
    function arReturnManager() {
        return {
            activeMainTab: 'detail',
            returns: @json($arReturns),
            selectedReturn: null,
            newItem: { account: '', account_desc: '', dept: '', cost: '', amount: '', description: '' },
            
            currencyOpen: false,
            customerOpen: false,
            rateOpen: false,
            
            currencies: ['IDR', 'USD', 'SGD', 'EUR'],
            customers: ['PT JAYA ABADI', 'CV MAJU TERUS', 'PT MENTARI SENTOSA', 'BINTANG MAKMUR'],
            rates: ['1', '15000', '16000'],

            get currentIndex() {
                if (!this.selectedReturn) return -1;
                return this.returns.findIndex(r => r.id === this.selectedReturn.id);
            },

            init() {
                if (this.returns && this.returns.length > 0) {
                    this.selectReturn(this.returns[0].id);
                }
            },

            selectReturn(id) {
                const rtn = this.returns.find(r => r.id === id);
                if (rtn) {
                    this.selectedReturn = JSON.parse(JSON.stringify(rtn));
                    this.newItem = { account: '', account_desc: '', dept: '', cost: '', amount: '', description: '' };
                }
            },

            formatDate(dateStr) {
                if (!dateStr) return '';
                const parts = dateStr.split('-');
                if (parts.length !== 3) return dateStr;
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            },

            formatCurrency(val) {
                if(isNaN(val) || val === '') return '0,00';
                return Number(val).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            saveCurrentChanges() {
                if (this.selectedReturn) {
                    if(this.selectedReturn.items) {
                        this.selectedReturn.amount_total = this.selectedReturn.items.reduce((acc, it) => acc + Number(it.amount || 0), 0);
                        this.selectedReturn.total = this.selectedReturn.amount_total;
                    }
                    const idx = this.returns.findIndex(r => r.id === this.selectedReturn.id);
                    if (idx !== -1) {
                        this.returns[idx] = JSON.parse(JSON.stringify(this.selectedReturn));
                    }
                }
            },

            addNewItem() {
                if(this.newItem.amount === '' && this.newItem.description === '') return;
                if(!this.selectedReturn.items) this.selectedReturn.items = [];
                
                this.selectedReturn.items.push({
                    account: '110-20',
                    account_desc: this.newItem.description.substring(0, 15) || 'Return Item',
                    dept: 'Sales',
                    cost: '000',
                    amount: Number(this.newItem.amount || 0),
                    description: this.newItem.description
                });
                this.newItem = { account: '', account_desc: '', dept: '', cost: '', amount: '', description: '' };
                this.saveCurrentChanges();
            },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.createNew(); break;
                    case 'save': 
                        this.saveCurrentChanges(); 
                        showToast('Return saved', 'success'); 
                        break;
                    case 'delete': this.deleteRecord(); break;
                    case 'refresh': window.location.reload(); break;
                    case 'find': this.activeMainTab = 'list'; break;
                    case 'preview': window.print(); break;
                    case 'edit': 
                        this.activeMainTab = 'detail';
                        break;
                    case 'ar-invoice': window.location.href = '{{ route('accounting.ar-invoice') }}'; break;
                    case 'ar-return': window.location.href = '{{ route('accounting.ar-return') }}'; break;
                    case 'ap-invoice': window.location.href = '{{ route('accounting.ap-invoice') }}'; break;
                    case 'ap-return': window.location.href = '{{ route('accounting.ap-return') }}'; break;
                    default: showToast('Action: ' + action, 'info');
                }
            },

            createNew() {
                const newId = this.returns.length ? Math.max(...this.returns.map(i => i.id)) + 1 : 1;
                const newReq = {
                    id: newId,
                    type: 'RTN',
                    ref: `New Ref ${newId}`,
                    branch: 'HQ',
                    user_no: 'NEW',
                    link: '',
                    date: new Date().toISOString().split('T')[0],
                    customer_name: '',
                    currency: 'IDR',
                    rate: 1.0,
                    note: '',
                    amount_total: 0, prepaid:0, total:0,
                    items: []
                };
                this.returns.push(newReq);
                this.selectReturn(newId);
                this.activeMainTab = 'detail';
                showToast('New customer return created', 'success');
            },

            deleteRecord() {
                if (!this.selectedReturn) return;
                if (confirm('Delete this return?')) {
                    this.returns = this.returns.filter(r => r.id !== this.selectedReturn.id);
                    if (this.returns.length > 0) {
                        this.selectReturn(this.returns[0].id);
                    } else {
                        this.selectedReturn = null;
                        this.createNew();
                    }
                    showToast('Return deleted', 'success');
                }
            },

            firstRecord() { if (this.returns.length > 0) this.selectReturn(this.returns[0].id); },
            prevRecord() {
                const idx = this.currentIndex;
                if (idx > 0) this.selectReturn(this.returns[idx - 1].id);
            },
            nextRecord() {
                const idx = this.currentIndex;
                if (idx < this.returns.length - 1) this.selectReturn(this.returns[idx + 1].id);
            },
            lastRecord() { if (this.returns.length > 0) this.selectReturn(this.returns[this.returns.length - 1].id); }
        }
    }
</script>
@endpush
