<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{route('account.requestStore')}}" method="post">
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
    
        <button type="submit">Register</button>
    </form>
</body>
</html>