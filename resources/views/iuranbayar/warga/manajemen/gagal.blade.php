<form action="{{route('manajemen-detail-iuran-rt-pengguna.gagal', $detail_iuran -> id)}}" method="post">
    @csrf
    @method('DELETE')

    <p>pembayaran dengan id {{$detail_iuran -> id}} gagal</p>

    <p>berikan keterangan pada kolom dibawah</p>
    <input type="text" name="keterangan" id="" placeholder="Keterangan">

    @error('keterangan')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Kirim</button>

</form>