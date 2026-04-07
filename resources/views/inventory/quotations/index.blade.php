@extends('layouts.app')

@section('title', 'Quotations')

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

    .detail-form-area { padding: 10px 15px; background: #e2e8f0; border-bottom: 1px solid var(--hr-border); display: flex; justify-content: space-between; flex-shrink: 0; }
    .form-group { display: flex; align-items: center; margin-bottom: 4px; font-size: 0.75rem; }
    .form-label { width: 100px; text-align: right; margin-right: 10px; color: #475569; }
    .form-input { border: 1px solid var(--hr-border); padding: 4px 8px; border-radius: 0; font-size: 0.75rem; background: white; }
    .form-select { border: 1px solid var(--hr-border); padding: 3px 6px; border-radius: 0; font-size: 0.75rem; background: white; }
    
    .vertical-label { writing-mode: vertical-rl; transform: rotate(180deg); text-align: center; font-size: 0.7rem; padding: 10px 4px; background: white; border-right: 1px solid var(--hr-border); color: #475569; letter-spacing: 1px;}
    .bordered-panel { border: 1px solid var(--hr-border); display: flex; background: white; flex: 1; overflow: hidden; margin: 5px 10px 0 10px;}
    .panel-content { flex: 1; display:flex; flex-direction:column; overflow:hidden;}
</style>
@endpush

@section('content')
<div x-data="quotationManager()" x-init="init()" style="background: white; border: 1px solid var(--hr-border); margin: 10px;">
    <!-- Windows like Title bar -->
    <div style="background: white; padding: 0; border-bottom: 1px solid #e2e8f0;">
        <div style="background: transparent; padding: 6px 10px; color: #334155; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem;">
            <div style="display: flex; gap: 8px; align-items: center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7" fill="#dc2626"></rect><rect x="14" y="14" width="7" height="7" fill="#2563eb"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span style="font-weight: 600;">Quotations</span>
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
        <button class="main-tab" :class="activeMainTab === 'detail' ? 'active' : ''" @click="activeMainTab = 'detail'">QUOTATION DETAIL</button>
        <button class="main-tab" :class="activeMainTab === 'list' ? 'active' : ''" @click="activeMainTab = 'list'">QUOTATION LIST</button>
        <button class="main-tab" :class="activeMainTab === 'detail_list' ? 'active' : ''" @click="activeMainTab = 'detail_list'">DETAIL QUOTATION</button>
    </div>

    <div class="tab-content" style="border-top: none;">
        
        <!-- QUOTATION DETAIL TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail' ? 'active' : ''" style="background: #e2e8f0;">
            <!-- Header forms -->
            <div class="detail-form-area" style="justify-content: flex-start; gap: 30px;">
                <div>
                    <div class="form-group">
                        <div class="form-label">Date</div>
                        <select class="form-select" style="width: 140px;" disabled><option></option></select>
                    </div>
                    <div class="form-group">
                        <div class="form-label">Customer Name</div>
                         <select class="form-select" style="width: 250px;" disabled><option></option></select>
                    </div>
                    <div class="form-group">
                        <div class="form-label">Attentions 1</div>
                        <input type="text" class="form-input" style="width: 250px;" readonly>
                    </div>
                    <div class="form-group">
                        <div class="form-label">Attentions 2</div>
                        <input type="text" class="form-input" style="width: 250px;" readonly>
                    </div>
                    <div class="form-group">
                        <div class="form-label">Select Estimation</div>
                        <div style="display:flex;">
                            <input type="text" class="form-input" style="width: 200px;" readonly>
                            <span style="border: 1px solid var(--hr-border); background:#f1f5f9; padding: 4px 6px; border-left:none; cursor: pointer;">📁</span>
                            <span style="border: 1px solid var(--hr-border); background:#f1f5f9; padding: 4px 6px; border-left:none; cursor: pointer;">✕</span>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <div class="form-label" style="text-align: left; width: 80px;">Sales Name</div>
                        <div style="display:flex;">
                            <input type="text" class="form-input" style="width: 150px;" readonly>
                            <span style="border: 1px solid var(--hr-border); background:white; padding: 4px 6px; border-left:none; cursor: pointer;">
                                <div style="width:8px; height:8px; background:#3b82f6;"></div>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label" style="text-align: left; width: 80px;">PO. No.</div>
                        <input type="text" class="form-input" style="width: 175px;" readonly>
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
                                    <!-- Empty body -->
                                </tbody>
                            </table>
                        </div>
                        <div style="display:flex; justify-content:center; gap:60px; padding: 10px 20px; border-top:1px solid var(--hr-border);">
                            <input type="text" class="form-input" style="width: 100px; text-align:right;" value="0" readonly>
                            <input type="text" class="form-input" style="width: 100px; text-align:right;" value="0" readonly>
                        </div>
                        <div class="grid-footer" style="padding: 2px 4px;">
                            <button class="pager-btn">|◀</button> <button class="pager-btn">◀◀</button> <button class="pager-btn">◀</button>
                            <span class="pager-btn" style="border:none; background:transparent;">Record 0 of 0</span>
                            <button class="pager-btn">▶</button> <button class="pager-btn">▶▶</button> <button class="pager-btn">▶|</button>
                            <button class="pager-btn" style="margin-left:5px;">+</button> <button class="pager-btn">-</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Notes & Total Area -->
            <div style="padding: 10px; display: flex; justify-content: space-between; align-items: flex-end; background:#e2e8f0;">
                <div>
                    <div style="font-size: 0.75rem; font-weight: bold; color: #475569; margin-bottom: 2px; padding-left:2px; background:#e2e8f0; border:1px solid #cbd5e1; border-bottom:0; width:300px;">NOTES</div>
                    <div style="display:flex;">
                        <textarea style="width: 300px; height: 50px; border: 1px solid var(--hr-border); resize:none;"></textarea>
                        <div style="display:flex; flex-direction:column; border: 1px solid var(--hr-border); border-left:none; background:#f8fafc; justify-content:space-between;">
                           <span style="font-size:0.5rem; padding: 2px 4px; cursor:pointer; color:#64748b;">▲</span>
                           <span style="font-size:0.5rem; padding: 2px 4px; cursor:pointer; color:#64748b;">▼</span>
                        </div>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size: 0.75rem; font-weight: bold; color: #334155;">Total Offers</span>
                    <input type="text" class="form-input" style="width: 120px;" readonly>
                </div>
            </div>
        </div>

        <!-- QUOTATION LIST TAB -->
        <div class="tab-pane" :class="activeMainTab === 'list' ? 'active' : ''" style="background: white;">
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
                            <th style="width: 50px;">E...</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- History data mapped over if needed; leaving empty logic -->
                    </tbody>
                </table>
            </div>
            <div class="grid-footer" style="padding: 4px; background: transparent; border-top: 1px solid var(--hr-border); border-bottom: 1px solid var(--hr-border); display:flex; justify-content:space-between; min-height: 38px;">
                <div style="display:flex; align-items:center;">
                    <button class="pager-btn">|◀</button> <button class="pager-btn">◀◀</button> <button class="pager-btn">◀</button>
                    <span class="pager-btn" style="border:none; background:transparent;">Record 0 of 0</span>
                    <button class="pager-btn">▶</button> <button class="pager-btn">▶▶</button> <button class="pager-btn">▶|</button>
                </div>
                <div style="display:flex; gap:30px; padding-right:120px;">
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="0" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="0" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="0" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="0" readonly>
                </div>
            </div>
        </div>

        <!-- DETAIL QUOTATION TAB -->
        <div class="tab-pane" :class="activeMainTab === 'detail_list' ? 'active' : ''" style="background: white;">
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
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Empty logic -->
                    </tbody>
                </table>
            </div>
            <div class="grid-footer" style="padding: 4px; background: transparent; border-top: 1px solid var(--hr-border); border-bottom: 1px solid var(--hr-border); display:flex; justify-content:space-between; min-height: 38px;">
                <div style="display:flex; align-items:center;">
                    <button class="pager-btn">|◀</button> <button class="pager-btn">◀◀</button> <button class="pager-btn">◀</button>
                    <span class="pager-btn" style="border:none; background:transparent;">Record 0 of 0</span>
                    <button class="pager-btn">▶</button> <button class="pager-btn">▶▶</button> <button class="pager-btn">▶|</button>
                </div>
                <div style="display:flex; gap:10px; padding-right:10px;">
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="0" readonly>
                    <input type="text" class="form-input" style="width: 100px; text-align:right;" value="0" readonly>
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
            quotations: @json($quotations),

            init() {
               // Initialization logic here
            }
        }
    }
</script>
@endpush
