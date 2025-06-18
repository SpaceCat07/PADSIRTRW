@extends('layouts.checkoutNavbar')

<title>SIMAS - dompetku</title>
<link rel="stylesheet" href="{{ asset('css/dompet-warga.css') }}">
<?php
$page = 'dashboard'; // or 'program-kerja', 'pembayaran', etc.
    ?>

@section('content')

    <div class="dompet-container">
        <div class="saldo-container">
            <h2>DOMPET SIMAS</h2>
            <div class="saldo-section">
                <p>Saldo Dompet Anda</p>
                <div class="saldo-amount">Rp 100.000</div>
            </div>
            <div class="saldo-buttons">
                <button class="btn-tambah">tambah saldo</button>
                <button class="btn-riwayat">riwayat transaksi</button>
            </div>
        </div>

        <div class="riwayat-section">
            <h3>Riwayat Transaksi Dompet</h3>
            <div class="riwayat-list">
                <div class="transaksi-item">
                    <div class="transaksi-detail">
                        <img src="{{ asset('storage/paymentIcon.png') }}" alt="">
                        <div class="transaksi-subdetail">
                            <span class="transaksi-title">Bayar Iuran</span>
                            <span class="transaksi-subtitle">Maret</span>
                        </div>
                    </div>
                    <div class="transaksi-subdetail">
                        <span class="transaksi-amount">-Rp32.000</span>
                        <span class="status-gagal">Gagal</span>
                    </div>
                </div>

                <div class="transaksi-item">
                    <div class="transaksi-detail">
                        <img src="{{ asset('storage/topupIcon.png') }}" alt="">
                        <div class="transaksi-subdetail">
                            <span class="transaksi-title">Top Up</span>
                            <span class="transaksi-subtitle">Dompet</span>
                        </div>
                    </div>
                    <div class="transaksi-subdetail">
                        <span class="transaksi-amount">+Rp32.000</span>
                        <span class="status-berhasil">Berhasil</span>
                    </div>
                </div>

                <div class="transaksi-item">
                    <div class="transaksi-detail">
                        <img src="{{ asset('storage/paymentIcon.png') }}" alt="">
                        <div class="transaksi-subdetail">
                            <span class="transaksi-title">Bayar Iuran</span>
                            <span class="transaksi-subtitle">Februari</span>
                        </div>
                    </div>
                    <div class="transaksi-subdetail">
                        <span class="transaksi-amount">-Rp32.000</span>
                        <span class="status-berhasil">Berhasil</span>
                    </div>
                </div>

                <div class="transaksi-item">
                    <div class="transaksi-detail">
                        <img src="{{ asset('storage/paymentIcon.png') }}" alt="">
                        <div class="transaksi-subdetail">
                            <span class="transaksi-title">Bayar Iuran</span>
                            <span class="transaksi-subtitle">Februari</span>
                        </div>
                    </div>
                    <div class="transaksi-subdetail">
                        <span class="transaksi-amount">-Rp32.000</span>
                        <span class="status-gagal">Gagal</span>
                    </div>
                </div>

                <div class="transaksi-item">
                    <div class="transaksi-detail">
                        <img src="{{ asset('storage/paymentIcon.png') }}" alt="">
                        <div class="transaksi-subdetail">
                            <span class="transaksi-title">Bayar Iuran</span>
                            <span class="transaksi-subtitle">Maret</span>
                        </div>
                    </div>
                    <div class="transaksi-subdetail">
                        <span class="transaksi-amount">-Rp32.000</span>
                        <span class="status-gagal">Gagal</span>
                    </div>
                </div>

                <div class="transaksi-item">
                    <div class="transaksi-detail">
                        <img src="{{ asset('storage/topupIcon.png') }}" alt="">
                        <div class="transaksi-subdetail">
                            <span class="transaksi-title">Top Up</span>
                            <span class="transaksi-subtitle">Dompet</span>
                        </div>
                    </div>
                    <div class="transaksi-subdetail">
                        <span class="transaksi-amount">+Rp32.000</span>
                        <span class="status-berhasil">Berhasil</span>
                    </div>
                </div>

                <div class="transaksi-item">
                    <div class="transaksi-detail">
                        <img src="{{ asset('storage/paymentIcon.png') }}" alt="">
                        <div class="transaksi-subdetail">
                            <span class="transaksi-title">Bayar Iuran</span>
                            <span class="transaksi-subtitle">Februari</span>
                        </div>
                    </div>
                    <div class="transaksi-subdetail">
                        <span class="transaksi-amount">-Rp32.000</span>
                        <span class="status-berhasil">Berhasil</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="topup-section" style="display: none;">
            <h3>Tambah Saldo</h3>
            <hr>
            <div class="topup-form">
                <p>Masukkan nominal tambah saldo</p>
                <div class="nominal-input">
                    <span>Rp</span>
                    <input type="text" placeholder="000.000" id="nominal-input">
                </div>
                <div class="metode-pembayaran">
                    <label>Pilih metode pembayaran</label>
                    <select>
                        <option>Transfer Bank</option>
                        <option>QRIS</option>
                        <option>OVO</option>
                        <option>GoPay</option>
                    </select>
                </div>
                <button class="btn-lanjutkan">Lanjutkan</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // =================================================================
            // 1. SETUP & DEKLARASI ELEMEN
            // =================================================================
            const saldoElement = document.querySelector('.saldo-amount');
            const riwayatListContainer = document.querySelector('.riwayat-list');
            const riwayatSection = document.querySelector('.riwayat-section');
            const topupSection = document.querySelector('.topup-section');
            const btnTambah = document.querySelector('.btn-tambah');
            const btnRiwayat = document.querySelector('.btn-riwayat');

            // [BARU] Elemen untuk form Top Up
            const nominalInput = document.getElementById('nominal-input');
            const btnLanjutkan = document.querySelector('.btn-lanjutkan');

            const token = localStorage.getItem('token');
            if (!token) {
                saldoElement.textContent = 'Error';
                riwayatListContainer.innerHTML = '<div class="transaksi-item"><p>Sesi berakhir, silakan login kembali.</p></div>';
                return;
            }

            const axiosInstance = axios.create({
                baseURL: 'https://sirtrw-api.vansite.cloud/api',
                headers: { 'Authorization': `Bearer ${token}` }
            });

            // Helper untuk format
            const formatCurrency = (value) => `Rp${Number(value).toLocaleString('id-ID')}`;
            const formatDate = (dateString) => new Date(dateString).toLocaleDateString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
            });

            // =================================================================
            // 2. LOGIKA UTAMA & RENDER
            // =================================================================

            async function loadWalletPage() {
                try {
                    const response = await axiosInstance.get('/wallet');
                    const walletData = response.data.data || [];
                    updateBalanceUI(walletData);
                    renderHistoryUI(walletData);
                } catch (error) {
                    console.error('Gagal mengambil data wallet:', error);
                    saldoElement.textContent = 'Error';
                    riwayatListContainer.innerHTML = '<div class="transaksi-item"><p>Gagal memuat riwayat transaksi.</p></div>';
                }
            }

            function updateBalanceUI(walletData) {
                if (walletData.length > 0) {
                    const latestTransaction = walletData[walletData.length - 1];
                    saldoElement.textContent = formatCurrency(latestTransaction.after);
                } else {
                    saldoElement.textContent = formatCurrency(0);
                }
            }

            function renderHistoryUI(walletData) {
                riwayatListContainer.innerHTML = '';
                if (walletData.length === 0) {
                    riwayatListContainer.innerHTML = '<div class="transaksi-item" style="justify-content: center;"><p>Belum ada riwayat transaksi.</p></div>';
                    return;
                }

                walletData.slice().reverse().forEach(tx => {
                    const isOutflow = tx.variance === 'outflow' || tx.value < 0;
                    const iconSrc = isOutflow ? "{{ asset('storage/paymentIcon.png') }}" : "{{ asset('storage/topupIcon.png') }}";
                    const amountClass = isOutflow ? 'outflow' : 'inflow';
                    const amountSign = isOutflow ? '-' : '+';
                    const displayValue = Math.abs(tx.value);
                    const statusText = tx.status === 'success' ? 'Berhasil' : 'Gagal';
                    const statusClass = tx.status === 'success' ? 'status-berhasil' : 'status-gagal';
                    const displayTitle = isOutflow ? `Pembayaran ${tx.name}` : tx.name;

                    const transactionItem = document.createElement('div');
                    transactionItem.className = 'transaksi-item';
                    transactionItem.innerHTML = `
                    <div class="transaksi-detail">
                        <img src="${iconSrc}" alt="Transaction Icon">
                        <div class="transaksi-subdetail">
                            <span class="transaksi-title">${displayTitle}</span>
                            <span class="transaksi-subtitle">${formatDate(tx.created_at)}</span>
                        </div>
                    </div>
                    <div class="transaksi-subdetail" style="text-align: right;">
                        <span class="transaksi-amount ${amountClass}">${amountSign}${formatCurrency(displayValue)}</span>
                        <span class="${statusClass}">${statusText}</span>
                    </div>
                `;
                    riwayatListContainer.appendChild(transactionItem);
                });
            }

            // [BARU] Fungsi untuk menangani proses Top Up
            async function handleTopUp() {
                const nominalValue = parseInt(nominalInput.value.replace(/[^0-9]/g, ''));

                // Validasi input
                if (!nominalValue || nominalValue <= 0) {
                    alert("Silakan masukkan nominal tambah saldo yang valid.");
                    return;
                }

                btnLanjutkan.disabled = true;
                btnLanjutkan.textContent = 'MEMPROSES...';

                try {
                    const payload = {
                        name: "Top Up",
                        status: "success",
                        value: nominalValue // value positif untuk pemasukan
                    };

                    const response = await axiosInstance.post('/wallet', payload);

                    if (response.data.success) {
                        alert(`Top Up sebesar ${formatCurrency(nominalValue)} berhasil!`);

                        // Muat ulang data dompet untuk update saldo dan riwayat
                        await loadWalletPage();

                        // Kembalikan tampilan ke riwayat transaksi
                        topupSection.style.display = 'none';
                        riwayatSection.style.display = 'block';

                        // Kosongkan input
                        nominalInput.value = '';
                    } else {
                        throw new Error(response.data.message || 'Terjadi kesalahan dari server.');
                    }

                } catch (error) {
                    console.error('Top Up Gagal:', error);
                    alert('Proses Top Up gagal, silakan coba lagi.');
                } finally {
                    // Apapun hasilnya, aktifkan kembali tombol
                    btnLanjutkan.disabled = false;
                    btnLanjutkan.textContent = 'Lanjutkan';
                }
            }

            // =================================================================
            // 3. EVENT LISTENERS & INISIALISASI
            // =================================================================

            btnTambah.addEventListener('click', () => {
                riwayatSection.style.display = 'none';
                topupSection.style.display = 'block';
            });

            btnRiwayat.addEventListener('click', () => {
                topupSection.style.display = 'none';
                riwayatSection.style.display = 'block';
            });

            // [BARU] Event listener untuk tombol Lanjutkan pada form Top Up
            btnLanjutkan.addEventListener('click', handleTopUp);

            nominalInput.addEventListener('input', function (e) {
                // Format input dengan pemisah ribuan saat pengguna mengetik
                let value = this.value.replace(/[^0-9]/g, '');
                if (value) {
                    this.value = Number(value).toLocaleString('id-ID');
                } else {
                    this.value = '';
                }
            });

            // Panggil fungsi utama untuk memuat halaman
            loadWalletPage();
        });
    </script>
@endsection