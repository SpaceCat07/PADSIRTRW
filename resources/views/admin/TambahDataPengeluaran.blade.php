@extends('layouts.adminSidebar')

<title>SIMAS - Tambah Data Pengeluaran</title>
<link rel="stylesheet" href="{{ asset('css/tambah-mutasi.css') }}">

@section('content')
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
            <h1>Tambah Data Pengeluaran</h1>
        </header>
    </div>

    {{-- Gunakan wrapper ini untuk styling --}}
    <div class="form-wrapper">
        <form id="addExpenseForm" class="keuangan-form">
            
            {{-- Baris 1: Nominal --}}
            <div class="form-row">
                <label for="value">Nominal :</label>
                <input type="text" id="value" name="value" placeholder="Masukkan Nominal" required>
            </div>

            {{-- Baris 2: Tanggal --}}
            <div class="form-row">
                <label for="date">Tanggal :</label>
                <input type="date" id="date" name="date" required>
            </div>

            {{-- Baris 3: Deskripsi --}}
            <div class="form-row">
                <label for="notes">Deskripsi :</label>
                <textarea id="notes" name="notes" placeholder="Masukkan Deskripsi Pengeluaran" required rows="3"></textarea>
            </div>

            {{-- Baris 4: Bukti --}}
            <div class="form-row">
                <label for="image">Bukti :</label>
                <div class="file-upload-container">
                    <input type="file" id="image" name="image" accept="image/jpeg, image/png" style="display: none;">
                    <label for="image" class="custom-file-upload">+ Upload file jpg/png</label>
                    <span id="fileName" class="file-name-display"></span>
                </div>
            </div>

            {{-- Tempat untuk pesan error/sukses --}}
            <div id="responseMessage" style="grid-column: 2;"></div>

            {{-- Baris 5: Tombol Simpan --}}
            <div class="submit-button-container">
                <button type="submit" class="simpan-button" id="submitButton">SIMPAN</button>
            </div>

        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- 1. SETUP AWAL & DEFINISI SEMUA ELEMEN ---
    const form = document.getElementById('addExpenseForm'); // Ganti ke 'addIncomeForm' jika di halaman pemasukan
    const submitButton = document.getElementById('submitButton');
    const responseMessage = document.getElementById('responseMessage');
    const token = localStorage.getItem('token');
    const nominalInput = document.getElementById('value');
    const imageInput = document.getElementById('image');
    const fileNameSpan = document.getElementById('fileName');
    const menuIcon = document.querySelector('.toggle-sidebar-icon');
    const sidebar = document.querySelector('.admin-sidebar');

    // Variabel untuk menyimpan ID admin
    let adminRtId = null;
    let adminRwId = null;

    // --- 2. VALIDASI TOKEN AWAL ---
    if (!token) {
        responseMessage.innerHTML = `<div class="alert alert-danger">Sesi berakhir. Silakan login kembali.</div>`;
        if (submitButton) submitButton.disabled = true;
        return;
    }

    // --- 3. FUNGSI UNTUK MENGAMBIL DATA ADMIN ---
    async function setupAdminInfo() {
        try {
            const response = await axios.get('https://sirtrw-api.vansite.cloud/api/me', {
                headers: { Authorization: `Bearer ${token}` }
            });
            const user = response.data.data;
            adminRtId = user.warga?.rt_id;
            adminRwId = user.warga?.rw_id;
            console.log(`Admin Info Loaded: RT ID=${adminRtId}, RW ID=${adminRwId}`);
        } catch (error) {
            console.error('Gagal mengambil data admin:', error);
            alert('Gagal memuat informasi admin. Form tidak dapat digunakan.');
            if (submitButton) submitButton.disabled = true;
        }
    }

    // --- 4. EVENT LISTENER UTAMA ---

    // A. Event Listener untuk Submit Form
    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
            responseMessage.innerHTML = '';

            const userRole = localStorage.getItem('userRole')?.toLowerCase();

            // Validasi: Pastikan ID admin sudah ada sebelum submit
            if ((userRole.includes('rt') && !adminRtId) || (userRole.includes('rw') && !adminRwId)) {
                alert('Error: Data admin tidak lengkap. Silakan muat ulang halaman.');
                submitButton.disabled = false;
                submitButton.innerHTML = 'SIMPAN';
                return;
            }

            const formData = new FormData();
            const rawValue = nominalInput.value.replace(/[^0-9]/g, '');

            formData.append('value', rawValue);
            formData.append('date', document.getElementById('date').value);
            formData.append('notes', document.getElementById('notes').value);
            
            // Sesuaikan 'variance' berdasarkan halaman (ini untuk pengeluaran)
            formData.append('variance', 'outflow');

            if (userRole.includes('rt')) {
                formData.append('rt_id', adminRtId);
            } else if (userRole.includes('rw')) {
                formData.append('rw_id', adminRwId);
            }

            if (imageInput.files.length > 0) {
                formData.append('image', imageInput.files[0]);
            }

            try {
                const response = await axios.post('https://sirtrw-api.vansite.cloud/api/mutasi', formData, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (response.data.success) {
                    responseMessage.innerHTML = `<div class="alert alert-success">Data pengeluaran berhasil disimpan!</div>`;
                    form.reset();
                    fileNameSpan.textContent = '';
                    setTimeout(() => {
                        window.location.href = '{{ route("laporan-pengeluaran") }}';
                    }, 1500);
                } else {
                    throw new Error(response.data.message || 'Terjadi kesalahan');
                }

            } catch (error) {
                const errorMessage = error.response?.data?.message || error.message;
                responseMessage.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = 'SIMPAN';
            }
        });
    }

    // B. Event Listener untuk UX Helper (Format Rupiah)
    if (nominalInput) {
        nominalInput.addEventListener('keyup', function(e) {
            let cursorPosition = this.selectionStart;
            let value = parseInt(this.value.replace(/[^0-9]/g, ''), 10);
            let originalLength = this.value.length;
            if (isNaN(value)) {
                this.value = "";
                return;
            }
            this.value = "Rp " + value.toLocaleString('id-ID');
            let newLength = this.value.length;
            cursorPosition = newLength - (originalLength - cursorPosition);
            this.setSelectionRange(cursorPosition, cursorPosition);
        });
    }

    // C. Event Listener untuk UX Helper (Nama File)
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            fileNameSpan.textContent = this.files.length > 0 ? this.files[0].name : '';
        });
    }

    // D. Event Listener untuk Sidebar Toggle
    if (menuIcon && sidebar) {
        menuIcon.addEventListener('click', (event) => {
            event.stopPropagation();
            sidebar.classList.toggle('active');
        });

        document.addEventListener('click', (event) => {
            // Logika untuk menutup sidebar jika diklik di luar area
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