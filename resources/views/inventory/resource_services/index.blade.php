@extends('layouts.app')

@section('title', 'Rembursment Contract')

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

    .bar-top { padding: 8px 10px; font-size: 0.75rem; color: #64748b; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; display: flex; justify-content: space-between; align-items:center;}
    
    /* Grid Styles */
    .list-grid { width: 100%; border-collapse: collapse; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; font-size: 0.75rem; background: white;}
    .list-grid th { background: white; color: #64748b; padding: 4px 6px; text-align: left; font-weight: normal; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); white-space: nowrap; text-transform: uppercase; font-size: 0.7rem; }
    .list-grid td { padding: 4px 6px; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); white-space: nowrap; color: #334155; }
    .list-grid tr:hover td { background: #f8fafc; cursor: pointer; }
    .list-grid tr.selected td { background: #e2e8f0; }
    .list-grid td.red-text { color: #dc2626; }
    
    .grid-footer { display: flex; align-items: center; padding: 4px; border-top: 1px solid var(--hr-border); background: #f8fafc; gap: 2px; flex-shrink: 0; min-height: 38px;}
    .pager-btn { background: white; border: 1px solid #cbd5e1; padding: 2px 6px; font-size: 0.65rem; cursor: pointer; color: #64748b; }
    .pager-btn:hover { background: #f1f5f9; }

    .detail-form-area { padding: 10px 15px; background: #f8fafc; border-bottom: 1px solid var(--hr-border); display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 4px; font-size: 0.75rem; }
    .form-label { width: 110px; text-align: right; margin-right: 10px; color: #475569; }
    .form-input { border: 1px solid var(--hr-border); padding: 4px 8px; border-radius: 0; font-size: 0.75rem; background: white; }
    .form-select { border: 1px solid var(--hr-border); padding: 3px 6px; border-radius: 0; font-size: 0.75rem; background: white; }
    
    .bordered-panel { border: 1px solid var(--hr-border); display: flex; background: white; flex: 1; flex-direction: column; overflow: hidden; margin: 10px 10px 0 10px;}
    
    /* Summary Inner Tab */
    .detail-tabs { display: flex; border-bottom: 1px solid var(--hr-border); background: #f1f5f9;}
    .detail-tab { padding: 6px 15px; font-size: 0.7rem; color: #334155; background: transparent; border: 1px solid transparent; border-right: 1px solid var(--hr-border); border-bottom: none; cursor: pointer; text-transform: uppercase; }
    .detail-tab.active { background: white; border-bottom-color: white;}
</style>
@endpush

@section('content')
<div x-data="resourceServiceManager()" x-init="init()" style="background: white; border: 1px solid var(--hr-border); margin: 10px;">
    <!-- Windows like Title bar -->
    <div style="background: white; padding: 0; border-bottom: 1px solid #e2e8f0;">
        <div style="background: transparent; padding: 6px 10px; color: #334155; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem;">
            <div style="display: flex; gap: 8px; align-items: center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7" fill="#dc2626"></rect><rect x="14" y="14" width="7" height="7" fill="#2563eb"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span style="font-weight: 600;">Rembursment Contract</span>
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
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">RECORD DETAIL</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">RECORDS LIST</button>
    </div>

    <div class="tab-content" style="border-top: none;">
        
        <!-- RECORD DETAIL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''" style="background: #e2e8f0;">
            <!-- Header forms -->
            <div class="detail-form-area" style="padding-top:20px; position: relative;" x-show="selectedContract">
                <div style="display:flex; gap: 20px;">
                    <div>
                        <div class="form-group">
                            <div class="form-label">Date</div>
                            <select class="form-select" style="width: 140px;" x-model="selectedContract.order_date">
                                <option x-text="selectedContract.order_date"></option>
                            </select>
                            <span style="margin-left: 10px; margin-right: 10px; font-size: 0.75rem; color: #475569;">BAP. Reff</span>
                            <input type="text" class="form-input" style="width: 150px;" x-model="selectedContract.ref">
                        </div>
                        <div class="form-group">
                            <div class="form-label">Customer</div>
                            <div style="display:flex;">
                                <input type="text" class="form-input" style="width: 250px;" x-model="selectedContract.customer_name">
                                <span class="pager-btn" style="border-left:none;" @click="lookup('Customer')">...</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-label">From Quotation</div>
                            <div style="display:flex; margin-right: 15px;">
                                <input type="text" class="form-input" style="width: 160px;" x-model="selectedContract.quotation">
                                <span class="pager-btn" style="border-left:none;" @click="lookup('Quotation')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </span>
                            </div>
                            <span style="margin-left: 5px; margin-right: 10px; font-size: 0.75rem; color: #475569;">Exp. Date</span>
                            <select class="form-select" style="width: 120px;" x-model="selectedContract.exp_date">
                                <option x-text="selectedContract.exp_date"></option>
                            </select>
                            
                            <label style="margin-left: 50px; display:flex; align-items:center; gap:5px; font-size:0.75rem; color:#334155;">
                                Set as Customer Budget <input type="checkbox" checked>
                            </label>
                        </div>
                        <div class="form-group">
                            <div class="form-label">Document Copy</div>
                            <div style="display:flex;">
                                <input type="text" class="form-input" style="width: 210px;" x-model="selectedContract.recid">
                                <span class="pager-btn" style="border-left:none;" @click="lookup('File')">📁</span>
                                <span class="pager-btn" style="border-left:none;" @click="lookup('Search File')">🔍</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; align-items:flex-end; justify-content:space-between;">
                    <div style="display:flex; gap: 40px; font-size: 0.8rem; color:#475569; padding-right: 20px;">
                        <span x-text="selectedContract.userno"></span>
                        <span x-text="selectedContract.id"></span>
                    </div>
                    <div style="font-size: 1.4rem; font-weight: bold; color: #1e293b; padding-right: 5px; margin-bottom: 10px; text-transform: lowercase;">
                        rembursment
                    </div>
                </div>
            </div>

            <!-- Grid Layout Panel -->
            <div class="bordered-panel">
                <div class="detail-tabs">
                    <button class="detail-tab active">DETAIL SERVICES</button>
                </div>
                <div style="flex: 1; overflow: auto; background: white;">
                    <table class="list-grid">
                        <thead>
                            <tr>
                                <th style="width:30px;">CODE</th>
                                <th style="width:250px;">SERVICES NAME</th>
                                <th style="width:80px; text-align:right;">QTY</th>
                                <th style="width:100px; text-align:right;">PRICE</th>
                                <th style="width:120px; text-align:right;">AMOUNT</th>
                                <th style="width:300px;">DESCRIPTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(sub, sidx) in (selectedContract ? selectedContract.services : [])" :key="sidx">
                                <tr>
                                    <td x-text="sidx === 0 ? '▶' : ''"></td>
                                    <td x-text="sub.name" style="background:#f8fafc;"></td>
                                    <td style="text-align:right; background:#f8fafc;" x-text="sub.qty"></td>
                                    <td style="text-align:right; background:#f8fafc;" x-text="sub.price"></td>
                                    <td style="text-align:right; background:#f8fafc;" x-text="sub.amount"></td>
                                    <td x-text="sub.description"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <!-- Inner Grid Footer -->
                <div style="display:flex; padding: 10px 20px; border-top:1px solid var(--hr-border); background:white;">
                    <div style="margin-left: 300px; display:flex; gap:35px;">
                        <input type="text" class="form-input" style="width: 80px; text-align:right;" value="22" readonly>
                        <input type="text" class="form-input" style="width: 120px; text-align:right;" value="185.000" readonly>
                    </div>
                </div>
                <div class="grid-footer" style="padding: 2px 4px; min-height: 25px;">
                    <button class="pager-btn" @click="first()">|◀</button> 
                    <button class="pager-btn" @click="first()">◀◀</button> 
                    <button class="pager-btn" @click="prev()">◀</button>
                    <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIndex + 1"></span> of <span x-text="contracts.length"></span></span>
                    <button class="pager-btn" @click="next()">▶</button> 
                    <button class="pager-btn" @click="last()">▶▶</button> 
                    <button class="pager-btn" @click="last()">▶|</button>
                </div>
            </div>

            <!-- Footer Notes & Total Area -->
            <div style="padding: 10px; display: flex; justify-content: space-between; align-items: flex-start; background:#e2e8f0;">
                <div style="margin-left: 10px; margin-top: 5px;">
                    <div style="font-size: 0.75rem; font-weight: bold; color: #475569; margin-bottom: 2px; padding-left:2px; background:#e2e8f0; border:1px solid #cbd5e1; border-bottom:0; width:300px;">Note</div>
                    <div style="display:flex;">
                        <textarea style="width: 300px; height: 60px; border: 1px solid var(--hr-border); resize:none;"></textarea>
                        <div style="display:flex; flex-direction:column; border: 1px solid var(--hr-border); border-left:none; background:#f8fafc; justify-content:space-between;">
                           <span style="font-size:0.5rem; padding: 2px 4px; cursor:pointer; color:#64748b;">▲</span>
                           <span style="font-size:0.5rem; padding: 2px 4px; cursor:pointer; color:#64748b;">▼</span>
                        </div>
                    </div>
                </div>
                <div style="display:flex; flex-direction:column; gap:4px; margin-right: 10px;" x-show="selectedContract">
                    <div style="display:flex; justify-content:flex-end; align-items:center; gap:10px;">
                        <span style="font-size: 0.75rem; color: #475569;">Amount</span>
                        <div style="display:flex;">
                            <input type="text" class="form-input" style="width: 120px; text-align:right; border-right:none;" x-model="selectedContract.amount">
                            <div style="display:flex; flex-direction:column; border: 1px solid var(--hr-border); background:#f1f5f9;">
                               <span class="pager-btn" style="font-size:0.4rem; padding: 2px; border:none; line-height: 0.5;" @click="adjustValue('amount', 1000)">▲</span>
                               <span class="pager-btn" style="font-size:0.4rem; padding: 2px; border:none; line-height: 0.5;" @click="adjustValue('amount', -1000)">▼</span>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; justify-content:flex-end; align-items:center; gap:10px;">
                        <span style="font-size: 0.75rem; color: #475569;">TAX</span>
                        <input type="text" class="form-input" style="width: 135px; text-align:right;" x-model="selectedContract.tax_value">
                    </div>
                    <div style="display:flex; justify-content:flex-end; align-items:center; gap:10px;">
                        <span style="font-size: 0.75rem; color: #475569;">PPH</span>
                        <input type="text" class="form-input" style="width: 135px; text-align:right;" x-model="selectedContract.pph_23">
                    </div>
                    <div style="display:flex; justify-content:flex-end; align-items:center; gap:10px;">
                        <span style="font-size: 0.75rem; color: #475569;">Total</span>
                        <input type="text" class="form-input" style="width: 135px; text-align:right; font-weight:bold;" x-model="selectedContract.total" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- RECORDS LIST TAB -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''" style="background: white;">
            <div class="bar-top" style="border-bottom:none;">
                <span>Drag a column header here to group by that column</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #64748b;"><circle cx="11" cy="11" r="8"></circle> <line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <div style="flex: 1; overflow: auto; border-top:1px solid var(--hr-border);">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 120px;">ORDER DATE</th>
                            <th style="width: 120px;">EXP. DATE</th>
                            <th style="width: 80px;">USERNO</th>
                            <th style="width: 150px;">QUOTATION</th>
                            <th style="width: 100px;">REF</th>
                            <th style="width: 250px;">CUSTOMER NAME</th>
                            <th style="width: 60px;">CURR</th>
                            <th style="width: 60px; text-align:right;">RATE</th>
                            <th style="width: 120px; text-align:right;">AMOUNT</th>
                            <th style="width: 100px; text-align:right;">DISCOUNT</th>
                            <th style="width: 120px; text-align:right;">TAX VALUE</th>
                            <th style="width: 100px; text-align:right;">PPH 23</th>
                            <th style="width: 120px; text-align:right;">TOTAL</th>
                            <th style="width: 150px;">NOTE</th>
                            <th style="width: 80px;">AUDIT</th>
                            <th style="width: 80px;">DAYS</th>
                            <th style="width: 100px;">RECID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, idx) in contracts" :key="item.id">
                            <tbody :class="selectedContract && selectedContract.id === item.id ? 'selected' : ''">
                                <tr @click="selectContract(item.id)">
                                    <td>
                                        <span @click.stop="toggleExpand(item.id)" 
                                              style="display:inline-block; width:14px; height:14px; text-align:center; line-height:12px; border:1px solid #cbd5e1; cursor:pointer; color:#334155; margin-right:5px; font-size: 0.7rem; background:white;"
                                              x-text="expandedIds.includes(item.id) ? '-' : '+'">
                                        </span>
                                        <span x-text="item.order_date" :class="item.alert ? 'red-text' : ''"></span>
                                    </td>
                                    <td>
                                        <span x-text="item.exp_date" :class="item.alert ? 'red-text' : ''"></span>
                                    </td>
                                    <td>
                                        <span x-text="item.userno" :class="item.alert ? 'red-text' : ''"></span>
                                    </td>
                                    <td>
                                        <span x-text="item.quotation" :class="item.alert ? 'red-text' : ''"></span>
                                    </td>
                                    <td>
                                        <span x-text="item.ref" :class="item.alert ? 'red-text' : ''"></span>
                                    </td>
                                    <td>
                                        <span x-text="item.customer_name" :class="item.alert ? 'red-text' : ''"></span>
                                        <span style="color:#cbd5e1; float:right;">...</span>
                                    </td>
                                    <td>
                                        <span style="color:#cbd5e1;">...</span> <span x-text="item.curr" :class="item.alert ? 'red-text' : ''"></span>
                                    </td>
                                    <td x-text="item.rate" style="text-align:right;" :class="item.alert ? 'red-text' : ''"></td>
                                    <td x-text="item.amount" style="text-align:right;" :class="item.alert ? 'red-text' : ''"></td>
                                    <td x-text="item.discount" style="text-align:right;" :class="item.alert ? 'red-text' : ''"></td>
                                    <td x-text="item.tax_value" style="text-align:right;" :class="item.alert ? 'red-text' : ''"></td>
                                    <td x-text="item.pph_23" style="text-align:right;" :class="item.alert ? 'red-text' : ''"></td>
                                    <td x-text="item.total" style="text-align:right;" :class="item.alert ? 'red-text' : ''"></td>
                                    <td x-text="item.note" :class="item.alert ? 'red-text' : ''"></td>
                                    <td>
                                        <span style="color:#cbd5e1;">...</span> <span x-text="item.audit" :class="item.alert ? 'red-text' : ''"></span>
                                    </td>
                                    <td x-text="item.days" style="text-align:right;" :class="item.alert ? 'red-text' : ''"></td>
                                    <td x-text="item.recid" :class="item.alert ? 'red-text' : ''"></td>
                                </tr>
                                <!-- EXPANDED ROW -->
                                <tr x-show="expandedIds.includes(item.id)" style="background: #f1f5f9;">
                                    <td colspan="17" style="padding: 10px 20px;">
                                        <div style="background: white; border: 1px solid var(--hr-border);">
                                            <div style="padding: 4px 10px; border-bottom: 1px solid var(--hr-border); background:#f8fafc; font-size: 0.7rem; font-weight: bold; color: #475569;">
                                                DETAIL SERVICES FOR <span x-text="item.customer_name"></span>
                                            </div>
                                            <table class="list-grid" style="border:none;">
                                                <thead>
                                                    <tr style="background:#f1f5f9;">
                                                        <th style="width:100px;">CODE</th>
                                                        <th style="width:250px;">NAME</th>
                                                        <th style="width:60px; text-align:right;">QTY</th>
                                                        <th style="width:100px; text-align:right;">PRICE</th>
                                                        <th>DESCRIPTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="sub in item.services" :key="sub.code">
                                                        <tr>
                                                            <td x-text="sub.code"></td>
                                                            <td x-text="sub.name"></td>
                                                            <td x-text="sub.qty" style="text-align:right;"></td>
                                                            <td x-text="sub.price" style="text-align:right;"></td>
                                                            <td x-text="sub.description"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="grid-footer" style="padding: 4px; background: #e2e8f0; border-top: 1px solid var(--hr-border); justify-content:flex-end;">
                <div style="display:flex; gap:10px; padding-right:450px;">
                    <input type="text" class="form-input" style="width: 120px; text-align:right;" value="14.213.596" readonly>
                    <input type="text" class="form-input" style="width: 80px; text-align:right;" value="0" readonly>
                    <input type="text" class="form-input" style="width: 120px; text-align:right;" value="46.133,00" readonly>
                    <input type="text" class="form-input" style="width: 80px; text-align:right;" value="0,00" readonly>
                    <input type="text" class="form-input" style="width: 120px; text-align:right;" value="14.234.729" readonly>
                </div>
            </div>
             <div class="grid-footer" style="padding: 2px 4px; min-height: 25px; border-bottom: 1px solid var(--hr-border);">
                <button class="pager-btn" @click="first()">|◀</button> <button class="pager-btn" @click="first()">◀◀</button> <button class="pager-btn" @click="prev()">◀</button>
                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIndex + 1"></span> of <span x-text="contracts.length"></span></span>
                <button class="pager-btn" @click="next()">▶</button> <button class="pager-btn" @click="next()">▶▶</button> <button class="pager-btn" @click="last()">▶|</button>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function resourceServiceManager() {
        return {
            activeMainTab: 'detail',
            contracts: @json($contracts),
            selectedContract: null,
            expandedIds: [],

            get currentIndex() {
                if (!this.selectedContract) return -1;
                return this.contracts.findIndex(c => c.id === this.selectedContract.id);
            },

            init() {
                if (this.contracts && this.contracts.length > 0) {
                    this.selectContract(this.contracts[0].id);
                }
            },

            selectContract(id) {
                const contract = this.contracts.find(c => c.id === id);
                if (contract) {
                    this.selectedContract = JSON.parse(JSON.stringify(contract));
                    this.activeMainTab = 'detail';
                }
            },

            toggleExpand(id) {
                if (this.expandedIds.includes(id)) {
                    this.expandedIds = this.expandedIds.filter(i => i !== id);
                } else {
                    this.expandedIds.push(id);
                }
            },

            adjustValue(field, amount) {
                if (!this.selectedContract) return;
                let val = parseFloat(this.selectedContract[field].replace(/\./g, '').replace(',', '.')) || 0;
                val += amount;
                this.selectedContract[field] = val.toLocaleString('id-ID', { minimumFractionDigits: 0 });
            },

            first() { if (this.contracts.length > 0) this.selectContract(this.contracts[0].id); },
            prev() {
                const idx = this.currentIndex;
                if (idx > 0) this.selectContract(this.contracts[idx - 1].id);
            },
            next() {
                const idx = this.currentIndex;
                if (idx < this.contracts.length - 1) this.selectContract(this.contracts[idx + 1].id);
            },
            last() { if (this.contracts.length > 0) this.selectContract(this.contracts[this.contracts.length - 1].id); },

            lookup(type) {
                alert('Looking up ' + type + '...');
                // In a real app, this would open a modal or fetch data
            }
        }
    }
</script>
@endpush
