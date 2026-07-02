Markdown
# Sistem Optimasi Tiket Ferry

Sistem Optimasi Tiket Ferry adalah aplikasi sistem informasi berbasis web yang digunakan untuk membantu proses pemesanan tiket kapal ferry, pengelolaan jadwal keberangkatan, data kapal, rute, penumpang, serta monitoring alokasi kapasitas kapal.

Sistem ini dibuat untuk mendukung proses digitalisasi layanan tiket ferry agar pemesanan menjadi lebih tertata, data lebih mudah dipantau, dan kapasitas kapal dapat dikelola secara lebih optimal.

## Deskripsi Singkat

Aplikasi ini menyediakan portal untuk penumpang dalam melakukan registrasi, login, pemesanan tiket, melihat riwayat pemesanan, dan memperbarui profil. Selain itu, sistem juga mendukung pengelolaan data internal seperti kapal, rute, jadwal keberangkatan, pemesanan tiket, alokasi tiket, dan hasil optimasi.

Studi kasus sistem ini berfokus pada proses pengelolaan tiket ferry dengan kebutuhan utama berupa pemanfaatan kapasitas kapal, monitoring status pemesanan, dan pencatatan data pemesanan secara digital.

## Fitur Utama

### Portal Penumpang
- Registrasi akun penumpang
- Login dan logout akun penumpang
- Dashboard penumpang
- Pemesanan tiket kapal ferry
- Riwayat pemesanan tiket
- Detail status pemesanan
- Edit pemesanan selama status masih menunggu proses
- Edit profil penumpang
- Notifikasi menggunakan SweetAlert2

### Pengelolaan Data Internal
- Manajemen data pengguna
- Manajemen data kapal
- Manajemen data rute
- Manajemen jadwal keberangkatan
- Manajemen data penumpang
- Manajemen pemesanan tiket
- Pengelolaan alokasi tiket
- Monitoring hasil optimasi
- Laporan pemanfaatan kapasitas kapal

### Role Pengguna
- Super Admin
- Admin
- Pimpinan
- Penumpang

## Teknologi yang Digunakan

- PHP
- Laravel
- MySQL
- Filament
- Bootstrap
- Bootstrap Icons
- SweetAlert2
- Mentor Bootstrap Template

## Struktur Utama Project

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   └── User/
│   │       ├── DashboardController.php
│   │       ├── PemesananTiketController.php
│   │       └── ProfilController.php
│   └── Middleware/
│       └── RoleMiddleware.php
├── Models/
│   ├── User.php
│   ├── Kapal.php
│   ├── Rute.php
│   ├── JadwalKeberangkatan.php
│   ├── Penumpang.php
│   ├── PemesananTiket.php
│   ├── AlokasiTiket.php
│   └── HasilOptimasi.php

resources/
└── views/
    └── user/
        ├── auth/
        │   ├── login.blade.php
        │   └── register.blade.php
        ├── home/
        │   └── index.blade.php
        ├── layouts/
        │   ├── app.blade.php
        │   └── guest.blade.php
        ├── partials/
        │   ├── alerts.blade.php
        │   ├── footer.blade.php
        │   └── navbar.blade.php
        ├── pemesanan/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   ├── edit.blade.php
        │   └── show.blade.php
        ├── profil/
        │   └── edit.blade.php
        └── dashboard.blade.php
Instalasi Project
Clone repository:

Bash
git clone [https://github.com/USERNAME/sistem-optimasi-tiket-ferry.git](https://github.com/USERNAME/sistem-optimasi-tiket-ferry.git)
cd sistem-optimasi-tiket-ferry
Install dependency PHP:

Bash
composer install
Install dependency frontend:

Bash
npm install
Salin file environment:

Bash
cp .env.example .env
Generate application key:

Bash
php artisan key:generate
Atur konfigurasi database pada file .env:

Cuplikan kode
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=optimasi_tiket_ferry
DB_USERNAME=root
DB_PASSWORD=
Jalankan migration:

Bash
php artisan migrate
Jalankan seeder jika tersedia:

Bash
php artisan db:seed
Jalankan server lokal:

Bash
php artisan serve
Buka aplikasi:

Plaintext
[http://127.0.0.1:8000](http://127.0.0.1:8000)
Halaman Aplikasi
Landing Page
/
Menampilkan informasi umum sistem, fitur utama, alur sistem, dan tombol masuk ke portal penumpang.

Login Penumpang
/user/login
Digunakan oleh penumpang untuk masuk ke sistem.

Registrasi Penumpang
/user/register
Digunakan oleh penumpang untuk membuat akun baru.

Dashboard Penumpang
/user/dashboard
Menampilkan ringkasan pemesanan tiket dan informasi akun penumpang.

Pemesanan Tiket
/user/pemesanan
Menampilkan riwayat pemesanan tiket penumpang.

Profil Penumpang
/user/profil
Digunakan untuk memperbarui data akun dan informasi penumpang.

Alur Penggunaan Penumpang
Penumpang membuka halaman utama sistem.

Penumpang melakukan registrasi akun.

Penumpang login ke portal.

Penumpang melengkapi profil.

Penumpang memilih jadwal keberangkatan.

Penumpang membuat pemesanan tiket.

Sistem menyimpan data pemesanan dengan status menunggu proses.

Penumpang dapat melihat riwayat dan status pemesanan.

Admin memproses data pemesanan dan alokasi tiket.

Penumpang melihat hasil status pemesanan.

Akun Testing
Gunakan data berikut untuk testing penumpang:

Nama: Penumpang

Email: penumpang@gmail.com

Password: password123

NIK: 9999999999999999

Nomor HP: 081234567890

Alamat: Jl. Poros Siwa - Tobaku

Catatan Environment
File .env tidak disertakan ke repository karena berisi konfigurasi lokal dan data sensitif. Gunakan .env.example sebagai acuan konfigurasi.

Pastikan nilai berikut disesuaikan saat menjalankan project secara lokal:

Cuplikan kode
APP_URL=[http://127.0.0.1:8000](http://127.0.0.1:8000)
SESSION_DOMAIN=
Status Pengembangan
Project ini masih dapat dikembangkan dengan beberapa fitur tambahan seperti:

Cetak tiket

Upload bukti pembayaran

Integrasi pembayaran

Notifikasi email

Export laporan PDF

Export laporan Excel

Grafik hasil optimasi

Validasi kapasitas kapal secara otomatis

Riwayat perubahan status pemesanan

Lisensi
Project ini dibuat untuk kebutuhan pembelajaran, penelitian, dan pengembangan sistem informasi optimasi tiket ferry.