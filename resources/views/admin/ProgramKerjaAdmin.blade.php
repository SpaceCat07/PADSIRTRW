@extends('layouts.adminSidebar')

<title>SIMAS - Program Kerja</title>
<link rel="stylesheet" href="{{ asset('css/admin-program-kerja.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('css/charts.css') }}"> --}} {{-- Jika tidak digunakan, bisa dikomentari --}}

{{-- Hapus referensi ke dhtmlxcalendar.css dan calendar.js jika tidak digunakan lagi --}}
{{-- <link rel="stylesheet" href="{{ asset('calendar_trial/codebase/dhtmlxcalendar.css') }}"> --}}
{{-- <script type="text/javascript" src="{{ asset('calendar_trial/codebase/calendar.js') }}"></script> --}}

{{-- Tambahkan Font Awesome jika belum ada untuk ikon --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

@section('content')
{{-- Header --}}
<div class="proker-header-container">
    <header class="admin-header">
        <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
        <h1>Program Kerja</h1>
    </header>
</div>

<div class="admin-proker-container">
    <div class="program-card-container">

        {{-- filter and add buttons --}}
        <div class="filter-add-group">
            <div class="status-filters">
                <button class="filter-button active" data-filter="all">All</button>
                <button class="filter-button" data-filter="done">Complete</button>
                <button class="filter-button" data-filter="progress">Progress</button>
                <button class="filter-button" data-filter="upcoming">Upcoming</button>
            </div>
            <a href="{{ route('tambah-program-kerja') }}">
                <button class="add-proker-button">+ ADD NEW</button>
            </a>
        </div>

        <div class="program-list" id="programList">
            <div>Loading programs...</div>
        </div>
    </div>

    <aside class="calendar-notification">
        {{-- Struktur Kalender Kustom --}}
        <div class="calendar-container">
            <div class="calendar-header">
                <div class="calendar-month" id="calendarMonthYear">Mei 2024</div> {{-- Placeholder --}}
                <div class="calendar-buttons">
                    <div class="calendar-button today-button" id="calendarTodayButton" title="Go to Today">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="calendar-button prev-button" id="calendarPrevButton" title="Previous Month">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <div class="calendar-button next-button" id="calendarNextButton" title="Next Month">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
            <div class="calendar-weekdays">
                <div class="weekday">Min</div> {{-- Disesuaikan dengan Bahasa Indonesia --}}
                <div class="weekday">Sen</div>
                <div class="weekday">Sel</div>
                <div class="weekday">Rab</div>
                <div class="weekday">Kam</div>
                <div class="weekday">Jum</div>
                <div class="weekday">Sab</div>
            </div>
            <div class="calendar-days" id="calendarDaysContainer">
                {{-- Hari-hari akan dirender oleh JavaScript di sini --}}
            </div>
        </div>

        <div class="notification">
            <div class="notification-header">
                <h3>Notifikasi</h3> {{-- Disesuaikan ke Bahasa Indonesia --}}
                <img src="{{ asset('storage/bell.png') }}" alt="Notifications">
            </div>
            <div id="notification-list" style="padding:10px;">
                Tidak ada notifikasi baru.
            </div>
        </div>
    </aside>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- SEMUA KODE JAVASCRIPT DIGABUNG DI SINI ---

    // Bagian untuk Sidebar Toggle
    const menuIcon = document.querySelector('.toggle-sidebar-icon');
    const sidebar = document.querySelector('.admin-sidebar');
    if (menuIcon && sidebar) {
        menuIcon.addEventListener('click', (event) => {
            event.stopPropagation();
            sidebar.classList.toggle('active');
        });
        document.addEventListener('click', (event) => {
            if (!sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });
    }

    // Bagian untuk Program Kerja
    const programList = document.getElementById('programList');
    const filterButtons = document.querySelectorAll('.filter-button');
    const notificationList = document.getElementById('notification-list');

    // Elemen Kalender Kustom
    const calendarMonthYear = document.getElementById('calendarMonthYear');
    const calendarDaysContainer = document.getElementById('calendarDaysContainer');
    const prevButton = document.getElementById('calendarPrevButton');
    const nextButton = document.getElementById('calendarNextButton');
    const todayButton = document.getElementById('calendarTodayButton');

    let programs = [];
    let currentDate = new Date(); // Untuk navigasi kalender
    let selectedDate = null; // Untuk tanggal yang dipilih di kalender

    // --- AWAL BAGIAN LOGIKA KALENDER KUSTOM ---
    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    function renderCalendar() {
        calendarDaysContainer.innerHTML = '';
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        calendarMonthYear.textContent = `${monthNames[month]} ${year}`;
        const firstDayOfMonth = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDayOfMonth; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.classList.add('calendar-day', 'empty');
            calendarDaysContainer.appendChild(emptyCell);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement('div');
            dayCell.classList.add('calendar-day');
            dayCell.textContent = day;
            dayCell.dataset.date = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const today = new Date();
            if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                dayCell.classList.add('today');
            }
            if (selectedDate && year === selectedDate.getFullYear() && month === selectedDate.getMonth() && day === selectedDate.getDate()) {
                dayCell.classList.add('selected');
            }
            const programsOnThisDate = programs.filter(p => p.date === dayCell.dataset.date);
            if (programsOnThisDate.length > 0) {
                dayCell.classList.add('event');
                const eventIndicator = document.createElement('div');
                eventIndicator.classList.add('event-indicator');
                dayCell.appendChild(eventIndicator);
                dayCell.title = programsOnThisDate.map(p => p.title).join('\n');
            }
            dayCell.addEventListener('click', () => {
                const prevSelected = calendarDaysContainer.querySelector('.selected');
                if (prevSelected) {
                    prevSelected.classList.remove('selected');
                }
                dayCell.classList.add('selected');
                selectedDate = new Date(year, month, day);
                renderProgramsForDate(selectedDate);
            });
            calendarDaysContainer.appendChild(dayCell);
        }
    }

    prevButton.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextButton.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    todayButton.addEventListener('click', () => {
        currentDate = new Date();
        selectedDate = new Date();
        selectedDate.setHours(0, 0, 0, 0);
        renderCalendar();
        renderProgramsForDate(selectedDate);
    });

    function renderProgramsForDate(sDate) {
        if (!sDate) {
            programList.innerHTML = '<div>Pilih tanggal di kalender untuk melihat program.</div>';
            return;
        }
        const year = sDate.getFullYear();
        const month = String(sDate.getMonth() + 1).padStart(2, '0');
        const day = String(sDate.getDate()).padStart(2, '0');
        const selectedDateString = `${year}-${month}-${day}`;
        const programsOnDate = programs.filter(program => program.date === selectedDateString);
        if (programsOnDate.length > 0) {
            renderPrograms(programsOnDate, false);
        } else {
            programList.innerHTML = `<div>Tidak ada program kerja untuk tanggal ${day}/${month}/${year}.</div>`;
        }
        filterButtons.forEach(btn => btn.classList.remove('active'));
    }
    // --- AKHIR BAGIAN LOGIKA KALENDER KUSTOM ---


    // --- FUNGSI fetchPrograms YANG BARU DAN LEBIH PINTAR ---
    async function fetchPrograms() {
        const token = localStorage.getItem('token');
        const userRole = localStorage.getItem('userRole')?.toLowerCase() || 'rt';

        if (!token) {
            programList.innerHTML = '<div>Token tidak ditemukan. Silakan login kembali.</div>';
            return;
        }

        programList.innerHTML = '<div><i class="fas fa-spinner fa-spin"></i> Loading programs...</div>';

        try {
            // 1. Ambil data proker dan data profil pengguna secara bersamaan
            const [prokerResponse, meResponse] = await Promise.all([
                axios.get('https://sirtrw-api.vansite.cloud/api/proker', {
                    headers: { Authorization: `Bearer ${token}` }
                }),
                axios.get('https://sirtrw-api.vansite.cloud/api/me', {
                    headers: { Authorization: `Bearer ${token}` }
                })
            ]);

            const allPrograms = prokerResponse.data.data || [];
            const user = meResponse.data.data;
            const userRtId = user.warga?.rt_id;
            const userRwId = user.warga?.rw_id;

            // 2. Filter data proker berdasarkan role dan ID admin
            const filteredPrograms = allPrograms.filter(program => {
                if (userRole.includes('rw')) {
                    // Admin RW melihat proker tingkat RW
                    return program.rw_id === userRwId;
                } else { // Admin RT
                    // Admin RT melihat proker tingkat RT-nya saja
                    return program.rt_id === userRtId;
                }
            });

            console.log(`Menampilkan ${filteredPrograms.length} dari total ${allPrograms.length} program kerja.`);

            // 3. Simpan data yang SUDAH DIFILTER ke variabel global
            programs = filteredPrograms.map(program => {
                const dateObj = new Date(program.date);
                const normalizedDateObj = new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate());
                return {
                    ...program,
                    month: dateObj.toLocaleString('id-ID', { month: 'short' }).toUpperCase(),
                    day: dateObj.getDate(),
                    day_of_week: dateObj.toLocaleString('id-ID', { weekday: 'long' }),
                    normalized_date: normalizedDateObj,
                    status: program.status ? program.status.toLowerCase() : 'upcoming'
                };
            });

            // 4. Lanjutkan proses seperti biasa dengan data yang sudah bersih
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-filter') === 'all') btn.classList.add('active');
            });
            applyFilter('all');
            renderCalendar();
            loadNotifications(programs);

        } catch (error) {
            console.error('Error fetching programs:', error);
            let errorMsg = '<div>Gagal memuat data program kerja. Periksa koneksi Anda.</div>';
            if (error.response) {
                errorMsg = error.response.status === 401 ?
                    '<div>Sesi Anda telah berakhir. Silakan login kembali.</div>' :
                    `<div>Gagal memuat data (Error: ${error.response.status}).</div>`;
            }
            programList.innerHTML = errorMsg;
        }
    }


    function renderPrograms(programsToRender, setActiveFilterToAll = false) {
        if (!programsToRender || programsToRender.length === 0) {
            programList.innerHTML = '<div>Tidak ada program kerja yang sesuai dengan filter ini.</div>';
            return;
        }
        programList.innerHTML = '';
        programsToRender.forEach(program => {
            const card = document.createElement('div');
            let statusClass = program.status;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (program.status !== 'done' && program.normalized_date < today) {
                statusClass += ' past-due';
            }
            card.className = `program-card ${statusClass}`;
            card.setAttribute('data-id', program.id);
            card.setAttribute('data-date', program.date);
            const editUrl = `/admin/program-kerja/edit/${program.id}`;
            card.innerHTML = `
                <div class="date">
                    <div class="month">${program.month}</div>
                    <div class="day">${program.day}</div>
                    <div class="day-of-week">${program.day_of_week}</div>
                </div>
                <div class="details">
                    <h3>${program.title}</h3>
                    <p>${program.description ? program.description.substring(0, 100) + '...' : '<em>Tidak ada deskripsi.</em>'}</p>
                    <div class="time-location">
                        <span class="time"><i class="fas fa-clock"></i> ${program.time}</span> • <span class="location"><i class="fas fa-map-marker-alt"></i> ${program.location}</span>
                    </div>
                </div>
                <div class="proker-button-group">
                    <button class="copy-button" title="Salin Detail"><img src="{{ asset('storage/copy.png') }}" alt="Salin"></button>
                    <a href="${editUrl}"><button class="edit-button" title="Edit Program"><img src="{{ asset('storage/editPen.png') }}" alt="Edit"></button></a>
                    <button class="delete-button" title="Hapus Program"><img src="{{ asset('storage/trashPurple.png') }}" alt="Hapus"></button>
                </div>
            `;
            programList.appendChild(card);
            card.querySelector('.delete-button').addEventListener('click', () => handleDeleteProgram(program.id, program.title));
            card.querySelector('.copy-button').addEventListener('click', () => handleCopyProgram(program));
        });
        if (setActiveFilterToAll) {
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-filter') === 'all') btn.classList.add('active');
            });
        }
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            const filter = button.getAttribute('data-filter');
            applyFilter(filter);
        });
    });

    // --- FUNGSI applyFilter YANG DIMODIFIKASI ---
    function applyFilter(filter) {
        let filteredPrograms = [];
        const now = new Date(); // Waktu sekarang (lengkap dengan jam)

        if (filter === 'all') {
            filteredPrograms = programs;
        } else if (filter === 'done') {
            filteredPrograms = programs.filter(p => p.status === 'done');
        } else if (filter === 'progress') {
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate()); // Hari ini jam 00:00
            filteredPrograms = programs.filter(p => {
                // 1. Cek apakah program untuk hari ini
                const isToday = p.normalized_date.getTime() === today.getTime();
                if (!isToday) return false;

                // 2. Cek apakah statusnya 'complete'
                if (p.status === 'done') return false;

                // 3. Gabungkan tanggal dan waktu program, lalu bandingkan dengan waktu sekarang
                const programDateTime = new Date(`${p.date}T${p.time}`);
                return now >= programDateTime;
            });
        } else if (filter === 'upcoming') {
            filteredPrograms = programs.filter(p => {
                // Dianggap 'upcoming' jika belum selesai DAN waktu mulainya di masa depan
                if (p.status === 'done') return false;
                const programDateTime = new Date(`${p.date}T${p.time}`);
                return programDateTime > now;
            }).sort((a, b) => new Date(`${a.date}T${a.time}`) - new Date(`${b.date}T${b.time}`));
        }

        renderPrograms(filteredPrograms, filter === 'all');
    }

    // --- FUNGSI handleDeleteProgram YANG DIPERBAIKI ---
    function handleDeleteProgram(programId, programTitle) {
        if (confirm(`Apakah Anda yakin ingin menghapus program "${programTitle}"?`)) {
            const token = localStorage.getItem('token');
            // Menggunakan backtick ` untuk interpolasi string, bukan single quote '
            axios.delete(`https://sirtrw-api.vansite.cloud/api/proker/${programId}`, {
                    headers: { Authorization: `Bearer ${token}` }
                })
                .then(response => {
                    alert(`Program "${programTitle}" berhasil dihapus.`);
                    fetchPrograms();
                })
                .catch(error => {
                    console.error('Error deleting program:', error);
                    alert(`Gagal menghapus program: ${error.response ? error.response.data.message || error.message : error.message}`);
                });
        }
    }

    function handleCopyProgram(program) {
        // Membuat format tanggal yang lebih lengkap, contoh: "Sabtu, 14 Juni 2025"
        const fullDate = `${program.day_of_week}, ${program.day} ${program.month} ${program.normalized_date.getFullYear()}`;

        // Template pesan broadcast
        const broadcastText = `
📢✨ *PEMBERITAHUAN PROGRAM KERJA* ✨📢

Assalamualaikum Wr. Wb.
Bapak/Ibu Warga yang kami hormati,

Dengan ini kami sampaikan bahwa akan diadakan program kerja:

*Nama Program:*
${program.title}

*Deskripsi:*
${program.description || 'Tidak ada deskripsi.'}

*Waktu Pelaksanaan:*
🗓️ Hari/Tanggal: ${fullDate}
⏰ Pukul: ${program.time} WIB

*Tempat Pelaksanaan:*
📍 Lokasi: ${program.location}

Besar harapan kami atas partisipasi aktif dari seluruh warga untuk kesuksesan acara ini.

Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.

Hormat kami,
Pengurus RT/RW
`.trim(); // .trim() untuk menghapus spasi kosong di awal dan akhir

        // Menggunakan Clipboard API untuk menyalin teks
        navigator.clipboard.writeText(broadcastText)
            .then(() => {
                // Notifikasi sukses
                alert('Pesan broadcast berhasil disalin ke clipboard! 📋');
            })
            .catch(err => {
                // Notifikasi jika gagal
                console.error('Gagal menyalin teks: ', err);
                alert('Gagal menyalin detail. Browser Anda mungkin tidak mendukung fitur ini.');
            });
    }

    function loadNotifications(allPrograms) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const sevenDaysFromNow = new Date(today);
        sevenDaysFromNow.setDate(today.getDate() + 7);
        const upcomingNotifications = allPrograms.filter(program => {
            return program.status !== 'done' && program.normalized_date >= today && program.normalized_date <= sevenDaysFromNow;
        }).sort((a, b) => a.normalized_date - b.normalized_date);
        notificationList.innerHTML = '';
        if (upcomingNotifications.length > 0) {
            const ul = document.createElement('ul');
            ul.style.listStyleType = 'none';
            ul.style.paddingLeft = '0';
            upcomingNotifications.forEach(prog => {
                const li = document.createElement('li');
                const dayDiff = Math.ceil((prog.normalized_date - today) / (1000 * 60 * 60 * 24));
                let due = '';
                if (dayDiff === 0) due = '(Hari ini)';
                else if (dayDiff === 1) due = '(Besok)';
                else due = `(Dalam ${dayDiff} hari)`;
                li.innerHTML = `<strong>${prog.title}</strong><br><small>${prog.day_of_week}, ${prog.day} ${prog.month} ${due} - ${prog.time}</small>`;
                ul.appendChild(li);
            });
            notificationList.appendChild(ul);
        } else {
            notificationList.innerHTML = '<p style="text-align:center; color: #777;">Tidak ada notifikasi program kerja dalam waktu dekat.</p>';
        }
    }

    // Panggilan Awal
    fetchPrograms();
});
</script>
@endsection