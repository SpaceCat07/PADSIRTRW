@if (session() -> has('passwordForgot'))
    <form action="{{route('reset-password.update', $user -> id)}}" method="post">
        @csrf
        @method('patch')

        <input type="password" name="password" id="" placeholder="Password baru">
        @error('password')
            <div class="alert alert-danger mt-2">
                {{$message}}
            </div>
        @enderror



        <input type="password" name="password_confirmation" id="" placeholder="Konfirmasi password baru">
        @error('password_confirmation')
            <div class="alert alert-danger mt-2">
                {{$message}}
            </div>
        @enderror

        <button type="submit">Reset Password</button>
    </form>

@else
<p>anda belum dapat mengubah password konfirmasi email dan nik terlebih dahulu <a href="{{route('forgot-password')}}">disini</a></p>
@endif