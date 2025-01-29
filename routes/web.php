<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// مسار عرض السجلات
Route::get('/logs', [LogController::class, 'showLogs'])->name('logs');

// مسار عرض الإحصائيات
Route::get('/log-counts', [LogController::class, 'showCounts'])->name('log-counts');