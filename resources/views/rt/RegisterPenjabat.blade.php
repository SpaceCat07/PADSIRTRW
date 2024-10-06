<form action="{{route('rt.store')}}" method="post">
    @csrf
    <input type="text" name="nama" id="" placeholder="Nama">
    <input type="email" name="email" id="" placeholder="Email">
    <input type="tel" name="no_hp" id="" placeholder="Nomor HandPhone">
    <input type="text" name="username" id="" placeholder="username">
    <input type="password" name="password" id="" placeholder="password">
    <select name="Role" id="">
        <option value="Admin_RT">Admin RT</option>
        <option value="Ketua_RT">Ketua RT</option>
    </select>
    <button type="submit">Register</button>
</form>