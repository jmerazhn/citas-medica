<?php

use App\Http\Controllers\SuperAdmin\AuthController;
use App\Http\Controllers\SuperAdmin\TenantController;
use Illuminate\Support\Facades\Route;

// Login del super-admin (sin middleware auth)
Route::middleware('web')->withoutMiddleware('auth:super-admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/', function () {
    return view('super-admin.dashboard');
})->name('dashboard');

// Gestión de consultorios (tenants)
Route::resource('consultorios', TenantController::class);
