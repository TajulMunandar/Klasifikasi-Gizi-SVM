<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAnakController;
use App\Http\Controllers\HasilTrainingController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.pages.index');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('/dashboard')->group(function () {
    Route::resource('/', DashboardController::class);
    Route::resource('/user', UserController::class);
    Route::post('/users/{id}/update-password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
    Route::resource('/klasifikasi', KlasifikasiController::class);
    Route::resource('/data-anak', DataAnakController::class);
    Route::post('/data-anak/import', [DataAnakController::class, 'import'])->name('data-aktual.import');
    Route::resource('/preprocessing', PreprocessingController::class);
    Route::resource('/hasil-training', HasilTrainingController::class);
});
