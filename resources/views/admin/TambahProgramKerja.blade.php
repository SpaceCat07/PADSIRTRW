@extends('layouts.adminSidebar')

<title>SIMAS - Tambah Program Kerja</title>
<link rel="stylesheet" href="{{ asset('css/tambah-proker.css') }}">

@section('content')
    {{-- Header --}}
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
            <h1>Tambah Program Kerja</h1>
        </header>
    </div>

    {{-- Container Form --}}
    <div class="edit-program-container">
        {{-- Form Tambah Program Kerja --}}
        <form id="add-program-form" class="edit-program-form" enctype="multipart/form-data">
            {{--
                Catatan: @csrf dan @method('POST') dihapus karena tidak diperlukan untuk
                API call stateless yang menggunakan Bearer Token. Method ('POST') akan
                didefinisikan di dalam JavaScript (fetch).
            --}}

            {{-- Menampilkan pesan error dari API --}}
            <div id="error-messages" style="color: red; margin-bottom: 15px;"></div>

            <div class="form-group">
                <label for="title">Judul Program</label>
                <input type="text" id="title" name="title" placeholder="Masukkan Judul Program" required>
            </div>

            {{-- Input untuk RT atau RW berdasarkan role --}}
            <div class="form-group">
                <label for="rt_rw_id" id="rt_rw_label">Nomor RT/RW</label>
                <input type="number" id="rt_rw_id" name="rt_rw_id" placeholder="Masukkan Nomor" required>
            </div>

            <div class="form-group">
                <label for="date">Tanggal Pelaksanaan</label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="form-group">
                <label for="time">Waktu Pelaksanaan</label>
                {{-- Menggunakan type="time" untuk konsistensi data --}}
                <input type="time" id="time" name="time" required>
            </div>

            <div class="form-group">
                <label for="location">Lokasi</label>
                <input type="text" id="location" name="location" placeholder="Masukkan Lokasi Pelaksanaan" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Program</label>
                <textarea id="description" name="description" rows="5" placeholder="Masukkan Deskripsi Program" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Gambar</label>
                <input type="file" id="image" name="image" accept="image/jpeg, image/png" style="display: none;">
                <label for="image" class="custom-file-upload" id="file-label">
                    + Upload JPG/PNG
                </label>
                <span id="file-name" style="margin-left: 10px;"></span>
            </div>

            <div class="submit-button-group">
                <button type="submit" class="proker-submit-button">SIMPAN</button>
            </div>
        </form>
    </div>

    {{--
        ================================================================
        SCRIPT UNTUK LOGIKA FORM
        ================================================================
    --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Menggabungkan semua script dalam satu event listener ---
            const form = document.getElementById('add-program-form');
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');
            const fileInput = document.getElementById('image');
            const fileLabel = document.getElementById('file-label');
            const fileNameSpan = document.getElementById('file-name');
            const errorMessagesDiv = document.getElementById('error-messages');

            // --- Mendapatkan data dari localStorage ---
            const token = localStorage.getItem('token'); // Pastikan nama key sesuai
            const userRole = localStorage.getItem('userRole');   // Pastikan nama key sesuai

            // --- Logika Kondisional untuk Label RT/RW ---
            const rtRwLabel = document.getElementById('rt_rw_label');
            if (userRole === 'Admin_RT') {
                rtRwLabel.textContent = 'Nomor RT';
            } else if (userRole === 'Admin_RW') {
                rtRwLabel.textContent = 'Nomor RW';
            }

            // --- UX: Menampilkan nama file yang dipilih ---
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    fileNameSpan.textContent = fileInput.files[0].name;
                } else {
                    fileNameSpan.textContent = '';
                }
            });

            // --- Logika Pengiriman Form ke API ---
            form.addEventListener('submit', async (event) => {
                event.preventDefault(); // Mencegah form submit default
                errorMessagesDiv.textContent = ''; // Bersihkan pesan error lama

                if (!confirm('Apakah Anda yakin ingin menyimpan program kerja ini?')) {
                    return;
                }

                // Menggunakan FormData untuk mengirim file dan data teks
                const formData = new FormData();
                formData.append('title', document.getElementById('title').value);
                formData.append('description', document.getElementById('description').value);
                formData.append('date', document.getElementById('date').value);
                formData.append('time', document.getElementById('time').value);
                formData.append('location', document.getElementById('location').value);
                
                // Tambahkan rt_id atau rw_id berdasarkan role
                const rtRwId = document.getElementById('rt_rw_id').value;
                if (userRole === 'Admin_RT') {
                    formData.append('rt_id', rtRwId);
                } else if (userRole === 'Admin_RW') {
                    formData.append('rw_id', rtRwId);
                }

                // Tambahkan file jika ada yang dipilih
                if (fileInput.files.length > 0) {
                    formData.append('image', fileInput.files[0]);
                } else {
                    // Berdasarkan contoh Postman Anda, 'image' bisa null
                    formData.append('image', null); 
                }

                try {
                    const response = await fetch('https://sirtrw-api.vansite.cloud/api/proker', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                        },
                        body: formData, // FormData sudah menyertakan header Content-Type yang benar
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert('Program kerja berhasil ditambahkan!');
                        // Arahkan ke halaman daftar program kerja atau halaman lain yang sesuai
                        window.location.href = '/program-kerja/admin';
                    } else {
                        // Menampilkan pesan error dari API
                        let errorText = `Error: ${result.message}`;
                        if (result.errors) {
                            errorText += '\n' + Object.values(result.errors).join('\n');
                        }
                        errorMessagesDiv.textContent = errorText;
                        alert(errorText); // Tampilkan juga sebagai alert
                    }
                } catch (error) {
                    console.error('Terjadi kesalahan:', error);
                    errorMessagesDiv.textContent = 'Tidak dapat terhubung ke server. Silakan coba lagi nanti.';
                    alert('Terjadi kesalahan koneksi. Periksa konsol untuk detail.');
                }
            });

            // --- Script untuk Toggle Sidebar ---
            if (menuIcon && sidebar) {
                menuIcon.addEventListener('click', (event) => {
                    event.stopPropagation();
                    sidebar.classList.toggle('active');
                });

                document.addEventListener('click', (event) => {
                    if (!sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                        sidebar.classList.remove('active');
                    }
                });
            }
        });
    </script>
@endsection