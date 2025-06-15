@extends('layouts.landingNavbar')

@section('content')
<title>SIMAS - Lupa Password</title>
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<div class="login-container d-flex justify-content-center align-items-center">
    <div class="login-card p-5 shadow-lg text-white">
        <h3 class="text-center mb-4 fw-bold">Lupa Password</h3>
        <p class="text-center text-white-50 mb-4">Masukkan email Anda yang terdaftar untuk proses verifikasi.</p>

        <form id="forgotPasswordForm">
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control rounded-pill px-3" name="email" id="email"
                       placeholder="Contoh: user@gmail.com" required>
            </div>

            <div id="responseMessage" class="mt-2"></div>

            <div class="text-center">
                <button type="submit" class="submit-btn btn-warning" id="submitButton">Kirim Verifikasi</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('forgotPasswordForm');
    const submitButton = document.getElementById('submitButton');
    const responseMessage = document.getElementById('responseMessage');

    form.addEventListener('submit', async (event) => {
        event.preventDefault(); 

        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        responseMessage.innerHTML = '';

        const email = document.getElementById('email').value;
        const apiUrl = 'https://sirtrw-api.vansite.cloud/api/reset-password';

        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ email: email })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // ======================================================================
                // === LOGIKA HYBRID: Mengambil data untuk membangun URL ===
                // ======================================================================
                const token = result.data.token;
                const userId = result.data.user.id;
                const userEmail = result.data.user.email;
                
                responseMessage.innerHTML = '<div class="alert alert-success p-2">Verifikasi berhasil! Anda akan diarahkan...</div>';
                
                // Bangun URL yang sesuai dengan format route di web.php: /reset-password/{id}
                // Sambil membawa token dan email sebagai query parameter untuk halaman selanjutnya
                const newUrl = `{{ url('/reset-password') }}/${userId}?token=${token}&email=${encodeURIComponent(userEmail)}`;
                
                // Arahkan ke URL yang baru
                window.location.href = newUrl;
                // ======================================================================

            } else {
                throw new Error(result.message || 'Gagal mengirim permintaan reset.');
            }

        } catch (error) {
            responseMessage.innerHTML = `<div class="alert alert-danger p-2">${error.message}</div>`;
            submitButton.disabled = false;
            submitButton.innerHTML = 'Kirim Verifikasi';
        }
    });
});
</script>
@endsection