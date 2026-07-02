<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Home - Sistem Optimasi Tiket Ferry</title>
    <meta name="description" content="Sistem informasi optimasi alokasi tiket dan kapasitas kapal ferry.">
    <meta name="keywords" content="tiket ferry, kapal ferry, sistem informasi, optimasi tiket, kapasitas kapal">

    <link href="{{ asset('Mentor-1.0.0/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('Mentor-1.0.0/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Raleway:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="{{ asset('Mentor-1.0.0/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Mentor-1.0.0/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('Mentor-1.0.0/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('Mentor-1.0.0/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Mentor-1.0.0/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Mentor-1.0.0/assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

@include('user.partials.navbar')

<main class="main">

    <section id="hero" class="hero section dark-background">
        <img src="{{ asset('Mentor-1.0.0/assets/img/hero-bg.jpg') }}" alt="Sistem Optimasi Tiket Ferry" data-aos="fade-in">

        <div class="container">
            <h2 data-aos="fade-up" data-aos-delay="100">
                Sistem Informasi Optimasi<br>Alokasi Tiket Ferry
            </h2>

            <p data-aos="fade-up" data-aos-delay="200">
                Platform digital untuk membantu proses pemesanan tiket, pengelolaan jadwal kapal,
                dan optimasi kapasitas penumpang pada layanan ferry.
            </p>

            <div class="d-flex mt-4 gap-2" data-aos="fade-up" data-aos-delay="300">
                @auth
                    <a href="{{ route('user.dashboard') }}" class="btn-get-started">
                        Masuk Dashboard
                    </a>
                @else
                    <a href="{{ route('user.login') }}" class="btn-get-started">
                        Pesan Tiket
                    </a>
                    <a href="{{ route('user.register') }}" class="btn-get-started">
                        Daftar Akun
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <section id="tentang" class="about section">
        <div class="container">

            <div class="row gy-4 align-items-center">

                <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{ asset('Mentor-1.0.0/assets/img/about.jpg') }}" class="img-fluid" alt="Tentang Sistem">
                </div>

                <div class="col-lg-6 order-2 order-lg-1 content" data-aos="fade-up" data-aos-delay="200">
                    <h3>Tentang Sistem</h3>

                    <p class="fst-italic">
                        Sistem ini dirancang untuk membantu pengelolaan pemesanan tiket kapal ferry agar lebih tertata,
                        cepat, dan sesuai dengan kapasitas kapal yang tersedia.
                    </p>

                    <ul>
                        <li>
                            <i class="bi bi-check-circle"></i>
                            <span>Penumpang dapat melakukan registrasi dan pemesanan tiket secara online.</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle"></i>
                            <span>Admin dapat mengelola data kapal, rute, jadwal, dan pemesanan tiket.</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle"></i>
                            <span>Pimpinan dapat memantau hasil optimasi dan laporan pemanfaatan kapasitas kapal.</span>
                        </li>
                    </ul>

                    @auth
                        <a href="{{ route('user.dashboard') }}" class="read-more">
                            <span>Buka Dashboard</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('user.register') }}" class="read-more">
                            <span>Mulai Registrasi</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    @endauth
                </div>

            </div>

        </div>
    </section>

    <section id="counts" class="section counts light-background">
        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span>4</span>
                        <p>Role Pengguna</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span>5</span>
                        <p>Modul Utama</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span>2</span>
                        <p>Metode Optimasi</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span>24</span>
                        <p>Akses Online</p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <section id="fitur" class="section why-us">
        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="why-box">
                        <h3>Mengapa Sistem Ini Dibutuhkan?</h3>
                        <p>
                            Sistem ini membantu proses pelayanan tiket ferry agar lebih efisien,
                            mengurangi pencatatan manual, memudahkan pemantauan kapasitas kapal,
                            dan mendukung proses pengambilan keputusan berdasarkan data.
                        </p>

                        <div class="text-center">
                            @auth
                                <a href="{{ route('user.dashboard') }}" class="more-btn">
                                    <span>Dashboard</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            @else
                                <a href="{{ route('user.login') }}" class="more-btn">
                                    <span>Mulai Gunakan</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 d-flex align-items-stretch">
                    <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">

                        <div class="col-xl-4">
                            <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                                <i class="bi bi-calendar-check"></i>
                                <h4>Jadwal Kapal</h4>
                                <p>Informasi jadwal keberangkatan kapal dapat dikelola dan dipantau secara terstruktur.</p>
                            </div>
                        </div>

                        <div class="col-xl-4" data-aos="fade-up" data-aos-delay="300">
                            <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                                <i class="bi bi-ticket-perforated"></i>
                                <h4>Pemesanan Tiket</h4>
                                <p>Penumpang dapat melakukan pemesanan tiket sesuai jadwal yang tersedia.</p>
                            </div>
                        </div>

                        <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
                            <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                                <i class="bi bi-bar-chart-line"></i>
                                <h4>Optimasi Kapasitas</h4>
                                <p>Sistem membantu proses alokasi tiket berdasarkan kapasitas kapal dan data pemesanan.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>

    <section id="alur" class="features section">
        <div class="container">

            <div class="section-title" data-aos="fade-up">
                <h2>Alur Sistem</h2>
                <p>Cara Kerja Sistem</p>
            </div>

            <div class="row gy-4">

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="features-item">
                        <i class="bi bi-person-plus" style="color: #5fcf80;"></i>
                        <h3><a class="stretched-link">Registrasi Penumpang</a></h3>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="features-item">
                        <i class="bi bi-box-arrow-in-right" style="color: #5578ff;"></i>
                        <h3><a class="stretched-link">Login Sistem</a></h3>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="features-item">
                        <i class="bi bi-ticket-detailed" style="color: #e80368;"></i>
                        <h3><a class="stretched-link">Pemesanan Tiket</a></h3>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="features-item">
                        <i class="bi bi-check-circle" style="color: #29cc61;"></i>
                        <h3><a class="stretched-link">Status Pemesanan</a></h3>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <section class="section trainers-index">
        <div class="container">

            <div class="section-title" data-aos="fade-up">
                <h2>Pengguna</h2>
                <p>Role dalam Sistem</p>
            </div>

            <div class="row">

                <div class="col-lg-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                    <div class="member">
                        <div class="member-content">
                            <h4>Super Admin</h4>
                            <span>Pengelola Utama</span>
                            <p>Mengelola akun pengguna dan mengatur akses utama pada sistem.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
                    <div class="member">
                        <div class="member-content">
                            <h4>Admin</h4>
                            <span>Operasional</span>
                            <p>Mengelola data kapal, rute, jadwal keberangkatan, dan pemesanan tiket.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="300">
                    <div class="member">
                        <div class="member-content">
                            <h4>Pimpinan</h4>
                            <span>Monitoring</span>
                            <p>Melihat laporan, hasil optimasi, dan pemanfaatan kapasitas kapal.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="400">
                    <div class="member">
                        <div class="member-content">
                            <h4>Penumpang</h4>
                            <span>Pengguna Layanan</span>
                            <p>Melakukan registrasi, pemesanan tiket, dan melihat status pemesanan.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <section class="section light-background">
        <div class="container" data-aos="fade-up">

            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h3>Mulai Gunakan Sistem Sekarang</h3>
                    <p>
                        Buat akun penumpang untuk melakukan pemesanan tiket kapal ferry secara online
                        dan pantau status pemesanan Anda melalui dashboard.
                    </p>

                    @auth
                        <a href="{{ route('user.dashboard') }}" class="btn btn-success rounded-pill px-4">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('user.login') }}" class="btn btn-success rounded-pill px-4 me-2">
                            Login
                        </a>
                        <a href="{{ route('user.register') }}" class="btn btn-outline-success rounded-pill px-4">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>

        </div>
    </section>

</main>

@include('user.partials.footer')

<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
</a>

<div id="preloader"></div>

<script src="{{ asset('Mentor-1.0.0/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('Mentor-1.0.0/assets/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('Mentor-1.0.0/assets/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('Mentor-1.0.0/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('Mentor-1.0.0/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('Mentor-1.0.0/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('Mentor-1.0.0/assets/js/main.js') }}"></script>

</body>
</html>