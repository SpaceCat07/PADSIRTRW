<form action="{{route('iuranRW.update', $iuran -> id)}}" method="post">
    @csrf
    @method('PATCH')

    <input type="text" name="nama_iuran" id="" value="{{$iuran -> nama_iuran}}" placeholder="Nama Iuran">
    @error('nama_iuran')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <select name="bulan" id="">
        @foreach ($bulanList as $bulan)
            <option value="{{ $bulan }}" {{$iuran -> bulan === $bulan ? 'selected' : ''}}>{{ $bulan }}</option>
        @endforeach
    </select>
    @error('bulan')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <input type="number" name="total_iuran" id="" value="{{$iuran -> total_iuran}}" placeholder="Total Iuran">
    @error('total_iuran')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <select name="jenis_iuran" id="">
        @foreach ($jenisList as $jenis)
            <option value="{{ $jenis }}" {{$iuran -> jenis_iuran === $jenis ? 'selected': ''}}>{{ $jenis }}</option>
        @endforeach
    </select>
    @error('jenis_iuran')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Ubah Data Iuran RT</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>