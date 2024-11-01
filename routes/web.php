<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenjabatRTController;
use App\Http\Controllers\PenjabatRWController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WargaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/warga', [WargaController::class, 'index']) -> name('warga.index');
// Route::get('/warga/masuk', [AuthController::class, 'wargaMasuk']) -> name('warga.masuk');
// Route::post('/warga', [AuthController::class, 'wargaLogin']) -> name('warga.login');
// Route::get('/warga/register', [WargaController::class, 'create']) -> name('warga.create');
// Route::post('/warga/store', [WargaController::class, 'store']) -> name('warga.store');

// route login user
// Route::resource('/otentikasi', AuthController::class) -> names([
//     'masuk' => 'masuk',
//     'login' => 'login',
//     'logout' => 'logout'
// ]);

Route::get('/masuk', [AuthController::class, 'masuk']) -> name('masuk');
Route::post('/login', [AuthController::class, 'login']) -> name('login');
Route::post('/logout', [AuthController::class, 'logout']) -> name('logout');
Route::get('/requestacc', [UsersController::class, 'requestCreate']) -> name('account.requestCreate');
Route::post('/request', [UsersController::class, 'requestStore']) -> name('account.requestStore');

// route register warga user by adminRt
Route::middleware('role:Admin_RT') -> group(function () {
    Route::resource('/account', UsersController::class) -> names([
        'index' => 'account.index',
        'create' => 'account.create',
        'store' => 'account.store',
        'edit' => 'account.edit',
        'update' => 'account.update',
        'show' => 'account.show', ## ini untuk show profile saja?
        'destroy' => 'account.destroy'
    ]);

    Route::resource('/warga', WargaController::class) -> names([
        'index' => 'warga.index',
        'create' => 'warga.create',
        'store' => 'warga.store',
        'edit' => 'warga.edit',
        'update' => 'warga.update',
        'destroy' => 'warga.destroy'
    ]);

    Route::get('/dashboard/adminrt', function(){
        return view('/rt/DashboardAdminRT');
    })->name('dashboard.adminrt');
});

// middleware untuk role warga
Route::middleware('role:Warga') -> group(function () {
    Route::get('/dashboard/warga', function(){
        return view('/warga/DashboardWarga');
    })->name('dashboard.warga');
});

// middleware untuk role admin rw
Route::middleware('role:Admin_RW') -> group(function () {
    Route::get('/dashboard/adminrw', function(){
        return view('/rw/DashboardAdminRW');
    })->name('dashboard.adminrw');
});

// middleware untuk role ketua rt
Route::middleware('role:Ketua_RT') -> group(function () {
    Route::get('/dashboard/ketuart', function(){
        return view('/rt/DashboardKetuaRT');
    })->name('dashboard.ketuart');
});

// middleware untuk role ketua rw
Route::middleware('role:Ketua_RW') -> group(function () {
    Route::get('/dashboard/ketuarw', function(){
        return view('/rw/DashboardKetuaRW');
    })->name('dashboard.ketuarw');
});

// middleware untuk role super admin
Route::middleware('role:Super_Admin') -> group(function () {
    Route::get('/dashboard/superadmin', function(){
        return view('/warga/DashboardWarga');
    })->name('dashboard.superadmin');
});

// Route::get('/account/create', [UsersController::class, 'create']) -> name('account.create');
// Route::post('/account/register', [UsersController::class, 'store']) -> name('account.store');

// route register akun pengguna warga by super admin
// Route::get('/warga/create', [WargaController::class, 'create']) -> name('warga.create');
// Route::post('/warga/register', [WargaController::class, 'store']) -> name('warga.store');
// Route::get('/warga', [WargaController::class, 'index']) -> name('warga.index');
// Route::get('/warga/edit/{id}', [WargaController::class, 'edit']) -> name('warga.edit');
// Route::post('/warga', [WargaController::class, 'update']) -> name('warga.update');
// Route::delete('/warga/{id}', [WargaController::class, 'destroy']) -> name('warga.destroy');

// Route::resource('/warga', WargaController::class) -> names([
//     'index' => 'warga.index',
//     'create' => 'warga.create',
//     'store' => 'warga.store',
//     'edit' => 'warga.edit',
//     'update' => 'warga.update',
//     'destroy' => 'warga.destroy'
// ]);


// // route untuk login register penjabat rt
// Route::get('/rt/masuk', [AuthController::class, 'penjabatRTMasuk']) -> name('rt.masuk');
// Route::post('/rt', [AuthController::class, 'penjabatRTLogin']) -> name('rt.login');
// Route::get('/rt/register', [PenjabatRTController::class, 'create']) -> name('rt.create');
// Route::post('/rt/store', [PenjabatRTController::class, 'store']) -> name('rt.store');

// // route untuk login register penjabat rw
// Route::get('/rw/masuk', [AuthController::class, 'penjabatRTMasuk']) -> name('rw.masuk');
// Route::post('/rw', [AuthController::class, 'penjabatRTLogin']) -> name('rw.login');
// Route::get('/rw/register', [PenjabatRWController::class, 'create']) -> name('rw.create');
// Route::post('/rw/store', [PenjabatRWController::class, 'store']) -> name('rw.store');

Route::middleware('auth') -> group(function () {
    Route::get('/hasil', function () {
        return view('hasil');
    });

    // route edit dan update by himself
    // Route::get('/edit/{id}', [UsersController::class, 'edit']) -> name('edit');
    // Route::post('/update', [UsersController::class, 'update']) -> name('update');
});

Route::get('/index', function(){
    return view('index');
});

Route::get('/program-kerja', function () {
    return view('/warga/LihatProgramKerja');
})->name('program-kerja');

Route::get('/laporan-keuangan/warga', function () {
    return view('/warga/LaporanKeuangan');
})->name('laporan-keuangan');
