<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dispatcher\RequestController as DispatcherRequestController;
use App\Http\Controllers\Master\RequestController as MasterRequestController;
use App\Http\Controllers\RepairRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return view('welcome');
    }

    return match (auth()->user()->role) {
        'dispatcher' => redirect()->route('dispatcher.index'),
        'master' => redirect()->route('master.index'),
        default => view('welcome'),
    };
})->name('home');

// Authentication routes
Route::get('/login', [LoginController::class, 'showForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Public repair request routes
Route::get('/requests/create', [RepairRequestController::class, 'create']);
Route::post('/requests', [RepairRequestController::class, 'store']);
Route::get('/requests/success', [RepairRequestController::class, 'success']);

// Dispatcher routes
Route::middleware(['auth', 'role:dispatcher'])->prefix('/dispatcher')->group(function () {
    Route::get('/', [DispatcherRequestController::class, 'index'])->name('dispatcher.index');
    Route::post('/requests/{repairRequest}/assign', [DispatcherRequestController::class, 'assign'])->name('dispatcher.assign');
    Route::post('/requests/{repairRequest}/cancel', [DispatcherRequestController::class, 'cancel'])->name('dispatcher.cancel');
});

// Master routes
Route::middleware(['auth', 'role:master'])->prefix('/master')->group(function () {
    Route::get('/', [MasterRequestController::class, 'index'])->name('master.index');
    Route::post('/requests/{repairRequest}/take', [MasterRequestController::class, 'take'])->name('master.take');
    Route::post('/requests/{repairRequest}/complete', [MasterRequestController::class, 'complete'])->name('master.complete');
});
