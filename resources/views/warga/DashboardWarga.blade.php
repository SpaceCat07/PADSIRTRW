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
                    <div class="carousel-inner" id="programCarouselInner">
                        <!-- Program carousel items will be dynamically inserted here -->
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
        <div class="dompet-container mt-5">
            <div class="dompet-overview">
                <div class="saldo-overview">
                    <h3 class="saldo-title">Saldo Dompet Anda</h3>
                    <h1 class="saldo-amount">Loading...</h1>
                </div>
                <div class="saldo-buttons">
                    <a href="{{ route('dompet-warga') }}">
                        <button>tambah saldo</button>
                    </a>
                    <a href="{{ route ('dompet-warga')}}">
                        <button>riwayat transaksi</button>
                    </a>
                </div>
            </div>
            <div class="iuran-overview">
                <h1 class="section-title mb-4">Informasi Iuran</h1>
                <a href="{{ route('pembayaran') }}" class="text-decoration-none">
                    <div class="iuran-report" id="iuranReport">
                        <!-- Payment status will be dynamically inserted here -->
                    </div>
                </a>
            </div>
        </div>

        <!-- Laporan Keuangan RT -->
        <div class="iuran-container mt-5">
            <div class="laporan-info">
                <h1 class="section-title mb-4">Laporan Keuangan RT</h1>
                <a href="{{ route('laporan-keuangan') }}" class="text-decoration-none">
                    <div class="iuran-report" id="laporanKeuangan">
                        <!-- Financial report will be dynamically inserted here -->
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

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const programCarouselInner = document.getElementById('programCarouselInner');
            const iuranReport = document.getElementById('iuranReport');
            const laporanKeuangan = document.getElementById('laporanKeuangan');

            let pieChart, barChart, lineChart;

            const token = localStorage.getItem('token');
            if (!token) {
                console.warn('No token found in localStorage');
                return;
            }

            try {
                // Ambil data proker (untuk carousel)
                const prokerResponse = await axios.get('https://sirtrw-api.vansite.cloud/api/proker', {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });

                const prokerData = prokerResponse.data.data;
                if (Array.isArray(prokerData) && prokerData.length > 0) {
                    programCarouselInner.innerHTML = '';
                    prokerData.forEach((program, index) => {
                        const activeClass = index === 0 ? 'active' : '';
                        const title = program.judul || program.title || 'Judul tidak tersedia'; // Cek beberapa kemungkinan
                        const date = program.tanggal || program.dateFormatted || '-';

                        const item = document.createElement('div');
                        item.className = `carousel-item ${activeClass}`;
                        item.innerHTML = `
                            <a href="/program-kerja">
                                <div class="carousel-card custom-card p-5" style="background-image: url('/storage/carouselBackground.png');"></div>
                            </a>
                            <div class="carousel-card-body">
                                <h5 class="carousel-card-title">${title}</h5>
                                <div class="carousel-card-date-link">
                                    <p class="carousel-card-text">${date}</p>
                                    <a href="/program-kerja" class="btn btn-link">Learn more ></a>
                                </div>
                            </div>
                        `;
                        programCarouselInner.appendChild(item);
                    });
                }

                // Ambil data wallet (untuk saldo dompet)
                const walletResponse = await axios.get('https://sirtrw-api.vansite.cloud/api/wallet', {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });

                const walletData = walletResponse.data.data;

                if (Array.isArray(walletData) && walletData.length > 0) {
                    const latestTransaction = walletData[walletData.length - 1]; // Asumsinya urutan dari awal ke akhir
                    const saldo = latestTransaction.after;

                    const saldoElement = document.querySelector('.saldo-amount');
                    saldoElement.textContent = `Rp. ${saldo.toLocaleString('id-ID')}`;
                } else {
                    const saldoElement = document.querySelector('.saldo-amount');
                    saldoElement.textContent = 'Rp. 0';
                }

                // Ambil data dashboard
                const dashboardResponse = await axios.get('/api/dashboard-data');
                const data = dashboardResponse.data;

                // Render Iuran
                if (data.iuran) {
                    iuranReport.innerHTML = `
                        <div class="status-box">
                            <div class="status text-center"><p>Status</p></div>
                            <div class="icon-container">
                                <img src="/storage/rectangleKuning.png" class="icon">
                            </div>
                        </div>
                        <div class="payment-status">
                            <img src="/storage/orderComplete.png" alt="">
                            <div>
                                <p class="status title">${data.iuran.statusPaidText}</p>
                                <p class="amount">${data.iuran.statusPaidAmount}</p>
                            </div>
                        </div>
                        <div class="billing-status d-flex align-items-center">
                            <img src="/storage/purchaseOrder.png" alt="">
                            <div>
                                <p class="status title">${data.iuran.statusDueText}</p>
                                <p class="amount">${data.iuran.statusDueAmount}</p>
                            </div>
                        </div>
                    `;
                }

                // Render Laporan Keuangan
                if (data.laporanKeuangan) {
                    laporanKeuangan.innerHTML = `
                        <div class="total-box">
                            <div class="total-status text-center">
                                <p class="total title">Total iuran saat ini</p>
                                <p class="amount">${data.laporanKeuangan.totalIuran}</p>
                            </div>
                        </div>
                        <div class="payment-status">
                            <div class="remaining-funds text-center">
                                <p class="status title">Sisa</p>
                                <p class="amount">${data.laporanKeuangan.sisa}</p>
                            </div>
                        </div>
                        <div class="billing-status">
                            <div class="used-funds text-center">
                                <p class="status title">Pengeluaran</p>
                                <p class="amount">${data.laporanKeuangan.pengeluaran}</p>
                            </div>
                        </div>
                    `;
                }

                // Inisialisasi chart
                const pieCtx = document.getElementById('pieChart').getContext('2d');
                const barCtx = document.getElementById('barChart').getContext('2d');
                const lineCtx = document.getElementById('lineChart').getContext('2d');

                pieChart?.destroy();
                barChart?.destroy();
                lineChart?.destroy();

                pieChart = new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: data.piechartData.labels,
                        datasets: [{
                            data: data.piechartData.data,
                            backgroundColor: [
                                getComputedStyle(document.documentElement).getPropertyValue('--pie-pemasukan-bg').trim(),
                                getComputedStyle(document.documentElement).getPropertyValue('--pie-pengeluaran-bg').trim(),
                            ],
                            borderWidth: 0,
                        }],
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

                barChart = new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: data.barchartData.labels,
                        datasets: [
                            {
                                label: 'Pemasukan',
                                data: data.barchartData.datasets[0].data,
                                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--bar-pemasukan-bg').trim(),
                            },
                            {
                                label: 'Pengeluaran',
                                data: data.barchartData.datasets[1].data,
                                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--bar-pengeluaran-bg').trim(),
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

                lineChart = new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: data.linechartData.labels,
                        datasets: [
                            {
                                label: 'This Month',
                                data: data.linechartData.datasets[0].data,
                                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--line-thismonth-bg').trim(),
                                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--line-thismonth-border').trim(),
                                fill: true,
                            },
                            {
                                label: 'Last Month',
                                data: data.linechartData.datasets[1].data,
                                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--line-lastmonth-bg').trim(),
                                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--line-lastmonth-border').trim(),
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

            } catch (error) {
                console.error('Error fetching dashboard or proker data:', error);
            }
        });
    </script>

@endsection
