<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\api\DataController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Auth
    Route::get('/logout', [AuthController::class, 'logout']);

    // data select
    Route::get('/selectkodepos', [DataController::class, 'selectKodepos']);
    Route::get('/selectinsured', [DataController::class, 'selectInsured']);
    Route::get('/selectokupasi', [DataController::class, 'selectOkupasi']);

    // data table
    Route::get('/datatransaksi', [DataController::class, 'dataTransaksi']);
});

Route::post('/login', [AuthController::class, 'login']);
