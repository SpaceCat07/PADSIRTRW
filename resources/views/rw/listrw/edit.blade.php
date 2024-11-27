<form action="{{route('RW.update', $rw -> id)}}" method="post">
    @csrf
    @method('PATCH')

    <input type="text" name="nama_rw" id="" value="{{$rw -> nama_rw}}">
    @error('nama_rw')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <input type="number" name="nomer_rekening" id="" value="{{$rw -> nomer_rekening}}">
    @error('nomer_rekening')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Ubah Data RW</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>