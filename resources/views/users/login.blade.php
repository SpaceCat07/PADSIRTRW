@extends('layouts.landingNavbar')

<title>SIMAS - Login</title>
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

@section('content')
<div class="login-container d-flex justify-content-center align-items-center">
    <div class="login-vector col-md-2 mb-2 mb-md-0">
        <a href="/" class="logo d-inline-flex link-body-emphasis text-decoration-none">
            <img src="{{ asset('storage/Woman.png') }}" alt="Logo" width="100%">
        </a>
    </div>

    <div class="login-card p-5 shadow-lg text-white">
        <h2 class="text-center mb-3 fw-bold text-white">Log In</h2>

        <!-- Login Form -->
        <form id="loginForm">
            <div class="mb-4">
                <label for="floatingEmail" class="form-label fw-semibold">Email</label>
                <input type="text" class="form-control rounded-pill px-3" name="email" id="floatingEmail"
                    placeholder="Enter your email" required>
            </div>

            <div class="mb-4 position-relative">
                <label for="floatingPassword" class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control rounded-pill px-3" name="password" id="floatingPassword"
                    placeholder="Enter your password" required>
                <span class="position-absolute password-toggle" style="right: 15px; top: 56px; cursor: pointer;">
                    <i class="fas fa-eye" id="togglePassword"></i>
                </span>
            </div>

            <!-- Error Message -->
            <div id="loginError" class="alert alert-danger d-none"></div>

            <!-- Success Message -->
            <div id="loginSuccess" class="alert alert-success d-none"></div>

            <div class="text-center">
                <button type="submit" class="submit-btn btn-warning">Masuk</button>
            </div>
        </form>

        <p class="text-center mt-4">Lupa password anda? 
            <a href="{{ route('forgot-password') }}" class="text-warning fw-bold">Klik disini untuk mengubah password</a>
        </p>

        <p class="text-center">
            Belum punya akun? 
            <a href="{{ route('account.requestCreate') }}" class="text-warning fw-bold">Klik untuk request akun</a>
        </p>
    </div>
</div>

<script>
    // Objek ini HARUS TETAP di sini karena dibuat oleh Blade.
    // auth.js akan menggunakan variabel global ini.
    window.roleRedirectRoutes = {
        Admin_RT: "{{ route('admin-dashboard') }}",
        Admin_RW: "{{ route('admin-dashboard') }}",
        Warga: "{{ route('dashboard.warga') }}"
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script src="{{ asset('js/auth.js') }}"></script>

@endsection