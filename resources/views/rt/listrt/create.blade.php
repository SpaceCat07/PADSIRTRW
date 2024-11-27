<form action="{{route('RT.store')}}" method="post">
    @csrf

    <input type="text" name="nama_rt" id="">
    @error('nama_rt')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <input type="number" name="nomor_rekening" id="">
    @error('nomer_rekening')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Tambah Data RT</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>