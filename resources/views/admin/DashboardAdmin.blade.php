@extends('layouts.adminSidebar')

<title>SIMAS - Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/dashboard-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/charts.css') }}">

{{-- File: dashboard.blade.php --}}
@section('content')
<title>SIMAS - Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/dashboard-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/charts.css') }}">

<div class="admin-dashboard-container">
    {{-- HEADER (Tidak Berubah) --}}
    <div class="header-container">
        <header class="dashboard-header">
            <div class="header-admin-left">
                <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
                <div class="header-greeting">
                    <p id="adminGreeting">Hi, Admin</p>
                    <h1>Welcome Back!</h1>
                </div>
            </div>
            <div class="header-admin-dropdown text-end me-3">
                <a href="#" class="profile-picture link-body-emphasis text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                    <p id="adminName">Admin</p>
                    <img id="adminProfilePic" src="https://github.com/mdo.png" alt="mdo" width="38" height="38" class="rounded-circle ms-2 align-middle">
                </a>
                <ul class="dropdown-menu text-small">
                    <li><a class="dropdown-item" href="{{ route('edit.profil') }}">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form class="text-center" action="{{ route('logout') }}" method="post">
                            @csrf
                            <button class="profile-logout-btn" type="submit">Sign out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>
    </div>

    {{-- STRUKTUR UTAMA DUA KOLOM --}}
    <div class="dashboard-main-content">

        <div class="dashboard-left-column">
            <div class="admin-card" id="dataWargaCard">
                <div class="title-arrow">
                    <h3 id="dataWargaTitle">Data Warga</h3>
                    <a href="#"><img src="{{ asset('storage/arrow.png') }}" alt="arrow"></a>
                </div>
                <div id="wargaListContainer">
                    <p>Loading...</p>
                </div>
            </div>
            <div class="admin-card" id="prokerCard">
                <h3>Program Kerja Mendatang</h3>
                <div id="prokerContainer"><p>Loading...</p></div>
            </div>
            <div class="admin-card" id="kritikSaranCard">
                <h3>Pesan Kritik & Saran</h3>
                <div id="kritikContainer"><p>Loading...</p></div>
            </div>
        </div>

        <div class="dashboard-right-column">
            <div class="admin-card" id="pembayaranCard">
                <div class="title-arrow">
                    <h3>Pembayaran butuh konfirmasi</h3>
                    <a href="#"><img src="{{ asset('storage/arrow.png') }}" alt="arrow"></a>
                </div>
                <ul id="pembayaranList"><li>Loading...</li></ul>
            </div>

            {{-- Kartu Pemasukan & Pengeluaran Kecil --}}
            <div class="financial-summary-cards">
                <div class="financial-card pemasukan" id="pemasukanCard">
                    <img src="{{ asset('storage/deposit.png') }}" alt="">
                    <div>
                        <label>Pemasukan</label>
                        <p id="pemasukanAmount">Loading...</p>
                    </div>
                </div>
                <div class="financial-card pengeluaran" id="pengeluaranCard">
                    <img src="{{ asset('storage/getCash.png') }}" alt="">
                    <div>
                        <label>Pengeluaran</label>
                        <p id="pengeluaranAmount">Loading...</p>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <h3>Perbandingan Pemasukan dan Pengeluaran</h3>
                <div class="pie-chart-container">
                    <div class="piechart-legend">
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: var(--pie-pemasukan-bg);"></span>
                            Pemasukan
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: var(--pie-pengeluaran-bg);"></span>
                            Pengeluaran
                        </div>
                    </div>
                    <div class="piechart-wrapper">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <h3>Grafik Laporan Pengeluaran</h3>
                <div class="barchart">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <div class="admin-card">
                <div class="chart-header">
                    <h3>Sale</h3>
                    <div class="chart-controls">
                        <select id="yearSelector" class="form-select"></select>
                        <select id="monthSelector" class="form-select"></select>
                    </div>
                </div>
                <div class="linechart">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- GANTI TOTAL SEMUA KODE JAVASCRIPT ANDA DENGAN INI --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // 1. SETUP AWAL
    const token = localStorage.getItem('token');
    const userRole = localStorage.getItem('userRole')?.toLowerCase() || 'rt';
    if (!token) {
        console.error("Token tidak ditemukan. Harap login kembali.");
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
        pemasukan: rootStyles.getPropertyValue('--pie-pemasukan-bg').trim() || '#676AFF',
        pengeluaran: rootStyles.getPropertyValue('--pie-pengeluaran-bg').trim() || '#FFD25D',
        linePengeluaran: rootStyles.getPropertyValue('--line-pengeluaran-border')?.trim() || '#F44336',
    };

    // 2. FUNGSI UTAMA
    async function initDashboard() {
        console.log("Memulai inisialisasi dashboard...");
        const results = await Promise.allSettled([
            axiosInstance.get('/mutasi'),
            axiosInstance.get('/warga'),
            axiosInstance.get('/pembayaran?status=pending'),
            axiosInstance.get('/proker'),
            axiosInstance.get('/kritik'),
            axiosInstance.get('/me')
        ]);
        const [mutasiResult, wargaResult, pembayaranResult, prokerResult, kritikResult, meResult] = results;

        // Proses setiap hasil promise
        if (meResult.status === 'fulfilled') processUserData(meResult.value.data);
        else console.error("Gagal mengambil data user:", meResult.reason);

        if (wargaResult.status === 'fulfilled') processWargaData(wargaResult.value.data);
        else console.error("Gagal mengambil data warga:", wargaResult.reason);

        if (pembayaranResult.status === 'fulfilled') processPembayaranData(pembayaranResult.value.data);
        else console.error("Gagal mengambil data pembayaran:", pembayaranResult.reason);
        
        if (prokerResult.status === 'fulfilled') processProkerData(prokerResult.value.data);
        else document.getElementById('prokerContainer').innerHTML = '<p class="text-danger">Gagal memuat data.</p>';

        if (kritikResult.status === 'fulfilled') processKritikData(kritikResult.value.data);
        else document.getElementById('kritikContainer').innerHTML = '<p class="text-danger">Gagal memuat data.</p>';

        if (mutasiResult.status === 'fulfilled') {
            const transactions = (userRole.includes('rt') ? mutasiResult.value.data.data.rt : mutasiResult.value.data.data.rw) || [];
            processFinancialData(transactions);
        } else {
             console.error("Gagal memuat data keuangan:", mutasiResult.reason);
             document.getElementById('pemasukanAmount').textContent = 'Error';
             document.getElementById('pengeluaranAmount').textContent = 'Error';
        }
    }

    // 3. FUNGSI-FUNGSI PENGOLAH DATA
    function processUserData(response) {
        const user = response.data;
        const name = user.warga?.nama || 'Admin';
        document.getElementById('adminGreeting').textContent = `Hi, ${name.split(' ')[0]}`;
        document.getElementById('adminName').textContent = name;
        if (user.warga?.foto) document.getElementById('adminProfilePic').src = user.warga.foto;
    }

    function processWargaData(response) {
        const wargaListContainer = document.getElementById('wargaListContainer');
        const dataWargaTitle = document.getElementById('dataWargaTitle');
        wargaListContainer.innerHTML = '';
        const wargaData = response.data || [];
        if (userRole.includes('rw')) {
            dataWargaTitle.textContent = "Data RT";
            const rtGroups = wargaData.reduce((acc, warga) => {
                const rtId = warga.rt_id || 'Tanpa RT';
                if (!acc[rtId]) acc[rtId] = 0;
                acc[rtId]++;
                return acc;
            }, {});
            if(Object.keys(rtGroups).length > 0) {
                for (const rtId in rtGroups) {
                    const item = document.createElement('div');
                    item.className = 'warga-item';
                    item.innerHTML = `<img src="{{ asset('storage/family.png') }}" alt=""><div><p>RT ${rtId}</p><span>${rtGroups[rtId]} Warga</span></div>`;
                    wargaListContainer.appendChild(item);
                }
            } else {
                 wargaListContainer.innerHTML = '<p>Belum ada data RT.</p>';
            }
        } else {
            dataWargaTitle.textContent = "Data Warga";
            wargaListContainer.innerHTML = `<div class="warga-item"><img src="{{ asset('storage/family.png') }}" alt=""><div><p>${wargaData.length} Keluarga</p><span>Di RT Anda</span></div></div>`;
        }
    }

    function processPembayaranData(response) {
        const payments = response.data || [];
        const pembayaranList = document.getElementById('pembayaranList');
        pembayaranList.innerHTML = '';
        if (payments.length > 0) {
            payments.slice(0, 2).forEach(payment => {
                const li = document.createElement('li');
                li.innerHTML = `<span>No. Rekening ${payment.rekening || 'N/A'}</span><span>${formatCurrency(payment.value)}</span>`;
                pembayaranList.appendChild(li);
            });
        } else {
            pembayaranList.innerHTML = '<li>Tidak ada pembayaran baru.</li>';
        }
    }

    function processProkerData(response) {
        const prokerContainer = document.getElementById('prokerContainer');
        prokerContainer.innerHTML = '';
        const upcoming = (response.data || [])
            .filter(item => new Date(item.date) >= new Date())
            .sort((a, b) => new Date(a.date) - new Date(b.date)).slice(0, 2);
        if (upcoming.length > 0) {
             upcoming.forEach(proker => {
                const prokerDate = new Date(proker.date);
                const month = prokerDate.toLocaleString('id-ID', { month: 'short' }).toUpperCase();
                const day = prokerDate.getDate();
                const card = document.createElement('div');
                card.className = 'program-card';
                card.innerHTML = `<div class="date"><div class="month">${month}</div><div class="day">${day}</div></div><div class="details"><h3>${proker.title}</h3></div>`;
                prokerContainer.appendChild(card);
            });
        } else {
            prokerContainer.innerHTML = '<p>Tidak ada program kerja mendatang.</p>';
        }
    }

    function processKritikData(response) {
        const kritikContainer = document.getElementById('kritikContainer');
        kritikContainer.innerHTML = '';
        const latestKritik = (response.data || []).sort((a,b) => new Date(b.created_at) - new Date(a.created_at)).slice(0, 1);
        if(latestKritik.length > 0) {
            latestKritik.forEach(item => {
                const card = document.createElement('div');
                card.className = 'kritik-saran-card';
                const userPhoto = item.user?.warga?.foto || "{{ asset('storage/maleUser.png') }}";
                card.innerHTML = `<img class="profile-icon" src="${userPhoto}" alt="user icon"><div><h4>${item.user?.warga?.nama || 'Warga'}</h4><p>${item.text}</p></div>`;
                kritikContainer.appendChild(card);
            });
        } else {
            kritikContainer.innerHTML = '<p>Belum ada kritik atau saran.</p>';
        }
    }
    
    function processFinancialData(transactions) {
        let totalPemasukan = 0;
        let totalPengeluaran = 0;
        transactions.forEach(t => {
            if (t.variance === 'inflow') totalPemasukan += t.value;
            if (t.variance === 'outflow') totalPengeluaran += t.value;
        });
        document.getElementById('pemasukanAmount').textContent = formatCurrency(totalPemasukan);
        document.getElementById('pengeluaranAmount').textContent = formatCurrency(totalPengeluaran);
        
        renderPieChart(totalPemasukan, totalPengeluaran);
        renderBarChart(transactions);
        setupLineChart(transactions);
    }
    
    // 4. FUNGSI-FUNGSI UNTUK MERENDER GRAFIK
    function renderPieChart(pemasukan, pengeluaran) {
        const ctx = document.getElementById('pieChart')?.getContext('2d');
        if (!ctx) return;
        if (pieChart) pieChart.destroy();
        pieChart = new Chart(ctx, {
            type: 'doughnut', data: { labels: ['Pemasukan', 'Pengeluaran'], datasets: [{ data: [pemasukan, pengeluaran], backgroundColor: [chartColors.pemasukan, chartColors.pengeluaran], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false }, datalabels: { display: false } } }
        });
    }

    function renderBarChart(transactions) {
        const ctx = document.getElementById('barChart')?.getContext('2d');
        if (!ctx) return;
        const data = { labels: [], datasets: [
            { label: 'Pemasukan', data: [], backgroundColor: chartColors.pemasukan },
            { label: 'Pengeluaran', data: [], backgroundColor: chartColors.pengeluaran }
        ]};
        const now = new Date();
        for (let i = 5; i >= 0; i--) {
            const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
            data.labels.push(date.toLocaleString('id-ID', { month: 'short' }));
            let monthlyInflow = 0, monthlyOutflow = 0;
            transactions.forEach(t => {
                const tDate = new Date(t.date);
                if (tDate.getMonth() === date.getMonth() && tDate.getFullYear() === date.getFullYear()) {
                    if (t.variance === 'inflow') monthlyInflow += t.value;
                    else if (t.variance === 'outflow') monthlyOutflow += t.value;
                }
            });
            data.datasets[0].data.push(monthlyInflow);
            data.datasets[1].data.push(monthlyOutflow);
        }
        if (barChart) barChart.destroy();
        barChart = new Chart(ctx, {
            type: 'bar', data: data,
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { callback: (value) => (value/1000)+'k' } } }, plugins: { legend: { position: 'top' }, datalabels: { display: false } } }
        });
    }

    function setupLineChart(transactions) {
        const yearSelector = document.getElementById('yearSelector');
        const monthSelector = document.getElementById('monthSelector');
        if (!yearSelector || !monthSelector) return;

        yearSelector.innerHTML = ''; // Kosongkan dulu
        monthSelector.innerHTML = ''; // Kosongkan dulu
        
        const sortedTransactions = [...transactions].sort((a, b) => new Date(b.date) - new Date(a.date));
        const latestTransactionDate = sortedTransactions.length > 0 ? new Date(sortedTransactions[0].date) : new Date();
        const defaultYear = latestTransactionDate.getFullYear();
        const defaultMonth = latestTransactionDate.getMonth();
        
        const years = [...new Set(transactions.map(t => new Date(t.date).getFullYear()))].sort((a,b) => b-a);
        if (years.length === 0) years.push(new Date().getFullYear());
        years.forEach(year => yearSelector.add(new Option(year, year)));
        
        const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        months.forEach((month, index) => monthSelector.add(new Option(month, index)));

        yearSelector.value = defaultYear;
        monthSelector.value = defaultMonth;

        updateLineChart(transactions);
        yearSelector.addEventListener('change', () => updateLineChart(transactions));
        monthSelector.addEventListener('change', () => updateLineChart(transactions));
    }
    
    function updateLineChart(transactions) {
        const ctx = document.getElementById('lineChart')?.getContext('2d');
        if (!ctx) return;
        const year = document.getElementById('yearSelector').value;
        const month = document.getElementById('monthSelector').value;
        const daysInMonth = new Date(year, parseInt(month) + 1, 0).getDate();
        const labels = Array.from({ length: daysInMonth }, (_, i) => i + 1);
        const dataPemasukan = new Array(daysInMonth).fill(0);
        const dataPengeluaran = new Array(daysInMonth).fill(0);

        transactions.forEach(t => {
            const tDate = new Date(t.date);
            if (tDate.getFullYear() == year && tDate.getMonth() == month) {
                const dayOfMonth = tDate.getDate() - 1;
                if (t.variance === 'inflow') dataPemasukan[dayOfMonth] += t.value;
                if (t.variance === 'outflow') dataPengeluaran[dayOfMonth] += t.value;
            }
        });

        if (lineChart) lineChart.destroy();
        lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Pemasukan', data: dataPemasukan, borderColor: chartColors.pemasukan, tension: 0.4 },
                    { label: 'Pengeluaran', data: dataPengeluaran, borderColor: chartColors.linePengeluaran, tension: 0.4 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { callback: (value) => (value/1000)+'k' } } }, plugins: { legend: { position: 'top' }, datalabels: { display: false } } }
        });
    }

    function setupCardNavigation() {
        console.log("Menyiapkan navigasi kartu...");

        // Helper function agar tidak menulis kode berulang
        const addNavigation = (cardId, routeUrl) => {
            const card = document.getElementById(cardId);
            if (card) {
                card.style.cursor = 'pointer'; // Ubah cursor menjadi tangan saat di-hover
                card.addEventListener('click', (event) => {
                    // Mencegah navigasi jika yang diklik adalah link atau tombol di dalam kartu
                    if (event.target.closest('a, button')) {
                        return;
                    }
                    window.location.href = routeUrl;
                });
            } else {
                console.warn(`Elemen kartu dengan ID '${cardId}' tidak ditemukan.`);
            }
        };

        // Daftarkan setiap kartu dengan tujuannya
        // PENTING: Pastikan nama rute ini sudah benar sesuai web.php Anda
        addNavigation('dataWargaCard', '{{ route("data-warga") }}');
        addNavigation('pembayaranCard', '{{ route("manajemen-iuran") }}');
        addNavigation('pemasukanCard', '{{ route("laporan-pemasukan") }}');
        addNavigation('pengeluaranCard', '{{ route("laporan-pengeluaran") }}');
        addNavigation('prokerCard', '{{ route("admin-program-kerja") }}');
        addNavigation('kritikSaranCard', '{{ route("admin-kritik-saran") }}');
    }

    // 5. INISIALISASI
    initDashboard();

    // Panggil fungsi untuk navigasi
    setupCardNavigation();

    // Event listener sidebar
    const menuIcon = document.querySelector('.toggle-sidebar-icon');
    const sidebar = document.querySelector('.admin-sidebar');
    if (menuIcon && sidebar) {
        menuIcon.addEventListener('click', (event) => { event.stopPropagation(); sidebar.classList.toggle('active'); });
        document.addEventListener('click', (event) => { if (sidebar.classList.contains('active') && !sidebar.contains(event.target) && !menuIcon.contains(event.target)) { sidebar.classList.remove('active'); }});
    }
});
</script>
@endsection