<form action="{{route('RT.Keuangan.store')}}" method="post" enctype="multipart/form-data">
    @csrf

    <select name="jenis" id="">
        @foreach ($jenisList as $jenis)
        <option value="{{ $jenis }}">{{ $jenis }}</option>
        @endforeach
    </select>
    @error('jenis')
    <div class="alert alert-danger mt-2">
        {{$message}}
    </div>
    @enderror


    <input type="number" name="jumlah" id="">
    @error('jumlah')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror


    <input type="date" name="tanggal" id="">
    @error('tanggal')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <input type="text" name="keterangan" id="">
    @error('keterangan')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror


    <input type="file" name="path_file" id="">
    @error('path_file')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Simpan Data Laporan Keuangan RT</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>