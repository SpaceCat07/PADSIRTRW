<form action="{{route('kritikRT.update', $kritik -> id)}}" method="post">
    @csrf
    @method('PATCH')

    {{RTModel::find($kritik -> id_rt) -> nama_rt}}
    {{$kritik -> isi}}

    <select name="id_rt" id="">
        @foreach ($listRT as $RT)
        <option value="{{ $RT }}">{{'RT ' . $RT }}</option>
        @endforeach
    </select>
    @error('id_rt')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror



    <input type="number" name="nomor_rekening" id="" value="{{$rt -> nomor_rekening}}">
    @error('nomor_rekening')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Ubah Data Kritik RT</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>