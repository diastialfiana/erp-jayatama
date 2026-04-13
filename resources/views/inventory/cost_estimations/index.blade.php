@extends('layouts.app')

@section('title', 'Cost Estimations')

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

    .bar-top { padding: 8px 10px; font-size: 0.75rem; color: #64748b; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; display: flex; justify-content: space-between; align-items:center;}
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

    .detail-form-area { padding: 10px 15px; background: #f8fafc; border-bottom: 1px solid var(--hr-border); display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 4px; font-size: 0.75rem; }
    .form-label { width: 100px; text-align: right; margin-right: 10px; color: #475569; }
    .form-input { border: 1px solid var(--hr-border); padding: 4px 8px; border-radius: 0; font-size: 0.75rem; background: white; }
    .form-select { border: 1px solid var(--hr-border); padding: 3px 6px; border-radius: 0; font-size: 0.75rem; background: white; }
    
    /* Layout specific */
    .vertical-label { writing-mode: vertical-rl; transform: rotate(180deg); text-align: center; font-size: 0.7rem; padding: 10px 4px; background: white; border-right: 1px solid var(--hr-border); color: #475569;}
    .bordered-panel { border: 1px solid var(--hr-border); display: flex; background: white; margin-bottom: 10px; overflow: hidden;}
    .panel-content { flex: 1; display:flex; flex-direction:column; overflow:hidden;}
    
    /* Summary inner tabs */
    .summary-tabs { display: flex; gap: 2px; background: #e2e8f0; }
    .summary-tab { padding: 4px 10px; font-size: 0.7rem; color: #334155; background: transparent; border: 1px solid transparent; cursor: pointer; text-transform: uppercase; }
    .summary-tab.active { background: white; border: 1px solid var(--hr-border); border-bottom: none; }
    .summary-pane { display: none; background: white; border: 1px solid var(--hr-border); border-top: none; }
    .summary-pane.active { display: block; }
</style>
@endpush

@section('content')
<div x-data="costEstimationManager()" x-init="init()" style="background: white; border: 1px solid var(--hr-border); margin: 10px;">
    <!-- Windows like Title bar -->
    <div style="background: white; padding: 0; border-bottom: 1px solid #e2e8f0;">
        <div style="background: transparent; padding: 6px 10px; color: #334155; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem;">
            <div style="display: flex; gap: 8px; align-items: center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7" fill="#dc2626"></rect><rect x="14" y="14" width="7" height="7" fill="#2563eb"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span style="font-weight: 600;">Cost Estimations</span>
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
            <div class="detail-form-area">
                <div>
                    <div class="form-group">
                        <div class="form-label">Date</div>
                        <input type="date" class="form-input" style="width: 140px;" x-model="formData.date">
                        <div class="form-label" style="width: 60px;">Location</div>
                        <select class="form-select" style="width: 250px;" x-model="formData.location">
                            <option value="">Select Location</option>
                            <template x-for="loc in locations" :key="loc">
                                <option :value="loc" x-text="loc"></option>
                            </template>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="form-label">Customer Name</div>
                        <select class="form-select" style="width: 250px;" x-model="formData.customerId">
                            <option value="">Select Customer</option>
                            <template x-for="cust in customers" :key="cust.id">
                                <option :value="cust.id" x-text="cust.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="form-label">Product Services</div>
                        <select class="form-select" style="width: 250px;" x-model="formData.productService">
                            <option value="">Select Service</option>
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
            <div style="display: flex; padding: 10px; gap: 10px; flex: 1; overflow:hidden;">
                <!-- LEFT COLUMN -->
                <div style="flex: 1.2; display: flex; flex-direction: column;">
                    
                    <!-- SERVICE COST -->
                    <div class="bordered-panel" style="flex: 1;">
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
                                            <tr>
                                                <td><input type="text" class="form-input" style="width:100%; border:none;" x-model="row.code"></td>
                                                <td><input type="text" class="form-input" style="width:100%; border:none;" x-model="row.name"></td>
                                                <td><input type="text" class="form-input" style="width:100%; border:none;" x-model="row.description"></td>
                                                <td><input type="number" class="form-input" style="width:100%; border:none;" x-model="row.mpp" @input="updateCalculations(row)"></td>
                                                <td><input type="number" class="form-input" style="width:100%; border:none;" x-model="row.price" @input="updateCalculations(row)"></td>
                                                <td style="text-align:right;" x-text="formatCurrency(row.amount)"></td>
                                                <td><input type="number" class="form-input" style="width:100%; border:none;" x-model="row.negotiation"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div style="display:flex; justify-content:flex-end; gap:30px; padding: 5px 20px; border-top:1px solid var(--hr-border);">
                                <div style="font-size: 0.75rem; color: #64748b; align-self: center;">TOTAL SERVICE COST:</div>
                                <input type="text" class="form-input" style="width: 80px; text-align:right;" :value="formatCurrency(totals.service.amount)" readonly>
                                <input type="text" class="form-input" style="width: 80px; text-align:right;" :value="formatCurrency(totals.service.nego)" readonly>
                            </div>
                            <div class="grid-footer" style="padding: 2px 4px;">
                                <button class="pager-btn">|◀</button> <button class="pager-btn">◀◀</button> <button class="pager-btn">◀</button>
                                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="serviceCosts.length"></span> of <span x-text="serviceCosts.length"></span></span>
                                <button class="pager-btn">▶</button> <button class="pager-btn">▶▶</button> <button class="pager-btn">▶|</button>
                                <button class="pager-btn" style="margin-left:5px;" @click="addRow('service')">+</button> <button class="pager-btn" @click="removeRow('service', serviceCosts.length-1)">-</button>
                            </div>
                        </div>
                    </div>

                    <!-- DIRECT COST -->
                    <div class="bordered-panel" style="flex: 1;">
                        <div class="vertical-label">DIRECT COST</div>
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
                                            <tr>
                                                <td><input type="text" class="form-input" style="width:100%; border:none;" x-model="row.name"></td>
                                                <td><input type="number" class="form-input" style="width:100%; border:none;" x-model="row.mpp" @input="updateCalculations(row)"></td>
                                                <td><input type="text" class="form-input" style="width:100%; border:none;" x-model="row.description"></td>
                                                <td style="text-align:right;" x-text="formatCurrency(row.amount)"></td>
                                                <td><input type="number" class="form-input" style="width:100%; border:none;" x-model="row.negotiation"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div style="display:flex; justify-content:flex-end; gap:30px; padding: 5px 20px; border-top:1px solid var(--hr-border);">
                                <div style="font-size: 0.75rem; color: #64748b; align-self: center;">TOTAL DIRECT COST:</div>
                                <input type="text" class="form-input" style="width: 100px; text-align:right;" :value="formatCurrency(totals.direct.amount)" readonly>
                                <input type="text" class="form-input" style="width: 100px; text-align:right;" :value="formatCurrency(totals.direct.nego)" readonly>
                            </div>
                            <div class="grid-footer" style="padding: 2px 4px;">
                                <button class="pager-btn">|◀</button> <button class="pager-btn">◀◀</button> <button class="pager-btn">◀</button>
                                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="directCosts.length"></span> of <span x-text="directCosts.length"></span></span>
                                <button class="pager-btn">▶</button> <button class="pager-btn">▶▶</button> <button class="pager-btn">▶|</button>
                                <button class="pager-btn" style="margin-left:5px;" @click="addRow('direct')">+</button> <button class="pager-btn" @click="removeRow('direct', directCosts.length-1)">-</button>
                            </div>
                        </div>
                    </div>

                    <!-- INDIRECT COST -->
                    <div class="bordered-panel" style="margin-bottom: 0;">
                        <div class="vertical-label">INDIRECT COST</div>
                        <div class="panel-content">
                            <div style="flex: 1; overflow: auto; min-height: 40px;">
                                <table class="list-grid">
                                    <tbody>
                                        <template x-for="(row, index) in indirectCosts" :key="index">
                                            <tr>
                                                <td style="width:200px;"><input type="text" class="form-input" style="width:100%; border:none;" x-model="row.name"></td>
                                                <td style="width:80px;"><input type="number" class="form-input" style="width:100%; border:none;" x-model="row.mpp" @input="updateCalculations(row)"></td>
                                                <td style="width:50px;"><input type="text" class="form-input" style="width:100%; border:none;" x-model="row.description"></td>
                                                <td style="width:100px; text-align:right;" x-text="formatCurrency(row.amount)"></td>
                                                <td style="width:100px;"><input type="number" class="form-input" style="width:100%; border:none;" x-model="row.negotiation"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div style="display:flex; justify-content:flex-end; gap:30px; padding: 10px 20px; border-top:1px solid var(--hr-border);">
                                <div style="font-size: 0.75rem; color: #64748b; align-self: center;">TOTAL INDIRECT COST:</div>
                                <input type="text" class="form-input" style="width: 100px; text-align:right;" :value="formatCurrency(totals.indirect.amount)" readonly>
                                <input type="text" class="form-input" style="width: 100px; text-align:right;" :value="formatCurrency(totals.indirect.nego)" readonly>
                            </div>
                            <div class="grid-footer" style="padding: 2px 4px; border-top:1px solid var(--hr-border);">
                                <button class="pager-btn">|◀</button> <button class="pager-btn">◀◀</button> <button class="pager-btn">◀</button>
                                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="indirectCosts.length"></span> of <span x-text="indirectCosts.length"></span></span>
                                <button class="pager-btn">▶</button> <button class="pager-btn">▶▶</button> <button class="pager-btn">▶|</button>
                                <button class="pager-btn" style="margin-left:5px;" @click="addRow('indirect')">+</button> <button class="pager-btn" @click="removeRow('indirect', indirectCosts.length-1)">-</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div style="flex: 1; display: flex; flex-direction: column; padding-top: 190px;">
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
                                    <td style="text-align:right; padding-right:10px;">MANAGEMENT FEE (<span x-text="managementFeePercent"></span>%)</td>  
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.managementFee.amount)" readonly></td> 
                                    <td><input type="text" class="form-input" style="width:100%; text-align:right;" :value="formatCurrency(totals.managementFee.nego)" readonly></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right; padding-right:10px;">PPN (<span x-text="ppnPercent"></span>%)</td>               
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
                                            <tr>
                                                <td x-text="item.code"></td>
                                                <td>
                                                    <span x-text="item.description"></span>
                                                    <span style="color:#cbd5e1; float:right;">...</span>
                                                </td>
                                                <td x-text="item.price" style="text-align:right;"></td>
                                                <td style="text-align:center;">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                                </td>
                                                <td x-text="item.lbl" style="text-align:center;"></td>
                                                <td><div style="width:16px; height:10px; border:1px solid #cbd5e1; background:white;"></div></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="grid-footer" style="padding: 2px 4px; border-top:1px solid var(--hr-border);">
                                <button class="pager-btn">|◀</button> <button class="pager-btn">◀◀</button> <button class="pager-btn">◀</button>
                                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="costProcessList.length"></span> of <span x-text="costProcessList.length"></span></span>
                                <button class="pager-btn">▶</button> <button class="pager-btn">▶▶</button> <button class="pager-btn">▶|</button>
                                <button class="pager-btn" style="margin-left:5px;">+</button> <button class="pager-btn">-</button>
                                <button class="pager-btn">✓</button> <button class="pager-btn">✕</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- RECORDS LIST TAB -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''" style="background: white;">
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
            
            <div style="flex: 1; overflow: auto;" id="records-list-content">
                <table class="list-grid" id="records-list-table">
                    <thead>
                        <tr>
                            <th colspan="7" style="text-align:center; border-bottom:1px solid var(--hr-border);">INFORMATION</th>
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
                            <tr>
                                <td x-text="est.date"></td>
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
                </table>
            </div>
            <div class="grid-footer" style="padding: 4px; background: transparent; border-top: 1px solid var(--hr-border); border-bottom: 1px solid var(--hr-border); display:flex; justify-content:space-between; min-height: 38px;">
                <div style="display:flex; align-items:center;">
                    <button class="pager-btn">|◀</button> <button class="pager-btn">◀◀</button> <button class="pager-btn">◀</button>
                    <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="estimationsHistory.length"></span> of <span x-text="estimationsHistory.length"></span></span>
                    <button class="pager-btn">▶</button> <button class="pager-btn">▶▶</button> <button class="pager-btn">▶|</button>
                </div>
                <div style="display:flex; gap:10px; padding-right:10px;">
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="20.700.000" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="4.300.000" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="800.000" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="2.500.000" readonly>
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

            serviceCosts: [],
            directCosts: [],
            indirectCosts: [],

            managementFeePercent: 10,
            ppnPercent: 11,

            init() {
                // Initial rows
                this.addRow('service');
                this.addRow('direct');
                this.addRow('indirect');
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
                row.amount = Number(row.mpp || 0) * Number(row.price || 0);
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
                const iTotal = sumAmount(this.indirectCosts);

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
            }
        }
    }
</script>
@endpush
