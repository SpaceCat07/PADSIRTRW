<form action="{{route('kritikRT.store')}}" method="post">
    @csrf

    <select name="id_rt" id="">
        @foreach ($listRT as $RT)
        <option value="{{ $RT }}">{{'RT ' . $RT }}</option>
        @endforeach
    </select>
    <!-- <input type="text" name="nama_rt" id=""> -->
    @error('id_rt')
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