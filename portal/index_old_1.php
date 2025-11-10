<?php
include("core/functions.php");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Pajak Daerah</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets_custom/css/style.css">
  <style>
    /* Hero section gradient */
    .hero-section {
      min-height: 90vh;
      background: linear-gradient(135deg, #004e92, #000428);
      padding: 80px 0;
    }

    /* Portal buttons */
    .btn-portal {
      border-radius: 50px;
      font-size: 1.2rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .btn-portal:hover {
      transform: scale(1.1);
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);
    }

    /* Feature cards */
    .feature-card:hover {
      transform: translateY(-10px);
      transition: all 0.3s ease-in-out;
    }

    /* CTA section */
    .cta-section {
      background: linear-gradient(135deg, #1d2671, #c33764);
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-3">
    <div class="container">
      <a class="navbar-brand fw-bold fs-3" href="#">💼 Portal Pajak Daerah</a>
    </div>
  </nav>

  <!-- Hero -->
  <section class="hero-section text-white text-center d-flex align-items-center">
    <div class="container">
      <h1 class="fw-bold display-4 mb-3">Monitoring Pajak Online & Realtime</h1>
      <p class="lead mb-5">Satu pintu digital untuk pajak daerah dan POS modern.</p>

      <div class="d-flex justify-content-center gap-4">
        <a href="http://10.10.9.7:3131/74/monitoringpajak" class="btn btn-lg btn-light fw-bold px-5 shadow-lg btn-portal">
          <i class="bi bi-graph-up"></i> Monitoring Pajak
        </a>
        <a href="pos.php" class="btn btn-lg btn-warning fw-bold px-5 shadow-lg btn-portal">
          <i class="bi bi-cart-check"></i> Aplikasi POS
        </a>
      </div>
    </div>
  </section>

  <!-- Features -->
  <section class="py-5 bg-light">
    <div class="container">
      <h2 class="text-center fw-bold mb-5">Fitur Utama</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card feature-card h-100 text-center p-4 shadow-sm">
            <i class="bi bi-bar-chart-line fs-1 text-primary mb-3"></i>
            <h5 class="fw-bold">Grafik Realtime</h5>
            <p class="text-muted">Pantau pemasukan pajak secara langsung melalui grafik interaktif.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card feature-card h-100 text-center p-4 shadow-sm">
            <i class="bi bi-shield-lock fs-1 text-success mb-3"></i>
            <h5 class="fw-bold">Data Aman</h5>
            <p class="text-muted">Sistem terenkripsi untuk menjaga keamanan data wajib pajak.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card feature-card h-100 text-center p-4 shadow-sm">
            <i class="bi bi-laptop fs-1 text-warning mb-3"></i>
            <h5 class="fw-bold">Akses Multi-Device</h5>
            <p class="text-muted">Portal dapat diakses dari laptop, tablet, maupun smartphone.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Slideshow -->
  <section class="py-5">
    <div class="container">
      <h2 class="text-center fw-bold mb-4">Cuplikan Aplikasi</h2>
      <div id="portalCarousel" class="carousel slide shadow-lg rounded" data-bs-ride="carousel">
        <div class="carousel-inner rounded">
          <div class="carousel-item active">
            <img src="assets/img/slide1.png" class="d-block w-100" alt="Dashboard Pajak">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
              <h5>Dashboard Pajak</h5>
              <p>Pantau ringkasan penerimaan pajak setiap hari.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="assets/img/slide2.png" class="d-block w-100" alt="Grafik Pajak">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
              <h5>Grafik Realtime</h5>
              <p>Lihat tren pajak daerah berdasarkan sektor usaha.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="assets/img/slide3.png" class="d-block w-100" alt="POS Terintegrasi">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
              <h5>Aplikasi POS</h5>
              <p>Catat transaksi usaha dengan sistem POS terintegrasi.</p>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#portalCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#portalCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section py-5 text-center text-white">
    <div class="container">
      <h2 class="fw-bold mb-4">Siap Meningkatkan Transparansi Pajak?</h2>
      <p class="mb-4">Gunakan portal ini untuk mempermudah pengawasan dan pelaporan pajak daerah.</p>
      <a href="monitoring.php" class="btn btn-lg btn-light fw-bold px-5 shadow-lg">Mulai Sekarang</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-secondary text-white text-center py-3 mt-5">
    <p class="mb-0">&copy; <?= date('Y'); ?> Pemerintah Daerah. Semua Hak Dilindungi.</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    splide();

    function splide() {
      var splide = new Splide(".splide", {
        arrows: false,
        autoplay: true,
        type: "loop",
      }).mount();
    }

    document.addEventListener("DOMContentLoaded", () => {
      console.log("Portal Pajak Daerah Loaded ✅");
    });
  </script>
  <script src="https://kit.fontawesome.com/a81368914c.js"></script>
  <script src="./app.js"></script>
</body>

</html>