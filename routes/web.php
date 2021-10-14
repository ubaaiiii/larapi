<?php

use App\Http\Controllers\API\CetakController;
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
Route::get('mode/{value}', [AuthController::class, 'DarkMode']);

Route::post('register', [AuthController::class, 'register']);

// Cek Invoice
Route::get('testt', [CetakController::class, 'redirectCek']);
Route::get('testtt/{any}', [CetakController::class, 'cekInvoice'])->name('testtt');

Route::group(['middleware' => 'auth'], function () {

  Route::get('home', [PageController::class, 'dashboard'])->name('home');
  Route::get('profile', [PageController::class, 'profile'])->name('profile');
  Route::get('logout', [AuthController::class, 'logout'])->name('logout');

  // laporan
  Route::get('laporan', [PageController::class, 'laporan'])->name('laporan')->middleware();
  Route::post('laporan', [PageController::class, 'laporan'])->name('laporan')->middleware();

  // Cetak
  Route::get('test', [CetakController::class, 'cetakInvoice'])->name('cetak')->middleware();

  // inquiry
  Route::get('inquiry', [PageController::class, 'inquiry'])->name('inquiry');
  Route::get('inquiry/{any}', [PageController::class, 'inquiry'])->name('inquiry');

  // pengajuan
  Route::get('pengajuan', [PageController::class, 'pengajuan'])->name('pengajuan')->middleware('role:ao|checker|adm');
  Route::get('pengajuan/{any}', [PageController::class, 'pengajuan'])->name('pengajuan');
  Route::post('pengajuan/{any}', [PageController::class, 'pengajuan'])->name('pengajuan');

  // master
  Route::get('user', [PageController::class, 'user'])->name('user')->middleware('role:adm|broker');
});
