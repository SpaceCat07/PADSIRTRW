<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <!-- bootstrap for carousel -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.2/css/bootstrap.min.css">
    <!-- bootstrap and css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- script for carousel -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <!-- datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Fonts -->
</head>

<body>
    <div class="admin-sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('storage/Logo.png') }}" alt="Logo" width="40%">
        </div>

        <div class="admin-sidebar-content active" id="rt-content">
            <div
                class="admin-sidebar-item {{ request()->routeIs('admin-dashboard') || request()->routeIs('data-warga') ? 'active' : '' }}">
                <a href="{{ route('admin-dashboard') }}">
                    <img class="default-icon" src="{{ asset('storage/home.png') }}" alt="">
                    <img class="active-icon" src="{{ asset('storage/homeWhite.png') }}" alt=""
                        style="display: none;">
                    Dashboard
                </a>
            </div>
            <div class="admin-sidebar-item {{ request()->routeIs('admin-program-kerja') || request()->routeIs('edit-program-kerja') || request()->routeIs('tambah-program-kerja') ? 'active' : '' }}">
                <a href="{{ route('admin-program-kerja') }}">
                    <img class="default-icon" src="{{ asset('storage/prokerList.png') }}" alt="">
                    <img class="active-icon" src="{{ asset('storage/prokerListWhite.png') }}" alt=""
                        style="display: none;">
                    Program Kerja
                </a>
            </div>
            <div class="admin-sidebar-item {{ request()->routeIs('laporan-pengeluaran') || request()->routeIs('laporan-pemasukan') || request()->routeIs('tambah-data-pemasukan') || request()->routeIs('tambah-data-pengeluaran') ? 'active' : '' }}"
                role="button" data-bs-toggle="collapse" data-bs-target="#collapseLaporan" aria-expanded="false"
                aria-controls="collapseLaporan">
                <img class="default-icon" src="{{ asset('storage/keuanganSack.png') }}" alt="">
                <img class="active-icon" src="{{ asset('storage/keuanganSackWhite.png') }}" alt=""
                    style="display: none;">
                Laporan Keuangan
            </div>

            <div class="collapse {{ request()->routeIs('laporan-pengeluaran') || request()->routeIs('laporan-pemasukan') || request()->routeIs('tambah-data-pengeluaran') || request()->routeIs('tambah-data-pemasukan') ? 'show' : '' }}"
                id="collapseLaporan">
                <ul class="list-unstyled ps-3">
                    <li>
                        <a class="admin-sidebar-item nav-link {{ request()->routeIs('laporan-pengeluaran') || request()->routeIs('tambah-data-pengeluaran') ? 'active' : '' }}"
                            href="{{ route('laporan-pengeluaran') }}">
                            Laporan Pengeluaran
                        </a>
                    </li>
                    <li>
                        <a class="admin-sidebar-item nav-link {{ request()->routeIs('laporan-pemasukan') || request()->routeIs('tambah-data-pemasukan') ? 'active' : '' }}"
                            href="{{ route('laporan-pemasukan') }}">
                            Laporan Pemasukan
                        </a>
                    </li>
                </ul>
            </div>
            <div class="admin-sidebar-item {{ request()->routeIs('admin-kritik-saran') ? 'active' : '' }}">
                <a href="{{ route('admin-kritik-saran') }}">
                    <img class="default-icon" src="{{ asset('storage/kritikSaranMail.png') }}" alt="">
                    <img class="active-icon" src="{{ asset('storage/kritikSaranMailWhite.png') }}" alt=""
                        style="display: none;">
                    Kritik dan Saran
                </a>
            </div>
        </div>

        <div class="admin-sidebar-content" id="rw-content"></div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set the active state based on the existing classes
            document.querySelectorAll('.admin-sidebar-item').forEach(item => {
                if (item.classList.contains('active')) {
                    // Show active icon and hide default icon
                    const activeIcon = item.querySelector('.active-icon');
                    const defaultIcon = item.querySelector('.default-icon');
                    if (activeIcon && defaultIcon) {
                        activeIcon.style.display = 'block';
                        defaultIcon.style.display = 'none';
                    }
                }
            });

            // Add click event listener for sidebar items
            document.querySelectorAll('.admin-sidebar-item').forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all items
                    document.querySelectorAll('.admin-sidebar-item').forEach(i => {
                        i.classList.remove('active');
                        // Hide active icons and show default icons
                        const activeIcon = i.querySelector('.active-icon');
                        const defaultIcon = i.querySelector('.default-icon');
                        if (activeIcon && defaultIcon) {
                            activeIcon.style.display = 'none';
                            defaultIcon.style.display = 'block';
                        }
                    });

                    // Add active class to the clicked item
                    this.classList.add('active');
                    // Show active icon and hide default icon
                    const clickedActiveIcon = this.querySelector('.active-icon');
                    const clickedDefaultIcon = this.querySelector('.default-icon');
                    if (clickedActiveIcon && clickedDefaultIcon) {
                        clickedActiveIcon.style.display = 'block';
                        clickedDefaultIcon.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/carousel.js') }}"></script>
</body>

</html>
