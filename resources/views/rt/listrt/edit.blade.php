<form action="{{route('RW.update', $rt -> id)}}" method="post">
    @csrf
    @method('PATCH')

    <input type="text" name="nama_rt" id="" value="{{$rt -> nama_rt}}">
    @error('nama_rw')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <input type="number" name="nomor_rekening" id="" value="{{$rt -> nomor_rekening}}">
    @error('nomor_rekening')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Ubah Data RT</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>