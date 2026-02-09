<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConnectionConfigurationsController;
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

    Route::prefix('servers')
        ->name('servers.')
        ->group(function () {
            Route::get('/', [ServersController::class, 'index'])->name('index');
            Route::post('/', [ServersController::class, 'store'])->name('store');
            Route::put('/', [ServersController::class, 'update'])->name('update');
            Route::delete('/', [ServersController::class, 'delete'])->name('delete');

            Route::prefix('deleted')
                ->name('deleted.')
                ->group(function () {
                    Route::get('/', [ServersController::class, 'deletedIndex'])->name('index');
                    Route::put('/', [ServersController::class, 'restore'])->name('restore');
                    Route::put('restore-all', [ServersController::class, 'restoreAll'])->name('restore-all');
                    Route::delete('/', [ServersController::class, 'finallyDelete'])->name('finally-delete');
                    Route::delete('all', [ServersController::class, 'finallyDeleteAll'])->name('finally-delete-all');
                });
        });

    Route::prefix('connection-configurations')
        ->name('connection-configurations.')
        ->group(function () {
            Route::get('/', [ConnectionConfigurationsController::class, 'index'])->name('index');
        });
});

require __DIR__.'/settings.php';
