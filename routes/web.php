<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/password/change', [PasswordController::class, 'update'])->name('password.update');

    Route::middleware(['forceChangePassword'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Placeholder menus
        Route::get('/inventory', function () {
            return "Inventory Module (Coming Soon)";
        })->name('inventory.index');
        // Finance Module
        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/', function () {
                return "Finance Module (Coming Soon)";
            })->name('index');

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
        Route::get('/accounting', function () {
            return "Accounting Module (Coming Soon)";
        })->name('accounting.index');
        Route::get('/administrator', function () {
            return "Administrator Module (Coming Soon)";
        })->name('administrator.index');
        Route::get('/help', function () {
            return "Help Module (Coming Soon)";
        })->name('help.index');
    });
});
