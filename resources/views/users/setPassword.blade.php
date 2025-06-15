@extends('layouts.landingNavbar')

@section('content')
<title>SIMAS - Reset Password</title>
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<div class="login-container d-flex justify-content-center align-items-center">
    <div class="login-card p-5 shadow-lg text-white">
        <h3 class="text-center mb-4 fw-bold">Atur Password Baru</h3>
        
        {{-- Form action ini akan diabaikan dan ditangani oleh JavaScript di bawah --}}
        <form id="resetPasswordForm">
            <div class="mb-4 position-relative">
                <label for="newPassword" class="form-label fw-semibold">Password Baru</label>
                <input type="password" class="form-control rounded-pill px-3" name="password" id="newPassword"
                       placeholder="Minimal 6 karakter" required>
                <span class="position-absolute password-toggle" style="right: 15px; top: 40px; cursor: pointer;">
                    <i class="fas fa-eye" id="toggleNewPassword"></i>
                </span>
            </div>

            <div class="mb-4 position-relative">
                <label for="confirmPassword" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                <input type="password" class="form-control rounded-pill px-3" name="password_confirmation"
                       id="confirmPassword" placeholder="Ketik ulang password baru" required>
                <span class="position-absolute password-toggle" style="right: 15px; top: 40px; cursor: pointer;">
                    <i class="fas fa-eye" id="toggleConfirmPassword"></i>
                </span>
            </div>

            <div id="responseMessage" class="mt-2"></div>

            <div class="text-center">
                <button type="submit" class="submit-btn btn-warning" id="submitButton" style="max-width: 200px;">Reset Password</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('resetPasswordForm');
    const submitButton = document.getElementById('submitButton');
    const responseMessage = document.getElementById('responseMessage');

    // Ambil token dan email dari query parameter URL
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    const email = urlParams.get('email');

    // Validasi: pastikan token dan email ada di URL
    if (!token || !email) {
        form.style.display = 'none';
        responseMessage.innerHTML = `<div class="alert alert-danger p-2">Link reset tidak valid atau telah kedaluwarsa. Silakan <a href="{{ url('/forgot-password') }}">ulangi proses</a>.</div>`;
        return;
    }
    
    form.addEventListener('submit', async (event) => {
        event.preventDefault(); // <-- Mencegah form dikirim ke action backend

        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        responseMessage.innerHTML = '';

        const password = document.getElementById('newPassword').value;
        const password_confirmation = document.getElementById('confirmPassword').value;

        if (password.length < 6) {
            responseMessage.innerHTML = '<div class="alert alert-danger p-2">Password minimal harus 8 karakter.</div>';
            submitButton.disabled = false;
            submitButton.innerHTML = 'Reset Password';
            return;
        }

        if (password !== password_confirmation) {
            responseMessage.innerHTML = '<div class="alert alert-danger p-2">Konfirmasi password tidak cocok.</div>';
            submitButton.disabled = false;
            submitButton.innerHTML = 'Reset Password';
            return;
        }

        // Endpoint final API untuk update password
        const apiUrl = 'https://sirtrw-api.vansite.cloud/api/change-password';

        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    email: email,
                    token: token,
                    password: password,
                    password_confirmation: password_confirmation
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                responseMessage.innerHTML = '<div class="alert alert-success p-2">Password berhasil direset! Anda akan diarahkan ke halaman login...</div>';
                setTimeout(() => {
                    window.location.href = '{{ route('masuk') }}'; // Arahkan ke route login Anda
                }, 3000);
            } else {
                if (result.errors) {
                    const errorMessages = Object.values(result.errors).flat().join('<br>');
                    throw new Error(errorMessages);
                }
                throw new Error(result.message || 'Gagal mereset password.');
            }
        } catch (error) {
            responseMessage.innerHTML = `<div class="alert alert-danger p-2">${error.message}</div>`;
            submitButton.disabled = false;
            submitButton.innerHTML = 'Reset Password';
        }
    });

    // --- Script untuk toggle ikon mata (tidak berubah) ---
    const toggleNewPassword = document.querySelector('#toggleNewPassword');
    const newPassword = document.querySelector('#newPassword');
    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirmPassword = document.querySelector('#confirmPassword');

    if(toggleNewPassword && newPassword){toggleNewPassword.addEventListener('click',function(){const type=newPassword.getAttribute('type')==='password'?'text':'password';newPassword.setAttribute('type',type);this.classList.toggle('fa-eye-slash')})}
    if(toggleConfirmPassword && confirmPassword){toggleConfirmPassword.addEventListener('click',function(){const type=confirmPassword.getAttribute('type')==='password'?'text':'password';confirmPassword.setAttribute('type',type);this.classList.toggle('fa-eye-slash')})}
});
</script>
@endsection