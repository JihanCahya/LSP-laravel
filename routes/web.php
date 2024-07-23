<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('auth/login', [AuthController::class, 'login_action'])->name('login.action');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('auth/register', [AuthController::class, 'register_action'])->name('register.action');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

    Route::controller(KategoriController::class)->prefix('kategori')->group(function () {
        Route::get('', 'index')->name('kategori');
        Route::get('get_data', 'get_data');
        Route::post('get_data_id', 'get_data_id');
        Route::post('insert_data', 'insert_data');
        Route::post('edit_data', 'edit_data');
        Route::post('delete_data', 'delete_data');
    });

    Route::controller(SuratController::class)->prefix('surat')->group(function () {
        Route::get('', 'index')->name('surat');
        Route::get('get_data', 'get_data');
        Route::post('get_data_id', 'get_data_id');
        Route::post('insert_data', 'insert_data');
        Route::post('edit_data', 'edit_data');
        Route::post('delete_data', 'delete_data');
        Route::get('download/{file_name}', 'downloadFile');
    });
});
