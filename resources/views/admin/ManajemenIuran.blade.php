@extends('layouts.adminSidebar')

@section('content')
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt=""></div>
            <h1>Manajemen Iuran</h1>
        </header>
    </div>

    <div class="laporan-container">
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


        <!-- Data Table -->
        <table class="data-warga-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>No. Rekening</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 6; $i++)
                    <tr>
                        <td>John Doe</td>
                        <td>51868723034</td>
                        <td>14/11/2024</td>
                        <td>Rp 10.000</td>
                        <td>Bulan Mei, Bulan Juni</td>
                        <td>
                            <div class="iuran-action-buttons">
                                <button class="status-btn yes" onclick="handleStatusChange(this, 'yes')">YES</button>
                                <button class="status-btn no" onclick="handleStatusChange(this, 'no')">NO</button>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-pending">Pending</span>
                        </td>                        
                    </tr>
                @endfor
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <div class="pagination-info">
                <span>6 per page</span>
            </div>
            <div class="pagination-controls">
                <button class="prev-btn">&lt;</button>
                <span>1 of 1 pages</span>
                <button class="next-btn">&gt;</button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Sidebar toggle functionality
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

        function handleStatusChange(button, action) {
            const parent = button.closest('.iuran-action-buttons');
            const row = button.closest('tr');
            const statusCell = row.querySelector('.badge');

            // Handle YES button click
            if (action === 'yes') {
                const confirmYes = confirm("Are you sure you want to mark this as Paid?");
                if (confirmYes) {
                    parent.innerHTML = `<button class="status-btn paid" onclick="resetStatus(this)">Paid</button>`;
                    statusCell.className = 'badge badge-paid';
                    statusCell.textContent = 'Paid';
                }
            }

            // Handle NO button click
            else if (action === 'no') {
                const confirmNo = confirm("Are you sure you want to mark this as Rejected?");
                if (confirmNo) {
                    parent.innerHTML = `<button class="status-btn rejected" onclick="resetStatus(this)">Rejected</button>`;
                    statusCell.className = 'badge badge-rejected';
                    statusCell.textContent = 'Rejected';
                }
            }
        }

        // Reset button logic
        function resetStatus(button) {
            const confirmReset = confirm("Do you want to reset this status?");
            if (confirmReset) {
                const parent = button.closest('.iuran-action-buttons');
                const row = button.closest('tr');
                const statusCell = row.querySelector('.badge');

                parent.innerHTML = `
                <button class="status-btn yes" onclick="handleStatusChange(this, 'yes')">YES</button>
                <button class="status-btn no" onclick="handleStatusChange(this, 'no')">NO</button>
            `;
                statusCell.className = 'badge badge-pending';
                statusCell.textContent = 'Pending';
            }
        }
    </script>
@endsection
