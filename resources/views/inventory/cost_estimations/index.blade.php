@extends('layouts.app')

@section('title', 'Cost Estimations')

@push('styles')
<style>
    :root { --hr-border: #999; --hr-primary: #1e293b; --hr-accent: #2563eb; }
    .fa-window { background: #f0f0f0; border: 1px solid #999; overflow: hidden; display: flex; flex-direction: column; height: calc(100vh - 120px); box-shadow: 2px 2px 5px rgba(0,0,0,0.2); color: #000; }
    .window-title-bar { background: linear-gradient(to bottom, #4f78b1, #3a5a8f); color: white; padding: 4px 8px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; font-weight: bold; }
    .main-tabs { display: flex; background: #f0f0f0; padding: 4px 4px 0 4px; border-bottom: 1px solid #999; }
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
    .bar-top { padding: 4px 8px; background: #f0f0f0; border-bottom: 1px solid #999; display: flex; justify-content: space-between; font-size: 11px; align-items: center; flex-shrink: 0; }
    .detail-form-area { padding: 4px 8px; background: #f0f0f0; border-bottom: 1px solid #999; display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 2px; font-size: 11px; }
    .form-label { width: 90px; text-align: right; margin-right: 5px; color: #333; font-size: 11px; flex-shrink: 0; }
    .form-input { border: 1px solid #999; padding: 1px 3px; border-radius: 0; font-size: 11px; background: white; height: 20px; box-sizing: border-box; }
    .form-input[readonly] { background: #e8e8e8; }
    .form-select { border: 1px solid #999; padding: 1px 3px; border-radius: 0; font-size: 11px; background: white; height: 20px; }
    .vertical-label { writing-mode: vertical-rl; transform: rotate(180deg); text-align: center; font-size: 10px; padding: 6px 3px; background: #e8e8e8; border-right: 1px solid #999; color: #333; flex-shrink: 0; }
    .bordered-panel { border: 1px solid #999; display: flex; background: white; margin-bottom: 4px; overflow: hidden; }
    .panel-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .summary-tabs { display: flex; background: #e0e0e0; border-bottom: 1px solid #999; flex-shrink: 0; }
    .summary-tab { padding: 2px 8px; font-size: 11px; color: #333; background: #e0e0e0; border: 1px solid #999; border-bottom: none; cursor: pointer; text-transform: uppercase; margin-right: 2px; border-radius: 2px 2px 0 0; }
    .summary-tab.active { background: white; font-weight: bold; }
</style>
@endpush

@section('content')
<div class="fa-window" x-data="costEstimationManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <span>Cost Estimations</span>
        <div style="display:flex;gap:2px;">
            <button style="background:#e0e0e0;border:1px solid #999;width:16px;height:14px;font-size:9px;cursor:pointer;">_</button>
            <button style="background:#e0e0e0;border:1px solid #999;width:16px;height:14px;font-size:9px;cursor:pointer;">□</button>
            <button style="background:#cc0000;border:1px solid #999;width:16px;height:14px;font-size:9px;color:white;cursor:pointer;">✕</button>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Main Navigation Tabs -->
    <div class="main-tabs" style="background: #e2e8f0; border-bottom: 1px solid var(--hr-border); padding-left: 10px; border-radius: 0; width: 100%;">
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">RECORD DETAIL</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">RECORDS LIST</button>
    </div>

    <div class="tab-content" style="border-top: none; flex: 1;">
        
        <!-- RECORD DETAIL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''">
            <!-- Header forms -->
            <div class="detail-form-area">
                <div style="display: flex; flex-direction: column; gap: 4px; padding: 10px 0;">
                    <div style="display: flex; align-items: center; font-size: 11px;">
                        <div style="width: 100px; text-align: right; padding-right: 5px; color: #333;">Date</div>
                        <input type="date" class="form-input" style="width: 150px;" x-model="formData.date">
                        <div style="width: 60px; text-align: right; padding-right: 5px; color: #333;">Location</div>
                        <input type="text" class="form-input" style="width: 250px;" x-model="locationInput" placeholder="Type or Select Location" @change="onLocationSelect">
                    </div>
                    <div style="display: flex; align-items: center; font-size: 11px;">
                        <div style="width: 100px; text-align: right; padding-right: 5px; color: #333;">Customer Name</div>
                        <select class="form-select" style="width: 460px;" x-model="customerNameInput" @change="onCustomerSelect">
                            <option value="">Type or Select Customer</option>
                            <template x-for="cust in customers" :key="cust.id">
                                <option :value="cust.name" x-text="cust.name"></option>
                            </template>
                        </select>
                    </div>
                    <div style="display: flex; align-items: center; font-size: 11px;">
                        <div style="width: 100px; text-align: right; padding-right: 5px; color: #333;">Product Services</div>
                        <select class="form-select" style="width: 460px;" x-model="formData.productService">
                            <option value="Select Service">Select Service</option>
                            <template x-for="srv in productServices" :key="srv">
                                <option :value="srv" x-text="srv"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <!-- Right Title -->
                <div style="display: flex; align-items: flex-end; padding-right: 20px;">
                    <div style="font-size: 1.2rem; font-weight: bold; color: #1e293b;">
                        cost estimation
                    </div>
                </div>
            </div>

            <!-- Body Layout Split -->
            <div style="display: flex; flex-direction: column; padding: 4px; gap: 4px; flex: 1; overflow:hidden;">
                
                <!-- TOP HALF: SERVICE COST -->
                <div class="bordered-panel" style="height: 35%; min-height: 150px; resize: vertical; overflow: hidden; margin-bottom: 0; position: relative;">
                    <div class="vertical-label">SERVICE COST</div>
                    <div class="panel-content">
                        <div style="flex: 1; overflow: auto;">
                            <table class="list-grid">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">CODE</th>
                                        <th style="width:150px;">COMPONENT NAME</th>
                                        <th style="width:200px;">DESCRIPTION</th>
                                        <th style="width:50px;">MPP</th>
                                        <th style="width:80px;">PRICE</th>
                                        <th style="width:80px;">AMOUNT</th>
                                        <th style="width:80px;">NEGOTIATION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in serviceCosts" :key="index">
                                        <tr @click="selSvcIdx = index" :style="selSvcIdx === index ? 'background:#d0d8e8;' : ''" style="cursor:pointer;">
                                            <td><input type="text" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.code"></td>
                                            <td><input type="text" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.name"></td>
                                            <td><input type="text" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.description"></td>
                                            <td><input type="number" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.mpp" @input="updateCalculations(row)"></td>
                                            <td><input type="number" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.price" @input="updateCalculations(row)"></td>
                                            <td style="text-align:right;" x-text="formatCurrency(row.amount)"></td>
                                            <td><input type="number" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.negotiation"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="grid-footer" style="padding: 2px 4px; border-top: 1px solid var(--hr-border);">
                            <button class="pager-btn" @click="selSvcIdx=0">|◀</button> <button class="pager-btn" @click="selSvcIdx=Math.max(0,selSvcIdx-5)">◀◀</button> <button class="pager-btn" @click="selSvcIdx=Math.max(0,selSvcIdx-1)">◀</button>
                            <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="serviceCosts.length ? selSvcIdx+1 : 0"></span> of <span x-text="serviceCosts.length"></span></span>
                            <button class="pager-btn" @click="selSvcIdx=Math.min(serviceCosts.length-1,selSvcIdx+1)">▶</button> <button class="pager-btn" @click="selSvcIdx=Math.min(serviceCosts.length-1,selSvcIdx+5)">▶▶</button> <button class="pager-btn" @click="selSvcIdx=serviceCosts.length-1">▶|</button>
                            <button class="pager-btn" style="margin-left:5px;" @click="addRow('service')">+</button> <button class="pager-btn" @click="removeRow('service', selSvcIdx)">-</button>
                        </div>
                    </div>
                </div>

                <!-- BOTTOM HALF -->
                <div style="display: flex; flex: 1; gap: 4px; overflow: hidden;">
                    
                    <!-- BOTTOM LEFT COLUMN -->
                    <div style="flex: 1.2; display: flex; flex-direction: column; gap: 4px; overflow: auto; padding-right: 2px;">

                    <!-- DIRECT COST -->
                    <div class="bordered-panel" style="height: 50%; min-height: 150px; resize: vertical; overflow: hidden; margin-bottom: 0; flex-shrink: 0;">
                        <div class="vertical-label" style="display:flex; flex-direction:column; justify-content:center; align-items:center;">
                            <span>. COST</span>
                            <span style="font-size: 8px; margin-top: 5px; transform: rotate(-180deg);">►</span>
                            <span style="font-size: 8px; margin-top: 5px; transform: rotate(-180deg);">▼</span>
                        </div>
                        <div class="panel-content">
                            <div style="flex: 1; overflow: auto;">
                                <table class="list-grid">
                                    <thead>
                                        <tr>
                                            <th style="width:200px;">COMPONENT NAME</th>
                                            <th style="width:80px;">VALUE</th>
                                            <th style="width:50px;">UNIT</th>
                                            <th style="width:100px;">AMOUNT</th>
                                            <th style="width:100px;">NEGOTIATION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(row, index) in directCosts" :key="index">
                                            <tr @click="selDirIdx = index" :style="selDirIdx === index ? 'background:#d0d8e8;' : ''" style="cursor:pointer;">
                                                <td><input type="text" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.name"></td>
                                                <td><input type="number" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.mpp" @input="updateCalculations(row)"></td>
                                                <td><input type="text" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.description"></td>
                                                <td style="text-align:right;" x-text="formatCurrency(row.amount)"></td>
                                                <td><input type="number" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.negotiation"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background: transparent;">
                                            <td colspan="3" style="border:none;"></td>
                                            <td style="border:none; padding: 2px 4px;"><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.direct.amount)" readonly></td>
                                            <td style="border:none; padding: 2px 4px;"><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.direct.nego)" readonly></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="grid-footer" style="padding: 2px 4px; border-top: 1px solid var(--hr-border);">
                                <button class="pager-btn" @click="selDirIdx=0">|◀</button> <button class="pager-btn" @click="selDirIdx=Math.max(0,selDirIdx-5)">◀◀</button> <button class="pager-btn" @click="selDirIdx=Math.max(0,selDirIdx-1)">◀</button>
                                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="directCosts.length ? selDirIdx+1 : 0"></span> of <span x-text="directCosts.length"></span></span>
                                <button class="pager-btn" @click="selDirIdx=Math.min(directCosts.length-1,selDirIdx+1)">▶</button> <button class="pager-btn" @click="selDirIdx=Math.min(directCosts.length-1,selDirIdx+5)">▶▶</button> <button class="pager-btn" @click="selDirIdx=directCosts.length-1">▶|</button>
                                <button class="pager-btn" style="margin-left:5px;" @click="addRow('direct')">+</button> <button class="pager-btn" @click="removeRow('direct', selDirIdx)">-</button>
                            </div>
                        </div>
                    </div>

                    <!-- UN-DIRECT COST -->
                    <div class="bordered-panel" style="flex: 1; min-height: 150px; resize: vertical; overflow: hidden; margin-bottom: 0; flex-shrink: 0;">
                        <div class="vertical-label">UN-DIRECT COST</div>
                        <div class="panel-content">
                            <div style="flex: 1; overflow: auto; min-height: 40px;">
                                <table class="list-grid">
                                    <thead>
                                        <tr>
                                            <th style="width:250px;">COMPONENT NAME</th>
                                            <th style="width:120px;">PRICE</th>
                                            <th style="width:120px;">NEGOTIATION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(row, index) in indirectCosts" :key="index">
                                            <tr @click="selIndIdx = index" :style="selIndIdx === index ? 'background:#d0d8e8;' : ''" style="cursor:pointer;">
                                                <td><input type="text" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.name"></td>
                                                <td><input type="number" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.price" @input="updateCalculations(row)"></td>
                                                <td><input type="number" class="form-input" style="width:100%; border:none; background:transparent;" x-model="row.negotiation"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background: transparent;">
                                            <td style="border:none;"></td>
                                            <td style="border:none; padding: 2px 4px;"><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.indirect.amount)" readonly></td>
                                            <td style="border:none; padding: 2px 4px;"><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.indirect.nego)" readonly></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="grid-footer" style="padding: 2px 4px; border-top:1px solid var(--hr-border);">
                                <button class="pager-btn" @click="selIndIdx=0">|◀</button> <button class="pager-btn" @click="selIndIdx=Math.max(0,selIndIdx-5)">◀◀</button> <button class="pager-btn" @click="selIndIdx=Math.max(0,selIndIdx-1)">◀</button>
                                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="indirectCosts.length ? selIndIdx+1 : 0"></span> of <span x-text="indirectCosts.length"></span></span>
                                <button class="pager-btn" @click="selIndIdx=Math.min(indirectCosts.length-1,selIndIdx+1)">▶</button> <button class="pager-btn" @click="selIndIdx=Math.min(indirectCosts.length-1,selIndIdx+5)">▶▶</button> <button class="pager-btn" @click="selIndIdx=indirectCosts.length-1">▶|</button>
                                <button class="pager-btn" style="margin-left:5px;" @click="addRow('indirect')">+</button> <button class="pager-btn" @click="removeRow('indirect', selIndIdx)">-</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTTOM RIGHT COLUMN -->
                <div style="flex: 1; display: flex; flex-direction: column; overflow: hidden; border: 1px solid var(--hr-border); background: white;">
                    <!-- Inner Tabs -->
                    <div class="summary-tabs">
                        <button class="summary-tab" :class="innerTab === 'total' ? 'active' : ''" @click="innerTab = 'total'">TOTAL COST AND REFFRENS</button>
                        <button class="summary-tab" :class="innerTab === 'process' ? 'active' : ''" @click="innerTab = 'process'">COST PROCESS LIST</button>
                    </div>

                    <div style="flex: 1; background: white; border: 1px solid var(--hr-border); border-top: none; position: relative;">
                        
                        <!-- TAB: TOTAL COST -->
                        <div x-show="innerTab === 'total'" style="padding: 15px; position:absolute; top:0; left:0; width:100%; height:100%; overflow:auto;">
                            <table style="width:100%; font-size:0.75rem; color:#475569; border-collapse: separate; border-spacing: 0 8px;">
                                <tr>
                                    <th style="font-weight:normal; text-align:right; padding-right:10px;"></th>
                                    <th style="font-weight:normal; text-align:center;">TOTAL</th>
                                    <th style="font-weight:normal; text-align:center;">NEGOTIATION</th>
                                </tr>
                                <tr>
                                    <td style="text-align:right; padding-right:10px;">SERVICES COST</td>     
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.service.amount)" readonly></td> 
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.service.nego)" readonly></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right; padding-right:10px;">DIRECT LABOR COSTS</td>
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.direct.amount)" readonly></td> 
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.direct.nego)" readonly></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right; padding-right:10px;">UN-DIRECT COST</td>    
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.indirect.amount)" readonly></td> 
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.indirect.nego)" readonly></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right; padding-right:10px; font-weight: bold;">SUB. TOTAL</td>        
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right; font-weight: bold;" :value="formatCurrency(totals.subTotal.amount)" readonly></td> 
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right; font-weight: bold;" :value="formatCurrency(totals.subTotal.nego)" readonly></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right; padding-right:10px; white-space: nowrap;">
                                        <div style="display:inline-flex; align-items:center; gap:5px; justify-content:flex-end; width:100%;">
                                            MANAGEMENT FEE <input type="number" class="form-input" style="width:40px; text-align:center;" x-model="managementFeePercent">
                                        </div>
                                    </td>  
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.managementFee.amount)" readonly></td> 
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.managementFee.nego)" readonly></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right; padding-right:10px; white-space: nowrap;">
                                        <div style="display:inline-flex; align-items:center; gap:5px; justify-content:flex-end; width:100%;">
                                            PPN <input type="number" class="form-input" style="width:40px; text-align:center;" x-model="ppnPercent">
                                        </div>
                                    </td>               
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.ppn.amount)" readonly></td> 
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.ppn.nego)" readonly></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right; padding-right:10px; font-weight:bold; color: #2563eb;">GRAND TOTAL</td>     
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right; font-weight:bold;" :value="formatCurrency(totals.grandTotal.amount)" readonly></td> 
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right; font-weight:bold;" :value="formatCurrency(totals.grandTotal.nego)" readonly></td>
                                </tr>
                            </table>
                        </div>

                        <!-- TAB: PROCESS LIST -->
                        <div x-show="innerTab === 'process'" style="position:absolute; top:0; left:0; width:100%; height:100%; display:flex; flex-direction:column;">
                            <div style="flex:1; overflow:auto;">
                                <table class="list-grid">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">CODE</th>
                                            <th style="width:150px;">DESCRIPTION</th>
                                            <th style="width:80px; text-align:right;">@PRICE</th>
                                            <th style="width:40px; text-align:center;">ICON</th>
                                            <th style="width:40px; text-align:center;">LBL</th>
                                            <th style="width:50px;">COLOR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(item, idx) in costProcessList" :key="idx">
                                            <tr @click="selPrcIdx = idx" :style="selPrcIdx === idx ? 'background:#d0d8e8;' : ''" style="cursor:pointer;">
                                                <td style="display:flex; align-items:center; gap:4px;">
                                                    <span x-show="selPrcIdx === idx" style="font-size:0.5rem; color:#475569;">▶</span>
                                                    <span x-text="item.code"></span>
                                                </td>
                                                <td>
                                                    <span x-text="item.description"></span>
                                                    <span style="color:#cbd5e1; float:right;">...</span>
                                                </td>
                                                <td x-text="item.price" style="text-align:right;"></td>
                                                <td style="text-align:center;">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                                </td>
                                                <td x-text="item.lbl" style="text-align:center;"></td>
                                                <td><input type="checkbox" style="width:12px; height:12px;" disabled> White</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="grid-footer" style="padding: 2px 4px; border-top:1px solid var(--hr-border);">
                                <button class="pager-btn" @click="selPrcIdx=0">|◀</button> <button class="pager-btn" @click="selPrcIdx=Math.max(0,selPrcIdx-5)">◀◀</button> <button class="pager-btn" @click="selPrcIdx=Math.max(0,selPrcIdx-1)">◀</button>
                                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="costProcessList.length ? selPrcIdx+1 : 0"></span> of <span x-text="costProcessList.length"></span></span>
                                <button class="pager-btn" @click="selPrcIdx=Math.min(costProcessList.length-1,selPrcIdx+1)">▶</button> <button class="pager-btn" @click="selPrcIdx=Math.min(costProcessList.length-1,selPrcIdx+5)">▶▶</button> <button class="pager-btn" @click="selPrcIdx=costProcessList.length-1">▶|</button>
                                <button class="pager-btn" style="margin-left:5px;">+</button> <button class="pager-btn">-</button>
                                <button class="pager-btn">✓</button> <button class="pager-btn">✕</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        </div> <!-- End of RECORD DETAIL TAB -->

        <!-- RECORDS LIST TAB -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''">
            <div class="bar-top">
                    <span>Drag a column header here to group by that column</span>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <label style="display:flex; align-items:center; gap:5px; font-size:0.75rem; color:#475569; cursor:pointer;">
                            <input type="checkbox" x-model="exportToSinglePage"> Export to 1 page / file
                        </label>
                        <button @click="exportToPdf()" style="background:none; border:none; cursor:pointer; padding: 0; display: flex; align-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </button>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>
                </div>
                
                <div style="flex: 1; overflow: auto; background: white;" id="records-list-content">
                    <table class="list-grid" id="records-list-table">
                        <thead>
                            <tr>
                                <th colspan="2" style="border-bottom:1px solid var(--hr-border); border-right:1px solid var(--hr-border);"></th>
                                <th colspan="5" style="text-align:center; border-bottom:1px solid var(--hr-border);">INFORMATION</th>
                                <th colspan="4" style="text-align:center; border-bottom:1px solid var(--hr-border);">ESTIMATION</th>
                            </tr>
                        <tr>
                            <th style="width: 80px;">DATE</th>
                            <th style="width: 150px;">CUSTOMER NAME</th>
                            <th style="width: 150px;">LOCATIONS</th>
                            <th style="width: 80px;">USERNO</th>
                            <th style="width: 100px;">DOC. REFF</th>
                            <th style="width: 100px;">QOUTATIONS</th>
                            <th style="width: 80px;">INV. DATE</th>
                            
                            <th style="width: 100px; text-align:right;">DIRECT COSTS</th>
                            <th style="width: 100px; text-align:right;">INDIRECT COSTS</th>
                            <th style="width: 100px; text-align:right;">OTHERS COSTS</th>
                            <th style="width: 100px; text-align:right;">MANAG. FE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(est, i) in estimationsHistory" :key="i">
                            <tr @click="selectAndDetail(i)" :style="selHistIdx === i ? 'background:#d0d8e8;' : ''" style="cursor:pointer;">
                                <td style="display:flex; align-items:center; gap:4px;">
                                    <span x-show="selHistIdx === i" style="font-size:0.5rem; color:#475569;">▶</span>
                                    <span x-text="est.date"></span>
                                </td>
                                <td x-text="est.customer"></td>
                                <td x-text="est.location"></td>
                                <td x-text="est.userno"></td>
                                <td x-text="est.doc_reff"></td>
                                <td x-text="est.quotations"></td>
                                <td x-text="est.inv_date"></td>
                                <td style="text-align:right;" x-text="est.direct_costs"></td>
                                <td style="text-align:right;" x-text="est.indirect_costs"></td>
                                <td style="text-align:right;" x-text="est.others_costs"></td>
                                <td style="text-align:right;" x-text="est.management_fee"></td>
                            </tr>
                        </template>
                        </tbody>
                        <tfoot>
                            <tr style="background: transparent;">
                                <td colspan="7" style="border:none;"></td>
                                <td style="border:none; padding: 2px 4px;"><input type="text" class="form-input" style="width:100%; text-align:right;" value="0" readonly></td>
                                <td style="border:none; padding: 2px 4px;"><input type="text" class="form-input" style="width:100%; text-align:right;" value="0" readonly></td>
                                <td style="border:none; padding: 2px 4px;"><input type="text" class="form-input" style="width:100%; text-align:right;" value="0" readonly></td>
                                <td style="border:none; padding: 2px 4px;"><input type="text" class="form-input" style="width:100%; text-align:right;" value="0" readonly></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="grid-footer" style="padding: 4px; background: transparent; border-top: 1px solid var(--hr-border); border-bottom: 1px solid var(--hr-border); display:flex; justify-content:space-between; min-height: 38px;">
                    <div style="display:flex; align-items:center;">
                        <button class="pager-btn" @click="selHistIdx=0">|◀</button> <button class="pager-btn" @click="selHistIdx=Math.max(0,selHistIdx-5)">◀◀</button> <button class="pager-btn" @click="selHistIdx=Math.max(0,selHistIdx-1)">◀</button>
                        <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="estimationsHistory.length ? selHistIdx+1 : 0"></span> of <span x-text="estimationsHistory.length"></span></span>
                        <button class="pager-btn" @click="selHistIdx=Math.min(estimationsHistory.length-1,selHistIdx+1)">▶</button> <button class="pager-btn" @click="selHistIdx=Math.min(estimationsHistory.length-1,selHistIdx+5)">▶▶</button> <button class="pager-btn" @click="selHistIdx=estimationsHistory.length-1">▶|</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function costEstimationManager() {
        return {
            activeMainTab: 'detail',
            innerTab: 'total', // 'total' or 'process'
            costProcessList: @json($costProcessList),
            customers: @json($customers),
            locations: @json($locations),
            productServices: @json($productServices),
            estimationsHistory: @json($estimationsHistory),
            exportToSinglePage: true,

            formData: {
                date: new Date().toISOString().split('T')[0],
                location: '',
                customerId: '',
                productService: ''
            },
            customerNameInput: '',
            locationInput: '',

            serviceCosts: [],
            directCosts: [],
            indirectCosts: [],

            selSvcIdx: 0,
            selDirIdx: 0,
            selIndIdx: 0,
            selPrcIdx: 0,
            selHistIdx: 0,

            managementFeePercent: 10,
            ppnPercent: 11,

            init() {
                // Initial rows
                this.addRow('service');
                this.addRow('direct');
                this.addRow('indirect');
            },

            selectAndDetail(idx) {
                this.selHistIdx = idx;
                
                // Usually we'd populate the form with the selected record data here:
                const record = this.estimationsHistory[idx];
                if (record) {
                    this.formData.date = record.date;
                    // ... etc
                }
                
                this.activeMainTab = 'detail';
            },

            addRow(type) {
                const row = {
                    code: '',
                    name: '',
                    description: '',
                    mpp: 1,
                    price: 0,
                    amount: 0,
                    negotiation: 0
                };
                if (type === 'service') this.serviceCosts.push({...row});
                else if (type === 'direct') this.directCosts.push({...row});
                else if (type === 'indirect') this.indirectCosts.push({...row});
            },

            removeRow(type, index) {
                if (type === 'service') this.serviceCosts.splice(index, 1);
                else if (type === 'direct') this.directCosts.splice(index, 1);
                else if (type === 'indirect') this.indirectCosts.splice(index, 1);
            },

            updateCalculations(row) {
                row.amount = Number(row.mpp || 1) * Number(row.price || 0);
                if (!row.negotiation || row.negotiation == 0) {
                    row.negotiation = row.amount;
                }
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('id-ID').format(Math.round(value));
            },

            get totals() {
                const sumAmount = (arr) => arr.reduce((acc, row) => acc + Number(row.amount || 0), 0);
                const sumNego = (arr) => arr.reduce((acc, row) => acc + Number(row.negotiation || 0), 0);

                const sTotal = sumAmount(this.serviceCosts);
                const dTotal = sumAmount(this.directCosts);
                const iTotal = this.indirectCosts.reduce((acc, row) => acc + Number(row.price || 0), 0);

                const sNego = sumNego(this.serviceCosts);
                const dNego = sumNego(this.directCosts);
                const iNego = sumNego(this.indirectCosts);

                const subTotalAmount = sTotal + dTotal + iTotal;
                const subTotalNego = sNego + dNego + iNego;

                const mFeeAmount = subTotalAmount * (this.managementFeePercent / 100);
                const mFeeNego = subTotalNego * (this.managementFeePercent / 100);

                const ppnAmount = (subTotalAmount + mFeeAmount) * (this.ppnPercent / 100);
                const ppnNego = (subTotalNego + mFeeNego) * (this.ppnPercent / 100);

                return {
                    service: { amount: sTotal, nego: sNego },
                    direct: { amount: dTotal, nego: dNego },
                    indirect: { amount: iTotal, nego: iNego },
                    subTotal: { amount: subTotalAmount, nego: subTotalNego },
                    managementFee: { amount: mFeeAmount, nego: mFeeNego },
                    ppn: { amount: ppnAmount, nego: ppnNego },
                    grandTotal: { amount: subTotalAmount + mFeeAmount + ppnAmount, nego: subTotalNego + mFeeNego + ppnNego }
                };
            },

            exportToPdf() {
                const element = document.getElementById('records-list-content');
                const opt = {
                    margin: [0.5, 0.5],
                    filename: 'cost-estimations-report.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
                };
                
                if (this.exportToSinglePage) {
                    opt.jsPDF.format = 'a3'; // Larger format to fit more or just rely on scaling
                }

                html2pdf().set(opt).from(element).save();
            },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.createNew(); break;
                    case 'save': 
                        this.saveCurrentChanges(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.formData, 'CostEstimation_' + (this.formData.docNo?.replace(/\//g, '-') || 'Draft') + '.json');
                        }
                        break;
                    case 'delete': showToast('Delete not available in this view', 'warning'); break;
                    case 'refresh': this.refreshData(); break;
                    case 'preview': this.exportToPdf(); break;
                    case 'find': this.activeMainTab = 'list'; this.$nextTick(() => { if(typeof erpFindOpen === 'function') erpFindOpen(); }); break;
                    case 'undo': this.undoChanges(); break;
                    case 'save-as': 
                        this.saveAsNew(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.formData, 'CostEstimation_Copy.json');
                        }
                        break;
                    case 'edit': this.focusFirstField(); break;
                    case 'barcode': showToast('Generating barcode...', 'info'); break;
                    case 'resend': showToast('Re-sending document...', 'info'); break;
                }
            },

            undoChanges() {
                if (confirm('Revert all unsaved changes for this estimation?')) {
                    this.refreshData(); // simulated
                    showToast('Changes reverted', 'info');
                }
            },

            saveAsNew() {
                const clone = JSON.parse(JSON.stringify(this.formData));
                clone.docNo += ' (COPY)';
                this.formData = clone;
                showToast('Estimation duplicated as new', 'success');
            },

            focusFirstField() {
                this.activeMainTab = 'detail';
                this.$nextTick(() => {
                    const firstInput = document.querySelector('.tab-pane.active input:not([readonly])');
                    if (firstInput) firstInput.focus();
                });
            },

            createNew() {
                this.formData = {
                    date: new Date().toISOString().split('T')[0],
                    docNo: `EST/${new Date().getFullYear()}/${String(Math.floor(Math.random()*1000)).padStart(4, '0')}`,
                    estimationName: '',
                    project: '',
                    customerId: '',
                    productService: '',
                    location: ''
                };
                this.customerNameInput = '';
                this.locationInput = '';
                this.serviceCosts = [];
                this.directCosts = [];
                this.indirectCosts = [];
                this.addRow('service');
                this.addRow('direct');
                this.addRow('indirect');
                this.activeMainTab = 'detail';
                showToast('New cost estimation created', 'success');
            },

            onCustomerSelect() {
                const cust = this.customers.find(c => c.name === this.customerNameInput);
                if(cust) {
                    this.formData.customerId = cust.id;
                } else {
                    this.formData.customerId = 'CUST-NEW';
                }
            },
            
            onLocationSelect() {
                this.formData.location = this.locationInput;
            },

            saveCurrentChanges() {
                // Mock save
                showToast('Estimation saved locally', 'success');
            },

            refreshData() {
                showToast('Data refreshed', 'success');
                window.location.reload();
            }
        }
    }
</script>
@endpush
