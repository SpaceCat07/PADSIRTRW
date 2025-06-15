@extends('layouts.checkoutNavbar')

@section('content')
<title>SIMAS - Pembayaran</title>
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
<?php $page = 'pembayaran'; ?>

<div style="text-align: center; padding: 100px 20px;">
    <h3 style="font-size: 20px; font-weight: 400; margin-bottom: 20px;">
        Pembayaran Anda sedang diproses.Anda dapat kembali ke halaman utama 
        <br>untuk menunggu Admin mengonfirmasi pembayaran Anda.
    </h3>

    <a href="{{ route('dashboard.warga') }}" style="color: #6c63ff; font-weight: bold; text-decoration: none;">
        Kembali ke Dashboard
    </a>
</div>
@endsection
