<form action="{{route('account.store')}}" method="post">
    @csrf
    <input type="text" name="nik" id="" placeholder="NIK">
    @error('nik')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror
    <input type="email" name="email" id="" placeholder="Email">
    @error('email')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror
    <input type="tel" name="no_hp" id="" placeholder="Nomor Telepon">
    @error('no_hp')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror
    <input type="password" name="password" id="" placeholder="Password">
    @error('password')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror
    <select name="role" id="">
        <option value="Admin_RW">Admin RW</option>
        <option value="Admin_RT">Admin RT</option>
        <option value="Ketua_RW">Ketua RW</option>
        <option value="Ketua_RT">Ketua RT</option>
        <option value="Warga">Warga</option>
        <option value="Super_Admin">Super Admin</option>
    </select>
    @error('role')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <button type="submit">Tambahkan User</button>

</form>