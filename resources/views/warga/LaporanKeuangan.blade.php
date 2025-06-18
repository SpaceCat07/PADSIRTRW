@extends('layouts.checkoutNavbar')

@section('content')
    <title>SIMAS - Pembayaran</title>
    <link rel="stylesheet" href="{{ asset('css/lihat-laporan-keuangan.css') }}">
    <?php
    $page = 'dashboard';
    ?>

    <div class="financial-report mt-5 text-center">
        <h1 class="h1 mb-4">Laporan Keuangan</h1>

        <!-- Button Group for Filters -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="btn-group" role="group" aria-label="Filter buttons">
                <button type="button" class="btn-filter active" id="btn-pengeluaran" onclick="showPengeluaran()">
                    Pengeluaran
                </button>
                <button type="button" class="btn-filter" id="btn-pemasukan" onclick="showPemasukan()">
                    Pemasukan
                </button>
            </div>

            <button class="btn btn-select-month" id="btn-pilih-bulan" onclick="selectMonth()">
                Pilih Bulan
            </button>
        </div>

        <!-- Financial Report Table -->
        <div class="table-responsive">
            <table class="table financial-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- Content will be inserted dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-container d-flex justify-content-between align-items-center mt-3">
            <div class="d-flex align-items-center">
                <select class="pagination-select">
                    <option>6</option>
                    <option>10</option>
                    <option>20</option>
                </select>
                <span>per page</span>
            </div>
            <div>
                <span>1 of 1 pages</span>
                <button class="btn-pagination">&#9664;</button>
                <button class="btn-pagination">&#9654;</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // =================================================================
            // 1. SETUP & DEKLARASI ELEMEN
            // =================================================================
            const tableBody = document.getElementById('table-body');
            const btnPengeluaran = document.getElementById('btn-pengeluaran');
            const btnPemasukan = document.getElementById('btn-pemasukan');
            const btnPilihBulan = document.getElementById('btn-pilih-bulan');

            // Pagination elements
            const itemsPerPageSelect = document.querySelector('.pagination-select');
            const prevPageBtn = document.querySelector('.btn-pagination:first-of-type');
            const nextPageBtn = document.querySelector('.btn-pagination:last-of-type');
            const pageInfoSpan = document.querySelector('.pagination-container div:last-child span');

            const token = localStorage.getItem('token');

            // State variables
            let allTransactions = [];
            let displayedTransactions = [];
            let currentPage = 1;
            let itemsPerPage = parseInt(itemsPerPageSelect.value);
            let currentView = 'outflow'; // 'outflow' atau 'inflow'
            let selectedMonth = 'all'; // Format 'YYYY-MM' atau 'all'

            // Cek otentikasi
            if (!token) {
                tableBody.innerHTML = '<tr><td colspan="4">Sesi Anda berakhir. Silakan login kembali.</td></tr>';
                return;
            }

            const axiosInstance = axios.create({
                baseURL: 'https://sirtrw-api.vansite.cloud/api',
                headers: { 'Authorization': `Bearer ${token}` }
            });

            const formatCurrency = (value) => `Rp ${Number(value).toLocaleString('id-ID')}`;
            const formatDate = (dateString) => new Date(dateString).toLocaleDateString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric'
            });

            // =================================================================
            // 2. LOGIKA FETCH, FILTER, DAN RENDER
            // =================================================================

            async function initializePage() {
                try {
                    tableBody.innerHTML = '<tr><td colspan="4">Loading...</td></tr>';

                    const [meResponse, mutasiResponse] = await Promise.all([
                        axiosInstance.get('/me'),
                        axiosInstance.get('/mutasi')
                    ]);

                    const userRtId = meResponse.data.data.warga.rt_id;
                    const rtTransactions = mutasiResponse.data.data.rt || [];

                    // Filter data mutasi hanya untuk RT pengguna dan urutkan dari terbaru
                    allTransactions = rtTransactions
                        .filter(trx => trx.rt_id === userRtId)
                        .sort((a, b) => new Date(b.date) - new Date(a.date));

                    applyFiltersAndRender();

                } catch (error) {
                    console.error("Gagal memuat data:", error);
                    tableBody.innerHTML = '<tr><td colspan="4">Gagal memuat data. Periksa koneksi Anda.</td></tr>';
                }
            }

            function applyFiltersAndRender() {
                let tempTransactions = allTransactions;

                // 1. Filter berdasarkan Pemasukan/Pengeluaran
                tempTransactions = tempTransactions.filter(trx => trx.variance === currentView);

                // 2. Filter berdasarkan Bulan (jika bukan 'all')
                if (selectedMonth !== 'all') {
                    tempTransactions = tempTransactions.filter(trx => trx.date.startsWith(selectedMonth));
                }

                displayedTransactions = tempTransactions;
                currentPage = 1; // Selalu reset ke halaman 1 setiap filter berubah
                renderTable();
                updatePagination();
            }

            function renderTable() {
                tableBody.innerHTML = '';
                if (displayedTransactions.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="4">Tidak ada data yang cocok.</td></tr>';
                    return;
                }

                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const pageItems = displayedTransactions.slice(start, end);

                pageItems.forEach(item => {
                    const row = document.createElement('tr');
                    const nominalClass = item.variance === 'inflow' ? 'text-success' : 'text-danger';
                    const nominalSign = item.variance === 'inflow' ? '+' : '-';

                    row.innerHTML = `
                    <td>#${item.id}</td>
                    <td>${formatDate(item.date)}</td>
                    <td class="${nominalClass}">${nominalSign} ${formatCurrency(item.value)}</td>
                    <td>${item.notes || '-'}</td>
                `;
                    tableBody.appendChild(row);
                });
            }

            function updatePagination() {
                const totalPages = Math.ceil(displayedTransactions.length / itemsPerPage) || 1;
                pageInfoSpan.textContent = `${currentPage} of ${totalPages} pages`;
                prevPageBtn.disabled = currentPage === 1;
                nextPageBtn.disabled = currentPage === totalPages;
            }

            function showMonthPicker() {
                Swal.fire({
                    title: 'Pilih Bulan dan Tahun',
                    html: `
                    <select id="swal-year" class="swal2-select">
                        ${[...new Set(allTransactions.map(t => t.date.substring(0, 4)))].map(year => `<option value="${year}">${year}</option>`).join('')}
                    </select>
                    <select id="swal-month" class="swal2-select">
                        ${["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"].map((month, index) => `<option value="${String(index + 1).padStart(2, '0')}">${month}</option>`).join('')}
                    </select>
                    <button id="swal-reset" class="swal2-confirm swal2-styled" style="background-color: #6c757d; margin-top: 10px;">Tampilkan Semua Bulan</button>
                `,
                    showConfirmButton: true,
                    confirmButtonText: 'Terapkan Filter',
                    didOpen: () => {
                        // Pre-select bulan dan tahun saat ini jika filter sudah ada
                        if (selectedMonth !== 'all') {
                            const [year, month] = selectedMonth.split('-');
                            document.getElementById('swal-year').value = year;
                            document.getElementById('swal-month').value = month;
                        }

                        document.getElementById('swal-reset').addEventListener('click', () => {
                            selectedMonth = 'all';
                            btnPilihBulan.textContent = 'Pilih Bulan';
                            applyFiltersAndRender();
                            Swal.close();
                        });
                    },
                    preConfirm: () => {
                        const year = document.getElementById('swal-year').value;
                        const month = document.getElementById('swal-month').value;
                        return `${year}-${month}`;
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        selectedMonth = result.value;
                        const [year, month] = selectedMonth.split('-');
                        const monthName = new Date(year, month - 1).toLocaleString('id-ID', { month: 'long' });
                        btnPilihBulan.textContent = `${monthName} ${year}`;
                        applyFiltersAndRender();
                    }
                });
            }

            // =================================================================
            // 3. EVENT LISTENERS & INISIALISASI
            // =================================================================
            btnPemasukan.addEventListener('click', () => {
                currentView = 'inflow';
                btnPemasukan.classList.add('active');
                btnPengeluaran.classList.remove('active');
                applyFiltersAndRender();
            });

            btnPengeluaran.addEventListener('click', () => {
                currentView = 'outflow';
                btnPengeluaran.classList.add('active');
                btnPemasukan.classList.remove('active');
                applyFiltersAndRender();
            });

            btnPilihBulan.addEventListener('click', showMonthPicker);

            itemsPerPageSelect.addEventListener('change', (e) => {
                itemsPerPage = parseInt(e.target.value);
                currentPage = 1;
                renderTable();
                updatePagination();
            });

            prevPageBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                    updatePagination();
                }
            });

            nextPageBtn.addEventListener('click', () => {
                const totalPages = Math.ceil(displayedTransactions.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                    updatePagination();
                }
            });

            initializePage();
        });
    </script>

@endsection