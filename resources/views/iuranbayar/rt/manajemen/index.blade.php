<table>
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
                    {{IuranRW::where('id', $detail->id_iuran_rw)->first()->jenis_iuran}}
                    {{IuranRW::where('id', $detail->id_iuran_rw)->first()->bulan}}  
                </td>
                <td>
                    <form action="{{route('manajemen-detail-iuran-rw-rt.selesai', $detail -> id)}}" method="post">
                        @csrf
                        @method('patch')

                        <button type="submit">Selesai</button>
                    </form>

                    <form action="{{route('manajemen-detail-iuran-rw-rt.gagal', $detail -> id)}}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="confirm('Ubah gagal akan menghapus record, apakah anda yakin')">Gagal</button>
                    </form>
                </td>
                <td>
                    {{$detail -> status}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>