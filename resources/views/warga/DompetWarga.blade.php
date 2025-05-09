@extends('layouts.checkoutNavbar')

<title>SIMAS - dompetku</title>
<link rel="stylesheet" href="{{ asset('css/dompet-warga.css') }}">
    <?php
    $page = 'dashboard'; // or 'program-kerja', 'pembayaran', etc.
    ?>

@section('content')

<div class="dompet-container">
    <div class="saldo-container">
        <h2>DOMPET SIMAS</h2>
        <div class="saldo-section">
            <p>Saldo Dompet Anda</p>
            <div class="saldo-amount">Rp 100.000</div>
        </div>
        <div class="saldo-buttons">
            <button class="btn-tambah">tambah saldo</button>
            <button class="btn-riwayat">riwayat transaksi</button>
        </div>
    </div>

    <div class="riwayat-section">
        <h3>Riwayat Transaksi Dompet</h3>
        <div class="riwayat-list">
            <div class="transaksi-item">
                <div class="transaksi-detail">
                    <img src="{{ asset('storage/paymentIcon.png') }}" alt="">
                    <div class="transaksi-subdetail">
                        <span class="transaksi-title">Bayar Iuran</span>
                    <span class="transaksi-subtitle">Maret</span>
                    </div>
                </div>
                <div class="transaksi-subdetail">
                    <span class="transaksi-amount">-Rp32.000</span>
                    <span class="status-gagal">Gagal</span>
                </div>
            </div>

            <div class="transaksi-item">
                <div class="transaksi-detail">
                    <img src="{{ asset('storage/topupIcon.png') }}" alt="">
                    <div class="transaksi-subdetail">
                        <span class="transaksi-title">Top Up</span>
                        <span class="transaksi-subtitle">Dompet</span>
                    </div>
                </div>
                <div class="transaksi-subdetail">
                    <span class="transaksi-amount">+Rp32.000</span>
                    <span class="status-berhasil">Berhasil</span>
                </div>
            </div>

            <div class="transaksi-item">
                <div class="transaksi-detail">
                    <img src="{{ asset('storage/paymentIcon.png') }}" alt="">
                    <div class="transaksi-subdetail">
                        <span class="transaksi-title">Bayar Iuran</span>
                        <span class="transaksi-subtitle">Februari</span>
                    </div>
                </div>
                <div class="transaksi-subdetail">
                    <span class="transaksi-amount">-Rp32.000</span>
                    <span class="status-berhasil">Berhasil</span>
                </div>
            </div>

            <div class="transaksi-item">
                <div class="transaksi-detail">
                    <img src="{{ asset('storage/paymentIcon.png') }}" alt="">
                    <div class="transaksi-subdetail">
                        <span class="transaksi-title">Bayar Iuran</span>
                        <span class="transaksi-subtitle">Februari</span>
                    </div>
                </div>
                <div class="transaksi-subdetail">
                    <span class="transaksi-amount">-Rp32.000</span>
                    <span class="status-gagal">Gagal</span>
                </div>
            </div>

            <div class="transaksi-item">
                <div class="transaksi-detail">
                    <img src="{{ asset('storage/paymentIcon.png') }}" alt="">
                    <div class="transaksi-subdetail">
                        <span class="transaksi-title">Bayar Iuran</span>
                    <span class="transaksi-subtitle">Maret</span>
                    </div>
                </div>
                <div class="transaksi-subdetail">
                    <span class="transaksi-amount">-Rp32.000</span>
                    <span class="status-gagal">Gagal</span>
                </div>
            </div>

            <div class="transaksi-item">
                <div class="transaksi-detail">
                    <img src="{{ asset('storage/topupIcon.png') }}" alt="">
                    <div class="transaksi-subdetail">
                        <span class="transaksi-title">Top Up</span>
                        <span class="transaksi-subtitle">Dompet</span>
                    </div>
                </div>
                <div class="transaksi-subdetail">
                    <span class="transaksi-amount">+Rp32.000</span>
                    <span class="status-berhasil">Berhasil</span>
                </div>
            </div>

            <div class="transaksi-item">
                <div class="transaksi-detail">
                    <img src="{{ asset('storage/paymentIcon.png') }}" alt="">
                    <div class="transaksi-subdetail">
                        <span class="transaksi-title">Bayar Iuran</span>
                        <span class="transaksi-subtitle">Februari</span>
                    </div>
                </div>
                <div class="transaksi-subdetail">
                    <span class="transaksi-amount">-Rp32.000</span>
                    <span class="status-berhasil">Berhasil</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection