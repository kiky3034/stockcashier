<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
}); 

// Route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Route untuk logout (memerlukan auth)
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('owner')) {
            return redirect()->route('owner.dashboard');
        }

        if ($user->hasRole('cashier')) {
            return redirect()->route('cashier.dashboard');
        }

        if ($user->hasRole('warehouse staff')) {
            return redirect()->route('warehouse.dashboard');
        }

        abort(403);
    })->name('dashboard');
});

// Route untuk kategori (dapat diakses admin dan warehouse staff)
Route::middleware(['auth', 'role:admin|warehouse staff'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
    });

// Route untuk masing-masing role
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::view('/dashboard', 'pages.admin.dashboard')->name('dashboard');
    });

Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::view('/dashboard', 'pages.owner.dashboard')->name('dashboard');
    });

Route::middleware(['auth', 'role:cashier'])
    ->prefix('cashier')
    ->name('cashier.')
    ->group(function () {
        Route::view('/dashboard', 'pages.cashier.dashboard')->name('dashboard');
    });

Route::middleware(['auth', 'role:warehouse staff'])
    ->prefix('warehouse')
    ->name('warehouse.')
    ->group(function () {
        Route::view('/dashboard', 'pages.warehouse.dashboard')->name('dashboard');
    });