@if(session('pesan'))
    <div class="alert alert-success">
        {{ session('pesan') }}
    </div>
@endif

<a href="{{route('kritikRT.create')}}">Tambah Data Kritik RT</a>
<h4>Data Kritik RT</h4>
<table>
    <thead>
        <tr>
            <th>Nama RT</th>
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
                     {{RTModel::find($kritik -> id_rt) -> nama_rt}}
                </td>
                <td>
                    {{User::find($kritik -> id_user) -> name}}
                <td>
                    {{$kritik -> isi}}
                </td>
                <td>
                    {{$kritik -> status}}
                <td>
                    <form action="{{route('kritikRT.destroy', $kritik -> id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data RT ini?')">Hapus Kritk RT</button>
                    </form>
                </td>
                <td>
                    <a href="{{route('kritikRT.edit', $kritik -> id)}}">Edit data Kritk RT</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>