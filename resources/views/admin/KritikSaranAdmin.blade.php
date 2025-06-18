@extends('layouts.adminSidebar')

<title>SIMAS - kritik dan saran</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

@section('content')
    {{-- Header --}}
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt=""></div>
            <h1>Kritik dan Saran</h1>
        </header>
    </div>

    {{-- Kritik dan Saran List --}}
    <div class="kritik-saran-container" id="kritikSaranContainer" data-user-icon="{{ asset('storage/maleUser.png') }}">
        <div class="status-filters">
            <button class="filter-button active" data-filter="all">All</button>
            <button class="filter-button" data-filter="unread">Unread</button>
            <button class="filter-button" data-filter="complete">Complete</button>
        </div>
        <div id="kritikSaranList">
            <div>Loading kritik dan saran...</div>
        </div>
    </div>


    {{-- Popup --}}
    <div class="popup-container" id="messagePopup" style="display:none;">
        <div class="popup">
            <button class="close-popup" id="closePopup">&times;</button>
            <div class="popup-header">
                <span id="messageStatus">Unread</span>
                <h2>PESAN KRITIK DAN SARAN</h2>
            </div>
            <input type="text" id="popupName" class="popup-input" readonly>
            <textarea id="popupMessage" class="popup-textarea" readonly></textarea>
            <div class="popup-buttons">
                <button class="popup-button complete" id="markComplete">TANDAI SELESAI</button>
                <button class="popup-button unread" id="markUnread">TANDAI BELUM DIBACA</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // =================================================================
            // 1. SETUP & DEKLARASI ELEMEN
            // =================================================================
            // Elemen UI
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');
            const kritikSaranList = document.getElementById('kritikSaranList');
            const filterButtons = document.querySelectorAll('.filter-button');
            const popupContainer = document.getElementById('messagePopup');
            const closePopup = document.getElementById('closePopup');
            const popupName = document.getElementById('popupName');
            const popupMessage = document.getElementById('popupMessage');
            const messageStatus = document.getElementById('messageStatus');
            const markComplete = document.getElementById('markComplete');
            const markUnread = document.getElementById('markUnread');

            // Variabel state
            const token = localStorage.getItem('token');
            const userIcon = document.getElementById('kritikSaranContainer').dataset.userIcon;
            let allKritikData = []; // Menyimpan data yang sudah difilter sesuai role
            let currentItem = null; // Menyimpan item yang aktif di popup

            // Cek otentikasi
            if (!token) {
                kritikSaranList.innerHTML = '<div>Sesi Anda berakhir. Silakan login kembali.</div>';
                return;
            }

            // Instance Axios dengan otentikasi
            const axiosInstance = axios.create({
                baseURL: 'https://sirtrw-api.vansite.cloud/api',
                headers: { Authorization: `Bearer ${token}` }
            });

            // =================================================================
            // 2. FUNGSI-FUNGSI UTAMA
            // =================================================================

            /**
             * [FUNGSI UTAMA] Mengambil data admin dan kritik, lalu memfilter sesuai role
             */
            async function initializePage() {
                try {
                    kritikSaranList.innerHTML = '<div>Loading kritik dan saran...</div>';

                    const [meResponse, kritikResponse] = await Promise.all([
                        axiosInstance.get('/me'),
                        axiosInstance.get('/kritik')
                    ]);

                    // [PENTING] Cetak data profil admin ke console untuk debugging
                    console.log("Data Profil Admin (/me):", meResponse.data.data);

                    const adminProfile = meResponse.data.data;
                    const adminRole = adminProfile.role; // Asumsi role ada di 'data.role'
                    const allKritikFromServer = kritikResponse.data.data;

                    if (adminRole === 'Admin_RW') {
                        // Jika Admin RW, tampilkan semua kritik
                        allKritikData = allKritikFromServer;
                        console.log("Role terdeteksi: Admin RW. Menampilkan semua kritik.");
                    } else if (adminRole === 'Admin_RT') {
                        // Jika Admin RT, filter berdasarkan rt_id
                        const adminRtId = adminProfile.warga.rt_id; // Asumsi rt_id ada di 'data.warga.rt_id'
                        if (!adminRtId) throw new Error("Tidak dapat menemukan RT ID untuk Admin RT.");

                        allKritikData = allKritikFromServer.filter(item => item.rt_id === adminRtId);
                        console.log(`Role terdeteksi: Admin RT. Menampilkan kritik untuk RT ID: ${adminRtId}`);
                    } else {
                        // Jika role tidak dikenali, jangan tampilkan apa-apa
                        allKritikData = [];
                        console.warn(`Role tidak dikenali: ${adminRole}`);
                    }

                    // Urutkan dari yang terbaru
                    allKritikData.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                    applyFilterAndRender(); // Render data yang sudah difilter

                } catch (error) {
                    console.error('Error saat inisialisasi halaman:', error);
                    kritikSaranList.innerHTML = `<div>Gagal memuat data. ${error.message}</div>`;
                }
            }

            /**
             * Merender daftar kritik ke dalam HTML
             */
            function renderKritikSaran(dataToRender) {
                kritikSaranList.innerHTML = '';
                if (!dataToRender || dataToRender.length === 0) {
                    kritikSaranList.innerHTML = '<div>Tidak ada data untuk ditampilkan.</div>';
                    return;
                }

                dataToRender.forEach(item => {
                    const card = document.createElement('div');
                    card.className = `kritik-saran-card ${item.status === 'yet' ? 'unread' : ''}`;
                    const senderName = item.user?.warga?.name || `User ID: ${item.user_id || 'Anonim'}`;
                    const targetRT = `RT ${String(item.rt_id).padStart(2, '0')}`; // Tampilkan RT tujuan

                    card.innerHTML = `
                    <div class="profile-icon"><img src="${userIcon}" alt="User Icon"></div>
                    <div class="kritik-saran-content">
                        <div class="kritik-header">
                            <h4>${senderName}</h4>
                            <span class="kritik-target">${targetRT}</span>
                        </div>
                        <p>${item.text}</p>
                        <small>${new Date(item.created_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' })}</small>
                    </div>
                `;
                    card.addEventListener('click', () => openPopup(item));
                    kritikSaranList.appendChild(card);
                });
            }

            /**
             * Menerapkan filter status (All, Unread, Complete) dan merender ulang
             */
            function applyFilterAndRender() {
                const activeFilter = document.querySelector('.filter-button.active').dataset.filter;
                let filteredData;

                if (activeFilter === 'all') {
                    filteredData = allKritikData;
                } else {
                    const filterStatus = activeFilter === 'unread' ? 'yet' : (activeFilter === 'complete' ? 'done' : activeFilter);
                    filteredData = allKritikData.filter(item => item.status === filterStatus);
                }
                renderKritikSaran(filteredData);
            }

            /**
             * Membuka popup dan mengisi datanya
             */
            function openPopup(item) {
                currentItem = item;
                const senderName = item.user?.warga?.name || `User ID: ${item.user_id || 'Anonim'}`;
                popupName.value = senderName;
                popupMessage.value = item.text;
                messageStatus.textContent = item.status === 'yet' ? 'Belum Dibaca' : 'Selesai';
                popupContainer.style.display = 'flex';
            }

            /**
             * Mengirim perubahan status ke API
             */
            async function updateStatus(newStatus) {
                if (!currentItem) return;
                try {
                    await axiosInstance.put(`/kritik/${currentItem.id}`, { status: newStatus });
                    const itemIndex = allKritikData.findIndex(item => item.id === currentItem.id);
                    if (itemIndex > -1) {
                        allKritikData[itemIndex].status = newStatus;
                    }
                    popupContainer.style.display = 'none';
                    applyFilterAndRender();
                } catch (error) {
                    console.error('Error updating status:', error);
                    alert('Gagal mengubah status pesan.');
                }
            }

            // =================================================================
            // 3. EVENT LISTENERS & INISIALISASI
            // =================================================================

            // Toggle sidebar
            menuIcon.addEventListener('click', (event) => {
                event.stopPropagation();
                sidebar.classList.toggle('active');
            });
            document.addEventListener('click', (event) => {
                if (sidebar && !sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            });

            // Event listener untuk tombol-tombol filter
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    applyFilterAndRender();
                });
            });

            // Event listener untuk popup
            closePopup.addEventListener('click', () => popupContainer.style.display = 'none');
            markComplete.addEventListener('click', () => updateStatus('done'));
            markUnread.addEventListener('click', () => updateStatus('yet'));

            // Panggil fungsi utama untuk memulai pengambilan data
            initializePage();
        });
    </script>
@endsection