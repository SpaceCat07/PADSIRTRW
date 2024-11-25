@if(session('pesan'))
    <div class="alert alert-success">
        {{ session('pesan') }}
    </div>
@endif

<a href="{{route('RW.Keuangan.create')}}">Tambah Laporan RW</a>
<h4>Data Laporan Keuangan RW</h4>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama RW</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
            <th>Tanggal</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($listKeu as $keuangan)
            <tr>
                <td>
                    {{$keuangan -> id}}
                </td>
                <td>
                    {{DB::table('rw') -> where('id_rw', $keuangan -> id_rw) -> first()}}
                </td>
                <td>
                    {{$keuangan -> jenis}}
                </td>
                <td>
                    {{$keuangan -> jumlah}}
                </td>
                <td>
                    {{$keuangan -> keterangan}}
                </td>
                <td>
                    {{$keuangan -> tanggal}}
                </td>
                <td>
                    <!-- <img src="{{$keuangan -> path_file}}" alt=""> -->
                     <!-- <img src="{{route('RW.Keuangan.lihatgambar', $keuangan -> path_file)}}" alt=""> -->
                     <a href="{{route('RW.Keuangan.show', $keuangan -> id)}}">Download Laporan</a>
                </td>
                <td>
                    <form action="{{route('RW.Keuangan.destroy', $keuangan -> id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data laporan Keuangan RW ini?')">Hapus RT</button>
                    </form>
                </td>
                <td>
                    <a href="{{route('RW.Keuangan.edit', $keuangan -> id)}}">Edit data Laporan Keuangan RW</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>