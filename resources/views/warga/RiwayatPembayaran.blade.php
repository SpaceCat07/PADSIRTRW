@extends('layouts.dashboardNavbar')

@section('content')
    <?php
    $page = 'pembayaran'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    <script src="{{ asset('js/pagination.js') }}"></script>

    <div class="payment-history-container">
        <h2>Riwayat Pembayaran</h2>

        <div class="filters">
            <button class="filter-button active" data-filter="all">All</button>
            <button class="filter-button" data-filter="success">Complete</button>
            <button class="filter-button" data-filter="pending">Pending</button>
            <button class="filter-button" data-filter="rejected">Rejected</button>
            <button class="date-picker">Pilih Tanggal</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Pembayaran</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="payment-table">
                <tr data-status="success">
                    <td>#16643</td>
                    <td>28 October, 2024</td>
                    <td>5000</td>
                    <td class="status-success">Success</td>
                </tr>
                <tr data-status="rejected">
                    <td>#13568</td>
                    <td>15 September, 2024</td>
                    <td>5000</td>
                    <td class="status-rejected">Rejected</td>
                </tr>
                <tr data-status="pending">
                    <td>#12345</td>
                    <td>29 Agustus, 2024</td>
                    <td>5000</td>
                    <td class="status-pending">Pending</td>
                </tr>
                <!-- Additional rows here -->
            </tbody>
        </table>

        <div class="pagination-container">
            <div class="items-per-page">
                <select>
                    <option value="6">6</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
                <span>per page</span>
            </div>

            <div class="pagination">
                <button disabled>&lt;</button>
                <span>1</span>
                <span>of 1 pages</span>
                <button disabled>&gt;</button>
            </div>
        </div>
    </div>

    <script>
        // Handle filter buttons
        document.querySelectorAll('.filter-button').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons and add to the clicked one
                document.querySelectorAll('.filter-button').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Get filter type from data-filter attribute
                const filter = button.getAttribute('data-filter');
                
                // Filter table rows
                filterTable(filter);
            });
        });

        // Filter function to show/hide rows based on status
        function filterTable(status) {
            const rows = document.querySelectorAll('#payment-table tr');

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                
                if (status === 'all' || rowStatus === status) {
                    row.style.display = ''; // Show row
                } else {
                    row.style.display = 'none'; // Hide row
                }
            });
        }
    </script>
@endsection
