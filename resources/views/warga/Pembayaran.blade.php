@extends('layouts.dashboardNavbar')

@section('content')
    <?php
    $page = 'pembayaran'; // or 'program-kerja', 'pembayaran', etc.
    $status = 'Belum Lunas'; // Define a default status for testing
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
                <tbody>
                    <tr>
                        <td colspan="4" class="no-data">Not Found</td>
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

            function populateYearPicker() {
                const yearPicker = document.getElementById('year-picker');
                const currentYear = new Date().getFullYear();
                const startYear = 2000; // Change this to the earliest year you want to display

                for (let year = currentYear; year >= startYear; year--) {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    yearPicker.appendChild(option);
                }
            }

            // Call the function to populate the year picker when the page loads
            populateYearPicker();

            // Optional: handle selection
            yearPicker.addEventListener('change', function() {
                const selectedYear = this.value;
                alert("Tahun yang dipilih: " + selectedYear);
                // Further action can be added here based on the selected year
            });
        </script>

    </div>
@endsection
