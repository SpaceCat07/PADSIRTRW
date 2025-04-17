@extends('layouts.dashboardNavbar')

<title>SIMAS - dashboard</title>
<link rel="stylesheet" href="{{ asset('css/dashboard-warga.css') }}">
<link rel="stylesheet" href="{{ asset('css/charts.css') }}">

@section('content')
    <?php
    $page = 'dashboard'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    <!-- Carousel Container -->
    <div class="carousel-container" style="background-image: url('{{ asset('storage/dashboardBackground.png') }}');">
        <div class="row align-items-center">
            <div class="carousel-title col-md-4 d-flex flex-column align-items-center">
                <h1>Agenda</h1>
                <div class="carousel-title-highlight">
                    <h1>PROGRAM KERJA</h1>
                </div>
                <h1>Terdekat</h1>
            </div>
            <div class="carousel-content col-md-7">
                <div id="programKerjaCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <a href="{{ route('program-kerja') }}">
                                <div class="carousel-card custom-card p-5"
                                    style="background-image: url({{ asset('storage/carouselBackground.png') }});">
                                    <!-- No content in this card, just the background -->
                                </div>
                            </a>
                            <div class="carousel-card-body">
                                <h5 class="carousel-card-title">Rapat Mingguan</h5>
                                <div class="carousel-card-date-link">
                                    <p class="carousel-card-text">Senin 33 Maret 2033</p>
                                    <a href="{{ route('program-kerja') }}" class="btn btn-link">Learn more ></a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="carousel-card custom-card p-5"
                                style="background-image: url({{ asset('storage/carouselBackground.png') }});">
                                <!-- No content in this card, just the background -->
                            </div>
                            <div class="carousel-card-body">
                                <h5 class="carousel-card-title">Posyandu Balita & Lansia</h5>
                                <div class="carousel-card-date-link">
                                    <p class="carousel-card-text">Minggu 32 Maret 2033</p>
                                    <a href="{{ route('program-kerja') }}" class="btn btn-link">Learn more ></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
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

    <div class="dashboard-container">
        <!-- Informasi Iuran Section -->
        <div class="iuran-container mt-5">
            <div class="iuran-info">
                <h1 class="section-title mb-4">Informasi Iuran</h1>
                <a href="{{ route('pembayaran') }}" class="text-decoration-none">
                    <div class="iuran-report">
                        <div class="status-box">
                            <div class="status text-center">
                                <p>Status</p>
                            </div>
                            <div class="icon-container">
                                <img src="{{ asset('storage/rectangleKuning.png') }}" class="icon">
                            </div>
                        </div>
                        <div class="payment-status">
                            <img src="{{ asset('storage/orderComplete.png') }}" alt="">
                            <div>
                                <p class="status title">Sudah dibayar</p>
                                <p class="amount">Rp 10.000</p>
                            </div>
                        </div>
                        <div class="billing-status d-flex align-items-center">
                            <img src="{{ asset('storage/purchaseOrder.png') }}" alt="">
                            <div>
                                <p class="status title">Belum Dibayar</p>
                                <p class="amount">Rp 50.000</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Laporan Keuangan RT -->
        <div class="iuran-container mt-5">
            <div class="iuran-info">
                <h1 class="section-title mb-4">Laporan Keuangan RT</h1>
                <a href="{{ route('laporan-keuangan') }}" class="text-decoration-none">
                    <div class="iuran-report">
                        <div class="total-box">
                            <div class="total-status text-center">
                                <p class="total title">Total iuran saat ini</p>
                                <p class="amount">Rp 50,000</p> <!-- Example status -->
                            </div>
                        </div>
                        <div class="payment-status">
                            <div class="remaining-funds text-center">
                                <p class="status title">Sisa</p>
                                <p class="amount">Rp 50,000</p>
                            </div>
                        </div>
                        <div class="billing-status">
                            <div class="used-funds text-center">
                                <p class="status title">Pengeluaran</p>
                                <p class="amount">Rp 50,000</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="charts-container">
            <!-- Linechart -->
            <div class="linechart">
                <canvas id="lineChart"></canvas>
            </div>

            <!-- Barchart -->
            <div class="barchart">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <div class="piechart-container">
            <div class="piechart">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Ensure Chart.js Plugin is registered
        Chart.register(ChartDataLabels);

        // Access CSS variables
        const rootStyles = getComputedStyle(document.documentElement);
        const chartColors = {
            piePemasukanBg: rootStyles.getPropertyValue('--pie-pemasukan-bg').trim(),
            piePengeluaranBg: rootStyles.getPropertyValue('--pie-pengeluaran-bg').trim(),
            barPemasukanBg: rootStyles.getPropertyValue('--bar-pemasukan-bg').trim(),
            barPengeluaranBg: rootStyles.getPropertyValue('--bar-pengeluaran-bg').trim(),
            lineThisMonthBg: rootStyles.getPropertyValue('--line-thismonth-bg').trim(),
            lineThisMonthBorder: rootStyles.getPropertyValue('--line-thismonth-border').trim(),
            lineLastMonthBg: rootStyles.getPropertyValue('--line-lastmonth-bg').trim(),
            lineLastMonthBorder: rootStyles.getPropertyValue('--line-lastmonth-border').trim(),
        };

        const piechart_data = @json($piechart_data);
        const barchart_data = @json($barchart_data);
        const linechart_data = @json($linechart_data);
        const totalPemasukan = @json($totalPemasukan);
        const totalPengeluaran = @json($totalPengeluaran);
        const totalSaldo = @json($totalSaldo);
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const barCtx = document.getElementById('barChart').getContext('2d');
        const lineCtx = document.getElementById('lineChart').getContext('2d');

        // Pie Chart
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: piechart_data.labels,
                datasets: [{
                    data: piechart_data.data,
                    backgroundColor: [
                        chartColors.piePemasukanBg,
                        chartColors.piePengeluaranBg,
                    ],
                    borderWidth: 0,
                }, ],
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        formatter: (value, ctx) => {
                            const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            return `${((value / total) * 100).toFixed(1)}%`;
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                    },
                },
            },
        });

        // Bar Chart
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: barchart_data.labels,
                datasets: [{
                        label: 'Pemasukan',
                        data: barchart_data.datasets[0].data,
                        backgroundColor: chartColors.barPemasukanBg,
                    },
                    {
                        label: 'Pengeluaran',
                        data: barchart_data.datasets[1].data,
                        backgroundColor: chartColors.barPengeluaranBg,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    },
                },
            },
        });

        // Line Chart
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: linechart_data.labels,
                datasets: [{
                        label: 'This Month',
                        data: linechart_data.datasets[0].data,
                        backgroundColor: chartColors.lineThisMonthBg,
                        borderColor: chartColors.lineThisMonthBorder,
                        fill: true,
                    },
                    {
                        label: 'Last Month',
                        data: linechart_data.datasets[1].data,
                        backgroundColor: chartColors.lineLastMonthBg,
                        borderColor: chartColors.lineLastMonthBorder,
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    },
                },
            },
        });
    </script>
@endsection
