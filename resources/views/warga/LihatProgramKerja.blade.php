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
                <div class="program-card" data-date="2023-10-28">
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
                            08.00 WIB &bull; Lokasi di Balai Desa
                        </div>
                    </div>
                    <div class="more-options">⋮</div>
                </div>

                <div class="program-card" data-date="2023-09-15">
                    <div class="date">
                        <div class="month">SEPTEMBER</div>
                        <div class="day">15</div>
                        <div class="day-of-week">Jumat</div>
                    </div>
                    <div class="details">
                        <h3>Program Imunisasi Anak</h3>
                        <p>Program ini bertujuan untuk memberikan imunisasi kepada anak-anak di wilayah setempat.</p>
                        <div class="time-location">
                            09.00 WIB &bull; Lokasi di Puskesmas
                        </div>
                    </div>
                    <div class="more-options">⋮</div>
                </div>

                <div class="program-card" data-date="2023-10-05">
                    <div class="date">
                        <div class="month">OKTOBER</div>
                        <div class="day">05</div>
                        <div class="day-of-week">Kamis</div>
                    </div>
                    <div class="details">
                        <h3>Pemeriksaan Kesehatan Gratis</h3>
                        <p>Pelayanan pemeriksaan kesehatan gratis bagi masyarakat.</p>
                        <div class="time-location">
                            10.00 WIB &bull; Lokasi di Balai Desa
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
    </script>
@endsection
