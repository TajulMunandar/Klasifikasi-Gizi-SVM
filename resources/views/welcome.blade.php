<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title> Klasifikasi Gizi - SVM</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('main/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('main/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('main/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('main/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('main/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('main/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('main/assets/css/main.css') }}" rel="stylesheet">

</head>

<body class="index-page">

    <header id="header" class="header sticky-top">



        <div class="branding d-flex align-items-center">

            <div class="container position-relative d-flex align-items-center justify-content-between">
                <a href="/" class="logo d-flex align-items-center me-auto">
                    <!-- Uncomment the line below if you also wish to use an image logo -->
                    <!-- <img src="assets/img/logo.png" alt=""> -->
                    <h1 class="sitename"> Klasifikasi Gizi SVM</h1>
                </a>

                <nav id="navmenu" class="navmenu">
                    <ul>
                    </ul>
                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav>

                <a class="cta-btn d-none d-sm-block" href="/login">Login</a>

            </div>

        </div>

    </header>

    <main class="main">
        <!-- Hero Section -->
        <section id="hero" class="hero section light-background">
            <img src="{{ asset('main/assets/img/hero-bg.jpg') }}" alt="" data-aos="fade-in">
            <div class="container position-relative">
                <div class="welcome position-relative" data-aos="fade-down" data-aos-delay="100">
                    <h2>Klasifikasi Status Gizi Anak</h2>
                    <p>Sistem klasifikasi status gizi anak menggunakan algoritma SVM (Support Vector Machine)</p>
                </div><!-- End Welcome -->

                <div class="content row gy-4">
                    <div class="col-lg-4 d-flex align-items-stretch">
                        <div class="why-box" data-aos="zoom-out" data-aos-delay="200">
                            <h3>Mengapa Menggunakan SVM?</h3>
                            <p>
                                SVM digunakan untuk mengklasifikasikan status gizi anak berdasarkan data antropometri.
                                Algoritma ini efektif untuk dataset kecil hingga sedang dan memberikan hasil klasifikasi
                                yang akurat.
                            </p>
                        </div>
                    </div><!-- End Why Box -->

                    <div class="col-lg-8 d-flex align-items-stretch">
                        <div class="d-flex flex-column justify-content-center">
                            <div class="row gy-4">

                                <!-- Total kampung -->
                                <div class="col-xl-4 d-flex align-items-stretch">
                                    <div class="icon-box" data-aos="zoom-out" data-aos-delay="300">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <h3>Total Kampung Terklasifikasi</h3>
                                        <p>{{ $totalKampung ?? '-' }} kampung memiliki data klasifikasi gizi anak.</p>
                                    </div>
                                </div>

                                <!-- Kampung terbanyak -->
                                <div class="col-xl-4 d-flex align-items-stretch">
                                    <div class="icon-box" data-aos="zoom-out" data-aos-delay="400">
                                        <i class="bi bi-people-fill"></i>
                                        <h4>Kampung Terbanyak</h4>
                                        <h4>Kampung dengan Jumlah Klasifikasi Terbanyak</h4>
                                        <p><strong>{{ $kampungTerbanyak->desa ?? '-' }}</strong> dengan total
                                            <strong>{{ $kampungTerbanyak->jumlah ?? '-' }}</strong> klasifikasi anak.
                                        </p>
                                    </div>
                                </div>

                                <!-- Daftar kampung -->
                                <div class="col-xl-4 d-flex align-items-stretch">
                                    <div class="icon-box" data-aos="zoom-out" data-aos-delay="500">
                                        <i class="bi bi-list-ul"></i>
                                        <h4>Daftar Kampung Terklasifikasi</h4>
                                        <div style="max-height: 200px; overflow-y: auto;">
                                            <ul class="mb-0 ps-3">
                                                @foreach ($listKampung as $desa)
                                                    <li>{{ $loop->iteration }}. {{ $desa }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div><!-- End  Content-->

            </div>
        </section><!-- /Hero Section -->


    </main>


    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('main/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('main/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('main/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('main/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('main/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('main/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('main/assets/js/main.js') }}"></script>

</body>

</html>
