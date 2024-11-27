<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PenjabatRTController;
use App\Http\Controllers\PenjabatRWController;
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

Route::get('/masuk', [AuthController::class, 'masuk'])->name('masuk');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// route register warga user by super admin
Route::resource('/account', UsersController::class)->names([
    'index' => 'account.index',
    'create' => 'account.create',
    'store' => 'account.store',
    'edit' => 'account.edit',
    'update' => 'account.update',
    'show' => 'account.show', ## ini untuk show profile saja?
    'destroy' => 'account.destroy'
]);

Route::get('/requestacc', [UsersController::class, 'requestCreate'])->name('account.requestCreate');
Route::post('/request', [UsersController::class, 'requestStore'])->name('account.requestStore');
// Route::get('/account/create', [UsersController::class, 'create']) -> name('account.create');
// Route::post('/account/register', [UsersController::class, 'store']) -> name('account.store');

// route register akun pengguna warga by super admin
// Route::get('/warga/create', [WargaController::class, 'create']) -> name('warga.create');
// Route::post('/warga/register', [WargaController::class, 'store']) -> name('warga.store');
// Route::get('/warga', [WargaController::class, 'index']) -> name('warga.index');
// Route::get('/warga/edit/{id}', [WargaController::class, 'edit']) -> name('warga.edit');
// Route::post('/warga', [WargaController::class, 'update']) -> name('warga.update');
// Route::delete('/warga/{id}', [WargaController::class, 'destroy']) -> name('warga.destroy');

Route::resource('/warga', WargaController::class)->names([
    'index' => 'warga.index',
    'create' => 'warga.create',
    'store' => 'warga.store',
    'edit' => 'warga.edit',
    'update' => 'warga.update',
    'destroy' => 'warga.destroy'
]);


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

Route::middleware('auth')->group(function () {
    Route::get('/hasil', function () {
        return view('hasil');
    });

    // route edit dan update by himself
    Route::get('/edit/{id}', [UsersController::class, 'edit'])->name('edit');
    Route::post('/update', [UsersController::class, 'update'])->name('update');
});

Route::get('/index', function () {
    return view('index');
});

// Route::get('/dashboard/warga', function () {
//     return view('/warga/DashboardWarga');
// })->name('dashboard.warga');

Route::get('/dashboard/superadmin', function () {
    return view('/warga/DashboardWarga');
})->name('dashboard.superadmin');

Route::get('/dashboard/adminrw', function () {
    return view('/rw/DashboardAdminRW');
})->name('dashboard.adminrw');

Route::get('/dashboard/adminrt', function () {
    return view('/rt/DashboardAdminRT');
})->name('dashboard.adminrt');

Route::get('/dashboard/ketuarw', function () {
    return view('/rw/DashboardKetuaRW');
})->name('dashboard.ketuarw');

Route::get('/dashboard/ketuart', function () {
    return view('/rt/DashboardKetuaRT');
})->name('dashboard.ketuart');

// Define a function to get the dashboard data
function getDashboardData(Request $request) {
    $year = $request->input('year', date('Y')); // Default to current year
    $interval = $request->input('interval', '7-days'); // Default interval

    // Replace these example values with actual data retrieval based on $year and $interval
    if ($interval === '7-days') {
        $labels = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'];
        $pemasukanData = [4500, 5200, 4800, 6000, 5800, 6200, 5000]; // Example values
        $pengeluaranData = [4000, 4900, 4600, 5800, 5400, 5900, 4500]; // Example values
    } elseif ($interval === 'month') {
        $labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        $pemasukanData = [15000, 18000, 17000, 20000]; // Example values
        $pengeluaranData = [14000, 17000, 16000, 19000]; // Example values
    } elseif ($interval === 'yoy') {
        $labels = ['2020', '2021', '2022', '2023'];
        $pemasukanData = [120000, 140000, 160000, 180000]; // Example values
        $pengeluaranData = [100000, 120000, 140000, 160000]; // Example values
    }

    // Calculate total Pemasukan and Pengeluaran for the Pie Chart
    $totalPemasukan = array_sum($pemasukanData);
    $totalPengeluaran = array_sum($pengeluaranData);

    // Pie Chart Data
    $piechart_data = [
        'labels' => ['Pemasukan', 'Pengeluaran'],
        'data' => [$totalPemasukan, $totalPengeluaran],
        'backgroundColor' => ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'], // Matching colors
    ];

    // Bar Chart Data
    $barchart_data = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Pemasukan',
                'data' => $pemasukanData,
                'backgroundColor' => 'rgba(75, 192, 192, 0.6)', // Color for Pemasukan
            ],
            [
                'label' => 'Pengeluaran',
                'data' => $pengeluaranData,
                'backgroundColor' => 'rgba(255, 99, 132, 0.6)', // Color for Pengeluaran
            ]
        ]
    ];

    // Line Chart Data
    $linechart_data = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Pemasukan',
                'data' => $pemasukanData,
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'fill' => true,
            ],
            [
                'label' => 'Pengeluaran',
                'data' => $pengeluaranData,
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'fill' => true,
            ]
        ]
    ];

    return compact('barchart_data', 'linechart_data', 'piechart_data', 'totalPemasukan', 'totalPengeluaran', 'year', 'interval');
}


// Admin Dashboard Route
Route::get('/dashboard/admin', function (Request $request) {
    $data = getDashboardData($request);
    return view('admin.DashboardAdmin', $data);
})->name('admin-dashboard');

// Warga Dashboard Route
Route::get('/dashboard/warga', function (Request $request) {
    $data = getDashboardData($request);
    return view('warga.DashboardWarga', $data);
})->name('dashboard.warga');


// Route::get('/dashboard/admin', [ChartController::class, 'barChart']);

// Route::get('/dashboard/admin', [ChartController::class, 'barChart'])->name('admin-dashboard');

Route::get('/data-warga/admin', function () {
    return view('/admin/DataWarga');
})->name('data-warga');

Route::get('/manajemen-iuran/admin', function () {
    return view('/admin/ManajemenIuran');
})->name('manajemen-iuran');

Route::get('/program-kerja/admin', function () {
    return view('/admin/ProgramKerjaAdmin');
})->name('admin-program-kerja');

Route::get('/edit-program-kerja/admin', function () {
    return view('/admin/EditProgramKerja');
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

Route::get('/riwayat-pembayaran/warga', function () {
    return view('/warga/RiwayatPembayaran');
})->name('riwayat-pembayaran');

Route::get('/bayar/warga', function () {
    return view('/warga/Pembayaran');
})->name('pembayaran');

Route::get('/kritik-saran/warga', function () {
    return view('/warga/FormKritikSaran');
})->name('kritik-saran');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout'); // Update with your controller and method


// chartttt
Route::get('/admin/DashboardAdmin', [ChartController::class, 'barChart']);
