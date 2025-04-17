@extends('layouts.adminSidebar')

<title>SIMAS - Program Kerja</title>
<link rel="stylesheet" href="{{ asset('css/admin-program-kerja.css') }}">
<link rel="stylesheet" href="{{ asset('css/charts.css') }}">

<script type="text/javascript" src="{{ asset('calendar_trial/codebase/calendar.js') }}"></script>
<link rel="stylesheet" href="{{ asset('calendar_trial/codebase/calendar.js') }}">

@section('content')
{{-- Header --}}
<div class="proker-header-container">
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
                        <button class="edit-button"><img src="{{ asset('storage/editPen.png') }}" alt=""></button>
                    </a>
                    <button class="delete-button"><img src="{{ asset('storage/trashPurple.png') }}" alt=""></button>
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
                        <button class="edit-button"><img src="{{ asset('storage/editPen.png') }}" alt=""></button>
                    </a>
                    <button class="delete-button"><img src="{{ asset('storage/trashPurple.png') }}" alt=""></button>
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
                        <button class="edit-button"><img src="{{ asset('storage/editPen.png') }}" alt=""></button>
                    </a>
                    <button class="delete-button"><img src="{{ asset('storage/trashPurple.png') }}" alt=""></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar and Notifications -->
    <aside class="calendar-notification">

        <div class="calendar-container">
            <div class="calendar-header">
                <div class="calendar-month"></div>
                <div class="calendar-buttons">
                    <div class="calendar-button today-button">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="calendar-button prev-button">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <div class="calendar-button next-button">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
            <div class="calendar-weekdays">
                <div class="weekday">Sun</div>
                <div class="weekday">Mon</div>
                <div class="weekday">Tue</div>
                <div class="weekday">Wed</div>
                <div class="weekday">Thu</div>
                <div class="weekday">Fri</div>
                <div class="weekday">Sat</div>
            </div>
            <div class="calendar-days">
                <!-- Days will be added using JavaScript -->
            </div>
        </div>

        <!-- Notifications -->
        <div class="notification">
            <div class="notification-header">
                <h3>Notification</h3>
                <img src="{{ asset('storage/bell.png') }}" alt="">
            </div>
        </div>
    </aside>
</div>

<!-- JavaScript -->
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

    // Handle filter buttons
    document.querySelectorAll('.filter-button').forEach(button => {
        button.addEventListener('click', function () {
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

    // Calendar variables and initialization
    const daysContainer = document.querySelector(".calendar-days"),
        nextBtn = document.querySelector(".next-button"),
        prevBtn = document.querySelector(".prev-button"),
        monthDisplay = document.querySelector(".calendar-month"),
        todayBtn = document.querySelector(".today-button"),
        programList = document.getElementById("programList");

    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const date = new Date();
    let currentMonth = date.getMonth();
    let currentYear = date.getFullYear();

    // Dummy data for program dates
    const events = [{
        date: "2023-10-28",
        title: "Posyandu Balita dan Lansia Sehat"
    },
    {
        date: "2023-09-15",
        title: "Program Imunisasi Anak"
    },
    {
        date: "2023-10-05",
        title: "Pemeriksaan Kesehatan Gratis"
    },
    ];

    // Render the calendar
    function renderCalendar() {
        const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const prevDaysInMonth = new Date(currentYear, currentMonth, 0).getDate();

        monthDisplay.innerHTML = `${months[currentMonth]} ${currentYear}`;
        daysContainer.innerHTML = "";

        // Render previous month's days (non-clickable, no container)
        for (let i = firstDayOfMonth; i > 0; i--) {
            daysContainer.innerHTML += `<div class="calendar-day prev" style="pointer-events: none; opacity: 0.5;">${prevDaysInMonth - i + 1}</div>`;
        }

        // Render current month's days (clickable)
        for (let i = 1; i <= daysInMonth; i++) {
            const currentDate = new Date(currentYear, currentMonth, i);
            const formattedDate = currentDate.toISOString().split('T')[0];
            const event = events.find(event => event.date === formattedDate);

            const isToday =
                i === date.getDate() &&
                currentMonth === date.getMonth() &&
                currentYear === date.getFullYear();

            daysContainer.innerHTML += `
            <div class="calendar-day${isToday ? " today" : ""}${event ? " event" : ""}" data-date="${formattedDate}">
                ${i}${event ? `<div class="event-indicator"></div>` : ""}
            </div>`;
        }

        // Render next month's days (non-clickable, no container)
        const nextDays = 42 - daysContainer.childElementCount; // Ensure 6 rows in the calendar
        for (let i = 1; i <= nextDays; i++) {
            daysContainer.innerHTML += `<div class="calendar-day next" style="pointer-events: none; opacity: 0.5;">${i}</div>`;
        }

        hideTodayBtn();
    }

    // Handle clicks on calendar days
    daysContainer.addEventListener("click", (e) => {
        const day = e.target.closest(".calendar-day");
        if (day && day.style.pointerEvents !== 'none') {
            const selectedDate = day.getAttribute("data-date");

            // Highlight the selected day
            document.querySelectorAll(".calendar-day").forEach(d => d.classList.remove("selected"));
            day.classList.add("selected");

            // Filter program cards by selected date
            filterProgramsByDate(selectedDate);
        }
    });

    // Function to filter program cards by selected date
    function filterProgramsByDate(date) {
        const programCards = document.querySelectorAll(".program-card");

        programCards.forEach(card => {
            const cardDate = card.getAttribute("data-date");

            if (date === cardDate || !date) {
                card.style.display = "flex"; // Show matching cards
            } else {
                card.style.display = "none"; // Hide non-matching cards
            }
        });
    }

    // Navigate to the next or previous month
    nextBtn.addEventListener("click", () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    });

    prevBtn.addEventListener("click", () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    });

    // Navigate to the current month
    todayBtn.addEventListener("click", () => {
        currentMonth = date.getMonth();
        currentYear = date.getFullYear();
        renderCalendar();
    });

    // Hide the "Today" button if already on the current month
    function hideTodayBtn() {
        todayBtn.style.display =
            currentMonth === date.getMonth() && currentYear === date.getFullYear() ? "none" : "flex";
    }

    // Initial render
    renderCalendar();
</script>
@endsection
