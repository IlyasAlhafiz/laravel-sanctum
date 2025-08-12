<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    Route::resource('/posts', \App\Http\Controllers\Api\PostController::class)
    ->except(['create', 'edit']);

    Route::resource('/kategoris', \App\Http\Controllers\Api\KategoriController::class)
    ->except(['create', 'edit']);

    Route::resource('/bukus', \App\Http\Controllers\Api\BukuController::class)
    ->except(['create', 'edit']);

    Route::resource('/peminjamen', \App\Http\Controllers\Api\PeminjamanController::class)
    ->except(['create', 'edit']);

    Route::resource('/pengembalians', \App\Http\Controllers\Api\PengembalianController::class)
    ->except(['create', 'edit']);
});