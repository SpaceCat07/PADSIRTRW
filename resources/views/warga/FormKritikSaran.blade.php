@extends('layouts.dashboardNavbar')

<title>SIMAS - Kirim Kritik & Saran</title>
<link rel="stylesheet" href="{{ asset('css/kritik-saran.css') }}">

@section('content')
    <?php
    $page = 'kritik-saran';
    ?>
    
    <div class="login-container d-flex justify-content-center align-items-center" style="min-height: 700px">
        
        <div class="kritik-card p-5 shadow-lg text-white d-flex flex-column" style="min-height: 400px; width: 100%; max-width: 700px;">
            <h2 class="text-center mb-3 fw-bold" style="color: white">KRITIK DAN SARAN</h2>

            <form id="kritikSaranForm" class="d-flex flex-column flex-grow-1">
                @csrf
                <div class="mb-4 position-relative d-flex flex-column flex-grow-1">
                    <label for="floatingKritik" class="form-label fw-semibold">Kritik / Saran</label>
                    <textarea class="form-control px-3 flex-grow-1" name="kritik" id="floatingKritik"
                        style="border: 1px solid white;" required></textarea>
                </div>

                <div class="text-center mt-auto"> <button type="submit" class="submit-btn btn-warning">Submit</button>
                </div>
            </form>
            
            <div id="formMessage" class="alert mt-4 text-center d-none" role="alert">
                {{-- Pesan akan muncul di sini --}}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Tidak ada perubahan pada JavaScript, script Anda sudah benar
        document.addEventListener('DOMContentLoaded', () => {
            const kritikSaranForm = document.getElementById('kritikSaranForm');
            const kritikTextarea = document.getElementById('floatingKritik');
            const submitButton = document.querySelector('.submit-btn');
            const formMessage = document.getElementById('formMessage');
            const token = localStorage.getItem('token');

            if (!token) {
                formMessage.classList.remove('d-none', 'alert-success', 'alert-warning');
                formMessage.classList.add('alert-danger');
                formMessage.textContent = 'Sesi Anda telah berakhir. Harap login kembali.';
                submitButton.disabled = true;
                return;
            }

            const axiosInstance = axios.create({
                baseURL: 'https://sirtrw-api.vansite.cloud/api',
                headers: { 'Authorization': `Bearer ${token}` }
            });

            kritikSaranForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const kritikText = kritikTextarea.value.trim();
                formMessage.classList.add('d-none'); 

                if (kritikText === '') {
                    formMessage.classList.remove('d-none', 'alert-success', 'alert-danger');
                    formMessage.classList.add('alert-warning');
                    formMessage.textContent = 'Harap isi kolom kritik atau saran terlebih dahulu.';
                    return;
                }

                submitButton.disabled = true;
                submitButton.textContent = 'Mengirim...';

                try {
                    const meResponse = await axiosInstance.get('/me');
                    const userRtId = meResponse.data.data.warga.rt_id;

                    if (!userRtId) throw new Error('ID RT pengguna tidak ditemukan.');

                    const payload = { rt_id: userRtId, text: kritikText };
                    const kritikResponse = await axiosInstance.post('/kritik', payload);

                    if (kritikResponse.data.success) {
                        formMessage.classList.remove('d-none', 'alert-danger', 'alert-warning');
                        formMessage.classList.add('alert-success');
                        formMessage.textContent = 'Terima kasih! Kritik berhasil dikirim. Anda akan diarahkan...';
                        
                        setTimeout(() => {
                            window.location.href = "{{ route('riwayat-kritik-saran') }}";
                        }, 2000);

                    } else {
                        throw new Error(kritikResponse.data.message || 'Gagal mengirim data.');
                    }

                } catch (error) {
                    console.error('Error submitting criticism:', error);
                    formMessage.classList.remove('d-none', 'alert-success', 'alert-warning');
                    formMessage.classList.add('alert-danger');
                    formMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi nanti.';
                    
                    submitButton.disabled = false;
                    submitButton.textContent = 'Submit';
                }
            });
        });
    </script>
@endsection