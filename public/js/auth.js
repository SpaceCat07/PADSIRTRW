// public/js/auth.js

// =================================================================
// BAGIAN LOGOUT (TETAP SAMA SEPERTI SEBELUMNYA)
// =================================================================
async function handleLogout(event) {
    event.preventDefault();
    console.log('Mencoba untuk logout...');
    const token = localStorage.getItem('token');
    if (!token) {
        localStorage.clear();
        window.location.href = '/masuk';
        return;
    }
    const apiUrl = 'https://sirtrw-api.vansite.cloud/api/logout';
    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });
        const result = await response.json();
        if (response.ok) {
            console.log('Logout dari server berhasil:', result.message);
        } else {
            console.warn('Gagal logout dari server:', result.message);
        }
    } catch (error) {
        console.error('Error saat menghubungi server logout:', error);
    } finally {
        console.log('Membersihkan data sesi dari browser...');
        localStorage.clear();
        console.log('Mengarahkan ke halaman login...');
        window.location.href = '/masuk';
    }
}


// =================================================================
// BAGIAN LOGIN (BARU)
// =================================================================
function initLoginFunctionality() {
    const loginForm = document.getElementById('loginForm');
    
    // Jika tidak ada form login di halaman ini, hentikan eksekusi fungsi ini.
    if (!loginForm) {
        return;
    }

    // --- Logika untuk toggle password ---
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#floatingPassword');
    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', function () {
            const isVisible = passwordField.getAttribute('type') === 'password';
            passwordField.setAttribute('type', isVisible ? 'text' : 'password');
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    }

    // --- Logika untuk submit form ---
    const loginError = document.getElementById('loginError');
    const loginSuccess = document.getElementById('loginSuccess');

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        loginError.classList.add('d-none');
        loginSuccess.classList.add('d-none');

        const email = document.querySelector('#floatingEmail').value;
        const password = document.querySelector('#floatingPassword').value;

        const apiBaseUrl = 'https://sirtrw-api.vansite.cloud';
        
        // Menggunakan Axios jika tersedia, atau Fetch sebagai fallback
        const httpClient = window.axios || fetch;

        httpClient.post(apiBaseUrl + '/api/login', {
            email: email,
            password: password
        })
        .then(function (response) {
            loginSuccess.textContent = 'Login berhasil! Anda akan diarahkan...';
            loginSuccess.classList.remove('d-none');

            // Data yang benar ada di response.data
            const responseData = response.data.data; 

            localStorage.setItem('token', responseData.token);
            const userRole = responseData.user.role;
            localStorage.setItem('userRole', userRole);

            // 'roleRedirectRoutes' diambil dari variabel global yang didefinisikan di Blade
            let redirectUrl = window.roleRedirectRoutes[userRole] || '/';

            setTimeout(() => {
                window.location.href = redirectUrl;
            }, 1500);
        })
        .catch(function (error) {
            if (error.response && error.response.data && error.response.data.message) {
                loginError.textContent = error.response.data.message;
            } else {
                loginError.textContent = 'Terjadi kesalahan saat login.';
            }
            loginError.classList.remove('d-none');
        });
    });
}


// =================================================================
// INISIALISASI SEMUA FUNGSI SAAT DOKUMEN SIAP
// =================================================================
document.addEventListener('DOMContentLoaded', () => {
    // Cari dan pasang event listener untuk semua tombol logout
    const logoutButtons = document.querySelectorAll('.logout-button');
    logoutButtons.forEach(button => {
        button.addEventListener('click', handleLogout);
    });

    // Jalankan fungsi untuk inisialisasi form login
    initLoginFunctionality();
});