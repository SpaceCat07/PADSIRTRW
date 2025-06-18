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
                    <a href="{{ route('dompet-warga')}}">
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
        <div class="financial-section">

            <div class="financial-report-container">
                <div class="section-header">
                    <h1 class="section-title">Laporan Keuangan RT</h1>
                    <a href="{{ route('laporan-keuangan') }}" class="btn-detail">Lihat Detail</a>
                </div>
                <div class="report-body">
                    <div class="report-summary" id="laporanKeuangan">
                    </div>
                    <div class="report-pie-chart">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="charts-container">
                <div class="section-header">
                    <h1 class="section-title">Grafik Keuangan RT</h1>
                </div>
                <div class="charts-body">
                    <div class="linechart">
                        <canvas id="lineChart"></canvas>
                    </div>
                    <div class="barchart">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // 1. SETUP AWAL
            const token = localStorage.getItem('token');
            if (!token) {
                console.error("Token tidak ditemukan. Harap login kembali.");
                document.getElementById('programCarouselInner').innerHTML = `<div class="carousel-item active"><div class="carousel-card custom-card p-5 d-flex justify-content-center align-items-center"><h5 class="text-white">Silakan login kembali.</h5></div></div>`;
                document.querySelector('.saldo-amount').textContent = 'Error';
                return;
            }

            const axiosInstance = axios.create({
                baseURL: 'https://sirtrw-api.vansite.cloud/api',
                headers: { 'Authorization': `Bearer ${token}` }
            });

            let pieChart, barChart, lineChart;
            const formatCurrency = (value) => `Rp ${Number(value).toLocaleString('id-ID')}`;

            const rootStyles = getComputedStyle(document.documentElement);
            const chartColors = {
                piePemasukan: rootStyles.getPropertyValue('--pie-pemasukan-bg').trim() || '#676AFF',
                piePengeluaran: rootStyles.getPropertyValue('--pie-pengeluaran-bg').trim() || '#FFD25D',
                barPemasukan: rootStyles.getPropertyValue('--bar-pemasukan-bg').trim() || '#676AFF',
                barPengeluaran: rootStyles.getPropertyValue('--bar-pengeluaran-bg').trim() || '#FFD25D',
                lineThisMonth: rootStyles.getPropertyValue('--line-pemasukan').trim() || '#676AFF',
                lineLastMonth: rootStyles.getPropertyValue('--line-pengeluaran').trim() || '#FFD25D'
            };


            // 2. FUNGSI UTAMA UNTUK MENGAMBIL SEMUA DATA
            async function initDashboard() {
                // Kode yang menyebabkan error sudah dihapus dari sini.
                // Kita tidak perlu lagi menampilkan container secara manual, CSS sudah mengaturnya.

                const results = await Promise.allSettled([
                    axiosInstance.get('/proker'),
                    axiosInstance.get('/wallet'),
                    axiosInstance.get('/mutasi'),
                    axiosInstance.get('/me'),
                    axiosInstance.get('/iuran')
                ]);

                const [prokerResult, walletResult, mutasiResult, meResult, iuranResult] = results;

                if (meResult.status === 'rejected') {
                    console.error("Kritis: Gagal mengambil data profil pengguna.", meResult.reason);
                    document.getElementById('laporanKeuangan').innerHTML = `<div class="text-center p-3">Gagal memuat data pengguna.</div>`;
                    document.getElementById('iuranReport').innerHTML = `<div class="p-3 text-center">Gagal memuat data iuran.</div>`;

                    // MODIFIKASI: Sembunyikan container yang baru jika ada error
                    const financialReport = document.querySelector('.financial-report-container');
                    const chartsContainer = document.querySelector('.charts-container');
                    if (financialReport) financialReport.style.display = 'none';
                    if (chartsContainer) chartsContainer.style.display = 'none';

                    return;
                }

                const userRtId = meResult.value.data.data.warga.rt_id;
                console.log(`User ini adalah warga dari RT: ${userRtId}`);

                // Proses data proker
                if (prokerResult.status === 'fulfilled') {
                    processProkerData(prokerResult.value.data.data);
                } else {
                    console.error("Gagal mengambil data proker:", prokerResult.reason);
                }

                // Proses data wallet
                if (walletResult.status === 'fulfilled') {
                    processWalletData(walletResult.value.data.data);
                } else {
                    console.error("Gagal mengambil data wallet:", walletResult.reason);
                }

                // Proses data keuangan RT
                if (mutasiResult.status === 'fulfilled') {
                    const allRtTransactions = mutasiResult.value.data.data.rt || [];
                    const specificRtTransactions = allRtTransactions.filter(t => t.rt_id === userRtId);
                    console.log(`Menemukan ${specificRtTransactions.length} transaksi untuk RT ${userRtId}`);
                    processFinancialData(specificRtTransactions);
                } else {
                    console.error("Gagal memuat data keuangan:", mutasiResult.reason);
                    document.getElementById('laporanKeuangan').innerHTML = `<div class="p-3 text-center text-danger">Gagal memuat data keuangan.</div>`;
                }

                // Proses data Iuran
                if (iuranResult.status === 'fulfilled') {
                    const allRtIuran = iuranResult.value.data.data.rt || [];
                    const specificRtIuran = allRtIuran.filter(iuran => iuran.rt_id === userRtId);
                    console.log(`Menemukan ${specificRtIuran.length} jenis iuran untuk RT ${userRtId}`);
                    processIuranData(specificRtIuran);
                } else {
                    console.error("Gagal memuat data iuran:", iuranResult.reason);
                    document.getElementById('iuranReport').innerHTML = `<div class="p-3 text-center text-danger">Gagal memuat data iuran.</div>`;
                }
            }


            // 3. FUNGSI-FUNGSI PENGOLAH DATA
            function processProkerData(prokerData) {
                // ... (Fungsi ini tidak berubah, biarkan seperti semula)
                const programCarouselInner = document.getElementById('programCarouselInner');
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const upcomingProker = (prokerData || [])
                    .filter(program => new Date(program.date) >= today && program.status !== 'done')
                    .sort((a, b) => new Date(a.date) - new Date(b.date));

                if (upcomingProker.length > 0) {
                    programCarouselInner.innerHTML = '';
                    upcomingProker.forEach((program, index) => {
                        const activeClass = index === 0 ? 'active' : '';
                        const title = program.title || 'Judul tidak tersedia';
                        const formattedDate = new Date(program.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                        const item = document.createElement('div');
                        item.className = `carousel-item ${activeClass}`;
                        item.innerHTML = `
                                                    <a href="/program-kerja"><div class="carousel-card custom-card p-5" style="background-image: url('/storage/carouselBackground.png');"></div></a>
                                                    <div class="carousel-card-body">
                                                        <h5 class="carousel-card-title">${title}</h5>
                                                        <div class="carousel-card-date-link">
                                                            <p class="carousel-card-text">${formattedDate}</p>
                                                            <a href="/program-kerja" class="btn btn-link">Learn more ></a>
                                                        </div>
                                                    </div>`;
                        programCarouselInner.appendChild(item);
                    });
                } else {
                    programCarouselInner.innerHTML = `<div class="carousel-item active"><div class="carousel-card custom-card p-5 d-flex justify-content-center align-items-center" style="background-image: url('/storage/carouselBackground.png');"><h5 class="text-white">Tidak ada program kerja terdekat.</h5></div></div>`;
                }
            }

            function processWalletData(walletData) {
                // ... (Fungsi ini tidak berubah, biarkan seperti semula)
                const saldoElement = document.querySelector('.saldo-amount');
                if (Array.isArray(walletData) && walletData.length > 0) {
                    const latestTransaction = walletData[walletData.length - 1];
                    saldoElement.textContent = formatCurrency(latestTransaction.after);
                } else {
                    saldoElement.textContent = formatCurrency(0);
                }
            }

            function processFinancialData(transactions) {
                let totalPemasukan = 0;
                let totalPengeluaran = 0;
                transactions.forEach(t => {
                    if (t.variance === 'inflow') totalPemasukan += t.value;
                    if (t.variance === 'outflow') totalPengeluaran += t.value;
                });
                const sisaDana = totalPemasukan - totalPengeluaran;

                // Target elemen summary
                const laporanKeuangan = document.getElementById('laporanKeuangan');

                // Buat struktur HTML baru yang sesuai dengan desain
                laporanKeuangan.innerHTML = `
                        <div class="summary-row">
                            <div class="summary-card">
                                <p>Pemasukan</p>
                                <span class="amount">${formatCurrency(totalPemasukan)}</span>
                            </div>
                            <div class="summary-card">
                                <p>Pengeluaran</p>
                                <span class="amount">${formatCurrency(totalPengeluaran)}</span>
                            </div>
                        </div>
                        <div class="summary-card-full">
                            <p>Sisa uang iuran saat ini</p>
                            <span class="amount-highlight">${formatCurrency(sisaDana)}</span>
                        </div>
                    `;

                // Panggil fungsi render chart seperti biasa
                renderPieChart(totalPemasukan, totalPengeluaran);
                renderBarChart(transactions);
                renderLineChart(transactions);
            }

            function processIuranData(iuranList) {
                const iuranReport = document.getElementById('iuranReport');
                iuranReport.innerHTML = ''; // Selalu kosongkan kontainer dulu

                if (!iuranList || iuranList.length === 0) {
                    iuranReport.innerHTML = `<div class="p-3 text-center">Tidak ada informasi iuran untuk RT Anda saat ini.</div>`;
                    return;
                }

                // 1. Urutkan daftar iuran berdasarkan tanggal pembuatan, dari yang terbaru ke yang terlama.
                const sortedIuran = iuranList.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                // 2. Dapatkan tinggi maksimal yang tersedia untuk daftar iuran.
                const availableHeight = iuranReport.offsetHeight;
                let accumulatedHeight = 0;

                // 3. Loop melalui iuran yang sudah diurutkan.
                for (const iuran of sortedIuran) {
                    // Buat elemen HTML untuk item iuran (menggunakan class dari CSS di atas)
                    const iuranName = iuran.name || 'Iuran';
                    const iuranMonth = iuran.month || '';
                    const iuranValue = formatCurrency(iuran.value || 0);

                    const item = document.createElement('div');
                    item.className = 'iuran-item-card';
                    item.innerHTML = `
                                    <div class="iuran-details">
                                        <p class="iuran-name">${iuranName}</p>
                                        <p class="iuran-meta">${iuranMonth} - ${iuran.variance}</p>
                                    </div>
                                    <div class="iuran-amount">
                                        ${iuranValue}
                                    </div>
                                `;

                    // 4. Tambahkan item ke DOM untuk mengukur tingginya.
                    iuranReport.appendChild(item);

                    // 5. Ukur tinggi item, termasuk gap/jarak antar elemen.
                    const itemHeight = item.offsetHeight + 10; // 10 adalah nilai 'gap' dari CSS

                    // 6. Cek apakah penambahan item ini akan melebihi tinggi yang tersedia.
                    if (accumulatedHeight + itemHeight > availableHeight) {
                        // Jika melebihi, hapus item terakhir yang ditambahkan dan hentikan loop.
                        iuranReport.removeChild(item);
                        break;
                    }

                    // Jika masih muat, tambahkan tingginya ke akumulasi.
                    accumulatedHeight += itemHeight;
                }
            }

            // 4. FUNGSI-FUNGSI UNTUK MERENDER GRAFIK
            // ... (Semua fungsi render chart (renderPieChart, renderBarChart, renderLineChart) tidak berubah)
            function renderPieChart(pemasukan, pengeluaran) {
                const ctx = document.getElementById('pieChart')?.getContext('2d');
                if (!ctx) return;
                if (pieChart) pieChart.destroy();
                pieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Pemasukan', 'Pengeluaran'],
                        datasets: [{
                            data: [pemasukan, pengeluaran],
                            backgroundColor: [chartColors.piePemasukan, chartColors.piePengeluaran],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            // MODIFIKASI: Aktifkan dan atur posisi legend
                            legend: {
                                display: true,
                                position: 'top',
                                align: 'end',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8,
                                    padding: 20,
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            // Sembunyikan datalabels (persentase) di dalam pie chart
                            datalabels: {
                                display: false
                            }
                        }
                    },
                    // Hapus plugins: [ChartDataLabels] jika ada, karena sudah di dalam options
                });
            }

            function renderBarChart(transactions) {
                const ctx = document.getElementById('barChart')?.getContext('2d');
                if (!ctx) return;
                const data = {
                    labels: [],
                    datasets: [{
                        label: 'Pemasukan',
                        data: [],
                        backgroundColor: chartColors.barPemasukan
                    }, {
                        label: 'Pengeluaran',
                        data: [],
                        backgroundColor: chartColors.barPengeluaran
                    }]
                };
                const now = new Date();
                for (let i = 5; i >= 0; i--) {
                    const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
                    data.labels.push(date.toLocaleString('id-ID', {
                        month: 'short'
                    }));
                    let monthlyIn = 0,
                        monthlyOut = 0;
                    transactions.forEach(t => {
                        const tDate = new Date(t.date);
                        if (tDate.getMonth() === date.getMonth() && tDate.getFullYear() === date.getFullYear()) {
                            if (t.variance === 'inflow') monthlyIn += t.value;
                            else if (t.variance === 'outflow') monthlyOut += t.value;
                        }
                    });
                    data.datasets[0].data.push(monthlyIn);
                    data.datasets[1].data.push(monthlyOut);
                }
                if (barChart) barChart.destroy();
                barChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => (value / 1000) + 'k'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            datalabels: {
                                display: false
                            }
                        }
                    }
                });
            }

            function renderLineChart(transactions) {
                const ctx = document.getElementById('lineChart')?.getContext('2d');
                if (!ctx) return;

                const now = new Date();
                const thisMonth = now.getMonth();
                const thisYear = now.getFullYear();

                const daysInMonth = new Date(thisYear, thisMonth + 1, 0).getDate();
                const labels = Array.from({ length: daysInMonth }, (_, i) => i + 1);

                const dataPemasukan = new Array(daysInMonth).fill(0);
                const dataPengeluaran = new Array(daysInMonth).fill(0);

                transactions.forEach(t => {
                    const tDate = new Date(t.date);
                    const dayOfMonth = tDate.getDate() - 1;

                    if (tDate.getFullYear() === thisYear && tDate.getMonth() === thisMonth) {
                        if (t.variance === 'inflow') {
                            dataPemasukan[dayOfMonth] += t.value;
                        } else if (t.variance === 'outflow') {
                            dataPengeluaran[dayOfMonth] += t.value;
                        }
                    }
                });

                if (lineChart) lineChart.destroy();
                lineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Pemasukan',
                            data: dataPemasukan,
                            borderColor: chartColors.lineThisMonth,
                            backgroundColor: chartColors.lineThisMonth + '33',
                            fill: true,
                            tension: 0.4
                        }, {
                            label: 'Pengeluaran',
                            data: dataPengeluaran,
                            borderColor: '#FF6384',
                            backgroundColor: '#FF638433',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => (value / 1000) + 'k'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            datalabels: {
                                display: false
                            }
                        }
                    }
                });
            }

            function setupCardNavigation() {
                const laporanKeuanganCard = document.querySelector('.financial-report-container');

                // Cek apakah elemennya ada
                if (laporanKeuanganCard) {
                    // Ubah cursor menjadi tangan saat di-hover untuk menandakan bisa diklik
                    laporanKeuanganCard.style.cursor = 'pointer';

                    laporanKeuanganCard.addEventListener('click', (event) => {
                        // PENTING: Jika yang diklik adalah link 'Lihat Detail' atau tombol lain di dalamnya,
                        // jangan lakukan apa-apa, biarkan link/tombol itu yang bekerja.
                        if (event.target.closest('a, button')) {
                            return;
                        }

                        // Jika bagian lain dari kartu yang diklik, arahkan ke halaman laporan keuangan
                        window.location.href = "{{ route('laporan-keuangan') }}";
                    });
                }
            }

            // 5. INISIALISASI
            setupCardNavigation();
            initDashboard();
        });
    </script>


@endsection