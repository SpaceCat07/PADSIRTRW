<form action="{{route('RW.store')}}" method="post">
    @csrf

    <input type="text" name="nama_rw" id="">
    @error('nama_rw')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <input type="number" name="nomer_rekening" id="">
    @error('nomer_rekening')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Tambah Data RW</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>