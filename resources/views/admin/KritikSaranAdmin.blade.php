@extends('layouts.adminSidebar')

@section('content')
    {{-- Header --}}
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt=""></div>
            <h1>Kritik dan Saran</h1>
        </header>
    </div>

    {{-- Kritik dan Saran List --}}
    <div class="kritik-saran-container">

        {{-- Status Filters --}}
        <div class="status-filters">
            <button class="filter-button active" data-filter="all">All</button>
            <button class="filter-button" data-filter="unread">Unread</button>
            <button class="filter-button" data-filter="complete">Complete</button>
        </div>

        <div class="kritik-saran-card unread">
            <div class="profile-icon"><img src="{{ asset('storage/maleUser.png') }}" alt=""></div>
            <div class="kritik-saran-content">
                <h4>John Doe</h4>
                <p>Saran untuk kegiatan kedepannya dapat dimulai tepat waktu sesuai dengan jadwal yang telah diinformasikan sebelumnya.</p>
            </div>
        </div>

        <div class="kritik-saran-card unread">
            <div class="profile-icon"><img src="{{ asset('storage/maleUser.png') }}" alt=""></div>
            <div class="kritik-saran-content">
                <h4>Jane Doe</h4>
                <p>Tolong lebih tegas pada warga yang suka memasang musik dengan volume tinggi di malam hari karena dapat mengganggu istirahat warga lainnya.</p>
            </div>
        </div>

        <div class="kritik-saran-card complete">
            <div class="profile-icon"><img src="{{ asset('storage/maleUser.png') }}" alt=""></div>
            <div class="kritik-saran-content">
                <h4>John Doe</h4>
                <p>Terlalu banyak iuran-iuran tambahan, uang saya habis.</p>
            </div>
        </div>
    </div>

    {{-- Popup --}}
    <div class="popup-container" id="messagePopup">
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.kritik-saran-card');
            const popupContainer = document.getElementById('messagePopup');
            const closePopup = document.getElementById('closePopup');
            const popupName = document.getElementById('popupName');
            const popupMessage = document.getElementById('popupMessage');
            const messageStatus = document.getElementById('messageStatus');
            const markComplete = document.getElementById('markComplete');
            const markUnread = document.getElementById('markUnread');

            let currentCard = null; // Track the currently opened card

            // Open popup when card is clicked
            cards.forEach(card => {
                card.addEventListener('click', () => {
                    currentCard = card; // Store reference to the clicked card
                    const name = card.querySelector('h4').textContent;
                    const message = card.querySelector('p').textContent;
                    const status = card.classList.contains('unread') ? 'Unread' : 'Complete';

                    popupName.value = name;
                    popupMessage.value = message;
                    messageStatus.textContent = status;

                    popupContainer.style.display = 'flex';
                });
            });

            // Close popup
            closePopup.addEventListener('click', () => {
                popupContainer.style.display = 'none';
            });

            // Mark as complete
            markComplete.addEventListener('click', () => {
                if (currentCard) {
                    currentCard.classList.remove('unread');
                    currentCard.classList.add('complete');
                }
                popupContainer.style.display = 'none';
            });

            // Mark as unread
            markUnread.addEventListener('click', () => {
                if (currentCard) {
                    currentCard.classList.remove('complete');
                    currentCard.classList.add('unread');
                }
                popupContainer.style.display = 'none';
            });
        });

        // Filter function to show/hide cards based on status
        document.querySelectorAll('.filter-button').forEach(button => {
            button.addEventListener('click', () => {
                const filter = button.getAttribute('data-filter');
                document.querySelectorAll('.kritik-saran-card').forEach(card => {
                    const cardStatus = card.classList.contains('unread') ? 'unread' : 'complete';
                    card.style.display = (filter === 'all' || filter === cardStatus) ? 'flex' : 'none';
                });

                // Highlight active filter button
                document.querySelectorAll('.filter-button').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });

        // Toggle sidebar appearance
        document.addEventListener('DOMContentLoaded', () => {
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');

            // Event listener to toggle sidebar visibility
            menuIcon.addEventListener('click', (event) => {
                event.stopPropagation();
                sidebar.classList.toggle('active');
            });

            // Event listener to hide sidebar when clicking outside
            document.addEventListener('click', (event) => {
                if (!sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            });
        });
    </script>
@endsection
