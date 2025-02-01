<?php

use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;


Route::get('/test', function () {
    return "LightLogLite Test";
});

// مسار عرض السجلات
Route::get('/logs', [LogController::class, 'showLogs'])->name('logs');

// مسار عرض الإحصائيات
Route::get('/log-counts', [LogController::class, 'showCounts'])->name('log-counts');
