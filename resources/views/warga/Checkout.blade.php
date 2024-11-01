@extends('layouts.dashboardNavbar')

@section('content')
    <?php
    $page = 'pembayaran'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    <div class="checkout-container">
        <h2>Checkout</h2> <!-- Updated header -->
        <ul id="item-list">
            @php $total = 0; @endphp <!-- Initialize total -->
            @foreach ($selectedItems as $item)
                <li class="item" data-price="5000"> <!-- Adjust price as needed -->
                    <label>{{ $item }}</label>
                    <span>Rp 5.000</span>
                    <span class="remove" onclick="removeItem(this)">remove</span>
                </li>
                @php $total += 5000; @endphp <!-- Update total amount -->
            @endforeach
        </ul>

        <div class="total-section">TOTAL: Rp <span id="total">{{ number_format($total) }}</span></div>
        <!-- Format total -->

        <div class="input-section">
            <p>Transfer ke rekening BRI : xxxx-xxxx-87x-9</p>
            <label>Masukkan nomor rekening yang anda gunakan</label>
            <input type="text" id="account-number" placeholder="Nomor Rekening">
        </div>

        <div class="checkout-buttons">
            <button class="btn pay-btn" onclick="submitPayment()">SUDAH BAYAR</button>
            <button class="btn cancel-btn" onclick="cancelPayment()">BATAL</button>
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
            element.parentElement.remove();
            calculateTotal();
        }

        // Function to handle payment submission
        function submitPayment() {
            const accountNumber = document.getElementById('account-number').value;
            if (!accountNumber) {
                alert("Masukkan nomor rekening Anda.");
                return;
            }
            alert("Pembayaran berhasil!");
        }

        // Function to handle payment cancellation
        function cancelPayment() {
            document.getElementById('account-number').value = '';
            const userConfirmed = confirm("Apakah Anda yakin ingin membatalkan pembayaran?");
            if (userConfirmed) {
                history.back(); // Redirects to the previous page if user confirms
            }
        }
    </script>
@endsection
