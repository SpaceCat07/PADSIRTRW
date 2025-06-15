@extends('layouts.adminSidebar')

<title>SIMAS - Tambah Data Pemasukan</title>
<link rel="stylesheet" href="{{ asset('css/tambah-mutasi.css') }}"> 

@section('content')
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
            <h1>Tambah Data Pemasukan</h1>
        </header>
    </div>

    <div class="form-wrapper">
        {{-- ID form diubah menjadi 'addIncomeForm' --}}
        <form id="addIncomeForm" class="keuangan-form">
            
            <div class="form-row">
                <label for="value">Nominal :</label>
                <input type="text" id="value" name="value" placeholder="Masukkan Nominal" required>
            </div>

            <div class="form-row">
                <label for="date">Tanggal :</label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="form-row">
                <label for="notes">Deskripsi :</label>
                {{-- Placeholder diubah --}}
                <textarea id="notes" name="notes" placeholder="Masukkan Deskripsi Pemasukan" required rows="3"></textarea>
            </div>

            <div class="form-row">
                <label for="image">Bukti :</label>
                <div class="file-upload-container">
                    <input type="file" id="image" name="image" accept="image/jpeg, image/png" style="display: none;">
                    <label for="image" class="custom-file-upload">+ Upload file jpg/png</label>
                    <span id="fileName" class="file-name-display"></span>
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

    const form = document.getElementById('addIncomeForm'); // ID form disesuaikan
    const submitButton = document.getElementById('submitButton');
    const responseMessage = document.getElementById('responseMessage');
    const token = localStorage.getItem('token');
    const nominalInput = document.getElementById('value');
    const imageInput = document.getElementById('image');
    const fileNameSpan = document.getElementById('fileName');

    if (!token) {
        responseMessage.innerHTML = `<div class="alert alert-danger">Sesi berakhir. Silakan login kembali.</div>`;
        if(submitButton) submitButton.disabled = true;
        return;
    }

    if(form) {
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
            formData.append('variance', 'inflow'); // Diubah menjadi 'inflow' untuk pemasukan
            // ====================================

            if (imageInput.files.length > 0) {
                formData.append('image', imageInput.files[0]);
            }
            
            try {
                const response = await axios.post('https://sirtrw-api.vansite.cloud/api/mutasi', formData, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (response.data.success) {
                    responseMessage.innerHTML = `<div class="alert alert-success">Data pemasukan berhasil disimpan!</div>`;
                    form.reset();
                    fileNameSpan.textContent = '';
                    
                    setTimeout(() => {
                        // Arahkan ke laporan pemasukan
                        window.location.href = '{{ route("laporan-pemasukan") }}';
                    }, 2000);

                } else { throw new Error(response.data.message || 'Terjadi kesalahan'); }

            } catch (error) {
                const errorMessage = error.response?.data?.message || error.message;
                responseMessage.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = 'SIMPAN';
            }
        });
    }

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