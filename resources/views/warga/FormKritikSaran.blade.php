@extends('layouts.dashboardNavbar')

<title>SIMAS - Program Kerja</title>
<link rel="stylesheet" href="{{ asset('css/kritik-saran.css') }}">

@section('content')
    <?php
    $page = 'kritik-saran'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    <div class="login-container d-flex justify-content-center align-items-center" style="height: 700px">

        <div class="kritik-card p-5 shadow-lg text-white" style="height: 660px">
            <h2 class="text-center mb-3 fw-bold" style="color: white">KRITIK DAN SARAN</h2>

            <form action="" method="post">
                @csrf
                <!-- Dropdown for selecting recipient -->
                <div class="dropdown-penerima mb-4">
                    <select class="form-select px-3 rounded-custom" name="recipient" id="recipientSelect" required>
                        <option value="" disabled selected>Ditujukan untuk RT berapa?</option>
                        <option value="recipient1">RT 1</option>
                        <option value="recipient2">RT 2</option>
                        <option value="recipient3">RT 3</option>
                    </select>
                </div>

                <!-- Floating label for name field -->
                <div class="mb-4">
                    <label for="floatingEmail" class="form-label fw-semibold">Nama</label>
                    <input type="text" class="form-control px-3" name="name" id="floatingName" required>
                </div>

                <!-- Floating label for content field -->
                <div class="mb-4 position-relative">
                    <label for="floatingContent" class="form-label fw-semibold">Kritik / Saran</label>
                    <textarea class="form-control px-3" name="kritik" id="floatingKritik" style="height: 200px; border: 1px solid white;"
                        required></textarea>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="submit-btn btn-warning">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
