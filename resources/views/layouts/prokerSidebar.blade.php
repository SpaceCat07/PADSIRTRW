<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Kerja</title>

    <!-- Bootstrap and CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

</head>
<?php
$page = 'program-kerja'; // or 'program-kerja', 'pembayaran', etc.
?>
<body>

    <div class="sidebar">
        <div class="toggle-container">
            <div class="toggle-item active" id="rt-toggle">RT</div>
            <div class="vertical-line"></div>
            <div class="toggle-item" id="rw-toggle">RW</div>
        </div>

        {{-- Filter untuk RT --}}
        <div class="sidebar-content active" id="rt-content-filters">
            <div class="sidebar-item active" data-status-filter="progress"> {{-- Default "Mendatang" aktif untuk RT --}}
                Mendatang
            </div>
            <div class="sidebar-item" data-status-filter="done">
                Sudah Terlaksana
            </div>
        </div>

        {{-- Filter untuk RW --}}
        <div class="sidebar-content" id="rw-content-filters"> {{-- ID diubah agar jelas ini untuk filter RW --}}
            <div class="sidebar-item active" data-status-filter="progress"> {{-- Default "Mendatang" aktif untuk RW juga --}}
                Mendatang
            </div>
            <div class="sidebar-item" data-status-filter="done">
                Sudah Terlaksana
            </div>
        </div>
    </div>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const rtToggle = document.getElementById('rt-toggle');
        const rwToggle = document.getElementById('rw-toggle');
        const rtContent = document.getElementById('rt-content-filters'); // Sesuai dengan ID di HTML Anda
        const rwContent = document.getElementById('rw-content-filters'); // Sesuai dengan ID di HTML Anda

        // Fungsi untuk menangani klik pada item filter (Mendatang/Sudah Terlaksana)
        function handleFilterItemClick(containerElement, clickedItem) {
            const items = containerElement.getElementsByClassName('sidebar-item');
            if (!clickedItem.classList.contains('active')) { // Hanya proses jika item belum aktif
                for (let item of items) {
                    item.classList.remove('active');
                }
                clickedItem.classList.add('active');
                // Pemanggilan filter data utama akan dilakukan oleh skrip di lihat-proker.blade.php
            }
        }

        if (rtToggle && rwToggle && rtContent && rwContent) {
            rtToggle.addEventListener('click', () => {
                // Hanya proses jika toggle RT belum aktif
                if (!rtToggle.classList.contains('active')) {
                    rtToggle.classList.add('active');
                    rwToggle.classList.remove('active');

                    rtContent.classList.add('active');    // Tampilkan filter RT
                    rwContent.classList.remove('active'); // Sembunyikan filter RW
                    // Skrip di lihat-proker.blade.php akan menangani pembaruan data
                }
            });

            rwToggle.addEventListener('click', () => {
                // Hanya proses jika toggle RW belum aktif
                if (!rwToggle.classList.contains('active')) {
                    rwToggle.classList.add('active');
                    rtToggle.classList.remove('active');

                    rwContent.classList.add('active');    // Tampilkan filter RW
                    rtContent.classList.remove('active'); // Sembunyikan filter RT
                    // Skrip di lihat-proker.blade.php akan menangani pembaruan data
                }
            });

            // Event listeners untuk item filter di dalam RT content
            Array.from(rtContent.getElementsByClassName('sidebar-item')).forEach(item => {
                item.addEventListener('click', function() {
                    handleFilterItemClick(rtContent, this);
                });
            });

            // Event listeners untuk item filter di dalam RW content
            Array.from(rwContent.getElementsByClassName('sidebar-item')).forEach(item => {
                item.addEventListener('click', function() {
                    handleFilterItemClick(rwContent, this);
                });
            });
        } else {
            console.error("Sidebar Error: Elemen rt-toggle, rw-toggle, rt-content-filters, atau rw-content-filters tidak ditemukan. Periksa ID elemen.");
        }
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
