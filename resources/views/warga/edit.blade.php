<form action="{{route('warga.update', $warga -> id_warga)}}" method="post">
    @csrf
    @method('PATCH')
    <input type="number" name="nik" id="" placeholder="NIK" value="{{$warga -> id_warga}}">
    @error('nik')
        <div><p>{{$message}}</p></div>
    @enderror
    <input type="text" name="nama" id="" placeholder="Nama" value="{{$warga -> nama}}">
    @error('nama')
        <div><p>{{$message}}</p></div>
    @enderror
    <input type="text" name="alamat" id="" placeholder="Alamat" value="{{$warga -> alamat}}">
    @error('alamat')
        <div><p>{{$message}}</p></div>
    @enderror
    <button type="submit">Update</button>
</form>
<a href="{{'/warga'}}">Kembali</a>