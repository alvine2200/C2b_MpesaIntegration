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

Route::get('mpesa_password',[MpesaController::class,'lipaNaMpesaPassword']);

Route::get('mpesa_token',[MpesaController::class,'generateAccessToken']);

Route::post('mpesa_stk_push',[MpesaController::class,'stkPush']);

Route::post('mpesa_callback_url',[MpesaController::class,'mpesaResponse']);




