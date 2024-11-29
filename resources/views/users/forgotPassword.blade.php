<form action="{{route('forgot-password.validation')}}" method="post">
    @csrf

    <input type="text" name="email" id="" placeholder="Email">
    @error('email')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    <input type="number" name="nik" id="" placeholder="NIK">
    @error('nik')
        <div class="alert alert-danger mt-2">
            {{$message}}
        </div>
    @enderror

    @if(session() -> has('fail'))
        <div class="alert alert-danger mt-2">
            {{session() -> get('fail')}}
        </div>
    @endif

    <button type="submit">Submit</button>
</form>