<form action="{{route('account.update', $user -> id)}}" method="post">
    @csrf
    @method('PATCH')
    <input type="email" name="email" id="" placeholder="Email" value="{{$user -> email}}">
    @error('email')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror
    <input type="tel" name="no_hp" id="" placeholder="No Handphone" value="{{$user -> no_hp}}">
    @error('no_hp')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror
    <select name="aktivasi" id="">
        <option value="Activated" {{$user -> aktivasi === 'Activated' ? 'selected': ''}}>Aktif</option>
        <option value="Unactivated" {{$user -> aktivasi === 'Unactivated' ? 'selected': ''}}>Tidak aktif</option>
    </select>
    @error('aktivasi')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Ubah data</button>

</form>
<a href="/account">Kembali</a>