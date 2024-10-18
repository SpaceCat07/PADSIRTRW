@extends('layouts.dashboardNavbar')

@section('content')
<?php
$page = 'dashboard'; // or 'program-kerja', 'pembayaran', etc.
?>

<div class="dashboard-container">

    <!-- Carousel Container -->
    <div class="carousel-container mt-6 p-5"
        style="background-color: #f1f1f1; border-radius: 12px; max-width: 1200px; margin: auto;">
        <div class="row align-items-center">
            <div class="col-md-4 d-flex flex-column align-items-start text-start">
                <h2 class="mb-0">Program Kerja Mendatang</h2>
                <a href="{{ route('program-kerja') }}" class="text-primary mt-2">Selengkapnya</a>
            </div>
            <div class="col-md-8">
                <div id="programKerjaCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="card p-3" style="border-radius: 12px; background-color: #f9f9f9;">
                                <div class="card-body text-start">
                                    <h5 class="card-title">Nama Program 1</h5>
                                    <p class="card-text">Tanggal 12/09/2024</p>
                                    <a href="#" class="btn btn-link">Learn more →</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="card p-3" style="border-radius: 12px; background-color: #f9f9f9;">
                                <div class="card-body text-start">
                                    <h5 class="card-title">Nama Program 2</h5>
                                    <p class="card-text">Tanggal 15/09/2024</p>
                                    <a href="#" class="btn btn-link">Learn more →</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Next/Prev outside the slides -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#programKerjaCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#programKerjaCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Informasi Iuran Section -->
    <div class="iuran-container mt-5">
        <div class="informasi-iuran">
            <h2 class="title-text-start mb-4">Informasi Iuran</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="group-status">
                        <div class="rectangle-status">
                            <div class="status text-center">
                                <p>Status</p>
                            </div>
                            <div class="vector-container text-center">
                                <div class="vector-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="group-total">
                        <div class="rectangle-total">
                            <div class="total-iuran-telah-dibayar text-center">
                                <p>Total iuran telah dibayar</p>
                                <p class="amount">Rp 50,000</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="group-tagihan">
                        <div class="rectangle-tagihan">
                            <div class="tagihan text-center">
                                <p>Tagihan</p>
                                <p class="amount">Rp 50,000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Keuangan RT -->
    <div class="iuran-container mt-5">
        <div class="informasi-iuran">
            <h2 class="title-text-start mb-4">Laporan Keuangan RT</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="group-status">
                        <div class="rectangle-status">
                            <div class="status text-center">
                                <p>Total iuran yang terkumpul</p>
                                <p class="amount">Rp 50,000</p> <!-- Example status -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="group-total">
                        <div class="rectangle-total">
                            <div class="total-iuran-telah-dibayar text-center">
                                <p>Sisa</p>
                                <p class="amount">Rp 50,000</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="group-tagihan">
                        <div class="rectangle-tagihan">
                            <div class="tagihan text-center">
                                <p>Uang Terpakai</p>
                                <p class="amount">Rp 50,000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection