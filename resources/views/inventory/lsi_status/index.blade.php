@extends('layouts.app')

@section('title', 'LSI Status')

@push('styles')
<style>
    :root {
        --hr-border: #999;
        --hr-primary: #1e293b;
        --hr-accent: #2563eb;
    }

    .fa-window { background: #f0f0f0; border: 1px solid #999; margin: 10px; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; height: calc(100vh - 100px); box-shadow: 2px 2px 5px rgba(0,0,0,0.2); color: #000; }
    .window-title-bar { background: linear-gradient(to bottom, #4f78b1, #3a5a8f); color: white; padding: 4px 8px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; font-weight: bold; }
    
    .lsi-top-bar { display: flex; align-items: center; padding: 10px 15px; font-size: 0.75rem; color: #334155; gap: 20px; background: #f0f0f0; border-bottom: 1px solid var(--hr-border); }
    .lsi-radio-group { display: flex; align-items: center; gap: 15px; }
    .lsi-radio { display: flex; align-items: center; gap: 5px; cursor: pointer; }
    
    .lsi-info-panel { margin: 10px; border: 1px solid var(--hr-border); background: #f8fafc; border-radius: 4px; overflow: hidden; }
    .lsi-info-header { background: #e2e8f0; padding: 6px 12px; font-weight: 700; font-size: 0.7rem; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; }
    .lsi-info-body { padding: 15px; }
    
    .form-row { display: flex; margin-bottom: 8px; font-size: 0.75rem; align-items: center; }
    .form-label { width: 140px; text-align: right; margin-right: 15px; color: #64748b; }
    .form-input { border: 1px solid var(--hr-border); padding: 4px 8px; border-radius: 0; font-size: 0.75rem; background: white; }
    .form-select { border: 1px solid var(--hr-border); padding: 3px 6px; border-radius: 0; font-size: 0.75rem; background: white; }
    
    .list-grid { width: 100%; border-collapse: collapse; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; font-size: 0.7rem; background: white; table-layout: fixed; }
    .list-grid th { background: #f8fafc; color: #64748b; padding: 8px 6px; text-align: left; font-weight: 600; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); border-top: none; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.65rem; }
    .list-grid td { padding: 6px; border-bottom: 1px solid var(--hr-border); border-right: 1px solid var(--hr-border); white-space: nowrap; color: #334155; }
    .list-grid tr:hover td { background: #f1f5f9; }
</style>
@endpush

@section('content')
<div class="fa-window" x-data="{ 
    selectedStatus: 'AP', 
    handleRibbonAction(action) {
        switch(action) {
            case 'refresh': window.location.reload(); break;
            case 'preview': window.print(); break;
            case 'save': 
                if(typeof exportToJSONFile === 'function') {
                    exportToJSONFile({status: this.selectedStatus}, 'LSIStatus_' + this.selectedStatus + '.json');
                }
                showToast('Status view state saved to file', 'success'); 
                break;
            case 'new': showToast('Filter reset to default', 'info'); this.selectedStatus = 'AP'; break;
            case 'find': if(typeof erpFindOpen === 'function') erpFindOpen(); break;
            case 'undo': showToast('Changes reverted', 'info'); break;
            case 'edit': showToast('Editing state active', 'info'); break;
            case 'save-as':
                if(typeof exportToJSONFile === 'function') {
                    exportToJSONFile({status: this.selectedStatus}, 'LSIStatus_Copy.json');
                }
                showToast('Status view state copied to file', 'success'); 
                break;
            case 'barcode': showToast('No barcode for status view', 'warning'); break;
            case 'resend': showToast('Emailing current status...', 'info'); break;
        }
    }
}" x-on:ribbon-action.window="handleRibbonAction($event.detail)">
    <!-- Title Bar -->
    <div class="window-title-bar">
        <div style="display: flex; gap: 8px; align-items: center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>LSI View Status</span>
        </div>
        <div style="display: flex; gap: 4px;">
            <div style="width:14px;height:14px;background:#ddd;border:1px solid #999;cursor:pointer;"></div>
            <div style="width:14px;height:14px;background:#ddd;border:1px solid #999;cursor:pointer;"></div>
            <div style="width:14px;height:14px;background:#e81123;border:1px solid #999;cursor:pointer;"></div>
        </div>
    </div>

    @include('partials.ribbon_toolbar')

    <!-- Actions Bar -->
    <div class="lsi-top-bar">
        <span style="font-weight: 600;">Select Status:</span>
        <div class="lsi-radio-group">
            <label class="lsi-radio">
                <input type="radio" name="statusOptions" value="AP" x-model="selectedStatus"> 
                <span :class="selectedStatus === 'AP' ? 'font-bold text-blue-600' : ''">AP (Account Payable)</span>
            </label>
            <label class="lsi-radio">
                <input type="radio" name="statusOptions" value="AR" x-model="selectedStatus"> 
                <span :class="selectedStatus === 'AR' ? 'font-bold text-blue-600' : ''">AR (Advance Request)</span>
            </label>
        </div>
        
        <span style="margin-left: auto;">Type User numbering</span>
        <div style="display:flex;">
            <input type="text" class="form-input" style="width: 120px; height: 28px;">
            <span style="border: 1px solid var(--hr-border); background:#f1f5f9; padding: 0 8px; border-left:none; cursor: pointer; display: flex; align-items: center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </span>
        </div>
    </div>


    <!-- Information Panel -->
    <div class="lsi-info-panel">
        <div class="lsi-info-header">Information</div>
        <div class="lsi-info-body">
            <div style="display:flex; gap: 50px;">
                <!-- Left Details -->
                <div style="flex:1; max-width:400px;">
                    <div class="form-row">
                        <div class="form-label">Date Request / Receive</div>
                        <select class="form-select" style="width: 150px;"></select>
                    </div>
                    <div class="form-row">
                        <div class="form-label">Vendors / Employee</div>
                        <div style="display: flex; align-items: center; background: white; border: 1px solid var(--hr-border); width: 175px;">
                            <input type="text" style="flex: 1; border: none; font-size: 0.75rem; padding: 3px 6px; outline: none;" 
                                   list="vendor-list" placeholder="Type or Select">
                            <datalist id="vendor-list">
                                <option value="PT JAYATAMA"></option>
                                <option value="PT MAJU SELALU"></option>
                                <option value="ALICE WONDER"></option>
                                <option value="JOHN SMITH"></option>
                            </datalist>
                            <span style="border:none; border-left:1px solid var(--hr-border); background:#f1f5f9; cursor:pointer; padding: 3px 6px;">▼</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Details -->
                <div style="flex:1;">
                    <div class="form-row" style="margin-bottom: 12px;">
                        <div class="form-label" style="text-align:left; font-weight:500; color:#475569;">ID Records</div>
                    </div>
                    <div class="form-row">
                        <div class="form-label" style="text-align:left; width:80px;">Value / Total</div>
                        <div style="display:flex;">
                            <input type="text" class="form-input" style="width: 100px; text-align:right;" value="0">
                            <div style="display:flex; flex-direction:column; border: 1px solid var(--hr-border); border-left:none; background:#f8fafc; justify-content:space-between;">
                               <span style="font-size:0.5rem; padding: 0 4px; cursor:pointer; color:#64748b;">▲</span>
                               <span style="font-size:0.5rem; padding: 0 4px; cursor:pointer; color:#64748b;">▼</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Area -->
    <div style="margin: 10px; flex: 1; border: 1px solid var(--hr-border); border-top: none; overflow: auto;">
        <table class="list-grid">
            <thead>
                <tr>
                    <th style="width: 40px; text-align:center;">CHK</th>
                    <th style="width: 200px;">DESCRIPTION</th>
                    <th style="width: 40px;">GA</th>
                    <th style="width: 40px;">AP</th>
                    <th style="width: 40px;">AC</th>
                    <th style="width: 40px;">KC</th>
                    <th style="width: 40px;">PF</th>
                    <th style="width: 40px;">AP</th>
                    <th style="width: 50px;">DU/O</th>
                    <th style="width: 40px;">AP</th>
                    <th style="width: 40px;">KF</th>
                    <th style="width: 40px;">PF</th>
                    <th style="width: 150px;">NOTE</th>
                    <th style="width: 100px;">UPD. STATES</th>
                    <th style="width: 100px;">CHECKED BY</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <!-- Empty data as per screenshot -->
            </tbody>
        </table>
    </div>

</div>
@endsection
