<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);

    // Warehouses
    Route::resource('warehouses', WarehouseController::class);

    // Categories (Simple CRUD without show)
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Suppliers (Full CRUD with show)
    Route::resource('suppliers', SupplierController::class);

    // Stock Movements
    Route::get('/stock-movements/report', [StockMovementController::class, 'report'])->name('stock-movements.report');
    Route::resource('stock-movements', StockMovementController::class)->except(['edit', 'update']);

    // Transfers
    Route::get('/transfers/history', [TransferController::class, 'history'])->name('transfers.history');
    Route::get('/transfers/{referenceId}', [TransferController::class, 'show'])->name('transfers.show');
    Route::get('/transfers', [TransferController::class, 'create'])->name('transfers.create');
    Route::post('/transfers', [TransferController::class, 'store'])->name('transfers.store');
});

require __DIR__.'/auth.php';