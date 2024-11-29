@php
use App\Models\User;
use App\Models\Warga;
@endphp
@foreach ($listKritikRW as $kritik)
    <p>{{$kritik -> id_rw}}</p>
    <p>{{Warga::where('id_warga', User::where('id', $kritik -> id_pengguna) -> first() -> id_warga) -> first() -> nama}}</p>
    <p>{{$kritik -> isi}}</p>
    <p>{{$kritik -> status}}</p>


    <a href="{{route('kritikRW.show', $kritik -> id)}}">lihat lebih lanjut</a>
@endforeach