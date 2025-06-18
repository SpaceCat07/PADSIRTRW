@extends('layouts.adminSidebar')

<title>SIMAS - Aktivasi Akun Warga</title>
<link rel="stylesheet" href="{{ asset('css/tambah-proker.css') }}">

@section('content')
    {{-- Header --}}
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
            <h1>Aktivasi Akun Warga</h1>
        </header>
    </div>

    {{-- Container Form --}}
    <div class="edit-program-container">
        {{-- Form Aktivasi Akun --}}
        <form id="activationForm" class="edit-program-form">

            {{-- Diberi class "form-description" dan style dihapus --}}
            <p class="form-description">
                Masukkan NIK warga yang telah mengajukan permintaan akun untuk mengaktifkannya.
            </p>

            {{-- Diberi class "form-group-full-width" dan style dihapus --}}
            <div class="form-group form-group-full-width">
                <label for="nik">NIK Warga</label>
                <input type="text" id="nik" name="nik" placeholder="Masukkan 16 digit NIK yang akan diaktivasi" required
                    pattern="\d{16}" title="NIK harus terdiri dari 16 digit angka.">
            </div>

            {{-- Style dihapus, akan diatur oleh #responseMessage di CSS --}}
            <div id="responseMessage"></div>

            {{-- Style dihapus, akan diatur oleh .submit-button-group di CSS --}}
            <div class="submit-button-group">
                <button type="submit" id="submitButton" class="proker-submit-button">AKTIVASI AKUN</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- 1. DEFINISI ELEMEN ---
            const form = document.getElementById('activationForm');
            const submitButton = document.getElementById('submitButton');
            const nikInput = document.getElementById('nik');
            const responseMessage = document.getElementById('responseMessage');
            const token = localStorage.getItem('token');

            // --- 2. VALIDASI TOKEN AWAL ---
            if (!token) {
                alert('Sesi Anda tidak valid. Silakan login kembali.');
                submitButton.disabled = true;
                return;
            }

            // --- 3. EVENT LISTENER UNTUK SUBMIT FORM ---
            if (form) {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengaktivasi...';
                    responseMessage.innerHTML = '';

                    // Siapkan payload yang hanya berisi NIK
                    const payload = {
                        nik: nikInput.value
                    };

                    const apiUrl = 'https://sirtrw-api.vansite.cloud/api/activate';

                    try {
                        const response = await axios.post(apiUrl, payload, {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });

                        if (response.data.success) {
                            const activatedUser = response.data.data;
                            // Tampilkan pesan sukses yang jelas
                            responseMessage.innerHTML = `<div class="alert alert-success">Aktivasi berhasil! Akun untuk email ${activatedUser.email} sekarang sudah aktif.</div>`;
                            form.reset(); // Kosongkan input NIK
                        }

                    } catch (error) {
                        // Tampilkan pesan error dari server (misal: NIK tidak ditemukan, akun sudah aktif)
                        const message = error.response?.data?.message || 'Gagal melakukan aktivasi.';
                        responseMessage.innerHTML = `<div class="alert alert-danger">${message}</div>`;
                    } finally {
                        // Kembalikan tombol ke keadaan semula
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'AKTIVASI AKUN';
                    }
                });
            }

            // --- Sidebar Toggle Logic (opsional, jika ada di layout) ---
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');
            if (menuIcon && sidebar) {
                menuIcon.addEventListener('click', (event) => {
                    event.stopPropagation();
                    sidebar.classList.toggle('active');
                });
                document.addEventListener('click', (event) => {
                    if (sidebar.classList.contains('active') && !sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                        sidebar.classList.remove('active');
                    }
                });
            }
        });
    </script>
@endsection