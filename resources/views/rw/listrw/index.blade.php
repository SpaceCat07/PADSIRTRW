@if(session('pesan'))
    <div class="alert alert-success">
        {{ session('pesan') }}
    </div>
@endif

<a href="{{route('RW.create')}}">Tambah Data RW</a>
<h4>Data RW</h4>
<table>
    <thead>
        <tr>
            <th>Nama RW</th>
            <th>Nomer Rekening</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($listrw as $rw)
            <tr>
                <td>
                    {{$rw -> nama_rw}}
                </td>
                <td>
                    {{$rw -> nomer_rekening}}
                </td>
                <td>
                    <form action="{{route('RW.destroy', $rw -> id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data RW ini?')">Hapus RW</button>
                    </form>
                </td>
                <td>
                    <a href="{{route('RW.edit', $rw -> id)}}">Edit data RW</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>