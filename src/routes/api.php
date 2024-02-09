<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "api" middleware group. Make something great!
 * |
 */


Route::middleware('auth:sanctum')->group(function () {
    // User CRUD
    // Route::resource('users', UserController::class);
    Route::get('users', [UserController::class,         'index']);
    Route::get('users/{id}', [UserController::class,    'show']);
    Route::post('users', [UserController::class,        'store']);
    Route::put('users/{id}', [UserController::class,    'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    // Role CRUD
    // Route::resource('roles', RoleController::class);
    Route::get('roles', [RoleController::class,         'index']);
    Route::get('roles/{id}', [RoleController::class,    'show']);
    Route::post('roles', [RoleController::class,        'store']);
    Route::put('roles/{id}', [RoleController::class,    'update']);
    Route::delete('roles/{id}', [RoleController::class, 'destroy']);

    // Transaction CRUD
    // Route::resource('transactions', TransactionController::class);
    Route::get('transactions', [TransactionController::class,         'index']);
    Route::get('transactions/{id}', [TransactionController::class,    'show']);
    Route::post('transactions', [TransactionController::class,        'store']);
    Route::put('transactions/{id}', [TransactionController::class,    'update']);
    Route::delete('transactions/{id}', [TransactionController::class, 'destroy']);
});

Route::post('login', [LoginController::class, 'login']);
