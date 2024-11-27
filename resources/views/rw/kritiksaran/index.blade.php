@if(session('pesan'))
    <div class="alert alert-success">
        {{ session('pesan') }}
    </div>
@endif

<a href="{{route('kritikRW.create')}}">Tambah Data Kritik RW</a>
<h4>Data Kritik RW</h4>
<table>
    <thead>
        <tr>
            <th>Nama RW</th>
            <th>Nama Pengguna</th>
            <th>Isi</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kritikRT as $kritik)
            <tr>
                <td>
                    <!-- {{DB::table('rw') -> where('id', $rt -> id_rw) -> first() -> nama_rw}} -->
                     {{RWModel::find($kritik -> id_rw) -> nama_rw}}
                </td>
                <td>
                    {{DB::table('warga') -> where('id', $kritik -> id_warga) -> first() -> nama}}
                <td>
                    {{$kritik -> isi}}
                </td>
                <td>
                    {{$kritik -> status}}
                <td>
                    <form action="{{route('kritikRW.destroy', $kritik -> id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data Kritik RW ini?')">Hapus Kritk RT</button>
                    </form>
                </td>
                <td>
                    <a href="{{route('kritikRW.edit', $kritik -> id)}}">Edit data Kritk RT</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>