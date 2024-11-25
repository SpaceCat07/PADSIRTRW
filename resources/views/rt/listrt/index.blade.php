@if(session('pesan'))
    <div class="alert alert-success">
        {{ session('pesan') }}
    </div>
@endif

<a href="{{route('RT.create')}}">Tambah Data RT</a>
<h4>Data RT</h4>
<table>
    <thead>
        <tr>
            <th>Nama RT</th>
            <th>Nama RW</th>
            <th>Nomer Rekening</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($listrt as $rt)
            <tr>
                <td>
                    {{$rt -> nama_rt}}
                </td>
                <td>
                    {{DB::table('rw') -> where('id', $rt -> id_rw) -> first() -> nama_rw}}
                </td>
                <td>
                    {{$rt -> nomor_rekening}}
                </td>
                <td>
                    <form action="{{route('RT.destroy', $rt -> id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data RT ini?')">Hapus RT</button>
                    </form>
                </td>
                <td>
                    <a href="{{route('RT.edit', $rt -> id)}}">Edit data RT</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>