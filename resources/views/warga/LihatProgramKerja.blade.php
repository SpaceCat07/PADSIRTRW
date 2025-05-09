@extends('layouts.dashboardNavbar')

<title>SIMAS - Program Kerja</title>
<link rel="stylesheet" href="{{ asset('css/lihat-proker.css') }}">

@section('content')
    <?php
    $page = 'program-kerja'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    @include('layouts.prokerSidebar')

    <div class="proker-main">
        <div class="proker-container">
            <!-- Search and filter section -->
            <div class="search-filter">
                <input type="text" placeholder="Search..." id="searchInput">
                <select class="text-center" id="dateFilter">
                    <option value="">dd / mm / yyyy</option>
                </select>
            </div>

            <!-- Sort by section -->
            <div class="sort-by">
                <div></div>
                <div class="sort-select-container">
                    <label for="sort-select">Sort by:</label>
                    <select id="sort-select">
                        <option value="latest">Latest update</option>
                        <option value="oldest">Oldest update</option>
                    </select>
                </div>
            </div>

            <!-- Program list -->
            <div class="program-list" id="programList">
                <!-- Program cards will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const programList = document.getElementById('programList');
            const searchInput = document.getElementById('searchInput');
            const sortSelect = document.getElementById('sort-select');
            const dateFilter = document.getElementById('dateFilter');

            let programs = [];

            // Fetch program data from API
            function fetchPrograms() {
                axios.get('/api/programs')
                    .then(response => {
                        programs = response.data;
                        populateDateFilter();
                        renderPrograms(programs);
                    })
                    .catch(error => {
                        console.error('Error fetching programs:', error);
                        programList.innerHTML = '<p>Failed to load program data.</p>';
                    });
            }

            // Render program cards
            function renderPrograms(data) {
                programList.innerHTML = '';
                if (data.length === 0) {
                    programList.innerHTML = '<p>No programs found.</p>';
                    return;
                }
                data.forEach(program => {
                    const card = document.createElement('div');
                    card.className = 'program-card';
                    card.setAttribute('data-date', program.date);
                    card.setAttribute('data-status', program.status);

                    card.innerHTML = `
                        <div class="date">
                            <div class="month">${program.month}</div>
                            <div class="day">${program.day}</div>
                            <div class="day-of-week">${program.day_of_week}</div>
                        </div>
                        <div class="details">
                            <h3>${program.title}</h3>
                            <p>${program.description}</p>
                            <div class="time-location">
                                <span class="time">${program.time}</span> &bull; Lokasi di ${program.location}
                            </div>
                        </div>
                        <div class="more-options">⋮</div>
                    `;
                    programList.appendChild(card);
                });
            }

            // Populate date filter dropdown with unique dates
            function populateDateFilter() {
                const dates = [...new Set(programs.map(p => p.date))].sort();
                dateFilter.innerHTML = '<option value="">dd / mm / yyyy</option>';
                dates.forEach(date => {
                    const option = document.createElement('option');
                    option.value = date;
                    option.textContent = date;
                    dateFilter.appendChild(option);
                });
            }

            // Filter programs by search term and date filter
            function filterPrograms() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedDate = dateFilter.value;

                let filtered = programs.filter(program => {
                    const matchesSearch = program.title.toLowerCase().includes(searchTerm) ||
                        program.description.toLowerCase().includes(searchTerm);
                    const matchesDate = selectedDate ? program.date === selectedDate : true;
                    return matchesSearch && matchesDate;
                });

                sortPrograms(filtered);
            }

            // Sort programs by date
            function sortPrograms(data) {
                const sortBy = sortSelect.value;
                data.sort((a, b) => {
                    const dateA = new Date(a.date);
                    const dateB = new Date(b.date);
                    return sortBy === 'latest' ? dateB - dateA : dateA - dateB;
                });
                renderPrograms(data);
            }

            // Event listeners
            searchInput.addEventListener('input', filterPrograms);
            sortSelect.addEventListener('change', filterPrograms);
            dateFilter.addEventListener('change', filterPrograms);

            // Initial fetch
            fetchPrograms();
        });
    </script>
@endsection
