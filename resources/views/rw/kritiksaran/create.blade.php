<form action="{{route('kritikRW.store')}}" method="post">
    @csrf

    <select name="id_rw" id="">
        @foreach ($listRW as $RW)
        <option value="{{ $Rw }}">{{'RW ' . $Rw }}</option>
        @endforeach
    </select>
    <!-- <input type="text" name="nama_rt" id=""> -->
    @error('id_rw')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <input type="text" name="isi" id="">
    @error('isi')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Tambah Data RT</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>