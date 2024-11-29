@extends('layouts.dashboardNavbar')

@section('content')
    <?php
    $page = 'dashboard'; // or 'program-kerja', 'pembayaran', etc.
    ?>

    <div class="profile-container">
        <!-- Sidebar Section -->
        <div class="profile-sidebar">
            <!-- Profile Image Placeholder -->
            <div class="profile-image-placeholder"></div>

            <!-- User Information -->
            <h2 class="profile-name">John Doe</h2>
            <p class="profile-id">1234567891011121</p>

            <div class="profile-info">
                <p class="profile-info-item"><i class="fas fa-envelope"></i> johndoe@gmail.com</p>
                <p class="profile-info-item"><i class="fas fa-phone"></i> 081234567890</p>
                <p class="profile-info-item"><i class="fas fa-map-marker-alt"></i> Jl. Yos Sudarso, RT. 2, RW. 5</p>
                <p class="profile-info-item"><i class="fas fa-users"></i> Warga</p>
            </div>

            <!-- Logout Button -->
            <form class="profile-logout-form" action="{{ route('logout') }}" method="post"> @csrf
                <button class="profile-logout-btn">KELUAR</button>
            </form>
            
        </div>

        <!-- Edit Profile Section -->
        <div class="profile-edit-section">
            <div class="profile-edit-title">
                <h2 class="edit-section-title">EDIT PROFILE</h2>
            </div>
            <div class="profile-edit-content">
                <!-- Form -->
                <form class="profile-edit-form">
                    <!-- Change Photo Button -->
                    <div class="profile-edit-form-group">
                        <button class="change-photo-btn">Ganti Foto</button>
                    </div>

                    <!-- Input Fields -->
                    <div class="profile-edit-form-group">
                        <input type="text" placeholder="Nama" class="input-field input-name">
                    </div>
                    <div class="profile-edit-form-group">
                        <input type="text" placeholder="No. Hp" class="input-field input-phone">
                    </div>
                    <div class="profile-edit-form-group">
                        <input type="email" placeholder="Email" class="input-field input-email">
                    </div>
                    <div class="profile-edit-form-group">
                        <textarea placeholder="Alamat" class="input-field input-address"></textarea>
                    </div>
                    <div class="profile-edit-form-group">
                        <input type="date" class="input-field input-dob">
                    </div>

                    <!-- Save Button -->
                    <button class="save-profile-btn">SIMPAN</button>
                </form>
            </div>
        </div>
    </div>
@endsection
