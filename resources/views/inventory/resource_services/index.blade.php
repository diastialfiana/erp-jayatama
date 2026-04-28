@extends('layouts.app')

@section('title', 'Resource Services')

@push('styles')
<style>
    :root { --hr-border: #999; --hr-primary: #1e293b; --hr-accent: #2563eb; }
    .fa-window { background: #f0f0f0; border: 1px solid #999; overflow: hidden; display: flex; flex-direction: column; height: calc(100vh - 120px); box-shadow: 2px 2px 5px rgba(0,0,0,0.2); color: #000; }
    .window-title-bar { background: linear-gradient(to bottom, #4f78b1, #3a5a8f); color: white; padding: 4px 8px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; font-weight: bold; }
    .main-tabs { display: flex; background: #f0f0f0; padding: 4px 4px 0 4px; border-bottom: 1px solid #999; flex-shrink: 0; }
    .main-tab { padding: 3px 10px; font-size: 11px; border: 1px solid #999; border-bottom: none; background: #e0e0e0; cursor: pointer; margin-right: 2px; border-radius: 3px 3px 0 0; text-transform: uppercase; }
    .main-tab.active { background: #fff; font-weight: bold; margin-bottom: -1px; }
    .tab-content { display: flex; flex-direction: column; flex: 1; overflow: hidden; border-top: none; min-height: 0; margin-top: 0; padding: 0; }
    .tab-pane { display: none; flex: 1; flex-direction: column; overflow: hidden; background: #f0f0f0; }
    .tab-pane.active { display: flex; }
    .list-grid { width: 100%; border-collapse: collapse; font-size: 11px; background: white; }
    .list-grid th { background: #e0e0e0; color: #333; padding: 2px 4px; text-align: left; font-weight: bold; border: 1px solid #999; white-space: nowrap; font-size: 11px; position: sticky; top: 0; }
    .list-grid td { padding: 2px 4px; border: 1px solid #ddd; white-space: nowrap; color: #000; font-size: 11px; }
    .list-grid tr:hover td { background: #eef3f8; cursor: pointer; }
    .list-grid tr.selected td { background: #b8cce4; }
    .red-text { color: red; }
    .grid-footer { display: flex; align-items: center; padding: 2px 4px; border-top: 1px solid #999; background: #f0f0f0; gap: 2px; flex-shrink: 0; }
    .pager-btn { background: white; border: 1px solid #999; padding: 1px 5px; font-size: 11px; cursor: pointer; color: #333; min-width: 22px; }
    .pager-btn:hover { background: #e0e0e0; }
    .detail-form-area { padding: 4px 8px; background: #f0f0f0; border-bottom: 1px solid #999; display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 2px; font-size: 11px; }
    .form-label { width: 90px; text-align: right; margin-right: 5px; color: #333; font-size: 11px; flex-shrink: 0; }
    .form-input { border: 1px solid #999; padding: 1px 3px; border-radius: 0; font-size: 11px; background: white; height: 20px; box-sizing: border-box; }
    .form-select { border: 1px solid #999; padding: 1px 3px; border-radius: 0; font-size: 11px; background: white; height: 20px; }
    .bordered-panel { border: 1px solid #999; display: flex; flex-direction: column; background: white; flex: 1; overflow: hidden; margin: 3px 4px; }
    .detail-tabs { display: flex; background: #e0e0e0; border-bottom: 1px solid #999; flex-shrink: 0; }
    .detail-tab { padding: 2px 10px; font-size: 11px; border: 1px solid #999; border-bottom: none; background: #e0e0e0; cursor: pointer; margin-right: 2px; border-radius: 2px 2px 0 0; text-transform: uppercase; }
    .detail-tab.active { background: white; font-weight: bold; }
    .bar-top { padding: 2px 6px; font-size: 11px; color: #555; background: #f0f0f0; border-bottom: 1px solid #ccc; flex-shrink: 0; display: flex; justify-content: space-between; align-items: center; }
</style>
@endpush

@section('content')
<div class="fa-window" x-data="resourceServiceManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 10px; align-items: center;">
            <div style="width: 28px; height: 28px; background: #eff6ff; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span style="font-weight: 700; font-size: 13px; color: #1e293b;">Resource & Services Management</span>
        </div>
        <div style="display: flex; gap: 8px; align-items:center;">
            <div style="display:flex; gap:2px; margin-right: 10px;">
                <button class="pager-btn" @click="navigate('prev')">◁</button>
                <button class="pager-btn" @click="navigate('next')">▷</button>
            </div>
            <button class="hamburger" style="padding: 4px; color: #94a3b8;"><svg viewBox="0 0 24 24" style="width:16px; height:16px;"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"/></svg></button>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Main Navigation Tabs -->
    <div class="main-tabs" style="width:100%;">
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">RECORD DETAIL</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">RECORDS LIST</button>
    </div>

    <div class="tab-content" style="border-top: none; flex: 1;">
        
        <!-- RECORD DETAIL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''">
            <!-- Header forms -->
            <!-- Header forms -->
            <div class="detail-form-area" style="padding: 10px; position: relative;" x-show="selectedContract">
                <div style="display:flex; justify-content: space-between; width: 100%;">
                    <!-- Form fields -->
                    <div style="display: flex; flex-direction: column; gap: 2px;">
                        <!-- Row 1: Date & BAP. Reff -->
                        <div style="display: flex; align-items: center;">
                            <div style="width: 90px; text-align: right; margin-right: 5px; font-size: 11px;">Date</div>
                            <select class="form-select" style="width: 120px;" x-model="selectedContract.order_date">
                                <option x-text="selectedContract.order_date"></option>
                            </select>
                            <div style="width: 60px; text-align: right; margin-right: 5px; font-size: 11px;">BAP. Reff</div>
                            <input type="text" class="form-input" style="width: 150px;" x-model="selectedContract.ref">
                        </div>

                        <!-- Row 2: Customer -->
                        <div style="display: flex; align-items: center;">
                            <div style="width: 90px; text-align: right; margin-right: 5px; font-size: 11px;">Customer</div>
                            <div style="display: flex; align-items: center; background: white; border: 1px solid var(--hr-border); width: 275px; height: 20px;">
                                <input type="text" style="flex: 1; border: none; font-size: 11px; padding: 0 4px; outline: none; background: transparent;" 
                                       x-model="customerNameInput" list="customer-names" @change="onCustomerSelect">
                                <datalist id="customer-names">
                                    <template x-for="cust in customers" :key="cust">
                                        <option :value="cust"></option>
                                    </template>
                                </datalist>
                                <span style="background: #e2e8f0; padding: 0 4px; border-left: 1px solid var(--hr-border); color: #64748b; font-size: 10px; cursor:pointer; height: 100%; display:flex; align-items:center;" @click="lookup('Customer')">...</span>
                            </div>
                        </div>

                        <!-- Row 3: From Quotation & Exp. Date & Set as Customer Budget -->
                        <div style="display: flex; align-items: center;">
                            <div style="width: 90px; text-align: right; margin-right: 5px; font-size: 11px;">From Quotation</div>
                            <div style="display:flex;">
                                <input type="text" class="form-input" style="width: 120px;" x-model="selectedContract.quotation">
                                <button class="pager-btn" style="border-left:none; height:20px; display:flex; align-items:center; justify-content:center; padding: 0 4px;" @click="lookup('Quotation')">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </div>
                            <div style="width: 60px; text-align: right; margin-right: 5px; font-size: 11px;">Exp. Date</div>
                            <select class="form-select" style="width: 90px;" x-model="selectedContract.exp_date">
                                <option x-text="selectedContract.exp_date"></option>
                            </select>
                            
                            <label style="margin-left: 30px; display:flex; align-items:center; gap:5px; font-size:11px; color:#333;">
                                Set as Customer Budget <input type="checkbox" checked style="margin:0; width:13px; height:13px;">
                            </label>
                        </div>

                        <!-- Row 4: Document Copy -->
                        <div style="display: flex; align-items: center;">
                            <div style="width: 90px; text-align: right; margin-right: 5px; font-size: 11px;">Document Copy</div>
                            <div style="display:flex;">
                                <input type="text" class="form-input" style="width: 250px;" x-model="selectedContract.recid">
                                <button class="pager-btn" style="border-left:none; height:20px; display:flex; align-items:center; justify-content:center; padding: 0 4px;" @click="lookup('File')">📁</button>
                                <button class="pager-btn" style="border-left:none; height:20px; display:flex; align-items:center; justify-content:center; padding: 0 4px;" @click="lookup('Search File')">🔍</button>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; flex-direction:column; align-items:flex-end; justify-content:space-between;">
                        <div style="display:flex; gap: 40px; font-size: 11px; color:#333; padding-right: 20px;">
                            <span x-text="selectedContract.userno"></span>
                            <span x-text="selectedContract.id"></span>
                        </div>
                        <div style="font-size: 18px; font-weight: bold; color: #333; padding-right: 5px; margin-bottom: 5px; text-transform: lowercase;">
                            rembursment
                        </div>
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
                                    <td style="text-align: center; font-size: 0.5rem; color: #475569;">
                                        <span x-show="sidx === selectedItemIdx">▶</span>
                                        <button @click="removeServiceItem(sidx)" x-show="sidx === selectedItemIdx" style="background:#ef4444; color:white; border:none; border-radius:2px; font-size:8px; padding:2px 4px; cursor:pointer;" title="Remove Item">✕</button>
                                    </td>
                                    <td><input type="text" x-model="sub.name" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                    <td><input type="number" x-model="sub.qty" class="form-input" style="width: 100%; box-sizing: border-box; text-align: center; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                    <td><input type="number" x-model="sub.price" class="form-input" style="width: 100%; box-sizing: border-box; text-align: center; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                    <td><input type="number" x-model="sub.amount" class="form-input" style="width: 100%; box-sizing: border-box; text-align: center; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                    <td><input type="text" x-model="sub.description" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                </tr>
                            </template>
                            <!-- New Item Row -->
                            <tr style="background: #f8fafc; border-top: 2px solid #cbd5e1;">
                                <td style="text-align: center; color: #10b981; font-weight: bold; cursor: pointer;" @click="addNewServiceItem()" title="Add item">+</td>
                                <td style="padding: 2px;">
                                    <input type="text" x-model="newServiceItem.name" class="form-input" style="width: 100%; box-sizing: border-box;" placeholder="Services name..." @keydown.enter="addNewServiceItem">
                                </td>
                                <td style="padding: 2px;"><input type="number" x-model="newServiceItem.qty" class="form-input" style="width: 100%; box-sizing: border-box; text-align: center;" @keydown.enter="addNewServiceItem"></td>
                                <td style="padding: 2px;"><input type="number" x-model="newServiceItem.price" class="form-input" style="width: 100%; box-sizing: border-box; text-align: center;" placeholder="Price..." @keydown.enter="addNewServiceItem"></td>
                                <td style="padding: 2px;"><input type="number" x-model="newServiceItem.amount" class="form-input" style="width: 100%; box-sizing: border-box; text-align: center;" placeholder="Amount..." @keydown.enter="addNewServiceItem"></td>
                                <td style="padding: 2px; display:flex;">
                                    <input type="text" x-model="newServiceItem.description" class="form-input" style="flex:1; box-sizing: border-box;" placeholder="Desc..." @keydown.enter="addNewServiceItem">
                                    <button @click="addNewServiceItem()" style="background:#2563eb; color:white; border:none; padding: 2px 8px; cursor:pointer;" title="Add Item">Add</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Inner Grid Footer -->
                <!-- Inner Grid Footer -->
                <div style="display:flex; padding: 4px; border-top:1px solid var(--hr-border); background:white;">
                    <div style="margin-left: 280px; display:flex; gap: 35px;">
                        <input type="text" class="form-input" style="width: 60px; text-align:center;" value="22" readonly>
                        <input type="text" class="form-input" style="width: 100px; text-align:right;" value="185.000" readonly>
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
            <!-- Footer Notes & Total Area -->
            <div style="padding: 4px 10px; display: flex; justify-content: space-between; align-items: flex-start; background:#f0f0f0;">
                <div style="margin-left: 10px; margin-top: 5px; background: #dcdcdc; padding: 2px; border: 1px solid var(--hr-border); width: 300px;">
                    <div style="font-size: 11px; font-weight: bold; color: #333; margin-bottom: 2px;">Note</div>
                    <div style="display:flex;">
                        <textarea style="width: 100%; height: 50px; border: 1px solid var(--hr-border); resize:none; font-size:11px;"></textarea>
                        <div style="display:flex; flex-direction:column; border: 1px solid var(--hr-border); border-left:none; background:#f8fafc; justify-content:space-between; width: 16px;">
                           <span style="font-size:8px; display:flex; align-items:center; justify-content:center; flex:1; cursor:pointer; color:#333; border-bottom:1px solid #999;">▲</span>
                           <span style="font-size:8px; display:flex; align-items:center; justify-content:center; flex:1; cursor:pointer; color:#333;">▼</span>
                        </div>
                    </div>
                </div>
                <div style="display:flex; flex-direction:column; gap:2px; margin-right: 10px;" x-show="selectedContract">
                    <div style="display:flex; justify-content:flex-end; align-items:center; gap:5px;">
                        <span style="font-size: 11px; color: #333; width: 60px; text-align:right;">Amount</span>
                        <div style="display:flex;">
                            <input type="text" class="form-input" style="width: 100px; text-align:right; border-right:none;" x-model="selectedContract.amount">
                            <div style="display:flex; flex-direction:column; border: 1px solid var(--hr-border); background:#e0e0e0; width: 16px;">
                               <span class="pager-btn" style="font-size:8px; padding:0; display:flex; align-items:center; justify-content:center; flex:1; border:none; border-bottom:1px solid #999;" @click="adjustValue('amount', 1000)">▲</span>
                               <span class="pager-btn" style="font-size:8px; padding:0; display:flex; align-items:center; justify-content:center; flex:1; border:none;" @click="adjustValue('amount', -1000)">▼</span>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; justify-content:flex-end; align-items:center; gap:5px;">
                        <span style="font-size: 11px; color: #333; width: 60px; text-align:right;">TAX</span>
                        <div style="display:flex;">
                            <input type="text" class="form-input" style="width: 100px; text-align:right; border-right:none;" x-model="selectedContract.tax_value">
                            <div style="display:flex; flex-direction:column; border: 1px solid var(--hr-border); background:#e0e0e0; width: 16px;">
                               <span class="pager-btn" style="font-size:8px; padding:0; display:flex; align-items:center; justify-content:center; flex:1; border:none; border-bottom:1px solid #999;">▲</span>
                               <span class="pager-btn" style="font-size:8px; padding:0; display:flex; align-items:center; justify-content:center; flex:1; border:none;">▼</span>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; justify-content:flex-end; align-items:center; gap:5px;">
                        <span style="font-size: 11px; color: #333; width: 60px; text-align:right;">PPH</span>
                        <div style="display:flex;">
                            <input type="text" class="form-input" style="width: 100px; text-align:right; border-right:none;" x-model="selectedContract.pph_23">
                            <div style="display:flex; flex-direction:column; border: 1px solid var(--hr-border); background:#e0e0e0; width: 16px;">
                               <span class="pager-btn" style="font-size:8px; padding:0; display:flex; align-items:center; justify-content:center; flex:1; border:none; border-bottom:1px solid #999;">▲</span>
                               <span class="pager-btn" style="font-size:8px; padding:0; display:flex; align-items:center; justify-content:center; flex:1; border:none;">▼</span>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; justify-content:flex-end; align-items:center; gap:5px;">
                        <span style="font-size: 11px; color: #333; width: 60px; text-align:right;">Total</span>
                        <input type="text" class="form-input" style="width: 116px; text-align:right; font-weight:bold;" x-model="selectedContract.total" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- RECORDS LIST TAB -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''">
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
            customers: ['PT JAYATAMA', 'PT MAJU SELALU', 'PT ANGIN RIBUT'],
            customerNameInput: '',
            selectedContract: null,
            expandedIds: [],
            selectedItemIdx: 0,
            newServiceItem: { code: 'SRV-NEW', name: '', qty: 1, price: 0, amount: 0, description: '' },

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
                    this.selectedItemIdx = 0;
                    this.customerNameInput = this.selectedContract.customer_name;
                }
            },

            onCustomerSelect() {
                if (this.selectedContract) {
                    this.selectedContract.customer_name = this.customerNameInput;
                    this.saveCurrentChanges();
                }
            },

            addNewServiceItem() {
                if(!this.newServiceItem.name) return;
                if(!this.selectedContract.services) this.selectedContract.services = [];
                
                this.selectedContract.services.push({...this.newServiceItem});
                this.newServiceItem = { code: 'SRV-NEW', name: '', qty: 1, price: 0, amount: 0, description: '' };
                this.saveCurrentChanges();
            },

            removeServiceItem(idx) {
                if(confirm('Remove this service?')) {
                    this.selectedContract.services.splice(idx, 1);
                    this.saveCurrentChanges();
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
            },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.createNew(); break;
                    case 'save': 
                        this.saveCurrentChanges(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.selectedContract, 'Contract_' + (this.selectedContract?.contract_no || 'Draft') + '.json');
                        }
                        showToast('Contract saved to file', 'success'); 
                        break;
                    case 'delete': this.deleteRecord(); break;
                    case 'refresh': this.refreshData(); break;
                    case 'preview': window.print(); break;
                    case 'find': this.activeMainTab = 'list'; this.$nextTick(() => { if(typeof erpFindOpen === 'function') erpFindOpen(); }); break;
                    case 'undo': this.undoChanges(); break;
                    case 'save-as': 
                        this.saveAsNew(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.selectedContract, 'Contract_Copy.json');
                        }
                        break;
                    case 'edit': this.focusFirstField(); break;
                    case 'barcode': showToast('Generating barcode...', 'info'); break;
                    case 'resend': showToast('Re-sending document...', 'info'); break;
                }
            },

            undoChanges() {
                if (this.selectedContract && confirm('Revert all unsaved changes for this contract?')) {
                    this.selectContract(this.selectedContract.id);
                    showToast('Changes reverted', 'info');
                }
            },

            saveAsNew() {
                if (!this.selectedContract) return;
                const clone = JSON.parse(JSON.stringify(this.selectedContract));
                clone.id = this.contracts.length + 1;
                clone.contract_no += ' (COPY)';
                this.contracts.push(clone);
                this.selectContract(clone.id);
                showToast('Contract duplicated', 'success');
            },

            focusFirstField() {
                this.activeMainTab = 'detail';
                this.$nextTick(() => {
                    const firstInput = document.querySelector('.tab-pane.active input:not([readonly])');
                    if (firstInput) firstInput.focus();
                });
            },

            createNew() {
                const newId = this.contracts.length + 1;
                const newContract = {
                    id: newId,
                    type: 'GENERAL',
                    client_name: 'NEW CLIENT',
                    contract_no: `CTR/${new Date().getFullYear()}/${String(newId).padStart(4, '0')}`,
                    start_date: new Date().toISOString().split('T')[0],
                    end_date: '',
                    value: '0',
                    tax: '0',
                    total: '0',
                    status: 'DRAFT',
                    note: '',
                    items: []
                };
                this.contracts.push(newContract);
                this.selectContract(newId);
                this.activeMainTab = 'detail';
                showToast('New contract created', 'success');
            },

            saveCurrentChanges() {
                if (this.selectedContract) {
                    const idx = this.contracts.findIndex(c => c.id === this.selectedContract.id);
                    if (idx !== -1) {
                        this.contracts[idx] = JSON.parse(JSON.stringify(this.selectedContract));
                    }
                    showToast('Contract saved locally', 'success');
                }
            },

            deleteRecord() {
                if (!this.selectedContract) return;
                if (confirm('Are you sure you want to delete this contract?')) {
                    const idx = this.contracts.findIndex(c => c.id === this.selectedContract.id);
                    if (idx !== -1) {
                        this.contracts.splice(idx, 1);
                        if (this.contracts.length > 0) {
                            const newIdx = Math.min(idx, this.contracts.length - 1);
                            this.selectContract(this.contracts[newIdx].id);
                        } else {
                            this.selectedContract = null;
                        }
                    }
                    showToast('Contract deleted', 'success');
                }
            },

            refreshData() {
                showToast('Data refreshed', 'success');
                window.location.reload();
            }
        }
    }
</script>
@endpush
