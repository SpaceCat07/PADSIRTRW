@extends('layouts.landingNavbar')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="login-card p-5 shadow-lg bg-white rounded">
        <h2 class="text-center mb-3">Log In to Sistem RTRW</h2>
        <p class="text-center text-muted mb-4">Quick & Simple way to Automate your payment</p>

        <form action="{{route('login')}}" method="post">
            @csrf
            <!-- Floating label for email (username) field -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="email" id="floatingEmail"
                    placeholder="johndoe@example.com" value="{{ old('email') }}">
                <label for="floatingEmail">EMAIL ADDRESS</label>
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
            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" name="password" id="floatingPassword"
                    placeholder="*********" value="">
                <label for="floatingPassword">PASSWORD</label>
                <!-- Eye icon to toggle password visibility -->
                <span class="position-absolute password-toggle" style="right: 10px; top: 15px; cursor: pointer;">
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

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="terms" id="terms" value="true">
                <label class="form-check-label text-muted" for="terms">
                    I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#"
                        class="text-decoration-none">Privacy Policy</a>.
                </label>
            </div>

            <button type="submit" class="btn btn-dark w-100 py-2">Masuk</button>
            @if(session('error'))
                <div class="alert alert-success">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('aktivasi'))
                <div class="alert alert-success">
                    {{ session('aktivasi') }}
                </div>
            @endif
            @if(session('akun'))
                <div class="">
                    {{ session('akun') }}
                </div>
            @endif
        </form>

        <p class="text-center mt-4">
            Belum punya akun? <a href="#" class="text-primary">Klik untuk request akun</a>
        </p>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // Toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Toggle the eye icon
        this.classList.toggle('fa-eye-slash');
    });
</script>

@endsection