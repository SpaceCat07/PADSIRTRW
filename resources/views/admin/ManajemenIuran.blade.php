@extends('layouts.adminSidebar')

<title>SIMAS - Manajemen Iuran</title>
<link rel="stylesheet" href="{{ asset('css/data-warga.css') }}"> {{-- Kita bisa pakai ulang CSS dari data warga --}}

@section('content')
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt=""></div>
            <h1>Manajemen Iuran</h1>
        </header>
    </div>

    <div class="laporan-container">
        <div class="search-filter">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan nama iuran...">

            {{-- sorting --}}
            <div class="sort-by">
                <label for="iuranSortSelect">Urutkan:</label>
                <select id="iuranSortSelect">
                    <option value="newest_added">Terbaru Ditambahkan</option>
                    <option value="oldest_added">Terlama Ditambahkan</option>
                    <option value="month_asc">Bulan (Jan-Des)</option>
                    <option value="month_desc">Bulan (Des-Jan)</option>
                    <option value="value_desc">Nominal Tertinggi</option>
                    <option value="value_asc">Nominal Terendah</option>
                </select>
            </div>

            <div class="RT-filter-buttons">
                {{-- Tombol tambah data bisa kita arahkan ke route baru nanti --}}
                <a href="#"><button class="action-btn btn-date">+ Tambah Iuran</button></a>
            </div>
        </div>

        {{-- Tabel dengan kolom yang sudah disesuaikan --}}
        <table class="data-warga-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Iuran</th>
                    <th>Bulan (jika berlaku)</th>
                    <th>Jenis Iuran</th>
                    <th>Nominal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="iuranTableBody">
                {{-- Data akan diisi oleh JavaScript --}}
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="pagination">
            <select class="items-per-page">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="15">15</option>
            </select>
            <label>per halaman</label>
            <button class="prev-btn" id="prevPage" disabled>&laquo; Sebelumnya</button>
            <span id="pageInfo">Halaman 1 dari 1</span>
            <button class="next-btn" id="nextPage" disabled>Berikutnya &raquo;</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- 1. DEFINISI ELEMEN & STATE ---
            const API_BASE_URL = 'https://sirtrw-api.vansite.cloud/api';
            const token = localStorage.getItem('token');
            const userRole = localStorage.getItem('userRole')?.toLowerCase() || 'rt';

            const tableBody = document.getElementById('iuranTableBody');
            const searchInput = document.getElementById('searchInput');
            const sortSelect = document.getElementById('iuranSortSelect');
            const itemsPerPageSelect = document.querySelector('.items-per-page');
            const pageInfo = document.getElementById('pageInfo');
            const prevPageBtn = document.getElementById('prevPage');
            const nextPageBtn = document.getElementById('nextPage');
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');
            const tambahIuranBtn = document.querySelector('.btn-date');

            const editUrlTemplate = "{{ route('edit-iuran', ['id' => '__ID__']) }}";

            let allIuran = [];
            let filteredIuran = [];
            let currentPage = 1;
            let itemsPerPage = parseInt(itemsPerPageSelect.value, 10);

            // --- 2. FUNGSI UTAMA (INIT) ---
            async function initializePage() {
                if (!token) {
                    showError('Token tidak ditemukan. Silakan login kembali.');
                    return;
                }
                try {
                    showLoading();

                    const [iuranResponse, meResponse] = await Promise.all([
                        axios.get(`${API_BASE_URL}/iuran`, { headers: { Authorization: `Bearer ${token}` } }),
                        axios.get(`${API_BASE_URL}/me`, { headers: { Authorization: `Bearer ${token}` } })
                    ]);

                    const iuranData = iuranResponse.data.data;
                    const user = meResponse.data.data;
                    const adminRtId = user.warga?.rt_id;
                    const adminRwId = user.warga?.rw_id;

                    const sourceData = (userRole.includes('rt') ? iuranData.rt : iuranData.rw) || [];
                    allIuran = sourceData.filter(item =>
                        userRole.includes('rt') ? item.rt_id === adminRtId : item.rw_id === adminRwId
                    );

                    attachEventListeners();
                    applyFiltersAndSort();

                } catch (error) {
                    console.error('Initialization error:', error);
                    showError(error.response?.data?.message || 'Gagal memuat data iuran.');
                }
            }

            // --- 3. FUNGSI RENDER & UI ---
            function renderTable() {
                tableBody.innerHTML = '';
                if (filteredIuran.length === 0) {
                    showError('Tidak ada data iuran yang ditemukan.');
                    updatePagination();
                    return;
                }

                const pageData = filteredIuran.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);

                pageData.forEach(iuran => {
                    const row = document.createElement('tr');
                    const formattedValue = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(iuran.value);
                    row.innerHTML = `
                        <td>${iuran.id}</td>
                        <td>${iuran.name}</td>
                        <td>${iuran.month || '-'}</td>
                        <td><span class="badge badge-${iuran.variance}">${iuran.variance}</span></td>
                        <td>${formattedValue}</td>
                        <td>
                            <button class="edit-btn" data-id="${iuran.id}" title="Edit"><img src="{{ asset('storage/Edit.png') }}" alt="Edit"></button>
                            <button class="delete-btn" data-id="${iuran.id}" title="Hapus"><img src="{{ asset('storage/trash.png') }}" alt="Hapus"></button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }

            function updatePagination() {
                const totalPages = Math.ceil(filteredIuran.length / itemsPerPage);
                pageInfo.textContent = `Halaman ${currentPage} dari ${totalPages || 1}`;
                prevPageBtn.disabled = currentPage === 1;
                nextPageBtn.disabled = currentPage >= totalPages;
            }

            function updateView() {
                renderTable();
                updatePagination();
            }

            function showLoading() {
                tableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Memuat data...</td></tr>`;
            }

            function showError(message) {
                tableBody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: red;">${message}</td></tr>`;
            }

            // --- 4. FUNGSI LOGIKA & EVENT HANDLER ---
            function applyFiltersAndSort() {
                const searchTerm = searchInput.value.toLowerCase();
                const sortValue = sortSelect.value;
                let tempIuran = allIuran.filter(iuran =>
                    iuran.name?.toLowerCase().includes(searchTerm) ||
                    iuran.month?.toLowerCase().includes(searchTerm)
                );
                const monthOrder = { "January": 1, "February": 2, "March": 3, "April": 4, "May": 5, "June": 6, "July": 7, "August": 8, "September": 9, "October": 10, "November": 11, "December": 12 };
                tempIuran.sort((a, b) => {
                    switch (sortValue) {
                        case 'month_asc': return (monthOrder[a.month] || 0) - (monthOrder[b.month] || 0);
                        case 'month_desc': return (monthOrder[b.month] || 0) - (monthOrder[a.month] || 0);
                        case 'value_desc': return b.value - a.value;
                        case 'value_asc': return a.value - b.value;
                        case 'oldest_added': return new Date(a.created_at) - new Date(b.created_at);
                        default: return new Date(b.created_at) - new Date(a.created_at);
                    }
                });
                filteredIuran = tempIuran;
                currentPage = 1;
                updateView();
            }

            async function deleteIuran(iuranId) {
                const iuranToDelete = allIuran.find(i => i.id === iuranId);
                const iuranName = iuranToDelete ? iuranToDelete.name : `ID ${iuranId}`;

                if (!confirm(`Apakah Anda yakin ingin menghapus iuran "${iuranName}"?`)) {
                    return;
                }

                try {
                    const response = await axios.delete(`${API_BASE_URL}/iuran/${iuranId}`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    });

                    if (response.data.success) {
                        alert(`Iuran "${iuranName}" berhasil dihapus.`);
                        allIuran = allIuran.filter(i => i.id !== iuranId);
                        applyFiltersAndSort();
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    alert(`Gagal menghapus data: ${error.response?.data?.message || error.message}`);
                }
            }

            function attachEventListeners() {
                searchInput.addEventListener('input', applyFiltersAndSort);
                sortSelect.addEventListener('change', applyFiltersAndSort);

                itemsPerPageSelect.addEventListener('change', () => {
                    itemsPerPage = parseInt(itemsPerPageSelect.value, 10);
                    currentPage = 1;
                    updateView();
                });
                prevPageBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; updateView(); } });
                nextPageBtn.addEventListener('click', () => {
                    const totalPages = Math.ceil(filteredIuran.length / itemsPerPage);
                    if (currentPage < totalPages) { currentPage++; updateView(); }
                });

                if (tambahIuranBtn) {
                    tambahIuranBtn.addEventListener('click', () => {
                        window.location.href = "{{ route('tambah-iuran') }}";
                    });
                }

                tableBody.addEventListener('click', (event) => {
                    const target = event.target.closest('button');
                    if (!target) return;

                    const iuranId = parseInt(target.dataset.id, 10);

                    if (target.classList.contains('edit-btn')) {
                        const finalEditUrl = editUrlTemplate.replace('__ID__', iuranId);
                        window.location.href = finalEditUrl;
                    } else if (target.classList.contains('delete-btn')) {
                        // DIUBAH: Memanggil fungsi deleteIuran
                        deleteIuran(iuranId);
                    }
                });

                if (menuIcon && sidebar) {
                    menuIcon.addEventListener('click', (event) => {
                        event.stopPropagation();
                        sidebar.classList.toggle('active');
                    });
                    document.addEventListener('click', (event) => {
                        if (sidebar.classList.contains('active') && !sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                            sidebar.classList.remove('active');
                        }
                    });
                }
            }

            // --- 5. INISIALISASI ---
            initializePage();
        });
    </script>
@endsection