@extends('layouts.checkoutNavbar')

@section('content')
<title>SIMAS - checkout</title>
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <?php
    $page = 'pembayaran'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    <div class="checkout-container">
        <h2 style="color: #43459B; margin-left: 30px">Item</h2> <!-- Updated header -->
        <ul id="item-list">
            <div class="checkout-item-container" id="checkoutItemContainer">
                <li>Loading items...</li>
            </div>
        </ul>

        <div class="total-section">TOTAL: Rp <span id="total">0</span></div>
        <!-- Format total -->

        <div class="input-section">
            <p>Transfer ke rekening BRI : xxxx-xxxx-87x-9</p>
            <label>Masukkan nomor rekening yang anda gunakan</label>
            <input type="text" id="account-number" placeholder="Nomor Rekening">
        </div>

        <div class="checkout-buttons">
            <button class="checkout-btn pay-btn" id="submitPaymentBtn">SUDAH BAYAR</button>
            <button class="checkout-btn cancel-btn" id="cancelPaymentBtn">BATAL</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkoutItemContainer = document.getElementById('checkoutItemContainer');
            const totalSpan = document.getElementById('total');
            const accountNumberInput = document.getElementById('account-number');
            const submitPaymentBtn = document.getElementById('submitPaymentBtn');
            const cancelPaymentBtn = document.getElementById('cancelPaymentBtn');

            let items = [];

            function fetchSelectedItems() {
                // Fetch selected items from API endpoint instead of query params
                axios.get('/api/selected-items')
                    .then(response => {
                        items = response.data;
                        renderItems();
                    })
                    .catch(error => {
                        checkoutItemContainer.innerHTML = '<li>Failed to load items.</li>';
                        console.error('Error fetching selected items:', error);
                    });
            }

            function renderItems() {
                checkoutItemContainer.innerHTML = '';
                let total = 0;
                if (!items || !items.monthly || !items.additional) {
                    checkoutItemContainer.innerHTML = '<li>No items to display.</li>';
                    totalSpan.textContent = '0';
                    return;
                }
                // For simplicity, assume each item costs 5000
                const pricePerItem = 5000;

                items.monthly.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'item';
                    li.setAttribute('data-price', pricePerItem);
                    li.innerHTML = `
                        <span class="remove" onclick="removeItem(this)">
                            <div class="remove-container">
                                <img src="{{ asset('storage/Remove.png') }}" alt="Remove" class="remove-icon">
                                <span class="remove-text">remove</span>
                            </div>
                        </span>
                        <label class="item-label">Bayar Kas Bulanan ${item}</label>
                        <span class="checkout-amount">Rp ${pricePerItem.toLocaleString()}</span>
                    `;
                    checkoutItemContainer.appendChild(li);
                    total += pricePerItem;
                });

                items.additional.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'item';
                    li.setAttribute('data-price', pricePerItem);
                    li.innerHTML = `
                        <span class="remove" onclick="removeItem(this)">
                            <div class="remove-container">
                                <img src="{{ asset('storage/Remove.png') }}" alt="Remove" class="remove-icon">
                                <span class="remove-text">remove</span>
                            </div>
                        </span>
                        <label class="item-label">Bayar Iuran Tambahan ${item}</label>
                        <span class="checkout-amount">Rp ${pricePerItem.toLocaleString()}</span>
                    `;
                    checkoutItemContainer.appendChild(li);
                    total += pricePerItem;
                });

                totalSpan.textContent = total.toLocaleString();
            }

            window.removeItem = function(element) {
                const li = element.closest('.item');
                li.remove();
                calculateTotal();
                if (checkoutItemContainer.children.length === 0) {
                    window.history.back();
                }
            };

            function calculateTotal() {
                let total = 0;
                document.querySelectorAll('.item').forEach(item => {
                    total += parseInt(item.getAttribute('data-price'));
                });
                totalSpan.textContent = total.toLocaleString();
            }

            submitPaymentBtn.addEventListener('click', () => {
                const accountNumber = accountNumberInput.value.trim();
                if (!accountNumber) {
                    alert("Masukkan nomor rekening Anda.");
                    return;
                }
                // Here you can send payment confirmation to API if needed
                alert("Pembayaran berhasil! Silahkan tunggu konfirmasi pembayaran anda.");
                window.location.href = "{{ route('riwayat-pembayaran') }}";
            });

            cancelPaymentBtn.addEventListener('click', () => {
                accountNumberInput.value = '';
                const userConfirmed = confirm("Batalkan pembayaran?");
                if (userConfirmed) {
                    window.history.back();
                }
            });

            fetchSelectedItems();
        });
    </script>
@endsection
