<?php

use App\Http\Controllers\AuthController;

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\IuranRTController;
use App\Http\Controllers\IuranRWController;
use App\Http\Controllers\KeuanganRTController;
use App\Http\Controllers\KeuanganRWController;
use App\Http\Controllers\KritikSaranController;
use App\Http\Controllers\KritikSaranRTController;
use App\Http\Controllers\KritikSaranRWCOntroller;
use App\Http\Controllers\ManajemenDetailIuranRTPengguna;
use App\Http\Controllers\ManajemenDetailIuranRWRT;
use App\Http\Controllers\PembayaranIuranRTController;
use App\Http\Controllers\PembayaranIuranWargaController;
use App\Http\Controllers\PenjabatRTController;
use App\Http\Controllers\PenjabatRWController;
use App\Http\Controllers\ProkerController;
use App\Http\Controllers\RTController;
use App\Http\Controllers\RWController;
use App\Http\Controllers\SetPasswordController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WargaController;
use Illuminate\Http\Request;
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
    // dd(Auth::user());
    return view('welcome');
});

Route::get('/masuk', function () {
    return view('users.login');
})->name('masuk');

Route::get('/forgot-password', function () {
    return view('users.forgotPassword');
})->name('forgot-password');

Route::get('/reset-password/{id}', function () {
    return view('users.setPassword');
})->name('reset-password');

Route::get('/reset-password/{id}', function () {
    return view('users.setPassword');
})->name('reset-password');

Route::get('/requestacc', function () {
    return view('users.requestAddUser');
})->name('account.requestCreate');

// Admin Dashboard Route
Route::get('/dashboard/admin', function () {
    return view('admin.DashboardAdmin');
})->name('admin-dashboard');

// Warga Dashboard Route
Route::get('/dashboard/warga', function () {
    return view('warga.DashboardWarga');
})->name('dashboard.warga');

Route::get('/data-warga/admin', function () {
    return view('/admin/DataWarga');
})->name('data-warga');

Route::get('/tambah-data-warga/admin', function () {
    return view('/admin/TambahDataWarga');
})->name('tambah-data-warga');

Route::get('/edit-data-warga/admin/{id}', function ($id) {
    return view('admin/EditDataWarga', ['wargaId' => $id]);
})->name('edit-data-warga');

Route::get('/aktivasi-akun/admin', function () {
    return view('/admin/AktivasiAkun');
})->name('aktivasi-akun');

Route::get('/manajemen-iuran/admin', function () {
    return view('/admin/ManajemenIuran');
})->name('manajemen-iuran');

Route::get('/tambah-iuran/admin', function () {
    return view('/admin/TambahIuran');
})->name('tambah-iuran');

Route::get('/edit-iuran/admin/{id}', function ($id) {
    return view('admin/EditIuran', ['iuranId' => $id]);
})->name('edit-iuran');

Route::get('/program-kerja/admin', function () {
    return view('/admin/ProgramKerjaAdmin');
})->name('admin-program-kerja');

Route::get('edit-program-kerja/admin/{id}', function ($id) {
    return view('/admin/EditProgramKerja', ['id' => $id]);
})->name('edit-program-kerja');

Route::get('/tambah-program-kerja/admin', function () {
    return view('/admin/TambahProgramKerja');
})->name('tambah-program-kerja');

Route::get('/laporan-pengeluaran/admin', function () {
    return view('/admin/LaporanPengeluaran');
})->name('laporan-pengeluaran');

Route::get('/laporan-pemasukan/admin', function () {
    return view('/admin/LaporanPemasukan');
})->name('laporan-pemasukan');

Route::get('/tambah-data-pemasukan/admin', function () {
    return view('/admin/TambahDataPemasukan');
})->name('tambah-data-pemasukan');

Route::get('/tambah-data-pengeluaran/admin', function () {
    return view('/admin/TambahDataPengeluaran');
})->name('tambah-data-pengeluaran');

Route::get('/kritik-saran/admin', function () {
    return view('/admin/KritikSaranAdmin');
})->name('admin-kritik-saran');

Route::get('/program-kerja', function () {
    return view('/warga/LihatProgramKerja');
})->name('program-kerja');

Route::get('/laporan-keuangan/warga', function () {
    return view('/warga/LaporanKeuangan');
})->name('laporan-keuangan');

Route::get('/dompet-warga/warga', function () {
    return view('/dompet/DompetWarga');
})->name('dompet-warga');

Route::get('/riwayat-pembayaran/warga', function () {
    return view('/warga/RiwayatPembayaran');
})->name('riwayat-pembayaran');

Route::get('/bayar/warga', function () {
    return view('/warga/Pembayaran');
})->name('pembayaran');

Route::get('/checkout/warga', function () {
    return view('/warga/Checkout');
})->name('checkout');

Route::get('/checkout-confirmation/warga', function () {
    return view('/warga/CheckoutConfirmation');
})->name('checkout-confirmation');

Route::get('/kritik-saran/warga', function () {
    return view('/warga/FormKritikSaran');
})->name('kritik-saran');

Route::get('/riwayat-kritik-saran/warga', function () {
    return view('/warga/KritikSaranWarga');
})->name('riwayat-kritik-saran');

Route::get('/edit-profil/warga', function () {
    return view('/warga/editInfo');
})->name('edit.profil');