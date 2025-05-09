@extends('layouts.adminSidebar')

<title>SIMAS - dashboard</title>
<link rel="stylesheet" href="{{ asset('css/dashboard-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/charts.css') }}">

@section('content')
    <div class="admin-dashboard-container">
        <div class="header-container">
            <header class="dashboard-header">
                <div class="header-admin-left">
                    <p id="adminGreeting">Hi, Admin</p>
                    <h1>Welcome Back!</h1>
                </div>
                <div class="header-admin-dropdown text-end me-3">
                    <a href="#" class="profile-picture link-body-emphasis text-decoration-none" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <p id="adminName">Admin</p>
                        <img src="https://github.com/mdo.png" alt="mdo" width="38" height="38"
                            class="rounded-circle ms-2 align-middle">
                    </a>
                    <ul class="dropdown-menu text-small">
                        <li><a class="dropdown-item" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="{{ route('edit.profil') }}">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form class="text-center justify-content-center" action="{{ route('logout') }}" method="post">
                                @csrf
                                <button class="profile-logout-btn" type="submit">Sign out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </header>
        </div>

        <div class="dashboard-content">

            <!-- Data Warga -->
            <div class="admin-card data-warga" id="dataWargaCard" data-url="/data-warga/admin">
                <div class="title-arrow">
                    <h3 id="dataWargaTitle">Data Warga</h3>
                    <img src="{{ asset('storage/arrow.png') }}" alt="">
                </div>
                <div class="family-data-container">
                    <img src="{{ asset('storage/family.png') }}" alt="">
                    <p id="familyCount">Loading...</p>
                </div>
            </div>

            <!-- Pembayaran Butuh Konfirmasi -->
            <div class="admin-card manajemen-iuran" id="manajemenIuranCard" data-url="/manajemen-iuran/admin">
                <div class="title-arrow">
                    <h3>Pembayaran butuh konfirmasi</h3>
                    <img src="{{ asset('storage/arrow.png') }}" alt="">
                </div>
                <ul id="pembayaranList">
                    <li>Loading...</li>
                </ul>
            </div>

            <!-- Pemasukan dan Pengeluaran -->
            <div class="admin-card pemasukan-pengeluaran">
                <h3>Perbandingan Pemasukan dan Pengeluaran</h3>
                <div class="keuangan-chart">
                    <div class="keuangan-info">
                        <div class="keuangan-info saldo" id="saldoInfo" data-url="/laporan-saldo/admin">
                            <div>
                                <img src="{{ asset('storage/deposit.png') }}" alt="">
                            </div>
                            <div>
                                <label for="saldo" class="keuangan-info-label">Saldo</label>
                                <p id="saldoAmount">Loading...</p>
                            </div>
                        </div>
                        <div class="keuangan-info pemasukan" id="pemasukanInfo" data-url="/laporan-pemasukan/admin">
                            <div>
                                <img src="{{ asset('storage/deposit.png') }}" alt="">
                            </div>
                            <div>
                                <label for="pemasukan" class="keuangan-info-label">Pemasukan</label>
                                <p id="pemasukanAmount">Loading...</p>
                            </div>
                        </div>
                        <div class="keuangan-info pengeluaran" id="pengeluaranInfo" data-url="/laporan-pengeluaran/admin">
                            <div>
                                <img src="{{ asset('storage/getCash.png') }}" alt="">
                            </div>
                            <div>
                                <label for="pengeluaran" class="keuangan-info-label">Pengeluaran</label>
                                <p id="pengeluaranAmount">Loading...</p>
                            </div>
                        </div>
                    </div>
                    <!-- Pie Chart Perbandingan -->
                    <div class="piechart">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik -->
            <div class="admin-card grafik">
                <div class="print-chart-container">
                    <h3>Grafik Laporan Pengeluaran</h3>
                    <button id="printButton" class="print-chart">Cetak</button>
                </div>

                {{-- barchart --}}
                <div class="barchart">
                    <canvas id="barChart"></canvas>
                </div>

                {{-- Linechart --}}
                <div class="linechart">
                    <div class="chart-controls">
                        <div class="custom-select">
                            <select id="yearSelector" class="form-select">
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024" selected>2024</option>
                            </select>
                        </div>

                        <div class="custom-select">
                            <select id="intervalSelector" class="form-select">
                                <option value="7-days" selected>7 Days</option>
                                <option value="month">Month</option>
                                <option value="yoy">Year-over-Year</option>
                            </select>
                        </div>
                    </div>

                    <canvas id="lineChart"></canvas>
                </div>

            </div>

            <!-- Program Kerja -->
            <div class="admin-card program-kerja" data-url="/program-kerja/admin">
                <h3>Program Kerja Mendatang</h3>
                <div class="program-card admin" data-date="2023-10-28">
                    <div class="admin-card date">
                        <div class="month">OCTOBER</div>
                        <div class="day">28</div>
                        <div class="day-of-week">Monday</div>
                    </div>
                    <div class="details">
                        <h3>Posyandu Balita dan Lansia Sehat</h3>
                    </div>
                </div>
                <div class="program-card admin" data-date="2023-10-28">
                    <div class="admin-card date">
                        <div class="month">SEPTEMBER</div>
                        <div class="day">15</div>
                        <div class="day-of-week">Friday</div>
                    </div>
                    <div class="details">
                        <h3>Program Imunisasi Anak</h3>
                    </div>
                </div>
            </div>

            <!-- Pesan Kritik dan Saran -->
            <div class="admin-card kritik-saran" data-url="/kritik-saran/admin">
                <h3>Pesan Kritik & Saran</h3>
                <div class="kritik-saran-card unread admin">
                    <div class="profile-icon admin">
                        <img src="{{ asset('storage/maleUser.png') }}" alt="">
                        <h5>John Doe</h5>
                    </div>
                    <div class="kritik-saran-content">
                        <p>Saran untuk kegiatan kedepannya dapat dimulai tepat waktu sesuai dengan jadwal yang telah
                            diinformasikan sebelumnya.</p>
                    </div>
                </div>

                <div class="kritik-saran-card unread admin">
                    <div class="profile-icon admin">
                        <img src="{{ asset('storage/maleUser.png') }}" alt="">
                        <h5>Jane Doe</h5>
                    </div>
                    <div class="kritik-saran-content">
                        <p>Tolong lebih tegas pada warga yang suka memasang musik dengan volume tinggi di malam hari karena
                            dapat mengganggu istirahat warga lainnya.</p>
                    </div>
                </div>

                <div class="kritik-saran-card unread admin">
                    <div class="profile-icon admin">
                        <img src="{{ asset('storage/maleUser.png') }}" alt="">
                        <h5>John Doe</h5>
                    </div>
                    <div class="kritik-saran-content">
                        <p>Terlalu banyak iuran-iuran tambahan, uang saya habis.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        // Sidebar initialization
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.admin-sidebar');

            // Function to handle sidebar visibility based on window width
            function handleSidebarVisibility() {
                if (window.innerWidth <= 1400) {
                    sidebar.classList.remove('active'); // Hide sidebar
                } else {
                    sidebar.classList.add('active'); // Show sidebar
                }
            }

            // Initial check on page load
            handleSidebarVisibility();

            // Add event listener for window resize
            window.addEventListener('resize', handleSidebarVisibility);
        });

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

        // Fetch dashboard data from API
        document.addEventListener('DOMContentLoaded', () => {
            axios.get('/api/admin-dashboard-data')
                .then(response => {
                    const data = response.data;

                    // Update greeting and admin name
                    document.getElementById('adminGreeting').textContent = `Hi, Admin ${data.adminName}`;
                    document.getElementById('adminName').textContent = data.adminName;

                    // Update Data Warga
                    document.getElementById('dataWargaTitle').textContent = data.userRole === 'Admin_RT' ? 'Data Warga' : 'Data RT';
                    document.querySelector('.family-data-container p').textContent = data.familyCount;

                    // Update Pembayaran Butuh Konfirmasi
                    const pembayaranList = document.getElementById('pembayaranList');
                    pembayaranList.innerHTML = '';
                    if (data.payments && data.payments.length > 0) {
                        data.payments.forEach(payment => {
                            const li = document.createElement('li');
                            li.className = 'admin-card pembayaran-list';
                            li.innerHTML = `<span>No. Rekening ${payment.accountNumber}</span><span>Rp ${payment.amount}</span>`;
                            pembayaranList.appendChild(li);
                        });
                    } else {
                        pembayaranList.innerHTML = '<li>No payments to confirm.</li>';
                    }

                    // Update Pemasukan dan Pengeluaran
                    document.getElementById('saldoAmount').textContent = `Rp ${data.totalSaldo.toLocaleString()}`;
                    document.getElementById('pemasukanAmount').textContent = `Rp ${data.totalPemasukan.toLocaleString()}`;
                    document.getElementById('pengeluaranAmount').textContent = `Rp ${data.totalPengeluaran.toLocaleString()}`;

                    // Pie Chart
                    const pieCtx = document.getElementById('pieChart').getContext('2d');
                    new Chart(pieCtx, {
                        type: 'pie',
                        data: {
                            labels: data.piechartData.labels,
                            datasets: [{
                                data: data.piechartData.data,
                                backgroundColor: [
                                    chartColors.piePemasukanBg,
                                    chartColors.piePengeluaranBg,
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

                    // Bar Chart
                    const barCtx = document.getElementById('barChart').getContext('2d');
                    new Chart(barCtx, {
                        type: 'bar',
                        data: {
                            labels: data.barchartData.labels,
                            datasets: [
                                {
                                    label: 'Pemasukan',
                                    data: data.barchartData.datasets[0].data,
                                    backgroundColor: chartColors.barPemasukanBg,
                                },
                                {
                                    label: 'Pengeluaran',
                                    data: data.barchartData.datasets[1].data,
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
                    const lineCtx = document.getElementById('lineChart').getContext('2d');
                    new Chart(lineCtx, {
                        type: 'line',
                        data: {
                            labels: data.linechartData.labels,
                            datasets: [
                                {
                                    label: 'This Month',
                                    data: data.linechartData.datasets[0].data,
                                    backgroundColor: chartColors.lineThisMonthBg,
                                    borderColor: chartColors.lineThisMonthBorder,
                                    fill: true,
                                },
                                {
                                    label: 'Last Month',
                                    data: data.linechartData.datasets[1].data,
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
                })
                .catch(error => {
                    console.error('Error fetching admin dashboard data:', error);
                });
        });
    </script>
@endsection
