<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RequestKaryawanController;
use App\Http\Controllers\RequestDriverController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\EkspedisiController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WhatsAppController;

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
Route::post('/request-karyawan/store', [RequestKaryawanController::class, 'store'])->name('request-karyawan.store');
Route::get('/request-driver/create', [RequestDriverController::class, 'create'])->name('request-driver.create');
Route::post('/request-driver/store', [RequestDriverController::class, 'store'])->name('request-driver.store');
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'authLogin'])->name('auth.login');
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'authRegister'])->name('auth.register');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/result', [SearchController::class, 'search'])->name('search');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/status/{status}', [DashboardController::class, 'getStatusData'])->name('dashboard.status');
    Route::get('/dashboard/weekly/{month}', [DashboardController::class, 'getWeeklyData'])->name('dashboard.weekly');
    Route::get('/dashboard/latest-requests', [DashboardController::class, 'getLatestRequests'])->name('dashboard.latest-requests');
    
    // request karyawan - hanya bisa diakses admin, security, lead, hr-ga
    Route::middleware(['role:admin,security,lead,hr-ga'])->group(function () {
        Route::resource('request-karyawan', RequestKaryawanController::class)->only(['index']);
        Route::get('/request-karyawan/latest-requests', [RequestKaryawanController::class, 'getLatestRequests'])->name('request-karyawan.latest-requests');
        Route::put('/request-karyawan/{id}', [RequestKaryawanController::class, 'update'])->name('request-karyawan.update');
        Route::post('/request-karyawan/{id}/acc/{role_id}', [RequestKaryawanController::class, 'accRequest'])->name('request-karyawan.acc');
        Route::post('/request-karyawan/{id}/update-status', [RequestKaryawanController::class, 'updateStatus'])->name('request-karyawan.update-status');
        
        // Export routes for Request Karyawan
        Route::get('/request-karyawan/export/preview/{month}/{year}/{type?}', [RequestKaryawanController::class, 'exportPDF'])->name('request-karyawan.export.preview');
        Route::get('/request-karyawan/export/pdf/{month}/{year}/{type?}', [RequestKaryawanController::class, 'exportPDF'])->name('request-karyawan.export.pdf');
        Route::get('/request-karyawan/export/excel/{month}/{year}/{type?}', [RequestKaryawanController::class, 'exportExcel'])->name('request-karyawan.export.excel');
        Route::get('/request-karyawan/export/single-pdf/{id}', [RequestKaryawanController::class, 'exportSinglePDF'])->name('request-karyawan.exportSinglePDF');
    });

    // request driver - hanya bisa diakses admin, security, checker, head-unit  
    Route::middleware(['role:admin,security,checker,head-unit'])->group(function () {
        Route::resource('request-driver', RequestDriverController::class)->only(['index']);
        Route::get('/request-driver/latest-requests', [RequestDriverController::class, 'getLatestRequests'])->name('request-driver.latest-requests');
        Route::put('/request-driver/{id}', [RequestDriverController::class, 'update'])->name('request-driver.update');
        Route::post('/request-driver/{id}/acc/{role_id}', [RequestDriverController::class, 'accRequest'])->name('request-driver.acc');
        Route::post('/request-driver/{id}/update-status', [RequestDriverController::class, 'updateStatus'])->name('request-driver.update-status');
        
        // Export routes for Request Driver
        Route::get('/request-driver/export/preview/{month}/{year}/{type?}', [RequestDriverController::class, 'exportPDF'])->name('request-driver.export.preview');
        Route::get('/request-driver/export/pdf/{month}/{year}/{type?}', [RequestDriverController::class, 'exportPDF'])->name('request-driver.export.pdf');
        Route::get('/request-driver/export/excel/{month}/{year}/{type?}', [RequestDriverController::class, 'exportExcel'])->name('request-driver.export.excel');
        Route::get('/request-driver/export/single-pdf/{id}', [RequestDriverController::class, 'exportSinglePDF'])->name('request-driver.exportSinglePDF');
    });
    
    // bisa diakses semua user yang sudah login
    Route::get('/users/{id}/profile', [UserController::class, 'profile'])->name('users.profile');
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

    // Route untuk Ekspedisi
    Route::get('/ekspedisi', [EkspedisiController::class, 'index'])->name('ekspedisi.index');
    Route::post('/ekspedisi/store', [EkspedisiController::class, 'store'])->name('ekspedisi.store');
    Route::put('/ekspedisi/update/{id}', [EkspedisiController::class, 'update'])->name('ekspedisi.update');
    Route::delete('/ekspedisi/delete/{id}', [EkspedisiController::class, 'destroy'])->name('ekspedisi.destroy');

    Route::get('/export/dashboard/preview/{month}/{year}/{type?}', [ExportController::class, 'previewPDF'])->name('export.dashboard.preview');
    Route::get('/export/dashboard/pdf/{month}/{year}/{type?}', [ExportController::class, 'exportPDF'])->name('export.dashboard.pdf');
    Route::get('/export/dashboard/excel/{month}/{year}/{type?}', [ExportController::class, 'exportExcel'])->name('export.dashboard.excel');

    // WhatsApp routes
    Route::get('/admin/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp.index');
    Route::get('/admin/whatsapp/status', [WhatsAppController::class, 'getStatus'])->name('whatsapp.status');
    Route::get('/admin/whatsapp/qr', [WhatsAppController::class, 'getQR'])->name('whatsapp.qr');
});