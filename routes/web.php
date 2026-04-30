<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
}); // ✅ Tutup closure route '/'

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