<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FixedAssetController;

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
        Route::get('/inventory/fixed-assets', [FixedAssetController::class, 'index'])->name('inventory.fixed_assets');
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
