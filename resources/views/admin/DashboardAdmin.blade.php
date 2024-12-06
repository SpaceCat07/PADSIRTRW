@extends('layouts.adminSidebar')

@php
    use App\Models\Warga;
@endphp

@section('content')
    <div class="admin-dashboard-container">
        <div class="header-container">
            <header class="dashboard-header">
                <div class="header-admin-left">
                    @if (Auth::user()->role == 'Admin_RT')
                        <p>Hi, Admin RT {{ Warga::where('id_warga', Auth::user()->id_warga)->first()->nama }}</p>
                    @elseif (Auth::user()->role == 'Admin_RW')
                        <p>Hi, Admin RW {{ Warga::where('id_warga', Auth::user()->id_warga)->first()->nama }}</p>
                    @endif
                    <h1>Welcome Back!</h1>
                </div>
                <div class="header-admin-dropdown text-end me-3">
                    <a href="#" class="profile-picture link-body-emphasis text-decoration-none" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <p>{{ Warga::where('id_warga', Auth::user()->id_warga)->first()->nama }}</p>
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
                            <!-- <a class="dropdown-item" href="{{ route('logout') }}">Sign out</a> -->
                        </li>
                    </ul>
                </div>
            </header>

            {{-- <form action="{{ route('logout') }}" method="post"> @csrf
                <button type="submit">Sign out</button>
            </form> --}}
        </div>


        <div class="dashboard-content">

            <!-- Data Warga -->
            <div class="admin-card data-warga" data-url="/data-warga/admin">
                @if (Auth::user()->role == 'Admin_RT')
                    <div class="title-arrow">
                        <h3>Data Warga</h3>
                        <img src="{{ asset('storage/arrow.png') }}" alt="">
                    </div>
                    <div class="family-data-container">
                        <img src="{{ asset('storage/family.png') }}" alt="">
                        <p>20 Keluarga</p>
                    </div>
                @elseif (Auth::user()->role == 'Admin_RW')
                    <div class="title-arrow">
                        <h3>Data RT</h3>
                        <img src="{{ asset('storage/arrow.png') }}" alt="">
                    </div>
                    <div class="family-data-container">
                        <img src="{{ asset('storage/family.png') }}" alt="">
                        <p>RT 001</p>
                    </div>
                    <div class="family-data-container">
                        <img src="{{ asset('storage/family.png') }}" alt="">
                        <p>RT 002</p>
                    </div>
                @endif

            </div>

            <!-- Pembayaran Butuh Konfirmasi -->
            <div class="admin-card manajemen-iuran" data-url="/manajemen-iuran/admin">
                <div class="title-arrow">
                    <h3>Pembayaran butuh konfirmasi</h3>
                    <img src="{{ asset('storage/arrow.png') }}" alt="">
                </div>
                <ul>
                    <div class="admin-card pembayaran-list">
                        <span>No. Rekening 1871xx87xx</span>
                        <span>Rp 35.000</span>
                    </div>
                    <div class="admin-card pembayaran-list">
                        <span>No. Rekening 1871xx87xx</span>
                        <span>Rp 15.000</span>
                    </div>
                </ul>
            </div>

            <!-- Pemasukan dan Pengeluaran -->
            <div class="admin-card pemasukan-pengeluaran">
                <h3>Perbandingan Pemasukan dan Pengeluaran</h3>
                <div class="keuangan-chart">
                    <div class="keuangan-info">
                        <div class="keuangan-info pemasukan" data-url="/laporan-pemasukan/admin">
                            <div>
                                <img src="{{ asset('storage/deposit.png') }}" alt="">
                            </div>
                            <div>
                                <label for="pemasukan" class="keuangan-info-label">Pemasukan</label>
                                <p>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="keuangan-info pengeluaran" data-url="/laporan-pengeluaran/admin">
                            <div>
                                <img src="{{ asset('storage/getCash.png') }}" alt="">
                            </div>
                            <div>
                                <label for="pengeluaran" class="keuangan-info-label">Pengeluaran</label>
                                <p>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
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
                                <option value="2022" {{ $year == 2022 ? 'selected' : '' }}>2022</option>
                                <option value="2023" {{ $year == 2023 ? 'selected' : '' }}>2023</option>
                                <option value="2024" {{ $year == 2024 ? 'selected' : '' }}>2024</option>
                            </select>
                        </div>

                        <div class="custom-select">
                            <select id="intervalSelector" class="form-select">
                                <option value="7-days" {{ $interval == '7-days' ? 'selected' : '' }}>7 Days</option>
                                <option value="month" {{ $interval == 'month' ? 'selected' : '' }}>Month</option>
                                <option value="yoy" {{ $interval == 'yoy' ? 'selected' : '' }}>Year-over-Year</option>
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

        const piechart_data = @json($piechart_data);
        const barchart_data = @json($barchart_data);
        const linechart_data = @json($linechart_data);
        const totalPemasukan = @json($totalPemasukan);
        const totalPengeluaran = @json($totalPengeluaran);
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

        // Print chart
        document.getElementById('printButton').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default button behavior

            // Create a container to hold the charts
            const chartsContainer = document.createElement('div');
            chartsContainer.style.display = 'flex';
            chartsContainer.style.flexDirection = 'column';
            chartsContainer.style.alignItems = 'center';

            // Get the bar chart and line chart canvas elements
            const barChartCanvas = document.getElementById('barChart');
            const lineChartCanvas = document.getElementById('lineChart');

            // Append the charts to the container
            chartsContainer.appendChild(barChartCanvas.cloneNode(true));
            chartsContainer.appendChild(lineChartCanvas.cloneNode(true));

            // Use html2canvas to capture the charts
            html2canvas(chartsContainer).then(canvas => {
                // Create a new window for printing
                const printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Print Charts</title></head><body>');
                printWindow.document.write('<h1>Grafik Laporan Pengeluaran</h1>');
                printWindow.document.body.appendChild(canvas);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            });
        });

        // Redirects
        document.addEventListener('DOMContentLoaded', () => {
            // Select all admin cards that need click events
            const cards = document.querySelectorAll('.admin-card, .keuangan-info');

            // Add click event listener to each card
            cards.forEach(card => {
                card.addEventListener('click', () => {
                    const url = card.getAttribute('data-url'); // Get the URL from data attribute
                    if (url) {
                        window.location.href = url; // Redirect to the specified URL
                    }
                });
            });
        });
    </script>
@endsection
