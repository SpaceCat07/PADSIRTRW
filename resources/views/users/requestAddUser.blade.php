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
            <h1>Request Akun SIMAS</h1>
        </header>
    </div>

    <div class="user-registration-form-container">
        {{-- Ganti form lama Anda dengan form yang sudah dimodifikasi ini --}}
        <form id="requestAccountForm" class="user-registration-form">
            <div class="form-group">
                <label for="nik">Nomor Induk Kependudukan (NIK)</label>
                {{-- ID dan Name diubah --}}
                <input type="text" id="nik" name="nik" placeholder="Masukkan 16 digit NIK yang sudah didaftarkan oleh admin"
                    required pattern="\d{16}" title="NIK harus terdiri dari 16 digit angka.">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                {{-- ID dan Name diubah --}}
                <input type="email" id="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                {{-- ID dan Name diubah --}}
                <input type="tel" id="phone" name="phone" placeholder="Phone Number" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                {{-- ID dan Name diubah --}}
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>

            {{-- Field konfirmasi password ditambahkan untuk UX yang lebih baik --}}
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Ketik ulang password Anda" required>
            </div>

            {{-- Dropdown untuk Role DIHAPUS untuk keamanan --}}

            {{-- Tempat untuk menampilkan pesan dari JavaScript --}}
            <div id="responseMessage" class="mt-3"></div>

            <div class="submit-button-group">
                <button type="submit" id="submitButton" class="register-button">Register</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Menargetkan form dengan ID baru
            const form = document.getElementById('requestAccountForm');
            const submitButton = document.getElementById('submitButton');
            const responseMessage = document.getElementById('responseMessage');

            // Hapus event listener sidebar jika tidak ada sidebar di halaman ini
            // ...

            if (form) {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';
                    responseMessage.innerHTML = '';

                    const nik = document.getElementById('nik').value;
                    const email = document.getElementById('email').value;
                    const phone = document.getElementById('phone').value;
                    const password = document.getElementById('password').value;
                    const passwordConfirmation = document.getElementById('password_confirmation').value;

                    if (password !== passwordConfirmation) {
                        responseMessage.innerHTML = `<div class="alert alert-danger p-2">Konfirmasi password tidak cocok!</div>`;
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'Kirim Permintaan';
                        return;
                    }

                    const payload = { nik, email, phone, password, role: 'Warga' };
                    const apiUrl = 'https://sirtrw-api.vansite.cloud/api/request';

                    try {
                        const response = await axios.post(apiUrl, payload);

                        if (response.data.success) {
                            form.reset();
                            responseMessage.innerHTML = '<div class="alert alert-success p-2">Permintaan berhasil dikirim! Akun Anda akan segera diaktivasi oleh admin.</div>';

                            // Mengubah tombol menjadi tombol redirect
                            submitButton.innerHTML = 'Kembali ke Halaman Utama';
                            submitButton.dataset.action = 'redirect';
                            submitButton.disabled = false;

                            form.setAttribute('novalidate', true);
                        }

                    } catch (error) {
                        // --- PERUBAHAN UTAMA ADA DI SINI ---
                        let friendlyMessage = 'Terjadi kesalahan. Silakan coba lagi nanti.'; // Pesan default
                        const serverMessage = error.response?.data?.message || error.message;

                        if (serverMessage) {
                            // 1. Cek apakah pesan error mengandung teks spesifik dari SQL
                            if (serverMessage.includes('Duplicate entry') && serverMessage.includes('users_warga_id_unique')) {
                                // 2. Jika ya, ganti dengan pesan yang user-friendly
                                friendlyMessage = 'Akun untuk NIK ini sudah terdaftar. Silakan coba login atau gunakan fitur Lupa Password.';
                            } else {
                                // 3. Jika error lain, tampilkan pesan asli dari server
                                friendlyMessage = serverMessage;
                            }
                        }

                        responseMessage.innerHTML = `<div class="alert alert-danger p-2">${friendlyMessage}</div>`;
                        // --- AKHIR PERUBAHAN ---

                    } finally {
                        // Hanya aktifkan tombol jika belum dalam mode redirect
                        if (submitButton.dataset.action !== 'redirect') {
                            submitButton.disabled = false;
                            submitButton.innerHTML = 'Kirim Permintaan';
                        }
                    }
                });
            }
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