<?php

use App\Http\Controllers\DataAnakController;
use App\Http\Controllers\HasilTrainingController;
use App\Http\Controllers\PreprocessingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/data-anak', [DataAnakController::class, 'getData']);
Route::post('/preprocessings', [PreprocessingController::class, 'store']);
Route::get('/train', [HasilTrainingController::class, 'getData']);
Route::post('/classifications', [HasilTrainingController::class, 'store']);
