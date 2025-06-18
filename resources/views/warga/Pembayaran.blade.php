@extends('layouts.dashboardNavbar')

@section('content')
<title>SIMAS - Pembayaran</title>
<link rel="stylesheet" href="{{ asset('css/pembayaran.css') }}">
    <?php
    $page = 'pembayaran';
    ?>

    <div class="payment-container">
        <div class="column-flex">
            {{-- Judul untuk Iuran Tambahan --}}
            <h2 style="color: #5a4dc7">Bayar Iuran Tambahan</h2>
            {{-- Dropdown untuk memilih tahun --}}
            <select class="year-picker" id="year-picker">
                <option value="" disabled selected>Pilih Tahun</option>
            </select>
        </div>

        {{-- Tabel untuk Iuran Tambahan --}}
        <div class="additional-payment">
            <table>
                <thead>
                    <tr>
                        <th>Pilih</th>
                        <th>Jenis Iuran</th>
                        <th>Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="additionalPaymentBody">
                    {{-- Data akan diisi oleh JavaScript --}}
                    <tr>
                        <td colspan="4" class="no-data">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Judul untuk Kas Bulanan --}}
        <h2 style="color: #5a4dc7">Bayar Kas Bulanan</h2>

        {{-- Tabel untuk Kas Bulanan --}}
        <div class="monthly-payment">
            <table>
                <thead>
                    <tr>
                        <th>Pilih</th>
                        <th>Bulan</th>
                        <th>Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="monthlyPaymentBody">
                    {{-- Data akan diisi oleh JavaScript --}}
                    <tr>
                        <td colspan="4" class="no-data">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Tombol untuk proses pembayaran --}}
        <button class="pay-button" id="payButton">Bayar</button>
    </div>

    {{-- ====================================================== --}}
    {{--                SCRIPT FUNGSIONAL LENGKAP               --}}
    {{-- ====================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {

        // =================================================================
        // 1. SETUP & DEKLARASI ELEMEN
        // =================================================================
        const yearPicker = document.getElementById('year-picker');
        const additionalPaymentBody = document.getElementById('additionalPaymentBody');
        const monthlyPaymentBody = document.getElementById('monthlyPaymentBody');
        const payButton = document.getElementById('payButton');

        const token = localStorage.getItem('token');
        let allRtIuran = []; // Akan menyimpan semua iuran untuk RT pengguna
        let userPaidIuranIds = new Set(); // Akan menyimpan ID iuran yang sudah dibayar pengguna

        // Cek otentikasi
        if (!token) {
            showError("Sesi Anda telah berakhir. Silakan login kembali.");
            return;
        }

        const axiosInstance = axios.create({
            baseURL: 'https://sirtrw-api.vansite.cloud/api',
            headers: { 'Authorization': `Bearer ${token}` }
        });

        const formatCurrency = (value) => `Rp ${Number(value).toLocaleString('id-ID')}`;

        // =================================================================
        // 2. FUNGSI-FUNGSI LOGIKA
        // =================================================================

        /**
         * Mengambil semua data awal yang diperlukan dari server
         */
        async function initializePageData() {
            try {
                const [meResponse, iuranResponse, paymentHistoryResponse] = await Promise.all([
                    axiosInstance.get('/me'),
                    axiosInstance.get('/iuran'),
                    axiosInstance.get('/iuran/pay') // Menggunakan endpoint riwayat pembayaran yang benar
                ]);

                const userRtId = meResponse.data.data.warga.rt_id;

                // Filter iuran berdasarkan RT pengguna
                allRtIuran = iuranResponse.data.data.rt.filter(iuran => iuran.rt_id === userRtId);

                // Buat set (daftar unik) dari ID iuran yang sudah lunas atau sedang diproses
                const userPayments = paymentHistoryResponse.data.data || [];
                userPayments.forEach(payment => {
                    if (payment.iuran_id) {
                        userPaidIuranIds.add(payment.iuran_id);
                    }
                });

                // Setelah semua data siap, populate halaman
                populateYearPicker();
                const currentYear = new Date().getFullYear();
                yearPicker.value = currentYear;
                processAndDisplayIuran(currentYear);

            } catch (error) {
                console.error('Initialization failed:', error);
                showError("Gagal memuat data. Periksa koneksi Anda dan coba lagi.");
            }
        }

        /**
         * Mengisi dropdown tahun
         */
        function populateYearPicker() {
            const currentYear = new Date().getFullYear();
            const startYear = 2020; // Tahun paling awal yang bisa dipilih
            for (let year = currentYear; year >= startYear; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearPicker.appendChild(option);
            }
        }

        /**
         * Memproses dan menampilkan iuran untuk tahun yang dipilih
         */
        function processAndDisplayIuran(year) {
            // Filter iuran berdasarkan tahun dari 'created_at'
            const iuranForYear = allRtIuran.filter(iuran => new Date(iuran.created_at).getFullYear() == year);

            const monthlyIuran = iuranForYear.filter(i => i.variance === 'monthly');
            const additionalIuran = iuranForYear.filter(i => i.variance === 'additional');

            renderPaymentTable(monthlyPaymentBody, monthlyIuran, 'monthly');
            renderPaymentTable(additionalPaymentBody, additionalIuran, 'additional');
        }

        /**
         * Merender data ke dalam tabel iuran (bulanan atau tambahan)
         */
        function renderPaymentTable(tableBody, iuranData, type) {
            tableBody.innerHTML = ''; // Kosongkan tabel
            if (!iuranData || iuranData.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="no-data">Tidak ada iuran pada tahun ini.</td></tr>';
                return;
            }

            iuranData.forEach(iuran => {
                const isPaid = userPaidIuranIds.has(iuran.id);
                const statusClass = isPaid ? 'status-paid' : 'status-due';
                const statusText = isPaid ? 'Lunas' : 'Belum Lunas';
                const displayName = type === 'monthly' ? iuran.month : iuran.name;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <input type="checkbox" 
                               data-amount="${iuran.value}" 
                               value="${iuran.id}" 
                               name="${type}_payment[]"
                               ${isPaid ? 'checked disabled' : ''}>
                    </td>
                    <td>${displayName}</td>
                    <td>${formatCurrency(iuran.value)}</td>
                    <td class="${statusClass}">${statusText}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        /**
         * Menangani proses pembayaran saat tombol "Bayar" diklik
         */
        function handlePayment() {
            const selectedItems = [];
            // Ambil semua checkbox yang dicentang dan tidak di-disable
            const checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked:not(:disabled)');

            if (checkedBoxes.length === 0) {
                alert('Silakan pilih minimal satu iuran untuk dibayar.');
                return;
            }

            let totalAmount = 0;
            checkedBoxes.forEach(cb => {
                const id = parseInt(cb.value);
                const amount = parseFloat(cb.dataset.amount);
                totalAmount += amount;
                
                // Cari detail iuran untuk dikirim ke checkout
                const iuranDetail = allRtIuran.find(i => i.id === id);
                if(iuranDetail) {
                    selectedItems.push({
                        id: iuranDetail.id,
                        name: iuranDetail.name,
                        month: iuranDetail.month,
                        value: iuranDetail.value
                    });
                }
            });

            const checkoutData = {
                items: selectedItems,
                total: totalAmount
            };

            // Redirect ke halaman checkout dengan membawa data iuran yang dipilih
            // Data dikirim sebagai JSON string dalam query parameter 'data'
            console.log("Redirecting to checkout with data:", checkoutData);
            window.location.href = `{{ route('checkout') }}?data=${encodeURIComponent(JSON.stringify(checkoutData))}`;
        }

        /**
         * Menampilkan pesan error di kedua tabel
         */
        function showError(message) {
            const errorHtml = `<tr><td colspan="4" class="no-data">${message}</td></tr>`;
            additionalPaymentBody.innerHTML = errorHtml;
            monthlyPaymentBody.innerHTML = errorHtml;
            payButton.disabled = true; // Matikan tombol bayar jika error
        }

        // =================================================================
        // 3. EVENT LISTENERS & INISIALISASI
        // =================================================================
        yearPicker.addEventListener('change', (e) => {
            processAndDisplayIuran(e.target.value);
        });

        payButton.addEventListener('click', handlePayment);

        // Mulai semuanya dengan memanggil fungsi inisialisasi
        initializePageData();
    });
    </script>

@endsection