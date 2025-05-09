<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- bootstrap for carousel -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.2/css/bootstrap.min.css">
    <!-- bootstrap and css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/checkout-navbar.css') }}">
    <!-- script for carousel -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <!-- datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Fonts -->
    <style>
        .link-active {
            color: #03A9F4;
            font-weight: 600;
            border-bottom: none;
            transition: all 0.3s ease;
        }
    </style>
</head>

<body>
    <div class="header-container" style="background-color: #676AFF;">
        <!-- header -->
        <header
            class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="col-md-3 mb-2 mb-md-0">
                <a href="/" class="logo d-inline-flex link-body-emphasis text-decoration-none">
                    <img src="{{ asset('storage/LogoSIMASputih.png') }}" alt="Logo" width="40%">
                </a>
            </div>

            <ul class="nav col-12 col-md-auto mb-2 justify-content-right mb-md-0">
                <li>
                    <a href="/dashboard/warga"
                        class="nav-link px-2 {{ $page == 'dashboard' ? 'link-active' : 'link-idle' }}"
                        style="{{ $page == 'dashboard' ? 'color: #FFD25D;' : 'color: white;' }}">Dashboard</a>
                </li>
                <li>
                    <a href="/program-kerja"
                        class="nav-link px-2 {{ $page == 'program-kerja' ? 'link-active' : 'link-idle' }}"
                        style="{{ $page == 'program-kerja' ? 'color: #FFD25D;' : 'color: white;' }}">Program Kerja</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#"
                        class="nav-link dropdown-toggle px-2 {{ $page == 'pembayaran' ? 'link-active' : 'link-idle' }}"
                        id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="{{ $page == 'pembayaran' ? 'color: #FFD25D;' : 'color: white;' }}">
                        Pembayaran
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('pembayaran') }}">Bayar</a></li>
                        <li><a class="dropdown-item" href="{{ route('riwayat-pembayaran') }}">Riwayat</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"
                        class="nav-link px-2 {{ $page == 'kritik-saran' ? 'link-active' : 'link-idle' }}"
                        style="{{ $page == 'kritik-saran' ? 'color: #FFD25D;' : 'color: white;' }}">Kritik Saran</a>
                </li>
                <li>
                    <div class="dropdown text-end me-3">
                        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://github.com/mdo.png" alt="mdo" width="38" height="38"
                                class="rounded-circle ms-2 align-middle">
                        </a>
                        <ul class="dropdown-menu text-small">
                            <li><a class="dropdown-item" href="#">New project...</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="post"> @csrf
                                    <button type="submit">Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>

            <!-- <div class="col-md-3 text-end">
                <button type="button" class="btn btn-outline-primary me-2">Login</button>
                <button type="button" class="btn btn-primary">Sign-up</button>
            </div> -->
        </header>
    </div>

    <!-- main content and sidebar -->
    @yield('content')



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/carousel.js') }}"></script>
</body>

</html>
