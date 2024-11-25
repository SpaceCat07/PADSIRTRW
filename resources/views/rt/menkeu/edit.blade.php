<form action="{{route('RT.Keuangan.update', $laporan -> id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    
    <select name="jenis" id="">
        @foreach ($jenisList as $jenis)
        <option value="{{ $jenis }}" {{$laporan -> jenis === $jenis ? 'selected' : ''}}>{{ $jenis }}</option>
        @endforeach
    </select>
    @error('jenis')
    <div class="alert alert-danger mt-2">
        {{$message}}
    </div>
    @enderror


    <input type="number" name="jumlah" id="" value="{{$laporan -> jumlah}}">
    @error('jumlah')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror


    <input type="date" name="tanggal" id="" value="{{$laporan -> tanggal}}">
    @error('tanggal')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <input type="text" name="keterangan" id="" value="{{$laporan -> keterangan}}">
    @error('keterangan')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <div>
        <label for="path_file">Laporan Keuangan RT:</label>
        @if($laporan->path_file)
            <!-- Menampilkan gambar yang ada di storage -->
            <!-- <img src="{{route('RT.Keuangan.show', $laporan -> id)}}" alt="Gambar Laporan Keuangan" width="150"> -->
            <a href="{{route('RT.Keuangan.show', $laporan -> id)}}">Download Laporan</a>
        @else
            <p>Tidak ada gambar yang tersedia</p>
        @endif
        <br>
        <!-- Input file untuk mengganti gambar -->
        <input type="file" name="path_file" id="">
    </div>
    @error('path_file')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Ubah Data Laporan Keuangan RT</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>