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
Route::get('/masuk', [AuthController::class, 'masuk']) -> name('masuk');
Route::post('/login', [AuthController::class, 'login']) -> name('login');

// route register warga user by super admin
Route::get('/account/create', [UsersController::class, 'create']) -> name('account.create');
Route::post('/account/register', [UsersController::class, 'store']) -> name('account.store');

// route register akun pengguna warga by super admin
Route::get('/warga/create', [WargaController::class, 'create']) -> name('warga.create');
Route::post('/warga/register', [WargaController::class, 'store']) -> name('warga.store');

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
    Route::get('/edit/{id}', [UsersController::class, 'edit']) -> name('edit');
    Route::post('/update', [UsersController::class, 'update']) -> name('update');
});

Route::get('/index', function(){
    return view('index');
});

Route::get('/warga/dashboard', function(){
    return view('/warga/DashboardWarga');
});