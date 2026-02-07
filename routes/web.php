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
});

require __DIR__.'/settings.php';
