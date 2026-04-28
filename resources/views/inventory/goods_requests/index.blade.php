@extends('layouts.app')

@section('title', 'Goods Requests')

@push('styles')
<style>
    :root { --hr-border: #999; --hr-primary: #1e293b; --hr-accent: #2563eb; }

    .fa-window { background: #f0f0f0; border: 1px solid #999; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; height: calc(100vh - 120px); box-shadow: 2px 2px 5px rgba(0,0,0,0.2); color: #000; }
    .window-title-bar { background: linear-gradient(to bottom, #4f78b1, #3a5a8f); color: white; padding: 4px 8px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; font-weight: bold; }
    .main-tabs { display: flex; background: #f0f0f0; padding: 4px 4px 0 4px; border-bottom: 1px solid #999; }
    .main-tab { padding: 4px 12px; font-size: 12px; border: 1px solid #999; border-bottom: none; background: #e0e0e0; cursor: pointer; margin-right: 2px; border-radius: 3px 3px 0 0; text-transform: uppercase; }
    .main-tab.active { background: #fff; font-weight: bold; margin-bottom: -1px; height: calc(100% + 1px); }
    .tab-content { display: flex; flex-direction: column; flex: 1; overflow: hidden; }
    .tab-pane { display: none; flex: 1; flex-direction: column; overflow: auto; background: #f0f0f0; }
    .tab-pane.active { display: flex; }
    .list-grid { width: 100%; border-collapse: collapse; font-size: 11px; background: white; }
    .list-grid th { background: #e0e0e0; color: #333; padding: 3px 5px; text-align: left; font-weight: bold; border: 1px solid #999; white-space: nowrap; font-size: 11px; position: sticky; top: 0; }
    .list-grid td { padding: 3px 5px; border: 1px solid #ccc; white-space: nowrap; color: #000; font-size: 11px; }
    .list-grid tr:hover td { background: #f0f0f0; cursor: pointer; }
    .list-grid tr.selected td { background: #3a5a8f; color: white; }
    .grid-footer { display: flex; align-items: center; padding: 3px; border-top: 1px solid #999; background: #f0f0f0; gap: 2px; flex-shrink: 0; }
    .pager-btn { background: white; border: 1px solid #999; padding: 2px 6px; font-size: 11px; cursor: pointer; color: #333; min-width: 25px; }
    .pager-btn:hover { background: #e0e0e0; }
    .detail-form-area { padding: 8px 12px; background: #f0f0f0; border-bottom: 1px solid #999; display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex !important; flex-direction: row !important; align-items: center !important; margin-bottom: 3px !important; font-size: 11px; }
    .form-label { width: 100px; text-align: right; margin-right: 8px; color: #333; font-size: 11px; flex-shrink: 0; }
    .form-input { border: 1px solid #999; padding: 2px 4px; border-radius: 0; font-size: 12px; background: white; height: 22px; }
    .form-select { border: 1px solid #999; padding: 2px 4px; border-radius: 0; font-size: 12px; background: white; height: 22px; }
    .status-active-row td { background: #d0d8e8 !important; font-weight: 500; }
</style>
@endpush

@section('content')
<div class="fa-window" x-data="goodsRequestManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 8px; align-items: center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3m18 0v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8m18 0-9 6-9-6"/></svg>
            <span>Goods Request</span>
        </div>
        <div style="display: flex; gap: 4px;">
            <div style="width:14px;height:14px;background:#ddd;border:1px solid #999;cursor:pointer;"></div>
            <div style="width:14px;height:14px;background:#ddd;border:1px solid #999;cursor:pointer;"></div>
            <div style="width:14px;height:14px;background:#e81123;border:1px solid #999;cursor:pointer;"></div>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Main Navigation Tabs -->
    <div class="main-tabs">
        <div class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">RECORD DETAIL</div>
        <div class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">RECORDS LIST</div>
        <div class="main-tab" :class="activeMainTab === 'histories' ? 'active' : ''" @click="activeMainTab = 'histories'">HISTORIES</div>
    </div>

    <div class="tab-content" style="flex: 1;">
        
        <!-- RECORD DETAIL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''">
            <template x-if="selectedRequest">
                <div style="display: contents;">
                    <div class="detail-form-area" style="padding: 15px 20px; border-bottom: 2px solid #e2e8f0; background: #f8fafc;">
                        <div style="flex: 2;">
                            <!-- Date & Priority Row -->
                            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 8px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 0.75rem; color: #475569; width: 40px;">Date</span>
                                    <div style="display: flex; align-items: center; background: white; border: 1px solid var(--hr-border); cursor: pointer;" @click="$refs.datePicker.showPicker()">
                                        <input type="date" x-ref="datePicker" x-model="selectedRequest.date" @change="onDateChange($event)"
                                               style="position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none;">
                                        <input type="text" style="width: 110px; border: none; font-size: 0.75rem; padding: 4px 8px; cursor: pointer;" 
                                               :value="formatDate(selectedRequest.date)" readonly>
                                        <span style="background: #f1f5f9; padding: 4px 6px; border-left: 1px solid var(--hr-border); color: #64748b;">▼</span>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 0.75rem; color: #475569;">Priority</span>
                                    <div style="display: flex; align-items: center; border: 1px solid var(--hr-border); background: white; position: relative;">
                                        <span style="color: #ef4444; padding: 0 6px; font-weight: bold; border-right: 1px solid var(--hr-border); pointer-events: none;">?</span>
                                        <select style="border: none; font-size: 0.75rem; padding: 3px 25px 3px 8px; cursor: pointer; background: transparent; appearance: none; -webkit-appearance: none; width: 100%;" x-model="selectedRequest.priority">
                                            <option>High</option>
                                            <option>Normal</option>
                                            <option>Low</option>
                                        </select>
                                        <span style="position: absolute; right: 8px; pointer-events: none; color: #64748b; font-size: 8px;">▼</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Employee Name Row -->
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 0.75rem; color: #475569; width: 100px;">Employee Name</span>
                                <div style="display: flex; align-items: center; background: white; border: 1px solid var(--hr-border); width: 350px;">
                                    <input type="text" style="flex: 1; border: none; font-size: 0.75rem; padding: 4px 8px; outline: none;" 
                                           x-model="selectedRequest.employees_name" list="employee-names" placeholder="Type or Select Employee" @change="saveCurrentChanges()">
                                    <datalist id="employee-names">
                                        <template x-for="emp in availableEmployees">
                                            <option :value="emp"></option>
                                        </template>
                                    </datalist>
                                    <span style="background: #f1f5f9; padding: 4px 6px; border-left: 1px solid var(--hr-border); color: #64748b; pointer-events: none;">▼</span>
                                </div>
                            </div>
                        </div>
                        <!-- Right Side Info -->
                        <div style="width: 250px; display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                            <div style="display: flex; gap: 40px; font-size: 0.8rem; color: #1e293b; font-weight: 500;">
                                <span x-text="selectedRequest.user_no"></span>
                                <span x-text="selectedRequest.top_id"></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; font-size: 0.75rem; color: #1e3a8a; font-weight: 600;">
                                ON Processed 
                                <input type="checkbox" :checked="selectedRequest.on_processed" disabled style="width: 14px; height: 14px;">
                            </div>
                        </div>
                    </div>

                    <!-- Inner Tab Style for ITEM REQUEST -->
                    <div style="margin: 5px 15px 0 15px;">
                         <div style="display:inline-block; border: 1px solid var(--hr-border); border-bottom: none; padding: 4px 10px; font-size: 0.7rem; color: #64748b; background: white;">
                             ITEM REQUEST
                         </div>
                    </div>
                    
                    <div style="flex: 1; overflow: auto; border-top: 1px solid var(--hr-border); padding: 5px;">
                        <table class="list-grid">
                            <thead>
                                <tr>
                                    <th style="width: 25px;"></th>
                                    <th style="width: 150px;">CODE</th>
                                    <th style="width: 250px;">PRODUCT NAME</th>
                                    <th style="width: 60px; text-align: center;">QTY</th>
                                    <th style="width: 400px;">DESCRIPTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, idx) in selectedRequest.items" :key="idx">
                                    <tr @click="selectedItemIdx = idx" :class="selectedItemIdx === idx ? 'status-active-row' : ''" style="cursor: pointer;">
                                        <td style="text-align: center; color: #475569;">
                                            <span style="font-size: 8px;" x-show="selectedItemIdx === idx">▶</span>
                                            <button @click="removeItem(idx)" x-show="selectedItemIdx === idx" style="background:#ef4444; color:white; border:none; border-radius:2px; font-size:8px; padding:2px 4px; cursor:pointer;" title="Remove Item">✕</button>
                                        </td>
                                        <td><input type="text" x-model="item.code" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                        <td><input type="text" x-model="item.product_name" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                        <td><input type="number" x-model="item.qty" class="form-input" style="width: 100%; box-sizing: border-box; text-align: center; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                        <td><input type="text" x-model="item.description" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                    </tr>
                                </template>
                                <!-- New Item Row -->
                                <tr style="background: #f8fafc; border-top: 2px solid #cbd5e1;">
                                    <td style="text-align: center; color: #10b981; font-weight: bold; cursor: pointer;" @click="addNewItem()" title="Save item">+</td>
                                    <td style="padding: 2px;">
                                        <input type="text" x-model="newItem.code" class="form-input" style="width: 100%; box-sizing: border-box;" placeholder="Code..." list="product-codes" @change="onProductSelect">
                                        <datalist id="product-codes">
                                            <template x-for="prod in availableProducts"><option :value="prod.code"></option></template>
                                        </datalist>
                                    </td>
                                    <td style="padding: 2px;">
                                        <input type="text" x-model="newItem.product_name" class="form-input" style="width: 100%; box-sizing: border-box;" placeholder="Name or select..." list="product-names" @change="onProductSelectName">
                                        <datalist id="product-names">
                                            <template x-for="prod in availableProducts"><option :value="prod.name"></option></template>
                                        </datalist>
                                    </td>
                                    <td style="padding: 2px;"><input type="number" x-model="newItem.qty" class="form-input" style="width: 100%; box-sizing: border-box; text-align: center;" @keydown.enter="addNewItem"></td>
                                    <td style="padding: 2px; display:flex; gap:2px; border:none;">
                                        <input type="text" x-model="newItem.description" class="form-input" style="flex:1;" placeholder="Desc..." @keydown.enter="addNewItem">
                                        <button @click="addNewItem()" style="background:#2563eb; color:white; border:none; padding: 2px 8px; cursor:pointer;" title="Add Item">Add</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Detail Pager Footer -->
                    <div class="grid-footer" x-show="requests.length">
                        <button class="pager-btn" @click="firstRecord()" :disabled="currentIdx <= 0" title="First">|◀</button>
                        <button class="pager-btn" @click="prevRecord()" :disabled="currentIdx <= 0" title="Previous">◀◀</button>
                        <button class="pager-btn" @click="prevRecord()" :disabled="currentIdx <= 0" title="Previous">◀</button>
                        <span class="pager-btn" style="border:none; background:transparent; font-weight: 500;">Record <span x-text="currentIdx + 1"></span> of <span x-text="requests.length"></span></span>
                        <button class="pager-btn" @click="nextRecord()" :disabled="currentIdx >= requests.length - 1" title="Next">▶</button>
                        <button class="pager-btn" @click="nextRecord()" :disabled="currentIdx >= requests.length - 1" title="Next">▶▶</button>
                        <button class="pager-btn" @click="lastRecord()" :disabled="currentIdx >= requests.length - 1" title="Last">▶|</button>
                    </div>
                    
                    <div style="padding: 15px; background: #f1f5f9; border-top: 1px solid var(--hr-border); display: flex; justify-content: space-between; align-items: flex-end;">
                        <div>
                            <div style="font-size: 0.65rem; font-weight: bold; color: #475569; margin-bottom: 5px;">NOTE</div>
                            <textarea x-model="selectedRequest.note" style="width: 400px; height: 60px; border: 1px solid var(--hr-border); font-size: 0.75rem; padding: 5px;"></textarea>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px;">
                            <span style="color: #ef4444; font-weight: bold; font-size: 0.75rem;">Approved</span> 
                            <input type="checkbox" :checked="selectedRequest.approved" disabled style="width: 14px; height: 14px;">
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="!selectedRequest">
                <div style="padding: 40px; text-align: center; color: #64748b;">
                    Please select a request from the list to view details.
                </div>
            </template>
        </div>

        <!-- LIST REQUEST TAB -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''">
            <div class="bar-top">Drag a column header here to group by that column</div>
            <div class="bar-search">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #64748b;">
                    <circle cx="11" cy="11" r="8"></circle> <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <div style="flex: 1; overflow: auto;" x-show="requests.length">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 20px;"></th>
                            <th style="width: 60px; text-align:center;">ACC</th>
                            <th style="width: 60px;">TYPE</th>
                            <th style="width: 80px;">REF</th>
                            <th style="width: 80px; text-align:right;">USERNO</th>
                            <th style="width: 90px;">DATE</th>
                            <th style="width: 60px; text-align: right;">TL.QTY</th>
                            <th style="width: 300px;">NOTE</th>
                            <th style="width: 80px; text-align:center;">PROCESS</th>
                            <th style="width: 100px;">USER ID</th>
                            <th style="width: 100px;">AUDIT</th>
                        </tr>
                    </thead>
                    <template x-for="(item, idx) in requests" :key="item.id">
                        <tbody :class="selectedRequest && selectedRequest.id === item.id ? 'selected-group' : ''">
                            <tr @click="selectRequest(item.id, idx)" :class="selectedRequest && selectedRequest.id === item.id ? 'selected' : ''" class="main-row">
                                <td style="text-align: center; font-size: 0.5rem; color: #475569;">
                                    <span x-show="selectedRequest && selectedRequest.id === item.id">▶</span>
                                </td>
                                <td style="text-align:center;">
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                                        <button @click.stop="toggleExpand(item.id)" 
                                                style="width: 13px; height: 13px; border: 1px solid #94a3b8; background: white; display: flex; align-items: center; justify-content: center; font-size: 11px; cursor: pointer; padding: 0; line-height: 1; color: #334155; flex-shrink: 0;">
                                            <span style="margin-top: -1px;" x-text="expandedIds.includes(item.id) ? '-' : '+'"></span>
                                        </button>
                                        <input type="checkbox" :checked="item.acc" disabled style="width:12px; height:12px; margin:0;">
                                    </div>
                                </td>
                                <td x-text="item.type"></td>
                                <td x-text="item.ref"></td>
                                <td x-text="item.user_no" style="text-align: right;"></td>
                                <td x-text="item.date"></td>
                                <td x-text="item.tl_qty" style="text-align: right;"></td>
                                <td>
                                    <span x-text="item.note" style="display: inline-block;"></span>
                                    <span style="color:#cbd5e1; float:right;">...</span>
                                </td>
                                <td style="text-align:center;">
                                    <input type="checkbox" :checked="item.process" disabled style="width:12px; height:12px; margin:0;">
                                </td>
                                <td x-text="item.user_id"></td>
                                <td x-text="item.audit"></td>
                            </tr>
                            <!-- EXPANDABLE ROW -->
                            <tr x-show="expandedIds.includes(item.id)" style="background: #f8fafc;">
                                <td colspan="11" style="padding: 10px 20px;">
                                    <div style="background: white; border: 1px solid var(--hr-border); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                                        <!-- Internal Tabs -->
                                        <div style="display: flex; gap: 2px; background: #e2e8f0; padding: 4px 4px 0 4px;">
                                            <button @click="activeSubTabs[item.id] = 'detail'" :class="(activeSubTabs[item.id] || 'detail') === 'detail' ? 'active' : ''" class="main-tab" style="padding: 5px 12px; font-size: 0.7rem;">REQUEST DETAIL</button>
                                            <button @click="activeSubTabs[item.id] = 'customer'" :class="activeSubTabs[item.id] === 'customer' ? 'active' : ''" class="main-tab" style="padding: 5px 12px; font-size: 0.7rem;">Customer Name</button>
                                            <button @click="activeSubTabs[item.id] = 'warehouse'" :class="activeSubTabs[item.id] === 'warehouse' ? 'active' : ''" class="main-tab" style="padding: 5px 12px; font-size: 0.7rem;">Warehouse Name</button>
                                        </div>
                                        <div style="border: 1px solid var(--hr-border); border-top: none; padding: 0;">
                                            <!-- Detail Tool Tab -->
                                            <div x-show="(activeSubTabs[item.id] || 'detail') === 'detail'">
                                                <div style="background: #f1f5f9; padding: 4px 10px; border-bottom: 1px solid var(--hr-border); display: flex; gap: 10px;">
                                                    <span style="font-size: 0.65rem; color: #64748b; cursor: pointer;">Drag a column header here to group by that column</span>
                                                </div>
                                                <table class="list-grid" style="border: none;">
                                                    <thead>
                                                        <tr style="background: #f8fafc;">
                                                            <th style="width: 25px;"></th>
                                                            <th style="width: 40px;">HOLD</th>
                                                            <th style="width: 120px;">CODE</th>
                                                            <th style="width: 200px;">MATERIALS NAME</th>
                                                            <th style="width: 100px;">DIMENSIONS</th>
                                                            <th style="width: 60px;">VOLUME</th>
                                                            <th style="width: 50px;">QTY</th>
                                                            <th style="width: 90px;">REQ. DATE</th>
                                                            <th style="width: 40px;">OTW</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template x-for="(sub, sidx) in item.items" :key="sidx">
                                                            <tr @click="selectedSubItemIdxs[item.id] = sidx" 
                                                                :class="(selectedSubItemIdxs[item.id] || 0) === sidx ? 'status-active-row' : ''" 
                                                                style="cursor: pointer;">
                                                                <td style="text-align: center; font-size: 0.5rem; color: #475569;">
                                                                    <span x-show="(selectedSubItemIdxs[item.id] || 0) === sidx">▶</span>
                                                                </td>
                                                                <td style="text-align:center;">
                                                                    <input type="checkbox" :checked="sub.hold" disabled style="width:12px; height:12px; margin:0;">
                                                                </td>
                                                                <td x-text="sub.code"></td>
                                                                <td>
                                                                    <span x-text="sub.product_name"></span>
                                                                    <span style="color:#cbd5e1; float:right;">...</span>
                                                                </td>
                                                                <td x-text="sub.dimensions"></td>
                                                                <td x-text="sub.volume" style="text-align: right;"></td>
                                                                <td x-text="sub.qty" style="text-align: right;"></td>
                                                                <td x-text="sub.req_date"></td>
                                                                <td x-text="sub.otw" style="text-align: right;"></td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                                <div class="grid-footer" style="background: white; border-top: 1px solid var(--hr-border); padding: 5px 10px; justify-content: flex-end; gap: 10px;">
                                                     <input type="text" class="form-input" style="width: 50px; text-align: right; background: #f8fafc;" readonly>
                                                     <input type="text" class="form-input" style="width: 40px; text-align: right; background: white;" :value="item.total_qty" readonly>
                                                </div>
                                            </div>
                                            <!-- Customer Tab -->
                                            <div x-show="activeSubTabs[item.id] === 'customer'" style="padding: 15px; font-size: 0.75rem; color: #334155;">
                                                <div style="font-weight: bold; margin-bottom: 5px;">Customer Reference:</div>
                                                <div x-text="item.customer_name"></div>
                                            </div>
                                            <!-- Warehouse Tab -->
                                            <div x-show="activeSubTabs[item.id] === 'warehouse'" style="padding: 15px; font-size: 0.75rem; color: #334155;">
                                                <div style="font-weight: bold; margin-bottom: 5px;">Warehouse Destination:</div>
                                                <div x-text="item.warehouse_name"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </table>
            </div>
            <div class="grid-footer" style="padding: 4px; background: #e2e8f0; border-top: 1px solid var(--hr-border);">
                <button class="pager-btn" @click="firstRecord()" :disabled="currentIdx <= 0" title="First">|◀</button>
                <button class="pager-btn" @click="prevRecord()" :disabled="currentIdx <= 0" title="Previous">◀◀</button>
                <button class="pager-btn" @click="prevRecord()" :disabled="currentIdx <= 0" title="Previous">◀</button>
                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIdx + 1"></span> of <span x-text="requests.length"></span></span>
                <button class="pager-btn" @click="nextRecord()" :disabled="currentIdx >= requests.length - 1" title="Next">▶</button>
                <button class="pager-btn" @click="nextRecord()" :disabled="currentIdx >= requests.length - 1" title="Next">▶▶</button>
                <button class="pager-btn" @click="lastRecord()" :disabled="currentIdx >= requests.length - 1" title="Last">▶|</button>
            </div>
        </div>

        <!-- HISTORIES TAB -->
        <div class="tab-pane" :class="activeMainTab === 'history' ? 'active' : ''">
            <template x-if="selectedRequest">
                <div style="display: contents;">
                    <div class="bar-top">Drag a column header here to group by that column</div>
                    <div class="bar-search">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #64748b;">
                            <circle cx="11" cy="11" r="8"></circle> <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <div style="flex: 1; overflow: auto;">
                        <table class="list-grid">
                            <thead>
                                <tr>
                                    <th style="width: 30px; text-align: center;">ACC</th>
                                    <th style="width: 60px;">TYPE</th>
                                    <th style="width: 100px;">REF</th>
                                    <th style="width: 80px; text-align: right;">USERNO</th>
                                    <th style="width: 100px;">DATE</th>
                                    <th style="width: 60px; text-align: center;">TL.QTY</th>
                                    <th style="width: 350px;">NOTE</th>
                                    <th style="width: 80px; text-align: center;">PROCESS</th>
                                    <th style="width: 100px;">AUDIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(hist, hidx) in selectedRequest.histories" :key="hidx">
                                    <tr>
                                        <td style="text-align:center;">
                                            <input type="checkbox" :checked="hist.acc" disabled style="width:12px; height:12px; margin:0;">
                                        </td>
                                        <td x-text="hist.type"></td>
                                        <td x-text="hist.ref"></td>
                                        <td x-text="hist.user_no" style="text-align: right;"></td>
                                        <td x-text="hist.date"></td>
                                        <td x-text="hist.tl_qty" style="text-align: right;"></td>
                                        <td x-text="hist.note"></td>
                                        <td style="text-align:center;">
                                            <input type="checkbox" :checked="hist.process" disabled style="width:12px; height:12px; margin:0;">
                                        </td>
                                        <td x-text="hist.audit"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="grid-footer" style="padding: 4px; background: transparent; border-top: 1px solid var(--hr-border); border-bottom: 1px solid var(--hr-border); min-height: 28px;">
                    </div>
                </div>
            </template>
            <template x-if="!selectedRequest">
                <div style="padding: 40px; text-align: center; color: #64748b;">
                    No record selected.
                </div>
            </template>
        </div>
        
    </div> <!-- Closing tab-content -->

</div> <!-- Closing root x-data container -->
@endsection

@push('scripts')
<script>
    function goodsRequestManager() {
        return {
            activeMainTab: 'detail',
            requests: @json($requests),
            selectedRequest: null,
            currentIdx: 0,
            expandedIds: [],
            activeSubTabs: {},
            selectedItemIdx: 0,
            selectedSubItemIdxs: {},
            availableEmployees: ['ENDANG HIDAYAT', 'YUNITA CHAERUNISSA', 'ANNE MARIE', 'SINGGIH', 'JOHN DOE', 'BAMBANG', 'PUTRI'],
            availableProducts: [
                {code: '010170072084', name: 'BEARING SKF 6206 ZZ'},
                {code: 'OFF-001', name: 'PAPER A4 80G'},
                {code: 'OFF-002', name: 'PEN BLUE'},
                {code: 'ELC-001', name: 'CABLE TIE'},
                {code: '006671113', name: '3M CREAM CLEANSER'},
                {code: '0210269804', name: 'AC COIL CLEANER'},
                {code: '009140269150', name: 'AC DAIKIN 1 PK'}
            ],
            newItem: { code: '', product_name: '', qty: 1, description: '' },

            init() {
                if (this.requests && this.requests.length > 0) {
                    this.selectRequest(this.requests[0].id, 0);
                }
            },

            selectRequest(id, idx) {
                if (idx === undefined) {
                    idx = this.requests.findIndex(r => r.id === id);
                }
                const req = this.requests.find(r => r.id === id);
                if (req) {
                    this.selectedRequest = { ...req };
                    this.currentIdx = idx;
                    this.selectedItemIdx = 0; // Reset sub-item focus on main record change
                    this.newItem = { code: '', product_name: '', qty: 1, description: '' };
                }
            },

            // Navigation methods
            saveCurrentChanges() {
                if (this.selectedRequest) {
                    const idx = this.requests.findIndex(r => r.id === this.selectedRequest.id);
                    if (idx !== -1) {
                        this.requests[idx] = { ...this.selectedRequest };
                    }
                }
            },

            addNewItem() {
                if(!this.newItem.code && !this.newItem.product_name) return;
                this.selectedRequest.items.push({
                    code: this.newItem.code,
                    product_name: this.newItem.product_name,
                    qty: this.newItem.qty,
                    description: this.newItem.description,
                    req_date: this.selectedRequest.date, 
                    hold: false, dimensions: '', volume: '', otw: '0'
                });
                this.newItem = { code: '', product_name: '', qty: 1, description: '' };
                this.saveCurrentChanges();
                showToast('Item added', 'success');
            },

            removeItem(idx) {
                if(confirm('Remove this item?')) {
                    this.selectedRequest.items.splice(idx, 1);
                    this.saveCurrentChanges();
                }
            },

            onProductSelect(e) {
                const prod = this.availableProducts.find(p => p.code === e.target.value);
                if(prod) this.newItem.product_name = prod.name;
            },

            onProductSelectName(e) {
                const prod = this.availableProducts.find(p => p.name === e.target.value);
                if(prod) this.newItem.code = prod.code;
            },

            onDateChange(e) {
                const val = e.target.value;
                if (!val) return;
                
                const newDateDMY = val.split('-').reverse().join('/');
                const foundIdx = this.requests.findIndex(r => r.date === val || r.date === newDateDMY);
                
                if (foundIdx !== -1) {
                    this.saveCurrentChanges();
                    const found = this.requests[foundIdx];
                    this.selectRequest(found.id, foundIdx);
                } else {
                    this.selectedRequest.date = val;
                    this.saveCurrentChanges();
                }
            },

            toggleExpand(id) {
                if (this.expandedIds.includes(id)) {
                    this.expandedIds = this.expandedIds.filter(i => i !== id);
                } else {
                    this.expandedIds = [...this.expandedIds, id];
                    if (!this.activeSubTabs[id]) {
                        this.activeSubTabs = { ...this.activeSubTabs, [id]: 'detail' };
                    }
                }
            },

            firstRecord() { this.saveCurrentChanges(); if (this.requests.length > 0) this.selectRequest(this.requests[0].id, 0); },
            prevRecord() { this.saveCurrentChanges(); if (this.currentIdx > 0) this.selectRequest(this.requests[this.currentIdx - 1].id, this.currentIdx - 1); },
            nextRecord() { this.saveCurrentChanges(); if (this.currentIdx < this.requests.length - 1) this.selectRequest(this.requests[this.currentIdx + 1].id, this.currentIdx + 1); },
            lastRecord() { this.saveCurrentChanges(); if (this.requests.length > 0) this.selectRequest(this.requests[this.requests.length - 1].id, this.requests.length - 1); },

            formatDate(dateStr) {
                if (!dateStr) return '';
                const parts = dateStr.split('-');
                if (parts.length !== 3) return dateStr;
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.createNew(); break;
                    case 'save': 
                        this.saveCurrentChanges(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.selectedRequest, 'GoodsRequest_' + (this.selectedRequest?.doc_no?.replace(/\//g, '-') || 'Draft') + '.json');
                        }
                        showToast('Request saved to file', 'success'); 
                        break;
                    case 'delete': this.deleteRecord(); break;
                    case 'refresh': window.location.reload(); break;
                    case 'preview': window.print(); break;
                    case 'find': this.activeMainTab = 'list'; this.$nextTick(() => { if(typeof erpFindOpen === 'function') erpFindOpen(); }); break;
                    case 'undo': this.undoChanges(); break;
                    case 'save-as': 
                        this.saveAsNew(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.selectedRequest, 'GoodsRequest_Copy.json');
                        }
                        break;
                    case 'edit': this.focusFirstField(); break;
                    case 'barcode': showToast('Generating barcode...', 'info'); break;
                    case 'resend': showToast('Re-sending document...', 'info'); break;
                }
            },

            undoChanges() {
                if (confirm('Revert all unsaved changes for this request?')) {
                    this.selectRequest(this.selectedRequest.id, this.currentIdx);
                    showToast('Changes reverted', 'info');
                }
            },

            saveAsNew() {
                if (!this.selectedRequest) return;
                const clone = JSON.parse(JSON.stringify(this.selectedRequest));
                clone.id = this.requests.length + 1;
                clone.doc_no += ' (COPY)';
                this.requests.push(clone);
                this.selectRequest(clone.id, this.requests.length - 1);
                showToast('Request duplicated', 'success');
            },

            focusFirstField() {
                this.activeMainTab = 'detail';
                this.$nextTick(() => {
                    const firstInput = document.querySelector('.tab-pane.active input:not([readonly])');
                    if (firstInput) firstInput.focus();
                });
            },

            createNew() {
                const newId = this.requests.length + 1;
                const newReq = {
                    id: newId,
                    date: new Date().toISOString().split('T')[0],
                    doc_no: `REQ/${new Date().getFullYear()}/${String(newId).padStart(4, '0')}`,
                    division: 'GENERAL',
                    status: 'DRAFT',
                    requester: 'SYSTEM',
                    items_count: 0,
                    total_amount: 0,
                    note: '',
                    items: []
                };
                this.requests.push(newReq);
                this.selectRequest(newId, this.requests.length - 1);
                this.activeMainTab = 'detail';
                showToast('New request created', 'success');
            },

            deleteRecord() {
                if (!this.selectedRequest) return;
                if (confirm('Are you sure you want to delete this goods request?')) {
                    const idx = this.requests.findIndex(r => r.id === this.selectedRequest.id);
                    if (idx !== -1) {
                        this.requests.splice(idx, 1);
                        if (this.requests.length > 0) {
                            const newIdx = Math.min(idx, this.requests.length - 1);
                            this.selectRequest(this.requests[newIdx].id, newIdx);
                        } else {
                            this.selectedRequest = null;
                        }
                    }
                    showToast('Request deleted', 'success');
                }
            }
        }
    }
</script>
@endpush
