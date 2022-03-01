<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MpesaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mpesa/password',[MpesaController::class,'lipaNaMpesaPassword']);

Route::post('/mpesa/token',[MpesaController::class,'generateAccessToken']);

Route::post('mpesa/stk/push',[MpesaController::class,'stkPush']);







