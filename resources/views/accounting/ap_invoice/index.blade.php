@extends('layouts.app')

@section('title', 'Supplier Invoice')

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
    .list-grid tr.subgrid td { padding: 0; background: #f1f5f9; border-bottom: 2px solid #94a3b8; }
    
    .grid-footer { display: flex; align-items: center; justify-content: space-between; padding: 4px; border-top: 1px solid var(--hr-border); border-bottom: 1px solid var(--hr-border); background: #e2e8f0; flex-shrink: 0; }
    .pager-group { display: flex; gap: 2px; }
    .pager-btn { background: white; border: 1px solid #cbd5e1; padding: 2px 6px; font-size: 0.65rem; cursor: pointer; color: #64748b; }
    .pager-btn:hover { background: #f1f5f9; }

    .detail-form-area { padding: 15px; background: #e2e8f0; border-bottom: 1px solid var(--hr-border); display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 4px; font-size: 0.75rem; }
    .form-label { width: 100px; text-align: right; margin-right: 10px; color: #475569; }
    .form-input { border: 1px solid var(--hr-border); padding: 4px 8px; border-radius: 0; font-size: 0.75rem; background: white; outline: none; }
    
    .bottom-section { display: flex; flex-direction: row; justify-content: space-between; flex-shrink: 0; background: #e2e8f0; border-top: 1px solid var(--hr-border); padding: 10px; }
    .note-area { display: flex; background: #e2e8f0; flex: 1; }
    .note-label { width: 40px; font-size: 0.75rem; color: #475569; margin-top:4px; }
    .note-input { width: 300px; height: 60px; border: 1px solid var(--hr-border); padding: 4px; font-size: 0.75rem; background: white; resize: none; margin-right: 5px; }
    .summary-cols { display: flex; flex-direction: column; width: 250px; font-size: 0.75rem; }
    .summary-row { display: flex; align-items: center; justify-content: flex-end; margin-bottom: 2px; }
    .summary-row label { width: 60px; text-align: right; margin-right: 8px; color: #475569; }
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

    .blue-block { width: 20px; height: 15px; background: blue; margin: 0 auto; cursor: pointer; }
    .check-cell { text-align: center; cursor: pointer; width: 35px; }
    .check-cell:hover { background: #e2e8f0; }

    .expander { width: 15px; height: 15px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #94a3b8; font-size: 10px; cursor: pointer; background: white; margin-right: 5px; }
    .expander:hover { background: #cbd5e1; }
</style>
@endpush

@section('content')
<div x-data="apInvoiceManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)" style="background: white; border: 1px solid var(--hr-border); margin: 10px; display: flex; flex-direction: column; height: calc(100vh - 80px);">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 8px; align-items: center;">
            <div style="display: flex; gap: 2px;">
                <div style="width: 6px; height: 6px; background: #ef4444;"></div>
                <div style="width: 6px; height: 6px; background: #eab308;"></div>
            </div>
            <span style="font-weight: 500; font-size: 0.8rem;">Supplier Invoice</span>
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
        <button class="main-tab" :class="activeMainTab === 'status' ? 'active' : ''" @click="activeMainTab = 'status'">INVOICE STATUS</button>
    </div>

    <div class="tab-content" style="border-top: none; flex: 1; min-height: 0;">
        
        <!-- =======================
             RECORD DETAIL TAB
             ======================= -->
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
                                        <div class="combo-option" :class="selectedInvoice.currency === opt ? 'selected' : ''" @click="selectedInvoice.currency = opt; saveCurrentChanges(); currencyOpen = false" x-text="opt"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-label">Supplier name</div>
                            <div class="custom-combobox" style="width: 250px; position: relative;" @click.away="supplierOpen = false">
                                <input type="text" class="combo-input" x-model="selectedInvoice.supplier_name" @focus="supplierOpen = true" @input="supplierOpen = true; saveCurrentChanges()" @change="saveCurrentChanges()">
                                <div class="combo-btn-wrapper" @click="supplierOpen = !supplierOpen">
                                    <div class="combo-arrow">▼</div>
                                </div>
                                <div class="combo-dropdown" x-show="supplierOpen" style="display: none;">
                                    <template x-for="opt in suppliers" :key="opt">
                                        <div class="combo-option" :class="selectedInvoice.supplier_name === opt ? 'selected' : ''" @click="selectedInvoice.supplier_name = opt; saveCurrentChanges(); supplierOpen = false" x-text="opt"></div>
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
                                        <div class="combo-option" :class="selectedInvoice.rate == opt ? 'selected' : ''" @click="selectedInvoice.rate = opt; saveCurrentChanges(); rateOpen = false" x-text="opt"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <div class="form-label" style="width: 100px; text-align: right;">Document Reff</div>
                            <input type="text" class="form-input" style="width: 150px; background: #f8fafc;" x-model="selectedInvoice.ref" @change="saveCurrentChanges()">
                        </div>
                        <div class="form-group" style="padding-left: 110px;">
                            <span style="font-size: 0.75rem; color: #64748b;" x-text="`${selectedInvoice.user_no}    ${selectedInvoice.link}    384376`"></span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: flex-end; padding-right: 20px;">
                    <button class="form-input" style="background:#f1f5f9; padding: 2px 10px; margin-right: 20px; font-size:0.75rem; border:1px solid #cbd5e1;">Button1</button>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b; text-transform: lowercase;">supplier invoice</div>
                </div>
            </div>
            
            <div style="flex: 1; overflow: auto; background: white;" x-show="selectedInvoice">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 20px;"></th>
                            <th style="width: 100px;">ACCOUNT</th>
                            <th style="width: 200px;">ACCOUNT DESC.</th>
                            <th style="width: 60px;">DEPT.</th>
                            <th style="width: 60px;">COST</th>
                            <th style="width: 110px; text-align: right;">AMOUNT</th>
                            <th style="width: 50px;">% PPH23</th>
                            <th style="width: 50px;">% PPN</th>
                            <th style="width: 110px; text-align: right;">TOTAL</th>
                            <th>DESC.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, idx) in selectedInvoice.items" :key="idx">
                            <tr class="row-data">
                                <td style="text-align: center; color: #64748b;">▶</td>
                                <td><input type="text" x-model="item.account" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.account_desc" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.dept" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.cost" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="number" x-model="item.amount" class="form-input" style="width: 100%; box-sizing: border-box; text-align: right; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="number" x-model="item.pph23" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="number" x-model="item.ppn" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="number" x-model="item.total" class="form-input" style="width: 100%; box-sizing: border-box; text-align: right; border:none; background:transparent;" readonly></td>
                                <td><input type="text" x-model="item.description" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                            </tr>
                        </template>
                        <!-- Add New Item Row -->
                        <tr style="background: white; border-top: 1px solid #cbd5e1;">
                            <td colspan="5" style="color: transparent;">.</td>
                            <td style="padding: 2px;"><input type="number" x-model="newItem.total" class="form-input" style="width: 100%; box-sizing: border-box; text-align: right;" placeholder="Amt..." @keydown.enter="addNewItem"></td>
                            <td colspan="3"></td>
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
            <div class="bottom-section" x-show="selectedInvoice">
                <div class="note-area">
                    <div class="note-label">Note</div>
                    <textarea class="note-input" x-model="selectedInvoice.note" @change="saveCurrentChanges()"></textarea>
                    <div style="width: 15px; background: #cbd5e1; display:flex; flex-direction:column; justify-content: space-between; height: 60px;">
                        <button style="height: 12px; background: #94a3b8; font-size:8px; line-height:12px; text-align:center; border:none; cursor:pointer; color:white;">▲</button>
                        <button style="height: 12px; background: #94a3b8; font-size:8px; line-height:12px; text-align:center; border:none; cursor:pointer; color:white;">▼</button>
                    </div>
                </div>
                <!-- Totals -->
                <div class="summary-cols">
                    <div class="summary-row">
                        <label>Amount</label>
                        <input type="text" class="form-input" :value="formatCurrency(selectedInvoice.amount_total)" readonly>
                    </div>
                    <div class="summary-row">
                        <label>PPH 23</label>
                        <input type="text" class="form-input" style="background: transparent; border: 1px solid transparent;" :value="formatCurrency(selectedInvoice.pph23)" @change="saveCurrentChanges()">
                    </div>
                    <div class="summary-row">
                        <label>Tax</label>
                        <input type="text" class="form-input" style="background: transparent; border: 1px solid transparent;" :value="formatCurrency(selectedInvoice.tax)" @change="saveCurrentChanges()">
                    </div>
                    <div class="summary-row">
                        <label>Total</label>
                        <input type="text" class="form-input" :value="formatCurrency(selectedInvoice.amount_total + Number(selectedInvoice.tax) - Number(selectedInvoice.pph23))" readonly>
                    </div>
                </div>
            </div>
            
            <div class="grid-footer">
                <div class="pager-group">
                    <button class="pager-btn" @click="firstRecord()" :disabled="currentIndex <= 0">|◀</button>
                    <button class="pager-btn" @click="prevRecord()" :disabled="currentIndex <= 0">◀</button>
                    <button class="pager-btn" @click="prevRecord()" :disabled="currentIndex <= 0">◀◀</button>
                    <span class="pager-btn" style="border:none; background:transparent; padding: 2px 10px;">Record <span x-text="currentIndex + 1"></span> of <span x-text="invoices.length"></span></span>
                    <button class="pager-btn" @click="nextRecord()" :disabled="currentIndex >= invoices.length - 1">▶▶</button>
                    <button class="pager-btn" @click="nextRecord()" :disabled="currentIndex >= invoices.length - 1">▶</button>
                    <button class="pager-btn" @click="lastRecord()" :disabled="currentIndex >= invoices.length - 1">▶|</button>
                </div>
                <div></div>
            </div>
        </div>

        <!-- =======================
             RECORDS LIST TAB
             ======================= -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''">
            <div class="bar-top">Drag a column header here to group by that column</div>
            <div style="flex: 1; overflow: auto;">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 25px;"></th>
                            <th style="width: 50px;">TYPE</th>
                            <th style="width: 120px;">REF</th>
                            <th style="width: 70px;">USERNO</th>
                            <th style="width: 80px;">DATE</th>
                            <th style="width: 80px;">DUEDATE</th>
                            <th style="width: 180px;">SUPPLIER NAME</th>
                            <th style="width: 50px;">CURR</th>
                            <th style="width: 50px;">RATE</th>
                            <th style="width: 60px;">LINK</th>
                            <th style="width: 100px; text-align: right;">TOTAL</th>
                            <th style="width: 70px; text-align: right;">PAID</th>
                            <th style="width: 60px; text-align: right;">DISC</th>
                            <th style="width: 60px; text-align: right;">PPH 23</th>
                            <th>NOTE</th>
                            <th style="width: 60px; text-align: right;">TAX</th>
                            <th style="width: 80px;">AUDIT</th>
                            <th style="width: 60px; text-align: right;">PREPAID</th>
                        </tr>
                    </thead>
                    <tbody style="border-bottom: none;">
                        <template x-for="(item, idx) in invoices" :key="item.id">
                            <tbody style="border-bottom: none;">
                                <!-- Parent row -->
                                <tr class="row-data" @click="selectInvoice(item.id)" :class="selectedInvoice && selectedInvoice.id === item.id ? 'selected' : ''" :style="selectedInvoice && selectedInvoice.id === item.id ? 'background:#e0b0ff; color:indigo;' : ''">
                                    <td style="text-align: center;">
                                        <span class="expander" @click.stop="toggleExpand(item.id)" x-text="expandedRows.includes(item.id) ? '-' : '+'"></span>
                                    </td>
                                    <td x-text="item.type"></td>
                                    <td x-text="item.ref" style="color:#d946ef;"></td>
                                    <td x-text="item.user_no" style="color:#d946ef;"></td>
                                    <td x-text="formatDate(item.date)" style="color:#d946ef;"></td>
                                    <td x-text="formatDate(item.duedate)" style="color:#d946ef;"></td>
                                    <td x-text="item.supplier_name" style="color:#d946ef; font-weight: 500;"></td>
                                    <td x-text="item.currency" style="color:#3b82f6;"></td>
                                    <td x-text="item.rate" style="color:#ef4444;"></td>
                                    <td x-text="item.link" style="color:#3b82f6;"></td>
                                    <td x-text="formatCurrency(item.amount_total)" style="text-align: right; color:#ef4444;"></td>
                                    <td x-text="formatCurrency(item.paid)" style="text-align: right; color:#3b82f6;"></td>
                                    <td x-text="formatCurrency(item.disc)" style="text-align: right; color:#ef4444;"></td>
                                    <td x-text="formatCurrency(item.pph23)" style="text-align: right; color:#ef4444;"></td>
                                    <td x-text="item.note" style="color:#3b82f6;"></td>
                                    <td x-text="formatCurrency(item.tax)" style="text-align: right; color:#ef4444;"></td>
                                    <td x-text="item.audit" style="color:#d946ef;"></td>
                                    <td x-text="formatCurrency(item.prepaid)" style="text-align: right; color:#ef4444;"></td>
                                </tr>
                                
                                <!-- Nested Subgrid, shown when directly expanded -->
                                <tr class="subgrid" x-show="expandedRows.includes(item.id)">
                                    <td></td>
                                    <td colspan="17" style="padding: 0; background: #f8fafc; border-bottom: 2px solid #cbd5e1;">
                                        <div style="background: white; border: 1px solid #cbd5e1; margin: 4px; border-radius: 2px;">
                                            <div style="display: flex; border-bottom: 1px solid #cbd5e1; background: #e2e8f0; font-size: 0.7rem;">
                                                <div style="padding: 4px 10px; background: white; border-right: 1px solid #cbd5e1; border-top: 2px solid #94a3b8;">Payable_Detail</div>
                                                <div style="padding: 4px 10px; border-right: 1px solid #cbd5e1; cursor: pointer;">Invoice_Monitor</div>
                                            </div>
                                            <table class="list-grid" style="border: none;">
                                                <thead>
                                                    <tr>
                                                        <th style="width:20px; background: #f8fafc;"></th>
                                                        <th style="background: #f8fafc;">ACCOUNT</th>
                                                        <th style="background: #f8fafc;">ACCOUNT DESCRIPTIONS</th>
                                                        <th style="background: #f8fafc;">DEPT.</th>
                                                        <th style="background: #f8fafc;">COST</th>
                                                        <th style="background: #f8fafc; text-align:right;">AMOUNT</th>
                                                        <th style="background: #f8fafc; text-align:right;">% PPH 23</th>
                                                        <th style="background: #f8fafc; text-align:right;">% PPN</th>
                                                        <th style="background: #f8fafc; text-align:right;">TOTAL</th>
                                                        <th style="background: #f8fafc;">DESCRIPTIONS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="line in item.items" :key="line.account">
                                                        <tr class="row-data">
                                                            <td style="color:#475569; text-align:center; background: white;">▶</td>
                                                            <td x-text="line.account" style="background: white;"></td>
                                                            <td x-text="line.account_desc" style="background: white;"></td>
                                                            <td x-text="line.dept" style="background: white;"></td>
                                                            <td x-text="line.cost" style="background: white;"></td>
                                                            <td x-text="formatCurrency(line.amount)" style="text-align:right; background: white;"></td>
                                                            <td x-text="formatCurrency(line.pph23)" style="text-align:right; background: white;"></td>
                                                            <td x-text="formatCurrency(line.ppn)" style="text-align:right; background: white;"></td>
                                                            <td x-text="formatCurrency(line.total)" style="text-align:right; background: white;"></td>
                                                            <td x-text="line.description" style="background: white;"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                            <div style="display:flex; justify-content:center; gap:30px; padding: 4px; background: #f8fafc; border-top: 1px solid #cbd5e1;">
                                                <input type="text" class="form-input" style="width:120px; text-align:right; border-radius:3px;" :value="formatCurrency(item.amount_total)" readonly>
                                                <input type="text" class="form-input" style="width:120px; text-align:right; border-radius:3px;" :value="formatCurrency(item.amount_total)" readonly>
                                                <div style="flex:1;"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <div class="grid-footer">
                <div class="pager-group">
                    <button class="pager-btn" @click="firstRecord()">|◀</button>
                    <button class="pager-btn" @click="prevRecord()">◀</button>
                    <button class="pager-btn" @click="prevRecord()">◀◀</button>
                    <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIndex + 1"></span> of <span x-text="invoices.length"></span></span>
                    <button class="pager-btn" @click="nextRecord()">▶▶</button>
                    <button class="pager-btn" @click="nextRecord()">▶</button>
                    <button class="pager-btn" @click="lastRecord()">▶|</button>
                </div>
                <div style="display: flex; gap: 4px;">
                    <input type="text" class="form-input" style="background: white; text-align: right; width: 150px; font-weight: bold;" :value="formatCurrency(135752601.4)" readonly>
                    <input type="text" class="form-input" style="background: white; text-align: right; width: 100px;" value="0" readonly>
                    <input type="text" class="form-input" style="background: white; text-align: right; width: 100px;" value="664.000" readonly>
                    <input type="text" class="form-input" style="background: white; text-align: right; width: 120px;" value="22.149.258" readonly>
                    <input type="text" class="form-input" style="background: white; text-align: right; width: 80px;" value="0" readonly>
                </div>
            </div>
        </div>
        
        <!-- =======================
             INVOICE STATUS TAB
             ======================= -->
        <div class="tab-pane" :class="activeMainTab === 'status' ? 'active' : ''">
            <div class="detail-form-area" x-show="selectedInvoice" style="background: #f1f5f9; padding: 10px;">
                <div style="display: flex; gap: 20px;">
                    <div>
                        <div class="form-group">
                            <div class="form-label">Date</div>
                            <input type="date" class="form-input" style="width: 140px; height: 25px; box-sizing: border-box;" x-model="selectedInvoice.date" readonly>
                        </div>
                        <div class="form-group">
                            <div class="form-label">Currency</div>
                            <input type="text" class="form-input" style="width: 140px; height: 25px; box-sizing: border-box;" x-model="selectedInvoice.currency" readonly>
                        </div>
                        <div class="form-group">
                            <div class="form-label">Principle / Supplier</div>
                            <input type="text" class="form-input" style="width: 250px; height: 25px; box-sizing: border-box;" x-model="selectedInvoice.supplier_name" readonly>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <div class="form-label" style="width: 60px;">Duedate</div>
                            <input type="date" class="form-input" style="width: 140px; height: 25px; box-sizing: border-box;" x-model="selectedInvoice.duedate" readonly>
                        </div>
                        <div class="form-group">
                            <div class="form-label" style="width: 60px;">Rate</div>
                            <input type="text" class="form-input" style="width: 140px; height: 25px; box-sizing: border-box;" x-model="selectedInvoice.rate" readonly>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <div class="form-label" style="width: 100px; text-align: left;">Document Reff</div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" style="width: 150px; background: #f8fafc;" x-model="selectedInvoice.ref" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="flex: 1; overflow: auto; background: white;" x-show="selectedInvoice && selectedInvoice.status_records">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 20px;"></th>
                            <th style="width: 250px;">DESCRIPTION</th>
                            <th class="check-cell" title="GA">GA</th>
                            <th class="check-cell" title="US">US</th>
                            <th class="check-cell" title="AP">AP</th>
                            <th class="check-cell" title="AC">AC</th>
                            <th class="check-cell" title="KC">KC</th>
                            <th class="check-cell" title="PF">PF</th>
                            <th class="check-cell" title="AP2">AP</th>
                            <th class="check-cell" title="DU/O">DU/O</th>
                            <th class="check-cell" title="AP3">AP</th>
                            <th class="check-cell" title="KF">KF</th>
                            <th class="check-cell" title="PF2">PF</th>
                            <th style="width: 150px;">NOTE</th>
                            <th style="width: 120px;">UPD. STATES</th>
                            <th style="width: 100px;">CHECKED BY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(st, idx) in selectedInvoice.status_records" :key="idx">
                            <tr class="row-data">
                                <td style="text-align: center; color: #64748b;"><span x-show="idx === 0">▶</span></td>
                                <td x-text="st.desc"></td>
                                <td class="check-cell" @click="st.ga = !st.ga; saveCurrentChanges()">
                                    <div x-show="st.ga" class="blue-block"></div>
                                </td>
                                <td class="check-cell" @click="st.us = !st.us; saveCurrentChanges()"><div x-show="st.us" class="blue-block"></div></td>
                                <td class="check-cell" @click="st.ap = !st.ap; saveCurrentChanges()"><div x-show="st.ap" class="blue-block"></div></td>
                                <td class="check-cell" @click="st.ac = !st.ac; saveCurrentChanges()"><div x-show="st.ac" class="blue-block"></div></td>
                                <td class="check-cell" @click="st.kc = !st.kc; saveCurrentChanges()"><div x-show="st.kc" class="blue-block"></div></td>
                                <td class="check-cell" @click="st.pf = !st.pf; saveCurrentChanges()"><div x-show="st.pf" class="blue-block"></div></td>
                                <td class="check-cell" @click="st.ap2 = !st.ap2; saveCurrentChanges()"><div x-show="st.ap2" class="blue-block"></div></td>
                                <td class="check-cell" @click="st.duo = !st.duo; saveCurrentChanges()"><div x-show="st.duo" class="blue-block"></div></td>
                                <td class="check-cell" @click="st.ap3 = !st.ap3; saveCurrentChanges()"><div x-show="st.ap3" class="blue-block"></div></td>
                                <td class="check-cell" @click="st.kf = !st.kf; saveCurrentChanges()"><div x-show="st.kf" class="blue-block"></div></td>
                                <td class="check-cell" @click="st.pf2 = !st.pf2; saveCurrentChanges()"><div x-show="st.pf2" class="blue-block"></div></td>
                                
                                <td><input type="text" x-model="st.note" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td x-text="st.upd_states" style="color:#64748b;"></td>
                                <td x-text="st.checked_by" style="color:#64748b;"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="grid-footer">
                <div class="pager-group">
                    <button class="pager-btn" style="border:none; background:transparent;">|◀◀  Record 1 of 1 ▶▶|</button>
                </div>
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
    function apInvoiceManager() {
        return {
            activeMainTab: 'detail',
            invoices: @json($apInvoices),
            selectedInvoice: null,
            newItem: { account: '400-99', account_desc: '', dept: '', cost: '', amount: 0, pph23: 0, ppn: 0, total: 0, description: '' },
            
            currencyOpen: false,
            supplierOpen: false,
            rateOpen: false,
            expandedRows: [1], // ID 1 expanded by default
            
            currencies: ['IDR', 'USD', 'SGD', 'EUR'],
            suppliers: ['REGIONAL OFFICE MEDAN', 'BPJS KESEHATAN 1', 'PT BINTANG KARYA SARANA', 'CV JASA SWADAYA UTAMA', 'Koperasi KITA'],
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
                    this.newItem = { account: '400-99', account_desc: '', dept: '', cost: '', amount: 0, pph23: 0, ppn: 0, total: 0, description: '' };
                }
            },

            toggleExpand(id) {
                if (this.expandedRows.includes(id)) {
                    this.expandedRows = this.expandedRows.filter(r => r !== id);
                } else {
                    this.expandedRows.push(id);
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
                return Number(val).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            saveCurrentChanges() {
                if (this.selectedInvoice) {
                    if(this.selectedInvoice.items) {
                        this.selectedInvoice.items.forEach(it => {
                            it.total = Number(it.amount || 0);
                        });
                        this.selectedInvoice.amount_total = this.selectedInvoice.items.reduce((acc, it) => acc + Number(it.total || 0), 0);
                    }
                    const idx = this.invoices.findIndex(r => r.id === this.selectedInvoice.id);
                    if (idx !== -1) {
                        this.invoices[idx] = JSON.parse(JSON.stringify(this.selectedInvoice));
                    }
                }
            },

            addNewItem() {
                if(this.newItem.total <= 0 && this.newItem.description === '') return;
                if(!this.selectedInvoice.items) this.selectedInvoice.items = [];
                
                this.selectedInvoice.items.push({
                    account: '400-00',
                    account_desc: this.newItem.description.substring(0, 15) || 'Cost',
                    dept: '05C',
                    cost: '006',
                    amount: Number(this.newItem.total),
                    pph23: 0,
                    ppn: 0,
                    total: Number(this.newItem.total),
                    description: this.newItem.description
                });
                this.newItem = { account: '400-99', account_desc: '', dept: '', cost: '', amount: 0, pph23: 0, ppn: 0, total: 0, description: '' };
                this.saveCurrentChanges();
            },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.createNew(); break;
                    case 'save': 
                        this.saveCurrentChanges(); 
                        showToast('Invoice saved', 'success'); 
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
                const newId = this.invoices.length ? Math.max(...this.invoices.map(i => i.id)) + 1 : 1;
                const newReq = {
                    id: newId,
                    type: 'VI',
                    ref: `New Ref ${newId}`,
                    user_no: 'NEW',
                    link: '',
                    date: new Date().toISOString().split('T')[0],
                    duedate: new Date().toISOString().split('T')[0],
                    supplier_name: '',
                    currency: 'IDR',
                    rate: 1.0,
                    note: '',
                    amount_total: 0, paid:0, disc:0, pph23:0, tax:0, audit:'', prepaid:0,
                    items: [],
                    status_records: [
                        {desc: 'Invoice diterima dari vendor', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', upd_states: '', checked_by: ''},
                        {desc: 'Invoice di serahkan ke user', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', upd_states: '', checked_by: ''}
                    ]
                };
                this.invoices.push(newReq);
                this.selectInvoice(newId);
                this.activeMainTab = 'detail';
                showToast('New supplier invoice created', 'success');
            },

            deleteRecord() {
                if (!this.selectedInvoice) return;
                if (confirm('Delete this invoice?')) {
                    this.invoices = this.invoices.filter(r => r.id !== this.selectedInvoice.id);
                    if (this.invoices.length > 0) {
                        this.selectInvoice(this.invoices[0].id);
                    } else {
                        this.selectedInvoice = null;
                        this.createNew();
                    }
                    showToast('Invoice deleted', 'success');
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
