@php
use App\Models\User;
use App\Models\Warga;
@endphp
<h1>Pesan Kritik Saran</h1>
<p>{{Warga::where('id_warga', User::where('id', $kritik -> id_pengguna) -> first() -> id_warga) -> first() -> nama}}</p>
<p>{{$kritik -> isi}}</p>

<form action="{{route('kritikRT.dibaca', $kritik -> id)}}" method="post">
    @csrf
    @method('patch')

    <button type="submit">Dibaca</button>
</form>

<form action="{{route('kritikRT.selesai', $kritik -> id)}}" method="post">
    @csrf
    @method('patch')

    <button type="submit">Selesai</button>
</form>
<a href="{{route('kritikRT.list')}}">kembali</a>