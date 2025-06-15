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
<script>
    // Toggle sidebar appearance
    document.addEventListener('DOMContentLoaded', () => {
        const menuIcon = document.querySelector('.toggle-sidebar-icon');
        const sidebar = document.querySelector('.admin-sidebar');

        menuIcon.addEventListener('click', (event) => {
            event.stopPropagation();
            sidebar.classList.toggle('active');
        });

        document.addEventListener('click', (event) => {
            if (!sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });
    });

    const token = localStorage.getItem('token');
    const userIcon = document.getElementById('kritikSaranContainer').dataset.userIcon;
    
    document.addEventListener('DOMContentLoaded', () => {
        const kritikSaranList = document.getElementById('kritikSaranList');
        const filterButtons = document.querySelectorAll('.filter-button');
        const popupContainer = document.getElementById('messagePopup');
        const closePopup = document.getElementById('closePopup');
        const popupName = document.getElementById('popupName');
        const popupMessage = document.getElementById('popupMessage');
        const messageStatus = document.getElementById('messageStatus');
        const markComplete = document.getElementById('markComplete');
        const markUnread = document.getElementById('markUnread');

        let kritikData = [];
        let currentItem = null;
        let currentCard = null;

        function fetchKritikSaran() {
            axios.get('https://sirtrw-api.vansite.cloud/api/kritik', {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(response => {
                kritikData = response.data.data; 
                applyFilterAndRender(); // Render pertama kali dengan filter 'all'
            })
            .catch(error => {
                console.error('Error fetching kritik dan saran:', error);
                kritikSaranList.innerHTML = '<div>Gagal memuat data. Coba lagi nanti.</div>';
            });
        }

        function renderKritikSaran(data) {
            if (!data || data.length === 0) {
                kritikSaranList.innerHTML = '<div>Tidak ada data yang cocok dengan filter ini.</div>';
                return;
            }

            kritikSaranList.innerHTML = '';
            data.forEach(item => {
                const card = document.createElement('div');
                card.className = `kritik-saran-card ${item.status === 'yet' ? 'unread' : 'complete'}`;
                const senderName = item?.user?.warga?.name || `User ID: ${item.user_id ?? 'Anonim'}`;
                card.innerHTML = `
                    <div class="profile-icon"><img src="${userIcon}" alt=""></div>
                    <div class="kritik-saran-content">
                        <h4>${senderName}</h4>
                        <p>${item.text}</p>
                        <small>${new Date(item.created_at).toLocaleString('id-ID')}</small>
                    </div>
                `;
                card.addEventListener('click', () => openPopup(item));
                kritikSaranList.appendChild(card);
            });
        }
        
        // --- FUNGSI BARU UNTUK MENGATASI MASALAH FILTER ---
        function applyFilterAndRender() {
            const activeFilterButton = document.querySelector('.filter-button.active');
            const filter = activeFilterButton.getAttribute('data-filter');

            if (filter === 'all') {
                renderKritikSaran(kritikData);
            } else {
                const filterStatus = filter === 'unread' ? 'yet' : 'done';
                const filteredData = kritikData.filter(item => item.status === filterStatus);
                renderKritikSaran(filteredData);
            }
        }

        function openPopup(item) {
            // Kita cari card yang sesuai dengan item.id agar selalu benar
            currentCard = Array.from(kritikSaranList.children).find(card => card.innerHTML.includes(item.text));
            currentItem = item;
            
            const senderName = item?.user?.warga?.name || `User ID: ${item.user_id ?? 'Anonim'}`;
            popupName.value = senderName;
            popupMessage.value = item.text;
            messageStatus.textContent = item.status === 'yet' ? 'Belum Dibaca' : 'Selesai';
            popupContainer.style.display = 'flex';
        }

        closePopup.addEventListener('click', () => {
            popupContainer.style.display = 'none';
        });

        function updateStatus(newStatus) {
            if (!currentItem) return;

            axios.put(`https://sirtrw-api.vansite.cloud/api/kritik/${currentItem.id}`, 
                { status: newStatus }, 
                { headers: { Authorization: `Bearer ${token}` } }
            )
            .then(response => {
                // Update data lokal
                currentItem.status = newStatus;
                
                // Tutup popup
                popupContainer.style.display = 'none';

                // --- INI BAGIAN PENTINGNYA ---
                // Render ulang daftar dengan filter yang sedang aktif
                applyFilterAndRender();
            })
            .catch(error => {
                console.error('Error updating status:', error);
                alert('Gagal mengubah status pesan.');
            });
        }
        
        markComplete.addEventListener('click', () => updateStatus('done'));
        markUnread.addEventListener('click', () => updateStatus('yet'));

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                // Cukup panggil fungsi baru kita
                applyFilterAndRender();
            });
        });

        fetchKritikSaran();
    });
</script>
@endsection
