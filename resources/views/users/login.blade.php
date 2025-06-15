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
    const roleRedirectRoutes = {
        Admin_RT: "{{ route('admin-dashboard') }}",
        Admin_RW: "{{ route('admin-dashboard') }}",
        Warga: "{{ route('dashboard.warga') }}"
    };
</script>

<!-- Axios CDN -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#floatingPassword');

    togglePassword.addEventListener('click', function () {
        const isVisible = passwordField.getAttribute('type') === 'password';
        passwordField.setAttribute('type', isVisible ? 'text' : 'password');
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });

    const loginForm = document.getElementById('loginForm');
    const loginError = document.getElementById('loginError');
    const loginSuccess = document.getElementById('loginSuccess');

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        loginError.classList.add('d-none');
        loginSuccess.classList.add('d-none');

        const email = document.querySelector('#floatingEmail').value;
        const password = document.querySelector('#floatingPassword').value;

        // Hardcode API base URL to 127.0.0.1:8001 as user wants access only via 127.0.0.1
        const apiBaseUrl = 'https://sirtrw-api.vansite.cloud';

        axios.post(apiBaseUrl + '/api/login', {
            email: email,
            password: password
        })
        .then(function (response) {
            // Tampilkan pesan berhasil
            loginSuccess.textContent = 'Login berhasil! Redirecting...';
            loginSuccess.classList.remove('d-none');

            // Simpan token ke localStorage
            localStorage.setItem('token', response.data.data.token);

            // Ambil role dari response
            const userRole = response.data.data.user.role;

            // Simpan role ke localStorage
            localStorage.setItem('userRole', userRole);

            // Redirect berdasarkan role
            console.log('Role:', userRole);
            console.log('Redirect URL:', roleRedirectRoutes[userRole]);

            let redirectUrl = roleRedirectRoutes[userRole] || '/';

            setTimeout(() => {
                window.location.href = redirectUrl;
            }, 1500);
        })
        .catch(function (error) {
            if (error.response && error.response.data && error.response.data.message) {
                loginError.textContent = error.response.data.message;
            } else {
                loginError.textContent = 'Terjadi kesalahan saat login.';
            }
            loginError.classList.remove('d-none');
        });

    });
</script>
@endsection
