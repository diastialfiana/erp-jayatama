@extends('layouts.app')

@section('title', 'Goods Request')

@push('styles')
<style>
    :root {
        --hr-primary: #1e293b;
        --hr-border: #cbd5e1;
        --hr-accent: #2563eb;
    }

    .main-tabs { display: flex; gap: 2px; background: #f1f5f9; padding: 2px 5px 0 5px; border-radius: 0; width: 100%; border-bottom: 1px solid var(--hr-border); }
    .main-tab { padding: 8px 20px; font-size: 0.75rem; font-weight: normal; color: #64748b; background: #e2e8f0; border: 1px solid var(--hr-border); border-bottom: none; cursor: pointer; border-radius: 0; text-transform: uppercase; margin-right: 2px; }
    .main-tab.active { background: white; color: #1e293b; border: 1px solid var(--hr-border); border-bottom: 1px solid white; font-weight: 600; margin-bottom: -1px; }
    
    .tab-content { background: white; border-top: 1px solid var(--hr-border); min-height: 500px; padding: 0; display: flex; flex-direction: column; overflow: hidden; margin-top: -1px; }
    .tab-pane { display: none; flex: 1; flex-direction: column; height: 100%; min-height: calc(100vh - 150px); }
    .tab-pane.active { display: flex; }

    .bar-top { padding: 8px 10px; font-size: 0.75rem; color: #64748b; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; }
    .bar-search { padding: 4px 10px; display: flex; justify-content: flex-end; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; }

    /* Grid Styles */
    .list-grid { width: 100%; border-collapse: collapse; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; font-size: 0.75rem; }
    .list-grid th { background: #f8fafc; color: #475569; padding: 6px 8px; text-align: left; font-weight: 600; border: 1px solid var(--hr-border); white-space: nowrap; text-transform: uppercase; font-size: 0.65rem; }
    .list-grid td { padding: 6px 8px; border: 1px solid var(--hr-border); white-space: nowrap; color: #334155; font-size: 0.75rem; }
    .list-grid tr:hover td { background: #f8fafc; cursor: pointer; }
    .list-grid tr.selected td { background: #e2e8f0; }
    
    .grid-footer { display: flex; align-items: center; padding: 4px; border-top: 1px solid var(--hr-border); background: #f8fafc; gap: 2px; flex-shrink: 0; }
    .pager-btn { background: white; border: 1px solid #cbd5e1; padding: 2px 6px; font-size: 0.65rem; cursor: pointer; color: #64748b; }
    .pager-btn:hover { background: #f1f5f9; }

    .detail-form-area { padding: 15px; background: white; border-bottom: none; display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 4px; font-size: 0.75rem; }
    .form-label { width: 80px; text-align: right; margin-right: 10px; color: #475569; }
    .form-input { border: 1px solid var(--hr-border); padding: 4px 8px; border-radius: 0; font-size: 0.75rem; background: white; }
    .form-select { border: 1px solid var(--hr-border); padding: 3px 6px; border-radius: 0; font-size: 0.75rem; background: white; }
    
    .status-active-row td { background: #f1f5f9 !important; font-weight: 500; }

</style>
@endpush

@section('content')
<div x-data="goodsRequestManager()" x-init="init()" style="background: white; border: 1px solid var(--hr-border); margin: 10px;">
    <!-- Windows like Title bar -->
    <div style="background: white; padding: 0; border-bottom: 1px solid #e2e8f0;">
        <div style="background: transparent; padding: 6px 10px; color: #334155; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#dc2626"><path d="M4 4h16v16H4z"/></svg>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b" style="margin-left:-8px;"><path d="M4 4h16v16H4z"/></svg>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#2563eb" style="margin-left:-8px;"><path d="M4 4h16v16H4z"/></svg>
                <span style="font-weight: 600; margin-left:5px;">Goods Request</span>
            </div>
            <div style="display: flex; gap: 15px;">
                <span style="cursor: pointer; font-size: 0.9rem;">◁</span>
                <span style="cursor: pointer; font-size: 0.9rem;">▷</span>
                <span style="cursor: pointer;">✕</span>
            </div>
        </div>
    </div>

    <div class="main-tabs">
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">DETAIL REQUEST</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">LIST REQUEST</button>
        <button class="main-tab" :class="activeMainTab === 'history' ? 'active' : ''" @click="activeMainTab = 'history'">HISTORIES</button>
    </div>

    <div class="tab-content" style="border-top: none;">
        
        <!-- DETAIL REQUEST TAB -->
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
                                <div style="display: flex; align-items: center; background: white; border: 1px solid var(--hr-border); width: 350px; cursor: pointer;" @click="alert('Employee selection list would open here')">
                                    <input type="text" style="flex: 1; border: none; font-size: 0.75rem; padding: 4px 8px; cursor: pointer;" 
                                           x-model="selectedRequest.employees_name" readonly>
                                    <span style="background: #f1f5f9; padding: 4px 6px; border-left: 1px solid var(--hr-border); color: #64748b;">▼</span>
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
                                        </td>
                                        <td x-text="item.code"></td>
                                        <td x-text="item.product_name"></td>
                                        <td x-text="item.qty" style="text-align: center;"></td>
                                        <td x-text="item.description"></td>
                                    </tr>
                                </template>
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
            }
        }
    }
</script>
@endpush
