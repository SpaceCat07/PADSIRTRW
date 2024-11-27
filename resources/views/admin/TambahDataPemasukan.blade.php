@extends('layouts.adminSidebar')

@section('content')
    {{-- Header --}}
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
            <h1>Tambah Data Pemasukan</h1>
        </header>
    </div>

    <div class="edit-keuangan-container">
        <form action="#" method="POST" class="edit-program-form" onsubmit="return confirm('Apakah Anda yakin ingin menyimpan perubahan?');">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="amount">Nominal</label>
                <input type="text" id="amount" name="amount" placeholder="Masukkan Nominal" required>
            </div>

            <div class="form-group">
                <label for="start_date">Tanggal</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <input type="text" id="description" name="description" placeholder="Masukkan Deskripsi" required>
            </div>

            <div class="form-group">
                <label for="gambar">Bukti</label>
                <input type="file" id="gambar" name="gambar" accept="image/jpeg, image/png" required
                    style="display: none;">
                <label for="gambar" class="custom-file-upload">
                    + Upload JPG/PNG
                </label>
            </div>

            <div class="submit-button-group">
                <button type="submit" class="proker-submit-button">SIMPAN</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('.edit-program-form');

            form.addEventListener('submit', (event) => {
                const amount = document.getElementById('amount').value.trim();
                const description = document.getElementById('description').value.trim();
                const startDate = document.getElementById('start_date').value;

                let isValid = true;
                let errorMessages = [];

                if (amount === '') {
                    isValid = false;
                    errorMessages.push('Nominal tidak boleh kosong.');
                }

                if (description === '') {
                    isValid = false;
                    errorMessages.push('Deskripsi tidak boleh kosong.');
                }

                if (startDate === '') {
                    isValid = false;
                    errorMessages.push('Tanggal Mulai harus diisi.');
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
