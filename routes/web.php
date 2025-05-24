<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RequestKaryawanController;
use App\Http\Controllers\RequestDriverController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function() {
    return redirect()->route('request-karyawan.create');
});
Route::get('/request-karyawan/create', [RequestKaryawanController::class, 'create'])->name('request-karyawan.create');
Route::get('/request-driver/create', [RequestDriverController::class, 'create'])->name('request-driver.create');
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'authLogin'])->name('auth.login');
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'authRegister'])->name('auth.register');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // request karyawan
    Route::resource('request-karyawan', RequestKaryawanController::class)->except(['create']);
    Route::post('/request-karyawan/{id}/acc/{role_id}', [RequestKaryawanController::class, 'accRequest'])->name('request-karyawan.acc');

    // request driver
    Route::resource('request-driver', RequestDriverController::class)->except(['create']);
    Route::post('/request-driver/{id}/acc/{role_id}', [RequestDriverController::class, 'accRequest'])->name('request-driver.acc');

    // notification
    Route::resource('notifications', NotificationController::class);
    Route::get('/notification/{id}/show', [NotificationController::class, 'showAndRead'])->name('notification.showAndRead');
});