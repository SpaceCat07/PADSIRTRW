@extends('layouts.checkoutNavbar')

@section('content')
    <title>SIMAS - Pembayaran</title>
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <?php $page = 'pembayaran'; ?>

    <div class="checkout-container">
        <div class="input-section">
            <h2>Pembayaran</h2>
            <p>Pilih opsi pembayaran :</p>
            <div class="payment-options">
                <label class="payment-option">
                    <input type="radio" name="payment-method" value="dompet" checked> Dompet
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment-method" value="transfer"> Transfer
                </label>
            </div>

            <div class="pin-wrapper" id="pinWrapper">
                <p>Masukkan 4 digit pin dompet Anda untuk mengonfirmasi pembayaran</p>
                <div class="pin-input">
                    <input type="password" maxlength="1" class="pin-box">
                    <input type="password" maxlength="1" class="pin-box">
                    <input type="password" maxlength="1" class="pin-box">
                    <input type="password" maxlength="1" class="pin-box">
                </div>
            </div>

            <div class="transfer-wrapper" id="transferWrapper">
                <label for="rekeningInput">Masukkan nomor rekening yang anda gunakan</label>
                <input type="text" id="rekeningInput" class="transfer-input" placeholder="Contoh: 1234567890">
                <p>Transfer ke rekening BRI a.n. Ismail Kanabawi</p>
                <p style="font-weight: bold">xxxxxxxx87×9 <button class="salin-button"
                        onclick="navigator.clipboard.writeText('xxxxxxxx87×9')">SALIN</button></p>
            </div>


            <div class="checkout-buttons">
                <button class="checkout-btn pay-btn" id="submitPaymentBtn">KONFIRMASI PEMBAYARAN</button>
                <button class="checkout-btn cancel-btn" id="cancelPaymentBtn">BATAL</button>
            </div>
        </div>

        <div class="item-container">
            <div class="top-content">
                <h2 style="color: #43459B; margin-left: 30px">ITEM</h2>
                <ul id="item-list">
                    <div class="checkout-item-container" id="checkoutItemContainer">
                        < li>Loading items...</li>
                    </div>
                </ul>
            </div>

            <div class="total-section">TOTAL : Rp <span id="total">0</span></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // =================================================================
            // 1. SETUP & DEKLARASI ELEMEN
            // =================================================================
            const dompetRadio = document.querySelector('input[value="dompet"]');
            const transferRadio = document.querySelector('input[value="transfer"]');
            const pinWrapper = document.getElementById('pinWrapper');
            const transferWrapper = document.getElementById('transferWrapper');
            const checkoutItemContainer = document.getElementById('checkoutItemContainer');
            const totalSpan = document.getElementById('total');
            const pinInputs = document.querySelectorAll('.pin-box');
            const submitPaymentBtn = document.getElementById('submitPaymentBtn');
            const cancelPaymentBtn = document.getElementById('cancelPaymentBtn');
            const token = localStorage.getItem('token');
            let checkoutData = { items: [], total: 0 };
            let currentWalletBalance = 0;

            // Cek otentikasi
            if (!token) {
                alert("Sesi Anda telah berakhir. Silakan login kembali.");
                window.location.href = "{{ route('masuk') }}"; // Arahkan ke halaman login
                return;
            }

            const axiosInstance = axios.create({
                baseURL: 'https://sirtrw-api.vansite.cloud/api',
                headers: { 'Authorization': `Bearer ${token}` }
            });

            const formatCurrency = (value) => Number(value).toLocaleString('id-ID');

            // =================================================================
            // 2. LOGIKA UTAMA & RENDER
            // =================================================================

            /**
             * Fungsi untuk toggle tampilan antara metode pembayaran Dompet dan Transfer
             */
            function togglePaymentSection() {
                if (dompetRadio.checked) {
                    pinWrapper.style.display = 'flex';
                    transferWrapper.style.display = 'none';
                } else if (transferRadio.checked) {
                    pinWrapper.style.display = 'none';
                    transferWrapper.style.display = 'flex';
                }
            }

            /**
             * Mengambil data dari URL dan saldo dompet saat halaman dimuat
             */
            async function initializeCheckout() {
                const urlParams = new URLSearchParams(window.location.search);
                const dataParam = urlParams.get('data');

                if (!dataParam) {
                    alert("Tidak ada item yang dipilih untuk dibayar.");
                    window.history.back();
                    return;
                }

                try {
                    checkoutData = JSON.parse(decodeURIComponent(dataParam));

                    const walletResponse = await axiosInstance.get('/wallet');
                    const walletHistory = walletResponse.data.data;
                    if (walletHistory && walletHistory.length > 0) {
                        currentWalletBalance = walletHistory[walletHistory.length - 1].after;
                    }

                    renderItems();
                } catch (error) {
                    console.error("Initialization Error:", error);
                    alert("Gagal memuat data checkout atau saldo dompet.");
                    checkoutItemContainer.innerHTML = '<li>Gagal memuat item.</li>';
                }
            }

            /**
             * Menampilkan item-item yang akan dibayar
             */
            function renderItems() {
                checkoutItemContainer.innerHTML = '';
                if (!checkoutData || !checkoutData.items || checkoutData.items.length === 0) {
                    checkoutItemContainer.innerHTML = '<li>Tidak ada item untuk ditampilkan.</li>';
                    totalSpan.textContent = '0';
                    return;
                }

                checkoutData.items.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'item';
                    li.dataset.id = item.id;
                    li.dataset.price = item.value;
                    const itemName = item.month ? `Kas Bulanan - ${item.month}` : `Iuran - ${item.name}`;

                    li.innerHTML = `
                            <span class="remove" onclick="removeItem(this)">
                                <div class="remove-container">
                                    <img src="{{ asset('storage/Remove.png') }}" alt="Remove" class="remove-icon">
                                    <span class="remove-text">remove</span>
                                </div>
                            </span>
                            <label class="item-label">${itemName}</label>
                            <span class="checkout-amount">Rp ${formatCurrency(item.value)}</span>
                        `;
                    checkoutItemContainer.appendChild(li);
                });
                calculateTotal();
            }

            /**
             * Menghitung ulang total pembayaran setelah ada item yang dihapus
             */
            function calculateTotal() {
                let newTotal = 0;
                const currentItems = [];
                document.querySelectorAll('.item').forEach(itemElement => {
                    newTotal += parseFloat(itemElement.dataset.price);
                    const originalItem = checkoutData.items.find(i => i.id == itemElement.dataset.id);
                    if (originalItem) currentItems.push(originalItem);
                });

                checkoutData.total = newTotal;
                checkoutData.items = currentItems;
                totalSpan.textContent = formatCurrency(newTotal);
            }

            /**
             * Menghapus item dari daftar checkout (dibuat global agar bisa diakses onclick)
             */
            window.removeItem = function (element) {
                element.closest('.item').remove();
                calculateTotal();

                if (checkoutData.items.length === 0) {
                    alert("Semua item telah dihapus. Kembali ke halaman pembayaran.");
                    window.history.back();
                }
            };

            /**
             * Fungsi utama untuk submit pembayaran
             */
            async function submitPayment() {
                // Jika metode transfer dipilih, tampilkan pesan dan hentikan proses
                if (transferRadio.checked) {
                    alert("Metode pembayaran transfer belum tersedia saat ini.");
                    return;
                }

                // --- Validasi di sisi pengguna (tetap dipertahankan) ---
                const pin = Array.from(pinInputs).map(input => input.value).join('');

                if (pin.length !== 4) {
                    alert("PIN tidak valid. Harap masukkan 4 digit PIN Anda.");
                    return;
                }

                if (currentWalletBalance < checkoutData.total) {
                    alert(`Saldo dompet tidak mencukupi. Saldo Anda: Rp ${formatCurrency(currentWalletBalance)}`);
                    return;
                }

                submitPaymentBtn.disabled = true;
                submitPaymentBtn.textContent = 'MEMPROSES...';

                try {
                    // =================================================================
                    //            BAGIAN PEMOTONGAN SALDO TOTAL DIHAPUS
                    // =================================================================
                    // const walletPayload = { ... };
                    // await axiosInstance.post('/wallet', walletPayload); --> BARIS INI DIHAPUS


                    // LANGSUNG KE LANGKAH PENCATATAN PEMBAYARAN IURAN SATU PER SATU
                    // Backend akan menangani pemotongan saldo untuk setiap request ini
                    console.log('Memulai pencatatan pembayaran iuran satu per satu...');
                    for (const item of checkoutData.items) {
                        const iuranPayPayload = { iuran: item.id };
                        console.log(`Mengirim pencatatan untuk iuran ID: ${item.id}`);
                        await axiosInstance.post('/iuran/pay', iuranPayPayload);
                        console.log(`Pencatatan untuk iuran ID: ${item.id} berhasil.`);
                    }

                    // JIKA SEMUA BERHASIL, BERI NOTIFIKASI & REDIRECT
                    alert("Pembayaran berhasil!");
                    window.location.href = "{{ route('riwayat-pembayaran') }}";

                } catch (error) {
                    console.error("Payment Error:", error.response ? error.response.data : error);
                    alert("Pembayaran gagal. Mohon periksa riwayat transaksi atau hubungi admin untuk konfirmasi.");
                    submitPaymentBtn.disabled = false;
                    submitPaymentBtn.textContent = 'KONFIRMASI PEMBAYARAN';
                }
            }

            // =================================================================
            // 3. EVENT LISTENERS & INISIALISASI
            // =================================================================
            dompetRadio.addEventListener('change', togglePaymentSection);
            transferRadio.addEventListener('change', togglePaymentSection);
            submitPaymentBtn.addEventListener('click', submitPayment);
            cancelPaymentBtn.addEventListener('click', () => {
                if (confirm("Apakah Anda yakin ingin membatalkan pembayaran?")) {
                    window.history.back();
                }
            });

            // Logika untuk input PIN (auto-focus dan backspace)
            pinInputs.forEach((input, index) => {
                input.addEventListener('input', () => {
                    if (input.value.length === 1 && index < pinInputs.length - 1) {
                        pinInputs[index + 1].focus();
                    }
                });
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && input.value === '' && index > 0) {
                        pinInputs[index - 1].focus();
                    }
                });
            });

            // Panggil fungsi-fungsi inisialisasi
            togglePaymentSection(); // Atur tampilan awal metode pembayaran
            initializeCheckout(); // Ambil data dan render halaman
        });
    </script>
@endsection