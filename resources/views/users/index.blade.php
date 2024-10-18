@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<a href="{{route('account.create')}}">Tambah Data Akun</a>
<table>
    <thead>
        <tr>
            <th>NIK</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Role</th>
            <th>Aktivasi Akun</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{$user -> id_warga}}</td>
                <td>{{$user -> email}}</td>
                <td>{{$user -> no_hp}}</td>
                <td>{{$user -> role}}</td>
                <td>{{$user -> aktivasi}}</td>
                <td>
                    <form action="{{route('account.destroy', $user -> id)}}" method="post">
                        @csrf
                        @method("DELETE")
                        <button type="submit" onclick="return confirm('Apakah anda yakin menghapus data akun ini?')">Hapus Akun</button>
                    </form>
                </td>
                <td>
                    <a href="{{route('account.edit', $user -> id)}}">Edit Data Warga</a>
                </td>
            </tr>
        @endforeach
        <tr></tr>
    </tbody>
</table>