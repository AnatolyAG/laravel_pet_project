<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->group(function () {
    // User CRUD
    Route::resource('users', UserController::class);

    // Role CRUD
    Route::resource('roles', RoleController::class);

    // Transaction CRUD
    Route::resource('transactions', TransactionController::class);
// });



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
