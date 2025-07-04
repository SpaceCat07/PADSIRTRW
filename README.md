# SIMAS - Sistem Informasi Masyarakat RW/RT

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
</p>

<p align="center">
  <strong>Sistem Informasi Masyarakat RW 05, Desa Pabean, Kec. Dringu, Kab. Probolinggo</strong>
</p>

## Tentang SIMAS

SIMAS (Sistem Informasi Masyarakat) adalah aplikasi web berbasis Laravel yang dirancang untuk memudahkan pengelolaan administrasi dan kegiatan masyarakat di tingkat RW (Rukun Warga) dan RT (Rukun Tetangga). Aplikasi ini menyediakan platform digital yang memungkinkan warga untuk berinteraksi dengan pengurus RW/RT secara efisien dan transparan.

## Fitur Utama

### 👥 Manajemen Pengguna
- **Multi-Role System**: Admin, Warga, Pengurus RW/RT
- **Autentikasi & Otorisasi**: Login/logout dengan sistem keamanan
- **Manajemen Profil**: Edit dan kelola informasi pengguna
- **Aktivasi Akun**: Sistem persetujuan akun baru

### 💰 Sistem Pembayaran & Iuran
- **Manajemen Iuran RW/RT**: Pencatatan dan tracking iuran bulanan
- **Pembayaran Digital**: Integrasi dengan Midtrans Payment Gateway
- **Riwayat Pembayaran**: Tracking status pembayaran warga
- **Notifikasi Email**: Pemberitahuan pembayaran gagal/berhasil
- **Laporan Keuangan**: Pemasukan dan pengeluaran RW/RT

### 📋 Program Kerja
- **Manajemen Program Kerja**: CRUD program kerja RW/RT
- **Penjadwalan**: Waktu dan lokasi pelaksanaan kegiatan
- **Status Tracking**: On progress/selesai
- **Galeri Foto**: Dokumentasi kegiatan

### 💬 Komunikasi
- **Kritik & Saran**: Platform feedback dari warga
- **Notifikasi**: Sistem pemberitahuan untuk berbagai aktivitas

### 📊 Laporan & Dashboard
- **Dashboard Admin**: Overview data dan statistik
- **Dashboard Warga**: Informasi personal dan kegiatan
- **Laporan Keuangan**: Transparansi pengelolaan dana
- **Visualisasi Data**: Chart dan grafik untuk analisis

## Teknologi yang Digunakan

### Backend
- **Laravel 10**: Framework PHP modern
- **PHP 8.1+**: Bahasa pemrograman utama
- **MySQL**: Database management system
- **Laravel Sanctum**: API authentication

### Frontend
- **Blade Templates**: Laravel templating engine
- **Bootstrap**: CSS framework untuk responsive design
- **JavaScript**: Interaktivitas dan AJAX
- **CSS3**: Styling dan animasi

### Payment Gateway
- **Midtrans**: Payment processor untuk transaksi digital

### Email Service
- **Laravel Mail**: Sistem email notification

## Struktur Database

Aplikasi ini menggunakan beberapa tabel utama:
- `users`: Data pengguna sistem
- `warga`: Informasi warga
- `rw`, `rt`: Data RW dan RT
- `proker`: Program kerja
- `iuran_rw`, `iuran_rt`: Data iuran
- `keuangan_rw`, `keuangan_rt`: Laporan keuangan
- `kritik_saran_rw`, `kritik_saran_rt`: Feedback warga

## Instalasi

### Persyaratan Sistem
- PHP 8.1 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL 5.7+
- Apache/Nginx

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone https://github.com/username/PADSIRTRW.git
cd PADSIRTRW
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Setup**
```bash
# Edit .env file dengan konfigurasi database
php artisan migrate
php artisan db:seed
```

5. **Midtrans Configuration**
```bash
# Tambahkan ke .env file
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

6. **Build Assets**
```bash
npm run build
```

7. **Start Development Server**
```bash
php artisan serve
```

## Konfigurasi

### Database
Edit file `.env` untuk konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simas_db
DB_USERNAME=root
DB_PASSWORD=
```

### Email
Konfigurasi email untuk notifikasi:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### Storage
Setup symbolic link untuk storage:
```bash
php artisan storage:link
```

## Penggunaan

### Admin
- Login ke `/masuk`
- Akses dashboard admin di `/dashboard/admin`
- Kelola data warga, iuran, dan program kerja

### Warga
- Login ke `/masuk`
- Akses dashboard warga di `/dashboard/warga`
- Lihat program kerja, bayar iuran, berikan feedback

### Pengurus RW/RT
- Login sesuai level akses
- Kelola program kerja dan iuran sesuai wilayah

## API Documentation

Aplikasi ini menyediakan API endpoints untuk:
- Autentikasi pengguna
- Manajemen data warga
- Transaksi pembayaran
- CRUD program kerja

## Testing

Jalankan test suite:
```bash
php artisan test
```

## Deployment

### Production Setup
1. Set environment ke production di `.env`
2. Optimize application:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Setup web server (Apache/Nginx)
4. Configure SSL certificate
5. Setup database backup

## Kontribusi

Kami menerima kontribusi dari developer lain. Silakan:
1. Fork repository
2. Buat branch feature (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## Roadmap

- [ ] Mobile app (React Native/Flutter)
- [ ] Real-time notifications
- [ ] Advanced reporting
- [ ] Integration with government systems
- [ ] Multi-language support

## Support

Jika Anda mengalami masalah atau memiliki pertanyaan:
- Buka issue di GitHub
- Email: support@simas.com
- Documentation: [Wiki](https://github.com/username/PADSIRTRW/wiki)

## Security

Jika Anda menemukan kerentanan keamanan, silakan laporkan ke:
- Email: security@simas.com
- Jangan publish vulnerability secara publik

## Changelog

### v1.0.0
- Initial release
- Basic CRUD operations
- Payment integration
- Email notifications

## License

Aplikasi ini dilisensikan under [MIT License](LICENSE).

## Credits

- **Laravel Framework**: [Laravel](https://laravel.com)
- **Midtrans Payment**: [Midtrans](https://midtrans.com)
- **Bootstrap**: [Bootstrap](https://getbootstrap.com)

---

<p align="center">
Dibuat dengan ❤️ untuk kemajuan masyarakat RW 05, Desa Pabean
</p> 
