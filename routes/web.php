<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RequestKaryawanController;
use App\Http\Controllers\RequestDriverController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartemenController;
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
    
    // request karyawan - hanya bisa diakses admin, security, lead, hr-ga
    Route::middleware(['role:admin,security,lead,hr-ga'])->group(function () {
        Route::resource('request-karyawan', RequestKaryawanController::class)->except(['create']);
        Route::post('/request-karyawan/{id}/acc/{role_id}', [RequestKaryawanController::class, 'accRequest'])->name('request-karyawan.acc');
    });

    // request driver - hanya bisa diakses admin, security, checker, head-unit  
    Route::middleware(['role:admin,security,checker,head-unit'])->group(function () {
        Route::resource('request-driver', RequestDriverController::class)->except(['create']);
        Route::post('/request-driver/{id}/acc/{role_id}', [RequestDriverController::class, 'accRequest'])->name('request-driver.acc');
    });

    // notification - bisa diakses semua user yang sudah login
    Route::resource('notifications', NotificationController::class);
    Route::get('/notification/{id}/show', [NotificationController::class, 'showAndRead'])->name('notification.showAndRead');

    // role - hanya bisa diakses admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('role', RoleController::class);
        Route::resource('departemen', DepartemenController::class)->except(['update', 'destroy']);
        Route::put('/departemen/update/{id}', [DepartemenController::class, 'update'])->name('departemen.update');
        Route::delete('/departemen/delete/{id}', [DepartemenController::class, 'destroy'])->name('departemen.destroy');
        
        // Users management
        Route::resource('users', UserController::class)->except(['update', 'destroy']);
        Route::put('/users/update-basic-info/{id}', [UserController::class, 'updateBasicInfo'])->name('users.update-basic-info');
        Route::put('/users/update-email/{id}', [UserController::class, 'updateEmail'])->name('users.update-email');
        Route::put('/users/reset-password/{id}', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::put('/users/update-photo/{id}', [UserController::class, 'updatePhoto'])->name('users.update-photo');
        Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::post('/users/{id}/reset-password-default', [UserController::class, 'resetPasswordToDefault'])->name('users.reset-password-default');
    });
});