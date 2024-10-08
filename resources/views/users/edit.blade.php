<form action="{{route('update')}}" method="post">
    <input type="text" name="nama" id="">
    @error('nama')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror
    <input type="email" name="email" id="">
    @error('email')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror
    <input type="tel" name="no_hp" id="">
    @error('no_hp')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Ubah data</button>

</form>