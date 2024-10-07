@extends('layouts.dashboardNavbar')

@section('content')
<?php
$page = 'dashboard'; // or 'program-kerja', 'pembayaran', etc.
?>

<div class="dashboard-container">

    <!-- Group 39479: Carousel Program Kerja -->
    <div class="carousel-program-kerja"></div>

    <!-- Informasi Iuran Section -->
    <div class="informasi-iuran">
        <h2>Informasi Iuran</h2>
        <div class="group-39490">
            <div class="rectangle-23791"></div>
            <div class="status">Status</div>
            <div class="vector-container">
                <div class="vector-3"></div>
                <div class="vector-4"></div>
            </div>
        </div>
        <div class="group-39488">
            <div class="rectangle-23792"></div>
            <div class="total-iuran-telah-dibayar">
                <p>Total iuran telah dibayar</p>
                <p class="amount">Rp 50000</p>
            </div>
        </div>
        <div class="group-39489">
            <div class="rectangle-23793"></div>
            <div class="tagihan">
                <p>Tagihan</p>
                <p class="amount">Rp 50000</p>
            </div>
        </div>
    </div>
</div>
@endsection