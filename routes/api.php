<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\api\DataController;
use App\Http\Controllers\api\LaporanController;
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
    Route::get('/logout', 'API\AuthController@logout');

    // data select
    Route::get('/selectkodepos', 'API\DataController@selectKodepos');
    Route::get('/selectinsured', 'API\DataController@selectInsured');
    Route::get('/selectokupasi', 'API\DataController@selectOkupasi');
    Route::get('/selectinstype', 'API\DataController@selectInstype');
    Route::get('/selectasuransi', 'API\DataController@selectAsuransi');
    Route::get('/datadashboard', 'API\DataController@dataDashboard');

    // data table
    Route::post('/datatransaksi', 'API\DataController@dataTransaksi');
    Route::post('/datadokumen', 'API\DataController@dataDokumen');
    Route::post('/datadokumen/{any}', 'API\DataController@dataDokumen');
    Route::post('/dataaktifitas', 'API\DataController@dataAktifitas');
    Route::post('/datauser', 'API\DataController@dataUser');
    Route::post('/datalaporan', 'API\LaporanController@tableLaporan');
    
    // cari data
    Route::get('/caritransaksi', 'API\DataController@cariTransaksi');
    
    // data notif
    Route::get('/notifikasi', 'API\DataController@dataNotifikasi');
    
    // proses data
    Route::post('/user', 'API\ProcessController@user');
    Route::post('/dokumen', 'API\ProcessController@dokumen');
    Route::post('/dokumen/{any}', 'API\ProcessController@dokumen');
    Route::post('/pengajuan', 'API\ProcessController@pengajuan');
    Route::post('/pembayaran', 'API\ProcessController@pembayaran');
    Route::post('/polis', 'API\ProcessController@polis');

});

Route::post('/login', 'API\AuthController@login');
