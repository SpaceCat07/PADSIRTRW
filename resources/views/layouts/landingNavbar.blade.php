<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Your website description">
    <meta name="keywords" content="Your website keywords">
    <title>SIMAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap');
    </style>
</head>

<body>
    <!-- header -->
    <header class="navbar navbar-expand-md py-3 mb-4 bg-white border-bottom position-fixed w-100 z-3">
        <div class="container-fluid">
        <a href="/" class="logo d-inline-flex text-decoration-none">
            <img src="{{ asset('storage/Logo.png') }}" alt="Logo" width="150px" loading="lazy">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="/" class="nav-link link-active">Home</a>
                </li>
                <li class="nav-item">
                    <a href="/#about-us" class="nav-link link-idle">About Us</a>
                </li>
                <li class="nav-item">
                    <a href="/#contact-section" class="nav-link link-idle">Contact</a>
                </li>
            </ul>
        </div>
        </div>
    </header>

    <!-- main content -->
    <div class="main-content" style="padding-top: 100px;"> <!-- Add padding to avoid overlap -->
        @yield('content')
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

</body>

</html>
