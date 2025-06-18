@extends('layouts.dashboardNavbar')

@section('content')
    <title>SIMAS - riwayat</title>
    <link rel="stylesheet" href="{{ asset('css/pembayaran.css') }}">
    <?php
    $page = 'pembayaran'; // or 'program-kerja', 'pembayaran', etc.
        ?>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>

    <div class="payment-history-container">
        <h2>Riwayat Pembayaran</h2>

        <div class="status-filters">
            <button class="filter-button active" data-filter="all">All</button>
            <button class="filter-button" data-filter="success">Complete</button>
            <button class="filter-button" data-filter="pending">Pending</button>
            <button class="filter-button" data-filter="rejected">Rejected</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Pembayaran</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="payment-table">
                <tr>
                    <td colspan="4">Loading...</td>
                </tr>
            </tbody>
        </table>

        <div class="pagination-container">
            <div class="items-per-page">
                <select id="itemsPerPageSelect">
                    <option value="6">6</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
                <span>per page</span>
            </div>

            <div class="pagination">
                <button id="prevPageBtn" disabled>
                    << /button>
                        <span id="currentPage">1</span>
                        <span>of <span id="totalPages">1</span> pages</span>
                        <button id="nextPageBtn" disabled>></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // =================================================================
            // 1. SETUP & DEKLARASI ELEMEN
            // =================================================================
            const paymentTable = document.getElementById('payment-table');
            const filterButtons = document.querySelectorAll('.filter-button');
            const itemsPerPageSelect = document.getElementById('itemsPerPageSelect');
            const prevPageBtn = document.getElementById('prevPageBtn');
            const nextPageBtn = document.getElementById('nextPageBtn');
            const currentPageSpan = document.getElementById('currentPage');
            const totalPagesSpan = document.getElementById('totalPages');

            const token = localStorage.getItem('token');
            let allPayments = []; // Akan menyimpan data yang sudah digabung
            let filteredPayments = [];
            let currentPage = 1;
            let itemsPerPage = parseInt(itemsPerPageSelect.value);

            // Cek otentikasi
            if (!token) {
                paymentTable.innerHTML = '<tr><td colspan="4">Sesi Anda telah berakhir. Silakan login kembali.</td></tr>';
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
            // 2. LOGIKA FETCH & PROSES DATA
            // =================================================================

            /**
             * Mengambil data riwayat dan data iuran, lalu menggabungkannya
             */
            async function fetchAndProcessPayments() {
                try {
                    // Ambil 2 data sekaligus: riwayat pembayaran dan definisi iuran
                    const [historyResponse, iuranResponse] = await Promise.all([
                        axiosInstance.get('/iuran/pay'),
                        axiosInstance.get('/iuran')
                    ]);

                    const rawHistory = historyResponse.data.data || [];
                    const rawIuran = iuranResponse.data.data.rt || [];

                    // Ubah array iuran menjadi Map untuk pencarian cepat (id -> iuran)
                    const iuranMap = new Map(rawIuran.map(iuran => [iuran.id, iuran]));

                    // Gabungkan data: tambahkan nominal dan nama iuran ke setiap riwayat
                    allPayments = rawHistory.map(payment => {
                        const iuranDetail = iuranMap.get(payment.iuran_id);
                        return {
                            id: payment.id,
                            date: payment.created_at,
                            total: iuranDetail ? iuranDetail.value : 0, // Ambil nominal dari Map
                            status: payment.status,
                            iuranName: iuranDetail ? iuranDetail.name : 'Iuran Dihapus'
                        };
                    });

                    // Urutkan pembayaran dari yang terbaru
                    allPayments.sort((a, b) => new Date(b.date) - new Date(a.date));

                    // Terapkan filter awal ("All") dan render halaman
                    applyFilter();

                } catch (error) {
                    console.error('Error fetching payment history:', error);
                    paymentTable.innerHTML = '<tr><td colspan="4">Gagal memuat data.</td></tr>';
                }
            }

            /**
             * Merender baris-baris tabel untuk halaman yang sedang aktif
             */
            function renderTable() {
                paymentTable.innerHTML = '';
                if (filteredPayments.length === 0) {
                    paymentTable.innerHTML = '<tr><td colspan="4">Tidak ada riwayat pembayaran yang cocok.</td></tr>';
                    return;
                }

                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const pageItems = filteredPayments.slice(start, end);

                pageItems.forEach(payment => {
                    const row = document.createElement('tr');
                    // Ganti status 'done' dari API menjadi 'success' untuk mencocokkan class CSS
                    const statusClass = (payment.status === 'done' ? 'success' : payment.status).toLowerCase();

                    row.innerHTML = `
                    <td>#${String(payment.id).padStart(4, '0')}</td>
                    <td>${formatDate(payment.date)}</td>
                    <td>${formatCurrency(payment.total)}</td>
                    <td class="status-${statusClass}">${payment.status}</td>
                `;
                    paymentTable.appendChild(row);
                });
            }

            /**
             * Memperbarui kontrol dan info pagination
             */
            function updatePagination() {
                const totalPages = Math.ceil(filteredPayments.length / itemsPerPage) || 1;
                totalPagesSpan.textContent = totalPages;
                currentPageSpan.textContent = currentPage;

                prevPageBtn.disabled = currentPage === 1;
                nextPageBtn.disabled = currentPage === totalPages;
            }

            /**
             * Menerapkan filter berdasarkan tombol yang aktif
             */
            function applyFilter() {
                const activeFilter = document.querySelector('.filter-button.active').dataset.filter;

                if (activeFilter === 'all') {
                    filteredPayments = allPayments;
                } else {
                    // Mapping 'success' dari tombol ke status 'done' dari API
                    const apiStatus = activeFilter === 'success' ? 'done' : activeFilter;
                    filteredPayments = allPayments.filter(p => p.status.toLowerCase() === apiStatus);
                }

                currentPage = 1;
                renderTable();
                updatePagination();
            }


            // =================================================================
            // 3. EVENT LISTENERS & INISIALISASI
            // =================================================================

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    applyFilter();
                });
            });

            prevPageBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                    updatePagination();
                }
            });

            nextPageBtn.addEventListener('click', () => {
                const totalPages = Math.ceil(filteredPayments.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                    updatePagination();
                }
            });

            itemsPerPageSelect.addEventListener('change', () => {
                itemsPerPage = parseInt(itemsPerPageSelect.value);
                currentPage = 1;
                renderTable();
                updatePagination();
            });

            // Mulai semuanya!
            fetchAndProcessPayments();
        });
    </script>
@endsection