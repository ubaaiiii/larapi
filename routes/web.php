<?php

use Illuminate\Support\Facades\Route;

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

// Route::group(['middleware' => 'auth:sanctum'], function () {
Route::get('/', function () {
    return view('index');
});
Route::get('/profile', function () {
    return view('profile');
});
Route::get('/pengajuan', function () {
    return view('pengajuan');
});
Route::get('/inquiry', function () {
    return view('inquiry');
});
Route::get('/laporan', function () {
    return view('laporan');
});
// });

Route::get('login', ['as' => 'login', 'uses' => function () {
    return view('welcome');
}]);
