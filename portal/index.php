<?php
$env = parse_ini_file(__DIR__ . '/.env');

$MONITORING_URL = $env['MONITORING_URL'] ?? '';
$POS_URL        = $env['POS_URL'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Portal Aplikasi OPTAX</title>
  <meta content="Portal Aplikasi Monitoring Pajak Online dan Aplikasi Kasir (POS) untuk Pemerintah Daerah." name="description">
  <meta content="pajak online, pos, pemerintah daerah, bapenda, pad" name="keywords">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,600,600i,700,700i|Inter:300,400,500,600,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <!-- Icon -->
  <link rel="shortcut icon" href="img/icon_title.png" />

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img alt="Logo" style="max-width: 550px; max-height: 100px; vertical-align: middle;" src="img/logo_optax_1/logo_optax_1.png" />
      </a>
      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="#beranda" class="active">Beranda</a></li>
          <li><a href="#slideshow">Galeri</a></li>
          <li><a href="#features">Fitur</a></li>
        </ul>
      </nav><!-- .navbar -->
    </div>
  </header><!-- End Header -->

  <section id="beranda" class="d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center" data-aos="fade-up" data-aos-delay="100">
          <h1 class="display-3 fw-bolder text-white">Transformasi Digital <span class="highlight-text">Pajak Daerah</span></h1>
          <h2 class="fw-light text-white mb-5">Tingkatkan Pendapatan Asli Daerah (PAD) melalui platform Optimalisasi Monitoring Pajak dan Aplikasi Kasir (POS) yang terintegrasi, transparan, dan realtime.</h2>
          <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center gap-4">
            <a href="<?= $MONITORING_URL; ?>" class="btn btn-pos shadow-lg">
              <i class="bi bi-graph-up-arrow me-2"></i>
              OPTAX
            </a>
            <a href="<?= $POS_URL; ?>" class="btn btn-monitoring shadow-lg">
              <i class="bi bi-receipt-cutoff me-2"></i>
              Aplikasi Kasir (POS)
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <main id="main">
    <section id="slideshow" class="slideshow">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Galeri Aplikasi</h2>
          <p>Cuplikan tampilan dari Aplikasi OPTAX yang intuitif dan kaya akan fitur. Dirancang untuk memudahkan pemantauan dan analisis data pajak secara efisien.</p>
        </div>

        <div id="appCarousel" class="carousel slide shadow-lg" data-bs-ride="carousel">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#appCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#appCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#appCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
          </div>
          <div class="carousel-inner rounded-3">
            <div class="carousel-item active">
              <!-- <img src="https://placehold.co/1200x600/3498db/ffffff?text=Dashboard+Utama+Realisasi+Pajak" class="d-block w-100" alt="Dashboard Utama"> -->
              <img src="img/dashboard_monitoring_1.PNG" class="d-block w-100" alt="Dashboard Utama">
              <div class="carousel-caption d-none d-md-block">
                <h5>Dashboard Analitik Realtime</h5>
                <p>Pantau semua realisasi pendapatan pajak dari berbagai sektor dalam satu layar.</p>
              </div>
            </div>
            <div class="carousel-item">
              <!-- <img src="https://placehold.co/1200x600/2ecc71/ffffff?text=Laporan+Transaksi+Harian+Wajib+Pajak" class="d-block w-100" alt="Laporan Transaksi">\ -->
              <img src="img/transaksi_wp.PNG" class="d-block w-100" alt="Dashboard Utama">
              <div class="carousel-caption d-none d-md-block">
                <h5>Detail Transaksi Wajib Pajak</h5>
                <p>Lihat detail transaksi harian dari setiap Wajib Pajak untuk transparansi maksimal.</p>
              </div>
            </div>
            <div class="carousel-item">
              <!-- <img src="https://placehold.co/1200x600/e74c3c/ffffff?text=Aplikasi+Point+of+Sales(POS)" class="d-block w-100" alt="Peta Potensi Pajak"> -->
              <img src="img/kasir.PNG" class="d-block w-100" alt="Dashboard Utama">
              <div class="carousel-caption d-none d-md-block">
                <h5>Aplikasi Point of Sales(POS)</h5>
                <p>Dapatkan Aplikasi Point of Sales secara gratis yang disediakan oleh Pemerintah Daerah.</p>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#appCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#appCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </section><!-- End Slideshow Section -->

    <!-- ======= Features Section ======= -->
    <section id="features" class="features section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Fitur Unggulan</h2>
          <p>Solusi kami dirancang dengan fitur-fitur terbaik untuk menjawab tantangan pengelolaan pajak modern.</p>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-clock-history"></i></div>
              <h4>Data Realtime</a></h4>
              <p>Dapatkan data transaksi terbaru langsung dari Wajib Pajak tanpa jeda waktu.</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-shield-check"></i></div>
              <h4>Cegah Kebocoran</h4>
              <p>Minimalkan potensi kebocoran pendapatan dengan sistem yang terverifikasi dan transparan.</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="300">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-file-earmark-bar-graph"></i></div>
              <h4>Laporan Otomatis</h4>
              <p>Hasilkan laporan harian, bulanan, atau tahunan secara otomatis dengan sekali klik.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="400">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-cloud-arrow-down"></i></div>
              <h4>Berbasis Cloud</h4>
              <p>Akses data kapan saja dan di mana saja dengan aman melalui teknologi cloud terpercaya.</p>
            </div>
          </div>
        </div>
      </div>
    </section><!-- End Features Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="container">
      <div class="container mt-4">
        <div class="copyright">
          &copy; Copyright <strong><span>PT Cendana Teknika Utama</span></strong>. All Rights Reserved
        </div>
      </div>
  </footer><!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true
    });
  </script>

  <script>
    /**
     * Template Name: Logis
     * Template URL: https://bootstrapmade.com/logis-bootstrap-logistics-website-template/
     * Updated: Jun 02 2024 with Bootstrap v5.3.3
     * Author: BootstrapMade.com
     * License: https://bootstrapmade.com/license/
     */
    document.addEventListener('DOMContentLoaded', () => {
      "use strict";

      /**
       * Preloader
       */
      const preloader = document.querySelector('#preloader');
      if (preloader) {
        window.addEventListener('load', () => {
          preloader.remove();
        });
      }

      /**
       * Sticky header on scroll
       */
      const selectHeader = document.querySelector('#header');
      if (selectHeader) {
        document.addEventListener('scroll', () => {
          window.scrollY > 100 ? selectHeader.classList.add('sticked') : selectHeader.classList.remove('sticked');
        });
      }

      /**
       * Mobile nav toggle
       */
      const mobileNavShow = document.querySelector('.mobile-nav-show');
      const mobileNavHide = document.querySelector('.mobile-nav-hide');

      document.querySelectorAll('.mobile-nav-toggle').forEach(el => {
        el.addEventListener('click', function(event) {
          event.preventDefault();
          mobileNavToogle();
        })
      });

      function mobileNavToogle() {
        document.querySelector('body').classList.toggle('mobile-nav-active');
        mobileNavShow.classList.toggle('d-none');
        mobileNavHide.classList.toggle('d-none');
      }

      /**
       * Hide mobile nav on same-page/hash links
       */
      document.querySelectorAll('#navbar a').forEach(navbarlink => {

        if (!navbarlink.hash) return;

        let section = document.querySelector(navbarlink.hash);
        if (!section) return;

        navbarlink.addEventListener('click', () => {
          if (document.querySelector('.mobile-nav-active')) {
            mobileNavToogle();
          }
        });

      });

      /**
       * Toggle mobile nav dropdowns
       */
      const navDropdowns = document.querySelectorAll('.navbar .dropdown > a');

      navDropdowns.forEach(el => {
        el.addEventListener('click', function(event) {
          if (document.querySelector('.mobile-nav-active')) {
            event.preventDefault();
            this.classList.toggle('active');
            this.nextElementSibling.classList.toggle('dropdown-active');

            let dropDownIndicator = this.querySelector('.dropdown-indicator');
            dropDownIndicator.classList.toggle('bi-chevron-up');
            dropDownIndicator.classList.toggle('bi-chevron-down');
          }
        })
      });

      /**
       * Scroll top button
       */
      const scrollTop = document.querySelector('.scroll-top');
      if (scrollTop) {
        const togglescrollTop = function() {
          window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
        }
        window.addEventListener('load', togglescrollTop);
        document.addEventListener('scroll', togglescrollTop);
        scrollTop.addEventListener('click', window.scrollTo({
          top: 0,
          behavior: 'smooth'
        }));
      }
    });
  </script>

</body>

</html>