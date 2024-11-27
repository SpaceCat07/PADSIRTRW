@if(session('pesan'))
    <div class="alert alert-success">
        {{ session('pesan') }}
    </div>
@endif

<a href="{{route('iuranRT.create')}}">Tambah Data Iuran RT</a>
<h4>Iuran Tambahan</h4>
<table>
    <thead>
        <tr>
            <th>Nama Iuran</th>
            <th>Bulan</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($iuranTambahan as $tambahan)
            <tr>
                <td>
                    {{$tambahan -> nama_iuran}}
                </td>
                <td>
                    {{$tambahan -> bulan}}
                </td>
                <td>
                    {{$tambahan -> total_iuran}}
                </td>
                <td>
                    <form action="{{route('iuranRT.destroy', $tambahan -> id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data Iuran ini?')">Hapus Iuran RT</button>
                    </form>
                </td>
                <td>
                    <a href="{{route('iuranRT.edit', $tambahan -> id)}}">Edit data Iuran Tambahan</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<h4>Iuran Bulanan</h4>
<table>
    <thead>
        <tr>
            <th>Nama Iuran</th>
            <th>Bulan</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($iuranBulanan as $bulanan)
            <tr>
                <td>{{$iuranBulanan -> nama_iuran}}</td>
                <td>{{$iuranBulanan -> bulan}}</td>
                <td>{{$iuranBulanan -> total_iuran}}</td>
                <td>
                    <form action="{{route('iuranRT.destroy', $bulanan -> id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data Iuran ini?')">Hapus Iuran RT</button>
                    </form>
                </td>
                <td>
                    <a href="{{route('iuranRT.edit', $tambahan -> id)}}">Edit data Iuran Tambahan</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>