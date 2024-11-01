@extends('layouts.dashboardNavbar')

@section('content')
    <?php
    $page = 'pembayaran'; // or 'program-kerja', 'pembayaran', etc.
    $status = 'Belum Lunas'; // Define a default status for testing
    ?>

    <div class="payment-container">
        <h2>Bayar Iuran Tambahan</h2>

        <div class="additional-payment">
            <button class="year-picker">Pilih Tahun</button>
            <table>
                <thead>
                    <tr>
                        <th>Pilih</th>
                        <th>Jenis Iuran</th>
                        <th>Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" class="no-data">Not Found</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>Bayar Kas Bulanan</h2>

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
                <tbody>
                    <tr>
                        <td><input type="checkbox" name="monthly_payment[]" value="January" /></td>
                        <td>Januari</td>
                        <td>Rp 5.000</td>
                        <td class="status-paid">Lunas</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="monthly_payment[]" value="February" /></td>
                        <td>Februari</td>
                        <td>Rp 5.000</td>
                        <td class="status-paid">Lunas</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="monthly_payment[]" value="March" /></td>
                        <td>Maret</td>
                        <td>Rp 5.000</td>
                        <td class="status-paid">Lunas</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="monthly_payment[]" value="April" /></td>
                        <td>April</td>
                        <td>Rp 5.000</td>
                        <td class="status-paid">Lunas</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="monthly_payment[]" value="May" /></td>
                        <td>Mei</td>
                        <td>Rp 5.000</td>
                        <td class="status-due">Belum Lunas</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="monthly_payment[]" value="June" /></td>
                        <td>Juni</td>
                        <td>Rp 5.000</td>
                        <td class="status-due">Belum Lunas</td>
                    </tr>
                    <!-- Add remaining months similarly -->
                </tbody>
            </table>
        </div>

        <button class="pay-button" onclick="proceedToCheckout()">Bayar</button>

        <script>
            function proceedToCheckout() {
                const selectedItems = [];
                const checkboxes = document.querySelectorAll('input[name="monthly_payment[]"]:checked');

                checkboxes.forEach((checkbox) => {
                    selectedItems.push(checkbox.value); // Get the value of each checked item
                });

                if (selectedItems.length > 0) {
                    // Redirect to the checkout page with selected items as query parameters
                    window.location.href = `{{ route('checkout') }}?items=${encodeURIComponent(JSON.stringify(selectedItems))}`;
                } else {
                    alert('Please select at least one item to proceed to checkout.');
                }
            }
        </script>

    </div>
@endsection
