@extends('layouts.app')
@section('title', 'Product Assets')

@push('styles')
    <style>
        :root { --hr-border: #999; --hr-primary: #1e293b; --hr-accent: #2563eb; }
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

        .fa-tabs {
            display: flex;
            background: #f0f0f0;
            padding: 4px 4px 0 4px;
            border-bottom: 1px solid #999;
        }
        .fa-tab {
            padding: 4px 12px;
            font-size: 12px;
            border: 1px solid #999;
            border-bottom: none;
            background: #e0e0e0;
            cursor: pointer;
            margin-right: 2px;
            border-radius: 3px 3px 0 0;
        }
        .fa-tab.active {
            background: #fff;
            font-weight: bold;
            margin-bottom: -1px;
            height: calc(100% + 1px);
        }

        .fa-pane { display:none; flex:1; overflow:auto; background: #f0f0f0; }
        .fa-pane.active { display:flex; flex-direction:column; }

        .main-content { padding: 15px; background: #f0f0f0; }
        .form-grid { display: grid !important; grid-template-columns: 1.2fr 1fr !important; gap: 20px !important; }
        
        .form-group { display: flex !important; flex-direction: row !important; align-items: center !important; margin-bottom: 3px !important; }
        .form-group label { display: block !important; width: 130px !important; font-size: 11px !important; text-align: right !important; margin-right: 8px !important; flex-shrink: 0 !important; font-weight: normal !important; margin-bottom: 0 !important; }
        .form-control { display: block !important; flex: 1 !important; height: 22px !important; padding: 2px 4px !important; border: 1px solid #999 !important; font-size: 12px !important; border-radius: 0 !important; background: white !important; box-sizing: border-box !important; }
        .form-control:read-only { background: #e0e0e0 !important; }

        .form-row { display: flex !important; flex-direction: row !important; gap: 10px !important; margin-bottom: 3px !important; align-items: center !important; flex-wrap: nowrap !important; }
        .form-row .form-group { flex: 1 !important; margin-bottom: 0 !important; }
        .form-row .form-group label { width: 130px !important; }

        .checkbox-group { display: flex; align-items: center; gap: 10px; font-size: 11px; margin-left: 10px; }
        .checkbox-item { display: flex; align-items: center; gap: 4px; cursor: pointer; }

        .textarea-control { height: 60px !important; resize: none; font-family: monospace; font-size: 12px; }
        
        .box-panel { border: 1px solid #999; background: #fff; margin-bottom: 10px; }
        .panel-header { background: #e0e0e0; padding: 4px 8px; font-size: 11px; font-weight: bold; border-bottom: 1px solid #999; }
        .panel-body { padding: 10px; }

        .image-placeholder { 
            width: 100%; height: 160px; border: 1px solid #999; background: #fff; 
            display: flex; align-items: center; justify-content: center; font-size: 11px; color: #666;
            margin-bottom: 10px; cursor: pointer;
        }

        .stat-grid { border: 1px solid #999; padding: 8px; background: #e8e8e8; margin-top: 5px; }
        .stat-row { display: flex; gap: 10px; margin-top: 5px; }
        .stat-item { flex: 1; text-align: center; }
        .stat-item label { display: block; font-size: 10px; color: #444; margin-bottom: 2px; }
        .stat-item input { width: 100%; height: 22px; text-align: center; border: 1px solid #999; font-size: 11px; }

        .rec-table-wrap { overflow:auto; flex:1; background: white; }
        .rec-table { width:100%; border-collapse:collapse; font-size:11px; }
        .rec-table th { background: #e0e0e0; border: 1px solid #999; padding: 4px; text-align: left; position: sticky; top: 0; z-index: 10; }
        .rec-table td { border: 1px solid #ccc; padding: 3px 5px; }
        .rec-table tr.selected td { background: #3a5a8f; color: white; }

        .pager-footer { background: #f0f0f0; border-top: 1px solid #999; padding: 4px 8px; display: flex; align-items: center; gap: 4px; font-size: 11px; }
        .pager-btn { padding: 2px 6px; border: 1px solid #999; background: #fff; cursor: pointer; min-width: 25px; }
        .pager-btn:hover { background: #eee; }
        .pager-btn:disabled { opacity: 0.5; cursor: not-allowed; }

        /* ── PRODUCT LIST & TOOLS TAB ── */
        .list-split-layout { display: flex; flex: 1; overflow: hidden; height: 100%; }
        .list-col-left { flex: 1; display: flex; flex-direction: column; overflow: hidden; border-right: 1px solid #999; position: relative; }
        .list-col-right { width: 310px; flex-shrink: 0; flex-direction: column; background: #f5f5f5; position: relative; border-left: 1px solid #ccc; }
        [x-cloak] { display: none !important; }

        /* grid toolbar */
        .grid-toolbar { background: #f0f0f0; border-bottom: 1px solid #999; padding: 3px 6px; font-size: 11px; color: #555; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .grid-search { border: 1px solid #999; background: #fff; padding: 1px 6px; cursor: pointer; font-size: 13px; }

        /* data table */
        .data-table-container { flex: 1; overflow: auto; background: #fff; }
        .data-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .data-table thead th { background: #e8e8e8; border: 1px solid #aaa; padding: 3px 6px; text-align: left; position: sticky; top: 0; z-index: 5; white-space: nowrap; font-weight: bold; }
        .data-table tbody td { border: 1px solid #ddd; padding: 2px 6px; white-space: nowrap; }
        .data-table tbody tr:hover td { background: #e8f0fb; cursor: pointer; }
        .data-table tbody tr.selected td { background: #3a5a8f; color: #fff; }
        .data-table td.num { text-align: right; }
        .data-table td.chk { text-align: center; }

        /* pagination */
        .pagination-controls { background: #f0f0f0; border-top: 1px solid #999; padding: 3px 8px; display: flex; align-items: center; gap: 3px; font-size: 11px; flex-shrink: 0; }
        .page-btn { border: 1px solid #999; background: #fff; padding: 1px 6px; cursor: pointer; font-size: 11px; text-decoration: none; color: #333; }
        .page-btn:hover { background: #eee; }

        /* right panel tabs */
        .panel-tabs { display: flex; background: #e0e0e0; border-bottom: 1px solid #999; flex-shrink: 0; }
        .panel-tab { padding: 3px 10px; font-size: 11px; cursor: pointer; border-right: 1px solid #bbb; border-top: 1px solid transparent; }
        .panel-tab:hover { background: #d0d0d0; }
        .panel-tab.active { background: #fff; font-weight: bold; border-top: 2px solid #3a5a8f; }

        /* panel content */
        .panel-content { flex: 1; overflow: auto; background: #fff; position: relative; }
        
        /* statistics & detail tables */
        .stat-table { width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 5px; }
        .stat-table th { background: transparent; padding: 6px 4px; text-align: center; color: #1e293b; font-weight: 700; text-transform: uppercase; font-size: 10px; border: none; }
        .stat-table td { padding: 2px 4px; height: 26px; vertical-align: middle; border: none; }
        .stat-table td:first-child { text-align: right; color: #334155; width: 105px; padding-right: 8px; font-weight: normal; }
        .stat-table input { border: 1px solid #cbd5e1; background: #fff; height: 22px; text-align: right; padding: 0 4px; font-size: 11px; color: #334155; border-radius: 1px; width: 100%; box-sizing: border-box; }
        .stat-table input[readonly] { background: #fff; cursor: default; }
        .stat-table input:focus { border-color: #3a5a8f; outline: none; }
        .stat-table input::-webkit-inner-spin-button, .stat-table input::-webkit-outer-spin-button { opacity: 0.6; }

        /* panel elements */
        .panel-footer { display: flex; justify-content: flex-end; gap: 8px; padding: 4px 8px; border-top: 1px solid #ddd; background: #f8f8f8; flex-shrink: 0; }
        .panel-footer input { width: 50px; text-align: right; border: 1px solid #ccc; font-size: 10px; padding: 1px 3px; background: #fff; height: 18px; }
        .panel-pager { display: flex; align-items: center; gap: 2px; padding: 3px 8px; border-top: 1px solid #ddd; background: #f8f8f8; font-size: 10px; color: #666; flex-shrink: 0; }
        
        .act-filter { display: flex; align-items: center; gap: 6px; padding: 6px 8px; border-bottom: 1px solid #ddd; background: #f8f8f8; font-size: 11px; flex-shrink: 0; }
        .act-filter input { border: 1px solid #ccc; font-size: 11px; padding: 2px 4px; }
        
        .wh-subtabs { display: flex; gap: 2px; background: #e0e0e0; padding: 4px 8px 0; border-bottom: 1px solid #bbb; flex-shrink: 0; }
        .wh-subtab { padding: 4px 8px; font-size: 10px; cursor: pointer; background: #d0d0d0; border: 1px solid #bbb; border-bottom: none; }
        .wh-subtab.active { background: #fff; font-weight: bold; }
        .wh-filter { display: flex; align-items: center; gap: 6px; padding: 5px 8px; border-bottom: 1px solid #ddd; background: #f8f8f8; font-size: 11px; flex-shrink: 0; }
        .wh-filter select { border: 1px solid #ccc; font-size: 11px; padding: 1px 4px; }
        .wh-filter label { display: flex; align-items: center; gap: 3px; font-size: 10px; margin-left: 10px; }

        /* right panel content */
        .panel-content { flex: 1; overflow: auto; background: #fff; }
        .panel-content table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .panel-content th { background: #f0f0f0; border: 1px solid #ccc; padding: 3px 6px; font-weight: normal; color: #444; }
        .panel-content td { border: 1px solid #e8e8e8; padding: 2px 6px; }
        .panel-content td input[type=text] { border: 1px solid #ccc; font-size: 11px; padding: 1px 3px; text-align: right; background: #fff; }

        /* panel footer */
        .panel-footer { border-top: 1px solid #ccc; background: #f5f5f5; padding: 3px 6px; display: flex; justify-content: flex-end; gap: 4px; flex-shrink: 0; }
        .panel-footer input { width: 60px; text-align: right; border: 1px solid #ccc; font-size: 11px; padding: 1px 3px; background: #fff; }
        .panel-pager { border-top: 1px solid #ccc; background: #f0f0f0; padding: 2px 6px; display: flex; align-items: center; gap: 3px; font-size: 11px; flex-shrink: 0; }
        .panel-pager button { border: 1px solid #999; background: #fff; padding: 1px 5px; cursor: pointer; font-size: 10px; }

        /* warehouse sub-tabs */
        .wh-subtabs { display: flex; gap: 2px; background: #e0e0e0; padding: 3px 6px 0; border-bottom: 1px solid #ccc; flex-shrink: 0; }
        .wh-subtab { padding: 2px 8px; font-size: 11px; border: 1px solid #bbb; border-bottom: none; background: #d0d0d0; cursor: pointer; border-radius: 2px 2px 0 0; }
        .wh-subtab.active { background: #fff; font-weight: bold; }
        .wh-filter { padding: 4px 6px; border-bottom: 1px solid #e0e0e0; background: #f5f5f5; display: flex; align-items: center; gap: 4px; font-size: 11px; flex-shrink: 0; }
        .wh-filter select { font-size: 11px; border: 1px solid #ccc; padding: 1px 4px; }
        .wh-filter button { border: 1px solid #999; background: #e8e8e8; padding: 1px 5px; cursor: pointer; font-size: 13px; color: green; }
        .wh-filter label { display: flex; align-items: center; gap: 3px; font-size: 11px; }

        /* activity filter */
        .act-filter { padding: 4px 6px; border-bottom: 1px solid #e0e0e0; background: #f5f5f5; display: flex; align-items: center; gap: 4px; font-size: 11px; flex-shrink: 0; flex-wrap: wrap; }
        .act-filter input[type=date] { font-size: 11px; border: 1px solid #ccc; padding: 1px 4px; }
        .act-filter button { border: 1px solid #999; background: #e8e8e8; padding: 1px 5px; cursor: pointer; font-size: 13px; color: green; }

        /* stat table */
        .stat-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .stat-table th { background: #f0f0f0; border: 1px solid #ccc; padding: 3px 8px; font-weight: normal; color: #555; text-align: right; }
        .stat-table th:first-child { text-align: left; width: 50%; }
        .stat-table td { border: 1px solid #eee; padding: 2px 8px; }
        .stat-table td:first-child { color: #444; }
        .stat-table td input { width: 55px; text-align: right; border: 1px solid #ccc; font-size: 11px; padding: 1px 3px; background: #fafafa; }
        .stat-table td:last-child input { width: 75px; }
        .stat-table tr:nth-child(even) { background: #fafafa; }
        .stat-table tr.selected td { background: #c8d8f0; }
    </style>
@endpush

@section('content')
<div class="fa-window" x-data="{ activeTab: 'detail' }">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 8px; align-items: center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            <span>Product Asset</span>
        </div>
        <div style="display: flex; gap: 4px;">
            <div style="width:14px;height:14px;background:#ddd;border:1px solid #999;cursor:pointer;"></div>
            <div style="width:14px;height:14px;background:#ddd;border:1px solid #999;cursor:pointer;"></div>
            <div style="width:14px;height:14px;background:#e81123;border:1px solid #999;cursor:pointer;"></div>
        </div>
    </div>

    @include('partials.ribbon_toolbar')
    <div class="fa-tabs">
        <div class="fa-tab" :class="activeTab === 'detail' ? 'active' : ''" @click="activeTab = 'detail'">RECORD DETAIL</div>
        <div class="fa-tab" :class="activeTab === 'list' ? 'active' : ''" @click="activeTab = 'list'">PRODUCT LIST & TOOLS</div>
        <div class="fa-tab" :class="activeTab === 'budget' ? 'active' : ''" @click="activeTab = 'budget'">PRODUCT BUDGET</div>
        <div class="fa-tab" :class="activeTab === 'summary' ? 'active' : ''" @click="activeTab = 'summary'">PRODUCT SUMMARY</div>
    </div>

    <div class="fa-pane" :class="activeTab === 'detail' ? 'active' : ''"
         x-data="productDetailManager()"
         x-init="init()"
         x-on:ribbon-action.window="handleRibbonAction($event.detail)">
        
        <div class="main-content">
            <div class="form-grid">
                <!-- Left Column -->
                <div class="form-col-left">
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" x-model="cur.category">
                            <option>ACCESSORIES</option>
                            <option>CHEMICAL</option>
                            <option>ELECTRICAL</option>
                            <option>FURNITURE</option>
                            <option>MECHANICAL</option>
                            <option>SAFETY</option>
                        </select>
                        <input type="checkbox" :checked="cur.cat_chk" @change="cur.cat_chk = $event.target.checked" style="margin-left:5px;">
                    </div>
                    <div class="form-group">
                        <label>Supplier / Brand</label>
                        <select class="form-control" x-model="cur.supplier">
                            <option>LAIN-LAIN</option>
                            <option>PT. MEGA SUPPLIER</option>
                            <option>CV. BERKAH JAYA</option>
                            <option>PT. SUMBER MAKMUR</option>
                        </select>
                        <input type="checkbox" :checked="cur.sup_chk" @change="cur.sup_chk = $event.target.checked" style="margin-left:5px;">
                    </div>
                    <div class="form-group">
                        <label>Model / Type</label>
                        <select class="form-control" x-model="cur.model">
                            <option>DRY CHEMICAL</option>
                            <option>CO2</option>
                            <option>FOAM</option>
                            <option>WATER</option>
                        </select>
                        <input type="checkbox" :checked="cur.model_chk" @change="cur.model_chk = $event.target.checked" style="margin-left:5px;">
                    </div>
                    <div class="form-group">
                        <label>Quality, Style / Color</label>
                        <select class="form-control" x-model="cur.quality">
                            <option>POWDER 2.5KG</option>
                            <option>POWDER 6KG</option>
                            <option>LIQUID 9L</option>
                            <option>GREEN</option>
                            <option>BLACK</option>
                        </select>
                        <input type="checkbox" :checked="cur.qual_chk" @change="cur.qual_chk = $event.target.checked" style="margin-left:5px;">
                    </div>

                    <div class="form-row" style="margin-top: 10px;">
                        <div class="form-group">
                            <label>Internal Code</label>
                            <input type="text" class="form-control" x-model="cur.internal_code">
                        </div>
                        <div class="form-group">
                            <label style="width: 70px;">Ext. Code</label>
                            <input type="text" class="form-control" x-model="cur.ext_code">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" class="form-control" x-model="cur.product_name" style="background: #fdfdfd;">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Physical Units</label>
                            <input type="text" class="form-control" x-model="cur.unit" style="background: #fdfdfd;">
                        </div>
                        <div class="form-group">
                            <label style="width: 100px;">Volume in Units</label>
                            <input type="number" class="form-control" x-model="cur.volume" style="text-align:right;">
                        </div>
                    </div>

                    <!-- Accounting Panel -->
                    <div class="box-panel" style="margin-top: 15px;">
                        <div class="panel-header">Accounting</div>
                        <div class="panel-body" style="padding: 5px 10px;">
                            <div class="form-group">
                                <label>Inventory Account</label>
                                <select class="form-control" x-model="cur.inv_account">
                                    <option>BIAYA KEBERSIHAN</option>
                                    <option>PERSEDIAAN BARANG</option>
                                    <option>BIAYA OPERASIONAL</option>
                                    <option>ASET TETAP</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>COGS Account</label>
                                <select class="form-control" x-model="cur.cogs_account">
                                    <option>HUTANG VENDOR</option>
                                    <option>HARGA POKOK PENJUALAN</option>
                                    <option>BEBAN POKOK</option>
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Cost Center</label>
                                    <select class="form-control" x-model="cur.cost_center">
                                        <option value=""></option>
                                        <option>GA</option>
                                        <option>IT</option>
                                        <option>FINANCE</option>
                                        <option>OPERATIONS</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label style="width: 90px;">Account Dept.</label>
                                    <select class="form-control" x-model="cur.account_dept">
                                        <option value=""></option>
                                        <option>DEPT-001</option>
                                        <option>DEPT-002</option>
                                        <option>DEPT-003</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="form-col-right">
                    <div style="display: flex; justify-content: flex-end; gap: 15px; margin-bottom: 5px;">
                        <label class="checkbox-item"><input type="checkbox" x-model="cur.not_for_count"> Not for Count</label>
                        <label class="checkbox-item"><input type="checkbox" x-model="cur.services"> Services</label>
                        <label class="checkbox-item"><input type="checkbox" x-model="cur.discontinue"> Discontinue</label>
                    </div>

                    <div class="image-placeholder">
                        <div style="text-align: center;">
                            Fill with your image<br>
                            (W:292px H:160px) Max
                        </div>
                    </div>

                    <!-- Conditions Panel -->
                    <div class="box-panel">
                        <div class="panel-header">Conditions</div>
                        <div class="panel-body" style="padding: 5px 10px;">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Minimum Stock</label>
                                    <input type="number" class="form-control" x-model="cur.min_stock" style="text-align: right;">
                                </div>
                                <div class="form-group">
                                    <label style="width: 80px;">Cost Price</label>
                                    <input type="text" class="form-control" x-model="cur.cost_price" style="text-align: right;">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>On Order</label>
                                    <input type="number" class="form-control" x-model="cur.on_order" style="text-align: right;">
                                </div>
                                <div class="form-group">
                                    <label style="width: 80px;">Sales Price</label>
                                    <input type="text" class="form-control" x-model="cur.sales_price" style="text-align: right;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="align-items: flex-start; margin-top: 10px;">
                        <label>NOTE</label>
                        <textarea class="form-control textarea-control" x-model="cur.note"></textarea>
                    </div>

                    <div class="stat-grid">
                        <div class="stat-row">
                            <div class="stat-item">
                                <label>ON TRANSIT</label>
                                <input type="number" x-model="cur.on_transit" readonly>
                            </div>
                            <div class="stat-item">
                                <label>STOCK</label>
                                <input type="text" x-model="cur.stock" readonly>
                            </div>
                            <div class="stat-item">
                                <label>BALANCE</label>
                                <input type="number" x-model="cur.balance" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pager-footer">
            <button class="pager-btn" @click="goFirst()">|◀</button>
            <button class="pager-btn" @click="prevRecord()">◀</button>
            <span style="margin: 0 10px;">Record <span x-text="currentIndex + 1"></span> of <span x-text="records.length"></span></span>
            <button class="pager-btn" @click="nextRecord()">▶</button>
            <button class="pager-btn" @click="goLast()">▶|</button>
        </div>
    </div><!-- /fa-pane detail -->
        <!-- END TAB 1 -->

    <div class="fa-pane" :class="activeTab === 'list' ? 'active' : ''"
         x-data="productAssets()"
         x-init="init()">

        <div class="list-split-layout">
            <!-- ═══ LEFT: Product Grid ═══ -->
            <div class="list-col-left">
                <div class="grid-toolbar">
                    <span>Drag a column header here to group by that column</span>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <div x-show="loading" style="width:12px;height:12px;border:2px solid #3a5a8f;border-top-color:transparent;border-radius:50%;animation:spin 0.6s linear infinite;"></div>
                        <button class="grid-search" title="Search">⚲</button>
                    </div>
                </div>
                <div class="data-table-container">
                    <table class="data-table" id="productGrid">
                        <thead>
                            <tr>
                                <th style="width:20px;"></th>
                                <th>CODE</th>
                                <th>PRODUCT NAME</th>
                                <th style="text-align:right;">COST PRICE</th>
                                <th style="text-align:right;">PRICE (GR)</th>
                                <th style="text-align:right;">PRICE (EC)</th>
                                <th style="text-align:right;">MN. ST</th>
                                <th style="text-align:right;">STOCK</th>
                                <th style="text-align:right;">ON ORDER</th>
                                <th style="width:28px;text-align:center;">DC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr :class="{'selected': selectedProduct && selectedProduct.id === {{ $product->id }}}"
                                @click="selectProduct({{ json_encode($product) }}, {{ $loop->index }})"
                                style="cursor: pointer;">
                                <td style="text-align:center; width: 25px;">
                                    <template x-if="selectedProduct && selectedProduct.id === {{ $product->id }}">
                                        <span>▶</span>
                                    </template>
                                </td>
                                <td>{{ $product->code }}</td>
                                <td>{{ Str::limit($product->product_name, 28) }}</td>
                                <td class="num">{{ number_format($product->cost_price, 3, ',', '.') }}</td>
                                <td class="num">{{ $product->price_gr ? number_format($product->price_gr, 2, ',', '.') : '' }}</td>
                                <td class="num">{{ $product->price_ec ? number_format($product->price_ec, 2, ',', '.') : '' }}</td>
                                <td class="num">{{ $product->min_stock }}</td>
                                <td class="num">{{ $product->stock }}</td>
                                <td class="num">{{ $product->on_order }}</td>
                                <td class="chk"><input type="checkbox" {{ $product->discontinue ? 'checked' : '' }} disabled style="pointer-events:none;"></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" style="text-align:center;padding:20px;color:#888;">No Product Assets Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination-controls">
                    <a href="{{ $products->url(1) }}" class="page-btn" title="First">|◀</a>
                    <a href="{{ $products->previousPageUrl() }}" class="page-btn" title="Prev">◀</a>
                    <span style="margin:0 8px;">Record {{ $products->firstItem() ?? 0 }} of {{ $products->total() }}</span>
                    <a href="{{ $products->nextPageUrl() }}" class="page-btn" title="Next">▶</a>
                    <a href="{{ $products->url($products->lastPage()) }}" class="page-btn" title="Last">▶|</a>
                </div>
            </div><!-- /list-col-left -->

            <!-- ═══ RIGHT: Detail Panels ═══ -->
            <div class="list-col-right" :style="selectedProduct ? 'display:flex;' : 'display:none;'">

                <!-- Loading Overlay -->
                <div x-show="loading"
                     style="position:absolute;inset:0;background:rgba(255,255,255,0.75);z-index:20;display:flex;align-items:center;justify-content:center;">
                    <div style="width:18px;height:18px;border:2px solid #3a5a8f;border-top-color:transparent;border-radius:50%;animation:spin 0.7s linear infinite;"></div>
                </div>

                <!-- Panel Tabs -->
                <div class="panel-tabs">
                    <div class="panel-tab" :class="{'active': activePanel==='pnl-statistics'}" @click="setActivePanel('pnl-statistics')">Statistics</div>
                    <div class="panel-tab" :class="{'active': activePanel==='pnl-summary'}"    @click="setActivePanel('pnl-summary')">Summary</div>
                    <div class="panel-tab" :class="{'active': activePanel==='pnl-activity'}"   @click="setActivePanel('pnl-activity')">Activity</div>
                    <div class="panel-tab" :class="{'active': activePanel==='pnl-warehouse'}"  @click="setActivePanel('pnl-warehouse')">Warehouse</div>
                </div>

                <!-- STATISTICS -->
                <div class="panel-content" x-show="activePanel==='pnl-statistics'" style="overflow-y:auto; padding-top: 10px;">
                    <table class="stat-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width: 70px;">STOCK</th>
                                <th style="width: 120px;">BALANCE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Begining Balance</td>
                                <td><input type="number" :value="pd ? pd.beg_stock : 0" readonly></td>
                                <td><input type="text" :value="pd ? formatCurr(pd.beg_balance) : '0,00'" readonly></td>
                            </tr>
                            <tr>
                                <td>Current Balance</td>
                                <td><input type="number" :value="pd ? pd.cur_stock : 0" readonly></td>
                                <td><input type="text" :value="pd ? formatCurr(pd.cur_balance) : '0,00'" readonly></td>
                            </tr>
                            <template x-for="m in months" :key="m.key">
                                <tr>
                                    <td x-text="m.label"></td>
                                    <td><input type="number" :value="pd ? (pd.monthly_stock[m.key] || 0) : 0" readonly></td>
                                    <td><input type="text" :value="pd ? formatCurr(pd.monthly_balance[m.key] || 0) : '0,00'" readonly></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- SUMMARY -->
                <div x-show="activePanel==='pnl-summary'" style="display:none;flex-direction:column;flex:1;overflow:hidden;">
                    <div class="panel-content" style="overflow-y:auto;flex:1;">
                        <table class="stat-table" style="margin-top: 0;">
                            <thead>
                                <tr><th style="text-align:left; padding-left: 10px;">WAREHOUSE</th><th>STOCK</th><th>ON TRANSIT</th></tr>
                            </thead>
                            <tbody>
                                <template x-for="(wh,idx) in (pd ? pd.warehouses : [])" :key="idx">
                                    <tr :class="{'selected': idx===0}" style="cursor:pointer;">
                                        <td style="text-align: left; padding-left: 10px; font-size: 10px;"><span x-show="idx===0">▶ </span><span x-text="wh.name"></span></td>
                                        <td style="text-align:right;"><input type="text" :value="wh.stock" readonly style="width: 60px; height: 18px; border:none; background:transparent; font-size: 11px;"></td>
                                        <td style="text-align:right;"><input type="text" :value="wh.on_transit" readonly style="width: 60px; height: 18px; border:none; background:transparent; font-size: 11px;"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <input type="text" :value="pd ? pd.warehouses.reduce((s,w)=>s+w.stock,0) : 0" readonly>
                        <input type="text" :value="pd ? pd.warehouses.reduce((s,w)=>s+w.on_transit,0) : 0" readonly>
                    </div>
                    <div class="panel-pager">
                        <button class="pager-btn" style="padding:0 3px;">|◀</button><button class="pager-btn" style="padding:0 3px;">◀</button>
                        <span style="padding:0 6px;" x-text="'Record 1 of '+(pd ? pd.warehouses.length : 0)"></span>
                        <span style="margin-left:4px;color:#999;">|◄</span>
                    </div>
                </div>

                <!-- ACTIVITY -->
                <div x-show="activePanel==='pnl-activity'" style="display:none;flex-direction:column;flex:1;overflow:hidden;">
                    <div class="act-filter">
                        <span>Period</span>
                        <input type="date" value="{{ date('Y-m-d') }}" style="width: 110px;">
                        <span>Thru</span>
                        <input type="date" value="{{ date('Y-m-d') }}" style="width: 110px;">
                        <button class="grid-search" style="font-size: 10px; padding: 0 4px; color: green; font-weight: bold;">⟳</button>
                    </div>
                    <div class="panel-content" style="overflow-y:auto;flex:1;">
                        <table class="stat-table" style="margin-top: 0; min-width: 600px;">
                            <thead>
                                <tr>
                                    <th style="width: 25px; border: 1px solid #ccc;"></th>
                                    <th style="text-align:left; padding-left: 5px; border: 1px solid #ccc;">DATE</th>
                                    <th style="text-align:left; border: 1px solid #ccc;">USERNO</th>
                                    <th style="border: 1px solid #ccc;">IN</th>
                                    <th style="border: 1px solid #ccc;">OUT</th>
                                    <th style="border: 1px solid #ccc;">STOCK</th>
                                    <th style="border: 1px solid #ccc;">DEBET</th>
                                    <th style="border: 1px solid #ccc;">CREDIT</th>
                                    <th style="border: 1px solid #ccc;">BALANCE</th>
                                    <th style="border: 1px solid #ccc;">COST</th>
                                    <th style="border: 1px solid #ccc; width: 20px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-if="!pd || !pd.activities || pd.activities.length === 0">
                                    <tr><td colspan="11" style="text-align:center;padding:20px;color:#aaa;">No activity in selected period</td></tr>
                                </template>
                                <template x-if="pd && pd.activities && pd.activities.length > 0">
                                    <template x-for="act in pd.activities" :key="act.id">
                                        <tr style="border-bottom: 1px solid #f0f0f0;">
                                            <td style="border: 1px solid #eee;"></td>
                                            <td style="text-align: left; padding-left: 5px; border: 1px solid #eee;" x-text="act.date"></td>
                                            <td style="text-align: left; border: 1px solid #eee;" x-text="act.userno"></td>
                                            <td style="text-align:right; border: 1px solid #eee;" x-text="act.in"></td>
                                            <td style="text-align:right; border: 1px solid #eee;" x-text="act.out"></td>
                                            <td style="text-align:right; border: 1px solid #eee;" x-text="act.sto"></td>
                                            <td style="text-align:right; border: 1px solid #eee;">0</td>
                                            <td style="text-align:right; border: 1px solid #eee;">0</td>
                                            <td style="text-align:right; border: 1px solid #eee;">0</td>
                                            <td style="text-align:right; border: 1px solid #eee;">0</td>
                                            <td style="border: 1px solid #eee;"></td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <input type="text" value="0" readonly>
                        <input type="text" value="0" readonly>
                    </div>
                    <div class="panel-pager">
                        <button class="pager-btn" style="padding:0 3px;">|◀</button><button class="pager-btn" style="padding:0 3px;">◀</button>
                        <span style="padding:0 6px;" x-text="'Record 0 of '+(pd && pd.activities ? pd.activities.length : 0)"></span>
                        <button class="pager-btn" style="padding:0 3px;">▶</button><button class="pager-btn" style="padding:0 3px;">▶|</button>
                    </div>
                </div>

                <!-- WAREHOUSE -->
                <div x-show="activePanel==='pnl-warehouse'" style="display:none;flex-direction:column;flex:1;overflow:hidden;">
                    <div class="wh-subtabs">
                        <div class="wh-subtab" :class="{'active': whSubTab==='stock'}"    @click="whSubTab='stock'">Stock Detail</div>
                        <div class="wh-subtab" :class="{'active': whSubTab==='stats'}"    @click="whSubTab='stats'">Statistics</div>
                        <div class="wh-subtab" :class="{'active': whSubTab==='activity'}" @click="whSubTab='activity'">Activity</div>
                    </div>

                    <!-- SUB: Stock Detail -->
                    <template x-if="whSubTab==='stock'">
                        <div style="display:flex;flex-direction:column;flex:1;overflow:hidden;">
                            <div class="wh-filter">
                                <span style="font-size: 10px;">Select Warehouse</span>
                                <select style="width: 150px; font-size: 11px;">
                                    <template x-for="(wh,i) in (pd ? pd.warehouses : [])" :key="i">
                                        <option x-text="wh.name"></option>
                                    </template>
                                </select>
                                <button class="grid-search" style="font-size: 10px; padding: 0 4px; color: green; font-weight: bold;">⟳</button>
                                <label style="font-size: 10px;"><input type="checkbox"> Recalculate stock</label>
                            </div>
                            <div class="panel-content" style="overflow-y:auto;flex:1;">
                                <table class="stat-table" style="margin-top: 0;">
                                    <thead>
                                        <tr>
                                            <th style="width:25px; border: 1px solid #ccc;"></th>
                                            <th style="text-align:left; border: 1px solid #ccc; padding-left: 5px;">CODE</th>
                                            <th style="text-align:left; border: 1px solid #ccc;">PRODUCT</th>
                                            <th style="border: 1px solid #ccc; width: 60px;">STOCK</th>
                                            <th style="border: 1px solid #ccc; width: 60px;">TRANSIT</th>
                                            <th style="border: 1px solid #ccc; width: 20px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="6" style="text-align:center;padding:20px;color:#aaa;">No data for selected warehouse</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="panel-footer">
                                <input type="text" value="0" readonly>
                                <input type="text" value="0" readonly>
                            </div>
                            <div class="panel-pager">
                                <button class="pager-btn" style="padding:0 3px;">|◀</button><button class="pager-btn" style="padding:0 3px;">◀</button>
                                <span style="padding:0 6px;">Record 0 of 0</span>
                                <button class="pager-btn" style="padding:0 3px;">▶</button><button class="pager-btn" style="padding:0 3px;">▶|</button>
                            </div>
                        </div>
                    </template>

                    <!-- SUB: Statistics -->
                    <template x-if="whSubTab==='stats'">
                        <div style="display:flex;flex-direction:column;flex:1;overflow:hidden; padding-top: 10px;">
                            <table class="stat-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="width: 80px;">STOCK</th>
                                        <th style="width: 120px;">BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Current Stock</td>
                                        <td><input type="text" value="0,00" readonly></td>
                                        <td><input type="number" value="0" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>Begining Balance</td>
                                        <td><input type="text" value="0,00" readonly></td>
                                        <td><input type="number" value="0" readonly></td>
                                    </tr>
                                    <template x-for="m in months" :key="m.key">
                                        <tr>
                                            <td x-text="m.label"></td>
                                            <td><input type="text" value="0,00" readonly></td>
                                            <td><input type="number" value="0" readonly></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>

                    <!-- SUB: Activity -->
                    <template x-if="whSubTab==='activity'">
                        <div style="display:flex;flex-direction:column;flex:1;overflow:hidden;">
                            <div class="act-filter" style="border-top: 1px solid #ddd;">
                                <span>Period</span>
                                <input type="date" value="{{ date('Y-m-d') }}" style="width: 110px;">
                                <span>Thru</span>
                                <input type="date" value="{{ date('Y-m-d') }}" style="width: 110px;">
                                <button class="grid-search" style="font-size: 10px; padding: 0 4px; color: green; font-weight: bold;">⟳</button>
                            </div>
                            <div class="panel-content" style="overflow-y:auto;flex:1;">
                                <table class="stat-table" style="margin-top: 0;">
                                    <thead>
                                        <tr>
                                            <th style="width: 25px; border: 1px solid #ccc;"></th>
                                            <th style="text-align:left; padding-left: 5px; border: 1px solid #ccc;">DATE</th>
                                            <th style="text-align:left; border: 1px solid #ccc;">USERNO</th>
                                            <th style="border: 1px solid #ccc;">IN</th>
                                            <th style="border: 1px solid #ccc;">OUT</th>
                                            <th style="border: 1px solid #ccc;">STOCK</th>
                                            <th style="border: 1px solid #ccc; width: 20px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="7" style="text-align:center;padding:20px;color:#aaa;">No warehouse activity</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="panel-footer">
                                <input type="text" value="0" readonly>
                                <input type="text" value="0" readonly>
                            </div>
                            <div class="panel-pager">
                                <button class="pager-btn" style="padding:0 3px;">|◀</button><button class="pager-btn" style="padding:0 3px;">◀</button>
                                <span style="padding:0 6px;">Record 0 of 0</span>
                                <button class="pager-btn" style="padding:0 3px;">▶</button><button class="pager-btn" style="padding:0 3px;">▶|</button>
                            </div>
                        </div>
                    </template>
                </div> <!-- /pnl-warehouse -->              </template>
                    </div>

                </div><!-- /list-col-right -->
            </div><!-- /list-split-layout -->
    </div><!-- /fa-pane list -->

    <div class="fa-pane" :class="activeTab === 'budget' ? 'active' : ''" 
         x-data='productBudget({!! json_encode($customers) !!}, {!! json_encode($products) !!})' x-init="init()">
        <div class="main-content" style="display: flex; flex-direction: column; height: 100%;">
            <!-- Filter Bar -->
            <div class="budget-filter-bar" style="padding: 10px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #ccc;">
                <label style="font-size: 11px; white-space: nowrap;">Select Customer</label>
                <div class="searchable-select-container" style="flex: 1; max-width: 400px;">
                    <input type="text" class="searchable-select-input" placeholder="" x-model="customerSearch" @focus="showCustomerDropdown = true" @click="showCustomerDropdown = true" @click.away="showCustomerDropdown = false" style="width: 100%; height: 22px; font-size: 11px; border: 1px solid #aaa;">
                    <div class="searchable-select-dropdown" x-show="showCustomerDropdown" style="max-height: 250px;">
                        <template x-for="cust in filteredCustomers" :key="cust.id">
                            <div class="searchable-select-option" @click="selectCustomer(cust)" x-text="cust.counter_name + (cust.code ? ' - ' + cust.code : '')"></div>
                        </template>
                    </div>
                </div>
                
                <label style="font-size: 11px; white-space: nowrap; margin-left: 10px;">Select Month</label>
                <select class="month-select" x-model="$store.assetSync.selectedMonth" style="height: 22px; font-size: 11px; border: 1px solid #aaa; padding: 0 5px;">
                    @php
                        $months = [
                            '01-2026' => 'January, 2026',
                            '02-2026' => 'February, 2026',
                            '03-2026' => 'March, 2026',
                            '04-2026' => 'April, 2026',
                            '05-2026' => 'May, 2026',
                            '06-2026' => 'June, 2026',
                            '07-2026' => 'July, 2026',
                            '08-2026' => 'August, 2026',
                            '09-2026' => 'September, 2026',
                            '10-2026' => 'October, 2026',
                            '11-2026' => 'November, 2026',
                            '12-2026' => 'December, 2026',
                        ];
                    @endphp
                    @foreach($months as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <button class="refresh-btn" @click="refreshData()" title="Refresh Data" style="background: none; border: 1px solid #aaa; height: 22px; padding: 0 5px; cursor: pointer; color: #3a5a8f;">↻</button>
            </div>

            <!-- Grid Toolbar -->
            <div class="grid-toolbar" style="padding: 5px 10px; background: #f5f5f5; border-bottom: 1px solid #ccc; display: flex; align-items: center;">
                <span style="font-size: 11px; color: #666;">Drag a column header here to group by that column</span>
                <button style="margin-left: auto; background: none; border: none; cursor: pointer; font-size: 14px; color: #666;">🔍</button>
            </div>

            <!-- Table -->
            <div class="budget-table-container" style="flex: 1; overflow: auto; background: #fff;">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f0f0f0;">
                            <th style="width: 25px; border: 1px solid #ccc;"></th>
                            <th style="width: 120px; border: 1px solid #ccc; text-align: left; padding: 4px 8px; font-weight: normal; font-size: 11px;">CODE</th>
                            <th style="border: 1px solid #ccc; text-align: left; padding: 4px 8px; font-weight: normal; font-size: 11px;">PRODUCT NAME</th>
                            <th style="width: 150px; border: 1px solid #ccc; text-align: left; padding: 4px 8px; font-weight: normal; font-size: 11px;">BUDGET</th>
                            <th style="width: 150px; border: 1px solid #ccc; text-align: left; padding: 4px 8px; font-weight: normal; font-size: 11px;">REALITY</th>
                            <th style="width: 150px; border: 1px solid #ccc; text-align: left; padding: 4px 8px; font-weight: normal; font-size: 11px;">PROFIT/LOSS</th>
                            <th style="width: 150px; border: 1px solid #ccc;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, idx) in items" :key="item.code">
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="text-align: center; border: 1px solid #eee;"><span x-show="idx===0">▶</span></td>
                                <td style="padding: 3px 8px; border: 1px solid #eee;" x-text="item.code"></td>
                                <td style="padding: 3px 8px; border: 1px solid #eee;" x-text="item.name"></td>
                                <td style="padding: 3px 8px; border: 1px solid #eee; text-align: right;"><input type="number" x-model="item.budget" @input="calculateTotals()" style="width: 100%; border: none; text-align: right; background: transparent; font-size: 11px;"></td>
                                <td style="padding: 3px 8px; border: 1px solid #eee; text-align: right;"><input type="number" x-model="item.reality" @input="calculateTotals()" style="width: 100%; border: none; text-align: right; background: transparent; font-size: 11px;"></td>
                                <td style="padding: 3px 8px; border: 1px solid #eee; text-align: right;" :style="item.profit < 0 ? 'color: red' : 'color: green'" x-text="formatCurr(item.profit)"></td>
                                <td style="border: 1px solid #eee;"></td>
                            </tr>
                        </template>
                        <tr x-show="items.length === 0">
                            <td colspan="7" style="text-align: center; padding: 40px; color: #999; font-size: 11px;">Select a customer to view budget details</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer Summary Bar -->
            <div class="budget-summary-bar" style="background: #f0f0f0; border-top: 1px solid #ccc; padding: 5px 10px; display: flex; justify-content: flex-end; gap: 2px;">
                <div style="width: 150px; display: flex; justify-content: flex-end;">
                    <input type="text" :value="formatCurr(totalBudget)" readonly style="width: 140px; height: 22px; text-align: right; border: 1px solid #ccc; font-size: 11px; background: #fff; padding: 0 5px;">
                </div>
                <div style="width: 150px; display: flex; justify-content: flex-end;">
                    <input type="text" :value="formatCurr(totalReality)" readonly style="width: 140px; height: 22px; text-align: right; border: 1px solid #ccc; font-size: 11px; background: #fff; padding: 0 5px;">
                </div>
                <div style="width: 150px; display: flex; justify-content: flex-end;">
                    <input type="text" :value="formatCurr(totalProfit)" readonly style="width: 140px; height: 22px; text-align: right; border: 1px solid #ccc; font-size: 11px; background: #fff; padding: 0 5px;">
                </div>
                <div style="width: 150px;"></div>
            </div>
        </div>
    </div><!-- /fa-pane budget -->
    
    <div class="fa-pane" :class="activeTab === 'summary' ? 'active' : ''" 
         x-data='productSummary({!! json_encode($customers) !!}, {!! json_encode($products) !!})' x-init="init()">
        <div class="main-content">
            <!-- Filter Bar -->
            <div class="budget-filter-bar">
                <div class="budget-filter-group" style="flex: 1;">
                    <label>Select Customer</label>
                    <div class="searchable-select-container">
                        <input type="text" 
                               class="searchable-select-input" 
                               placeholder="Select Customer..." 
                               x-model="customerSearch"
                               @focus="showCustomerDropdown = true"
                               @click="showCustomerDropdown = true"
                               @click.away="showCustomerDropdown = false">
                        <div class="searchable-select-dropdown" x-show="showCustomerDropdown" style="max-height: 250px;">
                            <div class="searchable-select-header">CUSTOMER</div>
                            <template x-for="cust in filteredCustomers" :key="cust.id">
                                <div class="searchable-select-option" 
                                     @click="selectCustomer(cust)"
                                     x-text="cust.counter_name + (cust.code ? ' - ' + cust.code : '')"></div>
                            </template>
                            <div x-show="filteredCustomers.length === 0" class="searchable-select-option" style="color: #94a3b8; cursor: default;">No results found</div>
                        </div>
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #64748b;">▼</span>
                    </div>
                </div>

                <div class="budget-filter-group">
                    <label>Select Month</label>
                    <select class="month-select" x-model="$store.assetSync.selectedMonth">
                        @php
                            $months = [
                                '01-2026' => 'January, 2026',
                                '02-2026' => 'February, 2026',
                                '03-2026' => 'March, 2026',
                                '04-2026' => 'April, 2026',
                                '05-2026' => 'May, 2026',
                                '06-2026' => 'June, 2026',
                                '07-2026' => 'July, 2026',
                                '08-2026' => 'August, 2026',
                                '09-2026' => 'September, 2026',
                                '10-2026' => 'October, 2026',
                                '11-2026' => 'November, 2026',
                                '12-2026' => 'December, 2026',
                            ];
                        @endphp
                        @foreach($months as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="refresh-btn" @click="refreshData()" title="Refresh Data">
                        <span style="font-size: 1rem;">↻</span>
                    </button>
                </div>

                <div class="budget-filter-group">
                   <button class="icon-btn" style="margin-left: 20px; cursor: pointer;" @click="refreshData()" title="Search">
                        <span style="font-size: 1.2rem;">🔍</span>
                    </button>
                </div>
            </div>
            
            <div class="grid-toolbar" style="border-top: 1px solid #aaa; border-bottom: 1px solid #aaa;">
                <span style="font-size: 11px;">Drag a column header here to group by that column</span>
                <button class="grid-search" style="margin-left: auto;">⚲</button>
            </div>

            <div class="budget-table-container" style="flex:1;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:25px; border: 1px solid #aaa;"></th>
                            <th style="width: 150px; border: 1px solid #aaa;">CODE</th>
                            <th style="border: 1px solid #aaa;">PRODUCT NAME</th>
                            <th style="width: 150px; text-align:right; border: 1px solid #aaa;">BUDGET</th>
                            <th style="width: 150px; text-align:right; border: 1px solid #aaa;">REALITY</th>
                            <th style="width: 150px; text-align:right; border: 1px solid #aaa;">PROFIT/LOSS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item,idx) in items" :key="item.code">
                            <tr>
                                <td style="text-align:center;"><span x-show="idx===0">▶</span></td>
                                <td x-text="item.code"></td>
                                <td x-text="item.name"></td>
                                <td class="num"><input type="number" class="stat-input" x-model="item.budget" @input="calculateTotals()"></td>
                                <td class="num"><input type="number" class="stat-input" x-model="item.reality" @input="calculateTotals()"></td>
                                <td class="num" :style="item.profit < 0 ? 'color: #ef4444' : 'color: #10b981'" x-text="formatCurr(item.profit)"></td>
                            </tr>
                        </template>
                        <tr x-show="items.length === 0">
                            <td colspan="6" style="text-align: center; padding: 20px; color: #64748b;">Select a customer to view budget details</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Summary Bar (Footer Totals) -->
            <div class="budget-summary-bar" style="justify-content: flex-end; gap: 2px; border-top: 1px solid #aaa;">
                <input type="text" :value="formatCurr(totalBudget)" readonly style="width: 150px; text-align:right;">
                <input type="text" :value="formatCurr(totalReality)" readonly style="width: 150px; text-align:right;">
                <input type="text" :value="formatCurr(totalProfit)" readonly style="width: 150px; text-align:right;">
            </div>
        </div>
    </div><!-- /fa-pane budget -->
    
    <div class="fa-pane" :class="activeTab === 'summary' ? 'active' : ''" 
         x-data='productSummary({!! json_encode($customers) !!}, {!! json_encode($products) !!})' x-init="init()">
        <div class="main-content">
            <!-- Filter Bar -->
            <div class="budget-filter-bar">
                <div class="budget-filter-group" style="flex: 1;">
                    <label>Select Customer</label>
                    <div class="searchable-select-container">
                        <input type="text" class="searchable-select-input" placeholder="Select Customer..." x-model="customerSearch" @focus="showCustomerDropdown = true" @click="showCustomerDropdown = true" @click.away="showCustomerDropdown = false">
                        <div class="searchable-select-dropdown" x-show="showCustomerDropdown" style="max-height: 250px;">
                            <div class="searchable-select-header">CUSTOMER</div>
                            <template x-for="cust in filteredCustomers" :key="cust.id">
                                <div class="searchable-select-option" @click="selectCustomer(cust)" x-text="cust.counter_name + (cust.code ? ' - ' + cust.code : '')"></div>
                            </template>
                        </div>
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #64748b;">▼</span>
                    </div>
                </div>

                <div class="budget-filter-group">
                    <label>Select Month</label>
                    <select class="month-select" x-model="$store.assetSync.selectedMonth">
                        @foreach($months as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="refresh-btn" @click="refreshData()" title="Refresh Data">↻</button>
                </div>
                <div class="budget-filter-group">
                   <button class="icon-btn" style="margin-left: 20px; cursor: pointer;" @click="refreshData()" title="Search">🔍</button>
                </div>
            </div>

            <div class="grid-toolbar" style="border-top: 1px solid #aaa; border-bottom: 1px solid #aaa;">
                <span style="font-size: 11px;">Drag a column header here to group by that column</span>
                <button class="grid-search" style="margin-left: auto;">⚲</button>
            </div>

            <div class="budget-table-container" style="flex:1;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:25px; border: 1px solid #aaa;"></th>
                            <th style="width: 100px; border: 1px solid #aaa;">CODE</th>
                            <th style="border: 1px solid #aaa;">PRODUCT NAME</th>
                            <th style="width: 120px; border: 1px solid #aaa;">CATEGORY</th>
                            <th style="width: 100px; text-align:right; border: 1px solid #aaa;">BEG. BALC</th>
                            <th style="width: 100px; text-align:right; border: 1px solid #aaa;">STOCK IN</th>
                            <th style="width: 100px; text-align:right; border: 1px solid #aaa;">STOCK OUT</th>
                            <th style="width: 100px; text-align:right; border: 1px solid #aaa;">CR. STOCK</th>
                            <th style="width: 120px; text-align:right; border: 1px solid #aaa;">BALANCE</th>
                            <th style="width:20px; border: 1px solid #aaa;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item,idx) in items" :key="item.code">
                            <tr>
                                <td style="text-align:center;"><span x-show="idx===0">▶</span></td>
                                <td x-text="item.code"></td>
                                <td x-text="item.name"></td>
                                <td x-text="item.category"></td>
                                <td class="num" x-text="item.beg_bal || 0"></td>
                                <td class="num" x-text="item.stock_in || '0,00'"></td>
                                <td class="num" x-text="item.stock_out || '0,00'"></td>
                                <td class="num" x-text="item.cr_stock || '0,00'"></td>
                                <td class="num" x-text="formatCurr(item.balanceValue || 0)"></td>
                                <td></td>
                            </tr>
                        </template>
                        <tr x-show="items.length === 0">
                            <td colspan="10" style="text-align: center; padding: 20px; color: #64748b;">Select a customer to view summary details</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Summary Bar (Footer Totals) -->
            <div class="budget-summary-bar" style="justify-content: flex-end; gap: 2px; border-top: 1px solid #aaa; padding-right: 25px;">
                <input type="text" value="0" readonly style="width: 100px; text-align:right; border:1px solid #ccc; font-size:11px;">
                <input type="text" value="0" readonly style="width: 100px; text-align:right; border:1px solid #ccc; font-size:11px;">
                <input type="text" value="0" readonly style="width: 100px; text-align:right; border:1px solid #ccc; font-size:11px;">
                <input type="text" value="0" readonly style="width: 100px; text-align:right; border:1px solid #ccc; font-size:11px;">
                <input type="text" value="0" readonly style="width: 120px; text-align:right; border:1px solid #ccc; font-size:11px;">
            </div>
            
            <div class="pagination-controls" style="border-top: 1px solid #aaa;">
                <button class="page-btn">|◀</button>
                <button class="page-btn">◀</button>
                <span style="margin:0 8px;" x-text="'Record 1 of ' + (items.length || 0)"></span>
                <button class="page-btn">▶</button>
                <button class="page-btn">▶|</button>
            </div>
        </div>
        </div>
    </div><!-- /fa-pane summary -->
</div><!-- /fa-window -->
</div><!-- /fa-window -->

@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Shared store for syncing Budget and Summary tabs
        Alpine.store('assetSync', {
            selectedCustomer: null,
            selectedMonth: '04-2026',
            
            setCustomer(cust) {
                this.selectedCustomer = cust;
            },
            setMonth(m) {
                this.selectedMonth = m;
            }
        });

        // Tab 2: Product List & Tools
        Alpine.data('productAssets', () => ({
            records: @json($products->items()),
            selectedProduct: null,
            pd: null,               // product detail including stats/warehouse/activity
            loading: false,
            activePanel: 'pnl-statistics',
            whSubTab: 'stock',

            months: [
                { key: 'jan', label: 'January' },
                { key: 'feb', label: 'February' },
                { key: 'mar', label: 'March' },
                { key: 'apr', label: 'April' },
                { key: 'may', label: 'May' },
                { key: 'jun', label: 'June' },
                { key: 'jul', label: 'July' },
                { key: 'aug', label: 'August' },
                { key: 'sep', label: 'September' },
                { key: 'oct', label: 'October' },
                { key: 'nov', label: 'November' },
                { key: 'dec', label: 'December' },
            ],

            warehouseNames: [
                'HEAD OFFICE - CHEMICAL', 'KC MEDAN', 'KC PADANG',
                'KC PEKANBARU', 'KC JAMBI', 'KC BATAM',
                'KCP MDN HARYONO', 'KC PALEMBANG', 'KCP MDN CIREBON',
                'KC PALEMBANG SAYANGAN', 'KCP TANJUNG PINANG', 'KCP PEKANBARU RIAU',
                'KCP MDN PULO BRAYAN', 'KC BENGKULU',
            ],

            init() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('page')) {
                    const btn = document.getElementById('btn-tab-list');
                    if (btn) btn.click();
                }

                // Also listen for sync from Record Detail tab
                window.addEventListener('select-product-index', (e) => {
                    const idx = e.detail.index;
                    if (this.records[idx]) {
                        this.selectedProduct = this.records[idx];
                        this.pd = this._buildDetail(this.selectedProduct);
                    }
                });
            },

            // Build deterministic-but-varied mock detail from a product code seed
            _buildDetail(product) {
                const code = product.code;
                const cost = product.cost_price;
                const stock = product.stock;
                const seed = code.split('').reduce((s, c) => s + c.charCodeAt(0), 0);
                const rng = (min, max, offset = 0) => min + ((seed + offset) % (max - min + 1));

                const begStock = stock || rng(0, 50, 1);
                const begBalance = begStock * (cost || rng(5000, 500000, 2));
                const curStock = begStock + rng(0, 10, 3) - rng(0, 5, 4);
                const curBalance = curStock * (cost || rng(5000, 500000, 2));

                const monthly_stock = {};
                const monthly_balance = {};
                this.months.forEach((m, i) => {
                    const s = i < 3 ? rng(0, 20, i * 7) : 0;
                    monthly_stock[m.key] = s;
                    monthly_balance[m.key] = s * (cost || rng(5000, 500000, i));
                });

                const warehouses = this.warehouseNames.map((name, i) => ({
                    name,
                    stock: rng(0, 5, seed + i * 3),
                    on_transit: rng(0, 2, seed + i * 5),
                }));

                const activities = [];
                if (rng(0, 1, 9) === 1) {
                    const users = ['RIZKI', 'ADMIN', 'WAREHOUSE01', 'OPS02'];
                    for (let i = 0; i < rng(1, 5, seed); i++) {
                        const inQty = rng(0, 10, i * 3 + seed);
                        const outQty = rng(0, inQty, i * 2 + seed);
                        activities.push({
                            id: i,
                            date: `${2026 - Math.floor(i/3)}-0${(i % 12) + 1}-${String(rng(1, 28, i)).padStart(2,'0')}`,
                            userno: users[rng(0, 3, seed + i)],
                            in: inQty,
                            out: outQty,
                            sto: begStock + inQty - outQty,
                        });
                    }
                }

                return { 
                    ...product, // Include all fields from the product object
                    beg_stock: begStock, 
                    beg_balance: begBalance, 
                    cur_stock: curStock, 
                    cur_balance: curBalance, 
                    monthly_stock, 
                    monthly_balance, 
                    warehouses, 
                    activities 
                };
            },

            selectProduct(product, index) {
                console.log('Selecting product:', product.code); // Debug
                this.selectedProduct = product;
                this.loading = true;
                this.activePanel = 'pnl-statistics';

                // Force visibility of the right column
                this.$nextTick(() => {
                    const rightCol = document.querySelector('.list-col-right');
                    if (rightCol) {
                        rightCol.style.display = 'flex';
                    }
                });

                // Sync with Record Detail Tab if needed
                window.dispatchEvent(new CustomEvent('select-product-index', { 
                    detail: { index: index, product: product } 
                }));

                // Load detail data
                setTimeout(() => {
                    this.pd = this._buildDetail(product);
                    this.loading = false;
                    console.log('Detail loaded for:', product.code);
                }, 100);
            },

            setActivePanel(panelId) {
                this.activePanel = panelId;
            },

            formatCurr(num) {
                return parseFloat(num || 0).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            formatDate(dateString) {
                if (!dateString) return '-';
                const d = new Date(dateString);
                return d.toLocaleDateString('id-ID');
            }
        }));

        // Tab 3: Product Budget
        Alpine.data('productBudget', (dbCustomers, initialProducts) => ({
            customers: dbCustomers.length > 0 ? dbCustomers : [
                { id: 1, counter_name: 'PT. BANK MEGA SYARIAH KC BANDUNG', code: '' },
                { id: 2, counter_name: 'PT. BANK MEGA TBK - KCP Kramat Jati', code: '' },
                { id: 3, counter_name: 'PT. BANK MEGA TBK - KCP Kramat Raya', code: '' },
                { id: 4, counter_name: 'PT. Daily Dinamika Kreasi', code: '' },
                { id: 5, counter_name: 'ALLO FRESH INDONESIA', code: '' },
                { id: 6, counter_name: 'ASTON HOTEL', code: '' },
                { id: 7, counter_name: 'ASTON HOTEL', code: '-' },
                { id: 8, counter_name: 'BANK MEGA CARD CENTER KUNINGAN', code: '' },
            ],
            allProducts: initialProducts.data,
            items: [],
            customerSearch: '',
            showCustomerDropdown: false,
            totalBudget: 0,
            totalReality: 0,
            totalProfit: 0,

            init() {
                // Watch the shared store for customer changes
                this.$watch('$store.assetSync.selectedCustomer', (val) => {
                    if (val) {
                        this.customerSearch = val.counter_name + (val.code ? ' - ' + val.code : '');
                        this.refreshData();
                    }
                });
                this.$watch('$store.assetSync.selectedMonth', () => this.refreshData());
                
                // Set initial values from store
                if (this.$store.assetSync.selectedCustomer) {
                    const val = this.$store.assetSync.selectedCustomer;
                    this.customerSearch = val.counter_name + (val.code ? ' - ' + val.code : '');
                }
                this.refreshData();
            },

            get filteredCustomers() {
                if (!this.customerSearch) return this.customers.slice(0, 10);
                const search = this.customerSearch.toLowerCase();
                return this.customers.filter(c => 
                    (c.counter_name && c.counter_name.toLowerCase().includes(search)) || 
                    (c.code && c.code.toLowerCase().includes(search))
                ).slice(0, 10);
            },

            selectCustomer(cust) {
                this.$store.assetSync.selectedCustomer = cust;
                this.showCustomerDropdown = false;
            },

            refreshData() {
                const cust = this.$store.assetSync.selectedCustomer;
                if (!cust) {
                    this.items = [];
                    this.calculateTotals();
                    return;
                }

                // Deterministic seed based on customer ID and month
                const seed = cust.id + (this.$store.assetSync.selectedMonth.split('').reduce((s, c) => s + c.charCodeAt(0), 0));
                const rng = (min, max, offset) => min + ((seed + offset) % (max - min + 1));

                // Generate varied data per company
                this.items = this.allProducts.slice(0, 12).map((p, i) => {
                    const budget = rng(10, 200, i * 7);
                    const reality = rng(5, budget + 20, i * 13);
                    return {
                        code: p.code,
                        name: p.product_name,
                        budget: budget,
                        reality: reality,
                        get profit() { return this.reality - this.budget; }
                    };
                });
                this.calculateTotals();
            },

            calculateTotals() {
                this.totalBudget = this.items.reduce((sum, item) => sum + (Number(item.budget) || 0), 0);
                this.totalReality = this.items.reduce((sum, item) => sum + (Number(item.reality) || 0), 0);
                this.totalProfit = this.items.reduce((sum, item) => sum + (this.totalReality - this.totalBudget), 0); 
                // fix: total profit is difference of totals
                this.totalProfit = this.totalReality - this.totalBudget;
            },

            formatCurr(num) {
                return parseFloat(num).toLocaleString('id-ID');
            }
        }));

        // Tab 4: Product Summary
        Alpine.data('productSummary', (dbCustomers, initialProducts) => ({
            customers: dbCustomers.length > 0 ? dbCustomers : [
                { id: 1, counter_name: 'PT. BANK MEGA SYARIAH KC BANDUNG', code: '' },
                { id: 2, counter_name: 'PT. BANK MEGA TBK - KCP Kramat Jati', code: '' },
                { id: 3, counter_name: 'PT. BANK MEGA TBK - KCP Kramat Raya', code: '' },
                { id: 4, counter_name: 'PT. Daily Dinamika Kreasi', code: '' },
                { id: 5, counter_name: 'ALLO FRESH INDONESIA', code: '' },
                { id: 6, counter_name: 'ASTON HOTEL', code: '' },
                { id: 7, counter_name: 'ASTON HOTEL', code: '-' },
                { id: 8, counter_name: 'BANK MEGA CARD CENTER KUNINGAN', code: '' },
            ],
            allProducts: initialProducts.data,
            items: [],
            customerSearch: '',
            showCustomerDropdown: false,
            totalBeg: 0,
            totalIn: 0,
            totalOut: 0,
            totalCr: 0,
            totalBal: 0,

            init() {
                // Sync with store
                this.$watch('$store.assetSync.selectedCustomer', (val) => {
                    if (val) {
                        this.customerSearch = val.counter_name + (val.code ? ' - ' + val.code : '');
                        this.refreshData();
                    }
                });
                this.$watch('$store.assetSync.selectedMonth', () => this.refreshData());

                if (this.$store.assetSync.selectedCustomer) {
                    const val = this.$store.assetSync.selectedCustomer;
                    this.customerSearch = val.counter_name + (val.code ? ' - ' + val.code : '');
                }
                this.refreshData();
            },

            get filteredCustomers() {
                if (!this.customerSearch) return this.customers.slice(0, 10);
                const search = this.customerSearch.toLowerCase();
                return this.customers.filter(c => 
                    (c.counter_name && c.counter_name.toLowerCase().includes(search)) || 
                    (c.code && c.code.toLowerCase().includes(search))
                ).slice(0, 10);
            },

            selectCustomer(cust) {
                this.$store.assetSync.selectedCustomer = cust;
                this.showCustomerDropdown = false;
            },

            refreshData() {
                const cust = this.$store.assetSync.selectedCustomer;
                if (!cust) {
                    this.items = [];
                    this.calculateTotals();
                    return;
                }

                // Deterministic seed based on customer ID
                const seed = cust.id + (this.$store.assetSync.selectedMonth.split('').reduce((s, c) => s + c.charCodeAt(0), 0));
                const rng = (min, max, offset) => min + ((seed + offset) % (max - min + 1));

                // Generate varied summary data per company
                this.items = this.allProducts.slice(0, 15).map((p, i) => {
                    const beg = rng(0, 100, i * 3);
                    const sin = rng(0, 50, i * 11);
                    const sout = rng(0, beg + sin, i * 17);
                    const balance = beg + sin - sout;
                    return {
                        code: p.code,
                        name: p.product_name,
                        category: p.category || 'ACCESSORIES',
                        beg_bal: beg,
                        stock_in: sin,
                        stock_out: sout,
                        cr_stock: balance,
                        balanceValue: balance * (p.cost_price || 1000)
                    };
                });
                this.calculateTotals();
            },

            calculateTotals() {
                this.totalBeg = this.items.reduce((sum, item) => sum + item.beg_bal, 0);
                this.totalIn = this.items.reduce((sum, item) => sum + item.stock_in, 0);
                this.totalOut = this.items.reduce((sum, item) => sum + item.stock_out, 0);
                this.totalCr = this.items.reduce((sum, item) => sum + item.cr_stock, 0);
                this.totalBal = this.items.reduce((sum, item) => sum + item.balanceValue, 0);
            },

            formatCurr(num) {
                return parseFloat(num).toLocaleString('id-ID');
            }
        }));

        // ── Product Detail Manager (Record Detail Tab) ──
        Alpine.data('productDetailManager', () => ({
            currentIndex: 0,
            records: @json($products->items()),

            get cur() {
                if (!this.records || this.records.length === 0) return {};
                return this.records[this.currentIndex];
            },

            init() {
                // Listen for product selection from other tabs
                window.addEventListener('select-product-index', (e) => {
                    this.currentIndex = e.detail.index;
                });
            },

            dispatchSync() {
                window.dispatchEvent(new CustomEvent('select-product-index', { 
                    detail: { index: this.currentIndex } 
                }));
            },

            nextRecord() {
                if (this.currentIndex < this.records.length - 1) {
                    this.currentIndex++;
                    this.dispatchSync();
                }
            },
            prevRecord() {
                if (this.currentIndex > 0) {
                    this.currentIndex--;
                    this.dispatchSync();
                }
            },
            goFirst() {
                this.currentIndex = 0;
                this.dispatchSync();
            },
            goLast() {
                this.currentIndex = this.records.length - 1;
                this.dispatchSync();
            },

            handleRibbonAction(action) {
                // Only respond if this tab is active
                const tab = document.querySelector('.main-tab[data-target="tab-record"]');
                if(!tab || !tab.classList.contains('active')) return;

                switch(action) {
                    case 'new': this.createNew(); break;
                    case 'save': 
                        this.saveCurrentChanges(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.cur, 'ProductAsset_' + (this.cur?.code || 'Draft') + '.json');
                        }
                        showToast('Product saved to file', 'success'); 
                        break;
                    case 'delete': this.deleteRecord(); break;
                    case 'refresh': window.location.reload(); break;
                    case 'preview': window.print(); break;
                    case 'find': document.querySelector('#btn-tab-list')?.click(); this.$nextTick(() => { if(typeof erpFindOpen === 'function') erpFindOpen(); }); break;
                    case 'undo': this.undoChanges(); break;
                    case 'save-as': 
                        this.saveAsNew(); 
                        if(typeof exportToJSONFile === 'function') {
                            const lastRec = this.records[this.records.length - 1];
                            exportToJSONFile(lastRec, 'ProductAsset_' + (lastRec?.code || 'Copy') + '.json');
                        }
                        break;
                    case 'edit': this.focusFirstField(); break;
                    case 'barcode': showToast('Generating product barcode...', 'info'); break;
                    case 'resend': showToast('Re-sending product details...', 'info'); break;
                }
            },

            undoChanges() {
                if (confirm('Revert all unsaved changes for this product?')) {
                    this.dispatchSync(); // re-fetch / mock reload
                    showToast('Changes reverted', 'info');
                }
            },

            saveAsNew() {
                const clone = JSON.parse(JSON.stringify(this.cur));
                clone.code = (this.records.length + 1).toString().padStart(6, '0');
                clone.name += ' (COPY)';
                this.records.push(clone);
                this.currentIndex = this.records.length - 1;
                this.dispatchSync();
                showToast('Product duplicated', 'success');
            },

            focusFirstField() {
                this.$nextTick(() => {
                    const firstInput = document.querySelector('#tab-record.active input:not([readonly])');
                    if (firstInput) firstInput.focus();
                });
            },


            createNew() {
                const newCode = (this.records.length + 1).toString().padStart(6, '0');
                const newRec = {
                    code: newCode,
                    name: 'NEW PRODUCT',
                    sku: '',
                    barcode: '',
                    category: '',
                    unit: 'PCS',
                    min_stock: 0,
                    price: 0,
                    note: '',
                    status: 'ACTIVE'
                };
                this.records.push(newRec);
                this.currentIndex = this.records.length - 1;
                this.dispatchSync();
                showToast('New product record created', 'success');
            },

            saveCurrentChanges() {
                showToast('Product details saved locally', 'success');
            },

            deleteRecord() {
                if (confirm('Are you sure you want to delete this product?')) {
                    this.records.splice(this.currentIndex, 1);
                    if (this.currentIndex >= this.records.length) {
                        this.currentIndex = Math.max(0, this.records.length - 1);
                    }
                    this.dispatchSync();
                    showToast('Product record deleted', 'success');
                }
            }
        }));
    });


    // Native JS for the main tabs
    document.addEventListener('DOMContentLoaded', () => {
        const mainTabs = document.querySelectorAll('.main-tab');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        mainTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const targetId = tab.getAttribute('data-target');
                if(!targetId) return;
                
                mainTabs.forEach(t => t.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));
                
                tab.classList.add('active');
                document.getElementById(targetId).classList.add('active');
            });
        });
    });
</script>
@endpush
