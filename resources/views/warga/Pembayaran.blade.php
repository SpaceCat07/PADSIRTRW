@extends('layouts.dashboardNavbar')

@section('content')
<title>SIMAS - pembayaran</title>
<link rel="stylesheet" href="{{ asset('css/pembayaran.css') }}">
    <?php
    $page = 'pembayaran'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    <div class="payment-container">
        <div class="column-flex">
            <h2 style="color: #5a4dc7">Bayar Iuran Tambahan</h2>
            <select class="year-picker" id="year-picker">
                <option value="" disabled selected>Pilih Tahun</option>
            </select>
        </div>

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
                    <tr>
                        <td colspan="4" class="no-data">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 style="color: #5a4dc7">Bayar Kas Bulanan</h2>

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
                    <tr>
                        <td colspan="4" class="no-data">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button class="pay-button" id="payButton">Bayar</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const yearPicker = document.getElementById('year-picker');
        const additionalPaymentBody = document.getElementById('additionalPaymentBody');
        const monthlyPaymentBody = document.getElementById('monthlyPaymentBody');
        const payButton = document.getElementById('payButton');

        function populateYearPicker() {
            const currentYear = new Date().getFullYear();
            const startYear = 2000; // Change this to the earliest year you want to display

            for (let year = currentYear; year >= startYear; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearPicker.appendChild(option);
            }
        }

        function fetchPaymentData(year) {
            axios.get(`/api/payments?year=${year}`)
                .then(response => {
                    const data = response.data;
                    renderAdditionalPayments(data.additionalPayments);
                    renderMonthlyPayments(data.monthlyPayments);
                })
                .catch(error => {
                    console.error('Error fetching payment data:', error);
                    additionalPaymentBody.innerHTML = '<tr><td colspan="4">Failed to load data.</td></tr>';
                    monthlyPaymentBody.innerHTML = '<tr><td colspan="4">Failed to load data.</td></tr>';
                });
        }

        function renderAdditionalPayments(payments) {
            if (!payments || payments.length === 0) {
                additionalPaymentBody.innerHTML = '<tr><td colspan="4">No data found.</td></tr>';
                return;
            }
            additionalPaymentBody.innerHTML = '';
            payments.forEach(payment => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="checkbox" name="additional_payment[]" value="${payment.id}" /></td>
                    <td>${payment.type}</td>
                    <td>${payment.amount}</td>
                    <td class="${payment.status === 'Lunas' ? 'status-paid' : 'status-due'}">${payment.status}</td>
                `;
                additionalPaymentBody.appendChild(row);
            });
        }

        function renderMonthlyPayments(payments) {
            if (!payments || payments.length === 0) {
                monthlyPaymentBody.innerHTML = '<tr><td colspan="4">No data found.</td></tr>';
                return;
            }
            monthlyPaymentBody.innerHTML = '';
            payments.forEach(payment => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${payment.status === 'Belum Lunas' ? `<input type="checkbox" name="monthly_payment[]" value="${payment.month}" />` : ''}</td>
                    <td>${payment.month}</td>
                    <td>${payment.amount}</td>
                    <td class="${payment.status === 'Lunas' ? 'status-paid' : 'status-due'}">${payment.status}</td>
                `;
                monthlyPaymentBody.appendChild(row);
            });
        }

        payButton.addEventListener('click', () => {
            const selectedAdditional = Array.from(document.querySelectorAll('input[name="additional_payment[]"]:checked')).map(cb => cb.value);
            const selectedMonthly = Array.from(document.querySelectorAll('input[name="monthly_payment[]"]:checked')).map(cb => cb.value);

            if (selectedAdditional.length === 0 && selectedMonthly.length === 0) {
                alert('Please select at least one item to proceed to checkout.');
                return;
            }

            const selectedItems = {
                additional: selectedAdditional,
                monthly: selectedMonthly
            };

            // Redirect to checkout page with selected items as query parameter
            window.location.href = `{{ route('checkout') }}?items=${encodeURIComponent(JSON.stringify(selectedItems))}`;
        });

        yearPicker.addEventListener('change', function() {
            fetchPaymentData(this.value);
        });

        // Initial setup
        populateYearPicker();
        yearPicker.value = new Date().getFullYear();
        fetchPaymentData(yearPicker.value);
    </script>
@endsection
