@extends('layouts.adminSidebar')

@section('content')
    {{-- Header --}}
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt=""></div>
            <h1>Program Kerja</h1>
        </header>
    </div>

    <div class="admin-proker-container">
        <div class="program-card-container">

            {{-- filter and add buttons --}}
            <div class="filter-add-group">
                <!-- Filter Buttons -->
                <div class="status-filters">
                    <button class="filter-button active" data-filter="all">All</button>
                    <button class="filter-button" data-filter="complete">Complete</button>
                    <button class="filter-button" data-filter="soon">Soon</button>
                </div>
                <a href="{{ route('tambah-program-kerja') }}">
                    <button class="add-proker-button">+ ADD NEW</button>
                </a>
                
            </div>


            <!-- Program List -->
            <div class="program-list" id="programList">
                <!-- Program card (your dummy data included here) -->
                <div class="program-card complete" data-date="2023-10-28">
                    <div class="date">
                        <div class="month">OCTOBER</div>
                        <div class="day">28</div>
                        <div class="day-of-week">Monday</div>
                    </div>
                    <div class="details">
                        <h3>Posyandu Balita dan Lansia Sehat</h3>
                        <p>Program ini memberikan pelayanan kesehatan rutin bagi balita dan lansia, bekerja sama dengan
                            puskesmas setempat...</p>
                        <div class="time-location">
                            <span class="time">08.00 WIB</span> • Lokasi di Balai Desa
                        </div>
                    </div>
                    <div class="proker-button-group">
                        <button class="copy-button"><img src="{{ asset('storage/copy.png') }}" alt=""></button>
                        <a href="{{ route('edit-program-kerja') }}">
                            <button class="edit-button"><img src="{{ asset('storage/editPen.png') }}"
                                    alt=""></button>
                        </a>
                        <button class="delete-button"><img src="{{ asset('storage/trashPurple.png') }}"
                                alt=""></button>
                    </div>
                </div>

                <div class="program-card soon" data-date="2023-09-15">
                    <div class="date">
                        <div class="month">SEPTEMBER</div>
                        <div class="day">15</div>
                        <div class="day-of-week">Friday</div>
                    </div>
                    <div class="details">
                        <h3>Program Imunisasi Anak</h3>
                        <p>Program ini bertujuan untuk memberikan imunisasi kepada anak-anak di wilayah setempat.</p>
                        <div class="time-location">
                            <span class="time">09.00 WIB</span> • Lokasi di Puskesmas
                        </div>
                    </div>
                    <div class="proker-button-group">
                        <button class="copy-button"><img src="{{ asset('storage/copy.png') }}" alt=""></button>
                        <a href="{{ route('edit-program-kerja') }}">
                            <button class="edit-button"><img src="{{ asset('storage/editPen.png') }}"
                                    alt=""></button>
                        </a>
                        <button class="delete-button"><img src="{{ asset('storage/trashPurple.png') }}"
                                alt=""></button>
                    </div>
                </div>

                <div class="program-card complete" data-date="2023-10-05">
                    <div class="date">
                        <div class="month">OCTOBER</div>
                        <div class="day">05</div>
                        <div class="day-of-week">Thursday</div>
                    </div>
                    <div class="details">
                        <h3>Pemeriksaan Kesehatan Gratis</h3>
                        <p>Pelayanan pemeriksaan kesehatan gratis bagi masyarakat.</p>
                        <div class="time-location">
                            <span class="time">10.00 WIB</span> • Lokasi di Balai Desa
                        </div>
                    </div>
                    <div class="proker-button-group">
                        <button class="copy-button"><img src="{{ asset('storage/copy.png') }}" alt=""></button>
                        <a href="{{ route('edit-program-kerja') }}">
                            <button class="edit-button"><img src="{{ asset('storage/editPen.png') }}"
                                    alt=""></button>
                        </a>
                        <button class="delete-button"><img src="{{ asset('storage/trashPurple.png') }}"
                                alt=""></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar and Notifications -->
        <aside class="calendar-notification">
            <div class="month-selector">
                <input type="month" id="monthSelect" value="{{ date('Y-m') }}">
                <button id="currentMonthBtn">Today</button>
            </div>

            <div class="calendar">
                <table>
                    <thead>
                        <tr>
                            <th>Mo</th>
                            <th>Tu</th>
                            <th>We</th>
                            <th>Th</th>
                            <th>Fr</th>
                            <th>Sa</th>
                            <th>Su</th>
                        </tr>
                    </thead>
                    <tbody id="calendarBody">
                        <!-- Calendar dates will be dynamically generated here -->
                    </tbody>
                </table>
            </div>

            <!-- Notifications -->
            <div class="notification">
                <h3>Notification</h3>
                <ul>
                    <li>1 Kerja Bakti <button>Edit</button> <button>Delete</button></li>
                    <li>28 Kerja Bakti <button>Edit</button> <button>Delete</button></li>
                </ul>
            </div>
        </aside>
    </div>

    <!-- JavaScript -->
    <script>
        // Handle filter buttons
        document.querySelectorAll('.filter-button').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons and add to the clicked one
                document.querySelectorAll('.filter-button').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                // Get filter type from data-filter attribute
                const filter = button.getAttribute('data-filter');

                // Filter cards based on status
                filterCards(filter);
            });
        });

        // Filter function to show/hide cards based on status
        function filterCards(status) {
            const cards = document.querySelectorAll('.program-card');

            cards.forEach(card => {
                const cardStatus = card.classList.contains('complete') ? 'complete' : 'soon';

                if (status === 'all' || cardStatus === status) {
                    card.style.display = 'flex'; // Show card
                } else {
                    card.style.display = 'none'; // Hide card
                }
            });
        }

        let currentYear = new Date().getFullYear();
        let currentMonth = new Date().getMonth();
        const currentDate = new Date().getDate();

        function loadCalendar() {
            const monthSelect = document.getElementById('monthSelect');
            const calendarBody = document.getElementById('calendarBody');

            // Function to update the calendar based on selected month
            function updateCalendar() {
                const [year, month] = monthSelect.value.split('-');

                // Parse month as an integer and subtract 1 to match JavaScript's zero-based month index
                const parsedMonth = parseInt(month) - 1;

                const daysInMonth = new Date(year, parsedMonth + 1, 0).getDate(); // Get the last day of the month
                const firstDay = new Date(year, parsedMonth, 1).getDay(); // Get the first day of the month

                // Clear previous calendar
                calendarBody.innerHTML = '';

                // Create empty cells for days before the first day of the month
                let row = '<tr>';
                for (let i = 0; i < firstDay; i++) {
                    row += '<td></td>'; // Empty cells
                }

                // Create cells for days of the month
                for (let day = 1; day <= daysInMonth; day++) {
                    row += `<td>${day}</td>`;
                    if ((day + firstDay) % 7 === 0) { // Start a new row after Sunday
                        row += '</tr><tr>';
                    }
                }
                row += '</tr>';
                calendarBody.innerHTML = row;

                // Highlight current date if it falls within the selected month
                if (currentMonth == parsedMonth && currentYear == year) {
                    const currentCell = calendarBody.querySelector(`td:nth-child(${currentDate + firstDay})`);
                    if (currentCell) {
                        currentCell.classList.add('current-date');
                    }
                }

                // Add event listeners to the new calendar cells
                document.querySelectorAll('.calendar td').forEach(cell => {
                    cell.addEventListener('click', function() {
                        const selectedDate = this.innerText.trim();
                        if (selectedDate) {
                            // Highlight the selected date
                            document.querySelectorAll('.calendar td').forEach(td => td.classList.remove(
                                'selected'));
                            this.classList.add('selected');

                            // Filter program cards based on selected date
                            filterByDate(selectedDate);
                        }
                    });
                });
            }

            // Event listener for month selection
            monthSelect.addEventListener('input', updateCalendar);

            // Event listener for "Today" button
            document.getElementById('currentMonthBtn').addEventListener('click', function() {
                monthSelect.value = new Date().toISOString().slice(0, 7); // Reset to current month
                updateCalendar(); // Update the calendar
            });

            updateCalendar(); // Initial load
        }

        // Call loadCalendar on page load
        loadCalendar();

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
