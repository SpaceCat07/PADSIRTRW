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
            <div>Loading programs...</div>
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

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const programList = document.getElementById('programList');
        const filterButtons = document.querySelectorAll('.filter-button');

        let programs = [];

        function fetchPrograms() {
            axios.get('/api/program-kerja')
                .then(response => {
                    programs = response.data;
                    renderPrograms(programs);
                })
                .catch(error => {
                    console.error('Error fetching programs:', error);
                    programList.innerHTML = '<div>Failed to load programs.</div>';
                });
        }

        function renderPrograms(programsToRender) {
            if (!programsToRender || programsToRender.length === 0) {
                programList.innerHTML = '<div>No programs found.</div>';
                return;
            }
            programList.innerHTML = '';
            programsToRender.forEach(program => {
                const card = document.createElement('div');
                card.className = 'program-card ' + (program.status === 'complete' ? 'complete' : 'soon');
                card.setAttribute('data-date', program.date);
                card.innerHTML = `
                    <div class="admin-card date">
                        <div class="month">${program.month}</div>
                        <div class="day">${program.day}</div>
                        <div class="day-of-week">${program.day_of_week}</div>
                    </div>
                    <div class="details">
                        <h3>${program.title}</h3>
                        <p>${program.description}</p>
                        <div class="time-location">
                            <span class="time">${program.time}</span> • Lokasi di ${program.location}
                        </div>
                    </div>
                    <div class="proker-button-group">
                        <button class="copy-button"><img src="{{ asset('storage/copy.png') }}" alt=""></button>
                        <a href="{{ route('edit-program-kerja') }}">
                            <button class="edit-button"><img src="{{ asset('storage/editPen.png') }}" alt=""></button>
                        </a>
                        <button class="delete-button"><img src="{{ asset('storage/trashPurple.png') }}" alt=""></button>
                    </div>
                `;
                programList.appendChild(card);
            });
        }

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                const filter = button.getAttribute('data-filter');
                if (filter === 'all') {
                    renderPrograms(programs);
                } else {
                    renderPrograms(programs.filter(p => p.status === filter));
                }
            });
        });

        fetchPrograms();
    });
</script>
@endsection
