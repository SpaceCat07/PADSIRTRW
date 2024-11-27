<form action="{{route('proker.store')}}" method="post" enctype ='multipart/form-data'>
    @csrf

    <input type="text" name="judul">
    @error('judul')
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


    <input type="time" name="waktu" id="">
    @error('waktu')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <input type="date" name="tanggal_pelaksanaan" id="">
    @error('tanggal_pelaksanaan')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <input type="text" name="lokasi">
    @error('lokasi')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror


    <input type="file" name="gambar" id="">
    @error('gambar')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <select name="status" id="">
        @foreach ($statusList as $status)
        <option value="{{ $status }}">{{ $status }}</option>
        @endforeach
    </select>
    @error('status')
    <div class="alert alert-danger mt-2">
        {{$message}}
    </div>
    @enderror

    <button type="submit">Simpan Data Program Kerja</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>