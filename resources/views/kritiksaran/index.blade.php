@if (session('pesan'))
    <div class="alert alert-success">
        {{ session('pesan') }}
    </div>
@endif
<form action="{{route('kritik.store')}}" method="post">
    @csrf

    <select name="tujuan" id="">
        @foreach ($listRW as $rw)
            <option value="{{ $rw -> nama_rw }}">{{ $rw -> nama_rw }}</option>
        @endforeach
        @foreach ($listRT as $rt)
            <option value="{{ $rt -> nama_rt }}">{{ $rt -> nama_rt}}</option>
        @endforeach
    </select>
    @error('role')
    <div class="alert alert-danger mt-2">
        {{$message}}
    </div>
    @enderror

    <input type="text" name="isi" id="" placeholder="Isi Kritik/Saran">
    @error('isi')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Kirim</button>
</form>