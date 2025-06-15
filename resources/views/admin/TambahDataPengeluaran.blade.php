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
// JavaScript dari halaman Tambah Pemasukan bisa disalin ke sini,
// dengan satu perubahan kecil.
document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('addExpenseForm');
    const submitButton = document.getElementById('submitButton');
    // ... (semua deklarasi variabel lain sama persis) ...
    const responseMessage = document.getElementById('responseMessage');
    const token = localStorage.getItem('token');
    const nominalInput = document.getElementById('value');
    const imageInput = document.getElementById('image');
    const fileNameSpan = document.getElementById('fileName');

    // ... (semua event listener sama persis) ...
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
        responseMessage.innerHTML = '';

        const formData = new FormData();
        const rawValue = nominalInput.value.replace(/[^0-9]/g, '');

        formData.append('value', rawValue);
        formData.append('date', document.getElementById('date').value);
        formData.append('notes', document.getElementById('notes').value);

        // === PERBEDAAN UTAMA ADA DI SINI ===
        formData.append('variance', 'outflow'); // Diubah menjadi 'outflow'
        // ====================================

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
                    // Arahkan ke laporan pengeluaran
                    window.location.href = '{{ route("laporan-pengeluaran") }}';
                }, 2000);

            } else { throw new Error(response.data.message); }

        } catch (error) {
            const errorMessage = error.response?.data?.message || error.message;
            responseMessage.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = 'SIMPAN';
        }
    });
    // 3. UX HELPERS (Format Rupiah & Tampilkan Nama File)

    // Format input nominal menjadi Rupiah saat diketik
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

    // Tampilkan nama file saat dipilih
    imageInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileNameSpan.textContent = this.files[0].name;
        } else {
            fileNameSpan.textContent = '';
        }
    });

    // Sidebar Toggle (Tetap ada)
    const menuIcon = document.querySelector('.toggle-sidebar-icon');
    const sidebar = document.querySelector('.admin-sidebar');
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