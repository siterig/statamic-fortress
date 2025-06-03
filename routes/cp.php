<?php

use Illuminate\Support\Facades\Route;
use Siterig\Fortress\Http\Controllers\DashboardController;
use Siterig\Fortress\Http\Controllers\LogsController;
use Siterig\Fortress\Http\Controllers\SettingsController;

Route::middleware(['web', 'statamic.cp.authenticated'])->group(function () {
    Route::prefix('fortress')->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('fortress.dashboard');
        
        // Logs
        Route::get('/logs', [LogsController::class, 'index'])->name('fortress.logs');
        
        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('fortress.settings');
        Route::put('/settings', [SettingsController::class, 'update'])->name('fortress.settings.update');
        Route::get('/settings/update-geoip', [SettingsController::class, 'updateGeoIP'])->name('fortress.settings.update-geoip');
        
        // API Routes
        Route::prefix('api')->group(function () {
            Route::get('/stats', [DashboardController::class, 'stats'])->name('fortress.stats');
            Route::post('/scan', [DashboardController::class, 'scan'])->name('fortress.scan');
        });
    });
}); 
