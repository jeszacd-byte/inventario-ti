<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::resource('categories', CategoryController::class)->except('show');
Route::resource('suppliers', SupplierController::class)->except('show');

// La exportación debe ir antes del resource de productos.
Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
Route::resource('products', ProductController::class)->except('show');

Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
