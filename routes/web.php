<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\ProductAssetController;
use App\Http\Controllers\HumanResourceController;
use App\Http\Controllers\AdvanceRequestController;
use App\Http\Controllers\GoodsRequestController;
use App\Http\Controllers\HrEbankingController;
use App\Http\Controllers\CostEstimationController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ResourceServiceController;
use App\Http\Controllers\LsiStatusController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/password/change', [PasswordController::class, 'update'])->name('password.update');

    Route::middleware(['forceChangePassword'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Placeholder menus
        Route::get('/inventory', function () {
            return "Inventory Module (Coming Soon)";
        })->name('inventory.index');
        Route::get('/inventory/fixed-assets', [FixedAssetController::class, 'index'])->name('inventory.fixed_assets');
        Route::get('/inventory/product-assets', [ProductAssetController::class, 'index'])->name('inventory.product_assets');
        Route::get('/inventory/product-assets/{id}/details', [ProductAssetController::class, 'getDetails'])->name('inventory.product_assets.details');
        Route::get('/inventory/human-resources', [HumanResourceController::class, 'index'])->name('inventory.human_resources');
        Route::get('/inventory/human-resources/{id}/details', [HumanResourceController::class, 'getDetails'])->name('inventory.human_resources.details');
        Route::get('/inventory/order-requests', [\App\Http\Controllers\OrderRequestController::class, 'index'])->name('inventory.order_requests');
        Route::get('/inventory/advance-requests', [AdvanceRequestController::class, 'index'])->name('inventory.advance_requests');
        Route::get('/inventory/goods-requests', [GoodsRequestController::class, 'index'])->name('inventory.goods_requests');
        Route::get('/inventory/hr-ebanking', [HrEbankingController::class, 'index'])->name('inventory.hr_ebanking');
        Route::get('/inventory/cost-estimations', [CostEstimationController::class, 'index'])->name('inventory.cost_estimations');
        Route::get('/inventory/quotations', [QuotationController::class, 'index'])->name('inventory.quotations');
        Route::get('/inventory/resource-services', [ResourceServiceController::class, 'index'])->name('inventory.resource_services');
        Route::get('/inventory/lsi-status', [LsiStatusController::class, 'index'])->name('inventory.lsi_status');
        Route::get('/a/{code}', [FixedAssetController::class, 'showPublic'])->name('asset.scan');

        // Finance Module
        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/', function () {
                return "Finance Module (Coming Soon)"; })->name('index');

            // Customers
            Route::prefix('customers')->name('customers.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Finance\CustomerController::class, 'index'])->name('index');
                Route::get('/detail', [\App\Http\Controllers\Finance\CustomerController::class, 'detail'])->name('detail.create');
                Route::get('/{customer}/detail', [\App\Http\Controllers\Finance\CustomerController::class, 'show'])->name('detail.show');
                Route::post('/store', [\App\Http\Controllers\Finance\CustomerController::class, 'store'])->name('store');
                Route::put('/{customer}/update', [\App\Http\Controllers\Finance\CustomerController::class, 'update'])->name('update');

                // Placeholder routes for other tabs
                Route::get('/{customer}/list', [\App\Http\Controllers\Finance\CustomerController::class, 'list'])->name('list');
                Route::get('/{customer}/statistic', [\App\Http\Controllers\Finance\CustomerController::class, 'statistic'])->name('statistic');
                Route::get('/{customer}/activity', [\App\Http\Controllers\Finance\CustomerController::class, 'activity'])->name('activity');
                Route::get('/{customer}/backdate', [\App\Http\Controllers\Finance\CustomerController::class, 'backdate'])->name('backdate');
                Route::get('/{customer}/summary', [\App\Http\Controllers\Finance\CustomerController::class, 'summary'])->name('summary');
            });
        });
        Route::prefix('accounting')->name('accounting.')->group(function () {
            Route::get('/', [\App\Http\Controllers\AccountingController::class, 'index'])->name('index');
            Route::get('/ar-invoice', [\App\Http\Controllers\AccountingController::class, 'arInvoice'])->name('ar_invoice');
            Route::get('/ar-return', [\App\Http\Controllers\AccountingController::class, 'arReturn'])->name('ar_return');
            Route::get('/ap-invoice', [\App\Http\Controllers\AccountingController::class, 'apInvoice'])->name('ap_invoice');
            Route::get('/ap-return', [\App\Http\Controllers\AccountingController::class, 'apReturn'])->name('ap_return');
        });
        Route::get('/administrator', function () {
            return "Administrator Module (Coming Soon)";
        })->name('administrator.index');
        Route::get('/help', function () {
            return "Help Module (Coming Soon)";
        })->name('help.index');
    });
});
