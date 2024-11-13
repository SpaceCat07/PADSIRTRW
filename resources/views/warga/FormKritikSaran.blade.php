@extends('layouts.dashboardNavbar')

@section('content')
    <?php
    $page = 'kritik-saran'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    <div class="login-container d-flex justify-content-center align-items-center" style="height: 800px">

        <div class="kritik-card p-5 shadow-lg text-white" style="height: 560px">
            <h2 class="text-center mb-3 fw-bold" style="color: white">KRITIK DAN SARAN</h2>

            <form action="" method="post">
                @csrf
                <!-- Floating label for email (username) field -->
                <div class="mb-4">
                    <label for="floatingEmail" class="form-label fw-semibold">Nama</label>
                    <input type="text" class="form-control px-3" name="name" id="floatingName"
                        required>
                </div>

                <!-- Floating label for password field -->
                <div class="mb-4 position-relative">
                    <label for="floatingPassword" class="form-label fw-semibold">Kritik / Saran</label>
                    <input type="password" class="form-control px-3" name="kritik" id="floatingKritik"
                        style="height: 200px; border: 1px solid white;" value="" required>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="submit-btn btn-warning">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
