@extends('layouts.dashboardNavbar')

<title>SIMAS - Riwayat Kritik & Saran Saya</title>
{{-- Tautan ke file CSS eksternal Anda --}}
<link rel="stylesheet" href="{{ asset('css/riwayat-kritik-saran.css') }}">

@section('content')
    <?php
    $page = 'kritik-saran';
    ?>

    {{-- Daftar Kritik dan Saran --}}
    <div class="kritik-saran-container" id="kritikSaranContainer">
        {{-- Header --}}
        <div class="data-warga-container">
            <header class="admin-header">
                <h1>Riwayat Kritik & Saran Saya</h1>
            </header>
        </div>
        <div class="status-filters">
            <button class="filter-button active" data-filter="all">Semua</button>
            <button class="filter-button" data-filter="unread">Menunggu Respon</button>
            <button class="filter-button" data-filter="complete">Selesai</button>
        </div>
        <div id="kritikSaranList">
            <div>Memuat riwayat kritik dan saran Anda...</div>
        </div>
    </div>

    {{-- Popup Detail --}}
    <div class="popup-container" id="messagePopup" style="display:none;">
        <div class="popup">
            <button class="close-popup" id="closePopup">&times;</button>
            <div class="popup-header">
                <span id="messageStatus"></span>
                <h2>DETAIL KRITIK & SARAN</h2>
            </div>
            <input type="text" id="popupDate" class="popup-input" placeholder="Tanggal Pengiriman" readonly>
            <textarea id="popupMessage" class="popup-textarea" placeholder="Isi pesan..." readonly></textarea>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    {{-- Script JavaScript tetap di sini karena ia berinteraksi langsung dengan elemen HTML di atas --}}
    <script>
        const token = localStorage.getItem('token');

        document.addEventListener('DOMContentLoaded', () => {
            const kritikSaranList = document.getElementById('kritikSaranList');
            const filterButtons = document.querySelectorAll('.filter-button');
            const popupContainer = document.getElementById('messagePopup');
            const closePopup = document.getElementById('closePopup');
            const popupDate = document.getElementById('popupDate');
            const popupMessage = document.getElementById('popupMessage');
            const messageStatus = document.getElementById('messageStatus');

            let myKritikData = [];

            function fetchAndFilterKritik() {
                const userPromise = axios.get('https://sirtrw-api.vansite.cloud/api/me', {
                    headers: { Authorization: `Bearer ${token}` }
                });
                const kritikPromise = axios.get('https://sirtrw-api.vansite.cloud/api/kritik', {
                    headers: { Authorization: `Bearer ${token}` }
                });

                Promise.all([userPromise, kritikPromise])
                    .then(([userResponse, kritikResponse]) => {
                        const currentUserId = userResponse.data.data.id;
                        const allKritikData = kritikResponse.data.data;
                        const filteredForUser = allKritikData.filter(kritik => kritik.user_id === currentUserId);
                        
                        myKritikData = filteredForUser;
                        applyFilterAndRender();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        kritikSaranList.innerHTML = '<div>Gagal memuat riwayat data Anda.</div>';
                    });
            }

            function renderKritikSaran(data) {
                if (!data || data.length === 0) {
                    kritikSaranList.innerHTML = '<div>Anda belum pernah mengirim kritik atau saran.</div>';
                    return;
                }
                kritikSaranList.innerHTML = '';
                data.forEach(item => {
                    const card = document.createElement('div');
                    const statusClass = item.status === 'yet' ? 'unread' : 'complete';
                    const statusText = item.status === 'yet' ? 'Menunggu Respon' : 'Selesai';

                    card.className = `kritik-saran-card ${statusClass}`;
                    card.innerHTML = `
                        <div class="kritik-saran-content">
                            <h4>Status: ${statusText}</h4>
                            <p>${item.text}</p>
                            <small>Dikirim pada: ${new Date(item.created_at).toLocaleString('id-ID', {day: '2-digit', month: 'long', year: 'numeric'})}</small>
                        </div>
                    `;
                    card.addEventListener('click', () => openPopup(item));
                    kritikSaranList.appendChild(card);
                });
            }

            function applyFilterAndRender() {
                const activeFilter = document.querySelector('.filter-button.active').dataset.filter;
                if (activeFilter === 'all') {
                    renderKritikSaran(myKritikData);
                } else {
                    const filterStatus = activeFilter === 'unread' ? 'yet' : 'done';
                    const filteredData = myKritikData.filter(item => item.status === filterStatus);
                    renderKritikSaran(filteredData);
                }
            }

            function openPopup(item) {
                const statusText = item.status === 'yet' ? 'Menunggu Respon' : 'Selesai';
                messageStatus.textContent = statusText.toUpperCase();
                popupDate.value = `Dikirim: ${new Date(item.created_at).toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' })}`;
                popupMessage.value = item.text;
                popupContainer.style.display = 'flex';
            }

            closePopup.addEventListener('click', () => {
                popupContainer.style.display = 'none';
            });

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    applyFilterAndRender();
                });
            });

            fetchAndFilterKritik();
        });
    </script>
@endsection