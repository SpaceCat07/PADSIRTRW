@if(session('pesan'))
    <div class="alert alert-success">
        {{ session('pesan') }}
    </div>
@endif

<a href="{{route('RT.Keuangan.create')}}">Tambah Laporan RT</a>
<h4>Data Laporan Keuangan RT</h4>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama RT</th>
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
                    {{DB::table('rt') -> where('id', $keuangan -> id_rt) -> first() -> nama_rt}}
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
                     <!-- <img src="{{route('RT.Keuangan.show', $keuangan -> id)}}" alt=""> -->
                      <a href="{{route('RT.Keuangan.show', $keuangan -> id)}}">Download Laporan</a>
                </td>
                <td>
                    <form action="{{route('RT.Keuangan.destroy', $keuangan -> id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data laporan Keuangan RT ini?')">Hapus Laporan Keuangan RT</button>
                    </form>
                </td>
                <td>
                    <a href="{{route('RT.Keuangan.edit', $keuangan -> id)}}">Edit data Laporan Keuangan RW</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>