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

            <form id="kritikSaranForm">
                @csrf
                <div class="mb-4 position-relative">
                    <label for="floatingContent" class="form-label fw-semibold">Kritik / Saran</label>
                    <textarea class="form-control px-3" name="kritik" id="floatingKritik" style="height: 200px; border: 1px solid white;"
                        required></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="submit-btn btn-warning">Submit</button>
                </div>
            </form>
            <div id="formMessage" class="mt-3 text-center"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('kritikSaranForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const kritik = document.getElementById('floatingKritik').value;
            const formMessage = document.getElementById('formMessage');

            axios.post('/api/kritik-saran', {
                kritik: kritik
            })
            .then(function (response) {
                formMessage.style.color = 'lightgreen';
                formMessage.textContent = 'Kritik dan saran berhasil dikirim.';
                document.getElementById('kritikSaranForm').reset();
            })
            .catch(function (error) {
                formMessage.style.color = 'red';
                formMessage.textContent = 'Terjadi kesalahan saat mengirim kritik dan saran.';
            });
        });
    </script>
@endsection
