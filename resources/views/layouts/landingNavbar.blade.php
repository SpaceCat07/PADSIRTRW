<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap');
    </style>
</head>

<body>
    <div class="header-container">
        <!-- header -->
        <header
            class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="col-md-3 mb-2 mb-md-0">
                <a href="/" class="logo d-inline-flex link-body-emphasis text-decoration-none">
                    <img src="{{ asset('storage/Logo.png') }}" alt="Logo" width="40%">
                </a>
            </div>

            <ul class="nav col-12 col-md-auto mb-2 justify-content-right mb-md-0">
                <li><a href="/" class="nav-link px-2 link-active">Home</a></li>
                <li><a href="/#about-us" class="nav-link px-2 link-idle">About Us</a></li>
                <li><a href="/#contact-section" class="nav-link px-2 link-idle">Contact</a></li>
            </ul>

            <!-- <div class="col-md-3 text-end">
                <button type="button" class="btn btn-outline-primary me-2">Login</button>
                <button type="button" class="btn btn-primary">Sign-up</button>
            </div> -->
        </header>
    </div>

    <!-- main content -->
    @yield('content')


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        crossorigin="anonymous"></script>
</body>

</html>