@if (session('pesan'))
    <div class="alert alert-success">
        {{ session('pesan') }}
    </div>
@endif
<form action="{{route('kritik.store')}}" method="post">
    @csrf
    <input type="text" name="isi" id="" placeholder="Isi Kritik/Saran" style="height: 200px; border: 1px solid white;">
    @error('isi')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Kirim</button>
</form>