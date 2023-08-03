<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\bidsController;
use App\Http\Controllers\RacesController;
use App\Http\Controllers\GiftCoinController;

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
Route::post('register', [userController::class,'register']);
Route::post('login', [userController::class,'login']);
Route::post('/bids', [bidsController::class, 'store']);
Route::post('/races', [RacesController::class, 'store']);
Route::post('/available_coins', [GiftCoinController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function(){
    // Route::post('/bids', [bidsController::class, 'store']);
});