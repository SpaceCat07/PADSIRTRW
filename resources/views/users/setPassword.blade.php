@extends('layouts.landingNavbar')

@section('content')
    <div class="login-container d-flex justify-content-center align-items-center">
        <div class="login-card p-5 shadow-lg text-white">
            @if (session()->has('passwordForgot'))
                <form action="{{ route('reset-password.update', $user->id) }}" method="post">
                    @csrf
                    @method('patch')

                    <div class="mb-4 position-relative">
                        <label for="newPassword" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control rounded-pill px-3" name="password" id="newPassword"
                            placeholder="Password baru" required>
                        <!-- Eye icon to toggle password visibility -->
                        <span class="position-absolute password-toggle" style="right: 15px; top: 56px; cursor: pointer;">
                            <i class="fas fa-eye" id="toggleNewPassword"></i>
                        </span>
                        @error('password')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4 position-relative">
                        <label for="confirmPassword" class="form-label fw-semibold">Konfirmasi Password</label>
                        <input type="password" class="form-control rounded-pill px-3" name="password_confirmation"
                            id="confirmPassword" placeholder="Konfirmasi password baru" required>
                        <!-- Eye icon to toggle password visibility -->
                        <span class="position-absolute password-toggle" style="right: 15px; top: 56px; cursor: pointer;">
                            <i class="fas fa-eye" id="toggleConfirmPassword"></i>
                        </span>
                        @error('password_confirmation')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="submit-btn btn-warning" style="max-width: 200px;">Reset Password</button>
                    </div>

                </form>
            @else
                <p>anda belum dapat mengubah password konfirmasi email dan nik terlebih dahulu <a
                        href="{{ route('forgot-password') }}">disini</a></p>
            @endif

        </div>
    </div>

    <script>
        const toggleNewPassword = document.querySelector('#toggleNewPassword');
        const newPassword = document.querySelector('#newPassword');
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#confirmPassword');

        toggleNewPassword.addEventListener('click', function() {
            const isPasswordVisible = newPassword.getAttribute('type') === 'password';
            newPassword.setAttribute('type', isPasswordVisible ? 'text' : 'password');
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });

        toggleConfirmPassword.addEventListener('click', function() {
            const isPasswordVisible = confirmPassword.getAttribute('type') === 'password';
            confirmPassword.setAttribute('type', isPasswordVisible ? 'text' : 'password');
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    </script>
@endsection
