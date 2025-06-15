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
            <p style="font-weight: bold">xxxxxxxx87×9 <button class="salin-button" onclick="navigator.clipboard.writeText('xxxxxxxx87×9')">SALIN</button></p>
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
                <   li>Loading items...</li>
                </div>
            </ul>
        </div>
        
        <div class="total-section">TOTAL : Rp <span id="total">0</span></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const dompetRadio = document.querySelector('input[value="dompet"]');
    const transferRadio = document.querySelector('input[value="transfer"]');
    const pinWrapper = document.getElementById('pinWrapper');
    const transferWrapper = document.getElementById('transferWrapper');

    // Fungsi untuk toggle tampilan
    function togglePaymentSection() {
        if (dompetRadio.checked) {
            pinWrapper.style.display = 'flex';
            transferWrapper.style.display = 'none';
        } else if (transferRadio.checked) {
            pinWrapper.style.display = 'none';
            transferWrapper.style.display = 'flex';
        }
    }

    // Jalankan saat halaman load
    togglePaymentSection();

    // Tambahkan event listener ke radio buttons
    dompetRadio.addEventListener('change', togglePaymentSection);
    transferRadio.addEventListener('change', togglePaymentSection);

    document.addEventListener('DOMContentLoaded', () => {
        const checkoutItemContainer = document.getElementById('checkoutItemContainer');
        const totalSpan = document.getElementById('total');
        const submitPaymentBtn = document.getElementById('submitPaymentBtn');
        const cancelPaymentBtn = document.getElementById('cancelPaymentBtn');

        let items = [];

        function fetchSelectedItems() {
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
                    <label class="item-label">BAYAR KAS BULANAN - ${item}</label>
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
                    <label class="item-label">BAYAR IURAN TAMBAHAN - ${item}</label>
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
            const pinInputs = document.querySelectorAll('.pin-box');
            const pin = Array.from(pinInputs).map(input => input.value).join('');
            if (pin.length < 4) {
                alert("Masukkan PIN dompet Anda.");
                return;
            }
            alert("Pembayaran berhasil! Silakan tunggu konfirmasi.");
            window.location.href = "{{ route('riwayat-pembayaran') }}";
        });

        cancelPaymentBtn.addEventListener('click', () => {
            const userConfirmed = confirm("Batalkan pembayaran?");
            if (userConfirmed) {
                window.history.back();
            }
        });

        fetchSelectedItems();
    });

    // Auto move to next pin box
    document.querySelectorAll('.pin-box').forEach((input, index, inputs) => {
        input.addEventListener('input', () => {
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && input.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
</script>
@endsection