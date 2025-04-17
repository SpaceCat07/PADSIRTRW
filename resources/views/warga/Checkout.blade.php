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
            @php $total = 0; @endphp <!-- Initialize total -->
            <div class="checkout-item-container">
                @foreach ($selectedItems as $item)
                    <li class="item" data-price="5000"> <!-- Adjust price as needed -->
                        <span class="remove" onclick="removeItem(this)">
                            <div class="remove-container"> <!-- New container for icon and text -->
                                <img src="{{ asset('storage/Remove.png') }}" alt="Remove" class="remove-icon">
                                <span class="remove-text">remove</span> <!-- Text underneath the icon -->
                            </div>
                        </span>
                        <label class="item-label">Bayar Kas Bulanan {{ $item }}</label>
                        <span class="checkout-amount">Rp 5.000</span>
                    </li>
                    @php $total += 5000; @endphp <!-- Update total amount -->
                @endforeach
            </div>
        </ul>

        <div class="total-section">TOTAL: Rp <span id="total">{{ number_format($total) }}</span></div>
        <!-- Format total -->

        <div class="input-section">
            <p>Transfer ke rekening BRI : xxxx-xxxx-87x-9</p>
            <label>Masukkan nomor rekening yang anda gunakan</label>
            <input type="text" id="account-number" placeholder="Nomor Rekening">
        </div>

        <div class="checkout-buttons">
            <button class="checkout-btn pay-btn" onclick="submitPayment()">SUDAH BAYAR</button>
            <button class="checkout-btn cancel-btn" onclick="cancelPayment()">BATAL</button>
        </div>
    </div>

    <script>
        // Function to calculate the total
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.item').forEach(item => {
                total += parseInt(item.getAttribute('data-price'));
            });
            document.getElementById('total').textContent = total.toLocaleString();
        }

        // Function to remove item
        function removeItem(element) {
            // Check if this is the last item
            const items = document.querySelectorAll('.item');
            if (items.length === 1) {
                const userConfirmed = confirm("Batalkan pembayaran?");
                if (!userConfirmed) {
                    return; // If user cancels, do nothing
                }
            }

            // Remove the item and update the total
            element.parentElement.remove();
            calculateTotal();

            // Check if there are any items left
            const remainingItems = document.querySelectorAll('.item');
            if (remainingItems.length === 0) {
                // If no items left, back to previous page
                history.back();
            }
        }

        // Function to handle payment submission
        function submitPayment() {
            const accountNumber = document.getElementById('account-number').value;
            if (!accountNumber) {
                alert("Masukkan nomor rekening Anda.");
                return;
            }
            alert("Pembayaran berhasil! Silahkan tunggu konfirmasi pembayaran anda.");

            window.location.href = "{{ route('riwayat-pembayaran') }}"
        }

        // Function to handle payment cancellation
        function cancelPayment() {
            document.getElementById('account-number').value = '';
            const userConfirmed = confirm("Batalkan pembayaran?");
            if (userConfirmed) {
                history.back(); // Redirects to the previous page if user confirms
            }
        }
    </script>
@endsection
