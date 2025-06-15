@extends('layouts.dashboardNavbar')

@section('title', 'SIMAS - Program Kerja')

<link rel="stylesheet" href="{{ asset('css/lihat-proker.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}"> --}}


@section('content')
    <?php
    $page = 'program-kerja';
    ?>

    @include('layouts.prokerSidebar') {{-- Merender HTML sidebar --}}

    <div class="proker-main">
        <div class="proker-container">
            <div class="search-filter">
                <input type="text" placeholder="Search..." id="searchInput">
                <select class="text-center" id="dateFilter">
                    <option value="">dd / mm / yy</option>
                </select>
            </div>

            <div class="sort-by">
                <div></div>
                <div class="sort-select-container">
                    <label for="sort-select">Sort by:</label>
                    <select id="sort-select">
                        <option value="latest_update">Latest update</option> {{-- Value diubah agar lebih jelas --}}
                        <option value="oldest_update">Oldest update</option> {{-- Value diubah agar lebih jelas --}}
                        <option value="event_date_newest">Event Date (Newest First)</option> {{-- OPSI BARU --}}
                        <option value="event_date_oldest">Event Date (Oldest First)</option> {{-- OPSI BARU --}}
                    </select>
                </div>
            </div>

            <div class="program-list" id="programList">
                <p>Loading programs...</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Elemen utama
            const programList = document.getElementById('programList');
            const searchInput = document.getElementById('searchInput');
            const sortSelect = document.getElementById('sort-select');
            const dateFilter = document.getElementById('dateFilter');

            // Elemen dari sidebar
            const rtToggle = document.getElementById('rt-toggle');
            const rwToggle = document.getElementById('rw-toggle');
            const rtContentFilters = document.getElementById('rt-content-filters');
            const rwContentFilters = document.getElementById('rw-content-filters');

            let allPrograms = [];
            let currentView = 'rt';
            let currentStatusFilter = 'progress';

            function fetchPrograms() {
                const token = localStorage.getItem('token');
                if (!token) { /* ... token error ... */ return; }
                programList.innerHTML = '<p>Mengambil data program...</p>';

                axios.get('http://127.0.0.1:8001/api/proker', { headers: { 'Authorization': `Bearer ${token}` } })
                .then(response => {
                    if (response.data && response.data.success && response.data.code === 200 && Array.isArray(response.data.data)) {
                        allPrograms = response.data.data.map(proker => {
                            const dateObj = new Date(proker.date);
                            if (isNaN(dateObj.getTime())) { // getTime() akan NaN jika Invalid Date
                                console.warn(`Tanggal tidak valid dari API untuk proker ID ${proker.id}: ${proker.date}`);
                            }
                            const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
                            const dayNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
                            return {
                                id: proker.id, title: proker.title, description: proker.description,
                                time: proker.time.substring(0, 5), location: proker.location, status: proker.status,
                                date: proker.date,
                                date_object: dateObj, // Objek Date
                                month: monthNames[dateObj.getMonth()],
                                day: dateObj.getDate().toString().padStart(2, '0'), day_of_week: dayNames[dateObj.getDay()],
                                updated_at: proker.updated_at, // Timestamp update
                                rt_id: proker.rt_id, rw_id: proker.rw_id
                            };
                        });
                        if (allPrograms.length === 0) { /* ... no programs found ... */ }
                        else {
                            updateInitialStatusFilter();
                            filterAndRenderPrograms();
                        }
                    } else { /* ... error handling ... */ }
                })
                .catch(error => { /* ... error handling ... */ });
            }

            function updateInitialStatusFilter() {
                let activeContent = currentView === 'rt' ? rtContentFilters : rwContentFilters;
                if (activeContent) {
                    const activeItem = activeContent.querySelector('.sidebar-item.active');
                    if (activeItem) {
                        currentStatusFilter = activeItem.dataset.statusFilter;
                    } else {
                        const mendatangItem = activeContent.querySelector('.sidebar-item[data-status-filter="progress"]');
                        if (mendatangItem) {
                            const items = activeContent.getElementsByClassName('sidebar-item');
                            for(let i of items) i.classList.remove('active');
                            mendatangItem.classList.add('active');
                            currentStatusFilter = 'progress';
                        }
                    }
                }
                // populateDateFilter dipanggil di akhir filterAndRenderPrograms agar selalu update
            }

            function renderPrograms(dataToRender) {
                programList.innerHTML = '';
                if (dataToRender.length === 0) {
                    programList.innerHTML = '<p>Tidak ada program yang sesuai dengan kriteria Anda.</p>';
                    return;
                }
                dataToRender.forEach(program => {
                    const card = document.createElement('div');
                    card.className = 'program-card';
                    card.setAttribute('data-date', program.date);
                    card.setAttribute('data-status', program.status);
                    let statusText = program.status === 'progress' ? 'Mendatang' : (program.status === 'done' ? 'Terlaksana' : program.status);
                    card.innerHTML = `
                        <div class="date">
                            <div class="month">${program.month}</div>
                            <div class="day">${program.day}</div>
                            <div class="day-of-week">${program.day_of_week}</div>
                        </div>
                        <div class="details">
                            <h3>${program.title}</h3>
                            <p>${program.description.substring(0, 100)}${program.description.length > 100 ? '...' : ''}</p>
                            <div class="time-location">
                                <span class="time">${program.time}</span> &bull; Lokasi di ${program.location}
                            </div>
                        </div>
                        <div class="status ${program.status.toLowerCase()}">${statusText}</div>
                        <div class="more-options" onclick="showProkerOptions(${program.id})">⋮</div>`;
                    programList.appendChild(card);
                 });
            }

            window.showProkerOptions = function(programId) { /* ... fungsi show options ... */ };

            function populateDateFilter() {
                // Ambil program yang sedang ditampilkan (setelah semua filter diterapkan KECUALI filter tanggal itu sendiri)
                const searchTerm = searchInput.value.toLowerCase();
                // const selectedDate = dateFilter.value; // Jangan gunakan ini untuk membuat opsi

                let programsForDateOptions = allPrograms.filter(program => {
                    const matchesView = (currentView === 'rt' && program.rt_id !== null) ||
                                        (currentView === 'rw' && program.rw_id !== null);
                    if (!matchesView) return false;

                    const matchesSearch = program.title.toLowerCase().includes(searchTerm) ||
                                          program.description.toLowerCase().includes(searchTerm);
                    const matchesStatus = program.status === currentStatusFilter;
                    return matchesSearch && matchesStatus;
                });

                const uniqueDates = [...new Set(programsForDateOptions.map(p => p.date))]
                    .sort((a, b) => new Date(b) - new Date(a)); // 🔥 Urutkan: Terbaru (descending) ke Terlama (ascending)

                const currentValue = dateFilter.value; // Simpan nilai yang sedang dipilih
                dateFilter.innerHTML = '<option value="">dd / mm / yy</option>';
                uniqueDates.forEach(dateString => {
                    const option = document.createElement('option');
                    option.value = dateString;
                    const [year, month, day] = dateString.split('-');
                    option.textContent = `${day} / ${month} / ${year}`;
                    dateFilter.appendChild(option);
                 });
                dateFilter.value = currentValue; // Set kembali nilai yang dipilih sebelumnya jika masih ada
            }

            function filterAndRenderPrograms() {
                if (!allPrograms || allPrograms.length === 0) {
                    programList.innerHTML = '<p>Data program belum tersedia atau kosong.</p>';
                    return;
                }

                const searchTerm = searchInput.value.toLowerCase();
                const selectedDate = dateFilter.value;
                const sortBy = sortSelect.value;

                console.log("Menerapkan filter dengan view:", currentView, "status:", currentStatusFilter, "search:", searchTerm, "date:", selectedDate, "sort:", sortBy);

                let filtered = allPrograms.filter(program => {
                    const matchesView = (currentView === 'rt' && program.rt_id !== null) ||
                                        (currentView === 'rw' && program.rw_id !== null);
                    if (!matchesView) return false;

                    const matchesSearch = program.title.toLowerCase().includes(searchTerm) ||
                                          program.description.toLowerCase().includes(searchTerm);
                    const matchesDate = selectedDate ? program.date === selectedDate : true;
                    const matchesStatus = program.status === currentStatusFilter;
                    return matchesSearch && matchesDate && matchesStatus;
                });

                // Log sebelum sorting
                // console.log("Data sebelum diurutkan (maks 5):", filtered.slice(0, 5).map(p => ({title: p.title, event_date: p.date, date_obj: p.date_object, updated_at: p.updated_at}) ));

                filtered.sort((a, b) => {
                    // Pastikan date_object a dan b adalah objek Date yang valid sebelum memanggil getTime()
                    const timeA_event = a.date_object instanceof Date ? a.date_object.getTime() : null;
                    const timeB_event = b.date_object instanceof Date ? b.date_object.getTime() : null;
                    const timeA_update = new Date(a.updated_at).getTime();
                    const timeB_update = new Date(b.updated_at).getTime();

                    if (sortBy === 'latest_update') {
                        if (isNaN(timeA_update) || isNaN(timeB_update)) return 0; // Hindari error jika updated_at tidak valid
                        return timeB_update - timeA_update;
                    } else if (sortBy === 'oldest_update') {
                        if (isNaN(timeA_update) || isNaN(timeB_update)) return 0;
                        return timeA_update - timeB_update;
                    } else if (sortBy === 'event_date_newest') {
                        if (timeA_event === null || timeB_event === null || isNaN(timeA_event) || isNaN(timeB_event)) {
                            // console.warn("Objek tanggal acara tidak valid saat sorting:", a.title, b.title);
                            return 0; // Atau strategi lain untuk tanggal tidak valid
                        }
                        return timeB_event - timeA_event;
                    } else if (sortBy === 'event_date_oldest') {
                        if (timeA_event === null || timeB_event === null || isNaN(timeA_event) || isNaN(timeB_event)) {
                            // console.warn("Objek tanggal acara tidak valid saat sorting:", a.title, b.title);
                            return 0;
                        }
                        return timeA_event - timeB_event;
                    }
                    return 0;
                });

                // Log setelah sorting
                // console.log("Data setelah diurutkan (maks 5):", filtered.slice(0, 5).map(p => ({title: p.title, event_date: p.date, date_obj: p.date_object, updated_at: p.updated_at}) ));

                renderPrograms(filtered);
                populateDateFilter();
            }

            // --- Event Listeners ---
            if (rtToggle) {
                rtToggle.addEventListener('click', () => {
                    if (currentView === 'rt' && rtToggle.classList.contains('active')) return;
                    currentView = 'rt';
                    updateInitialStatusFilter();
                    filterAndRenderPrograms();
                });
            }

            if (rwToggle) {
                rwToggle.addEventListener('click', () => {
                    if (currentView === 'rw' && rwToggle.classList.contains('active')) return;
                    currentView = 'rw';
                    updateInitialStatusFilter();
                    filterAndRenderPrograms();
                });
            }

            function addFilterItemListeners(contentElement) {
                if (contentElement) {
                    Array.from(contentElement.getElementsByClassName('sidebar-item')).forEach(item => {
                        item.addEventListener('click', function() {
                            currentStatusFilter = this.dataset.statusFilter;
                            const parentContentId = this.closest('.sidebar-content').id;
                            if ((currentView === 'rt' && parentContentId === 'rt-content-filters') ||
                                (currentView === 'rw' && parentContentId === 'rw-content-filters')) {
                                filterAndRenderPrograms();
                            }
                        });
                    });
                }
            }

            addFilterItemListeners(rtContentFilters);
            addFilterItemListeners(rwContentFilters);

            searchInput.addEventListener('input', filterAndRenderPrograms);
            sortSelect.addEventListener('change', filterAndRenderPrograms); // Listener untuk sort
            dateFilter.addEventListener('change', filterAndRenderPrograms); // Listener untuk filter tanggal

            fetchPrograms();
        });
    </script>
@endsection