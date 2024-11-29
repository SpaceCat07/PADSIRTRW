@php
use App\Models\User;
use App\Models\Warga;
@endphp
@foreach ($listKritikRT as $kritik)
    <p>{{$kritik -> id_rt}}</p>
    <p>{{Warga::where('id_warga', User::where('id', $kritik -> id_pengguna) -> first() -> id_warga) -> first() -> nama}}</p>
    <p>{{$kritik -> isi}}</p>
    <p>{{$kritik -> status}}</p>


    <a href="{{route('kritikRT.show', $kritik -> id)}}">lihat lebih lanjut</a>
@endforeach