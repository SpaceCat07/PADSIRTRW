<form action="{{route('kritikRT.update', $kritik -> id)}}" method="post">
    @csrf
    @method('PATCH')

    {{RTModel::find($kritik -> id_rt) -> nama_rt}}
    {{$kritik -> isi}}

    <select name="status" id="">
        @foreach ($listStatus as $status)
        <option value="{{ $status }}" {{}}>{{$status}}</option>
        @endforeach
    </select>
    @error('status')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Ubah Data Kritik RT</button>
</form>

<a href="{{redirect() -> back()}}">Kembali</a>