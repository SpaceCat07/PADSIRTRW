@extends('layouts.adminSidebar')

<title>SIMAS - Edit Jenis Iuran</title>
<link rel="stylesheet" href="{{ asset('css/tambah-mutasi.css') }}">

@section('content')
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
            {{-- Judul diubah --}}
            <h1>Edit Jenis Iuran</h1>
        </header>
    </div>

    <div class="form-wrapper">
        {{-- ID form diubah --}}
        <form id="editIuranForm" class="keuangan-form">

            <div class="form-row">
                <label for="name">Nama Iuran :</label>
                <input type="text" id="name" name="name" placeholder="Contoh: Iuran Keamanan, Iuran 17an" required>
            </div>

            <div class="form-row">
                <label for="value">Nominal :</label>
                <input type="text" id="value" name="value" placeholder="Masukkan Nominal" required>
            </div>

            <div class="form-row">
                <label for="variance">Jenis Iuran :</label>
                <select id="variance" name="variance" required>
                    <option value="" disabled>-- Pilih Jenis --</option>
                    <option value="monthly">Bulanan</option>
                    <option value="additional">Tambahan</option>
                </select>
            </div>

            <div class="form-row" id="month-form-row" style="display: none;">
                <label for="month">Untuk Bulan :</label>
                <select id="month" name="month">
                    <option value="" disabled>-- Pilih Bulan --</option>
                    <option value="January">Januari</option>
                    <option value="February">Februari</option>
                    <option value="March">Maret</option>
                    <option value="April">April</option>
                    <option value="May">Mei</option>
                    <option value="June">Juni</option>
                    <option value="July">Juli</option>
                    <option value="August">Agustus</option>
                    <option value="September">September</option>
                    <option value="October">Oktober</option>
                    <option value="November">November</option>
                    <option value="December">Desember</option>
                </select>
            </div>

            <div id="responseMessage" style="grid-column: 2;"></div>

            <div class="submit-button-container">
                {{-- Teks tombol diubah --}}
                <button type="submit" class="simpan-button" id="submitButton">UPDATE IURAN</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- 1. SETUP AWAL & DEFINISI ELEMEN ---
            const API_BASE_URL = 'https://sirtrw-api.vansite.cloud/api';
            const token = localStorage.getItem('token');

            // Mengambil ID iuran dari URL
            const urlParts = window.location.pathname.split('/');
            const iuranId = urlParts[urlParts.length - 1];

            const form = document.getElementById('editIuranForm');
            const submitButton = document.getElementById('submitButton');
            const responseMessage = document.getElementById('responseMessage');
            const nominalInput = document.getElementById('value');
            const varianceSelect = document.getElementById('variance');
            const monthFormRow = document.getElementById('month-form-row');
            const monthSelect = document.getElementById('month');
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');

            // --- 2. FUNGSI UTAMA (INIT) & PENGISIAN FORM ---
            async function initializePage() {
                if (!token || !iuranId || isNaN(iuranId)) {
                    alert('Error: Data tidak valid atau sesi telah berakhir.');
                    submitButton.disabled = true;
                    return;
                }

                try {
                    // Ambil data iuran yang spesifik
                    const response = await axios.get(`${API_BASE_URL}/iuran/${iuranId}`, {
                        headers: { Authorization: `Bearer ${token}` }
                    });

                    const iuranData = response.data.data;
                    populateForm(iuranData);

                } catch (error) {
                    console.error('Gagal memuat data iuran:', error);
                    alert('Gagal memuat data iuran yang akan diedit.');
                    submitButton.disabled = true;
                }
            }

            function populateForm(iuran) {
                document.getElementById('name').value = iuran.name;
                nominalInput.value = iuran.value; // Akan diformat oleh UX helper
                varianceSelect.value = iuran.variance;

                // Atur tampilan input bulan sesuai data
                if (iuran.variance === 'monthly') {
                    monthFormRow.style.display = 'contents';
                    monthSelect.required = true;
                    monthSelect.value = iuran.month;
                } else {
                    monthFormRow.style.display = 'none';
                    monthSelect.required = false;
                }

                // Panggil event 'keyup' secara manual untuk format Rupiah
                nominalInput.dispatchEvent(new Event('keyup'));
            }

            // --- 3. EVENT LISTENER ---
            varianceSelect.addEventListener('change', () => {
                if (varianceSelect.value === 'monthly') {
                    monthFormRow.style.display = 'contents';
                    monthSelect.required = true;
                } else {
                    monthFormRow.style.display = 'none';
                    monthSelect.required = false;
                }
            });

            if (form) {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengupdate...';
                    responseMessage.innerHTML = '';

                    const payload = new FormData();
                    payload.append('name', document.getElementById('name').value);
                    payload.append('value', nominalInput.value.replace(/[^0-9]/g, ''));
                    payload.append('variance', varianceSelect.value);
                    if (varianceSelect.value === 'monthly') {
                        payload.append('month', monthSelect.value);
                    }

                    // Method Spoofing untuk permintaan PUT
                    payload.append('_method', 'PUT');

                    try {
                        // Kirim sebagai POST tapi dengan _method=PUT
                        const response = await axios.post(`${API_BASE_URL}/iuran/${iuranId}`, payload, {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });

                        if (response.data.success) {
                            alert('Data iuran berhasil diupdate!');
                            window.location.href = '{{ route("manajemen-iuran") }}';
                        }
                    } catch (error) {
                        const errorMessage = error.response?.data?.message || 'Gagal mengupdate data.';
                        responseMessage.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
                    } finally {
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'UPDATE IURAN';
                    }
                });
            }

            // --- 4. UX HELPER ---
            // Format input nominal menjadi Rupiah saat diketik
            nominalInput.addEventListener('keyup', function (e) {
                let cursorPosition = this.selectionStart;
                let value = parseInt(this.value.replace(/[^0-9]/g, ''), 10);
                let originalLength = this.value.length;
                if (isNaN(value)) { this.value = ""; return; }
                this.value = "Rp " + value.toLocaleString('id-ID');
                let newLength = this.value.length;
                cursorPosition = newLength - (originalLength - cursorPosition);
                this.setSelectionRange(cursorPosition, cursorPosition);
            });

            // sidebar
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
            // --- 5. INISIALISASI ---
            initializePage();
        });
    </script>
@endsection