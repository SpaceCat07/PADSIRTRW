<form action="{{route('kritikRW.update', $kritik -> id)}}" method="post">
    @csrf
    @method('PATCH')

    {{RWModel::find($kritik -> id_rw) -> nama_rw}}
    {{$kritik -> isi}}

    <select name="status" id="">
        @foreach ($listStatus as $status)
        <option value="{{ $status }}">{{$status}}</option>
        @endforeach
    </select>
    @error('status')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Ubah Data Kritik RW</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>