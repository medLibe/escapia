<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
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

Route::get('/', [AuthController::class, 'index']);
Route::get('/home', [AdminController::class, 'index']);
Route::get('/item', [AdminController::class, 'item']);
Route::get('/adjustment', [AdminController::class, 'adjustment']);
Route::get('/user', [AdminController::class, 'user']);
Route::get('/report', [AdminController::class, 'report']);
