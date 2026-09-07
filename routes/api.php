<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\KategoriController;
use App\Http\Controllers\Api\V1\TransaksiController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API v1 routes
Route::prefix('v1')->group(function () {
    // Public routes (Authentication)
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // Auth routes
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // Items management
        Route::get('/items', [ItemController::class, 'index']);
        Route::get('/items/{id}', [ItemController::class, 'show']);
        Route::post('/items', [ItemController::class, 'store']);
        Route::put('/items/{id}', [ItemController::class, 'update']);
        Route::delete('/items/{id}', [ItemController::class, 'destroy']);
        Route::get('/items/low-stock', [ItemController::class, 'lowStock']);

        // Categories management
        Route::get('/kategoris', [KategoriController::class, 'index']);
        Route::get('/kategoris/{id}', [KategoriController::class, 'show']);
        Route::post('/kategoris', [KategoriController::class, 'store']);
        Route::put('/kategoris/{id}', [KategoriController::class, 'update']);
        Route::delete('/kategoris/{id}', [KategoriController::class, 'destroy']);

        // Suppliers management
        Route::get('/suppliers', [SupplierController::class, 'index']);
        Route::get('/suppliers/{id}', [SupplierController::class, 'show']);
        Route::post('/suppliers', [SupplierController::class, 'store']);
        Route::put('/suppliers/{id}', [SupplierController::class, 'update']);
        Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy']);

        // Transactions management
        Route::get('/transaksis', [TransaksiController::class, 'index']);
        Route::get('/transaksis/{id}', [TransaksiController::class, 'show']);
        Route::post('/transaksis', [TransaksiController::class, 'store']);
        Route::put('/transaksis/{id}', [TransaksiController::class, 'update']);
        Route::delete('/transaksis/{id}', [TransaksiController::class, 'destroy']);
        Route::get('/transaksis/summary', [TransaksiController::class, 'summary']);
        Route::get('/transaksis/report/daily', [TransaksiController::class, 'dailyReport']);

        // Users management (admin only - you can add authorization later)
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});
