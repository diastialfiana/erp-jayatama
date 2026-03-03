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
            return "Inventory Module (Coming Soon)"; })->name('inventory.index');
        Route::get('/finance', function () {
            return "Finance Module (Coming Soon)"; })->name('finance.index');
        Route::get('/accounting', function () {
            return "Accounting Module (Coming Soon)"; })->name('accounting.index');
        Route::get('/administrator', function () {
            return "Administrator Module (Coming Soon)"; })->name('administrator.index');
        Route::get('/help', function () {
            return "Help Module (Coming Soon)"; })->name('help.index');
    });
});
