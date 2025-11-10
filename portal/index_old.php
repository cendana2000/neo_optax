<?php
include("core/functions.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Primary Meta Tags -->
  <title>Aplikasi POS dan PERSADA - Bapenda Kota Malang</title>
  <meta name="title" content="Aplikasi POS Dan Monitoring Pajak - Bapenda Kota Malang" />
  <meta name="description" content="With Meta Tags you can edit and experiment with your content then preview how your webpage will look on Google, Facebook, Twitter and more!" />
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://persada.malangkota.go.id/" />
  <meta property="og:title" content="Aplikasi POS Dan Monitoring Pajak - Bapenda Kota Malang" />
  <meta property="og:description" content="With Meta Tags you can edit and experiment with your content then preview how your webpage will look on Google, Facebook, Twitter and more!" />
  <meta property="og:image" content="https://persada.malangkota.go.id/img/metaimage.png" />
  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://persada.malangkota.go.id/" />
  <meta property="twitter:title" content="Aplikasi POS Dan Monitoring Pajak - Bapenda Kota Malang" />
  <meta property="twitter:description" content="With Meta Tags you can edit and experiment with your content then preview how your webpage will look on Google, Facebook, Twitter and more!" />
  <meta property="twitter:image" content="https://persada.malangkota.go.id/img/metaimage.png" />

  <link rel="shortcut icon" href="img/logo.png" />
  <link rel="stylesheet" href="style.css" />

  <!-- TypeWritter -->
  <link rel="stylesheet" href="js/typewritter/css/typewritter.css" />
  <script src="js/typewritter/js/typewritter.js"></script>
  <!--  -->
  <script src="js/splide/dist/js/splide.min.js"></script>
  <link rel="stylesheet" href="js/splide/dist/css/splide.min.css" />
  <!-- Jquery -->
  <script src="jquery.js"></script>

  <style>
    .splide__progress__bar {
      height: 3px;
      background: #ccc;
    }
  </style>
</head>

<body>
  <main>
    <div class="big-wrapper light">
      <img src="./img/shape.png" alt="" class="shape" />
      <header>
        <div class="container">
          <div class="logo">
            <img src="./img/logo.png" alt="Logo" />
            <h3>Pemerintah Kota Malang</h3>
          </div>

          <div class="links">
            <ul>
              <li>
                <a href="<?= base_url(); ?>" target="_blank" style="font-weight: 500">Bapenda Malang</a>
              </li>
              <li>
                <a href="https://malangkota.go.id/" target="_blank" style="font-weight: 500">Pemkot malang</a>
              </li>
              <!-- <li><a href="#">Testimonials</a></li> -->
              <!-- <li><a href="#" class="btn">Sign up</a></li> -->
            </ul>
          </div>

          <div class="overlay"></div>

          <div class="hamburger-menu">
            <div class="bar"></div>
          </div>
        </div>
      </header>

      <div class="showcase-area">
        <div class="container">
          <div class="left">
            <div class="big-title">
              <h3>Kelola Pajak Dan Manajemen Penjualan</h3>
              <h3 class="typewrite" data-period="2000" data-type='[ "Lebih Mudah.", "Lebih Cepat.", "Lebih Baik."]'>
                <span class="wrap"></span>
              </h3>
            </div>
            <p class="text">
              Klik tombol dibawah untuk membuka atau melakukan pendaftaran
              Aplikasi <span style="font-weight: 700">POS</span> dan
              <span style="font-weight: 700">PERSADA</span>.
            </p>
            <div class="cta">
              <a href="<?= base_url('pos'); ?>" class="btn" style="margin-right: 10px; font-weight: 500">POS</a>
              <a href="<?= base_url('backoffice'); ?>" class="btn" style="font-weight: 500">PERSADA</a>
            </div>
          </div>

          <div class="right">
            <div class="splide" style="width: 100%">
              <div class="splide__track">
                <ul class="splide__list">
                  <li class="splide__slide">
                    <img src="./img/slide01.png" alt="Person Image" class="person" style="width: 85%; margin-bottom: 40px" />
                  </li>
                  <li class="splide__slide">
                    <img src="./img/slide02.png" alt="Person Image" class="person" style="width: 85%; margin-bottom: 40px" />
                  </li>
                  <li class="splide__slide">
                    <img src="./img/slide03.png" alt="Person Image" class="person" style="width: 85%; margin-bottom: 40px" />
                  </li>
                </ul>
              </div>
            </div>
            <!-- <img src="./img/person.png" alt="Person Image" class="person" /> -->
          </div>
        </div>
      </div>
      <div class="bottom-area">
        <div class="container">
          <button class="toggle-btn">
            <i class="far fa-moon"></i>
            <i class="far fa-sun"></i>
          </button>
        </div>
      </div>
    </div>
  </main>

  <script>
    splide();

    function splide() {
      var splide = new Splide(".splide", {
        arrows: false,
        autoplay: true,
        type: "loop",
      }).mount();
    }
  </script>
  <script src="https://kit.fontawesome.com/a81368914c.js"></script>
  <script src="./app.js"></script>
</body>

</html>