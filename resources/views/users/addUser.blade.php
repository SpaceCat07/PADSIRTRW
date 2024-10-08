<form action="{{route('store')}}" method="post">
    <input type="text" name="nik" id="">
    @error('nik')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror
    <input type="text" name="nama" id="">
    @error('nama')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror
    <input type="email" name="email" id="">
    @error('email')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror
    <input type="tel" name="no_hp" id="">
    @error('no_hp')
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