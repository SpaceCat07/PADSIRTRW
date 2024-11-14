@extends('layouts.dashboardNavbar')

@section('content')

<?php
$page = 'dashboard';
?>

<div class="financial-report mt-5 text-center">
    <h1 class="h1 mb-4">Laporan Keuangan</h1>

    <!-- Button Group for Filters -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="btn-group" role="group" aria-label="Filter buttons">
            <button type="button" class="btn-filter active" id="btn-pengeluaran" onclick="showPengeluaran()">
                Pengeluaran
            </button>
            <button type="button" class="btn-filter" id="btn-pemasukan" onclick="showPemasukan()">
                Pemasukan
            </button>
        </div>

        <button class="btn btn-select-month" id="btn-pilih-bulan" onclick="selectMonth()">
            Pilih Bulan
        </button>
    </div>

    <!-- Financial Report Table -->
    <div class="table-responsive">
        <table class="table financial-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Nominal</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Content will be inserted dynamically -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container d-flex justify-content-between align-items-center mt-3">
        <div class="d-flex align-items-center">
            <select class="pagination-select">
                <option>6</option>
                <option>10</option>
                <option>20</option>
            </select>
            <span>per page</span>
        </div>
        <div>
            <span>1 of 1 pages</span>
            <button class="btn-pagination">&#9664;</button>
            <button class="btn-pagination">&#9654;</button>
        </div>
    </div>
</div>

<script>
    const pengeluaranData = [
        { id: 1, tanggal: '2024-10-23', nominal: 500000, keterangan: 'Pembelian alat tulis' },
        { id: 2, tanggal: '2024-10-20', nominal: 200000, keterangan: 'Pembelian makanan' }
    ];

    const pemasukanData = [
        { id: 1, tanggal: '2024-10-18', nominal: 1000000, keterangan: 'Iuran warga' },
        { id: 2, tanggal: '2024-10-15', nominal: 500000, keterangan: 'Donasi' }
    ];

    function showPengeluaran() {
        populateTable(pengeluaranData);
        document.getElementById('btn-pengeluaran').classList.add('active');
        document.getElementById('btn-pemasukan').classList.remove('active');
    }

    function showPemasukan() {
        populateTable(pemasukanData);
        document.getElementById('btn-pemasukan').classList.add('active');
        document.getElementById('btn-pengeluaran').classList.remove('active');
    }

    function populateTable(data) {
        const tableBody = document.getElementById('table-body');
        tableBody.innerHTML = '';
        data.forEach((item) => {
            const row = `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.tanggal}</td>
                    <td>${item.nominal.toLocaleString('id-ID')}</td>
                    <td>${item.keterangan}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    showPengeluaran();

    function selectMonth() {
        alert('Pilih bulan');
    }
</script>

@endsection