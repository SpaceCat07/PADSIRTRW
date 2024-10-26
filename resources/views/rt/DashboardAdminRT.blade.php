@extends('layouts.dashboardNavbar')

@section('content')
<?php
$page = 'dashboard'; // or 'program-kerja', 'pembayaran', etc.
?>

<h2>Dashboard Admin RT</h2>

<div class="dashboard-rt-container">
    <div class="data-warga">
        <h3>Data Warga</h3>
        <div class="warga-photo"></div>
        <button>selengkapnya</button>
    </div>

    <div class="pembayaran-konfirmasi">
        <h3>Pembayaran butuh konfirmasi</h3>
        <div class="payment-item">
            <span>No. Rekening 1871×87××</span>
            <span>Rp 35.000</span>
        </div>
        <div class="payment-item">
            <span>No. Rekening 1871×87××</span>
            <span>Rp 15.000</span>
        </div>
        <button>selengkapnya</button>
    </div>

    <div class="program-kerja">
        <h3>Program Kerja Mendatang</h3>
        <div class="program-box"></div>
    </div>

    <div class="kritik-saran">
        <h3>Pesan Kritik & Saran</h3>
        <div class="message-box"></div>
        <div class="message-box"></div>
    </div>

    <div class="pemasukan">
        <h3>Pemasukan</h3>
        <p>...</p>
    </div>

    <div class="pengeluaran">
        <h3>Pengeluaran</h3>
        <p>...</p>
    </div>

    <div class="perbandingan">
        <h3>Perbandingan Pemasukan dan Pengeluaran</h3>
        <div class="comparison-chart">
            <div class="legend">
                <div class="legend-item"><span class="grey-box"></span>Pemasukan</div>
                <div class="legend-item"><span class="dark-box"></span>Pengeluaran</div>
            </div>
            <div class="pie-chart">20%</div>
        </div>
    </div>

    <div class="grafik-laporan">
        <h3>Grafik Laporan Pengeluaran</h3>
        <div class="chart-box"></div>
        <button>Cetak</button>
    </div>
</div>
@endsection