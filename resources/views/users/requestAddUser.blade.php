@extends('layouts.landingNavbar')

<title>SIMAS - Request Akun</title>
<link rel="stylesheet" href="{{ asset('css/account-request.css') }}">

@section('content')
    <?php
    $currentPage = 'dashboard'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    {{-- Header --}}
    <div class="user-registration-container">
        <header class="admin-header">
            <h1>Register User</h1>
        </header>
    </div>

    <div class="user-registration-form-container">
        <form action="{{ route('account.requestStore') }}" method="POST" class="user-registration-form"
            onsubmit="return confirm('Apakah Anda yakin ingin menyimpan perubahan?');">
            @csrf

            <div class="form-group">
                <label for="nationalIdentityNumber">National Identity Number (NIK)</label>
                <input type="text" id="nationalIdentityNumber" name="nationalIdentityNumber" placeholder="NIK" required>
                @error('nationalIdentityNumber')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="emailAddress">Email Address</label>
                <input type="email" id="emailAddress" name="emailAddress" placeholder="Email Address" required>
                @error('emailAddress')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Phone Number" required>
                @error('phoneNumber')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
                @error('password')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="userRole">User  Role</label>
                <select name="userRole" id="userRole" required>
                    @foreach ($roleList as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>
                @error('userRole')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="submit-button-group">
                <button type="submit" class="register-button">Register</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('.user-registration-form');

            form.addEventListener('submit', (event) => {
                const nationalIdentityNumber = document.getElementById('nationalIdentityNumber').value.trim();
                const emailAddress = document.getElementById('emailAddress').value.trim();
                const phoneNumber = document.getElementById('phoneNumber').value.trim();
                const password = document.getElementById('password').value.trim();

                let isValid = true;
                let errorMessages = [];

                if (nationalIdentityNumber === '') {
                    isValid = false;
                    errorMessages.push('National Identity Number (NIK) tidak boleh kosong.');
                }

                if (emailAddress === '') {
                    isValid = false;
                    errorMessages.push('Email Address tidak boleh kosong.');
                }

                if (phoneNumber === '') {
                    isValid = false;
                    errorMessages.push('Phone Number tidak boleh kosong.');
                }

                if (password === '') {
                    isValid = false;
                    errorMessages.push('Password tidak boleh kosong.');
                }

                if (!isValid) {
                    event.preventDefault();
                    alert(errorMessages.join('\n'));
                }
            });
        });

        // Toggle sidebar
        document.addEventListener('DOMContentLoaded', () => {
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');

            menuIcon.addEventListener('click', (event) => {
                event.stopPropagation();
                sidebar.classList.toggle('active');
            });

            document.addEventListener('click', (event) => {
                if (!sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            });
        });
    </script>
@endsection