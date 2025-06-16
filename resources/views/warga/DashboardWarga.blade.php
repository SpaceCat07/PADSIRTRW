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
            <div class="linechart-barchart">
                <div class="linechart">
                    <canvas id="lineChart"></canvas>
                </div>
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
        // Beri pesan error di beberapa komponen utama
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
    // GANTI BLOK const chartColors LAMA ANDA DENGAN INI
    const rootStyles = getComputedStyle(document.documentElement);
    const chartColors = {
        piePemasukan: rootStyles.getPropertyValue('--pie-pemasukan-bg').trim() || '#676AFF',
        piePengeluaran: rootStyles.getPropertyValue('--pie-pengeluaran-bg').trim() || '#FFD25D',
        barPemasukan: rootStyles.getPropertyValue('--bar-pemasukan-bg').trim() || '#676AFF',
        barPengeluaran: rootStyles.getPropertyValue('--bar-pengeluaran-bg').trim() || '#FFD25D',
        // Memberi warna cadangan yang pasti ada
        lineThisMonth: rootStyles.getPropertyValue('--line-pemasukan').trim() || '#676AFF',
        lineLastMonth: rootStyles.getPropertyValue('--line-pengeluaran').trim() || '#FFD25D'
    };


    // 2. FUNGSI UTAMA UNTUK MENGAMBIL SEMUA DATA
    async function initDashboard() {
        // Tampilkan semua kontainer chart yang mungkin disembunyikan sebelumnya
        document.querySelector('.charts-container').style.display = 'flex';
        document.querySelector('.piechart-container').style.display = 'block';

        const results = await Promise.allSettled([
            axiosInstance.get('/proker'),
            axiosInstance.get('/wallet'),
            axiosInstance.get('/mutasi'),
            axiosInstance.get('/me') // DITAMBAHKAN: Panggil API untuk data user
        ]);

        const [prokerResult, walletResult, mutasiResult, meResult] = results;

        // --- Langkah Kunci: Dapatkan ID RT Pengguna ---
        // Cek dulu apakah data user berhasil didapat
        if (meResult.status === 'rejected') {
            console.error("Kritis: Gagal mengambil data profil pengguna.", meResult.reason);
            // Tampilkan pesan error karena kita tidak bisa melanjutkan tanpa tahu RT pengguna
            document.getElementById('laporanKeuangan').innerHTML = `<div class="text-center p-3">Gagal memuat data pengguna.</div>`;
            document.querySelector('.charts-container').style.display = 'none';
            document.querySelector('.piechart-container').style.display = 'none';
            return; // Hentikan eksekusi jika data user gagal
        }
        
        // Asumsi struktur response: data.warga.rt_id
        const userRtId = meResult.value.data.data.warga.rt_id;
        console.log(`User ini adalah warga dari RT: ${userRtId}`); // Untuk debugging

        // Proses data lain seperti biasa
        if (prokerResult.status === 'fulfilled') {
            processProkerData(prokerResult.value.data.data);
        } else {
            console.error("Gagal mengambil data proker:", prokerResult.reason);
            // ... (kode error handling proker)
        }

        if (walletResult.status === 'fulfilled') {
            processWalletData(walletResult.value.data.data);
        } else {
            console.error("Gagal mengambil data wallet:", walletResult.reason);
            // ... (kode error handling wallet)
        }

        if (mutasiResult.status === 'fulfilled') {
            // Ambil SEMUA transaksi RT dari API
            const allRtTransactions = mutasiResult.value.data.data.rt || [];

            // --- Langkah Kunci: Filter Transaksi ---
            // Buat array baru yang HANYA berisi transaksi yang rt_id-nya cocok
            const specificRtTransactions = allRtTransactions.filter(t => t.rt_id === userRtId);

            console.log(`Menemukan ${specificRtTransactions.length} transaksi untuk RT ${userRtId}`); // Untuk debugging

            // Kirim data yang SUDAH DIFILTER ke fungsi proses
            processFinancialData(specificRtTransactions);
        } else {
            console.error("Gagal memuat data keuangan:", mutasiResult.reason);
            // ... (kode error handling mutasi)
        }

        // Setup placeholder untuk informasi iuran pribadi
        setupIuranPlaceholder();
    }


    // 3. FUNGSI-FUNGSI PENGOLAH DATA
    function processProkerData(prokerData) {
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

        // Update Kartu Laporan Keuangan RT
        const laporanKeuangan = document.getElementById('laporanKeuangan');
        laporanKeuangan.innerHTML = `
            <div class="total-box"><div class="total-status text-center"><p class="total title">Total Pemasukan</p><p class="amount">${formatCurrency(totalPemasukan)}</p></div></div>
            <div class="payment-status"><div class="remaining-funds text-center"><p class="status title">Sisa Dana</p><p class="amount">${formatCurrency(sisaDana)}</p></div></div>
            <div class="billing-status"><div class="used-funds text-center"><p class="status title">Total Pengeluaran</p><p class="amount">${formatCurrency(totalPengeluaran)}</p></div></div>`;

        // Render semua chart
        renderPieChart(totalPemasukan, totalPengeluaran);
        renderBarChart(transactions);
        renderLineChart(transactions);
    }
    
    // Fungsi untuk bagian iuran yang API-nya belum jelas
    function setupIuranPlaceholder() {
        const iuranReport = document.getElementById('iuranReport');
        iuranReport.innerHTML = `<div class="p-3 text-center">Status pembayaran iuran Anda akan ditampilkan di sini.</div>`;
    }


    // 4. FUNGSI-FUNGSI UNTUK MERENDER GRAFIK
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
                maintainAspectRatio: false, // PENTING: Agar chart mengisi penuh container
                plugins: {
                    legend: {
                        display: false // Sembunyikan legend default karena sudah ada di HTML
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            if (total === 0) return '0%';
                            return `${((value / total) * 100).toFixed(1)}%`;
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels] // Pastikan plugin terdaftar
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
            options: { // Opsi yang hilang kini ditambahkan
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => (value / 1000) + 'k' // Format angka (misal: 50000 -> 50k)
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    datalabels: {
                        display: false // Matikan datalabels untuk bar chart agar tidak ramai
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

        // Siapkan array untuk pemasukan DAN pengeluaran
        const dataPemasukan = new Array(daysInMonth).fill(0);
        const dataPengeluaran = new Array(daysInMonth).fill(0);

        transactions.forEach(t => {
            const tDate = new Date(t.date);
            const dayOfMonth = tDate.getDate() - 1;

            // Proses hanya transaksi di bulan dan tahun berjalan
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
                datasets: [
                    // Dataset untuk Pemasukan
                    {
                        label: 'Pemasukan',
                        data: dataPemasukan,
                        borderColor: chartColors.lineThisMonth, // Gunakan warna yang sudah ada
                        backgroundColor: chartColors.lineThisMonth + '33',
                        fill: true,
                        tension: 0.4
                    },
                    // Dataset untuk Pengeluaran
                    {
                        label: 'Pengeluaran',
                        data: dataPengeluaran,
                        borderColor: '#FF6384', // Beri warna berbeda untuk pengeluaran
                        backgroundColor: '#FF638433',
                        fill: true,
                        tension: 0.4
                    }
                ]
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
    
    // 5. INISIALISASI
    initDashboard();
});
</script>


@endsection
