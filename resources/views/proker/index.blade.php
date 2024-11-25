@if (Auth::user() -> role == 'Admin_RT')
    <!-- ini untuk program kerja rw, nanti di tampilkan ketika tombol RW ditekan -->
    @if(session('pesan'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{route('proker.create')}}">Tambah Program Kerja RT anda</a>
    <h4>Data Program Kerja RT</h4>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Isi</th>
                <th>Waktu</th>
                <th>Tanggal Pelaksanaan</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($prokerRTList as $prokerRT)
                <tr>
                    <td>
                        {{$prokerRT -> id}}
                    </td>
                    <td>
                        {{$prokerRT -> judul}}
                    </td>
                    <td>
                        {{$prokerRT -> isi}}
                    </td>
                    <td>
                        {{$prokerRT -> waktu}}
                    </td>
                    <td>
                        {{$prokerRT -> tanggal_pelaksanaan}}
                    </td>
                    <td>
                        {{$prokerRT -> lokasi}}
                    </td>
                    <td>
                        {{$prokerRT -> status}}
                    </td>
                    <td>
                        <img src="{{route('proker.show', $prokerRT -> id)}}" alt="" style="width:100px">
                    </td>
                    <td>
                        <form action="{{route('proker.destroy', $prokerRT -> id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data laporan Program Kerja RT ini?')">Hapus Program Kerja RT</button>
                        </form>
                    </td>
                    <td>
                        <a href="{{route('proker.edit', $prokerRT -> id)}}">Edit data Program Kerja RT</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endif


@if (Auth::user() -> role == 'Admin_RW')
    <!-- ini untuk program kerja rt, nanti di tampilkan ketika tombol RT ditekan -->
    @if(session('pesan'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{route('proker.create')}}">Tambah Program Kerja RW anda</a>
    <h4>Data Program Kerja RW</h4>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Isi</th>
                <th>Waktu</th>
                <th>Tanggal Pelaksanaan</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($prokerRWList as $prokerRW)
                <tr>
                    <td>
                        {{$prokerRW -> id}}
                    </td>
                    <td>
                        {{$prokerRW -> judul}}
                    </td>
                    <td>
                        {{$prokerRW -> isi}}
                    </td>
                    <td>
                        {{$prokerRW -> waktu}}
                    </td>
                    <td>
                        {{$prokerRW -> tanggal_pelaksanaan}}
                    </td>
                    <td>
                        {{$prokerRW -> lokasi}}
                    </td>
                    <td>
                        {{$prokerRW -> status}}
                    </td>
                    <td>
                        <img src="{{route('proker.show', $prokerRW -> id)}}" alt="">
                    </td>
                    <td>
                        <form action="{{route('proker.destroy', $prokerRW -> id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data laporan Program Kerja RW ini?')">Hapus Program Kerja RW</button>
                        </form>
                    </td>
                    <td>
                        <a href="{{route('proker.edit', $prokerRW -> id)}}">Edit data Program Kerja RW</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endif