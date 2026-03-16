@extends('layouts.app')
@section('title', 'Fixed Assets')

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

        /* ── Main Content Wrapper ── */
        .main-content {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid #eef2ff;
        }

        /* ── Form Layout ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .form-group {
            margin-bottom: 14px;
            display: flex;
            align-items: center;
        }

        .form-group label {
            flex: 0 0 160px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-right: 15px;
            text-align: right;
        }

        .form-control {
            flex: 1;
            padding: 8px 12px;
            font-size: 0.85rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            color: #1e293b;
            background-color: #f8fafc;
            transition: all 0.2s;
            width: 100%;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #2563EB;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 32px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
            display: flex;
            align-items: center;
        }
        
        .form-row .form-group label {
            flex: 0 0 160px; /* Primary label in a row */
            margin-right: 15px;
        }
        
        .form-row .form-group.pl-3 label {
            flex: 0 0 95px; /* Secondary label alignment */
            margin-left: 10px;
            text-align: right;
        }

        .radio-group {
            display: flex;
            gap: 15px;
            flex: 1;
            align-items: center;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #374151;
            cursor: pointer;
        }

        input[type="radio"] {
            accent-color: #2563EB;
            width: 14px;
            height: 14px;
        }

        .textarea-control {
            resize: vertical;
            min-height: 80px;
        }

        /* ── QR Code Section ── */
        .qr-section {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
        }

        .qr-placeholder {
            text-align: center;
        }
        
        .qr-placeholder img {
            width: 120px;
            height: 120px;
            background: #fff;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .qr-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 0.05em;
        }

        /* ── Status Section ── */
        .status-grid {
            background: #f8fafc;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            margin-top: 20px;
        }

        .status-grid .form-group label {
            flex: 0 0 120px;
        }

        /* ── Buttons ── */
        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            margin-top: 20px;
        }

        .btn {
            padding: 8px 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: #2563EB;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-outline {
            background: transparent;
            border-color: #cbd5e1;
            color: #475569;
        }

        .btn-outline:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        /* ── Modal Styles ── */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            opacity: 0;
            transition: opacity 0.2s ease-out;
        }
        
        .modal-overlay.show {
            display: flex;
            opacity: 1;
        }

        .custom-modal {
            background: #f8fafc;
            border-radius: 10px;
            width: 100%;
            max-width: 750px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: scale(0.95);
            transition: transform 0.2s ease-out;
            border: 1px solid #cbd5e1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .modal-overlay.show .custom-modal {
            transform: scale(1);
        }

        .modal-header {
            background: #e2e8f0;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #cbd5e1;
        }

        .modal-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .modal-title svg {
            width: 18px; height: 18px; color: #f59e0b;
        }

        .modal-close {
            background: none; border: none; font-size: 1.25rem;
            color: #64748b; cursor: pointer; line-height: 1;
        }
        .modal-close:hover { color: #ef4444; }

        .modal-body {
            background: #f1f5f9;
            padding: 0;
            flex: 1;
        }
        
        .modal-tabs {
            display: flex;
            background: #f1f5f9;
            border-bottom: 1px solid #cbd5e1;
            padding: 10px 20px 0 20px;
        }
        
        .mod-tab {
            padding: 8px 16px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            border-bottom: none;
            border-radius: 4px 4px 0 0;
            margin-right: 4px;
            cursor: pointer;
        }
        
        .mod-tab.active {
            background: #f8fafc;
            color: #334155;
            border-bottom-color: #f8fafc;
            margin-bottom: -1px;
            z-index: 1;
        }

        .mod-content {
            display: none;
            padding: 30px;
            background: #f8fafc;
        }
        
        .mod-content.active {
            display: block;
        }

        /* ── Modal Form Content ── */
        .mod-form-group {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        .mod-form-group label {
            flex: 0 0 130px;
            text-align: right;
            margin-right: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
        }
        
        /* ── Data Table ── */
        .table-responsive {
            overflow-x: auto;
            background: #fff;
            border: 1px solid #cbd5e1;
            margin: 0;
            height: 300px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }
        .data-table th, .data-table td {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }
        .data-table th {
            background: #e2e8f0;
            color: #334155;
            font-weight: 600;
            text-transform: uppercase;
        }
        .data-table tr.active {
            background: #cbd5e1; /* Simulating selected row */
        }
        .data-table tr:hover:not(.active) {
            background: #f1f5f9;
            cursor: pointer;
        }
        
        /* ── Responsive ── */
        @media (max-width: 992px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .form-group, .mod-form-group {
                flex-direction: column;
                align-items: flex-start;
            }
            .form-group label, .mod-form-group label {
                flex: none;
                margin-bottom: 6px;
                text-align: left;
            }
            .form-row {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">Fixed Assets</h1>
        <p class="page-desc">Kelola data Master Fixed Assets, penyusutan, dan akumulasi nilai aset.</p>
    </div>

    <!-- MAIN FORM WRAPPER -->
    <div class="main-content">
        <div class="form-grid">
            <!-- Left Column -->
            <div class="form-col-left">
                <div class="form-row">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" id="main-asset-code" class="form-control" value="001101">
                    </div>
                    <div class="form-group pl-3">
                        <label>Currency</label>
                        <select class="form-control">
                            <option>Indonesia</option>
                            <option>USD</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Fixed Asset Name</label>
                        <input type="text" class="form-control" value="lemari">
                    </div>
                    <div class="form-group pl-3">
                        <label>Qty</label>
                        <input type="number" class="form-control" value="0">
                    </div>
                </div>

                <div class="form-group">
                    <label>Initial Date</label>
                    <input type="date" class="form-control" value="2017-07-18">
                </div>

                <div class="form-group">
                    <label>Cost Center</label>
                    <input type="text" class="form-control" value="UMUM">
                </div>

                <div class="form-group">
                    <label>Department</label>
                    <select class="form-control">
                        <option value=""></option>
                        <option value="IT">IT</option>
                        <option value="Finance">Finance</option>
                        <option value="Procurement">Procurement</option>
                        <option value="GA">GA</option>
                        <option value="HR">HR</option>
                        <option value="HC">HC</option>
                        <option value="Legal">Legal</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <div style="display:flex; flex:1; gap:6px;">
                        <input type="text" class="form-control" value="Perabot Kantor Unsur Logam" readonly style="cursor: pointer; background-color: #fff;" onclick="openCategoryModal()">
                        <button type="button" class="btn btn-outline" style="padding: 0 10px;" onclick="openCategoryModal()">...</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" class="form-control" value="HEAD OFFICE">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Initial Cost</label>
                        <input type="text" class="form-control" value="1.667.000">
                    </div>
                    <div class="form-group pl-3">
                        <label>Rate</label>
                        <input type="number" class="form-control" value="1">
                    </div>
                </div>

                <div class="form-group">
                    <label>Residual Values</label>
                    <input type="text" class="form-control" value="0">
                </div>

                <div class="form-group">
                    <label>Deprec. Method</label>
                    <div class="radio-group">
                        <label class="radio-item"><input type="radio" name="deprec_method" value="None"> (None)</label>
                        <label class="radio-item"><input type="radio" name="deprec_method" value="Declining"> Declining</label>
                        <label class="radio-item"><input type="radio" name="deprec_method" value="Straight Line" checked> Straight Line</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Useful Life</label>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <input type="number" class="form-control" style="width: 70px;" value="96">
                            <span style="font-size:0.8rem; color:#64748b;">Month</span>
                        </div>
                    </div>
                    <div class="form-group pl-3">
                        <label>% Depreciation</label>
                        <input type="text" class="form-control" value="0,13">
                    </div>
                </div>

                <div class="form-group">
                    <label>Reg. / Serial No.</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>Supplier Name</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>Services provider</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>User</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>Valid Guaranty</label>
                    <input type="date" class="form-control">
                </div>

                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>Assets Type</label>
                    <input type="text" class="form-control">
                </div>
            </div>

            <!-- Right Column -->
            <div class="form-col-right">
                <div class="form-group">
                    <label>Accumulated Account</label>
                    <select class="form-control">
                        <option>AKUM PENYST PERABOT KTR UNSUR LOGAM</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Depreciation Expense</label>
                    <select class="form-control">
                        <option>BIAYA PENYUSUTAN PERABOT KANTOR UNSUR LOGAM</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Note</label>
                    <textarea class="form-control textarea-control"></textarea>
                </div>

                <!-- QR CODE SECTION (New Barcode Model) -->
                <div class="qr-section">
                    <a id="qr-link" href="/a/001101" target="_blank" style="text-decoration: none; color: inherit;">
                        <div class="qr-placeholder" style="cursor: pointer;" title="Klik untuk membuka detail publik">
                            <img id="qr-image" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ url('/a/001101') }}" alt="QR Code Asset">
                            <div id="qr-label-display" class="qr-label">001101 - 011013</div>
                        </div>
                    </a>
                </div>

                <div class="status-grid">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Start</label>
                            <input type="date" class="form-control" value="2017-08-17">
                        </div>
                        <div class="form-group pl-3">
                            <label>End</label>
                            <input type="date" class="form-control" value="2025-07-17">
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>Life to date</label>
                        <input type="text" class="form-control" value="0,00" style="text-align: right;">
                    </div>

                    <div class="form-group">
                        <label>Year to date</label>
                        <input type="text" class="form-control" value="520.937,40" style="text-align: right;">
                    </div>

                    <div class="form-group">
                        <label>Monthly</label>
                        <input type="text" class="form-control" value="17.364,58" style="text-align: right;">
                    </div>

                    <div class="form-group">
                        <label>Journal Posted</label>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <input type="number" class="form-control" value="0" style="text-align: right; width: 80px;">
                            <span style="font-size:0.8rem; color:#64748b;">Times</span>
                        </div>
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-outline">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CATEGORY MODAL -->
    <div class="modal-overlay" id="categoryModal">
        <div class="custom-modal">
            <div class="modal-header">
                <div class="modal-title">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <rect x="3" y="3" width="7" height="7" fill="#f59e0b" />
                        <rect x="14" y="3" width="7" height="7" fill="#ef4444" />
                        <rect x="3" y="14" width="7" height="7" fill="#3b82f6" />
                    </svg>
                    Assets Category
                </div>
                <button class="modal-close" onclick="closeCategoryModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-tabs">
                    <div class="mod-tab active" data-modtab="detail">DETAIL CATEGORY</div>
                    <div class="mod-tab" data-modtab="list">LIST CATEGORY</div>
                </div>
                
                <div class="mod-content active" id="modtab-detail">
                    <div class="mod-form-group">
                        <label>Code</label>
                        <input type="text" class="form-control" value="002" style="max-width: 150px;">
                    </div>
                    <div class="mod-form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" value="Bangunan Gedung Kantor" style="max-width: 400px;">
                    </div>
                    
                    <div style="height: 20px;"></div>
                    
                    <div class="mod-form-group">
                        <label>Accumulated</label>
                        <select class="form-control" style="max-width: 400px;">
                            <option>AKUM PENYST BANG.GEDUNG KANTOR</option>
                        </select>
                    </div>
                    <div class="mod-form-group">
                        <label>Depreciation</label>
                        <select class="form-control" style="max-width: 400px;">
                            <option>BIAYA PENYUSUTAN GEDUNG</option>
                        </select>
                    </div>
                    
                    <div style="display: flex; justify-content: center; gap: 15px; margin-top: 30px;">
                        <button type="button" class="btn btn-primary" style="width: 100px;">Save</button>
                        <button type="button" class="btn btn-outline" style="width: 100px;" onclick="closeCategoryModal()">Cancel</button>
                    </div>
                </div>
                
                <div class="mod-content" id="modtab-list" style="padding:0;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">CODE</th>
                                    <th>CATEGORY</th>
                                    <th>ACCUMULATED ACCOUNT</th>
                                    <th>EXPENSES ACCOUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>000</td>
                                    <td>None Depreciable ...</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>001</td>
                                    <td>Standard Depreciation ...</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="active">
                                    <td>002</td>
                                    <td>Bangunan Gedung Kantor ...</td>
                                    <td>AKUM PENYST BANG.GEDUNG KANT...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>003</td>
                                    <td>Instalasi Listrik ...</td>
                                    <td>AKUM PENYST INSTALASI LISTRIK ...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>004</td>
                                    <td>Instalasi Telepon ...</td>
                                    <td>AKUM PENYST INSTALASI TELEPON ...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>005</td>
                                    <td>Kendaraan Mobil ...</td>
                                    <td>AKUM PENYST KENDARAAN MOBIL ...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>006</td>
                                    <td>Kendaraan Motor ...</td>
                                    <td>AKUM PENYST KENDARAAN MOTOR ...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>007</td>
                                    <td>Komputer (Hardware) ...</td>
                                    <td>AKUM PENYST KOMPUTER (HARDWA...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                                <tr>
                                    <td>008</td>
                                    <td>Mesin Kantor ...</td>
                                    <td>AKUM PENYST MESIN KANTOR ...</td>
                                    <td>BIAYA PENYUSUTAN...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal logic
        function openCategoryModal() {
            document.getElementById('categoryModal').classList.add('show');
        }
        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.remove('show');
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            // Modal Tabs logic
            const modTabs = document.querySelectorAll('.mod-tab');
            const modContents = document.querySelectorAll('.mod-content');

            modTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    modTabs.forEach(t => t.classList.remove('active'));
                    modContents.forEach(c => c.classList.remove('active'));

                    tab.classList.add('active');
                    const target = document.getElementById('modtab-' + tab.dataset.modtab);
                    if (target) {
                        target.classList.add('active');
                    }
                });
            });
            
            // Close modal when clicking overlay background
            document.getElementById('categoryModal').addEventListener('click', function(e) {
                if(e.target === this) {
                    closeCategoryModal();
                }
            });

            // Modal Table Row Click Logic
            const tableRows = document.querySelectorAll('.data-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('click', function() {
                    // Remove active from all rows
                    tableRows.forEach(r => r.classList.remove('active'));
                    // Add active to clicked row
                    this.classList.add('active');

                    // Extract data from the row
                    const cells = this.querySelectorAll('td');
                    if (cells.length >= 4) {
                        const code = cells[0].textContent.trim();
                        // Remove the trailing '...' if present in description
                        let desc = cells[1].textContent.trim();
                        if(desc.endsWith('...')) desc = desc.slice(0, -3).trim();
                        
                        let acc = cells[2].textContent.trim();
                        let exp = cells[3].textContent.trim();

                        // Update the Detail Form in Modal
                        const modalCodeInput = document.querySelector('#modtab-detail .mod-form-group:nth-child(1) input');
                        const modalDescInput = document.querySelector('#modtab-detail .mod-form-group:nth-child(2) input');
                        const modalAccSelect = document.querySelector('#modtab-detail .mod-form-group:nth-child(4) select');
                        const modalExpSelect = document.querySelector('#modtab-detail .mod-form-group:nth-child(5) select');

                        if(modalCodeInput) modalCodeInput.value = code;
                        if(modalDescInput) modalDescInput.value = desc;
                        
                        // Update Select options text for visual effect (In real app, update by value)
                        if(modalAccSelect && modalAccSelect.options.length > 0) {
                            let textAcc = acc ? acc : 'AKUM PENYST BANG.GEDUNG KANTOR';
                            if(textAcc.endsWith('...')) textAcc = textAcc.slice(0, -3).trim();
                            modalAccSelect.options[0].text = textAcc;
                        }
                        
                        if(modalExpSelect && modalExpSelect.options.length > 0) {
                            let textExp = exp ? exp : 'BIAYA PENYUSUTAN GEDUNG';
                            if(textExp.endsWith('...')) textExp = textExp.slice(0, -3).trim();
                            modalExpSelect.options[0].text = textExp;
                        }
                        
                        // Automatically switch back to Detail Tab
                        document.querySelector('.mod-tab[data-modtab="detail"]').click();
                    }
                });
            });
            
            // Assign value to main form when Save is clicked
            const saveBtn = document.querySelector('#modtab-detail .btn-primary');
            if (saveBtn) {
                saveBtn.addEventListener('click', function() {
                    const selectedDesc = document.querySelector('#modtab-detail .mod-form-group:nth-child(2) input').value;
                    const mainCategoryInput = document.querySelector('input[value="Perabot Kantor Unsur Logam"]'); // The category input
                    
                    if(mainCategoryInput) {
                        mainCategoryInput.value = selectedDesc;
                    }
                    closeCategoryModal();
                });
            }

            // Dynamic QR Code Logic
            const assetCodeInput = document.getElementById('main-asset-code');
            const qrImage = document.getElementById('qr-image');
            const qrLink = document.getElementById('qr-link');
            const qrLabel = document.getElementById('qr-label-display');

            if (assetCodeInput && qrImage && qrLink) {
                assetCodeInput.addEventListener('input', function() {
                    const code = this.value.trim() || 'EMPTY';
                    const baseUrl = window.location.origin;
                    const scanUrl = `${baseUrl}/a/${code}`;
                    
                    // Update QR Image source (using QRServer API)
                    qrImage.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(scanUrl)}`;
                    
                    // Update Link href
                    qrLink.href = scanUrl;
                    
                    // Update Label display
                    if(qrLabel) qrLabel.textContent = `${code} - 011013`;
                });
            }
        });
    </script>
@endsection
