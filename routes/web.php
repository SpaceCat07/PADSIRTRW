<?php

use App\Http\Controllers\AuthController;
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

Route::get('/warga', [WargaController::class, 'index']) -> name('warga.index');
Route::get('/warga/masuk', [AuthController::class, 'wargaMasuk']) -> name('warga.masuk');
Route::post('/warga', [AuthController::class, 'wargaLogin']) -> name('warga.login');
Route::get('/warga/register', [WargaController::class, 'create']) -> name('warga.create');
Route::post('/warga/store', [WargaController::class, 'store']) -> name('warga.store');
Route::get('/hasil', function () {
    auth() -> check();
    return view('hasil');
});

Route::get('/login', function () {
    return view('users.login');
});

Route::get('/index', function(){
    return view('index');
});