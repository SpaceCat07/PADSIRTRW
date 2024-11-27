<form action="{{route('proker.update', $proker -> id)}}" method="post" enctype ='multipart/form-data'>
    @csrf
    @method('PUT')

    <input type="text" name="judul" value="{{$proker -> judul}}">
    @error('judul')
    <div class="alert alert-danger mt-2">
        {{$message}}
    </div>
    @enderror


    <input type="text" name="isi" id="" value="{{$proker -> isi}}">
    @error('isi')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror


    <input type="time" name="waktu" id="" value="{{$proker -> waktu}}">
    @error('waktu')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <input type="date" name="tanggal_pelaksanaan" id="" value="{{$proker -> tanggal_pelaksanaan}}">
    @error('tanggal_pelaksanaan')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <input type="text" name="lokasi" value="{{$proker -> lokasi}}">
    @error('lokasi')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror


    <div>
        <label for="gambar">Gambar:</label>
        @if($proker->gambar)
            <!-- Menampilkan gambar yang ada di storage -->
            <img src="{{route('proker.show', $proker -> id)}}" alt="Gambar Program Kerja" width="150">
        @else
            <p>Tidak ada gambar yang tersedia</p>
        @endif
        <br>
        <!-- Input file untuk mengganti gambar -->
        <input type="file" name="gambar" id="gambar">
    </div>
    @error('gambar')
        <div class="alert alert-danger mt-2">{{ $message }}</div>
    @enderror

    <select name="status" id="">
        @foreach ($statusList as $status)
        <option value="{{ $status }}" {{ $proker->status == $status ? 'selected' : '' }}>
            {{ $status }}
        </option>
        @endforeach
    </select>
    @error('status')
    <div class="alert alert-danger mt-2">
        {{$message}}
    </div>
    @enderror

    <!-- <select name="status" id="">
        <option value="Belum">Belum</option>
        <option value="Sudah">Sudah</option>
    </select> -->

    <button type="submit">Simpan Data Program Kerja</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>