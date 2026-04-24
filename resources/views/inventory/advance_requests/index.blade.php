@extends('layouts.app')

@section('title', 'Advance Request')

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

    .bar-top { padding: 8px 10px; font-size: 0.75rem; color: #64748b; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; }
    .bar-search { padding: 4px 10px; display: flex; justify-content: flex-end; background: white; border-bottom: 1px solid var(--hr-border); flex-shrink: 0; }

    /* Grid Styles */
    .list-grid { width: 100%; border-collapse: collapse; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; font-size: 0.75rem; }
    .list-grid th { background: #f1f5fb; color: #475569; padding: 4px 6px; text-align: left; font-weight: normal; border-bottom: 1px solid #cbd5e1; border-right: 1px solid #cbd5e1; white-space: nowrap; text-transform: uppercase; font-size: 0.65rem; }
    .list-grid td { padding: 4px 6px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; white-space: nowrap; color: #334155; height: 22px; }
    .list-grid tr:hover td { background: #f1f5f9; cursor: pointer; }
    .list-grid tr.selected td { background: #cbdcfd; }
    
    /* Status Table Specific Styles */
    .status-active-row { background-color: #cbdcfd !important; }
    .focus-text { border: 1px dotted #333; padding: 0 2px; }
    .blue-square { width: 14px; height: 14px; background: blue; margin: auto; }
    
    .grid-footer { display: flex; align-items: center; padding: 4px; border-top: 1px solid var(--hr-border); background: #f8fafc; gap: 2px; flex-shrink: 0; }
    .pager-btn { background: white; border: 1px solid #cbd5e1; padding: 2px 6px; font-size: 0.65rem; cursor: pointer; color: #64748b; }
    .pager-btn:hover { background: #f1f5f9; }
    .pager-btn:disabled { color: #cbd5e1; cursor: default; background: #f8fafc; }

    .detail-form-area { padding: 15px; background: white; border-bottom: 1px solid var(--hr-border); display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 4px; font-size: 0.75rem; }
    .form-label { width: 100px; text-align: right; margin-right: 10px; color: #475569; }
    .form-input { border: 1px solid var(--hr-border); padding: 4px 8px; border-radius: 0; font-size: 0.75rem; background: white; }
    .form-select { border: 1px solid var(--hr-border); padding: 3px 6px; border-radius: 0; font-size: 0.75rem; background: white; }

</style>
@endpush

@section('content')
<div x-data="advanceRequestManager()" x-init="init()" x-on:ribbon-action.window="handleRibbonAction($event.detail)" style="background: white; border: 1px solid var(--hr-border); margin: 10px;">
    <!-- Windows like Title bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 10px; align-items: center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#1d4ed8"><path d="M12 2l10 20H2L12 2z"/></svg>
            <span style="font-weight: 500;">Advance Request</span>
        </div>
        <div style="display: flex; gap: 15px;">
            <span style="cursor: pointer; font-size: 0.9rem;" @click="prevRecord()">◁</span>
            <span style="cursor: pointer; font-size: 0.9rem;" @click="nextRecord()">▷</span>
            <span style="cursor: pointer;">✕</span>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Main Navigation Tabs -->
    <div class="main-tabs" style="background: #f1f5f9; border-bottom: 1px solid var(--hr-border); padding-left: 10px; border-radius: 0;">
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">RECORD DETAIL</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">RECORDS LIST</button>
        <button class="main-tab" :class="activeMainTab === 'status' ? 'active' : ''" @click="activeMainTab = 'status'">ADVANCE STATUS</button>
    </div>

    <div class="tab-content" style="border-top: none;">
        
        <!-- RECORD DETAIL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''">
            <div class="detail-form-area" x-show="selectedRequest" style="padding: 10px 15px;">
                <div style="flex: 2;">
                    <div class="form-group" style="gap: 5px;">
                        <div class="form-label" style="width: 80px;">Date</div>
                        <div style="display: flex; align-items: center; width: 140px;">
                            <input type="date" class="form-input" style="width: 110px;" x-model="selectedRequest.date" x-ref="dateDetail" @change="onDateChange($event)">
                            <span style="border: 1px solid var(--hr-border); background:#f1f5f9; padding: 4px 6px; border-left:none; cursor: pointer;" @click="$refs.dateDetail.showPicker()">▼</span>
                        </div>
                        
                        <div class="form-label" style="width: 70px; margin-left:10px;">Duedate</div>
                        <div style="display: flex; align-items: center; width: 140px;">
                            <input type="date" class="form-input" style="width: 110px;" x-model="selectedRequest.due_date" x-ref="dueDetail">
                            <span style="border: 1px solid var(--hr-border); background:#f1f5f9; padding: 4px 6px; border-left:none; cursor: pointer;" @click="$refs.dueDetail.showPicker()">▼</span>
                        </div>

                        <div class="form-label" style="width: 50px; margin-left:10px;">Reff.</div>
                        <input type="text" class="form-input" style="background:#f8fafc; width: 140px;" x-model="selectedRequest.doc_ref" readonly>
                    </div>
                    <div class="form-group" style="gap: 5px;">
                        <div class="form-label" style="width: 80px;">Employes Name</div>
                        <div style="display: flex; align-items: center; background: white; border: 1px solid var(--hr-border); width: 275px;">
                            <input type="text" style="flex: 1; border: none; font-size: 0.75rem; padding: 4px 8px; outline: none;" 
                                   x-model="employeeNameInput" list="employee-names" placeholder="Type or Select Employee" @change="onEmployeeSelect">
                            <datalist id="employee-names">
                                <template x-for="emp in employees" :key="emp">
                                    <option :value="emp"></option>
                                </template>
                            </datalist>
                            <span style="border:none; border-left:1px solid var(--hr-border); background:#f1f5f9; cursor:pointer; padding: 4px 6px;">▼</span>
                        </div>
                    </div>
                    <div class="form-group" style="gap: 5px;">
                        <div class="form-label" style="width: 80px;">Bank Name</div>
                        <input type="text" class="form-input" style="width: 250px;" x-model="selectedRequest.bank_name">
                    </div>
                    <div class="form-group" style="gap: 5px;">
                        <div class="form-label" style="width: 80px;">Account No</div>
                        <input type="text" class="form-input" style="width: 250px;" x-model="selectedRequest.account_no">
                    </div>
                    <div class="form-group" style="gap: 5px;">
                        <div class="form-label" style="width: 80px;">Acccount Name</div>
                        <input type="text" class="form-input" style="width: 250px;" x-model="selectedRequest.account_name">
                    </div>
                </div>
                <!-- Right Side Info -->
                <div style="flex: 1; display: flex; flex-direction: column; align-items: flex-end; justify-content: space-between; padding-right: 30px; font-size: 0.8rem; color: #475569;">
                    <div style="display: flex; gap: 40px; margin-top: 10px;">
                        <span x-text="selectedRequest.user_no"></span>
                        <span x-text="selectedRequest.link || '0'"></span>
                        <span x-text="selectedRequest.unique_id"></span>
                    </div>
                    <div style="font-size: 1.2rem; font-weight: bold; color: #334155; margin-bottom: 30px;">
                        advance request
                    </div>
                </div>
            </div>
            
            <div style="flex: 1; overflow: auto;" x-show="selectedRequest">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 25px;"></th>
                            <th style="width: 120px;">CODE</th>
                            <th style="width: 150px;">BANK NAME</th>
                            <th style="width: 60px;">COST</th>
                            <th style="width: 60px;">DEPT.</th>
                            <th style="width: 120px; text-align: right;">AMOUNT</th>
                            <th style="width: 250px;">DESCRIPTION</th>
                            <th style="width: 200px;">ACCOUNT NAME</th>
                        </tr>
                    </thead>
                        <template x-for="(item, idx) in (selectedRequest ? selectedRequest.items || [] : [])" :key="idx">
                            <tr>
                                <td style="text-align: center; font-size: 0.5rem; color: #475569;">
                                    <span x-show="idx === selectedItemIdx">▶</span>
                                    <button @click="removeItem(idx)" x-show="idx === selectedItemIdx" style="background:#ef4444; color:white; border:none; border-radius:2px; font-size:8px; padding:2px 4px; cursor:pointer;" title="Remove Item">✕</button>
                                </td>
                                <td><input type="text" x-model="item.code" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.bank_name" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" list="bank-list" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.cost" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.dept" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" list="dept-list" @change="saveCurrentChanges()"></td>
                                <td><input type="number" x-model="item.amount" class="form-input" style="width: 100%; box-sizing: border-box; text-align: right; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.description" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                                <td><input type="text" x-model="item.account_name" class="form-input" style="width: 100%; box-sizing: border-box; border:none; background:transparent;" @change="saveCurrentChanges()"></td>
                            </tr>
                        </template>
                        <!-- New Item Row -->
                        <tr style="background: #f8fafc; border-top: 2px solid #cbd5e1;">
                            <td style="text-align: center; color: #10b981; font-weight: bold; cursor: pointer;" @click="addNewItem()" title="Add item">+</td>
                            <td style="padding: 2px;"><input type="text" x-model="newItem.code" class="form-input" style="width: 100%; box-sizing: border-box;" placeholder="Code..." @keydown.enter="addNewItem"></td>
                            <td style="padding: 2px;">
                                <input type="text" x-model="newItem.bank_name" class="form-input" style="width: 100%; box-sizing: border-box;" placeholder="Bank..." list="bank-list" @keydown.enter="addNewItem">
                                <datalist id="bank-list">
                                    <template x-for="b in ['BCA', 'MANDIRI', 'BNI', 'BRI']"><option :value="b"></option></template>
                                </datalist>
                            </td>
                            <td style="padding: 2px;"><input type="text" x-model="newItem.cost" class="form-input" style="width: 100%; box-sizing: border-box;" placeholder="Cost..." @keydown.enter="addNewItem"></td>
                            <td style="padding: 2px;">
                                <input type="text" x-model="newItem.dept" class="form-input" style="width: 100%; box-sizing: border-box;" placeholder="Dept..." list="dept-list" @keydown.enter="addNewItem">
                                <datalist id="dept-list">
                                    <template x-for="d in ['IT', 'HR', 'FINANCE', 'MARKETING']"><option :value="d"></option></template>
                                </datalist>
                            </td>
                            <td style="padding: 2px;"><input type="number" x-model="newItem.amount" class="form-input" style="width: 100%; box-sizing: border-box; text-align: right;" placeholder="0" @keydown.enter="addNewItem"></td>
                            <td style="padding: 2px;"><input type="text" x-model="newItem.description" class="form-input" style="width: 100%; box-sizing: border-box;" placeholder="Desc..." @keydown.enter="addNewItem"></td>
                            <td style="padding: 2px; display:flex;">
                                <input type="text" x-model="newItem.account_name" class="form-input" style="flex:1; box-sizing: border-box;" placeholder="Acc Name..." @keydown.enter="addNewItem">
                                <button @click="addNewItem()" style="background:#2563eb; color:white; border:none; padding: 2px 8px; cursor:pointer;" title="Add Item">Add</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Detail Footer -->
            <div class="grid-footer" style="padding: 10px; background: #f8fafc; justify-content: flex-start; gap: 20px; border-bottom: 1px solid var(--hr-border);">
                <!-- To align with AMOUNT column in grid -->
                <div style="width: 535px; text-align: right;">
                    <input type="text" class="form-input" style="width: 120px; text-align: right; background: white;" :value="selectedRequest ? selectedRequest.total_amount : ''" readonly>
                </div>
            </div>
            <div class="grid-footer" style="padding: 4px; background: white; border-bottom: 1px solid var(--hr-border);">
                <button class="pager-btn" @click="firstRecord()" :disabled="currentIdx <= 0">|◀</button>
                <button class="pager-btn" @click="firstRecord()" :disabled="currentIdx <= 0">◀◀</button>
                <button class="pager-btn" @click="prevRecord()" :disabled="currentIdx <= 0">◀</button>
                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIdx + 1"></span> of <span x-text="requests.length"></span></span>
                <button class="pager-btn" @click="nextRecord()" :disabled="currentIdx >= requests.length - 1">▶</button>
                <button class="pager-btn" @click="lastRecord()" :disabled="currentIdx >= requests.length - 1">▶▶</button>
                <button class="pager-btn" @click="lastRecord()" :disabled="currentIdx >= requests.length - 1">▶|</button>
            </div>
            
            <div style="padding: 10px; background: #e2e8f0;">
                <div style="font-size: 0.75rem; font-weight: bold; color: #475569; margin-bottom: 5px;">NOTE</div>
                <textarea x-model="selectedRequest.note" style="width: 300px; height: 60px; border: 1px solid var(--hr-border);"></textarea>
            </div>
        </div>

        <!-- RECORDS LIST TAB -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''">
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
                            <th style="width: 20px;"></th>
                            <th style="width: 50px;">POST</th>
                            <th style="width: 90px;">DATE</th>
                            <th style="width: 80px;">USER NO</th>
                            <th style="width: 100px;">DOC. REF</th>
                            <th style="width: 180px;">EMPLOYEES</th>
                            <th style="width: 120px;">BANK NAME</th>
                            <th style="width: 180px;">ACCOUNT NAME</th>
                            <th style="width: 120px;">ACCOUNT NO</th>
                            <th style="width: 50px; text-align: right;">LINK</th>
                            <th style="width: 100px; text-align: right;">TOTAL</th>
                            <th style="width: 200px;">NOTE</th>
                            <th style="width: 100px;">AUDIT</th>
                            <th style="width: 100px;">UNIQUE ID</th>
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
                                            <span style="margin-top: -1px; display: block;" x-text="expandedIds.includes(item.id) ? '-' : '+'"></span>
                                        </button>
                                        <input type="checkbox" :checked="item.post" disabled style="width:12px; height:12px; margin:0;">
                                    </div>
                                </td>
                                <td x-text="formatDate(item.date)"></td>
                                <td x-text="item.user_no" style="text-align: right;"></td>
                                <td x-text="item.doc_ref"></td>
                                <td>
                                    <span x-text="item.employees_name" style="display: inline-block;"></span>
                                    <span style="color:#cbd5e1; float:right;">...</span>
                                </td>
                                <td x-text="item.bank_name"></td>
                                <td>
                                    <span x-text="item.account_name" style="display: inline-block;"></span>
                                    <span x-show="item.account_name" style="color:#cbd5e1; float:right;">...</span>
                                </td>
                                <td x-text="item.account_no"></td>
                                <td x-text="item.link" style="text-align: right;"></td>
                                <td x-text="item.total" style="text-align: right;"></td>
                                <td x-text="item.note"></td>
                                <td>
                                    <span style="color:#cbd5e1; margin-right: 4px;">...</span>
                                    <span x-text="item.audit"></span>
                                </td>
                                <td x-text="item.unique_id"></td>
                            </tr>
                            <!-- EXPANDABLE ROW -->
                            <tr x-show="expandedIds.includes(item.id)" style="background: #f8fafc;">
                                <td colspan="14" style="padding: 10px 20px;">
                                    <div style="background: white; border: 1px solid var(--hr-border); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                                        <!-- Internal Tabs -->
                                        <div style="display: flex; gap: 2px; background: #e2e8f0; padding: 4px 4px 0 4px;">
                                            <button @click="activeSubTabs[item.id] = 'detail'" :class="(activeSubTabs[item.id] || 'detail') === 'detail' ? 'active' : ''" class="main-tab" style="padding: 5px 12px; font-size: 0.7rem;">Advance Detail</button>
                                            <button @click="activeSubTabs[item.id] = 'status'" :class="activeSubTabs[item.id] === 'status' ? 'active' : ''" class="main-tab" style="padding: 5px 12px; font-size: 0.7rem;">Advan_Status</button>
                                        </div>
                                        <div style="border: 1px solid var(--hr-border); border-top: none; padding: 0;">
                                            <!-- Advance Detail Tab -->
                                            <div x-show="(activeSubTabs[item.id] || 'detail') === 'detail'">
                                                <div style="background: #f1f5f9; padding: 4px 10px; border-bottom: 1px solid var(--hr-border); display: flex; gap: 10px;">
                                                    <span style="font-size: 0.65rem; color: #64748b; cursor: pointer;">Drag a column header here to group by that column</span>
                                                </div>
                                                <table class="list-grid" style="border: none;">
                                                    <thead>
                                                        <tr style="background: #f8fafc;">
                                                            <th style="width: 25px;"></th>
                                                            <th style="width: 120px;">CODE</th>
                                                            <th style="width: 150px;">BANK NAME</th>
                                                            <th style="width: 60px;">COST</th>
                                                            <th style="width: 60px;">DEPT.</th>
                                                            <th style="width: 120px; text-align: right;">AMOUNT</th>
                                                            <th style="width: 250px;">DESCRIPTIONS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template x-for="(sub, sidx) in item.items" :key="sidx">
                                                            <tr>
                                                                <td style="text-align: center; font-size: 0.5rem; color: #475569;">
                                                                    <span x-show="sidx === 0">▶</span>
                                                                </td>
                                                                <td x-text="sub.code"></td>
                                                                <td x-text="sub.bank_name"></td>
                                                                <td x-text="sub.cost"></td>
                                                                <td x-text="sub.dept"></td>
                                                                <td x-text="sub.amount" style="text-align: right;"></td>
                                                                <td x-text="sub.description"></td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                                <div class="grid-footer" style="background: white; border-top: 1px solid var(--hr-border); padding: 5px 10px; justify-content: flex-end; gap: 10px;">
                                                     <input type="text" class="form-input" style="width: 100px; text-align: right; background: white;" :value="item.total" readonly>
                                                </div>
                                            </div>
                                            <!-- Advance Status Tab -->
                                            <div x-show="activeSubTabs[item.id] === 'status'">
                                                <table class="list-grid" style="border: none;">
                                                    <thead>
                                                        <tr style="background: #f8fafc;">
                                                            <th style="width: 40px; text-align: center;">CHK</th>
                                                            <th style="width: 250px;">DESCRIPTION</th>
                                                            <th>DATE</th>
                                                            <th>CHECKER</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template x-for="step in (item.status_steps || [])" :key="step.id">
                                                            <tr>
                                                                <td style="text-align: center;"><span x-show="step.ga">▶</span></td>
                                                                <td x-text="step.description"></td>
                                                                <td x-text="step.date"></td>
                                                                <td x-text="step.checker"></td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                    </tbody>
                </table>
            </div>
            <div class="grid-footer" style="padding: 4px; background: #e2e8f0; border-top: 1px solid var(--hr-border);">
                <button class="pager-btn" @click="firstRecord()" :disabled="currentIdx <= 0">|◀</button>
                <button class="pager-btn" @click="firstRecord()" :disabled="currentIdx <= 0">◀◀</button>
                <button class="pager-btn" @click="prevRecord()" :disabled="currentIdx <= 0">◀</button>
                <span class="pager-btn" style="border:none; background:transparent;">Record <span x-text="currentIdx + 1"></span> of <span x-text="requests.length"></span></span>
                <button class="pager-btn" @click="nextRecord()" :disabled="currentIdx >= requests.length - 1">▶</button>
                <button class="pager-btn" @click="lastRecord()" :disabled="currentIdx >= requests.length - 1">▶▶</button>
                <button class="pager-btn" @click="lastRecord()" :disabled="currentIdx >= requests.length - 1">▶|</button>
            </div>
        </div>

        <!-- ADVANCE STATUS TAB -->
        <div class="tab-pane" :class="activeMainTab === 'status' ? 'active' : ''" style="display: flex; flex-direction: column;">
            <div x-show="selectedRequest" style="padding: 10px 15px; border-bottom: 1px solid #cbd5e1; background: #f8fafc; position: relative;">
                <!-- Top Row: Date, Duedate, Reff -->
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 5px;">
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="font-size: 0.75rem; color: #333; width: 40px;">Date</span>
                        <div style="display: flex; align-items: center; background: white; border: 1px solid #cbd5e1;">
                            <input type="text" style="width: 100px; border: none; font-size: 0.75rem; padding: 2px 5px;" 
                                   :value="selectedRequest.date ? formatDate(selectedRequest.date) : ''" readonly>
                            <span style="padding: 0 4px; color: #666; font-size: 8px;">▼</span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="font-size: 0.75rem; color: #333;">Duedate</span>
                        <div style="display: flex; align-items: center; background: white; border: 1px solid #cbd5e1;">
                            <input type="text" style="width: 100px; border: none; font-size: 0.75rem; padding: 2px 5px;"
                                   :value="selectedRequest.due_date ? formatDate(selectedRequest.due_date) : ''" readonly>
                            <span style="padding: 0 4px; color: #666; font-size: 8px;">▼</span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="font-size: 0.75rem; color: #333;">Reff.</span>
                        <input type="text" style="width: 100px; border: 1px solid #cbd5e1; padding: 2px 5px; font-size: 0.75rem;" 
                               x-model="selectedRequest.doc_ref">
                    </div>
                    
                    <!-- Right Corner Numbers -->
                    <div style="position: absolute; right: 20px; top: 10px; display: flex; gap: 30px; font-size: 0.75rem; color: #333;">
                        <span x-text="selectedRequest.user_no"></span>
                        <span>0</span>
                        <span x-text="selectedRequest.unique_id"></span>
                    </div>
                </div>
                
                <!-- Bottom Row: Employees Name -->
                <div style="display: flex; align-items: center; gap: 5px;">
                    <span style="font-size: 0.75rem; color: #333; width: 85px;">Employes Name</span>
                    <div style="display: flex; align-items: center; background: white; border: 1px solid #cbd5e1; width: 300px;">
                        <input type="text" style="flex: 1; border: none; font-size: 0.75rem; padding: 2px 5px;"
                               x-model="selectedRequest.employees_name" readonly>
                        <span style="padding: 0 4px; color: #666; font-size: 8px;">▼</span>
                    </div>
                </div>
            </div>
            
            <div style="flex: 1; overflow: auto;" x-show="selectedRequest">
                <table class="list-grid">
                    <thead>
                        <tr>
                            <th style="width: 22px;"></th> <!-- Indicator -->
                            <th style="width: 30px;">CHK</th>
                            <th style="width: 250px;">DESCRIPTION</th>
                            <th style="width: 35px; text-align: center;">GA</th>
                            <th style="width: 35px; text-align: center;">US</th>
                            <th style="width: 35px; text-align: center;">AP</th>
                            <th style="width: 35px; text-align: center;">AC</th>
                            <th style="width: 35px; text-align: center;">KC</th>
                            <th style="width: 35px; text-align: center;">PF</th>
                            <th style="width: 35px; text-align: center;">AP</th>
                            <th style="width: 45px; text-align: center;">DU/O</th>
                            <th style="width: 35px; text-align: center;">AP</th>
                            <th style="width: 35px; text-align: center;">KF</th>
                            <th style="width: 35px; text-align: center;">PF</th>
                            <th style="width: 150px;">NOTE</th>
                            <th style="width: 100px;">UPD. STATES</th>
                            <th style="width: 100px;">CHECK BY</th>
                        </tr>
                    </thead>
                    <template x-for="(step, sidx) in (selectedRequest.status_steps || statusSteps)" :key="step.id">
                        <tbody :class="expandedStatusIds.includes(step.id) ? 'selected-group' : ''">
                                <tr @click="toggleStatusExpand(step.id)" 
                                    :class="sidx === 0 ? 'status-active-row' : ''"
                                    style="cursor:pointer; border-bottom: 1px solid #e2e8f0;">
                                    <td style="text-align: center; width: 22px; border-right: 1px solid #cbd5e1;">
                                        <span x-show="sidx === 0" style="font-size: 8px;">▶</span>
                                    </td>
                                    <td style="text-align: center; width: 30px; border-right: 1px solid #cbd5e1;">
                                        <input type="checkbox" :checked="step.ga" disabled style="width:12px; height:12px; margin:0;">
                                    </td>
                                    <td style="border-right: 1px solid #cbd5e1;">
                                        <span :class="sidx === 0 ? 'focus-text' : ''" x-text="step.description"></span>
                                    </td>
                                    <!-- GA Column -->
                                    <td style="text-align: center; width: 35px; border-right: 1px solid #cbd5e1;">
                                        <div :class="step.ga ? 'blue-square' : ''"></div>
                                    </td>
                                    <td style="text-align: center; width: 35px; border-right: 1px solid #cbd5e1;"><div :class="step.us ? 'blue-square' : ''"></div></td>
                                    <td style="text-align: center; width: 35px; border-right: 1px solid #cbd5e1;"><div :class="step.ap ? 'blue-square' : ''"></div></td>
                                    <td style="text-align: center; width: 35px; border-right: 1px solid #cbd5e1;"><div :class="step.ac ? 'blue-square' : ''"></div></td>
                                    <td style="text-align: center; width: 35px; border-right: 1px solid #cbd5e1;"><div :class="step.kc ? 'blue-square' : ''"></div></td>
                                    <td style="text-align: center; width: 35px; border-right: 1px solid #cbd5e1;"><div :class="step.pf ? 'blue-square' : ''"></div></td>
                                    <td style="text-align: center; width: 35px; border-right: 1px solid #cbd5e1;"><div :class="step.ap2 ? 'blue-square' : ''"></div></td>
                                    <td style="text-align: center; width: 45px; border-right: 1px solid #cbd5e1;"><div :class="step.duo ? 'blue-square' : ''"></div></td>
                                    <td style="text-align: center; width: 35px; border-right: 1px solid #cbd5e1;"><div :class="step.ap3 ? 'blue-square' : ''"></div></td>
                                    <td style="text-align: center; width: 35px; border-right: 1px solid #cbd5e1;"><div :class="step.kf ? 'blue-square' : ''"></div></td>
                                    <td style="text-align: center; width: 35px; border-right: 1px solid #cbd5e1;"><div :class="step.pf2 ? 'blue-square' : ''"></div></td>
                                    <td x-text="step.note" style="border-right: 1px solid #cbd5e1;"></td>
                                    <td x-text="step.date" style="font-size: 0.65rem; border-right: 1px solid #cbd5e1;"></td>
                                    <td x-text="step.checker"></td>
                                </tr>
                                <tr x-show="expandedStatusIds.includes(step.id)" style="background: #f8fafc;">
                                    <td colspan="17" style="padding: 10px 40px; border-bottom: 1px solid #cbd5e1;">
                                        <div style="background: white; border: 1px solid #cbd5e1; padding: 5px;">
                                            <div style="font-weight: bold; margin-bottom: 5px; color: #1e3a8a;">Step Details</div>
                                            <div style="font-size: 0.7rem; color: #475569;">
                                                Status: <span x-text="step.ga ? 'Completed' : 'Pending'"></span> | 
                                                Last Update: <span x-text="step.date || 'N/A'"></span> | 
                                                Checker: <span x-text="step.checker || 'N/A'"></span>
                                            </div>
                                            <div style="margin-top: 5px; font-size: 0.7rem; color: #64748b;">
                                                Note: <span x-text="step.note || 'No specific notes for this step.'"></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                        </tbody>
                    </template>
                </table>
            </div>
        </div>
        
    </div>

</div>
@endsection

@push('scripts')
<script>
    function advanceRequestManager() {
        return {
            activeMainTab: 'detail',
            requests: @json($advanceRequests),
            selectedRequest: null,
            currentIdx: 0,
            expandedIds: [],
            activeSubTabs: {},
            employees: ['JANE DOE', 'JOHN SMITH', 'ALICE WONDER', 'BOB BUILDER'],
            employeeNameInput: '',
            selectedItemIdx: 0,
            newItem: { code: 'ADV-NEW', bank_name: '', cost: '', dept: '', amount: 0, description: '', account_name: '' },
            
            // Mock workflow steps for Advance Status as seen in screenshot
            statusSteps: [
                { id: 1, description: 'Invoice diterima dari vendor', ga: true, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', date: '5/1/26 11:22:38', checker: 'ANNE' },
                { id: 2, description: 'Invoice di serahkan ke user', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', date: '', checker: '' },
                { id: 3, description: 'Invoice diterima dari user', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', date: '', checker: '' },
                { id: 4, description: 'Pembuatan voucher transaksi', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', date: '', checker: '' },
                { id: 5, description: 'Checker', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', date: '', checker: '' },
                { id: 6, description: 'Otorisasi PINDIV Finance', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', date: '', checker: '' },
                { id: 7, description: 'Pembuatan Form IB/Cheque/Giro', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', date: '', checker: '' },
                { id: 8, description: 'Otorisasi Direksi', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', date: '', checker: '' },
                { id: 9, description: 'Input e-banking', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', date: '', checker: '' },
                { id: 10, description: 'Approval e-banking', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', date: '', checker: '' },
                { id: 11, description: 'Transaction Release', ga: false, us: false, ap: false, ac: false, kc: false, pf: false, ap2: false, duo: false, ap3: false, kf: false, pf2: false, note: '', date: '', checker: '' }
            ],

            init() {
                if (this.requests && this.requests.length > 0) {
                    let defaultIdx = 1; 
                    if(this.requests.length <= 1) defaultIdx = 0;
                    this.selectRequest(this.requests[defaultIdx].id, defaultIdx);
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
                }
            },

            expandedStatusIds: [],
            
            toggleStatusExpand(id) {
                if (this.expandedStatusIds.includes(id)) {
                    this.expandedStatusIds = this.expandedStatusIds.filter(i => i !== id);
                } else {
                    this.expandedStatusIds = [...this.expandedStatusIds, id];
                }
            },

            onEmployeeSelect() {
                if (this.selectedRequest) {
                    this.selectedRequest.employees_name = this.employeeNameInput;
                    this.saveCurrentChanges();
                }
            },

            addNewItem() {
                if(!this.newItem.description) return;
                if(!this.selectedRequest.items) this.selectedRequest.items = [];
                
                this.selectedRequest.items.push({...this.newItem});
                this.newItem = { code: 'ADV-NEW', bank_name: '', cost: '', dept: '', amount: 0, description: '', account_name: '' };
                this.saveCurrentChanges();
            },

            removeItem(idx) {
                if(confirm('Remove this item?')) {
                    this.selectedRequest.items.splice(idx, 1);
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

            formatDate(dateStr) {
                if (!dateStr) return '';
                const parts = dateStr.split('-');
                if (parts.length !== 3) return dateStr;
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            },

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
                
                // In this module, the data uses yyyy-mm-dd or dd/mm/yyyy.
                // The input gives yyyy-mm-dd.
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

            // Navigation methods
            firstRecord() { this.saveCurrentChanges(); if (this.requests.length > 0) this.selectRequest(this.requests[0].id, 0); },
            prevRecord() { this.saveCurrentChanges(); if (this.currentIdx > 0) this.selectRequest(this.requests[this.currentIdx - 1].id, this.currentIdx - 1); },
            nextRecord() { this.saveCurrentChanges(); if (this.currentIdx < this.requests.length - 1) this.selectRequest(this.requests[this.currentIdx + 1].id, this.currentIdx + 1); },
            lastRecord() { this.saveCurrentChanges(); if (this.requests.length > 0) this.selectRequest(this.requests[this.requests.length - 1].id, this.requests.length - 1); },

            handleRibbonAction(action) {
                switch(action) {
                    case 'new': this.createNew(); break;
                    case 'save': 
                        this.saveCurrentChanges(); 
                        if(typeof exportToJSONFile === 'function') {
                            exportToJSONFile(this.selectedRequest, 'AdvanceRequest_' + (this.selectedRequest?.doc_no?.replace(/\//g, '-') || 'Draft') + '.json');
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
                            exportToJSONFile(this.selectedRequest, 'AdvanceRequest_Copy.json');
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
                    const firstInput = document.querySelector('.fa-pane.active input:not([readonly]), .tab-pane.active input:not([readonly])');
                    if (firstInput) firstInput.focus();
                });
            },

            createNew() {
                const newId = this.requests.length + 1;
                const newReq = {
                    id: newId,
                    date: new Date().toISOString().split('T')[0],
                    doc_no: `ADV/${new Date().getFullYear()}/${String(newId).padStart(4, '0')}`,
                    division: 'GENERAL',
                    status: 'DRAFT',
                    employees_name: 'SYSTEM',
                    amount: 0,
                    note: '',
                    status_steps: JSON.parse(JSON.stringify(this.statusSteps))
                };
                this.requests.push(newReq);
                this.selectRequest(newId, this.requests.length - 1);
                this.activeMainTab = 'detail';
                showToast('New advance request created', 'success');
            },

            deleteRecord() {
                if (!this.selectedRequest) return;
                if (confirm('Are you sure you want to delete this advance request?')) {
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
