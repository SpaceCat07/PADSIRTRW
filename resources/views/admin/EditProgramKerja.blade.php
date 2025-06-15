@extends('layouts.adminSidebar')

@section('content')
<title>SIMAS - Tambah Program Kerja</title>
<link rel="stylesheet" href="{{ asset('css/tambah-proker.css') }}">
    {{-- Header --}}
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
            <h1>Edit Program Kerja</h1>
        </header>
    </div>

    <div class="edit-program-container">
        <div id="loading-spinner" style="display: none; text-align: center; padding: 50px;">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
            <p>Memuat data...</p>
        </div>

        <form class="edit-program-form" style="display: none;">
            {{-- Form akan ditampilkan oleh JavaScript setelah data dimuat --}}

            {{-- Judul Program --}}
            <div class="form-group">
                <label for="title">Judul Program</label>
                <input type="text" id="title" name="title" placeholder="Masukkan Judul Program" required>
            </div>

            <div class="form-row">
                {{-- Waktu Pelaksanaan --}}
                <div class="form-group">
                    <label for="time">Waktu Pelaksanaan</label>
                    <input type="time" id="time" name="time" required>
                </div>

                {{-- Tanggal Pelaksanaan --}}
                <div class="form-group">
                    <label for="date">Tanggal Pelaksanaan</label>
                    <input type="date" id="date" name="date" required>
                </div>
            </div>

            {{-- Lokasi --}}
            <div class="form-group">
                <label for="location">Lokasi</label>
                <input type="text" id="location" name="location" placeholder="Masukkan Lokasi Pelaksanaan" required>
            </div>

            <div class="form-row">
                {{-- Status Program --}}
                <div class="form-group">
                    <label for="status">Status Program</label>
                    <select id="status" name="status" required>
                        <option value="" disabled>-- Pilih Status --</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="progress">Progress</option>
                        <option value="done">Done</option>
                    </select>
                </div>

                {{-- RT ID --}}
                <div class="form-group">
                    <label for="rt_id">RT ID</label>
                    <input type="number" id="rt_id" name="rt_id" placeholder="Masukkan ID RT" required>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label for="description">Deskripsi Program</label>
                <textarea id="description" name="description" rows="5" placeholder="Masukkan Deskripsi Program" required></textarea>
            </div>

            {{-- Gambar --}}
            <div class="form-group">
                <label>Gambar Saat Ini</label>
                <img id="current-image-preview" src="" alt="Tidak ada gambar" style="max-width: 200px; border-radius: 8px; margin-bottom: 10px; display: none;">
                <p id="no-image-text" style="display: none; color: #777;">Tidak ada gambar yang diunggah.</p>
            </div>

            <div class="form-group">
                <label for="image">Ganti Gambar (Opsional)</label>
                <input type="file" id="image" name="image" accept="image/jpeg, image/png" style="display: none;">
                <label for="image" class="custom-file-upload">
                    <i class="fas fa-upload"></i> Upload JPG/PNG
                </label>
                <span id="file-name" style="margin-left: 10px; font-style: italic; color: #555;"></span>
            </div>

            {{-- Tombol Aksi --}}
            <div class="submit-button-group">
                <button type="submit" class="proker-submit-button">SIMPAN PERUBAHAN</button>
                <a href="{{ route('program-kerja') }}" class="proker-cancel-button">BATAL</a>
            </div>
        </form>
    </div>

    {{-- Tambahkan Font Awesome jika belum ada di layout utama --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('.edit-program-form');
            const loadingSpinner = document.getElementById('loading-spinner');
            const token = localStorage.getItem('token');
            const fileInput = document.getElementById('image');
            const fileNameSpan = document.getElementById('file-name');

            // --- 1. MENGAMBIL ID & MEMUAT DATA AWAL ---
            const getProgramIdFromUrl = () => {
                const urlParts = window.location.pathname.split('/').filter(part => part);
                const programId = urlParts.pop();
                if (programId && !isNaN(programId)) {
                    return programId;
                }
                return null;
            };

            const populateForm = async (programId) => {
                loadingSpinner.style.display = 'block';
                form.style.display = 'none';
                const apiUrl = `https://sirtrw-api.vansite.cloud/api/proker/${programId}`;
                
                try {
                    const response = await axios.get(apiUrl, { headers: { Authorization: `Bearer ${token}` } });
                    const program = response.data.data;

                    document.getElementById('title').value = program.title;
                    document.getElementById('time').value = program.time;
                    document.getElementById('date').value = program.date;
                    document.getElementById('location').value = program.location;
                    document.getElementById('status').value = program.status;
                    document.getElementById('rt_id').value = program.rt_id;
                    document.getElementById('description').value = program.description;

                    const imagePreview = document.getElementById('current-image-preview');
                    const noImageText = document.getElementById('no-image-text');
                    if (program.image) {
                        imagePreview.src = program.image;
                        imagePreview.style.display = 'block';
                        noImageText.style.display = 'none';
                    } else {
                        imagePreview.style.display = 'none';
                        noImageText.style.display = 'block';
                    }

                    loadingSpinner.style.display = 'none';
                    form.style.display = 'block';

                } catch (error) {
                    loadingSpinner.style.display = 'none';
                    console.error('Gagal mengambil data program:', error);
                    const container = document.querySelector('.edit-program-container');
                    container.innerHTML = `<p style='color: red; text-align: center;'>Gagal memuat data. Pastikan ID program benar dan Anda memiliki koneksi internet. (Error: ${error.response ? error.response.status : 'Network Error'})</p>`;
                }
            };

            // --- 2. MENGIRIM PERUBAHAN (SUBMIT FORM) ---
            const handleFormSubmit = async (event) => {
                event.preventDefault();
                if (!confirm('Apakah Anda yakin ingin menyimpan perubahan?')) return;

                const programId = getProgramIdFromUrl();
                const apiUrl = `https://sirtrw-api.vansite.cloud/api/proker/${programId}`;
                const submitButton = form.querySelector('button[type="submit"]');

                const formData = new FormData(form);
                formData.append('_method', 'PUT');

                submitButton.disabled = true;
                submitButton.textContent = 'MENYIMPAN...';

                try {
                    const response = await axios.post(apiUrl, formData, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    });

                    alert('Program kerja berhasil diperbarui!');
                    // =======================================================
                    // PERBAIKAN REDIRECT ADA DI SINI
                    // =======================================================
                    window.location.href = "{{ route('admin-program-kerja') }}";

                } catch (error) {
                    console.error('Gagal memperbarui program:', error.response ? error.response.data : error);
                    const apiErrors = error.response.data.errors;
                    let errorMessages = 'Gagal memperbarui data.\n';
                    if (apiErrors) {
                        for (const key in apiErrors) {
                            errorMessages += `- ${apiErrors[key].join(', ')}\n`;
                        }
                    }
                    alert(errorMessages);
                    submitButton.disabled = false;
                    submitButton.textContent = 'SIMPAN PERUBAHAN';
                }
            };

            // --- 3. EVENT LISTENERS & INISIALISASI ---
            fileInput.addEventListener('change', () => {
                fileNameSpan.textContent = fileInput.files[0] ? fileInput.files[0].name : '';
            });

            form.addEventListener('submit', handleFormSubmit);

            const programId = getProgramIdFromUrl();
            if (programId) {
                populateForm(programId);
            } else {
                const container = document.querySelector('.edit-program-container');
                container.innerHTML = "<p style='color: red; text-align: center;'>ID Program Kerja tidak valid atau tidak ditemukan di URL.</p>";
            }

            // Sidebar Toggle
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');
            if (menuIcon && sidebar) {
                menuIcon.addEventListener('click', (e) => { e.stopPropagation(); sidebar.classList.toggle('active'); });
                document.addEventListener('click', (e) => { if (!sidebar.contains(e.target) && !menuIcon.contains(e.target)) { sidebar.classList.remove('active'); }});
            }
        });
    </script>
@endsection