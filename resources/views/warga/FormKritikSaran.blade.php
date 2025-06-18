@extends('layouts.dashboardNavbar')

<title>SIMAS - Program Kerja</title>
<link rel="stylesheet" href="{{ asset('css/kritik-saran.css') }}">

@section('content')
    <?php
    $page = 'kritik-saran'; // or 'program-kerja', 'pembayaran', etc.
        ?>

    <div class="login-container d-flex justify-content-center align-items-center" style="height: 700px">

        <div class="kritik-card p-5 shadow-lg text-white" style="height: 660px">
            <h2 class="text-center mb-3 fw-bold" style="color: white">KRITIK DAN SARAN</h2>

            <form id="kritikSaranForm">
                @csrf
                <div class="mb-4 position-relative">
                    <label for="floatingContent" class="form-label fw-semibold">Kritik / Saran</label>
                    <textarea class="form-control px-3" name="kritik" id="floatingKritik"
                        style="height: 200px; border: 1px solid white;" required></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="submit-btn btn-warning">Submit</button>
                </div>
            </form>
            <div id="formMessage" class="mt-3 text-center"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // =================================================================
            // 1. SETUP & DEKLARASI ELEMEN
            // =================================================================
            const kritikSaranForm = document.getElementById('kritikSaranForm');
            const kritikTextarea = document.getElementById('floatingKritik');
            const submitButton = document.querySelector('.submit-btn');
            const formMessage = document.getElementById('formMessage');

            const token = localStorage.getItem('token');

            // Cek otentikasi
            if (!token) {
                formMessage.style.color = 'red';
                formMessage.textContent = 'Sesi Anda telah berakhir. Harap login kembali untuk mengirim kritik.';
                submitButton.disabled = true;
                return;
            }

            // Buat instance axios dengan otentikasi
            const axiosInstance = axios.create({
                baseURL: 'https://sirtrw-api.vansite.cloud/api',
                headers: { 'Authorization': `Bearer ${token}` }
            });


            // =================================================================
            // 2. EVENT LISTENER UNTUK SUBMIT FORM
            // =================================================================
            kritikSaranForm.addEventListener('submit', async (e) => {
                // Mencegah form melakukan submit standar
                e.preventDefault();

                // 1. Dapatkan teks dari textarea
                const kritikText = kritikTextarea.value.trim();

                // Validasi simpel: jangan kirim jika kosong
                if (kritikText === '') {
                    formMessage.style.color = 'orange';
                    formMessage.textContent = 'Harap isi kolom kritik atau saran terlebih dahulu.';
                    return;
                }

                // Beri feedback visual ke pengguna
                submitButton.disabled = true;
                submitButton.textContent = 'Mengirim...';
                formMessage.textContent = ''; // Kosongkan pesan sebelumnya

                try {
                    // 2. Ambil rt_id pengguna dari API /me
                    const meResponse = await axiosInstance.get('/me');
                    const userRtId = meResponse.data.data.warga.rt_id;

                    if (!userRtId) {
                        // Handle jika karena suatu alasan rt_id tidak ditemukan
                        throw new Error('ID RT pengguna tidak ditemukan.');
                    }

                    // 3. Siapkan payload yang benar sesuai spesifikasi API
                    const payload = {
                        rt_id: userRtId,
                        text: kritikText
                    };

                    // 4. Kirim kritik ke API /kritik
                    const kritikResponse = await axiosInstance.post('/kritik', payload);

                    if (kritikResponse.data.success) {
                        // Jika berhasil
                        formMessage.style.color = 'lightgreen';
                        formMessage.textContent = 'Terima kasih! Kritik dan saran Anda berhasil dikirim.';
                        kritikSaranForm.reset(); // Kosongkan form
                    } else {
                        // Jika API mengembalikan success: false
                        throw new Error(kritikResponse.data.message || 'Gagal mengirim data.');
                    }

                } catch (error) {
                    // Jika terjadi error (koneksi, server error, dll)
                    console.error('Error submitting criticism:', error);
                    formMessage.style.color = 'red';
                    formMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi nanti.';
                } finally {
                    // Apapun hasilnya, aktifkan kembali tombol submit
                    submitButton.disabled = false;
                    submitButton.textContent = 'Submit';
                }
            });
        });
    </script>
@endsection