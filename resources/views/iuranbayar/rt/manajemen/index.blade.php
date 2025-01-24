@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif
<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>No. Rekening</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Bukti Pembayaran</th>
            <th>Keterangan</th>
            <th>Action</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @php
            use App\Models\IuranRW;
            use App\Models\RTModel;
        @endphp
        @foreach ($detail_iuran as $detail)
            <tr>
                <td>
                     {{RTModel::where('id', $detail->id_rt)->first()->nama_rt}}
                </td>
                <td>
                    {{$detail -> nomor_rekening}}
                </td>
                <td>
                    {{$detail -> created_at}}
                </td>
                <td>
                    <!-- Rp {{ number_format($detail->total, 0, ',', '.') }} -->
                      {{IuranRW::where('id', $detail->id_iuran_rw)->first()->total_iuran}}
                </td>
                <td>
                    <img src="{{route('manajemen-detail-rw-rt.show', $detail -> id)}}" alt="" width="100" >
                </td>
                <td>
                    {{IuranRW::where('id', $detail->id_iuran_rw)->first()->jenis_iuran}}
                    {{IuranRW::where('id', $detail->id_iuran_rw)->first()->bulan}}  
                </td>
                <td>
                    <form action="{{route('manajemen-detail-iuran-rw-rt.selesai', $detail -> id)}}" method="post">
                        @csrf
                        @method('patch')

                        <button type="submit">Selesai</button>
                    </form>

                    <a href="{{route('manajemen-detail-iuran-rw-rt.keGagal', $detail -> id)}}">Gagal</a>
                </td>
                <td>
                    {{$detail -> status}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>