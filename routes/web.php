<?php

use App\Http\Controllers\CetakController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
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
Route::get('test/{any}', [CetakController::class, 'cetakAkseptasi']);
Route::get('cek_invoice/{any}', [CetakController::class, 'cekInvoice']);
Route::get('cek_covernote/{any}', [CetakController::class, 'cekCoverNote']);
Route::get('cek_nota_pembayaran/{any}', [CetakController::class, 'cekNotaPembayaran']);

// Notifikasi
Route::get('/notification/{any}', [NotificationController::class, 'index']);

Route::group(['middleware' => 'auth'], function () {
  // Pemberitahuan
  Route::get('/send-notification', [NotificationController::class, 'sendPushNotif']);

  Route::get('home', [PageController::class, 'dashboard'])->name('home');
  Route::get('profile', [PageController::class, 'profile'])->name('profile');
  Route::get('logout', [AuthController::class, 'logout'])->name('logout');

  // laporan
  Route::get('laporan', [PageController::class, 'laporan'])->name('laporan')->middleware();
  Route::post('laporan', [PageController::class, 'laporan'])->name('laporan')->middleware();

  // Cetak
  Route::get('cetak_invoice/{any}', [CetakController::class, 'cetakInvoice'])->name('invoice')->middleware('role:maker|checker|adm|broker|insurance');
  Route::get('cetak_nota_pembayaran/{any}', [CetakController::class, 'cetakNotaPembayaran'])->name('notapembayaran')->middleware('role:adm|broker');
  Route::get('cetak_covernote/{any}', [CetakController::class, 'cetakCoverNote'])->name('covernote')->middleware('role:maker|checker|adm|broker');
  Route::get('cetak_placing/{any}', [CetakController::class, 'cetakPlacing'])->name('placing')->middleware('role:insurance|adm|broker');

  // inquiry
  Route::get('inquiry', [PageController::class, 'inquiry'])->name('inquiry');
  Route::get('inquiry/{any}', [PageController::class, 'inquiry'])->name('inquiry');
  
  // notifikasi
  Route::get('notifikasi', [PageController::class, 'notifikasi'])->name('notifikasi');

  // pembayaran
  Route::get('pembayaran', [PageController::class, 'pembayaran'])->name('pembayaran');
  Route::get('pembayaran/{any}', [PageController::class, 'pembayaran'])->name('pembayaran');

  // pengajuan
  Route::get('pengajuan', [PageController::class, 'pengajuan'])->name('pengajuan')->middleware('role:maker|adm');
  Route::get('pengajuan/{any}', [PageController::class, 'pengajuan'])->name('pengajuan');
  Route::post('pengajuan/{any}', [PageController::class, 'pengajuan'])->name('pengajuan');

  // perpanjangan
  Route::get('perpanjangan', [PageController::class, 'perpanjangan'])->name('perpanjangan')->middleware('role:maker|adm');
  Route::get('perpanjangan/{any}', [PageController::class, 'perpanjangan'])->name('perpanjangan');
  Route::post('perpanjangan/{any}', [PageController::class, 'perpanjangan'])->name('perpanjangan');

  // master
  Route::get('user', [PageController::class, 'user'])->name('user')->middleware('role:adm|broker');
});
