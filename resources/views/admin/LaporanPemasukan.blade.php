@extends('layouts.adminSidebar')

@section('content')
<title>SIMAS - Laporan Pemasukan</title>
<link rel="stylesheet" href="{{ asset('css/laporan-mutasi.css') }}">
{{-- HTML tidak berubah --}}
<div class="laporan-container">
    <header class="admin-header">
        <div class="toggle-sidebar-icon"><img src="{{ asset('storage/sidebarIcon.png') }}" alt="Toggle Sidebar"></div>
        <h1>Laporan Pemasukan</h1>
    </header>
    <div class="report-action-buttons">
        <button class="action-btn btn-report" onclick="printReport()">Cetak Laporan</button>
        <a href="{{ route('tambah-data-pemasukan') }}"><button class="action-btn btn-add-data">+ Tambah Data</button></a>
    </div>
    <div class="sort-by">
        <div class="sort-select-container">
            <label for="sort-select">Sort by:</label>
            <select id="sort-select">
                <option value="latest">Tanggal Terbaru</option>
                <option value="oldest">Tanggal Terlama</option>
            </select>
        </div>
    </div>
    <table id="incomeTable" class="expense-table">
        <thead>
            <tr>
                <th>ID</th><th>Tanggal</th><th>Nominal</th><th>Keterangan</th><th>Bukti Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="5" style="text-align: center;">Memuat data...</td></tr>
        </tbody>
    </table>
    <div class="pagination">
        <select class="items-per-page">
            <option value="5">5</option><option value="10" selected>10</option><option value="15">15</option>
        </select>
        <label>per halaman</label>
        <button id="prevPage" disabled>&laquo; Sebelumnya</button>
        <span id="pageInfo">Halaman 1 dari 1</span>
        <button id="nextPage" disabled>Berikutnya &raquo;</button>
    </div>
</div>
<div id="imageModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <img id="modalImage" src="" alt="Bukti Transaksi">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // ... (konfigurasi dan referensi elemen tidak berubah)
    const API_URL = 'https://sirtrw-api.vansite.cloud/api/mutasi';
    const tableBody = document.querySelector('#incomeTable tbody');
    const sortSelect = document.getElementById('sort-select');
    const itemsPerPageSelect = document.querySelector('.items-per-page');
    const pageInfo = document.getElementById('pageInfo');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const menuIcon = document.querySelector('.toggle-sidebar-icon');
    const sidebar = document.querySelector('.admin-sidebar');
    
    let allData = [], currentPage = 1, itemsPerPage = parseInt(itemsPerPageSelect.value, 10);

    async function fetchData() {
        const token = localStorage.getItem('token');
        let userRole = localStorage.getItem('userRole');

        if (!token || !userRole) {
            tableBody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: red;">Error: Informasi login (Token atau Role) tidak ditemukan.</td></tr>';
            return;
        }
        
        // Normalisasi role ke huruf kecil
        userRole = userRole.toLowerCase();
        
        try {
            const response = await fetch(API_URL, { headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }});
            if (!response.ok) throw new Error(`Gagal mengambil data. Status: ${response.status}`);
            
            const result = await response.json();

            if (result.success && result.data) {
                let sourceData = [];
                // ================================================================
                // === PERBAIKAN FINAL: Gunakan .includes() untuk mengecek role ===
                if (userRole.includes('rw') && Array.isArray(result.data.rw)) {
                    console.log("Role terdeteksi sebagai RW.");
                    sourceData = result.data.rw;
                } else if (userRole.includes('rt') && Array.isArray(result.data.rt)) {
                    console.log("Role terdeteksi sebagai RT.");
                    sourceData = result.data.rt;
                }
                // ================================================================

                allData = sourceData.filter(item => item.variance === 'inflow');

                if (allData.length === 0) {
                     tableBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Tidak ada data pemasukan ditemukan.</td></tr>';
                } else {
                     sortTable();
                }
            } else {
                throw new Error('Format data dari API tidak sesuai harapan.');
            }
        } catch (error) {
            console.error('Kesalahan saat fetch data:', error);
            tableBody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: red;"><strong>Gagal Memuat Data:</strong><br>${error.message}</td></tr>`;
        } finally {
            updatePaginationControls();
        }
    }

    // ... (Sisa fungsi JavaScript tidak ada perubahan)
    function renderTable(){tableBody.innerHTML="";if(allData.length===0&&currentPage===1){tableBody.innerHTML='<tr><td colspan="5" style="text-align: center;">Tidak ada data pemasukan ditemukan.</td></tr>';return}const startIndex=(currentPage-1)*itemsPerPage;const endIndex=startIndex+itemsPerPage;const pageData=allData.slice(startIndex,endIndex);pageData.forEach(item=>{const row=document.createElement('tr');const formattedValue=new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(item.value);row.innerHTML=`<td>${item.id}</td><td>${item.date}</td><td>${formattedValue}</td><td>${item.notes||"-"}</td><td><button class="btn-view" disabled>Lihat</button></td>`;tableBody.appendChild(row)})}
    function updatePaginationControls(){const totalPages=Math.ceil(allData.length/itemsPerPage);pageInfo.textContent=`Halaman ${currentPage} dari ${totalPages||1}`;prevPageBtn.disabled=currentPage===1;nextPageBtn.disabled=currentPage===totalPages||totalPages===0}
    function sortTable(){const sortOrder=sortSelect.value;allData.sort((a,b)=>{const dateA=new Date(a.date);const dateB=new Date(b.date);return sortOrder==='latest'?dateB-dateA:dateA-dateB});currentPage=1;renderTable();updatePaginationControls()}
    sortSelect.addEventListener('change',sortTable);itemsPerPageSelect.addEventListener('change',e=>{itemsPerPage=parseInt(e.target.value,10);sortTable()});prevPageBtn.addEventListener('click',()=>{if(currentPage>1){currentPage--;renderTable();updatePaginationControls()}});nextPageBtn.addEventListener('click',()=>{const totalPages=Math.ceil(allData.length/itemsPerPage);if(currentPage<totalPages){currentPage++;renderTable();updatePaginationControls()}});
    if(menuIcon&&sidebar){menuIcon.addEventListener('click',event=>{event.stopPropagation();sidebar.classList.toggle('active')});document.addEventListener('click',event=>{if(sidebar.classList.contains('active')&&!sidebar.contains(event.target)&&!menuIcon.contains(event.target)){sidebar.classList.remove('active')}})}
    window.printReport=function(){let printContent=`<html><head><title>Laporan Pemasukan</title><style>body{font-family:Arial,sans-serif}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background-color:#6A5ACD;color:#fff}h1{text-align:center}</style></head><body><h1>Laporan Pemasukan</h1><table><thead><tr><th>ID</th><th>Tanggal</th><th>Nominal</th><th>Keterangan</th></tr></thead><tbody>`;allData.forEach(item=>{const formattedValue=new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(item.value);printContent+=`<tr><td>${item.id}</td><td>${item.date}</td><td>${formattedValue}</td><td>${item.notes||"-"}</td></tr>`});printContent+=`</tbody></table></body></html>`;const printWindow=window.open('','','width=800,height=600');printWindow.document.write(printContent);printWindow.document.close();printWindow.focus();printWindow.print()}
    fetchData();
});
</script>
@endsection