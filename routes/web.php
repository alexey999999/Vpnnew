<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConnectionConfigurationsController;
use App\Http\Controllers\ServersController;
use App\Http\Controllers\TariffsController;
use App\Http\Controllers\UsersController;
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
                    Route::get('/', [ServersController::class, 'deleted'])->name('index');
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
            Route::post('/', [ConnectionConfigurationsController::class, 'store'])->name('store');
            Route::put('/', [ConnectionConfigurationsController::class, 'update'])->name('update');
            Route::delete('/', [ConnectionConfigurationsController::class, 'delete'])->name('delete');

            Route::prefix('deleted')
                ->name('deleted.')
                ->group(function () {
                    Route::get('/', [ConnectionConfigurationsController::class, 'deleted'])->name('index');
                    Route::put('/', [ConnectionConfigurationsController::class, 'restore'])->name('restore');
                    Route::put('restore-all', [ConnectionConfigurationsController::class, 'restoreAll'])->name('restore-all');
                    Route::delete('/', [ConnectionConfigurationsController::class, 'finallyDelete'])->name('finally-delete');
                    Route::delete('all', [ConnectionConfigurationsController::class, 'finallyDeleteAll'])->name('finally-delete-all');
                });
        });

    Route::prefix('tariffs')
        ->name('tariffs.')
        ->group(function () {
            Route::get('/', [TariffsController::class, 'index'])->name('index');
            Route::post('/', [TariffsController::class, 'store'])->name('store');
            Route::put('/', [TariffsController::class, 'update'])->name('update');
            Route::delete('/', [TariffsController::class, 'delete'])->name('delete');

            Route::prefix('deleted')
                ->name('deleted.')
                ->group(function () {
                    Route::get('/', [TariffsController::class, 'deleted'])->name('index');
                    Route::put('/', [TariffsController::class, 'restore'])->name('restore');
                    Route::put('restore-all', [TariffsController::class, 'restoreAll'])->name('restore-all');
                    Route::delete('/', [TariffsController::class, 'finallyDelete'])->name('finally-delete');
                    Route::delete('all', [TariffsController::class, 'finallyDeleteAll'])->name('finally-delete-all');
                });
        });

    Route::prefix('users')
        ->name('users.')
        ->group(function () {
            Route::get('/', [UsersController::class, 'index'])->name('index');
        });
});

require __DIR__.'/settings.php';
