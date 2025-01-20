@extends('layouts.landingNavbar')

<title>SIMAS - Lupa Password</title>
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

@section('content')
    <div class="login-container d-flex justify-content-center align-items-center">
        <div class="login-card p-5 shadow-lg text-white">
            <form action="{{ route('forgot-password.validation') }}" method="post">
                @csrf

                <div class="mb-4">
                    <label for="floatingEmail" class="form-label fw-semibold">Email</label>
                    <input type="text" class="form-control rounded-pill px-3" name="email" id=""
                        placeholder="Enter your email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="floatingEmail" class="form-label fw-semibold">Nomor Induk Kependudukan</label>
                    <input type="number" class="form-control rounded-pill px-3" name="nik" id=""
                        placeholder="Enter your NIK" value="{{ old('nik') }}" required>
                    @error('nik')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                @if (session()->has('fail'))
                    <div class="alert alert-danger mt-2">
                        {{ session()->get('fail') }}
                    </div>
                @endif

                <div class="text-center">
                    <button type="submit" class="submit-btn btn-warning">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
