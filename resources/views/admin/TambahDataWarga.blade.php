@extends('layouts.adminSidebar')

<title>SIMAS - Tambah Data Warga</title>
{{-- Anda bisa menggunakan CSS yang sama atau buat baru --}}
<link rel="stylesheet" href="{{ asset('css/tambah-mutasi.css') }}">

@section('content')
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
            <h1>Tambah Data Warga Baru</h1>
        </header>
    </div>

    <div class="form-wrapper">
        <form id="addWargaForm" class="keuangan-form">

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

            {{-- Bagian ini akan diisi otomatis oleh JavaScript --}}
            <div class="form-row">
                <label id="rt_rw_label" for="rt_id">Nomor RT :</label>
                <div id="rt_container">
                    {{-- JavaScript akan mengisi input atau select di sini --}}
                    <input type="text" id="rt_id" name="rt_id" placeholder="Memuat data admin..." readonly>
                </div>
            </div>

            <div id="responseMessage" style="grid-column: 2;"></div>

            <div class="submit-button-container">
                <button type="submit" class="simpan-button" id="submitButton">SIMPAN</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- 1. SETUP AWAL & DEFINISI ELEMEN ---
            const API_BASE_URL = 'https://sirtrw-api.vansite.cloud/api';
            const token = localStorage.getItem('token');
            const userRole = localStorage.getItem('userRole')?.toLowerCase() || 'rt';

            const form = document.getElementById('addWargaForm');
            const submitButton = document.getElementById('submitButton');
            const responseMessage = document.getElementById('responseMessage');
            const rtContainer = document.getElementById('rt_container');
            const rtRwLabel = document.getElementById('rt_rw_label');

            let adminRtId = null; // Untuk menyimpan ID RT admin jika dia admin RT

            // --- 2. FUNGSI UNTUK MENYIAPKAN FORM SESUAI ROLE ADMIN ---
            async function setupAdminForm() {
                if (!token) {
                    alert('Token tidak ditemukan. Silakan login kembali.');
                    return;
                }

                try {
                    const meResponse = await axios.get(`${API_BASE_URL}/me`, { headers: { Authorization: `Bearer ${token}` } });
                    const user = meResponse.data.data;

                    if (userRole.includes('rt')) {
                        // Jika Admin RT, isi dan kunci input RT
                        adminRtId = user.warga?.rt_id;
                        rtContainer.innerHTML = `<input type="number" id="rt_id" name="rt_id" value="${adminRtId}" readonly>`;
                    } else if (userRole.includes('rw')) {
                        // Jika Admin RW, buat dropdown daftar RT
                        rtRwLabel.textContent = 'Pilih RT :';
                        const wargaResponse = await axios.get(`${API_BASE_URL}/warga`, { headers: { Authorization: `Bearer ${token}` } });
                        const allWarga = wargaResponse.data.data || [];
                        const rtIds = [...new Set(allWarga.map(w => w.rt_id))].sort((a, b) => a - b);

                        let selectHTML = '<select id="rt_id" name="rt_id" required>';
                        selectHTML += '<option value="" disabled selected>-- Pilih Nomor RT --</option>';
                        rtIds.forEach(id => {
                            selectHTML += `<option value="${id}">RT ${String(id).padStart(3, '0')}</option>`;
                        });
                        selectHTML += '</select>';
                        rtContainer.innerHTML = selectHTML;
                    }
                } catch (error) {
                    console.error('Gagal mengambil data untuk setup form:', error);
                    alert('Gagal memuat data pendukung. Form tidak dapat digunakan.');
                    submitButton.disabled = true;
                }
            }

            // --- 3. EVENT LISTENER UNTUK SUBMIT FORM ---
            if (form) {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
                    responseMessage.innerHTML = '';

                    // Ambil rt_id, baik dari input (untuk admin RT) atau dari select (untuk admin RW)
                    const rtIdValue = document.getElementById('rt_id').value;

                    if (!rtIdValue) {
                        alert('Nomor RT harus dipilih atau tersedia.');
                        // Re-enable tombol jika validasi gagal
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'SIMPAN';
                        return;
                    }

                    // Kumpulkan data ke dalam objek, karena tidak ada file upload
                    const payload = {
                        name: document.getElementById('name').value,
                        nik: document.getElementById('nik').value,
                        birth: document.getElementById('birth').value,
                        address: document.getElementById('address').value,
                        rt_id: rtIdValue,
                    };

                    try {
                        const response = await axios.post(`${API_BASE_URL}/warga`, payload, {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });

                        if (response.data.success) {
                            alert('Data warga baru berhasil ditambahkan!');
                            form.reset();
                            // Arahkan kembali ke halaman data warga
                            window.location.href = '{{ route("data-warga") }}';
                        }
                    } catch (error) {
                        const errors = error.response?.data?.errors;
                        let errorMessage = 'Gagal menyimpan data.';
                        if (errors) {
                            errorMessage = Object.values(errors).map(e => e[0]).join('\n');
                        } else if (error.response?.data?.message) {
                            errorMessage = error.response.data.message;
                        }
                        responseMessage.innerHTML = `<div class="alert alert-danger" style="white-space: pre-line;">${errorMessage}</div>`;
                    } finally {
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'SIMPAN';
                    }
                });
            }

            // --- 4. PANGGIL FUNGSI INISIASI ---
            setupAdminForm();
        });
    </script>

@endsection