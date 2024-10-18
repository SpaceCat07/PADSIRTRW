<a href="{{route('warga.create')}}">Tambah data warga</a>
<table>
    <thead>
        <tr>
            <!-- <th>No</th> -->
            <th>NIK</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($warga as $warg)
            <tr>
                <td>{{$warg -> id_warga}}</td>
                <td>{{$warg -> nama}}</td>
                <td>{{$warg -> alamt}}</td>
                <td>
                    <a href="{{route('warga.edit', $warg -> id_warga)}}">Ubah Data Warga</a>
                </td>
                <td>
                    <form action="{{route('warga.destroy', $warg -> id_warga)}}" method="post">
                        @csrf
                        @method("DELETE")
                        <button onclick="return confirm('Apakah anda yakin menghapus data warga ini?')" type="submit">Hapus Data Warga</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>