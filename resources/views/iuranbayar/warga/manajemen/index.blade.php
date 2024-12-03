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
            use App\Models\Warga;
            use App\Models\RTModel;
            use App\Models\IuranRT;
            use App\Models\User;
            //$nama_warga = Warga::where('id_warga', Auth::user() -> id_warga)->first()->nama;
        @endphp
        @foreach ($detail_iuran as $detail)
            <tr>
                <td>
                    {{Warga::where('id_warga', User::where('id', $detail->id_pengguna)->first()->id_warga)->first()->nama}}
                </td>
                <td>
                    {{$detail -> nomor_rekening}}
                </td>
                <td>
                    {{$detail -> created_at}}
                </td>
                <td>
                    <!-- Rp {{ number_format($detail->total, 0, ',', '.') }} -->
                     {{IuranRT::where('id', $detail->id_iuran_rt)->first()->total_iuran}}
                </td>
                <td>
                    {{IuranRT::where('id', $detail->id_iuran_rt)->first()->jenis_iuran}}
                    {{IuranRT::where('id', $detail->id_iuran_rt)->first()->bulan}}
                </td>
                <td>
                    <form action="{{route('manajemen-detail-iuran-rt-pengguna.selesai', $detail -> id)}}" method="post">
                        @csrf
                        @method('patch')

                        <button type="submit">Selesai</button>
                    </form>

                    <form action="{{route('manajemen-detail-iuran-rt-pengguna.gagal', $detail -> id)}}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="alert('Ubah gagal akan menghapus record, apakah anda yakin')">Gagal</button>
                    </form>
                </td>
                <td>
                    {{$detail -> status}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>