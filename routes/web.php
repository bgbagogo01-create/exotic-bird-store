<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

// 1. Guest & Auth Routes (Only accessible when NOT logged in)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout Route (Accessible when logged in)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 2. Public / Pembeli / Customer Product Catalog (Accessible by everyone/Pembeli)
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/product/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// 3. Protected Routes (Must be logged in)
Route::middleware(['auth'])->group(function () {
    
    // User Profile Actions (Admin, Kasir, Pembeli)
    Route::get('/profile', [SettingController::class, 'profile'])->name('profile');
    Route::post('/profile', [SettingController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [SettingController::class, 'changePassword'])->name('profile.password');

    // 4. Role-based Access Group: Admin & Kasir (Manage inventory & POS)
    Route::middleware(['role:admin,kasir'])->group(function () {
        
        // Unified Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Product Categories (Kasir is read-only implicitly or explicitly, Admin full CRUD)
        Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
        
        // Products (Admin full CRUD, Kasir read-only)
        Route::resource('products', ProductController::class)->except(['show', 'create', 'edit']);
        
        // Stock Adjustment & Log
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::post('/stock/in', [StockController::class, 'storeIn'])->name('stock.in');
        Route::post('/stock/out', [StockController::class, 'storeOut'])->name('stock.out');
        
        // POS Sales Transaction
        Route::get('/pos', [TransactionController::class, 'pos'])->name('transactions.pos');
        Route::post('/pos/checkout', [TransactionController::class, 'storeTransaction'])->name('transactions.store');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{id}/receipt', [TransactionController::class, 'printReceipt'])->name('transactions.receipt');
        
        // View Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    });

    // 5. Admin Only Actions (Restricted Access)
    Route::middleware(['role:admin'])->group(function () {
        
        // Excel Reporting Exports
        Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
        
        // User Account Management
        Route::get('/users', [SettingController::class, 'usersIndex'])->name('users.index');
        Route::post('/users', [SettingController::class, 'userStore'])->name('users.store');
        Route::delete('/users/{id}', [SettingController::class, 'userDestroy'])->name('users.destroy');
        
        // System Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/reset', [SettingController::class, 'resetSystem'])->name('settings.reset');
        Route::get('/settings/backup', [SettingController::class, 'backupDb'])->name('settings.backup');
    });
});
