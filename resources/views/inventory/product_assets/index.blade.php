@extends('layouts.app')
@section('title', 'Product Assets')

@push('styles')
    <style>
        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .page-desc {
            font-size: 0.85rem;
            color: #64748b;
        }

        /* ── Tabs ── */
        .main-tabs {
            display: flex;
            gap: 2px;
            margin-bottom: 0;
            border-bottom: 1px solid #cbd5e1;
        }

        .main-tab {
            padding: 8px 16px;
            font-size: 0.85rem;
            color: #475569;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-bottom: none;
            cursor: pointer;
            border-radius: 4px 4px 0 0;
        }

        .main-tab.active {
            background-color: #fff;
            color: #1e293b;
            font-weight: 600;
            border-bottom: 1px solid #fff;
            margin-bottom: -1px;
            z-index: 10;
        }

        .inner-tabs {
            display: flex;
            gap: 2px;
            margin-bottom: 15px;
            border-bottom: 1px solid #cbd5e1;
        }

        .inner-tab {
            padding: 6px 16px;
            font-size: 0.8rem;
            color: #475569;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-bottom: none;
            cursor: default;
            border-radius: 4px 4px 0 0;
        }

        .inner-tab.active {
            background-color: #fff;
            color: #1e293b;
            font-weight: 600;
            border-bottom: 1px solid #fff;
            margin-bottom: -1px;
            z-index: 10;
        }

        /* ── Main Wrapper ── */
        .main-content {
            background: #fff;
            padding: 24px;
            border: 1px solid #cbd5e1;
            border-top: none;
            min-height: 500px;
        }

        /* ── Layout grids ── */
        .flex-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .box-panel {
            border: 1px solid #cbd5e1;
            padding: 15px;
            border-radius: 4px;
            background: #fff;
        }
        
        .box-panel.no-pad {
            padding: 0;
        }

        .box-panel.gray-header {
            border-top: none;
        }

        .gray-header-title {
            background: #e2e8f0;
            padding: 8px 15px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #334155;
            border: 1px solid #cbd5e1;
            border-bottom: none;
            border-radius: 4px 4px 0 0;
        }

        .col-left { flex: 6; }
        .col-right { flex: 4; }

        /* ── Forms ── */
        .form-group-sm {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .form-group-sm>label {
            flex: 0 0 130px;
            font-size: 0.8rem;
            color: #475569;
            text-align: right;
            margin-right: 12px;
        }

        .form-control-sm {
            flex: 1;
            padding: 4px 8px;
            font-size: 0.8rem;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            color: #1e293b;
            background-color: #fff;
        }

        select.form-control-sm {
            appearance: auto;
        }

        .form-control-sm:read-only {
            background-color: #f1f5f9;
        }

        input[type="checkbox"] {
            margin-left: 8px;
            width: 14px;
            height: 14px;
            cursor: pointer;
        }

        .inline-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        /* ── Top Right Block ── */
        .top-right-header {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 0.8rem;
            color: #475569;
        }

        .top-right-header label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .image-placeholder {
            border: 1px solid #cbd5e1;
            height: 190px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            color: #64748b;
            font-size: 0.8rem;
            text-align: center;
            cursor: pointer;
        }

        .image-placeholder-text {
            margin-bottom: 20px;
        }

        .image-placeholder-action {
            font-size: 0.75rem;
            position: absolute;
            bottom: 10px;
        }

        /* ── Middle Blocks ── */
        .accounting-fields {
            padding: 15px;
            border: 1px solid #cbd5e1;
            border-top: none;
            border-radius: 0 0 4px 4px;
        }

        .conditions-label {
            font-size: 0.85rem;
            color: #334155;
            background: #fff;
            padding: 0 5px;
            margin-left: 10px;
            position: relative;
            top: 10px;
            display: inline-block;
        }

        .conditions-box {
            border: 1px solid #cbd5e1;
            padding: 20px 15px 15px 15px;
            border-radius: 4px;
        }

        .cond-group {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            justify-content:flex-end;
        }

        .cond-group>label {
            font-size: 0.8rem;
            color: #475569;
            margin-right: 12px;
        }

        .cond-group .form-control-sm {
            flex: 0 0 120px;
            text-align: right;
        }

        /* ── Bottom Bar ── */
        .bottom-bar {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            align-items: flex-end;
        }

        .note-section {
            flex: 1;
        }

        .note-section label {
            display: block;
            font-size: 0.8rem;
            color: #475569;
            margin-bottom: 5px;
        }

        .note-textarea {
            width: 100%;
            height: 40px;
            resize: none;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
        }

        .stats-section {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            margin-bottom: 2px;
        }

        .stat-box {
            text-align: center;
        }

        .stat-box label {
            display: block;
            font-size: 0.75rem;
            color: #475569;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .stat-box input {
            width: 90px;
            text-align: center;
            background: #f8fafc;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            color: #475569;
            background: #fff;
            padding: 4px;
        }

        .page-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #64748b;
            padding: 2px 4px;
        }
        .page-btn:hover {
            color: #1e293b;
        }
        
        /* Loading Spinner */
        .spinner {
            border: 2px solid #e2e8f0;
            border-top: 2px solid #2563EB;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
            display: inline-block;
            vertical-align: middle;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ── Product List Tab Styles ── */
        .list-split-layout {
            display: flex;
            gap: 15px;
            height: calc(100vh - 250px);
            min-height: 500px;
        }
        
        .list-col-left {
            flex: 7;
            display: flex;
            flex-direction: column;
            border: 1px solid #cbd5e1;
            background: #fff;
            position: relative;
        }
        
        .list-col-right {
            flex: 3;
            display: flex; /* We rely on AlpineJS x-show to hide it initially */
            flex-direction: column;
            border: 1px solid #cbd5e1;
            background: #fff;
            position: relative;
        }
        
        .grid-toolbar {
            padding: 8px 12px;
            background: #f1f5f9;
            border-bottom: 1px solid #cbd5e1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: #64748b;
        }
        
        .grid-search {
            background: transparent;
            border: none;
            outline: none;
            width: 20px;
            cursor: pointer;
        }
        
        .data-table-container {
            flex: 1;
            overflow: auto;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
        }
        
        .data-table th {
            background: #f8fafc;
            position: sticky;
            top: 0;
            padding: 6px 10px;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-weight: 500;
            text-align: left;
            white-space: nowrap;
            z-index: 5;
        }
        
        .data-table td {
            padding: 4px 10px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            white-space: nowrap;
        }
        
        .data-table tr:hover:not(.selected) {
            background: #f1f5f9;
            cursor: pointer;
        }
        
        .data-table tr.selected {
            background: #c7d2fe;
        }
        
        .data-table td.num {
            text-align: right;
        }
        
        .panel-tabs {
            display: flex;
            background: #f1f5f9;
            border-bottom: 1px solid #cbd5e1;
        }
        
        .panel-tab {
            padding: 6px 12px;
            font-size: 0.8rem;
            color: #475569;
            cursor: pointer;
            border-right: 1px solid #cbd5e1;
        }
        
        .panel-tab.active {
            background: #fff;
            color: #1e293b;
            font-weight: 600;
            border-bottom: 2px solid #2563EB;
        }
        
        .panel-content {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f8fafc;
        }
        
        /* We remove the hardcoded display:none / block logic since Alpine x-show handles it dynamically */
        
        .stat-grid {
            display: grid;
            grid-template-columns: 100px 1fr 1fr;
            gap: 5px 10px;
            align-items: center;
            font-size: 0.8rem;
        }
        
        .stat-label {
            text-align: right;
            color: #475569;
        }
        
        .stat-input {
            width: 100%;
            padding: 3px 6px;
            border: 1px solid #cbd5e1;
            text-align: right;
            background: #fff;
        }
        
        .stat-input[readonly] {
            background: #fff;
            border-color: #e2e8f0;
        }

        .tab-pane {
            display: none;
        }
        .tab-pane.active {
            display: block;
        }

        /* ── Budget Tab Styles ── */
        .budget-filter-bar {
            background: #f1f5f9;
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            border-bottom: none;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .budget-filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .budget-filter-group label {
            font-size: 0.8rem;
            color: #475569;
            white-space: nowrap;
        }

        .searchable-select-container {
            position: relative;
            flex: 1;
            min-width: 250px;
        }

        .searchable-select-input {
            width: 100%;
            padding: 4px 30px 4px 8px;
            font-size: 0.8rem;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
        }

        .searchable-select-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #cbd5e1;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .searchable-select-option {
            padding: 6px 10px;
            font-size: 0.8rem;
            cursor: pointer;
        }

        .searchable-select-option:hover {
            background: #f1f5f9;
        }

        .searchable-select-header {
            padding: 4px 10px;
            font-weight: 600;
            font-size: 0.75rem;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            text-transform: uppercase;
        }

        .month-select {
            padding: 4px 8px;
            font-size: 0.8rem;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            width: 150px;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'%3E%3C/rect%3E%3Cline x1='16' y1='2' x2='16' y2='6'%3E%3C/line%3E%3Cline x1='8' y1='2' x2='8' y2='6'%3E%3C/line%3E%3Cline x1='3' y1='10' x2='21' y2='10'%3E%3C/line%3E%3C/svg%3E") no-repeat right 8px center;
            appearance: none;
            padding-right: 32px;
        }

        .refresh-btn {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            padding: 4px 8px;
            cursor: pointer;
            color: #64748b;
            display: flex;
            align-items: center;
        }

        .refresh-btn:hover {
            background: #f1f5f9;
            color: #2563eb;
        }

        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #64748b;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px;
        }

        .icon-btn:hover {
            color: #2563eb;
        }

        .budget-table-container {
            border: 1px solid #cbd5e1;
            height: calc(100vh - 350px);
            min-height: 300px;
            overflow: auto;
            background: #fff;
        }

        .budget-summary-bar {
            display: flex;
            justify-content: flex-end;
            padding: 10px 15px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-top: none;
            gap: 10px;
        }

        .summary-input-group {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .summary-input-group input {
            width: 120px;
            text-align: right;
            padding: 4px 8px;
            font-size: 0.8rem;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            background: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">Product Assets</h1>
        <p class="page-desc">Manage product inventory and accounting details.</p>
    </div>
    <div class="main-tabs">
        <button class="main-tab active" data-target="tab-record">RECORD DETAIL</button>
        <button class="main-tab" id="btn-tab-list" data-target="tab-list">PRODUCT LIST & TOOLS</button>
        <button class="main-tab" data-target="tab-budget">PRODUCT BUDGET</button>
        <button class="main-tab" data-target="tab-summary">PRODUCT SUMMARY</button>
    </div>

    <div class="main-content">
        <!-- TAB 1: RECORD DETAIL -->
        <div id="tab-record" class="tab-pane active"
             x-data="productDetailManager()"
             x-init="init()">
        <div class="inner-tabs">
            <button class="inner-tab active">PRODUCT ID</button>
        </div>

        <div class="flex-row">
            <!-- Top Left -->
            <div class="col-left box-panel">
                <div class="form-group-sm">
                    <label>Category</label>
                    <select class="form-control-sm" x-model="cur.category">
                        <option>ACCESSORIES</option>
                        <option>CHEMICAL</option>
                        <option>ELECTRICAL</option>
                        <option>FURNITURE</option>
                        <option>MECHANICAL</option>
                        <option>SAFETY</option>
                    </select>
                    <input type="checkbox" :checked="cur.cat_chk" @change="cur.cat_chk = $event.target.checked">
                </div>
                <div class="form-group-sm">
                    <label>Supplier / Brand</label>
                    <select class="form-control-sm" x-model="cur.supplier">
                        <option>LAIN-LAIN</option>
                        <option>PT. MEGA SUPPLIER</option>
                        <option>CV. BERKAH JAYA</option>
                        <option>PT. SUMBER MAKMUR</option>
                    </select>
                    <input type="checkbox" :checked="cur.sup_chk" @change="cur.sup_chk = $event.target.checked">
                </div>
                <div class="form-group-sm">
                    <label>Model / Type</label>
                    <select class="form-control-sm" x-model="cur.model">
                        <option>DRY CHEMICAL</option>
                        <option>CO2</option>
                        <option>FOAM</option>
                        <option>WATER</option>
                    </select>
                    <input type="checkbox" :checked="cur.model_chk" @change="cur.model_chk = $event.target.checked">
                </div>
                <div class="form-group-sm">
                    <label>Quality, Style / Color</label>
                    <select class="form-control-sm" x-model="cur.quality">
                        <option>POWDER 2.5KG</option>
                        <option>POWDER 6KG</option>
                        <option>LIQUID 9L</option>
                        <option>GREEN</option>
                        <option>BLACK</option>
                    </select>
                    <input type="checkbox" :checked="cur.qual_chk" @change="cur.qual_chk = $event.target.checked">
                </div>

                <div class="form-group-sm">
                    <label>Internal Code</label>
                    <div class="inline-group">
                        <input type="text" class="form-control-sm" x-model="cur.internal_code">
                        <span style="font-size:0.8rem; color:#475569; white-space:nowrap; margin-left:10px; margin-right:5px;">Ext. Code</span>
                        <input type="text" class="form-control-sm" x-model="cur.ext_code">
                    </div>
                </div>

                <div class="form-group-sm">
                    <label>Product Name</label>
                    <input type="text" class="form-control-sm" x-model="cur.product_name" style="background: #f1f5f9;">
                </div>

                <div class="form-group-sm">
                    <label>Physical Units</label>
                    <div class="inline-group">
                        <input type="text" class="form-control-sm" x-model="cur.unit" style="background: #f1f5f9;">
                        <span style="font-size:0.8rem; color:#475569; white-space:nowrap; margin-left:10px; margin-right:5px;">Volume in Units</span>
                        <input type="number" class="form-control-sm" x-model="cur.volume" style="text-align:right;">
                    </div>
                </div>
            </div>

            <!-- Top Right -->
            <div class="col-right">
                <div class="top-right-header">
                    <label>Not for Count <input type="checkbox" :checked="cur.not_for_count" @change="cur.not_for_count = $event.target.checked"></label>
                    <label>Services <input type="checkbox" :checked="cur.services" @change="cur.services = $event.target.checked"></label>
                    <label>Discontinue <input type="checkbox" :checked="cur.discontinue" @change="cur.discontinue = $event.target.checked"></label>
                </div>
                <div class="image-placeholder" style="position:relative;">
                    <div class="image-placeholder-text">
                        Fill with your image<br>
                        (W:292px H:190px) Max
                    </div>
                    <div class="image-placeholder-action">
                        Click on image above or here to add product image
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-row">
            <!-- Middle Left: Accounting -->
            <div class="col-left box-panel no-pad gray-header">
                <div class="gray-header-title">Accounting</div>
                <div class="accounting-fields">
                    <div class="form-group-sm">
                        <label>Inventory Account</label>
                        <select class="form-control-sm" x-model="cur.inv_account">
                            <option>BIAYA KEBERSIHAN</option>
                            <option>PERSEDIAAN BARANG</option>
                            <option>BIAYA OPERASIONAL</option>
                            <option>ASET TETAP</option>
                        </select>
                    </div>
                    <div class="form-group-sm">
                        <label>COGS Account</label>
                        <select class="form-control-sm" x-model="cur.cogs_account">
                            <option>HUTANG VENDOR</option>
                            <option>HARGA POKOK PENJUALAN</option>
                            <option>BEBAN POKOK</option>
                        </select>
                    </div>
                    <div class="form-group-sm">
                        <label>Cost Center</label>
                        <select class="form-control-sm" x-model="cur.cost_center">
                            <option value=""></option>
                            <option>GA</option>
                            <option>IT</option>
                            <option>FINANCE</option>
                            <option>OPERATIONS</option>
                        </select>
                    </div>
                    <div class="form-group-sm">
                        <label>Account Dept.</label>
                        <select class="form-control-sm" x-model="cur.account_dept">
                            <option value=""></option>
                            <option>DEPT-001</option>
                            <option>DEPT-002</option>
                            <option>DEPT-003</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Middle Right: Conditions -->
            <div class="col-right">
                <div class="conditions-label">Conditions</div>
                <div class="conditions-box">
                    <div class="cond-group">
                        <label>Minimum Stock</label>
                        <input type="number" class="form-control-sm" x-model="cur.min_stock">
                    </div>
                    <div class="cond-group">
                        <label>Cost Price</label>
                        <input type="text" class="form-control-sm" x-model="cur.cost_price">
                    </div>
                    <div class="cond-group">
                        <label>On Order</label>
                        <input type="number" class="form-control-sm" x-model="cur.on_order">
                    </div>
                    <div class="cond-group">
                        <label>Sales Price</label>
                        <input type="text" class="form-control-sm" x-model="cur.sales_price">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="bottom-bar">
            <div class="note-section">
                <label>NOTE</label>
                <textarea class="note-textarea" x-model="cur.note"></textarea>
            </div>

            <div class="stats-section">
                <div class="stat-box">
                    <label>ON TRANSIT</label>
                    <input type="number" class="form-control-sm" x-model="cur.on_transit">
                </div>
                <div class="stat-box">
                    <label>STOCK</label>
                    <input type="text" class="form-control-sm" x-model="cur.stock">
                </div>
                <div class="stat-box">
                    <label>BALANCE</label>
                    <input type="number" class="form-control-sm" x-model="cur.balance">
                </div>

                <div class="pagination-controls pb-1">
                    <button class="page-btn" @click="goFirst()" :disabled="currentIndex === 0" title="First Record">⏮</button>
                    <button class="page-btn" @click="prevRecord()" :disabled="currentIndex === 0" title="Previous Record">◀</button>
                    <span x-text="'Record ' + (currentIndex + 1) + ' of ' + records.length"></span>
                    <button class="page-btn" @click="nextRecord()" :disabled="currentIndex === records.length - 1" title="Next Record">▶</button>
                    <button class="page-btn" @click="goLast()" :disabled="currentIndex === records.length - 1" title="Last Record">⏭</button>
                </div>
            </div>
        </div>
        </div>
        <!-- END TAB 1 -->

        <div id="tab-list" class="tab-pane" 
             x-data="productAssets()" 
             x-init="init()">
            <div class="list-split-layout">
                <!-- Left Grid -->
                <div class="list-col-left">
                    <div class="grid-toolbar">
                        <span>Drag a column header here to group by that column</span>
                        <div x-show="loading" class="spinner" style="margin-left: 10px;"></div>
                        <button class="grid-search">⚲</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table" id="productGrid">
                            <thead>
                                <tr>
                                    <th style="width: 30px;"></th>
                                    <th>CODE</th>
                                    <th>PRODUCT NAME</th>
                                    <th>COST PRICE</th>
                                    <th>PRICE (GR)</th>
                                    <th>PRICE (EC)</th>
                                    <th>MN. ST</th>
                                    <th>STOCK</th>
                                    <th>ON ORDER</th>
                                    <th>DC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr class="prod-row" 
                                    :class="{'selected': selectedProduct && selectedProduct.code === '{{ $product->code }}'}"
                                    @click="selectProduct(@json($product), {{ $loop->index }})">
                                    <td></td>
                                    <td>{{ $product->code }}</td>
                                    <td>{{ Str::limit($product->product_name, 25) }}</td>
                                    <td class="num">{{ number_format($product->cost_price, 3, ',', '.') }}</td>
                                    <td>{{ $product->price_gr ? number_format($product->price_gr, 2, ',', '.') : '' }}</td>
                                    <td>{{ $product->price_ec ? number_format($product->price_ec, 2, ',', '.') : '' }}</td>
                                    <td class="num">{{ $product->min_stock }}</td>
                                    <td class="num">{{ $product->stock }}</td>
                                    <td class="num">{{ $product->on_order }}</td>
                                    <td style="text-align:center;"><input type="checkbox" {{ $product->discontinue ? 'checked' : '' }} disabled></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" style="text-align:center; padding: 20px;">No Product Assets Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-controls" style="border-top: 1px solid #cbd5e1; padding: 6px 12px; background: #fff; position: absolute; bottom: 0; left: 0; right: 0;">
                        <a href="{{ $products->url(1) }}" class="page-btn" title="First Page" style="text-decoration:none;">⏮</a>
                        <a href="{{ $products->previousPageUrl() }}" class="page-btn" title="Previous Page" style="text-decoration:none;">◀</a>
                        
                        <span style="user-select:none; margin: 0 10px;">Record {{ $products->firstItem() ?? 0 }} of {{ $products->total() }}</span>
                        
                        <a href="{{ $products->nextPageUrl() }}" class="page-btn" title="Next Page" style="text-decoration:none;">▶</a>
                        <a href="{{ $products->url($products->lastPage()) }}" class="page-btn" title="Last Page" style="text-decoration:none;">⏭</a>
                    </div>
                </div>
                
                <!-- Right Panel -->
                <div class="list-col-right" x-show="selectedProduct" x-transition.opacity.duration.300ms style="display: none; flex-direction:column;">

                    <!-- Loading Overlay -->
                    <div x-show="loading" x-transition.opacity
                         style="position: absolute; inset: 0; background: rgba(255,255,255,0.7); z-index: 10; display: flex; align-items: center; justify-content: center;">
                        <div class="spinner"></div>
                    </div>

                    <!-- Top panel tabs -->
                    <div class="panel-tabs" style="flex-shrink:0;">
                        <div class="panel-tab" :class="{'active': activePanel === 'pnl-statistics'}" @click="setActivePanel('pnl-statistics')">Statistics</div>
                        <div class="panel-tab" :class="{'active': activePanel === 'pnl-summary'}" @click="setActivePanel('pnl-summary')">Summary</div>
                        <div class="panel-tab" :class="{'active': activePanel === 'pnl-activity'}" @click="setActivePanel('pnl-activity')">Activity</div>
                        <div class="panel-tab" :class="{'active': activePanel === 'pnl-warehouse'}" @click="setActivePanel('pnl-warehouse')">Warehouse</div>
                    </div>

                    <!-- ── STATISTICS ── -->
                    <div class="panel-content" x-show="activePanel === 'pnl-statistics'" style="padding:0; overflow-y:auto; flex:1;">
                        <table style="width:100%; border-collapse:collapse; font-size:0.72rem;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; width:50%;"></th>
                                    <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">STOCK</th>
                                    <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">BALANCE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding:3px 8px; border:1px solid #f1f5f9; color:#475569;">Begining Balance</td>
                                    <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;"><input type="text" style="width:50px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px;" :value="pd ? pd.beg_stock : 0" readonly></td>
                                    <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;"><input type="text" style="width:80px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px;" :value="pd ? formatCurr(pd.beg_balance) : '0,00'" readonly></td>
                                </tr>
                                <tr style="background:#f8fafc;">
                                    <td style="padding:3px 8px; border:1px solid #f1f5f9; color:#475569;">Current Balance</td>
                                    <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;"><input type="text" style="width:50px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px;" :value="pd ? pd.cur_stock : 0" readonly></td>
                                    <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;"><input type="text" style="width:80px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px;" :value="pd ? formatCurr(pd.cur_balance) : '0,00'" readonly></td>
                                </tr>
                                <template x-for="m in months" :key="m.key">
                                    <tr>
                                        <td style="padding:3px 8px; border:1px solid #f1f5f9; color:#475569;" x-text="m.label"></td>
                                        <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;"><input type="text" style="width:50px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px;" :value="pd ? (pd.monthly_stock[m.key] || 0) : 0" readonly></td>
                                        <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;"><input type="text" style="width:80px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px;" :value="pd ? formatCurr(pd.monthly_balance[m.key] || 0) : '0,00'" readonly></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- ── SUMMARY (Warehouse list) ── -->
                    <div class="panel-content" x-show="activePanel === 'pnl-summary'" style="padding:0; overflow-y:auto; flex:1; display:none;">
                        <table style="width:100%; border-collapse:collapse; font-size:0.72rem;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal;">WAREHOUSE</th>
                                    <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">STOCK</th>
                                    <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">ON TRANSIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(wh, idx) in (pd ? pd.warehouses : [])" :key="idx">
                                    <tr :class="idx === 0 ? 'selected' : ''" style="cursor:pointer;">
                                        <td style="padding:3px 8px; border:1px solid #f1f5f9;">
                                            <span x-show="idx === 0" style="color:#475569;">▶ </span>
                                            <span x-text="wh.name"></span>
                                        </td>
                                        <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;" x-text="wh.stock"></td>
                                        <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;" x-text="wh.on_transit"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <!-- footer totals -->
                        <div style="display:flex; justify-content:flex-end; padding:4px 8px; border-top:1px solid #e2e8f0; background:#f8fafc; gap:8px;">
                            <input type="text" style="width:50px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px; background:#fff;" :value="pd ? pd.warehouses.reduce((s,w)=>s+w.stock,0) : 0" readonly>
                            <input type="text" style="width:50px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px; background:#fff;" :value="pd ? pd.warehouses.reduce((s,w)=>s+w.on_transit,0) : 0" readonly>
                        </div>
                        <!-- pager -->
                        <div style="display:flex; align-items:center; gap:2px; padding:3px 8px; border-top:1px solid #e2e8f0; background:#f8fafc; font-size:0.7rem; color:#64748b;">
                            <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">|◀</button>
                            <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">◀</button>
                            <span style="padding: 0 6px;" x-text="'Record 1 of ' + (pd ? pd.warehouses.length : 0)"></span>
                            <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">▶</button>
                            <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">▶|</button>
                        </div>
                    </div>

                    <!-- ── ACTIVITY ── -->
                    <div class="panel-content" x-show="activePanel === 'pnl-activity'" style="padding:0; overflow-y:auto; flex:1; display:none;">
                        <!-- date range filter -->
                        <div style="display:flex; align-items:center; gap:6px; padding:6px 8px; border-bottom:1px solid #e2e8f0; background:#f8fafc; font-size:0.72rem; flex-wrap:wrap;">
                            <span style="color:#475569;">Period</span>
                            <input type="date" style="border:1px solid #cbd5e1; font-size:0.7rem; padding:2px 4px;" value="2026-03-16">
                            <span style="color:#475569;">Thru</span>
                            <input type="date" style="border:1px solid #cbd5e1; font-size:0.7rem; padding:2px 4px;" value="2026-03-16">
                            <button style="background:#e2e8f0; border:1px solid #cbd5e1; padding:2px 6px; font-size:0.7rem; cursor:pointer;">↻</button>
                        </div>
                        <table style="width:100%; border-collapse:collapse; font-size:0.72rem;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal;">DATE</th>
                                    <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal;">USERNO</th>
                                    <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">IN</th>
                                    <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">OUT</th>
                                    <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">STO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-if="!pd || !pd.activities || pd.activities.length === 0">
                                    <tr><td colspan="5" style="text-align:center; padding:20px; color:#94a3b8;">No activity in selected period</td></tr>
                                </template>
                                <template x-if="pd && pd.activities && pd.activities.length > 0">
                                    <template x-for="act in pd.activities" :key="act.id">
                                        <tr>
                                            <td style="padding:3px 8px; border:1px solid #f1f5f9;" x-text="act.date"></td>
                                            <td style="padding:3px 8px; border:1px solid #f1f5f9;" x-text="act.userno"></td>
                                            <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;" x-text="act.in"></td>
                                            <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;" x-text="act.out"></td>
                                            <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;" x-text="act.sto"></td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                        <!-- footer -->
                        <div style="display:flex; justify-content:flex-end; gap:8px; padding:4px 8px; border-top:1px solid #e2e8f0; background:#f8fafc;">
                            <input type="text" style="width:40px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px;" value="0" readonly>
                            <input type="text" style="width:40px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px;" value="0" readonly>
                        </div>
                        <div style="display:flex; align-items:center; gap:2px; padding:3px 8px; border-top:1px solid #e2e8f0; background:#f8fafc; font-size:0.7rem; color:#64748b;">
                            <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">|◀</button>
                            <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">◀</button>
                            <span style="padding:0 6px;" x-text="'Record 0 of ' + (pd && pd.activities ? pd.activities.length : 0)"></span>
                            <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">▶</button>
                            <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">▶|</button>
                        </div>
                    </div>

                    <!-- ── WAREHOUSE ── -->
                    <div class="panel-content" x-show="activePanel === 'pnl-warehouse'" style="padding:0; overflow-y:auto; flex:1; display:none; flex-direction:column;">
                        <!-- inner sub-tabs -->
                        <div style="display:flex; gap:2px; background:#e2e8f0; padding:4px 8px 0; border-bottom:1px solid #cbd5e1; flex-shrink:0;">
                            <button class="fa-tab active" style="font-size:0.65rem; padding:4px 8px;" @click="whSubTab = 'stock'" :class="whSubTab==='stock'?'active':''">Stock Detail</button>
                            <button class="fa-tab" style="font-size:0.65rem; padding:4px 8px;" @click="whSubTab = 'stats'" :class="whSubTab==='stats'?'active':''">Statistics</button>
                            <button class="fa-tab" style="font-size:0.65rem; padding:4px 8px;" @click="whSubTab = 'activity'" :class="whSubTab==='activity'?'active':''">Activity</button>
                        </div>
                        <!-- Stock Detail sub-tab -->
                        <template x-if="whSubTab === 'stock'">
                            <div style="overflow-y:auto; flex:1;">
                                <table style="width:100%; border-collapse:collapse; font-size:0.72rem;">
                                    <thead>
                                        <tr style="background:#f8fafc;">
                                            <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal;">WAREHOUSE</th>
                                            <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">STOCK</th>
                                            <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">ON TRANSIT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(wh, i) in (pd ? pd.warehouses : [])" :key="i">
                                            <tr>
                                                <td style="padding:3px 8px; border:1px solid #f1f5f9;" x-text="wh.name"></td>
                                                <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;" x-text="wh.stock"></td>
                                                <td style="padding:3px 8px; border:1px solid #f1f5f9; text-align:right;" x-text="wh.on_transit"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                        <!-- Statistics sub-tab -->
                        <template x-if="whSubTab === 'stats'">
                            <div style="overflow-y:auto; flex:1; padding:10px; font-size:0.72rem; color:#64748b;">
                                <p>Warehouse statistics for selected product.</p>
                            </div>
                        </template>
                        <!-- Activity sub-tab within Warehouse -->
                        <template x-if="whSubTab === 'activity'">
                            <div style="overflow-y:auto; flex:1; display:flex; flex-direction:column;">
                                <div style="display:flex; gap:6px; padding:5px 8px; border-bottom:1px solid #e2e8f0; background:#f8fafc; font-size:0.72rem; align-items:center; flex-wrap:wrap;">
                                    <span style="color:#475569;">Period</span>
                                    <input type="date" style="border:1px solid #cbd5e1; font-size:0.7rem; padding:2px 4px;" value="2026-03-13">
                                    <span style="color:#475569;">Thru</span>
                                    <input type="date" style="border:1px solid #cbd5e1; font-size:0.7rem; padding:2px 4px;" value="2026-03-17">
                                    <button style="background:#e2e8f0; border:1px solid #cbd5e1; padding:2px 6px; font-size:0.7rem; cursor:pointer;">↻</button>
                                </div>
                                <table style="width:100%; border-collapse:collapse; font-size:0.72rem; flex:1;">
                                    <thead>
                                        <tr style="background:#f8fafc;">
                                            <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal;">DATE</th>
                                            <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal;">USERNO</th>
                                            <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">IN</th>
                                            <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">OUT</th>
                                            <th style="padding:4px 8px; border:1px solid #e2e8f0; color:#64748b; font-weight:normal; text-align:right;">STOCK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="5" style="text-align:center; padding:20px; color:#94a3b8;">No warehouse activity</td></tr>
                                    </tbody>
                                </table>
                                <div style="display:flex; justify-content:flex-end; gap:8px; padding:4px 8px; border-top:1px solid #e2e8f0; background:#f8fafc;">
                                    <input type="text" style="width:40px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px;" value="0" readonly>
                                    <input type="text" style="width:40px; text-align:right; border:1px solid #e2e8f0; font-size:0.7rem; padding:1px 3px;" value="0" readonly>
                                </div>
                                <div style="display:flex; align-items:center; gap:2px; padding:3px 8px; border-top:1px solid #e2e8f0; background:#f8fafc; font-size:0.7rem; color:#64748b;">
                                    <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">|◀</button>
                                    <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">◀</button>
                                    <span style="padding:0 6px;">Record 0 of 0</span>
                                    <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">▶</button>
                                    <button class="pager-btn" style="padding:1px 5px; font-size:0.6rem;">▶|</button>
                                </div>
                            </div>
                        </template>
                    </div>

                </div><!-- /list-col-right -->
            </div><!-- /list-split-layout -->
        </div><!-- END TAB 2 -->



        <div id="tab-budget" class="tab-pane" x-data='productBudget({!! json_encode($customers) !!}, {!! json_encode($products) !!})' x-init="init()">
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
            <!-- Table Container -->
            <div class="budget-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 150px;">CODE</th>
                            <th>PRODUCT NAME</th>
                            <th style="width: 150px;">BUDGET</th>
                            <th style="width: 150px;">REALITY</th>
                            <th style="width: 150px;">PROFIT/LOSS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="item in items" :key="item.code">
                            <tr>
                                <td x-text="item.code"></td>
                                <td x-text="item.name"></td>
                                <td class="num">
                                    <input type="number" class="stat-input" x-model="item.budget" @input="calculateTotals()">
                                </td>
                                <td class="num">
                                    <input type="number" class="stat-input" x-model="item.reality" @input="calculateTotals()">
                                </td>
                                <td class="num" :style="item.profit < 0 ? 'color: #ef4444' : 'color: #10b981'" x-text="formatCurr(item.profit)"></td>
                            </tr>
                        </template>
                        <tr x-show="items.length === 0">
                            <td colspan="5" style="text-align: center; padding: 20px; color: #64748b;">Select a customer to view budget details</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Summary Bar -->
            <div class="budget-summary-bar">
                <div class="summary-input-group">
                    <input type="text" :value="formatCurr(totalBudget)" readonly>
                </div>
                <div class="summary-input-group">
                    <input type="text" :value="formatCurr(totalReality)" readonly>
                </div>
                <div class="summary-input-group">
                    <input type="text" :value="formatCurr(totalProfit)" readonly>
                </div>
            </div>
        </div>
        
        <div id="tab-summary" class="tab-pane" x-data='productSummary({!! json_encode($customers) !!}, {!! json_encode($products) !!})' x-init="init()">
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
            <div class="budget-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 100px;">CODE</th>
                            <th>PRODUCT NAME</th>
                            <th style="width: 120px;">CATEGORY</th>
                            <th style="width: 100px;">BEG. BALC</th>
                            <th style="width: 100px;">STOCK IN</th>
                            <th style="width: 100px;">STOCK OUT</th>
                            <th style="width: 100px;">CR. STOCK</th>
                            <th style="width: 120px;">BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="item in items" :key="item.code">
                            <tr>
                                <td x-text="item.code"></td>
                                <td x-text="item.name"></td>
                                <td x-text="item.category"></td>
                                <td class="num" x-text="item.beg_bal"></td>
                                <td class="num" style="color: #10b981" x-text="item.stock_in"></td>
                                <td class="num" style="color: #ef4444" x-text="item.stock_out"></td>
                                <td class="num" x-text="item.cr_stock"></td>
                                <td class="num" style="font-weight: 600;" x-text="formatCurr(item.balanceValue)"></td>
                            </tr>
                        </template>
                        <tr x-show="items.length === 0">
                            <td colspan="8" style="text-align: center; padding: 20px; color: #64748b;">Select a customer to view product summary</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Summary Bar -->
            <div class="budget-summary-bar">
                <div class="summary-input-group">
                    <input type="text" :value="totalBeg" readonly>
                </div>
                <div class="summary-input-group">
                    <input type="text" :value="totalIn" readonly>
                </div>
                <div class="summary-input-group">
                    <input type="text" :value="totalOut" readonly>
                </div>
                <div class="summary-input-group">
                    <input type="text" :value="totalCr" readonly>
                </div>
                <div class="summary-input-group">
                    <input type="text" :value="formatCurr(totalBal)" readonly>
                </div>
            </div>
        </div>
    </div>

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
                this.selectedProduct = product;
                this.loading = true;
                document.querySelector('.list-col-right').style.display = 'flex';

                // Sync with Record Detail Tab
                window.dispatchEvent(new CustomEvent('select-product-index', { 
                    detail: { index: index } 
                }));

                // Simulate async load with mock data
                setTimeout(() => {
                    this.pd = this._buildDetail(product);
                    this.loading = false;
                }, 200);
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
