@extends('layouts.adminSidebar')

<title>SIMAS - Tambah Jenis Iuran</title>
<link rel="stylesheet" href="{{ asset('css/tambah-mutasi.css') }}">

@section('content')
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
            <h1>Tambah Jenis Iuran Baru</h1>
        </header>
    </div>

    <div class="form-wrapper">
        <form id="addIuranForm" class="keuangan-form">

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
                    <option value="" disabled selected>-- Pilih Jenis --</option>
                    <option value="monthly">Bulanan</option>
                    <option value="additional">Tambahan</option>
                </select>
            </div>

            {{-- Input Bulan ini akan muncul/hilang secara dinamis --}}
            <div class="form-row" id="month-form-row" style="display: none;">
                <label for="month">Untuk Bulan :</label>
                <select id="month" name="month">
                    <option value="" disabled selected>-- Pilih Bulan --</option>
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
                <button type="submit" class="simpan-button" id="submitButton">SIMPAN IURAN</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- 1. SETUP AWAL & DEFINISI ELEMEN ---
            const API_BASE_URL = 'https://sirtrw-api.vansite.cloud/api';
            const token = localStorage.getItem('token');

            const form = document.getElementById('addIuranForm');
            const submitButton = document.getElementById('submitButton');
            const responseMessage = document.getElementById('responseMessage');
            const nominalInput = document.getElementById('value');
            const varianceSelect = document.getElementById('variance');
            const monthFormRow = document.getElementById('month-form-row');
            const monthSelect = document.getElementById('month');
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');

            let adminRtId = null;
            let adminRwId = null;

            // --- 2. FUNGSI UNTUK MENGAMBIL DATA ADMIN ---
            async function setupAdminInfo() {
                if (!token) {
                    alert('Sesi Anda tidak valid. Silakan login kembali.');
                    submitButton.disabled = true;
                    return;
                }
                try {
                    const response = await axios.get(`${API_BASE_URL}/me`, {
                        headers: { Authorization: `Bearer ${token}` }
                    });
                    const user = response.data.data;
                    adminRtId = user.warga?.rt_id;
                    adminRwId = user.warga?.rw_id;
                } catch (error) {
                    console.error('Gagal mengambil data admin:', error);
                    alert('Gagal memuat informasi admin. Form tidak dapat digunakan.');
                    submitButton.disabled = true;
                }
            }

            // --- 3. EVENT LISTENER ---

            // Tampilkan/sembunyikan input bulan berdasarkan jenis iuran
            varianceSelect.addEventListener('change', () => {
                if (varianceSelect.value === 'monthly') {
                    monthFormRow.style.display = 'contents';
                    monthSelect.required = true;
                } else {
                    monthFormRow.style.display = 'none';
                    monthSelect.required = false;
                }
            });

            // Event listener untuk submit form
            if (form) {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
                    responseMessage.innerHTML = '';

                    const userRole = localStorage.getItem('userRole')?.toLowerCase();

                    if ((userRole.includes('rt') && !adminRtId) || (userRole.includes('rw') && !adminRwId)) {
                        alert('Error: Data admin tidak lengkap. Silakan muat ulang halaman.');
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'SIMPAN IURAN';
                        return;
                    }

                    // Siapkan payload dasar
                    const payload = {
                        name: document.getElementById('name').value,
                        value: nominalInput.value.replace(/[^0-9]/g, ''),
                        variance: varianceSelect.value,
                    };

                    // Tambahkan bulan HANYA jika jenisnya 'monthly'
                    if (payload.variance === 'monthly') {
                        payload.month = monthSelect.value;
                    }

                    // Tambahkan rt_id atau rw_id secara otomatis
                    if (userRole.includes('rt')) {
                        payload.rt_id = adminRtId;
                    } else if (userRole.includes('rw')) {
                        payload.rw_id = adminRwId;
                    }

                    try {
                        const response = await axios.post(`${API_BASE_URL}/iuran`, payload, {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });

                        if (response.data.success) {
                            alert('Jenis iuran baru berhasil ditambahkan!');
                            form.reset();
                            monthFormRow.style.display = 'none'; // Sembunyikan lagi setelah reset
                            window.location.href = '{{ route("manajemen-iuran") }}'; // Arahkan ke halaman daftar iuran
                        }
                    } catch (error) {
                        const errorMessage = error.response?.data?.message || 'Gagal menyimpan data.';
                        responseMessage.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
                    } finally {
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'SIMPAN IURAN';
                    }
                });
            }

            // --- 4. UX HELPERS (Format Rupiah) ---
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

            // --- 5. PANGGIL FUNGSI INISIASI ---
            setupAdminInfo();
        });
    </script>
@endsection