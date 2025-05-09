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
    <div class="kritik-saran-container" id="kritikSaranContainer">
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
            let currentCard = null;

            function fetchKritikSaran() {
                axios.get('/api/kritik-saran')
                    .then(response => {
                        kritikData = response.data;
                        renderKritikSaran(kritikData);
                    })
                    .catch(error => {
                        console.error('Error fetching kritik dan saran:', error);
                        kritikSaranList.innerHTML = '<div>Failed to load data.</div>';
                    });
            }

            function renderKritikSaran(data) {
                if (!data || data.length === 0) {
                    kritikSaranList.innerHTML = '<div>No kritik dan saran found.</div>';
                    return;
                }
                kritikSaranList.innerHTML = '';
                data.forEach(item => {
                    const card = document.createElement('div');
                    card.className = 'kritik-saran-card ' + (item.status === 'unread' ? 'unread' : 'complete');
                    card.innerHTML = `
                        <div class="profile-icon"><img src="{{ asset('storage/maleUser.png') }}" alt=""></div>
                        <div class="kritik-saran-content">
                            <h4>${item.name}</h4>
                            <p>${item.message}</p>
                        </div>
                    `;
                    card.addEventListener('click', () => openPopup(card, item));
                    kritikSaranList.appendChild(card);
                });
            }

            function openPopup(card, item) {
                currentCard = card;
                popupName.value = item.name;
                popupMessage.value = item.message;
                messageStatus.textContent = item.status === 'unread' ? 'Unread' : 'Complete';
                popupContainer.style.display = 'flex';
            }

            closePopup.addEventListener('click', () => {
                popupContainer.style.display = 'none';
            });

            markComplete.addEventListener('click', () => {
                if (currentCard) {
                    currentCard.classList.remove('unread');
                    currentCard.classList.add('complete');
                    messageStatus.textContent = 'Complete';
                    // Optionally, send update to API here
                }
                popupContainer.style.display = 'none';
            });

            markUnread.addEventListener('click', () => {
                if (currentCard) {
                    currentCard.classList.remove('complete');
                    currentCard.classList.add('unread');
                    messageStatus.textContent = 'Unread';
                    // Optionally, send update to API here
                }
                popupContainer.style.display = 'none';
            });

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filter = button.getAttribute('data-filter');
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    if (filter === 'all') {
                        renderKritikSaran(kritikData);
                    } else {
                        renderKritikSaran(kritikData.filter(item => item.status === filter));
                    }
                });
            });

            fetchKritikSaran();
        });
    </script>
@endsection
