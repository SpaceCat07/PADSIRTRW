@extends('layouts.landingNavbar')

@section('content')
    <div class="landing-page-container">
        <div class="selamat-datang">
            <div class="selamat-datang-top">
                <div class="selamat-datang-top-texts">
                    <h1>Selamat Datang</h1>
                    <h1>Di Website</h1>
                    <h1 class="simas">SIMAS</h1>
                    <p>Sistem Informasi Masyarakat RW 05, Desa Pabean, Kec. Dringu, Kab. Probolinggo</p>
                </div>
                <div class="selamat-datang-top-images">
                    <div class="image-accessory-wrapper">
                        <div class="image-wrapper img-1">
                            <img src="{{ asset('storage/selamatDatang1.png') }}" alt="Selamat Datang">
                        </div>
                        <div class="img-1-accessory"></div>
                    </div>
                    <div class="image-accessory-wrapper">
                        <div class="img-2-accessory"></div>
                        <div class="image-wrapper img-2">
                            <img src="{{ asset('storage/selamatDatang2.png') }}" alt="Selamat Datang">
                        </div>
                    </div>
                </div>
            </div>

            <div class="selamat-datang-bottom">
                <p>Masuk Terlebih Dahulu</p>
                <a href="{{ route('masuk') }}" class="btn-masuk text-align-center">Masuk<img
                        src="{{ asset('storage/arrowRight.png') }}" alt=""></a>
            </div>
        </div>

        <div class="about-us" style="background-image: url({{ asset('storage/landingBackground.png') }});">
            <h2>About Us</h2>
            <p>
                SIMAS hadir sebagai solusi modern untuk mengintegrasikan manajemen data warga, pelaporan keuangan,
                dan pelaksanaan program kerja dalam satu sistem yang efisien dan transparan. Melalui SIMAS,
                pengurus RT/RW dapat mengelola data warga secara otomatis, melacak status iuran, membuat laporan keuangan
                yang
                akurat,
                serta merencanakan kegiatan komunitas dengan mudah.
            </p>
        </div>

        <div class="fitur-tersedia">
            <h2>Fitur yang tersedia</h2>
            <div class="fitur-list">
                <div class="fitur-item item-1">
                    <h3>Program Kerja</h3>
                    <p>Pengguna dapat melihat status iuran, melakukan pembayaran secara online, serta mendapatkan pengingat
                        otomatis.</p>
                </div>
                <div class="fitur-item item-2">
                    <h3>Program Kerja</h3>
                    <p>Pengurus dapat membuat dan memperbarui jadwal program kerja, mengumumkan kegiatan kepada warga, serta
                        memberikan pengingat otomatis.</p>
                </div>
                <div class="fitur-item item-3">
                    <h3>Kritik dan Saran</h3>
                    <p>Wadah bagi warga untuk menyampaikan kritik, saran, atau masukan secara langsung kepada pengurus
                        RT/RW.
                    </p>
                </div>
            </div>
        </div>

        <div class="contact-section">
            <div class="map">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15876.797657217443!2d113.2383873!3d-7.7606267!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7adab38c44b05%3A0x43ab048f746781aa!2sKantor%20Kepala%20Desa%20Pabean!5e0!3m2!1sen!2sid!4v1634887945140!5m2!1sen!2sid"
                    allowfullscreen="" loading="lazy">
                </iframe>
            </div>
            <div class="contact-info">
                <h3>Contact</h3>
                <div class="contact-info-details">
                    <div class="contact-item">
                        <img src="{{ asset('storage/email.png') }}" alt="">
                        <p>contact@company.com</p>
                    </div>
                    <div class="contact-item">
                        <img src="{{ asset('storage/phone.png') }}" alt="">
                        <p>(414) 687-5892</p>
                    </div>
                    <div class="contact-item">
                        <img src="{{ asset('storage/locationMark.png') }}" alt="">
                        <p>794 Mcallister St, San Francisco, 94102</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-section">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="{{ asset('storage/logo.png') }}" alt="SIMAS Logo">
            </div>
            <div class="footer-links">
                <div class="footer-links-item">
                    <p class="links-header">Product</p>
                    <a href="/">Features</a>
                    <a href="/">Pricing</a>
                    <a href="/">Case studies</a>
                    <a href="/">Reviews</a>
                    <a href="/">Updates</a>
                </div>
                <div class="footer-links-item">
                    <p class="links-header">Company</p>
                    <a href="/">About</a>
                    <a href="/">Contact us</a>
                    <a href="/">Careers</a>
                    <a href="/">Culture</a>
                    <a href="/">Blog</a>
                </div>
                <div class="footer-links-item">
                    <p class="links-header">Support</p>
                    <a href="/">Getting started</a>
                    <a href="/">Help center</a>
                    <a href="/">Server status</a>
                    <a href="/">Report a bug</a>
                    <a href="/">Chat support</a>
                </div>
                <div class="footer-links-item">
                    <p class="links-header">Contact Us</p>
                    <div class="contact-item">
                        <img src="{{ asset('storage/emailDark.png') }}" alt="">
                        <a href="">contact@company.com</a>
                    </div>
                    <div class="contact-item">
                        <img src="{{ asset('storage/phoneDark.png') }}" alt="">
                        <a href="">(414) 687-5892</a>
                    </div>
                    <div class="contact-item">
                        <img src="{{ asset('storage/locationMarkDark.png') }}" alt="">
                        <a href="">794 Mcallister St, San Francisco, 94102</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-socials">
            <a href="https://www.facebook.com" target="_blank">
                <img src="{{ asset('storage/Facebook.png') }}" alt="Facebook">
            </a>
            <a href="https://www.twitter.com" target="_blank">
                <img src="{{ asset('storage/Twitter.png') }}" alt="Twitter">
            </a>
            <a href="https://www.instagram.com" target="_blank">
                <img src="{{ asset('storage/Instagram.png') }}" alt="Instagram">
            </a>
            <a href="https://www.twitter.com" target="_blank">
                <img src="{{ asset('storage/LinkedIn.png') }}" alt="Twitter">
            </a>
            <a href="https://www.twitter.com" target="_blank">
                <img src="{{ asset('storage/Youtube.png') }}" alt="Twitter">
            </a>
        </div>
        <div class="footer-bottom">
            <p>Copyright © 2024 SIMAS RT/RW</p>
            <p>All Rights Reserved | <a href="">Terms and Conditions</a> | <a href="">Privacy Policy</a></p>
        </div>
    </footer>
@endsection
