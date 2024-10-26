@extends('layouts.dashboardNavbar')

@section('content')

<?php
$page = 'dashboard'; // or 'program-kerja', 'pembayaran', etc.
?>

<div class="financial-report mt-5">
    <h1 class="text-center mb-4">Laporan Keuangan</h1>

    <!-- Button Group for Filters -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="btn-group" role="group" aria-label="Filter buttons">
            <button type="button" class="btn btn-outline-primary" id="btn-pengeluaran" onclick="showPengeluaran()">
                Pengeluaran
            </button>
            <button type="button" class="btn btn-outline-success" id="btn-pemasukan" onclick="showPemasukan()">
                Pemasukan
            </button>
        </div>

        <button class="btn btn-secondary" id="btn-pilih-bulan" onclick="selectMonth()">
            Pilih Bulan
        </button>
    </div>

    <!-- Financial Report Table -->
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered align-middle" id="financial-table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">ID</th>
                    <th scope="col">User</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Nominal (Rp)</th>
                    <th scope="col">Keterangan</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Content will be inserted dynamically -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <button class="btn btn-outline-primary pagination-button" onclick="prevPage()">
            &#9664; Previous
        </button>
        <span class="pagination-info">Page 1</span>
        <button class="btn btn-outline-primary pagination-button" onclick="nextPage()">
            Next &#9654;
        </button>
    </div>
</div>

<script>
    // Dummy data for Pengeluaran and Pemasukan
    const pengeluaranData = [
        { id: 1, user: 'Udin', tanggal: '2024-10-23', nominal: 500000, keterangan: 'Pembelian alat tulis' },
        { id: 2, user: 'Siti', tanggal: '2024-10-20', nominal: 200000, keterangan: 'Pembelian makanan' }
    ];

    const pemasukanData = [
        { id: 1, user: 'Bu Ani', tanggal: '2024-10-18', nominal: 1000000, keterangan: 'Iuran warga' },
        { id: 2, user: 'Andi', tanggal: '2024-10-15', nominal: 500000, keterangan: 'Donasi' }
    ];

    // Function to display Pengeluaran data
    function showPengeluaran() {
        populateTable(pengeluaranData);
    }

    // Function to display Pemasukan data
    function showPemasukan() {
        populateTable(pemasukanData);
    }

    // Helper function to populate the table dynamically
    function populateTable(data) {
        const tableBody = document.getElementById('table-body');
        tableBody.innerHTML = ''; // Clear existing rows

        data.forEach((item, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.id}</td>
                    <td>${item.user}</td>
                    <td>${item.tanggal}</td>
                    <td class="text-end">${item.nominal.toLocaleString('id-ID')}</td>
                    <td>${item.keterangan}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    // Initial load: Display Pengeluaran data by default
    showPengeluaran();

    function selectMonth() {
        alert('Pilih bulan');
    }

    function prevPage() {
        alert('Halaman sebelumnya');
    }

    function nextPage() {
        alert('Halaman berikutnya');
    }
</script>

@endsection
