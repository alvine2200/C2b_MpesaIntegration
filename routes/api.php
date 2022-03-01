<?php

use App\Http\Controllers\MpesaController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/mpesa/password',[MpesaController::class,'lipaNaMpesaPassword']);

Route::get('/mpesa/token',[MpesaController::class,'generateAccessToken']);

Route::get('mpesa/stk/push',[MpesaController::class,'stkPush']);

Route::post('/mpesa/push/callback/url',[MpesaController::class,'mpesaResponse']);




