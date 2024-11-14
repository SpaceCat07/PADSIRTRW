@extends('layouts.dashboardNavbar')

@section('content')
    <?php
    $page = 'program-kerja'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    @include('layouts.prokerSidebar')

    <div class="proker-container">
        <!-- Search and filter section -->
        <div class="search-filter">
            <input type="text" placeholder="Search...">
            <select>
                <option value="">dd / mm / yyyy</option>
            </select>
        </div>

        <!-- Sort by section -->
        <div class="sort-by">
            Sort by: Latest update
        </div>

        <!-- Program list -->
        <div class="program-list">
            <!-- Program card (dummy data) -->
            <div class="program-card">
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

            <!-- Repeat more program cards as needed (dummy data) -->
            <div class="program-card">
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

            <!-- Add more program cards as necessary -->
        </div>
    </div>
@endsection
