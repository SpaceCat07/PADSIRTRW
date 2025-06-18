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

            {{-- Menampilkan pesan error dari API --}}
            {{-- style dihapus, diatur oleh #error-messages di CSS --}}
            <div id="error-messages"></div>

            <div class="form-group">
                <label for="title">Judul Program</label>
                <input type="text" id="title" name="title" placeholder="Masukkan Judul Program" required>
            </div>

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
                <input type="time" id="time" name="time" required>
            </div>

            <div class="form-group">
                <label for="location">Lokasi</label>
                <input type="text" id="location" name="location" placeholder="Masukkan Lokasi Pelaksanaan" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Program</label>
                <textarea id="description" name="description" rows="5" placeholder="Masukkan Deskripsi Program"
                    required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Gambar</label>
                {{-- style dihapus, diatur oleh #image di CSS --}}
                <input type="file" id="image" name="image" accept="image/jpeg, image/png">
                <label for="image" class="custom-file-upload" id="file-label">
                    + Upload JPG/PNG
                </label>
                {{-- style dihapus, diatur oleh #file-name di CSS --}}
                <span id="file-name"></span>
            </div>

            <div class="submit-button-group">
                <button type="submit" class="proker-submit-button">SIMPAN</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- 1. SETUP AWAL & DEFINISI ELEMEN ---
            const form = document.getElementById('add-program-form');
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');
            const fileInput = document.getElementById('image');
            const fileNameSpan = document.getElementById('file-name');
            const errorMessagesDiv = document.getElementById('error-messages');
            const rtRwLabel = document.getElementById('rt_rw_label');
            const rtRwInput = document.getElementById('rt_rw_id'); // Kita ambil inputnya

            const token = localStorage.getItem('token');
            const userRole = localStorage.getItem('userRole')?.toLowerCase() || 'admin_rt'; // lebih baik pakai lowercase

            // Variabel untuk menyimpan ID admin yang didapat dari API
            let adminRtId = null;
            let adminRwId = null;


            // --- 2. FUNGSI UNTUK MENGAMBIL DATA ADMIN & MENYIAPKAN FORM ---
            async function setupAdminInfo() {
                if (!token) {
                    alert('Token tidak ditemukan. Silakan login kembali.');
                    return;
                }

                try {
                    // Ambil data profil admin dari API /me
                    const response = await axios.get('https://sirtrw-api.vansite.cloud/api/me', {
                        headers: { Authorization: `Bearer ${token}` }
                    });

                    const user = response.data.data;
                    adminRtId = user.warga?.rt_id;
                    adminRwId = user.warga?.rw_id;

                    // Logika untuk mengisi dan mengunci form berdasarkan role
                    if (userRole.includes('rt')) {
                        rtRwLabel.textContent = 'Nomor RT';
                        rtRwInput.value = adminRtId; // Isi otomatis
                        rtRwInput.readOnly = true; // Kunci inputnya
                    } else if (userRole.includes('rw')) {
                        rtRwLabel.textContent = 'Nomor RW';
                        rtRwInput.value = adminRwId; // Isi otomatis
                        rtRwInput.readOnly = true; // Kunci inputnya
                    }

                } catch (error) {
                    console.error('Gagal mengambil data admin:', error);
                    alert('Gagal memuat informasi admin. Pastikan Anda login dengan benar.');
                    // Matikan form jika data admin gagal diambil
                    form.querySelector('button[type="submit"]').disabled = true;
                }
            }


            // --- 3. EVENT LISTENER UNTUK SUBMIT FORM ---
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                errorMessagesDiv.textContent = '';

                if (!confirm('Apakah Anda yakin ingin menyimpan program kerja ini?')) {
                    return;
                }

                // Validasi: Pastikan ID admin sudah terisi
                if ((userRole.includes('rt') && !adminRtId) || (userRole.includes('rw') && !adminRwId)) {
                    alert('Gagal menyimpan. Data ID admin tidak ditemukan. Silakan muat ulang halaman.');
                    return;
                }

                const formData = new FormData();
                formData.append('title', document.getElementById('title').value);
                formData.append('description', document.getElementById('description').value);
                formData.append('date', document.getElementById('date').value);
                formData.append('time', document.getElementById('time').value);
                formData.append('location', document.getElementById('location').value);

                // Tambahkan rt_id atau rw_id dari variabel yang sudah kita simpan, BUKAN dari input manual
                if (userRole.includes('rt')) {
                    formData.append('rt_id', adminRtId);
                } else if (userRole.includes('rw')) {
                    formData.append('rw_id', adminRwId);
                }

                if (fileInput.files.length > 0) {
                    formData.append('image', fileInput.files[0]);
                }

                try {
                    // Menggunakan Axios agar konsisten dengan halaman lain
                    const response = await axios.post('https://sirtrw-api.vansite.cloud/api/proker', formData, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'multipart/form-data', // Axios otomatis mengatur ini untuk FormData
                        }
                    });

                    alert('Program kerja berhasil ditambahkan!');
                    window.location.href = '/admin/program-kerja'; // Arahkan ke halaman daftar proker admin

                } catch (error) {
                    console.error('Terjadi kesalahan:', error);
                    let errorText = 'Terjadi kesalahan pada server.';
                    if (error.response) {
                        // Menampilkan pesan error validasi dari API Laravel
                        if (error.response.status === 422 && error.response.data.errors) {
                            const errors = error.response.data.errors;
                            errorText = Object.values(errors).map(e => e[0]).join('\n');
                        } else {
                            errorText = error.response.data.message || 'Error tidak diketahui.';
                        }
                    }
                    errorMessagesDiv.innerText = errorText; // Gunakan innerText agar newline tampil
                    alert(`Gagal menambahkan program:\n${errorText}`);
                }
            });


            // --- 4. EVENT LISTENER LAINNYA (UX & SIDEBAR) ---
            fileInput.addEventListener('change', () => {
                fileNameSpan.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : '';
            });

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


            // --- 5. PANGGIL FUNGSI INISIASI SAAT HALAMAN DIMUAT ---
            setupAdminInfo();
        });
    </script>
@endsection