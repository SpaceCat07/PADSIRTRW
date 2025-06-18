@extends('layouts.adminSidebar')

<title>SIMAS - Edit Data Warga</title>
<link rel="stylesheet" href="{{ asset('css/tambah-mutasi.css') }}">

@section('content')
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
            {{-- Judul diubah --}}
            <h1>Edit Data Warga</h1>
        </header>
    </div>

    <div class="form-wrapper">
        {{-- ID form diubah --}}
        <form id="editWargaForm" class="keuangan-form">

            <div class="form-row">
                <label for="name">Nama Lengkap :</label>
                <input type="text" id="name" name="name" placeholder="Masukkan Nama Lengkap" required>
            </div>

            <div class="form-row">
                <label for="nik">NIK :</label>
                <input type="text" id="nik" name="nik" placeholder="Masukkan 16 digit NIK" required pattern="\d{16}"
                    title="NIK harus terdiri dari 16 digit angka.">
            </div>

            <div class="form-row">
                <label for="birth">Tanggal Lahir :</label>
                <input type="date" id="birth" name="birth" required>
            </div>

            <div class="form-row">
                <label for="address">Alamat Lengkap :</label>
                <textarea id="address" name="address" placeholder="Masukkan Alamat Lengkap sesuai KTP" required
                    rows="3"></textarea>
            </div>

            <div class="form-row">
                <label id="rt_rw_label" for="rt_id">Nomor RT :</label>
                <div id="rt_container">
                    {{-- JavaScript akan mengisi input atau select di sini --}}
                    <input type="text" id="rt_id_display" placeholder="Memuat data..." readonly>
                </div>
            </div>

            <div id="responseMessage" style="grid-column: 2;"></div>

            <div class="submit-button-container">
                <button type="submit" class="simpan-button" id="submitButton">UPDATE DATA</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- 1. SETUP & MENDAPATKAN ID DARI URL ---
            const API_BASE_URL = 'https://sirtrw-api.vansite.cloud/api';
            const token = localStorage.getItem('token');

            // Mengambil ID warga dari URL (contoh: /edit/39 -> 39)
            const urlParts = window.location.pathname.split('/');
            const wargaId = urlParts[urlParts.length - 1];

            // Definisi Elemen
            const form = document.getElementById('editWargaForm');
            const submitButton = document.getElementById('submitButton');
            const responseMessage = document.getElementById('responseMessage');
            const rtContainer = document.getElementById('rt_container');
            const rtRwLabel = document.getElementById('rt_rw_label');

            // --- 2. FUNGSI UTAMA (INIT) ---
            async function initializePage() {
                if (!token) {
                    alert('Token tidak ditemukan. Silakan login kembali.');
                    return;
                }
                if (!wargaId || isNaN(wargaId)) {
                    alert('ID Warga tidak valid.');
                    return;
                }

                try {
                    // Ambil data warga spesifik dan data admin secara bersamaan
                    const [wargaResponse, meResponse] = await Promise.all([
                        axios.get(`${API_BASE_URL}/warga/${wargaId}`, { headers: { Authorization: `Bearer ${token}` } }),
                        axios.get(`${API_BASE_URL}/me`, { headers: { Authorization: `Bearer ${token}` } })
                    ]);

                    const wargaData = wargaResponse.data.data;
                    const adminData = meResponse.data.data;

                    // Panggil fungsi untuk mengisi form dengan data yang didapat
                    populateForm(wargaData, adminData);

                } catch (error) {
                    console.error('Gagal memuat data awal:', error);
                    alert('Gagal memuat data warga yang akan diedit.');
                }
            }

            // --- 3. FUNGSI UNTUK MENGISI FORM ---
            function populateForm(warga, admin) {
                // Isi field-field standar
                document.getElementById('name').value = warga.name;
                document.getElementById('nik').value = warga.nik;
                document.getElementById('birth').value = warga.birth;
                document.getElementById('address').value = warga.address;

                // Logika untuk menampilkan input atau dropdown RT
                const userRole = localStorage.getItem('userRole')?.toLowerCase();
                if (userRole.includes('rt')) {
                    // Jika Admin RT, tampilkan nomor RT-nya dan kunci
                    rtContainer.innerHTML = `<input type="number" id="rt_id" value="${warga.rt_id}" readonly>`;
                } else if (userRole.includes('rw')) {
                    // Jika Admin RW, kita perlu daftar semua warga untuk membuat dropdown
                    rtRwLabel.textContent = 'Pilih RT :';
                    axios.get(`${API_BASE_URL}/warga`, { headers: { Authorization: `Bearer ${token}` } })
                        .then(response => {
                            const allWarga = response.data.data || [];
                            const rtIds = [...new Set(allWarga.map(w => w.rt_id))].sort((a, b) => a - b);

                            let selectHTML = '<select id="rt_id" name="rt_id" required>';
                            rtIds.forEach(id => {
                                // Pilih RT yang sesuai dengan data warga saat ini
                                const isSelected = id === warga.rt_id ? 'selected' : '';
                                selectHTML += `<option value="${id}" ${isSelected}>RT ${String(id).padStart(3, '0')}</option>`;
                            });
                            selectHTML += '</select>';
                            rtContainer.innerHTML = selectHTML;
                        });
                }
            }

            // --- 4. EVENT LISTENER UNTUK SUBMIT FORM (UPDATE) ---
            if (form) {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengupdate...';
                    responseMessage.innerHTML = '';

                    // Gunakan FormData untuk mengirim data, ini cara paling umum dan kompatibel
                    // Terutama jika di masa depan Anda ingin menambahkan upload foto profil
                    const formData = new FormData();
                    formData.append('name', document.getElementById('name').value);
                    formData.append('nik', document.getElementById('nik').value);
                    formData.append('birth', document.getElementById('birth').value);
                    formData.append('address', document.getElementById('address').value);
                    formData.append('rt_id', document.getElementById('rt_id').value);

                    // Method Spoofing: Kirim sebagai POST tapi beri tahu backend ini adalah PUT
                    formData.append('_method', 'PUT');

                    try {
                        // Kirim sebagai POST ke URL spesifik warga
                        const response = await axios.post(`${API_BASE_URL}/warga/${wargaId}`, formData, {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });

                        if (response.data.success) {
                            alert('Data warga berhasil diupdate!');
                            // Arahkan kembali ke halaman data warga
                            window.location.href = '{{ route("data-warga") }}';
                        }
                    } catch (error) {
                        const errors = error.response?.data?.errors;
                        let errorMessage = 'Gagal mengupdate data.';
                        if (errors) {
                            errorMessage = Object.values(errors).map(e => e[0]).join('\n');
                        } else if (error.response?.data?.message) {
                            errorMessage = error.response.data.message;
                        }
                        responseMessage.innerHTML = `<div class="alert alert-danger" style="white-space: pre-line;">${errorMessage}</div>`;
                    } finally {
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'UPDATE DATA';
                    }
                });
            }

            // --- 5. INISIALISASI ---
            initializePage();
        });
    </script>

@endsection