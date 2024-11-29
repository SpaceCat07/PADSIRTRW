@extends('layouts.dashboardNavbar')

@section('content')
    <?php
    $page = 'program-kerja'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    @include('layouts.prokerSidebar')

    <div class="proker-main">
        <div class="proker-container">
            <!-- Search and filter section -->
            <div class="search-filter">
                <input type="text" placeholder="Search...">
                <select class="text-center">
                    <option value="">dd / mm / yyyy</option>
                </select>
            </div>

            <!-- Sort by section -->
            <div class="sort-by">
                Sort by:
                <select id="sortOptions">
                    <option value="latest">Latest update</option>
                    <option value="oldest">Oldest update</option>
                </select>
            </div>

            <!-- Program list -->
            <div class="program-list" id="programList">
                <!-- Program card (dummy data) -->
                <div class="program-card" data-date="2023-10-28" data-status="upcoming">
                    <div class="date">
                        <div class="month">OKTOBER</div>
                        <div class="day">28</div>
                        <div class="day-of-week">Senin</div>
                    </div>
                    <div class="details">
                        <h3>Posyandu Balita dan Lansia Sehat</h3>
                        <p>Program ini memberikan pelayanan kesehatan rutin bagi balita dan lansia, bekerja sama dengan
                            puskesmas setempat. Warga dapat mengakses layanan imunisasi, pemeriksaan kesehatan dasar, dan
                            edukasi gizi yang penting bagi keluarga.</p>
                        <div class="time-location">
                            <span class="time">08.00 WIB</span> &bull; Lokasi di Balai Desa
                        </div>
                    </div>
                    <div class="more-options">⋮</div>
                </div>

                <div class="program-card" data-date="2023-09-15" data-status="completed">
                    <div class="date">
                        <div class="month">SEPTEMBER</div>
                        <div class="day">15</div>
                        <div class="day-of-week">Jumat</div>
                    </div>
                    <div class="details">
                        <h3>Program Imunisasi Anak</h3>
                        <p>Program ini bertujuan untuk memberikan imunisasi kepada anak-anak di wilayah setempat.</p>
                        <div class="time-location">
                            <span class="time">09.00 WIB</span> &bull; Lokasi di Puskesmas
                        </div>
                    </div>
                    <div class="more-options">⋮</div>
                </div>

                <div class="program-card" data-date="2023-10-05" data-status="upcoming">
                    <div class="date">
                        <div class="month">OKTOBER</div>
                        <div class="day">05</div>
                        <div class="day-of-week">Kamis</div>
                    </div>
                    <div class="details">
                        <h3>Pemeriksaan Kesehatan Gratis</h3>
                        <p>Pelayanan pemeriksaan kesehatan gratis bagi masyarakat.</p>
                        <div class="time-location">
                            <span class="time">10.00 WIB</span> &bull; Lokasi di Balai Desa
                        </div>
                    </div>
                    <div class="more-options">⋮</div>
                </div>

                <!-- Add more program cards as necessary -->
            </div>
        </div>
    </div>

    <script>
        document.getElementById('sortOptions').addEventListener('change', function() {
            const programList = document.getElementById('programList');
            const programs = Array.from(document.querySelectorAll('.program-card'));
            const sortBy = this.value;

            programs.sort((a, b) => {
                const dateA = new Date(a.getAttribute('data-date'));
                const dateB = new Date(b.getAttribute('data-date'));

                return sortBy === 'latest' ? dateB - dateA : dateA - dateB;
            });

            programList.innerHTML = ''; // Clear the existing program cards
            programs.forEach(program => programList.appendChild(program)); // Append sorted program cards
        });

        // Sidebar filtering
        const sidebarItems = document.querySelectorAll('.sidebar-item');

        for (let item of sidebarItems) {
            item.addEventListener('click', function() {
                const status = this.textContent.trim() === 'Mendatang' ? 'upcoming' : 'completed';

                // Highlight the selected sidebar item
                for (let i of sidebarItems) i.classList.remove('active');
                this.classList.add('active');

                // Filter program cards
                const programCards = document.querySelectorAll('.program-card');
                programCards.forEach(card => {
                    if (card.getAttribute('data-status') === status) {
                        card.style.display = 'flex'; // Show matching cards
                    } else {
                        card.style.display = 'none'; // Hide non-matching cards
                    }
                });
            });
        }

        // Search functionality
        document.querySelector('.search-filter input').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const programCards = document.querySelectorAll('.program-card');

            programCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();

                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'flex'; // Show matching cards
                } else {
                    card.style.display = 'none'; // Hide non-matching cards
                }
            });
        });
    </script>
@endsection
