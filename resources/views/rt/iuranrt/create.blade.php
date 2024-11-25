<form action="{{route('iuranRT.store')}}" method="POST">
    @csrf


    <input type="text" name="nama_iuran" id="" placeholder="Nama Iuran">
    @error('nama_iuran')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <select name="bulan" id="">
        @foreach ($bulanList as $bulan)
            <option value="{{ $bulan }}">{{ $bulan }}</option>
        @endforeach
    </select>
    @error('bulan')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <input type="number" name="total_iuran" id="" placeholder="Total Iuran">
    @error('total_iuran')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <select name="jenis_iuran" id="">
        @foreach ($jenis_iuran as $jenis)
            <option value="{{ $jenis }}">{{ $jenis }}</option>
        @endforeach
    </select>
    @error('jenis_iuran')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Tambah Iuran RT</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>