<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
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
//     Route::get('/', function () {   
//         return view('index');
//     });
// });

// Route::get('/profile', function () {
//     return view('profile');
// });
// Route::get('/pengajuan', function () {
//     return view('pengajuan');
// });
// Route::get('/inquiry', function () {
//     return view('inquiry');
// });
// Route::get('/laporan', function () {
//     return view('laporan');
// });
// // });

// Route::get('login', ['as' => 'login', 'uses' => function () {
//     return view('auth.login');
// }]);

Route::get('/', [AuthController::class, 'showFormLogin'])->name('login');
Route::get('login', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);

    Route::get('home', [PageController::class, 'dashboard'])->name('home');
    Route::get('profile', [PageController::class, 'profile'])->name('profile');
    Route::get('pengajuan', [PageController::class, 'pengajuan'])->name('pengajuan');
    Route::get('inquiry', [PageController::class, 'inquiry'])->name('inquiry');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
