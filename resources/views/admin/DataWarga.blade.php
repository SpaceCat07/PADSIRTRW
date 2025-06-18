@extends('layouts.adminSidebar')

<title>SIMAS - Data Warga</title>
<link rel="stylesheet" href="{{ asset('css/data-warga.css') }}">

@section('content')
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt=""></div>
            <h1>Data Warga</h1>
        </header>
    </div>

    <div class="laporan-container">
        <!-- Search and filter section -->
        <div class="search-filter">
            <input type="text" placeholder="Search...">
            <div class="RT-filter-buttons">
                @if (Auth::check() && Auth::user()->level == 'RW')
                    <select class="action-btn btn-select-rt" name="RT-list" id="RT-list">
                        <option value="RT-1">RT 001</option>
                        <option value="RT-2">RT 002</option>
                        <option value="RT-3">RT 003</option>
                        {{-- @foreach ($rtList as $rt)
                        <option value="{{ $rt }}">{{ $rt }}</option>
                        @endforeach --}}
                    </select>
                @endif

                <button class="action-btn btn-date">+ Tambah Data</button>
            </div>
        </div>

        {{-- Table --}}
        <table class="data-warga-table">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>No.Hp</th>
                    <th>Email</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat Rumah</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <div class="pagination">
            <select class="items-per-page">
                <option value="5">5</option>
                <option value="8" selected>8</option>
                <option value="10">10</option>
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

            // --- 1. DEFINISI ELEMEN & STATE MANAGEMENT ---
            const API_BASE_URL = 'https://sirtrw-api.vansite.cloud/api';
            const token = localStorage.getItem('token');
            const userRole = localStorage.getItem('userRole')?.toLowerCase() || 'rt';
            const tableBody = document.querySelector('.data-warga-table tbody');
            const searchInput = document.querySelector('.search-filter input');
            const rtSelect = document.getElementById('RT-list');

            const itemsPerPageSelect = document.querySelector('.items-per-page');
            const pageInfo = document.getElementById('pageInfo');
            const prevPageBtn = document.getElementById('prevPage');
            const nextPageBtn = document.getElementById('nextPage');

            const tambahDataBtn = document.querySelector('.btn-date');
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');

            let allWarga = [];
            let filteredWarga = [];
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

                    const [wargaResponse, meResponse] = await Promise.all([
                        axios.get(`${API_BASE_URL}/warga`, { headers: { Authorization: `Bearer ${token}` } }),
                        axios.get(`${API_BASE_URL}/me`, { headers: { Authorization: `Bearer ${token}` } })
                    ]);

                    const allWargaFromApi = wargaResponse.data.data || [];
                    const user = meResponse.data.data;
                    const adminRtId = user.warga?.rt_id;
                    const adminRwId = user.warga?.rw_id; // Kita ambil juga rw_id untuk logika RW

                    if (userRole.includes('rt')) {
                        allWarga = allWargaFromApi.filter(w => w.rt_id === adminRtId);
                        if (rtSelect) rtSelect.style.display = 'none';
                    } else if (userRole.includes('rw')) {
                        // Admin RW diasumsikan melihat semua warga di bawahnya.
                        // Jika API tidak otomatis filter, Anda perlu logika tambahan di sini.
                        // Untuk sekarang, kita asumsikan API sudah benar.
                        allWarga = allWargaFromApi;
                        populateRtFilter(allWarga);
                    }

                    filteredWarga = [...allWarga];
                    updateView();
                    attachEventListeners();

                } catch (error) {
                    console.error('Initialization error:', error);
                    showError(error.response?.data?.message || 'Gagal memuat data warga.');
                }
            }

            // --- 3. FUNGSI RENDER & UPDATE UI ---
            function renderTable() {
                tableBody.innerHTML = '';
                if (filteredWarga.length === 0) {
                    showError('Tidak ada data warga yang cocok.');
                    // Pastikan pagination juga update saat tidak ada data
                    updatePagination();
                    return;
                }

                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const pageData = filteredWarga.slice(startIndex, endIndex);

                pageData.forEach(warga => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${warga.nik || '-'}</td>
                        <td>${warga.name || '-'}</td>
                        <td>-</td> 
                        <td>-</td>
                        <td>${warga.birth || '-'}</td>
                        <td>${warga.address || '-'}</td>
                        <td>
                            <button class="edit-btn" data-id="${warga.id}" title="Edit"><img src="{{ asset('storage/Edit.png') }}" alt="Edit"></button>
                            <button class="delete-btn" data-id="${warga.id}" title="Hapus"><img src="{{ asset('storage/trash.png') }}" alt="Hapus"></button>
                            <button class="more-btn" data-id="${warga.id}" title="Lainnya"><img src="{{ asset('storage/three-dots.png') }}" alt="Lainnya"></button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }

            function updatePagination() {
                const totalPages = Math.ceil(filteredWarga.length / itemsPerPage);
                pageInfo.textContent = `Halaman ${currentPage} dari ${totalPages || 1}`;
                prevPageBtn.disabled = currentPage === 1;
                nextPageBtn.disabled = currentPage >= totalPages;
            }

            function updateView() {
                renderTable();
                updatePagination();
            }

            function showLoading() {
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center">Memuat data...</td></tr>`;
            }

            function showError(message) {
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">${message}</td></tr>`;
            }

            // --- 4. FUNGSI LOGIKA & EVENT HANDLER ---
            function handleFilterAndSearch() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedRt = rtSelect ? rtSelect.value : 'all';

                filteredWarga = allWarga.filter(warga => {
                    const rtMatch = (userRole.includes('rw') && selectedRt !== 'all') ? warga.rt_id == selectedRt : true;
                    const searchMatch = (
                        warga.name?.toLowerCase().includes(searchTerm) ||
                        warga.nik?.toLowerCase().includes(searchTerm) ||
                        warga.address?.toLowerCase().includes(searchTerm)
                    );
                    return rtMatch && searchMatch;
                });

                currentPage = 1;
                updateView();
            }

            function populateRtFilter(wargaData) {
                if (!rtSelect) return;
                const rtIds = [...new Set(wargaData.map(w => w.rt_id))].sort((a, b) => a - b);
                rtSelect.innerHTML = '<option value="all">Semua RT</option>';
                rtIds.forEach(rtId => {
                    const option = document.createElement('option');
                    option.value = rtId;
                    option.textContent = `RT ${String(rtId).padStart(3, '0')}`;
                    rtSelect.appendChild(option);
                });
            }

            async function deleteWarga(wargaId) {
                try {
                    const response = await axios.delete(`${API_BASE_URL}/warga/${wargaId}`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    });

                    if (response.data.success) {
                        alert('Data warga berhasil dihapus!');
                        allWarga = allWarga.filter(w => w.id !== wargaId);
                        handleFilterAndSearch();
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    alert(`Gagal menghapus data: ${error.response?.data?.message || error.message}`);
                }
            }

            function attachEventListeners() {
                searchInput.addEventListener('input', handleFilterAndSearch);
                if (rtSelect) rtSelect.addEventListener('change', handleFilterAndSearch);

                itemsPerPageSelect.addEventListener('change', () => {
                    itemsPerPage = parseInt(itemsPerPageSelect.value, 10);
                    currentPage = 1;
                    updateView();
                });

                prevPageBtn.addEventListener('click', () => {
                    if (currentPage > 1) { currentPage--; updateView(); }
                });

                nextPageBtn.addEventListener('click', () => {
                    const totalPages = Math.ceil(filteredWarga.length / itemsPerPage);
                    if (currentPage < totalPages) { currentPage++; updateView(); }
                });

                tambahDataBtn.addEventListener('click', () => {
                    window.location.href = '{{ route("tambah-data-warga") }}';
                });

                tableBody.addEventListener('click', (event) => {
                    const target = event.target.closest('button');
                    if (!target) return;

                    const wargaId = parseInt(target.dataset.id, 10);

                    if (target.classList.contains('edit-btn')) {
                        window.location.href = `/edit-data-warga/admin/${wargaId}`;

                    } else if (target.classList.contains('delete-btn')) {
                        // --- PERUBAHAN DIMULAI DI SINI ---

                        // 1. Cari objek warga di dalam array berdasarkan ID
                        const wargaToDelete = filteredWarga.find(w => w.id === wargaId);

                        // Lakukan pengecekan jika datanya ada
                        if (!wargaToDelete) {
                            alert('Data warga tidak ditemukan!');
                            return;
                        }

                        // 2. Gunakan nama warga di dalam dialog konfirmasi
                        if (confirm(`Apakah Anda yakin ingin menghapus data warga "${wargaToDelete.name}"?`)) {
                            // 3. Panggil fungsi deleteWarga dengan ID, bukan nama
                            deleteWarga(wargaId);
                        }
                        // --- AKHIR PERUBAHAN ---
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