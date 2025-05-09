@extends('layouts.dashboardNavbar')

@section('content')
<title>SIMAS - riwayat</title>
<link rel="stylesheet" href="{{ asset('css/pembayaran.css') }}">
    <?php
    $page = 'pembayaran'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>

    <div class="payment-history-container">
        <h2>Riwayat Pembayaran</h2>

        <div class="status-filters">
            <button class="filter-button active" data-filter="all">All</button>
            <button class="filter-button" data-filter="success">Complete</button>
            <button class="filter-button" data-filter="pending">Pending</button>
            <button class="filter-button" data-filter="rejected">Rejected</button>
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
                <tr>
                    <td colspan="4">Loading...</td>
                </tr>
            </tbody>
        </table>

        <div class="pagination-container">
            <div class="items-per-page">
                <select id="itemsPerPageSelect">
                    <option value="6">6</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
                <span>per page</span>
            </div>

            <div class="pagination">
                <button id="prevPageBtn" disabled><</button>
                <span id="currentPage">1</span>
                <span>of <span id="totalPages">1</span> pages</span>
                <button id="nextPageBtn" disabled>></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const paymentTable = document.getElementById('payment-table');
            const filterButtons = document.querySelectorAll('.filter-button');
            const itemsPerPageSelect = document.getElementById('itemsPerPageSelect');
            const prevPageBtn = document.getElementById('prevPageBtn');
            const nextPageBtn = document.getElementById('nextPageBtn');
            const currentPageSpan = document.getElementById('currentPage');
            const totalPagesSpan = document.getElementById('totalPages');

            let payments = [];
            let filteredPayments = [];
            let currentPage = 1;
            let itemsPerPage = parseInt(itemsPerPageSelect.value);

            function fetchPayments() {
                axios.get('/api/payment-history')
                    .then(response => {
                        payments = response.data;
                        filteredPayments = payments;
                        currentPage = 1;
                        renderTable();
                        updatePagination();
                    })
                    .catch(error => {
                        console.error('Error fetching payment history:', error);
                        paymentTable.innerHTML = '<tr><td colspan="4">Failed to load data.</td></tr>';
                    });
            }

            function renderTable() {
                paymentTable.innerHTML = '';
                if (filteredPayments.length === 0) {
                    paymentTable.innerHTML = '<tr><td colspan="4">No payment history found.</td></tr>';
                    return;
                }
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const pageItems = filteredPayments.slice(start, end);

                pageItems.forEach(payment => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-status', payment.status.toLowerCase());
                    row.innerHTML = `
                        <td>#${payment.id}</td>
                        <td>${payment.date}</td>
                        <td>${payment.total}</td>
                        <td class="status-${payment.status.toLowerCase()}">${payment.status}</td>
                    `;
                    paymentTable.appendChild(row);
                });
            }

            function updatePagination() {
                const totalPages = Math.ceil(filteredPayments.length / itemsPerPage);
                totalPagesSpan.textContent = totalPages;
                currentPageSpan.textContent = currentPage;
                prevPageBtn.disabled = currentPage === 1;
                nextPageBtn.disabled = currentPage === totalPages || totalPages === 0;
            }

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    const filter = button.getAttribute('data-filter');
                    if (filter === 'all') {
                        filteredPayments = payments;
                    } else {
                        filteredPayments = payments.filter(p => p.status.toLowerCase() === filter);
                    }
                    currentPage = 1;
                    renderTable();
                    updatePagination();
                });
            });

            prevPageBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                    updatePagination();
                }
            });

            nextPageBtn.addEventListener('click', () => {
                const totalPages = Math.ceil(filteredPayments.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                    updatePagination();
                }
            });

            itemsPerPageSelect.addEventListener('change', () => {
                itemsPerPage = parseInt(itemsPerPageSelect.value);
                currentPage = 1;
                renderTable();
                updatePagination();
            });

            fetchPayments();
        });
    </script>
@endsection
