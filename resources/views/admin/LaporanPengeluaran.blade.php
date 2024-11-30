@extends('layouts.adminSidebar')

@section('content')
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt=""></div>
            <h1>Laporan Pengeluaran</h1>
        </header>
    </div>

    <div class="laporan-container">
        {{-- Action Buttons --}}
        <div class="report-action-buttons">
            <button class="action-btn btn-report" onclick="printReport()">Cetak Laporan</button>
            <a href="{{ route('tambah-data-pengeluaran') }}"><button class="action-btn btn-add-data">+ Tambah
                    Data</button></a>
        </div>

        {{-- Sort Dropdown --}}
        <div class="sort-by">
            <button class="action-btn btn-date">Pilih Tanggal</button>
            <div class="sort-select-container">
                <label for="sort-select">Sort by:</label>
                <select id="sort-select">
                    <option value="latest">Latest update</option>
                    <option value="oldest">Oldest update</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <table id="expenseTable" class="expense-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Nominal</th>
                    <th>Keterangan</th>
                    <th>Bukti Transaksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Dummy data --}}
                <tr data-status="unread">
                    <td>001</td>
                    <td>2024-11-10</td>
                    <td>Rp 500,000</td>
                    <td>Pembelian alat tulis</td>
                    <td><button class="btn-view">Lihat</button></td>
                </tr>
                <tr data-status="unread">
                    <td>002</td>
                    <td>2024-11-11</td>
                    <td>Rp 1,200,000</td>
                    <td>Biaya perbaikan</td>
                    <td><button class="btn-view">Lihat</button></td>
                </tr>
                <tr data-status="read">
                    <td>003</td>
                    <td>2024-11-12</td>
                    <td>Rp 300,000</td>
                    <td>Transportasi</td>
                    <td><button class="btn-view">Lihat</button></td>
                </tr>
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="pagination">
            <select class="items-per-page">
                <option value="3">3</option>
                <option value="5">5</option>
                <option value="10">10</option>
            </select>
            per page
            <span>1 of 1 pages</span>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        // Toggle sidebar appearance
        document.addEventListener('DOMContentLoaded', () => {
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');

            menuIcon.addEventListener('click', (event) => {
                event.stopPropagation();
                sidebar.classList.toggle('active');
            });

            document.addEventListener('click', (event) => {
                if (!sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            });
        });

        // Sort Table Function
        function sortTable() {
            const table = document.getElementById('expenseTable');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const sortSelect = document.getElementById('sort-select');
            const sortOrder = sortSelect.value;

            // Check if there are rows to sort
            if (rows.length === 0) return;

            // Sort rows based on the selected order
            rows.sort((a, b) => {
                const dateA = new Date(a.cells[1].textContent);
                const dateB = new Date(b.cells[1].textContent);

                return sortOrder === 'latest' ? dateB - dateA : dateA - dateB;
            });

            // Clear the table body and append sorted rows
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        }

        // Add event listener for the sort select
        document.getElementById('sort-select').addEventListener('change', sortTable);


        // Print Report Function
        function printReport() {
            const table = document.getElementById('expenseTable');
            const rows = table.querySelectorAll('tbody tr');

            // Start HTML for the print view
            let printContent = `
                <html>
                <head>
                    <title>Laporan Pengeluaran</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #6A5ACD; color: white; }
                    </style>
                </head>
                <body>
                    <h1>Laporan Pengeluaran</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>`;

            // Loop through table rows and extract data for the print content
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const id = cells[0].textContent;
                const tanggal = cells[1].textContent;
                const nominal = cells[2].textContent;
                const keterangan = cells[3].textContent;

                printContent += `
                    <tr>
                        <td>${id}</td>
                        <td>${tanggal}</td>
                        <td>${nominal}</td>
                        <td>${keterangan}</td>
                    </tr>`;
            });

            // Close the table and HTML tags for the print view
            printContent += `
                        </tbody>
                    </table>
                </body>
                </html>`;

            // Open the print window and write content
            const printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endsection
