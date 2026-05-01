<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\StockAdjustmentController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cashier\PosController;
use App\Http\Controllers\Cashier\SaleController;
use App\Http\Controllers\Cashier\SaleRefundController;
use App\Http\Controllers\Owner\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Dashboard Redirect (Fallback)
|--------------------------------------------------------------------------
| Route ini hanya akan dipanggil jika:
| 1. Ada yang memanggil route('dashboard') secara manual
| 2. Redirect dari middleware atau controller yang tidak spesifik
| 
| Untuk redirect setelah login, sudah ditangani di LoginController
| sehingga user tidak akan pernah melewati route ini setelah login.
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // Prioritas: admin > owner > warehouse staff > cashier
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    
    if ($user->hasRole('owner')) {
        return redirect()->route('owner.dashboard');
    }
    
    if ($user->hasRole('warehouse staff')) {
        return redirect()->route('warehouse.dashboard');
    }
    
    if ($user->hasRole('cashier')) {
        return redirect()->route('cashier.dashboard');
    }

    abort(403);
})->middleware(['auth', 'no-cache'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'no-cache', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('users', UserController::class)
            ->except(['show']);

        Route::get('activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity-logs.index');

        Route::get('settings', [AppSettingController::class, 'edit'])
            ->name('settings.edit');

        Route::put('settings', [AppSettingController::class, 'update'])
            ->name('settings.update');

        Route::get('backups', [BackupController::class, 'index'])
            ->name('backups.index');

        Route::get('backups/database', [BackupController::class, 'database'])
            ->name('backups.database');
    });

/*
|--------------------------------------------------------------------------
| Admin + Warehouse Staff Routes (Inventory & Master Data)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'no-cache', 'role:admin|warehouse staff'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        Route::resource('units', UnitController::class)
            ->except(['show']);

        Route::resource('suppliers', SupplierController::class)
            ->except(['show']);

        Route::resource('warehouses', WarehouseController::class)
            ->except(['show']);

        Route::resource('products', ProductController::class)
            ->except(['show']);

        Route::get('stocks', [StockController::class, 'index'])
            ->name('stocks.index');

        Route::get('stock-movements', [StockMovementController::class, 'index'])
            ->name('stock-movements.index');

        Route::get('stock-adjustments/create', [StockAdjustmentController::class, 'create'])
            ->name('stock-adjustments.create');

        Route::post('stock-adjustments', [StockAdjustmentController::class, 'store'])
            ->name('stock-adjustments.store');

        Route::get('purchases', [PurchaseController::class, 'index'])
            ->name('purchases.index');

        Route::get('purchases/create', [PurchaseController::class, 'create'])
            ->name('purchases.create');

        Route::post('purchases', [PurchaseController::class, 'store'])
            ->name('purchases.store');

        Route::get('purchases/{purchase}', [PurchaseController::class, 'show'])
            ->name('purchases.show');
    });

/*
|--------------------------------------------------------------------------
| Cashier Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'no-cache', 'role:cashier'])
    ->prefix('cashier')
    ->name('cashier.')
    ->group(function () {
        Route::view('/dashboard', 'pages.cashier.dashboard')
            ->name('dashboard');

        Route::get('/pos', [PosController::class, 'index'])
            ->name('pos.index');

        Route::post('/pos', [PosController::class, 'store'])
            ->name('pos.store');

        Route::get('/sales', [SaleController::class, 'index'])
            ->name('sales.index');

        Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])
            ->name('sales.receipt');

        Route::patch('/sales/{sale}/void', [SaleController::class, 'void'])
            ->name('sales.void');

        Route::get('/sales/{sale}/refund', [SaleRefundController::class, 'create'])
            ->name('sales.refunds.create');

        Route::post('/sales/{sale}/refund', [SaleRefundController::class, 'store'])
            ->name('sales.refunds.store');

        Route::get('/refunds/{refund}', [SaleRefundController::class, 'show'])
            ->name('refunds.show');

        /*
         * Keep this route last because /sales/{sale} is dynamic.
         */
        Route::get('/sales/{sale}', [SaleController::class, 'show'])
            ->name('sales.show');
    });

/*
|--------------------------------------------------------------------------
| Owner Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'no-cache', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/reports/sales', [ReportController::class, 'sales'])
            ->name('reports.sales');

        Route::get('/reports/sales/export', [ReportController::class, 'exportSales'])
            ->name('reports.sales.export');

        Route::get('/reports/profit', [ReportController::class, 'profit'])
            ->name('reports.profit');

        Route::get('/reports/profit/export', [ReportController::class, 'exportProfit'])
            ->name('reports.profit.export');

        Route::get('/reports/stock', [ReportController::class, 'stock'])
            ->name('reports.stock');

        Route::get('/reports/stock/export', [ReportController::class, 'exportStock'])
            ->name('reports.stock.export');

        Route::get('/reports/purchases', [ReportController::class, 'purchases'])
            ->name('reports.purchases');

        Route::get('/reports/purchases/export', [ReportController::class, 'exportPurchases'])
            ->name('reports.purchases.export');
    });

/*
|--------------------------------------------------------------------------
| Warehouse Staff Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'no-cache', 'role:warehouse staff'])
    ->prefix('warehouse')
    ->name('warehouse.')
    ->group(function () {
        Route::view('/dashboard', 'pages.warehouse.dashboard')
            ->name('dashboard');
    });