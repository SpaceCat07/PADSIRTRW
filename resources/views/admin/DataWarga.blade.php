@extends('layouts.adminSidebar')

@section('content')
    <div class="data-warga-container">
        <header class="admin-header">
            <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt=""></div>
            <h1>Data Warga</h1>
        </header>
    </div>

    <div class="laporan-container">
        <!-- Search and filter section -->
        <div class="search-filter">
            <input type="text" placeholder="Search...">
            <div class="RT-filter-buttons">
                @if (Auth::check() && Auth::user()->level == 'RW')
                    <select class="action-btn btn-select-rt" name="RT-list" id="RT-list">
                        <option value="RT-1">RT 001</option>
                        <option value="RT-2">RT 002</option>
                        <option value="RT-3">RT 003</option>
                        {{-- @foreach ($rtList as $rt)
                    <option value="{{ $rt }}">{{ $rt }}</option>
                    @endforeach --}}
                    </select>
                @endif

                <button class="action-btn btn-date">+ Tambah Data</button>
            </div>
        </div>

        {{-- Table --}}
        <table class="data-warga-table">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>No.Hp</th>
                    <th>Email</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat Rumah</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 8; $i++)
                    <tr>
                        <td>123456789101121</td>
                        <td>John Doe</td>
                        <td>081234567890</td>
                        <td>johndoe@gmail.com</td>
                        <td>14-11-2004</td>
                        <td>Jalan Yos Sudarso, No.101</td>
                        <td>
                            <button class="edit-btn"><img src="{{ asset('storage/Edit.png') }}" alt=""></button>
                            <button class="delete-btn"><img src="{{ asset('storage/trash.png') }}" alt=""></button>
                            <button class="more-btn"><img src="{{ asset('storage/three-dots.png') }}"
                                    alt=""></button>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <div class="pagination">
            <span>8 per page</span>
            <div class="page-controls">
                <span>1 of 1 pages</span>
                <button class="prev-btn">&lt;</button>
                <button class="next-btn">&gt;</button>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar appearance
        document.addEventListener('DOMContentLoaded', () => {
            const menuIcon = document.querySelector('.toggle-sidebar-icon');
            const sidebar = document.querySelector('.admin-sidebar');

            // Event listener to toggle sidebar visibility
            menuIcon.addEventListener('click', (event) => {
                event.stopPropagation();
                sidebar.classList.toggle('active');
            });

            // Event listener to hide sidebar when clicking outside
            document.addEventListener('click', (event) => {
                if (!sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            });
        });

        // Search functionality
        document.querySelector('.search-filter input').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.data-warga-table tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let rowContainsSearchTerm = false;

                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchTerm)) {
                        rowContainsSearchTerm = true; // If any cell contains the search term
                    }
                });

                // Show or hide the row based on search term
                row.style.display = rowContainsSearchTerm ? 'table-row' : 'none';
            });
        });
    </script>
@endsection
