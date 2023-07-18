<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/auth', [AuthController::class, 'auth']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function() {
        return auth()->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(UserController::class)->group(function() {
        Route::get('/user-count', 'index');
        Route::get('/user-all', 'all');
        Route::post('/user-store', 'store');
    });

    Route::controller(ItemController::class)->group(function() {
        // item master
        Route::get('/item-count', 'count');
        Route::get('/item-inflow-count', 'inflow');
        Route::get('/item-outflow-count', 'outflow');
        Route::get('/item-all', 'all');
        Route::get('/item-show/{id}', 'show');
        Route::post('/item-store', 'store');
        Route::post('/item-update/{any}', 'update');
        Route::post('/item-import', 'import');
        Route::post('/item-delete', 'delete');

        // adjustment
        Route::get('/report', 'filter');
        Route::post('/adjustment', 'adjustment');
    });

});
