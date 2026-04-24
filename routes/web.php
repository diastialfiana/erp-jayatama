<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'update'])->name('password.update');

    Route::middleware(['forceChangePassword'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('check.menu:Dashboard');

        // Inventory Module
        Route::prefix('inventory')->name('inventory.')->middleware('check.menu:Inventory & GA')->group(function () {
            Route::get('/', function () {
                return "Inventory Module (Coming Soon)";
            })->name('index');

            Route::get('/fixed-assets', [\App\Http\Controllers\FixedAssetController::class, 'index'])->name('fixed_assets');
            Route::get('/product-assets', [\App\Http\Controllers\ProductAssetController::class, 'index'])->name('product_assets');
            Route::get('/product-assets/{id}/details', [\App\Http\Controllers\ProductAssetController::class, 'getDetails'])->name('product_assets.details');
            Route::get('/human-resources', [\App\Http\Controllers\HumanResourceController::class, 'index'])->name('human_resources');
            Route::get('/human-resources/{id}/details', [\App\Http\Controllers\HumanResourceController::class, 'getDetails'])->name('human_resources.details');
            Route::get('/order-requests', [\App\Http\Controllers\OrderRequestController::class, 'index'])->name('order_requests');
            Route::get('/advance-requests', [\App\Http\Controllers\AdvanceRequestController::class, 'index'])->name('advance_requests');
            Route::get('/goods-requests', [\App\Http\Controllers\GoodsRequestController::class, 'index'])->name('goods_requests');
            Route::get('/hr-ebanking', [\App\Http\Controllers\HrEbankingController::class, 'index'])->name('hr_ebanking');
            Route::get('/cost-estimations', [\App\Http\Controllers\CostEstimationController::class, 'index'])->name('cost_estimations');
            Route::get('/quotations', [\App\Http\Controllers\QuotationController::class, 'index'])->name('quotations');
            Route::get('/resource-services', [\App\Http\Controllers\ResourceServiceController::class, 'index'])->name('resource_services');
            Route::get('/lsi-status', [\App\Http\Controllers\LsiStatusController::class, 'index'])->name('lsi_status');
        });

        // Public Asset Scan Route (tidak butuh Inventory access)
        Route::get('/a/{code}', [\App\Http\Controllers\FixedAssetController::class, 'showPublic'])->name('asset.scan');

        // Finance Module
        Route::prefix('finance')->name('finance.')->middleware('check.menu:Finance')->group(function () {
            Route::get('/', [\App\Http\Controllers\Finance\OverviewController::class, 'index'])->name('index');

            // Customers
            Route::controller(\App\Http\Controllers\Finance\CustomerController::class)->group(function() {
                Route::get('customers', 'index')->name('customers.index');
                Route::get('customers/detail', 'detail')->name('customers.detail');
                Route::get('customers/records-list', 'recordsList')->name('customers.records-list');
                Route::get('customers/data', 'data')->name('customers.data');
                
                // ERP Tabs with optional IDs
                Route::get('customers/statistic/{id?}', 'statistic')->name('customers.statistic');
                Route::get('customers/activity/{id?}', 'activity')->name('customers.activity');
                Route::get('customers/summary/{id?}', 'summary')->name('customers.summary');
                Route::get('customers/backdate/{id?}', 'backdate')->name('customers.backdate');

                // For saving and updating customers
                Route::post('customers/store', 'store')->name('customers.store');
                Route::put('customers/{customer}/update', 'update')->name('customers.update');
                Route::delete('customers/{customer}/delete', 'delete')->name('customers.delete');
                
                // Keep the root show route with optional ID last
                Route::get('customers/{id?}', 'show')->name('customers.show');
            });

            Route::get('cash-in/records-list', [\App\Http\Controllers\Finance\CashInController::class, 'recordsList'])->name('cash-in.records-list');
            Route::get('cash-ins/api/accounts', [\App\Http\Controllers\Finance\CashInController::class, 'apiAccounts'])->name('cash-in.api.accounts');
            Route::get('cash-ins/api/departments', [\App\Http\Controllers\Finance\CashInController::class, 'apiDepartments'])->name('cash-in.api.deps');
            Route::get('cash-ins/api/cost-centers', [\App\Http\Controllers\Finance\CashInController::class, 'apiCostCenters'])->name('cash-in.api.costs');

            Route::get('cash-out/records-list', [\App\Http\Controllers\Finance\CashOutController::class, 'recordsList'])->name('cash-out.records-list');

            Route::resource('cash-in', \App\Http\Controllers\Finance\CashInController::class);
            Route::get('cash-transfer/records-list', [\App\Http\Controllers\Finance\CashTransferController::class, 'recordsList'])->name('cash-transfer.records-list');
            Route::get('cash-transfer/api/banks', [\App\Http\Controllers\Finance\CashTransferController::class, 'apiBanks'])->name('cash-transfer.api.banks');
            Route::resource('cash-out', \App\Http\Controllers\Finance\CashOutController::class);
            Route::resource('cash-transfer', \App\Http\Controllers\Finance\CashTransferController::class);

            Route::get('advance-report/api/accounts', [\App\Http\Controllers\Finance\AdvanceReportController::class, 'apiAccounts'])->name('advance-report.api.accounts');
            Route::get('advance-report/records-list', [\App\Http\Controllers\Finance\AdvanceReportController::class, 'recordList'])->name('advance-report.recordList');
            Route::resource('advance-report', \App\Http\Controllers\Finance\AdvanceReportController::class);

            Route::get('advance-report', [\App\Http\Controllers\Finance\AdvanceReportController::class, 'index'])
                ->name('advance-report.index');

            Route::get('ebanking-request/api/invoices', [\App\Http\Controllers\Finance\EBankingController::class, 'apiInvoices'])->name('ebanking-request.api.invoices');
            Route::post('ebanking-request/store', [\App\Http\Controllers\Finance\EBankingController::class, 'store'])->name('ebanking-request.store');
            Route::post('ebanking-request/{id}/approve', [\App\Http\Controllers\Finance\EBankingController::class, 'approve'])->name('ebanking-request.approve');
            Route::get('ebanking-request/records-list', [\App\Http\Controllers\Finance\EBankingController::class, 'recordList'])->name('ebanking-request.recordList');
            Route::get('ebanking-request', [\App\Http\Controllers\Finance\EBankingController::class, 'index'])->name('ebanking-request.index');

            // Supplier Module
            Route::prefix('suppliers')->group(function () {
                // Index redirects to records-list properly
                Route::get('/', [\App\Http\Controllers\Finance\SupplierController::class, 'index'])->name('suppliers.index');
                
                Route::get('/data', [\App\Http\Controllers\Finance\SupplierController::class, 'data'])->name('suppliers.data');
                Route::get('/records-list', [\App\Http\Controllers\Finance\SupplierController::class, 'recordsList'])->name('suppliers.records-list');
                
                // Form actions
                Route::post('/store', [\App\Http\Controllers\Finance\SupplierController::class, 'store'])->name('suppliers.store');
                Route::put('/{supplier}/update', [\App\Http\Controllers\Finance\SupplierController::class, 'update'])->name('suppliers.update');
                
                // ERP Tabs with optional IDs
                Route::get('/statistics/{id?}', [\App\Http\Controllers\Finance\SupplierController::class, 'statistic'])->name('suppliers.statistic');
                Route::get('/activity/{id?}', [\App\Http\Controllers\Finance\SupplierController::class, 'activity'])->name('suppliers.activity');
                Route::get('/summary/{id?}', [\App\Http\Controllers\Finance\SupplierController::class, 'summary'])->name('suppliers.summary');
                Route::get('/backdate/{id?}', [\App\Http\Controllers\Finance\SupplierController::class, 'backdate'])->name('suppliers.backdate');
                
                // Detail Views
                Route::get('/detail', [\App\Http\Controllers\Finance\SupplierController::class, 'detail'])->name('suppliers.detail'); // New record form
                Route::get('/{id?}', [\App\Http\Controllers\Finance\SupplierController::class, 'show'])->name('suppliers.show');
            });

            // Bank Account Module
            Route::prefix('bank-accounts')->group(function () {
                Route::get('/',              [\App\Http\Controllers\Finance\BankAccountController::class, 'recordsList'])->name('bank-accounts.index');
                Route::get('/record-detail', [\App\Http\Controllers\Finance\BankAccountController::class, 'recordDetail'])->name('bank-accounts.record-detail');
                Route::get('/records-list',  [\App\Http\Controllers\Finance\BankAccountController::class, 'recordsList'])->name('bank-accounts.records-list');
                Route::get('/statistics',    [\App\Http\Controllers\Finance\BankAccountController::class, 'statistics'])->name('bank-accounts.statistics');
                Route::get('/activity',      [\App\Http\Controllers\Finance\BankAccountController::class, 'activity'])->name('bank-accounts.activity');
                Route::get('/backdate',      [\App\Http\Controllers\Finance\BankAccountController::class, 'backdate'])->name('bank-accounts.backdate');
                Route::get('/summary',       [\App\Http\Controllers\Finance\BankAccountController::class, 'bankSummary'])->name('bank-accounts.summary');
                // Store / Update / Delete
                Route::post('/store',                  [\App\Http\Controllers\Finance\BankAccountController::class, 'store'])->name('bank-accounts.store');
                Route::put('/{bankAccount}/update',    [\App\Http\Controllers\Finance\BankAccountController::class, 'update'])->name('bank-accounts.update');
                // Specific record by ID (keep LAST to avoid matching named sub-paths)
                Route::get('/{id}',          [\App\Http\Controllers\Finance\BankAccountController::class, 'show'])->name('bank-accounts.show');
            });

            // Sales Module
            Route::get('sales-invoice/records-list', [\App\Http\Controllers\Finance\SalesInvoiceController::class, 'recordsList'])->name('sales-invoice.records-list');
            Route::get('sales-invoice/custom-group', [\App\Http\Controllers\Finance\SalesInvoiceController::class, 'customGroup'])->name('sales-invoice.custom-group');
            Route::get('sales-invoice/detail-list', [\App\Http\Controllers\Finance\SalesInvoiceController::class, 'detailList'])->name('sales-invoice.detail-list');
            Route::get('sales-invoice/api/customers', [\App\Http\Controllers\Finance\SalesInvoiceController::class, 'apiCustomers'])->name('sales-invoice.api.customers');
            Route::get('sales-invoice/api/products', [\App\Http\Controllers\Finance\SalesInvoiceController::class, 'apiProducts'])->name('sales-invoice.api.products');
            Route::get('cash-receipt/api/customers', [\App\Http\Controllers\Finance\CashReceiptController::class, 'apiCustomers'])->name('cash-receipt.api.customers');
            Route::get('cash-receipt/api/banks', [\App\Http\Controllers\Finance\CashReceiptController::class, 'apiBanks'])->name('cash-receipt.api.banks');
            Route::get('cash-receipt/api/invoices', [\App\Http\Controllers\Finance\CashReceiptController::class, 'apiInvoices'])->name('cash-receipt.api.invoices');
            Route::get('cash-receipt/api/accounts', [\App\Http\Controllers\Finance\CashReceiptController::class, 'apiAccounts'])->name('cash-receipt.api.accounts');
            Route::get('cash-receipt/api/departments', [\App\Http\Controllers\Finance\CashReceiptController::class, 'apiDeps'])->name('cash-receipt.api.deps');
            Route::get('cash-receipt/api/costs', [\App\Http\Controllers\Finance\CashReceiptController::class, 'apiCosts'])->name('cash-receipt.api.costs');
            Route::get('cash-receipt/records-list', [\App\Http\Controllers\Finance\CashReceiptController::class, 'recordsList'])->name('cash-receipt.records-list');

            Route::resource('sales-invoice', \App\Http\Controllers\Finance\SalesInvoiceController::class);
            Route::resource('cash-receipt', \App\Http\Controllers\Finance\CashReceiptController::class);
        });
        Route::prefix('accounting')->name('accounting.')->middleware('check.menu:Accounting')->group(function () {
            Route::get('/', function () { return "Accounting Module (Coming Soon)"; })->name('index');
            Route::get('/journal-posting', function() { return "Journal Posting (Coming Soon)"; })->name('journal-posting');
            Route::get('/fx-ass-posting', function() { return "Fx. Ass. Posting (Coming Soon)"; })->name('fx-ass-posting');
            Route::get('/closing-month', function() { return "Closing Month (Coming Soon)"; })->name('closing-month');
            Route::get('/closing-year', function() { return "Closing Year (Coming Soon)"; })->name('closing-year');
            Route::get('/change-period', function() { return "Change Period (Coming Soon)"; })->name('change-period');
            Route::get('/coa', function() { return redirect()->route('accounting.account-list.detail', 1); })->name('coa');
            Route::get('/account-list', [\App\Http\Controllers\Accounting\AccountListController::class, 'index'])->name('account-list.index');
            Route::get('/account-list/{id}', [\App\Http\Controllers\Accounting\AccountListController::class, 'show'])->name('account-list.detail');
            Route::get('/account-list/{id}/statistics', [\App\Http\Controllers\Accounting\AccountListController::class, 'statistics'])->name('account-list.statistics');
            Route::get('/account-list/{id}/activity', [\App\Http\Controllers\Accounting\AccountListController::class, 'activity'])->name('account-list.activity');
            Route::get('/account-list/{id}/backdate', [\App\Http\Controllers\Accounting\AccountListController::class, 'backdate'])->name('account-list.backdate');
            Route::get('/account-list/{id}/summary', [\App\Http\Controllers\Accounting\AccountListController::class, 'summary'])->name('account-list.summary');
            Route::get('/account-budget/{id?}', [\App\Http\Controllers\Accounting\AccountBudgetController::class, 'detailView'])->name('account-budget');
            Route::get('/account-budget/{id}/statistics', [\App\Http\Controllers\Accounting\AccountBudgetController::class, 'statistics'])->name('account-budget.statistics');
            Route::post('/account-budget/{id}', [\App\Http\Controllers\Accounting\AccountBudgetController::class, 'store'])->name('account-budget.store');
            Route::prefix('dept-account')->name('dept-account.')->group(function () {
                Route::get('/list', [\App\Http\Controllers\Accounting\DepartmentAccountController::class, 'list'])->name('list');
                Route::post('/store', [\App\Http\Controllers\Accounting\DepartmentAccountController::class, 'store'])->name('store');
                Route::put('/{id}/update', [\App\Http\Controllers\Accounting\DepartmentAccountController::class, 'update'])->name('update');
                Route::get('/detail/{id?}', [\App\Http\Controllers\Accounting\DepartmentAccountController::class, 'detail'])->name('detail');
            });
            Route::prefix('cost-center')->name('cost-center.')->group(function () {
                Route::get('/list', [\App\Http\Controllers\Accounting\CostCenterController::class, 'list'])->name('list');
                Route::get('/statistic', [\App\Http\Controllers\Accounting\CostCenterController::class, 'statistic'])->name('statistic');
                Route::post('/store', [\App\Http\Controllers\Accounting\CostCenterController::class, 'store'])->name('store');
                Route::put('/{id}/update', [\App\Http\Controllers\Accounting\CostCenterController::class, 'update'])->name('update');
                Route::get('/detail-cost/{id?}', [\App\Http\Controllers\Accounting\CostCenterController::class, 'detail'])->name('detail');
            });
            Route::get('/ar-invoice', [\App\Http\Controllers\AccountingController::class, 'arInvoice'])->name('ar-invoice');
            Route::get('/ar-return', [\App\Http\Controllers\AccountingController::class, 'arReturn'])->name('ar-return');
            Route::get('/ap-invoice', [\App\Http\Controllers\AccountingController::class, 'apInvoice'])->name('ap-invoice');
            Route::get('/ap-return', [\App\Http\Controllers\AccountingController::class, 'apReturn'])->name('ap-return');
            Route::get('/journal', function() { return "Journal Entry (Coming Soon)"; })->name('journal');
            Route::get('/post-journal', function() { return "Post Journal (Coming Soon)"; })->name('post-journal');
            Route::get('/unpost-journal', function() { return "Unpost Journal (Coming Soon)"; })->name('unpost-journal');
            Route::get('/journal-check', function() { return "Journal Check (Coming Soon)"; })->name('journal-check');
            Route::get('/financial-report', function() { return "Financial Report (Coming Soon)"; })->name('financial-report');
            Route::get('/ledger', function() { return "General Ledger (Coming Soon)"; })->name('ledger');
            Route::get('/trial-balance', function() { return "Trial Balance (Coming Soon)"; })->name('trial-balance');
        });
        // Administrator Module
        Route::prefix('administrator')->name('administrator.')->middleware('check.menu:Administrator')->group(function () {
            Route::get('/', [\App\Http\Controllers\AdministratorController::class, 'index'])->name('index');
            Route::get('/menu-visibility', [\App\Http\Controllers\AdministratorController::class, 'menuVisibility'])->name('menu-visibility');
            Route::post('/menu-visibility/update', [\App\Http\Controllers\AdministratorController::class, 'updateMenuVisibility'])->name('menu-visibility.update');

            // User Management
            Route::put('/users/{user}/reset-password', [\App\Http\Controllers\Administrator\UserController::class, 'resetPassword'])->name('users.reset-password');
            Route::resource('users', \App\Http\Controllers\Administrator\UserController::class)->except(['create', 'show', 'edit']);
        });
        
        // Help Module
        Route::prefix('help')->name('help.')->middleware('check.menu:Panduan Penggunaan')->group(function () {
            Route::get('/', function () { return "Help Module (Coming Soon)"; })->name('index');
        });
    });
});

Route::fallback(function () {
    return redirect('/login');
});
