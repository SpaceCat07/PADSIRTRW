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
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const token = localStorage.getItem('token');

        try {
            const walletResponse = await axios.get('https://sirtrw-api.vansite.cloud/api/wallet', {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const walletData = walletResponse.data.data;

            // Update Saldo
            const saldoElement = document.querySelector('.saldo-amount');
            if (Array.isArray(walletData) && walletData.length > 0) {
                const latestTransaction = walletData[walletData.length - 1];
                const saldo = latestTransaction.after;
                saldoElement.textContent = `Rp. ${saldo.toLocaleString('id-ID')}`;
            } else {
                saldoElement.textContent = 'Rp. 0';
            }

            // Render Riwayat Transaksi
            const riwayatList = document.querySelector('.riwayat-list');
            riwayatList.innerHTML = ''; // Kosongkan dulu dummy-nya

            walletData.reverse().forEach(tx => {
                const isTopup = tx.transaction_type.toLowerCase() === 'topup';
                const iconSrc = isTopup ? '{{ asset('storage/topupIcon.png') }}' : '{{ asset('storage/paymentIcon.png') }}';
                const statusClass = tx.status.toLowerCase() === 'berhasil' ? 'status-berhasil' : 'status-gagal';
                const nominal = `${tx.transaction_type.toLowerCase() === 'topup' ? '+' : '-'}Rp${parseInt(tx.nominal).toLocaleString('id-ID')}`;

                riwayatList.innerHTML += `
                    <div class="transaksi-item">
                        <div class="transaksi-detail">
                            <img src="${iconSrc}" alt="">
                            <div class="transaksi-subdetail">
                                <span class="transaksi-title">${tx.transaction_type}</span>
                                <span class="transaksi-subtitle">${tx.description}</span>
                            </div>
                        </div>
                        <div class="transaksi-subdetail">
                            <span class="transaksi-amount">${nominal}</span>
                            <span class="${statusClass}">${tx.status}</span>
                        </div>
                    </div>
                `;
            });

        } catch (error) {
            console.error('Gagal mengambil data wallet:', error);
        }
    });

    // Event toggle tampilan
    document.querySelector('.btn-tambah').addEventListener('click', () => {
        document.querySelector('.riwayat-section').style.display = 'none';
        document.querySelector('.topup-section').style.display = 'block';
    });
    document.querySelector('.btn-riwayat').addEventListener('click', () => {
        document.querySelector('.topup-section').style.display = 'none';
        document.querySelector('.riwayat-section').style.display = 'block';
    });

    document.getElementById('nominal-input').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9.]/g, '');
    });
</script>


@endsection