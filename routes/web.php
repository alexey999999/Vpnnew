<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.store');

Route::get('/', function () {
    return to_route('dashboard');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('servers', [ServersController::class, 'index'])->name('servers.index');
    Route::post('servers', [ServersController::class, 'store'])->name('servers.store');
    Route::put('servers', [ServersController::class, 'update'])->name('servers.update');
    Route::delete('servers', [ServersController::class, 'delete'])->name('servers.delete');
    
    Route::get('servers/deleted', [ServersController::class, 'deletedIndex'])->name('servers.deleted.index');
    Route::put('servers/deleted', [ServersController::class, 'restore'])->name('servers.deleted.restore');
    Route::put('servers/deleted/restore-all', [ServersController::class, 'restoreAll'])->name('servers.deleted.restoreAll');
    Route::delete('servers/deleted', [ServersController::class, 'finallyDelete'])->name('servers.deleted.finallyDelete');
    Route::delete('servers/deleted/all', [ServersController::class, 'finallyDeleteAll'])->name('servers.deleted.finallyDeleteAll');
});

require __DIR__.'/settings.php';
