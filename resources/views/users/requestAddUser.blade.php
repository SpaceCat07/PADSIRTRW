@extends('layouts.landingNavbar')

@section('content')
    <?php
    $page = 'dashboard'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    {{-- Header --}}
    <div class="data-warga-container">
        <header class="admin-header">
            <h1>Register User</h1>
        </header>
    </div>

    <div class="edit-keuangan-container">
        <form action="{{ route('account.requestStore') }}" method="POST" class="edit-program-form"
            onsubmit="return confirm('Apakah Anda yakin ingin menyimpan perubahan?');">
            @csrf

            <div class="form-group">
                <label for="nik">NIK</label>
                <input type="text" id="nik" name="nik" placeholder="NIK" required>
                @error('nik')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
                @error('email')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="no_hp">Nomor Telepon</label>
                <input type="tel" id="no_hp" name="no_hp" placeholder="Nomor Telepon" required>
                @error('no_hp')
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
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    @foreach ($roleList as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>
                @error('role')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="submit-button-group">
                <button type="submit" class="proker-submit-button">Register</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('.edit-program-form');

            form.addEventListener('submit', (event) => {
                const nik = document.getElementById('nik').value.trim();
                const email = document.getElementById('email').value.trim();
                const no_hp = document.getElementById('no_hp').value.trim();
                const password = document.getElementById('password').value.trim();

                let isValid = true;
                let errorMessages = [];

                if (nik === '') {
                    isValid = false;
                    errorMessages.push('NIK tidak boleh kosong.');
                }

                if (email === '') {
                    isValid = false;
                    errorMessages.push('Email tidak boleh kosong.');
                }

                if (no_hp === '') {
                    isValid = false;
                    errorMessages.push('Nomor Telepon tidak boleh kosong.');
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
