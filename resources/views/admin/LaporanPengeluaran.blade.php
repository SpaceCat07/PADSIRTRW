@extends('layouts.adminSidebar')

@section('content')
    <title>SIMAS - Laporan Pengeluaran</title>
    <link rel="stylesheet" href="{{ asset('css/laporan-mutasi.css') }}">
    {{-- HTML tidak berubah --}}
    <div class="laporan-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
            <h1>Laporan Pengeluaran</h1>
        </header>
        <div class="report-action-buttons">
            <button class="action-btn btn-report" onclick="printReport()">Cetak Laporan</button>
            <a href="{{ route('tambah-data-pengeluaran') }}"><button class="action-btn btn-add-data">+ Tambah
                    Data</button></a>
        </div>
        <div class="sort-by">
            <div class="sort-select-container">
                <label for="sort-select">Sort by:</label>
                <select id="sort-select">
                    <option value="latest">Tanggal Terbaru</option>
                    <option value="oldest">Tanggal Terlama</option>
                </select>
            </div>
        </div>

        {{-- VERSI BARU --}}
        <div class="total-pemasukan-card">
            <p>Saldo Akhir</p>
            {{-- ID diubah agar lebih jelas --}}
            <span id="saldoAkhirAmount">Memuat...</span>
        </div>

        <table id="expenseTable" class="expense-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Nominal</th>
                    <th>Keterangan</th>
                    <th>Bukti Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" style="text-align: center;">Memuat data...</td>
                </tr>
            </tbody>
        </table>
        <div class="pagination">
            <select class="items-per-page">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="15">15</option>
            </select>
            <label>per halaman</label>
            <button id="prevPage" disabled>&laquo; Sebelumnya</button>
            <span id="pageInfo">Halaman 1 dari 1</span>
            <button id="nextPage" disabled>Berikutnya &raquo;</button>
        </div>
    </div>
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <img id="modalImage" src="" alt="Bukti Transaksi">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // === 1. KONFIGURASI & REFERENSI ELEMEN ===
            const API_BASE_URL = 'https://sirtrw-api.vansite.cloud/api';
            const tableBody = document.querySelector('#expenseTable tbody');
            const sortSelect = document.getElementById('sort-select');
            const itemsPerPageSelect = document.querySelector('.items-per-page');
            const pageInfo = document.getElementById('pageInfo');
            const prevPageBtn = document.getElementById('prevPage');
            const nextPageBtn = document.getElementById('nextPage');
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');

            const saldoAkhirAmountSpan = document.getElementById('saldoAkhirAmount');

            let allData = [], currentPage = 1, itemsPerPage = parseInt(itemsPerPageSelect.value, 10);

            // === 2. FUNGSI UTAMA PENGAMBIL DATA ===
            async function fetchData() {
                const token = localStorage.getItem('token');
                let userRole = localStorage.getItem('userRole')?.toLowerCase();

                if (!token || !userRole) {
                    tableBody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: red;">Error: Informasi login tidak ditemukan.</td></tr>';
                    if (saldoAkhirAmountSpan) saldoAkhirAmountSpan.textContent = 'Error';
                    return;
                }

                tableBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Mengambil data...</td></tr>';
                if (saldoAkhirAmountSpan) saldoAkhirAmountSpan.textContent = 'Memuat...';

                try {
                    const mutasiResponse = await axios.get(`${API_BASE_URL}/mutasi`, { headers: { 'Authorization': `Bearer ${token}` } });

                    const mutasiData = mutasiResponse.data.data;
                    const sourceData = (userRole.includes('rt') ? mutasiData.rt : mutasiData.rw) || [];

                    // [LOGIKA BARU] Hitung dan tampilkan Saldo Akhir
                    if (sourceData.length > 0) {
                        // Urutkan SEMUA transaksi (pemasukan & pengeluaran) untuk menemukan yang paling baru
                        const sortedTransactions = [...sourceData].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                        // Ambil saldo 'after' dari transaksi teratas (paling baru)
                        const latestSaldo = sortedTransactions[0].after;
                        const formattedSaldo = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(latestSaldo);
                        if (saldoAkhirAmountSpan) {
                            saldoAkhirAmountSpan.textContent = formattedSaldo;
                        }
                    } else {
                        if (saldoAkhirAmountSpan) {
                            saldoAkhirAmountSpan.textContent = 'Rp 0';
                        }
                    }

                    // Saring data untuk menampilkan HANYA pengeluaran di tabel
                    allData = sourceData.filter(item => item.variance === 'outflow');

                    if (allData.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Tidak ada data pengeluaran ditemukan.</td></tr>';
                    } else {
                        sortTable();
                    }

                } catch (error) {
                    console.error('Kesalahan saat fetch data:', error);
                    const errorMessage = error.response?.data?.message || error.message || "Terjadi kesalahan";
                    tableBody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: red;"><strong>Gagal Memuat Data:</strong><br>${errorMessage}</td></tr>`;
                    if (saldoAkhirAmountSpan) saldoAkhirAmountSpan.textContent = 'Error';
                } finally {
                    updatePaginationControls();
                }
            }

            // === 3. FUNGSI-FUNGSI BANTUAN (TIDAK ADA PERUBAHAN) ===
            function renderTable() { tableBody.innerHTML = ""; if (allData.length === 0 && currentPage === 1) { tableBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Tidak ada data pengeluaran ditemukan.</td></tr>'; return } const startIndex = (currentPage - 1) * itemsPerPage; const endIndex = startIndex + itemsPerPage; const pageData = allData.slice(startIndex, endIndex); pageData.forEach(item => { const row = document.createElement('tr'); const formattedValue = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(item.value); row.innerHTML = `<td>${item.id}</td><td>${item.date}</td><td>${formattedValue}</td><td>${item.notes || "-"}</td><td><button class="btn-view" disabled>Lihat</button></td>`; tableBody.appendChild(row) }) }
            function updatePaginationControls() { const totalPages = Math.ceil(allData.length / itemsPerPage); pageInfo.textContent = `Halaman ${currentPage} dari ${totalPages || 1}`; prevPageBtn.disabled = currentPage === 1; nextPageBtn.disabled = currentPage === totalPages || totalPages === 0 }
            function sortTable() { const sortOrder = sortSelect.value; allData.sort((a, b) => { const dateA = new Date(a.date); const dateB = new Date(b.date); return sortOrder === 'latest' ? dateB - dateA : dateA - dateB }); currentPage = 1; renderTable(); updatePaginationControls() }

            // === 4. EVENT LISTENERS ===
            sortSelect.addEventListener('change', sortTable); itemsPerPageSelect.addEventListener('change', e => { itemsPerPage = parseInt(e.target.value, 10); sortTable() }); prevPageBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderTable(); updatePaginationControls() } }); nextPageBtn.addEventListener('click', () => { const totalPages = Math.ceil(allData.length / itemsPerPage); if (currentPage < totalPages) { currentPage++; renderTable(); updatePaginationControls() } });
            if (menuIcon && sidebar) { menuIcon.addEventListener('click', event => { event.stopPropagation(); sidebar.classList.toggle('active') }); document.addEventListener('click', event => { if (sidebar.classList.contains('active') && !sidebar.contains(event.target) && !menuIcon.contains(event.target)) { sidebar.classList.remove('active') } }) }
            window.printReport = async function () {
                const printButton = document.querySelector('.btn-report');
                printButton.disabled = true;
                printButton.innerText = 'Mencetak...';

                const token = localStorage.getItem('token');
                const userRole = localStorage.getItem('userRole')?.toLowerCase();
                const API_URL = 'https://sirtrw-api.vansite.cloud/api/mutasi';

                if (!token || !userRole) {
                    alert("Gagal mencetak: Informasi login tidak ditemukan.");
                    printButton.disabled = false;
                    printButton.innerText = 'Cetak Laporan';
                    return;
                }

                try {
                    // 1. Ambil data mentah terbaru dari API
                    const response = await axios.get(API_URL, { headers: { 'Authorization': `Bearer ${token}` } });
                    if (!response.data.success) {
                        throw new Error("Gagal mengambil data laporan dari server.");
                    }

                    const transactions = (userRole.includes('rt') ? response.data.data.rt : response.data.data.rw) || [];

                    if (transactions.length === 0) {
                        alert("Tidak ada data untuk dicetak.");
                        return;
                    }

                    // 2. Olah data
                    const pemasukanData = transactions.filter(item => item.variance === 'inflow');
                    const pengeluaranData = transactions.filter(item => item.variance === 'outflow');

                    const totalPemasukan = pemasukanData.reduce((sum, item) => sum + item.value, 0);
                    const totalPengeluaran = pengeluaranData.reduce((sum, item) => sum + item.value, 0);

                    // Saldo akhir diambil dari transaksi paling baru
                    const saldoAkhir = transactions.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))[0].after;

                    const formatCurrency = (value) => `Rp ${Number(value).toLocaleString('id-ID', { minimumFractionDigits: 0 })}`;

                    // 3. Buat konten HTML untuk dicetak
                    let printContent = `
                        <html>
                        <head>
                            <title>Laporan Keuangan</title>
                            <style>
                                body { font-family: Arial, sans-serif; font-size: 12px; }
                                h1, h2 { text-align: center; color: #333; }
                                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                                th { background-color: #f2f2f2; }
                                .header-pemasukan { background-color: #d4edda; color: #155724; }
                                .header-pengeluaran { background-color: #f8d7da; color: #721c24; }
                                .summary-table { width: 50%; margin: 20px auto; border: none; }
                                .summary-table td { border: 1px solid #ddd; padding: 10px; font-weight: bold; }
                                .summary-label { text-align: right; }
                                .summary-value { text-align: left; }
                                .total-pemasukan { color: #155724; }
                                .total-pengeluaran { color: #721c24; }
                                .saldo-akhir { color: #004085; background-color: #cce5ff; }
                            </style>
                        </head>
                        <body>
                            <h1>Laporan Keuangan Gabungan</h1>

                            <h2>Pemasukan</h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:5%;">ID</th>
                                        <th style="width:15%;">Tanggal</th>
                                        <th>Keterangan</th>
                                        <th style="width:20%;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${pemasukanData.map(item => `
                                        <tr>
                                            <td>${item.id}</td>
                                            <td>${item.date}</td>
                                            <td>${item.notes || '-'}</td>
                                            <td>${formatCurrency(item.value)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>

                            <h2>Pengeluaran</h2>
                            <table>
                                 <thead>
                                    <tr>
                                        <th style="width:5%;">ID</th>
                                        <th style="width:15%;">Tanggal</th>
                                        <th>Keterangan</th>
                                        <th style="width:20%;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${pengeluaranData.map(item => `
                                        <tr>
                                            <td>${item.id}</td>
                                            <td>${item.date}</td>
                                            <td>${item.notes || '-'}</td>
                                            <td>${formatCurrency(item.value)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>

                            <h2>Ringkasan</h2>
                            <table class="summary-table">
                                <tr>
                                    <td class="summary-label">Total Pemasukan:</td>
                                    <td class="summary-value total-pemasukan">${formatCurrency(totalPemasukan)}</td>
                                </tr>
                                <tr>
                                    <td class="summary-label">Total Pengeluaran:</td>
                                    <td class="summary-value total-pengeluaran">${formatCurrency(totalPengeluaran)}</td>
                                </tr>
                                <tr>
                                    <td class="summary-label saldo-akhir">Saldo Akhir:</td>
                                    <td class="summary-value saldo-akhir">${formatCurrency(saldoAkhir)}</td>
                                </tr>
                            </table>

                        </body>
                        </html>
                    `;

                    // 4. Buka jendela baru dan cetak
                    const printWindow = window.open('', '', 'width=800,height=600');
                    printWindow.document.write(printContent);
                    printWindow.document.close();
                    printWindow.focus();
                    printWindow.print();

                } catch (error) {
                    alert("Gagal mencetak laporan: " + error.message);
                } finally {
                    printButton.disabled = false;
                    printButton.innerText = 'Cetak Laporan';
                }
            };

            // === 5. PANGGILAN AWAL ===
            fetchData();
        });
    </script>
@endsection