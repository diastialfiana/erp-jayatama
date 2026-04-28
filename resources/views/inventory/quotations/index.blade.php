@extends('layouts.app')

@section('title', 'Quotations')

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
    .grid-footer { display: flex; align-items: center; padding: 2px 4px; border-top: 1px solid #999; background: #f0f0f0; gap: 2px; flex-shrink: 0; }
    .pager-btn { background: white; border: 1px solid #999; padding: 1px 5px; font-size: 11px; cursor: pointer; color: #333; min-width: 22px; }
    .pager-btn:hover { background: #e0e0e0; }
    .detail-form-area { padding: 4px 8px; background: #f0f0f0; border-bottom: 1px solid #999; display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 2px; font-size: 11px; }
    .form-label { width: 90px; text-align: right; margin-right: 5px; color: #333; font-size: 11px; flex-shrink: 0; }
    .form-input { border: 1px solid #999; padding: 1px 3px; border-radius: 0; font-size: 11px; background: white; height: 20px; box-sizing: border-box; }
    .form-select { border: 1px solid #999; padding: 1px 3px; border-radius: 0; font-size: 11px; background: white; height: 20px; }
    .vertical-label { writing-mode: vertical-rl; transform: rotate(180deg); text-align: center; font-size: 10px; padding: 6px 3px; background: #e8e8e8; border-right: 1px solid #999; color: #333; flex-shrink: 0; }
    .bordered-panel { border: 1px solid #999; display: flex; background: white; flex: 1; overflow: hidden; margin: 3px 4px 0 4px; }
    .panel-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .bar-top { padding: 2px 6px; font-size: 11px; color: #555; background: #f0f0f0; border-bottom: 1px solid #ccc; flex-shrink: 0; display: flex; justify-content: space-between; align-items: center; }
</style>
@endpush

@section('content')
<div class="fa-window" x-data="quotationManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)">
    <!-- Title Bar -->
    <div class="window-title-bar">
        <span>Quotations</span>
        <div style="display:flex;gap:2px;">
            <button style="background:#e0e0e0;border:1px solid #999;width:16px;height:14px;font-size:9px;cursor:pointer;">_</button>
            <button style="background:#e0e0e0;border:1px solid #999;width:16px;height:14px;font-size:9px;cursor:pointer;">□</button>
            <button style="background:#cc0000;border:1px solid #999;width:16px;height:14px;font-size:9px;color:white;cursor:pointer;">✕</button>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Main Navigation Tabs -->
    <div class="main-tabs" style="background: #e2e8f0; border-bottom: 1px solid var(--hr-border); padding-left: 10px; border-radius: 0; width: 100%;">
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">QUOTATION DETAIL</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">QUOTATION LIST</button>
        <button class="main-tab" :class="activeMainTab === 'detail_list' ? 'active' : ''" @click="activeMainTab = 'detail_list'">DETAIL QUOTATION</button>
    </div>

    <div class="tab-content" style="border-top: none; flex: 1;">
        
        <!-- RECORD DETAIL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''">
            <!-- Header forms -->
            <div class="detail-form-area" style="justify-content: flex-start; gap: 80px; padding: 4px 15px; background: #f0f0f0;">
                <!-- Left Column -->
                <div style="display: flex; flex-direction: column; align-items: center; gap: 4px; width: 220px;">
                    <!-- Date -->
                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                        <div style="font-weight: bold; font-size: 10px; color: #333; margin-bottom: 1px;">DATE</div>
                        <input type="date" class="form-input" style="width: 130px; text-align: center; border: 1px solid #999; height: 18px; font-size: 11px;" x-model="formData.date">
                    </div>

                    <!-- Customer Name -->
                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                        <div style="font-weight: bold; font-size: 10px; color: #333; text-align: center; margin-bottom: 1px; line-height: 1;">CUSTOMER<br>NAME</div>
                        <div style="display: flex; align-items: center; background: white; border: 1px solid #999; width: 100%; height: 18px;">
                            <input type="text" style="flex: 1; border: none; font-size: 11px; padding: 0 4px; outline: none; background: transparent;" 
                                   x-model="customerNameInput" list="customer-names" @change="onCustomerSelect">
                            <span style="background: #e2e8f0; padding: 0 4px; border-left: 1px solid #999; color: #64748b; font-size: 9px; cursor:pointer; height: 100%; display:flex; align-items:center;">▼</span>
                        </div>
                    </div>

                    <!-- Attentions 1 -->
                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                        <div style="font-weight: bold; font-size: 10px; color: #333; margin-bottom: 1px;">ATTENTIONS 1</div>
                        <input type="text" class="form-input" style="width: 100%; border: 1px solid #999; padding: 0 4px; height: 18px; font-size: 11px;" x-model="formData.attn1">
                    </div>

                    <!-- Attentions 2 -->
                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                        <div style="font-weight: bold; font-size: 10px; color: #333; margin-bottom: 1px;">ATTENTIONS 2</div>
                        <input type="text" class="form-input" style="width: 100%; border: 1px solid #999; padding: 0 4px; height: 18px; font-size: 11px;" x-model="formData.attn2">
                    </div>

                    <!-- Select Estimation -->
                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                        <div style="font-weight: bold; font-size: 10px; color: #333; text-align: center; margin-bottom: 1px; line-height: 1;">SELECT<br>ESTIMATION</div>
                        <div style="display:flex; width: 100%; gap: 1px; height: 18px;">
                            <div style="display: flex; align-items: center; background: white; border: 1px solid #999; flex: 1;">
                                <select style="flex: 1; border: none; font-size: 11px; padding: 0 4px; outline: none; appearance: none; background: transparent; height: 100%;" x-model="formData.estimationCode" @change="loadEstimationItems()">
                                    <option value="">Select Estimation</option>
                                    <template x-for="est in estimations" :key="est.code">
                                        <option :value="est.code" x-text="est.code + ' - ' + est.name"></option>
                                    </template>
                                </select>
                                <span style="background: #e2e8f0; padding: 0 4px; border-left: 1px solid #999; color: #64748b; font-size: 9px; pointer-events: none; height: 100%; display:flex; align-items:center;">▼</span>
                            </div>
                            <button style="border: 1px solid #999; background: #fcd34d; width: 18px; height: 18px; display: flex; justify-content: center; align-items: center; cursor: pointer; padding:0; font-size: 10px;" @click="selectRandomEstimation()">📁</button>
                            <button style="border: 1px solid #999; background: white; width: 18px; height: 18px; display: flex; justify-content: center; align-items: center; cursor: pointer; padding:0; font-size: 10px;" @click="formData.estimationCode = ''; loadEstimationItems()">✕</button>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div style="display: flex; flex-direction: column; align-items: center; gap: 4px; width: 200px;">
                    <!-- Sales Name -->
                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                        <div style="font-weight: bold; font-size: 10px; color: #333; margin-bottom: 1px;">SALES NAME</div>
                        <div style="display:flex; align-items: center; gap: 4px; height: 18px;">
                            <div style="display: flex; align-items: center; background: white; border: 1px solid #999; width: 130px; height: 100%;">
                                <select style="flex: 1; border: none; font-size: 11px; padding: 0 4px; outline: none; appearance: none; background: transparent; height: 100%;" x-model="formData.salesName">
                                    <option value="">Select Sales</option>
                                    <template x-for="name in salesNames" :key="name">
                                        <option :value="name" x-text="name"></option>
                                    </template>
                                </select>
                                <span style="background: #e2e8f0; padding: 0 4px; border-left: 1px solid #999; color: #64748b; font-size: 9px; pointer-events: none; height: 100%; display:flex; align-items:center;">▼</span>
                            </div>
                            <input type="checkbox" style="margin: 0; width: 12px; height: 12px; border: 1px solid #999; border-radius:0; cursor:pointer;">
                        </div>
                    </div>

                    <!-- PO. No. -->
                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                        <div style="font-weight: bold; font-size: 10px; color: #333; margin-bottom: 1px;">PO. NO.</div>
                        <input type="text" class="form-input" style="width: 130px; border: 1px solid #999; padding: 0 4px; height: 18px; font-size: 11px;" x-model="formData.poNo">
                    </div>
                </div>
            </div>

            <!-- Grid Layout Panel -->
            <div style="flex: 1; display: flex; flex-direction: column;">
                <div class="bordered-panel">
                    <div class="vertical-label">ESTIMATION SELECTED</div>
                    <div class="panel-content">
                        <div class="bar-top" style="padding-left:0; border-bottom:0;">
                            <span>Drag a column header here to group by that column</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #64748b;"><circle cx="11" cy="11" r="8"></circle> <line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                        <div style="flex: 1; overflow: auto; border-top:1px solid var(--hr-border);">
                            <datalist id="est-product-names">
                                <option value="SERVER RACK 42U"></option>
                                <option value="CISCO SWITCH 24P"></option>
                                <option value="APC UPS 3000VA"></option>
                                <option value="CAT6 CABLE BOX"></option>
                            </datalist>
                            <table class="list-grid">
                                <thead>
                                    <tr>
                                        <th style="width:100px;">EST. N...</th>
                                        <th style="width:300px;">PRODUCT NAME</th>
                                        <th style="width:50px;">QTY</th>
                                        <th style="width:100px;">PRICE</th>
                                        <th style="width:100px;">AMOUNT</th>
                                        <th style="width:100px;">@UNIT OFF...</th>
                                        <th style="width:100px;">TL. OFFERS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in selectedEstimationItems" :key="index">
                                        <tr>
                                            <td><input type="text" class="form-input" style="width:100%; border:none;" x-model="row.estNo"></td>
                                            <td><input type="text" class="form-input" style="width:100%; border:none;" x-model="row.productName" list="est-product-names"></td>
                                            <td><input type="number" class="form-input" style="width:100%; border:none;" x-model="row.qty" @input="updateCalculations(row)"></td>
                                            <td><input type="number" class="form-input" style="width:100%; border:none;" x-model="row.price" @input="updateCalculations(row)"></td>
                                            <td style="text-align:right;" x-text="formatCurrency(row.amount)"></td>
                                            <td><input type="text" class="form-input" style="width:100%; border:none;" x-model="row.unitOff"></td>
                                            <td style="text-align:right;" x-text="formatCurrency(row.tlOffers)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div style="display:flex; padding: 4px 0; background: white; border-top:1px solid var(--hr-border);">
                            <div style="flex: 0 0 550px;"></div>
                            <div style="flex: 0 0 100px; padding: 0 4px;"><input type="text" class="form-input" style="width: 100%; text-align:right; border-color: #ccc; color:#000;" :value="formatCurrency(totalOffers)" readonly></div>
                            <div style="flex: 0 0 100px; padding: 0 4px;"><input type="text" class="form-input" style="width: 100%; text-align:right; border-color: #ccc; color:#000;" :value="formatCurrency(totalOffers)" readonly></div>
                            <div style="flex: 0 0 100px;"></div>
                        </div>
                        <div class="grid-footer" style="padding: 2px 4px; background: white; border-top: none;">
                            <button class="pager-btn" @click="navigate('first')">|◀</button> 
                            <button class="pager-btn" @click="navigate('prev')">◀◀</button> 
                            <button class="pager-btn" @click="navigate('prev')">◀</button>
                            <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIndex + 1"></span> of <span x-text="quotationsHistory.length"></span></span>
                            <button class="pager-btn" @click="navigate('next')">▶</button> 
                            <button class="pager-btn" @click="navigate('next')">▶▶</button> 
                            <button class="pager-btn" @click="navigate('last')">▶|</button>
                            <button class="pager-btn" style="margin-left: 20px;" @click="addRow()">+</button>
                            <button class="pager-btn" @click="removeRow(selectedEstimationItems.length - 1)">-</button>
                            <button class="pager-btn">◀</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Notes & Total Area -->
            <div style="padding: 10px; display: flex; justify-content: space-between; align-items: flex-start; background:#f0f0f0;">
                <div>
                    <div style="font-size: 11px; color: #333; margin-bottom: 2px; padding: 2px 4px; background:#f0f0f0; width:200px;">NOTES</div>
                    <div style="display:flex;">
                        <textarea style="width: 250px; height: 50px; border: 1px solid var(--hr-border); resize:none; font-size:11px;" x-model="formData.notes"></textarea>
                        <div style="display:flex; flex-direction:column; border: 1px solid var(--hr-border); border-left:none; background:#e8e8e8; justify-content:space-between; width: 16px;">
                           <span style="font-size:8px; display:flex; align-items:center; justify-content:center; flex:1; cursor:pointer; color:#333; border-bottom:1px solid #999;">▲</span>
                           <span style="font-size:8px; display:flex; align-items:center; justify-content:center; flex:1; cursor:pointer; color:#333;">▼</span>
                        </div>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:5px; margin-top: 15px;">
                    <span style="font-size: 11px; color: #333;">Total Offers</span>
                    <div style="display: flex;">
                        <input type="text" class="form-input" style="width: 120px; text-align:right; border-right: none;" :value="formatCurrency(totalOffers)" readonly>
                        <div style="display:flex; flex-direction:column; border: 1px solid var(--hr-border); background:#e8e8e8; width: 16px;">
                           <span style="font-size:8px; display:flex; align-items:center; justify-content:center; flex:1; cursor:pointer; color:#333; border-bottom:1px solid #999; height:9px;">▲</span>
                           <span style="font-size:8px; display:flex; align-items:center; justify-content:center; flex:1; cursor:pointer; color:#333; height:9px;">▼</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QUOTATION LIST TAB -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''">
            <div style="flex: 1; overflow: auto;">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width:20px; text-align:center;">
                                <div style="font-size:1rem; color:#f59e0b;">*</div>
                            </th>
                            <th style="width: 80px;">DATE</th>
                            <th style="width: 80px;">USERNO</th>
                            <th style="width: 150px;">CUSTOMER NAME</th>
                            <th style="width: 100px;">ATTN-1</th>
                            <th style="width: 150px;">SALES NAME</th>
                            <th style="width: 100px; text-align:right;">AMOUNT</th>
                            <th style="width: 80px; text-align:right;">DISCOUNT</th>
                            <th style="width: 80px; text-align:right;">TAX</th>
                            <th style="width: 100px; text-align:right;">TOTAL</th>
                            <th style="width: 150px;">NOTE</th>
                            <th style="width: 120px;">EST. SELECTED</th>
                            <th style="width: 100px;">ORDER NO.</th>
                            <th style="width: 100px;">PO. REF</th>
                            <th style="width: 80px;">RECID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(q, i) in quotationsHistory" :key="i">
                            <tr @click="selectRecord(i)" :class="currentIndex === i ? 'selected' : ''" style="cursor: pointer;">
                                <td style="text-align:center;"><div style="width:8px; height:8px; background:#3b82f6; margin:0 auto;"></div></td>
                                <td x-text="q.date"></td>
                                <td x-text="q.userno"></td>
                                <td x-text="q.customer"></td>
                                <td x-text="q.attn1"></td>
                                <td x-text="q.sales"></td>
                                <td style="text-align:right;" x-text="q.amount"></td>
                                <td style="text-align:right;" x-text="q.discount"></td>
                                <td style="text-align:right;" x-text="q.tax"></td>
                                <td style="text-align:right;" x-text="q.total"></td>
                                <td x-text="q.note"></td>
                                <td x-text="q.est_selected"></td>
                                <td x-text="q.order_no"></td>
                                <td x-text="q.po_ref"></td>
                                <td x-text="q.recid"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="grid-footer" style="padding: 4px; background: transparent; border-top: 1px solid var(--hr-border); border-bottom: 1px solid var(--hr-border); display:flex; justify-content:space-between; min-height: 38px;">
                <div style="display:flex; align-items:center;">
                    <button class="pager-btn" @click="navigate('first')">|◀</button> 
                    <button class="pager-btn" @click="navigate('prev')">◀◀</button> 
                    <button class="pager-btn" @click="navigate('prev')">◀</button>
                    <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIndex + 1"></span> of <span x-text="quotationsHistory.length"></span></span>
                    <button class="pager-btn" @click="navigate('next')">▶</button> 
                    <button class="pager-btn" @click="navigate('next')">▶▶</button> 
                    <button class="pager-btn" @click="navigate('last')">▶|</button>
                </div>
                <div style="display:flex; gap:30px; padding-right:120px;">
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="23.500.000" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="500.000" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="2.530.000" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="25.530.000" readonly>
                </div>
            </div>
        </div>

        <!-- DETAIL QUOTATION TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail_list' ? 'active' : ''">
            <div class="bar-top" style="border-bottom:none;">
                <span>Drag a column header here to group by that column</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #64748b;"><circle cx="11" cy="11" r="8"></circle> <line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <div style="flex: 1; overflow: auto; border-top:1px solid var(--hr-border);">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width:20px; text-align:center;">
                                <div style="font-size:1rem; color:#f59e0b;">*</div>
                            </th>
                            <th style="width: 80px;">DATE</th>
                            <th style="width: 80px;">USERNO</th>
                            <th style="width: 150px;">CUSTOMER NAME</th>
                            <th style="width: 100px;">ATTN-1</th>
                            <th style="width: 100px;">ATTN-2</th>
                            <th style="width: 150px;">SALES NAME</th>
                            <th style="width: 200px;">PRODUCT NAME</th>
                            <th style="width: 50px;">QTY</th>
                            <th style="width: 100px; text-align:right;">PRICE</th>
                            <th style="width: 100px; text-align:right;">AMOUNT</th>
                            <th style="width: 60px;">%DISC</th>
                            <th style="width: 100px; text-align:right;">DISCOUNT</th>
                            <th style="width: 100px; text-align:right;">TAX</th>
                            <th style="width: 100px; text-align:right;">TOTAL</th>
                            <th style="width: 120px;">EST. SELECTED</th>
                            <th style="width: 100px;">ORDER NO.</th>
                            <th style="width: 100px;">PO. REF</th>
                            <th style="width: 150px;">NOTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, i) in detailedQuotationItems" :key="i">
                            <tr @click="selectDetailRecord(item)" style="cursor: pointer;">
                                <td style="text-align:center;"><div style="width:8px; height:8px; background:#3b82f6; margin:0 auto;"></div></td>
                                <td x-text="item.date"></td>
                                <td x-text="item.userno"></td>
                                <td x-text="item.customer"></td>
                                <td x-text="item.attn1"></td>
                                <td x-text="item.attn2"></td>
                                <td x-text="item.sales"></td>
                                <td x-text="item.product"></td>
                                <td style="text-align:center;" x-text="item.qty"></td>
                                <td style="text-align:right;" x-text="item.price"></td>
                                <td style="text-align:right;" x-text="item.amount"></td>
                                <td style="text-align:center;" x-text="item.disc_pct"></td>
                                <td style="text-align:right;" x-text="item.discount"></td>
                                <td style="text-align:right;" x-text="item.tax"></td>
                                <td style="text-align:right;" x-text="item.total"></td>
                                <td x-text="item.est_selected"></td>
                                <td x-text="item.order_no"></td>
                                <td x-text="item.po_ref"></td>
                                <td x-text="item.note"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="grid-footer" style="padding: 4px; background: transparent; border-top: 1px solid var(--hr-border); border-bottom: 1px solid var(--hr-border); display:flex; justify-content:space-between; min-height: 38px;">
                <div style="display:flex; align-items:center;">
                    <button class="pager-btn">|◀</button> <button class="pager-btn">◀◀</button> <button class="pager-btn">◀</button>
                    <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="detailedQuotationItems.length"></span> of <span x-text="detailedQuotationItems.length"></span></span>
                    <button class="pager-btn">▶</button> <button class="pager-btn">▶▶</button> <button class="pager-btn">▶|</button>
                </div>
                <div style="display:flex; gap:10px; padding-right:10px;">
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="15.000.000" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="0" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="1.650.000" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="16.650.000" readonly>
                </div>
            </div>
        </div>
        
    </div>

</div>
@endsection

@push('scripts')
<script>
    function quotationManager() {
        return {
            activeMainTab: 'detail',
            customers: @json($customers),
            salesNames: @json($salesNames),
            estimations: @json($estimations),
            quotationsHistory: @json($quotationsHistory),
            detailedQuotationItems: @json($detailedQuotationItems),
            estimationItems: @json($estimationItems),
            currentIndex: -1,

            formData: {
                date: new Date().toISOString().split('T')[0],
                customerId: '',
                attn1: '',
                attn2: '',
                estimationCode: '',
                salesName: '',
                poNo: '',
                notes: ''
            },
            customerNameInput: '',

            selectedEstimationItems: [],

            init() {
                this.addRow();
            },

            addRow() {
                this.selectedEstimationItems.push({
                    estNo: '',
                    productName: '',
                    qty: 1,
                    price: 0,
                    amount: 0,
                    unitOff: '',
                    tlOffers: 0
                });
            },

            removeRow(index) {
                this.selectedEstimationItems.splice(index, 1);
            },

            loadEstimationItems() {
                const items = this.estimationItems[this.formData.estimationCode] || [];
                // Fresh copy to avoid reference issues
                this.selectedEstimationItems = JSON.parse(JSON.stringify(items));
                if (this.selectedEstimationItems.length === 0) {
                    this.addRow();
                }
            },

            selectRandomEstimation() {
                const codes = Object.keys(this.estimationItems);
                this.formData.estimationCode = codes[Math.floor(Math.random() * codes.length)];
                this.loadEstimationItems();
            },

            navigate(direction) {
                const total = this.quotationsHistory.length;
                if (total === 0) return;

                if (direction === 'first') this.currentIndex = 0;
                else if (direction === 'last') this.currentIndex = total - 1;
                else if (direction === 'next') this.currentIndex = Math.min(this.currentIndex + 1, total - 1);
                else if (direction === 'prev') this.currentIndex = Math.max(this.currentIndex - 1, 0);

                if (this.currentIndex === -1) this.currentIndex = 0;
                
                this.loadCurrentRecord();
            },

            loadCurrentRecord() {
                const record = this.quotationsHistory[this.currentIndex];
                if (!record) return;

                this.formData = {
                    date: record.date,
                    customerId: this.customers.find(c => c.name === record.customer)?.id || '',
                    attn1: record.attn1,
                    attn2: '',
                    estimationCode: record.est_selected,
                    salesName: record.sales,
                    poNo: record.order_no,
                    notes: record.note
                };
                this.customerNameInput = record.customer || '';

                // Load items for this estimation
                this.loadEstimationItems();
            },

            selectRecord(index) {
                this.currentIndex = index;
                this.loadCurrentRecord();
                this.activeMainTab = 'detail';
            },

            selectDetailRecord(item) {
                // Find matching quote in history
                let index = this.quotationsHistory.findIndex(q => q.order_no === item.order_no);
                if (index === -1) {
                    index = this.quotationsHistory.findIndex(q => q.customer === item.customer && q.date === item.date);
                }
                
                if (index !== -1) {
                    this.selectRecord(index);
                } else {
                    this.formData.date = item.date;
                    this.formData.poNo = item.order_no;
                    this.formData.estimationCode = item.est_selected;
                    this.customerNameInput = item.customer;
                    this.onCustomerSelect();
                    this.activeMainTab = 'detail';
                }
            },

            onCustomerSelect() {
                const cust = this.customers.find(c => c.name === this.customerNameInput);
                if(cust) {
                    this.formData.customerId = cust.id;
                } else {
                    // Custom typed customer support
                    this.formData.customerId = 'CUST-NEW';
                }
            },

            updateCalculations(row) {
                row.amount = Number(row.qty || 0) * Number(row.price || 0);
                row.tlOffers = Number(row.qty || 0) * Number(row.price || 0);
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('id-ID').format(Math.round(value));
            },

            get totalOffers() {
                return this.selectedEstimationItems.reduce((acc, row) => acc + Number(row.tlOffers || 0), 0);
            },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.createNew(); break;
                    case 'save': 
                        this.saveCurrentChanges(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.formData, 'Quotation_' + (this.formData.estimationCode || 'Draft') + '.json');
                        }
                        showToast('Quotation saved to file', 'success'); 
                        break;
                    case 'delete': this.deleteRecord(); break;
                    case 'refresh': this.refreshData(); break;
                    case 'preview': window.print(); break;
                    case 'find': this.activeMainTab = 'list'; this.$nextTick(() => { if(typeof erpFindOpen === 'function') erpFindOpen(); }); break;
                    case 'undo': this.undoChanges(); break;
                    case 'save-as': 
                        this.saveAsNew(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.formData, 'Quotation_Copy.json');
                        }
                        break;
                    case 'edit': this.focusFirstField(); break;
                    case 'barcode': showToast('Generating barcode...', 'info'); break;
                    case 'resend': showToast('Re-sending document...', 'info'); break;
                }
            },

            undoChanges() {
                if (confirm('Revert all unsaved changes for this quotation?')) {
                    this.loadCurrentRecord();
                    showToast('Changes reverted', 'info');
                }
            },

            saveAsNew() {
                const clone = JSON.parse(JSON.stringify(this.formData));
                this.quotationsHistory.push({
                    ...clone,
                    id: this.quotationsHistory.length + 1,
                    customer: 'CLONED RECORD'
                });
                this.currentIndex = this.quotationsHistory.length - 1;
                this.loadCurrentRecord();
                showToast('Quotation duplicated', 'success');
            },

            focusFirstField() {
                this.activeMainTab = 'detail';
                this.$nextTick(() => {
                    const firstInput = document.querySelector('.panel-content input:not([readonly])');
                    if (firstInput) firstInput.focus();
                });
            },

            createNew() {
                this.formData = {
                    date: new Date().toISOString().split('T')[0],
                    customerId: '',
                    attn1: '',
                    attn2: '',
                    estimationCode: '',
                    salesName: 'SYSTEM',
                    poNo: '',
                    notes: ''
                };
                this.selectedEstimationItems = [];
                this.addRow();
                this.activeMainTab = 'detail';
                showToast('New quotation created', 'success');
            },

            saveCurrentChanges() {
                showToast('Quotation saved locally', 'success');
            },

            deleteRecord() {
                if (confirm('Are you sure you want to delete this quotation?')) {
                    this.quotationsHistory.splice(this.currentIndex, 1);
                    if (this.currentIndex >= this.quotationsHistory.length) {
                        this.currentIndex = Math.max(0, this.quotationsHistory.length - 1);
                    }
                    this.loadCurrentRecord();
                    showToast('Quotation deleted', 'success');
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
