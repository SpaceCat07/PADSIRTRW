<form action="{{route('warga.store')}}" method="post">
    @csrf
    <input type="number" name="nik" id="" placeholder="NIK">
    @error('nik')
        <div><p>{{$message}}</p></div>
    @enderror
    <input type="text" name="nama" id="" placeholder="Nama">
    @error('nama')
        <div><p>{{$message}}</p></div>
    @enderror
    <input type="text" name="alamat" id="" placeholder="Alamat">
    @error('alamat')
        <div><p>{{$message}}</p></div>
    @enderror
    <button type="submit">Register</button>
</form>