<h1>Login coi</h1>
<form action="{{route('rw.login')}}" method="post">
    @csrf
    <input type="text" name="email" id="" placeholder="Email">
    @if (session('email'))
        <div class="alert alert-danger">{{ session('email') }}</div>
    @endif
    <input type="password" name="password" id="" placeholder="Password">
    @if (session('password'))
        <div class="alert alert-danger">{{ session('password') }}</div>
    @endif
    <button type="submit">Masuk</button>
</form>