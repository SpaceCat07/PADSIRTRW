@extends('layouts.landingNavbar')

@section('content')
<div class="login-container d-flex justify-content-center align-items-center">

    <div class="login-vector col-md-2 mb-2 mb-md-0">
        <a href="/" class="logo d-inline-flex link-body-emphasis text-decoration-none">
            <img src="{{ asset('storage/Woman.png') }}" alt="Logo" width="100%">
        </a>
    </div>

    <div class="login-card p-5 shadow-lg text-white">
        <h2 class="text-center mb-3 fw-bold">Log In</h2>

        <form action="{{route('login')}}" method="post">
            @csrf
            <!-- Floating label for email (username) field -->
            <div class="mb-4">
                <label for="floatingEmail" class="form-label fw-semibold">Email</label>
                <input type="text" class="form-control rounded-pill px-3" name="email" id="floatingEmail"
                    placeholder="Enter your email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="alert alert-danger mt-2">
                        {{$message}}
                    </div>
                @enderror
                <!-- @if (session('email'))
                    <div class="alert alert-danger mt-2">{{ session('email') }}</div>
                @endif -->
            </div>

            <!-- Floating label for password field -->
            <div class="mb-4 position-relative">
                <label for="floatingPassword" class="form-label fw-semibold">PASSWORD</label>
                <input type="password" class="form-control rounded-pill px-3" name="password" id="floatingPassword"
                    placeholder="Enter your password" value="" required>
                <!-- Eye icon to toggle password visibility -->
                <span class="position-absolute password-toggle" style="right: 15px; top: 56px; cursor: pointer;">
                    <i class="fas fa-eye" id="togglePassword"></i>
                </span>
                @error('password')
                    <div class="alert alert-danger mt-2">
                        {{$message}}
                    </div>
                @enderror
                <!-- @if (session('password'))
                    <div class="alert alert-danger mt-2">{{ session('password') }}</div>
                @endif -->
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn btn-warning w-100 rounded-pill py-2">Masuk</button>

            <!-- Messages -->
            @if(session('error'))
                <div class="alert alert-danger mt-2">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('aktivasi'))
                <div class="alert alert-danger mt-2">
                    {{ session('aktivasi') }}
                </div>
            @endif
            @if(session('akun'))
                <div class="alert alert-danger mt-2">
                    {{ session('akun') }}
                </div>
            @endif
        </form>

        <p class="text-center mt-4">
            Belum punya akun? <a href="{{route('account.requestCreate')}}" class="text-warning fw-bold">Klik untuk
                request akun</a>
        </p>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#floatingPassword');

    togglePassword.addEventListener('click', function () {
        const isPasswordVisible = password.getAttribute('type') === 'password';
        password.setAttribute('type', isPasswordVisible ? 'text' : 'password');
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });

    const form = document.querySelector('form');
    form.addEventListener('submit', function (e) {
        const terms = document.querySelector('#terms');
        if (!terms.checked) {
            e.preventDefault();
            alert('Anda harus menyetujui syarat dan ketentuan.');
        }
    });

</script>

@endsection