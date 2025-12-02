<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KASIR POS - <?= $this->session->userdata('toko_nama') ?></title>
    <meta name="description" content="No subheader example" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <!--end::Fonts-->
    <!--begin::Page Vendors Styles(used by this page)-->
    <link href="<?= base_url(); ?>assets/plugins/custom/fullcalendar/fullcalendar.bundle.css?v=7.0.5" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="<?= base_url(); ?>assets/plugins/global/plugins.bundle.css?v=7.0.5" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.5" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/style.bundle.css?v=7.0.5" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="<?= base_url(); ?>assets/css/themes/layout/header/base/light.css?v=7.0.5" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/themes/layout/header/menu/light.css?v=7.0.5" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/themes/layout/brand/dark.css?v=7.0.5" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/themes/layout/aside/dark.css?v=7.0.5" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/plugins/custom/jstree/jstree.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/plugins/custom/bootstrap-fileinput-master/css/fileinput.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/plugins/custom/bootstrap-fileinput-master/themes/explorer-fas/theme.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/plugins/custom/lightbox2/lightbox.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/pages/wizard/wizard-1.css?v=7.0.5" rel="stylesheet" type="text/css" />
    <!-- custom css -->
    <link href="<?= base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/leaflet/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/leaflet/leaflet.fullscreen/Control.FullScreen.css" />
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/media/icon_title.png" />
    <!-- End Sec CSS Library -->

    <!-- Custom CSS -->
    <style>
        :root {
            /* Color */
            --color2: #0e9f68;
            --color2-darken: #17845a;
            --color1: #5d78ff;
            --color1-darken: #3c50b5;
            --color-primary: #0C8CE9;
            --color-body: #2B2F38;
            --color-white: #FFFFFF;
            --color-warning: #F07613;
            --color-danger: #ED5575;

            /* Height */
            --container-height: 100%;

            /* Font Size */
            --label-checkout: 13px;
        }

        html,
        body {
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            color: var(--color-body);
            background: var(--color-white);
        }

        #listHandler,
        #receipts,
        #form-receipt {
            scrollbar-color: var(--color-primary) #EBEDF3;
            scrollbar-width: thin;
        }

        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            background-color: #EBEDF3;
        }

        ::-webkit-scrollbar {
            width: 8px;
            background-color: #EBEDF3;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
            background-color: var(--color-primary);
        }

        .font-weight-black {
            font-weight: 700 !important;
        }

        .text-body {
            color: var(--color-body) !important;
        }

        .text-primary {
            color: var(--color-primary) !important;
        }

        .text-warning {
            color: var(--color-warning) !important;
        }

        .text-danger {
            color: var(--color-danger) !important;
        }

        .text-success {
            color: var(--color-success) !important;
        }

        .text-light-gray {
            color: var(--color-light-gray) !important;
        }

        .text-dark-gray {
            color: var(--color-dark-gray) !important;
        }

        .bg-primary {
            background-color: var(--color-primary) !important;
        }

        .bg-danger {
            background-color: var(--color-danger) !important;
            border-color: var(--color-danger) !important;
        }

        .btn-primary {
            background-color: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            color: var(--color-white) !important;
        }

        .btn-primary:hover {
            background-color: #0b71ba !important;
            border-color: #0b71ba !important;
        }

        .btn-reset {
            background-color: #0C8CE933 !important;
            color: var(--color-primary) !important;
        }

        .btn-reset:hover {
            background-color: #0C8CE955 !important;
        }

        .btn-quantity {
            background: linear-gradient(180deg, rgba(237, 85, 117, 1) 0%, rgba(240, 118, 19, 1) 100%);
            padding: 1px;
            color: var(--color-warning);
            border-radius: 0.62rem;
        }

        .btn-quantity>div {
            width: 22px;
            height: 22px;
        }

        .btn-quantity:hover>div {
            background: linear-gradient(180deg, rgba(237, 85, 117, 1) 0%, rgba(240, 118, 19, 1) 100%);
            color: var(--color-white);
        }

        .btn-quantity[data-quantity="delete"] {
            background: var(--color-danger);
            color: var(--color-white);
            box-sizing: initial;
            width: 22px;
            height: 22px;
        }

        .btn-quantity[data-quantity="delete"]:hover {
            background: #e83f62;
        }

        .btn-outline-primary {
            border-color: var(--color-primary) !important;
            color: var(--color-primary) !important;
        }

        .btn.btn-outline-primary:not(:disabled):not(.disabled).active,
        .btn.btn-outline-primary:hover:not(.btn-text):not(:disabled):not(.disabled),
        .btn.btn-outline-primary:focus:not(.btn-text),
        .show>.btn.btn-outline-primary.dropdown-toggle,
        .show .btn.btn-outline-primary.btn-dropdown .btn.btn-outline-primary.focus:not(.btn-text) {
            background-color: var(--color-primary) !important;
            color: var(--color-white) !important;
        }

        .btn.btn-outline-primary:not(:disabled):not(.disabled).active:hover {
            background-color: #0a79c9 !important;
            border-color: #0a79c9 !important;
        }

        .text-hover-primary:hover {
            color: var(--color-primary) !important;
        }

        nav,
        .navbar-custome {
            background-color: var(--color1);
            height: 10%;
        }

        nav,
        div-nav {
            background-color: white;
        }

        .form-rounded {
            border-radius: 1rem;
        }

        a:link {
            text-decoration: none !important;
        }

        .card {
            box-shadow: 0 0 10px 0 rgba(100, 100, 100, 0.26);
        }

        .customLink {
            color: var(--color1);
        }

        .customLink:hover {
            color: var(--color1-darken);
        }

        .cardCustom {
            background-color: var(--color1);
            color: white;
        }

        .cardCustom:hover {
            background-color: var(--color1-darken);
            color: white;
        }

        .borderless td,
        .borderless th {
            border: none;
        }

        .dropdown-toggle.nav-link:after,
        .dropdown-toggle.btn:after {
            content: none;
        }

        .dropdown-menu.dropdown-menu-filter {
            height: 240px;
            overflow-y: scroll;
        }

        .navi .navi-item .navi-link.active {
            background-color: #F3F6F9;
        }

        #filter-by-category .navi .navi-item .navi-link.active {
            background-color: var(--color-primary);
        }

        #filter-by-category .navi .navi-item .navi-link.active .navi-text {
            color: var(--color-white);
        }

        #filter-by-category .navi .navi-item .navi-link:hover {
            background-color: var(--color-primary);
        }

        #filter-by-category .navi .navi-item .navi-link:hover .navi-text {
            color: var(--color-white);
        }

        #listHandler {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 15px;
            max-height: 65vh;
        }

        #listHandler>.card {
            height: fit-content;
        }

        #listHandler>.card:last-child {
            margin-bottom: 10px;
        }

        label.print-checkbox {
            display: block;
            position: relative;
            padding-left: 35px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        label.print-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: absolute;
            left: 0;
            height: 25px;
            width: 25px;
            border-radius: 5px;
            background-color: #eee;
            border: 3px solid var(--color-primary);
        }

        label.print-checkbox:hover input~.checkmark {
            background-color: #0C8CE933;
        }

        label.print-checkbox input:checked~.checkmark {
            background-color: var(--color-primary);
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        label.print-checkbox input:checked~.checkmark:after {
            display: block;
        }

        label.print-checkbox .checkmark:after {
            top: 2px;
            left: 7px;
            width: 7px;
            height: 12px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        #modal-rental .modal-header .close {
            color: #565657ff !important;
            /* abu tua (ikon terlihat di background putih) */
            opacity: 1 !important;
            background: transparent !important;
            border: none !important;
            position: absolute;
            right: 25px;
            top: 25px;
            z-index: 10;
        }

        #modal-rental .modal-header .close:hover {
            color: #565657ff !important;
            transform: scale(1.1);
        }

        #modal-rental .modal-header .close i {
            font-size: 16px !important;
            color: inherit !important;
        }

        @media only screen and (min-width: 500px) {
            #listHandler {
                grid-template-columns: repeat(2, 1fr);
            }

            .fill-grid-column {
                grid-column: 1 / 3;
            }
        }

        @media only screen and (min-width: 992px) {
            body {
                max-height: 100vh;
            }

            #listHandler {
                grid-template-columns: repeat(3, 1fr);
            }

            .fill-grid-column {
                grid-column: 1 / 4;
            }
        }

        @media only screen and (min-width: 1200px) {
            #listHandler {
                grid-template-columns: repeat(4, 1fr);
            }

            .fill-grid-column {
                grid-column: 1 / 5;
            }
        }
    </style>
</head>

<script>
    var KTAppSettings = {
        "breakpoints": {
            "sm": 576,
            "md": 768,
            "lg": 992,
            "xl": 1200,
            "xxl": 1400
        },
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#3699FF",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#E4E6EF",
                    "dark": "#181C32"
                },
                "light": {
                    "white": "#ffffff",
                    "primary": "#E1F0FF",
                    "secondary": "#EBEDF3",
                    "success": "#C9F7F5",
                    "info": "#EEE5FF",
                    "warning": "#FFF4DE",
                    "danger": "#FFE2E5",
                    "light": "#F3F6F9",
                    "dark": "#D6D6E0"
                },
                "inverse": {
                    "white": "#ffffff",
                    "primary": "#ffffff",
                    "secondary": "#3F4254",
                    "success": "#ffffff",
                    "info": "#ffffff",
                    "warning": "#ffffff",
                    "danger": "#ffffff",
                    "light": "#464E5F",
                    "dark": "#ffffff"
                }
            },
            "gray": {
                "gray-100": "#F3F6F9",
                "gray-200": "#EBEDF3",
                "gray-300": "#E4E6EF",
                "gray-400": "#D1D3E0",
                "gray-500": "#B5B5C3",
                "gray-600": "#7E8299",
                "gray-700": "#5E6278",
                "gray-800": "#3F4254",
                "gray-900": "#181C32"
            }
        },
        "font-family": "Poppins"
    };
</script>
<!-- Sec JS Library -->

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/jqueryNumber/jquery.number.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/global/plugins.bundle.js?v=7.0.5"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.5"></script>
<script src="<?= base_url(); ?>assets/js/scripts.bundle.js?v=7.0.5"></script>
<!-- <script src="<?= base_url(); ?>assets/js/custom.js"></script> -->
<script src="<?= base_url(); ?>assets/js/pages/id_ID_FormValidation.js"></script>

<!--end::Global Theme Bundle-->
<!--begin::Page Vendors(used by this page)-->
<script src="<?= base_url(); ?>assets/plugins/custom/fullcalendar/fullcalendar.bundle.js?v=7.0.5"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/jstree/jstree.bundle.js"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-fileinput-master/js/fileinput.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-fileinput-master/js/plugins/piexif.js"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-fileinput-master/js/plugins/sortable.js"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-fileinput-master/js/locales/id.js"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-fileinput-master/themes/fas/theme.js"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-fileinput-master/themes/explorer-fas/theme.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/lightbox2/lightbox.js"></script>
<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="<?= base_url(); ?>assets/js/pages/crud/datatables/advanced/column-rendering.js?v=7.0.5"></script>
<!-- <script src="<?= base_url(); ?>assets/helper/js.cookie.js"></script> -->
<script src="<?php echo base_url(); ?>assets/helper/js.cookie.min.js"></script>

<script>
    BASE_URL = "<?php echo base_url() ?>index.php/";
    BASE_URL_NO_INDEX = "<?php echo base_url() ?>";
    BASE_ASSETS = "<?php echo base_url() ?>assets/";
    var base_ws = 'wss://api-fms.pttimah.co.id/api/socket';
    var base_ses = 'https://api-fms.pttimah.co.id/api/session';
    var hostnow = new URL(window.location);
    if (hostnow.hostname == 'api-fms.pttimah.co.id') {
        base_ws = 'ws://api-fms.pttimah.co.id:8081/api/socket';
        base_ses = 'http://api-fms.pttimah.co.id:8081/api/session'
    }
    TRACCAR = {
        'token': "<?= $this->config->item('token_traccar'); ?>",
        'url_session': base_ses,
        'url_ws': base_ws
    };
    WS_FMS = null;
</script>
<script src="<?php echo base_url(); ?>assets/helper/fnReloadAjax.js"></script>
<!-- <script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-star-rating/js/star-rating.js"></script> -->
<!-- <script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-star-rating/themes/krajee-fas/theme.js"></script> -->
<!-- <script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-colorpicker/package/dist/js/bootstrap-colorpicker.js"></script> -->
<script src="<?= base_url(); ?>assets/leaflet/leaflet.js"></script>
<script src="<?= base_url(); ?>assets/leaflet/AnimatedMarker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="<?= base_url(); ?>assets/leaflet/leaflet.fullscreen/Control.FullScreen.js"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/moment/moment-with-locale.js"></script>
<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="<?= base_url(); ?>assets/js/pages/widgets.js?v=7.0.5"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-messaging.js"></script>
<script src="<?php echo base_url(); ?>assets/helper/helper.js?v=1.0.9"></script>
<script src="<?php echo base_url(); ?>assets/helper/FCM.js" type="text/javascript"></script>
<!--end::Page Scripts-->

<body>
    <?php
    if ($this->session->userdata('user_id') == null) {
        redirect(base_url());
    }
    ?>
    <div class="h-100">
        <form class="kt-form h-100" action="javascript:savingKasirBaru('form-penjualanbarang')" name="form-penjualanbarang" id="form-penjualanbarang">
            <div class="row d-flex justify-content-center h-100">
                <div class="col-12 col-lg-8 p-0">
                    <!-- Card Menu -->
                    <div class="card rounded-0 shadow-none border-0" style="height: var(--container-height) !important;">
                        <!-- Card Header -->
                        <div class="py-8 card-header rounded-0 border-0 d-flex align-items-center" style="background-color: white; gap: 15px;">
                            <div class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                <a href="#" class="btn btn-primary btn-fixed-height font-weight-bold px-5 mr-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="fas fa-bars"></span>
                                </a>
                                <div class="dropdown-menu p-0 m-0 dropdown-menu-md">
                                    <ul class="navi navi-hover">
                                        <li class="navi-header font-weight-bold py-4">
                                            <span class="font-size-lg">Administarator:</span>
                                        </li>
                                        <li class="navi-separator mb-3 opacity-70"></li>
                                        <li class="navi-item">
                                            <a href="javascript:void(0)" onclick="onPengaturanCetak()" class="navi-link">
                                                <span class="navi-text text-dark">
                                                    <span class="flaticon2-gear pr-3"></span>Pengaturan Cetak
                                                </span>
                                            </a>
                                        </li>
                                        <!-- <li class="navi-item">
                                            <a href="javascript:void(0)" onclick="onPengaturanJasa()" class="navi-link">
                                                <span class="navi-text text-dark">
                                                    <span class="flaticon-price-tag pr-3"></span>Setup Jasa
                                                </span>
                                            </a>
                                        </li> -->
                                        <li class="navi-item">
                                            <a href="javascript:void(0)" onclick="onRental()" class="navi-link">
                                                <span class="navi-text text-dark">
                                                    <span class="flaticon2-list-3 pr-3"></span>Data Rental
                                                </span>
                                            </a>
                                        </li>
                                        <li class="navi-item">
                                            <a href="<?= base_url() ?>" class="navi-link">
                                                <span class="navi-text text-dark">
                                                    <span class="flaticon2-browser-1 pr-3"></span>Halaman Administrator
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <h1 class="font-weight-bolder h2 mb-0"><?= $this->session->userdata('toko_nama'); ?></h1>
                            <!-- <select class="form-control" style="border-radius: 1.5rem !important;" id="category" name="category" onchange="searchMenu()">
                                        </select> -->
                            <!-- <div class="row">
                                <div class="col-12 col-lg-2 d-flex flex-row  align-items-center justify-content-between justify-content-lg-start mb-5 mb-lg-0">
                                    <div class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                        <a href="#" class="btn btn-primary btn-fixed-height font-weight-bold px-5 mr-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="fas fa-bars"></span>
                                        </a>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                            <ul class="navi navi-hover">
                                                <li class="navi-header font-weight-bold py-4">
                                                    <span class="font-size-lg">Administarator:</span>
                                                </li>
                                                <li class="navi-separator mb-3 opacity-70"></li>
                                                <li class="navi-item">
                                                    <a href="javascript:void(0)" onclick="onPengaturanCetak()" class="navi-link">
                                                        <span class="navi-text text-dark">
                                                            <span class="flaticon2-gear pr-3"></span>Pengaturan Cetak
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="javascript:void(0)" onclick="onTables()" class="navi-link">
                                                        <span class="navi-text text-dark">
                                                            <span class="flaticon2-list-3 pr-3"></span>Data Penjualan
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="javascript:void(0)" onclick="onRental()" class="navi-link">
                                                        <span class="navi-text text-dark">
                                                            <span class="flaticon2-list-3 pr-3"></span>Data Rental
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="<?= base_url() ?>" class="navi-link">
                                                        <span class="navi-text text-dark">
                                                            <span class="flaticon2-browser-1 pr-3"></span>Halaman Administrator
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <img alt="Logo" width="35" src="<?= base_url(); ?>assets/media/logo.png" />
                                </div>
                                <div class="col-12 col-lg-5 d-flex flex-row justify-content-between mx-0 mx-lg-0 pb-5 pb-lg-0 px-3 px-lg-0">
                                    <div class="w-100 mr-4">
                                        <select class="form-control" style="border-radius: 1.5rem !important;" id="order_by" name="order_by" onchange="searchMenu()" placeholder="Pilih Berdasarkan">
                                            <option></option>
                                            <optgroup label="Default / Semua Jenis">
                                                <option value="semua">Semua</option>
                                                <option value="total_jual">Terlaris</option>
                                                <option value="barang_created_at">Terbaru</option>
                                                <option value="barang_stok">Stok Terbesar</option>
                                                <option value="barang_stok_kecil">Stok Terkecil</option>
                                                <option value="tersedia">Stok Tersedia</option>
                                            </optgroup>
                                            <optgroup label="Rental">
                                                <option value="rental_booking">Booking</option>
                                                <option value="rental_booked">Booked</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="w-100">
                                        <select class="form-control" style="border-radius: 1.5rem !important;" id="category" name="category" onchange="searchMenu()">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5 d-flex flex-row ml-auto">
                                    <input type="text" class="form-control px-6" placeholder="Search item here..." style="border-radius: 1.5rem;background-color: #eaeaea; color: #636060; font-size: 12px;" id="valSearch">
                                    <button class="btn btn-icon btn-circle btn-primary ml-2 p-3" data-toggle="tooltip" title="Cari" style="background-color: var(--color1);" id="btn-search-menu" onclick="searchMenu()"><i class="fas fa-search"></i></button>
                                    
                                    <button onclick="searchMenu()" class="btn btn-icon btn-circle btn-success ml-2 p-3"><i class="fas fa-sync-alt text-center"></i></button>
                                </div>
                            </div> -->
                        </div>
                        <!-- Card Body -->
                        <div class="card-body py-0 d-flex flex-column">
                            <div class="d-flex flex-stack flex-column flex-md-row">
                                <div class="card-toolbar w-100 d-flex align-items-center">
                                    <div class="dropdown dropdown-inline">
                                        <a href="#" class="btn text-dark font-weight-bold h4 d-flex align-items-center w-100 mb-0 dropdown-toggle p-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="gap: 10px;">
                                            <span class="material-icons text-primary">
                                                filter_list
                                            </span>
                                            Cari Kamar
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-filter">
                                            <input type="text" value="semua" id="order_by" name="order_by" hidden>
                                            <!--begin::Navigation-->
                                            <ul class="navi navi-hover">
                                                <li class="navi-header pb-2">
                                                    <span class="text-primary text-uppercase font-weight-bold font-size-sm">Default / Semua Jenis</span>
                                                </li>
                                                <li class="navi-item">
                                                    <button class="navi-link active btn w-100 text-left" data-order="semua" onclick="changeOrderBy(this)">
                                                        <span class="navi-text">Semua</span>
                                                    </button>
                                                </li>
                                                <!-- <li class="navi-item">
                                                    <button class="navi-link btn w-100 text-left" data-order="total_jual" onclick="changeOrderBy(this)">
                                                        <span class="navi-text">Terlaris</span>
                                                    </button>
                                                </li>
                                                <li class="navi-item">
                                                    <button class="navi-link btn w-100 text-left" data-order="barang_created_at" onclick="changeOrderBy(this)">
                                                        <span class="navi-text">Terbaru</span>
                                                    </button>
                                                </li>
                                                <li class="navi-item">
                                                    <button class="navi-link btn w-100 text-left" data-order="barang_stok" onclick="changeOrderBy(this)">
                                                        <span class="navi-text">Stok Terbesar</span>
                                                    </button>
                                                </li>
                                                <li class="navi-item">
                                                    <button class="navi-link btn w-100 text-left" data-order="barang_stok_kecil" onclick="changeOrderBy(this)">
                                                        <span class="navi-text">Stok Terkecil</span>
                                                    </button>
                                                </li> 
                                                <li class="navi-item">
                                                    <button class="navi-link btn w-100 text-left" data-order="tersedia" onclick="changeOrderBy(this)">
                                                        <span class="navi-text">Stok Tersedia</span>
                                                    </button>
                                                </li> -->
                                                <li class="navi-header pb-2 mt-2">
                                                    <span class="text-primary text-uppercase font-weight-bold font-size-sm">Rental</span>
                                                </li>
                                                <li class="navi-item">
                                                    <button class="navi-link btn w-100 text-left" data-order="rental_booking" onclick="changeOrderBy(this)">
                                                        <span class="navi-text">Booking</span>
                                                    </button>
                                                </li>
                                                <li class="navi-item">
                                                    <button class="navi-link btn w-100 text-left" data-order="rental_booked" onclick="changeOrderBy(this)">
                                                        <span class="navi-text">Booked</span>
                                                    </button>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>
                                </div>
                                <div class="fw-bolder fs-3 text-primary d-flex flex-column flex-md-row w-100" style="gap: 10px;">
                                    <input type="search" name="valSearch" id="valSearch" placeholder="Cari Disini" class="border-0 py-4 px-5 w-100" style="background-color: #fafafa; border-radius: 6px" />
                                    <button class="btn btn-primary d-flex flex-center" onclick="searchMenu()">
                                        <span class="material-icons-outlined">
                                            search
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap py-5" id="filter-by-category" style="gap: 5px;"></div>
                            <div class="pb-4 pr-5" style="overflow-y: auto;" id="listHandler">

                            </div>
                            <!-- <div class="row d-flex justify-content-start flex-fill h-50" style="overflow-y: auto;">
                                <div class="table-responsive" style="padding: 0 !important;">
                                    <table class="table table-borderless borderless" id="tableOrder" style="border: 0;">
                                        <thead style="background-color: var(--color1); color:white; height: 10px;">
                                            <td style="width: 20% !important;">NAMA</td>
                                            <td style="width: 10% !important;">SATUAN</td>
                                            <td style="width: 20% !important;">HARGA</td>
                                            <td style="width: 20% !important;">KUANTITAS</td>
                                            <td style="width: 20% !important;">TOTAL</td>
                                            <td style="width: 10% !important;">AKSI</td>
                                        </thead>
                                        <tbody id="orderHandler" style="font-size: 14px;">
                                        </tbody>
                                    </table>
                                </div>
                            </div> -->
                        </div>

                        <!-- <div class="card-footer rounded-0">
                            <div class="row align-items-center">
                                <div class="col-6 col-lg-3 offset-lg-1">
                                    <div class="d-flex flex-lg-row flex-column">
                                        <label>Total Item</label>
                                        <input class="form-control" type="text" id="penjualan_total_item" name="penjualan_total_item" readonly="">
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="d-flex flex-lg-row flex-column">
                                        <label>Total Qty</label>
                                        <input class="form-control number" type="number" id="penjualan_total_qty" name="penjualan_total_qty" readonly="">
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="d-flex flex-lg-row flex-column">
                                        <label>Sub Total</label>
                                        <input class="form-control number" type="text" id="penjualan_total_harga" name="penjualan_total_harga" style="background: #eaeaea;" readonly="">
                                    </div>
                                </div>
                                <div class="col-6 col-lg-2">
                                    <div class="d-flex flex-lg-row flex-column">
                                        <label style="font-size: 12px;" class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" onchange="handleIncludePajak()" id="includePajak" />
                                            <span></span>&nbsp;
                                            Termasuk Pajak
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="text-right" style="border: 0;vertical-align: middle; width: 15%; font-size: 13px;">Total Item</td>
                                        <td class="" style="border: 0;vertical-align: middle;"><input class="form-control" type="text" id="penjualan_total_item" name="penjualan_total_item" readonly=""></td>
                                        <td class="text-right" style="border: 0;vertical-align: middle; width: 15%; font-size: 13px;">Total Qty</td>
                                        <td class="" style="border: 0;vertical-align: middle;"><input class="form-control number" type="number" id="penjualan_total_qty" name="penjualan_total_qty" readonly=""></td>
                                        <td class="text-right" style="border: 0;vertical-align: middle;  width: 15%; font-size: 13px;">Sub Total</td>
                                        <td style="border: 0;vertical-align: middle;"><input class="form-control number" type="text" id="penjualan_total_harga" name="penjualan_total_harga" style="background: #eaeaea;" readonly=""></td>
                                        <td style="border: 0;vertical-align: middle;"></td>
                                        <td style="border: 0;vertical-align: middle; width: 15%;">
                                            <label style="font-size: 12px;" class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                <input type="checkbox" onchange="handleIncludePajak()" id="includePajak" />
                                                <span></span>&nbsp;
                                                Termasuk Pajak
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> -->
                    </div>
                </div>

                <div class="col-12 col-lg-4 p-0">
                    <div class="card rounded-0 shadow-none" style="height: var(--container-height); border-left: 1px solid #EFEFEF;">
                        <!-- Card Header -->
                        <div class="card-header rounded-0 d-flex justify-content-between align-items-center py-5" style="height: 70px;">
                            <h2 class="text-primary mb-0 font-weight-bolder h4">Receipt Summary</h2>
                            <button type="reset" class="btn btn-reset font-weight-bold" onclick="onReset()">Clear All</button>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body py-0">
                            <div class="row h-100">
                                <div class="col-12 d-flex flex-column py-5" id="receipts" style="height: calc((100vh - 70px - 120px - 160px) / 2); overflow-y: auto; gap: 20px;"></div>
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-lg-row flex-column align-items-lg-center" style="max-width: 50%;">
                                        <label class="mb-0" style="font-size: 12px; width: 50%;">Sub Total</label>
                                        <input class="py-1 px-3 rounded number w-100 border-0" type="text" id="penjualan_total_harga" name="penjualan_total_harga" style="background: #eaeaea;" value="0" readonly="">
                                    </div>
                                    <div class="d-flex flex-lg-row flex-column">
                                        <label style="font-size: 12px;" class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary mb-0">
                                            <input type="checkbox" onchange="handleIncludePajak()" id="includePajak" />
                                            <span></span>&nbsp;
                                            Termasuk Pajak
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 py-5" id="form-receipt" style="height: calc((100vh - 70px - 120px) / 2); overflow-y: auto;">
                                    <div class=" form-group row">
                                        <label for="penjualan_id" class="col-5 col-form-label" style="font-size: var(--label-checkout);">Tanggal</label>
                                        <div class="col-sm-7  float-left">
                                            <input type="text" class="form-control datepickermt" id="penjualan_tuanggal" name="penjualan_tanggal">
                                            <!-- <input type="text" class="form-control datepickermt" id="penjualan_tanggal" name="penjualan_tanggal" readonly="readonly"> -->
                                        </div>
                                    </div>
                                    <div class=" form-group row" style="display: none;">
                                        <label for="penjualan_id" class="col-5 col-form-label" style="font-size: var(--label-checkout);">No Transaksi</label>
                                        <div class="col-sm-7  float-left">
                                            <div class="input-group">
                                                <input type="text" style="display:none" name="penjualan_id" id="penjualan_id" class="form-control" placeholder="AUTO.#00" readonly="">
                                                <input type="text" style="display:none" id="penjualan_kasir" name="penjualan_kasir" value="<?php echo $kasir['kasir_kode']; ?>">
                                            </div>
                                            <input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" style="background-color: #eaeaea;" placeholder="AUTO.#00" readonly="">
                                        </div>
                                    </div>
                                    <div class=" form-group row">
                                        <label for="pos_penjualan_customer_id" class="col-5 col-form-label" style="font-size: var(--label-checkout);">Customer</label>
                                        <div class="col-sm-7  float-left" id="customerDiv">
                                            <select name="pos_penjualan_customer_id" id="pos_penjualan_customer_id" class="form-control h-100"></select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pajak" class="col-sm-5 col-form-label" style="font-size: var(--label-checkout);">Biaya Jasa(%)</label>
                                        <div class="col-sm-7  float-left">
                                            <div class="input-group">
                                                <input type="text" id="penjualan_jasa" name="penjualan_jasa" value="0" onkeyup="countKembalian()" class="form-control mw-8">
                                                <input type="text" id="penjualan_jasa_nominal" name="penjualan_jasa_nominal" value="Rp. 0" class="form-control mw-8" readonly style="background-color: #eaeaea;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" form-group row" style="display: none;">
                                        <label for="discount" class="col-5 col-form-label" style="font-size: var(--label-checkout);">Diskon(%)</label>
                                        <div class="col-sm-7  float-left">
                                            <div class="input-group">
                                                <input type="text" onkeyup="countKembalian()" value="0" class="form-control mw-8" value="0" id="penjualan_total_potongan_persen" name="penjualan_total_potongan_persen" placeholder="%">
                                                <input type="text" value="Rp. 0" class="form-control mw-8" id="penjualan_total_potongan_nominal" name="penjualan_total_potongan_nominal" placeholder="Rp. 0" readonly style="background-color: #eaeaea;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pajak" class="col-sm-5 col-form-label" style="font-size: var(--label-checkout);">Pajak(%)</label>
                                        <div class="col-sm-7  float-left">
                                            <div class="input-group">
                                                <input type="text" class="form-control mw-8" value="<?= $this->session->userdata('global_pajak'); ?>" readonly style="background-color: #eaeaea;" placeholder="%" id="penjualan_pajak_persen" name="penjualan_pajak_persen">
                                                <input type="text" class="form-control mw-8" value="Rp. 0" readonly style="background-color: #eaeaea;" placeholder="Rp. 0" id="penjualan_pajak_nominal" name="penjualan_pajak_nominal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" form-group row">
                                        <label for="discount" class="col-sm-5 col-form-label" style="font-size: var(--label-checkout);">Metode</label>
                                        <div class="col-sm-7  float-left">
                                            <select name="penjualan_metode" id="penjualan_metode" class="form-control h-100" onchange="handlePembayaran()">
                                                <option value="B">Lunas</option>
                                                <option value="K">Kredit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <section>
                                        <div class=" form-group row" id="divBank" style="display: none;">
                                            <label for="discount" class="col-sm-5 col-form-label" style="font-size: var(--label-checkout);">Bank</label>
                                            <div class="col-sm-7  float-left">
                                                <select name="penjualan_bank" id="penjualan_bank" class="form-control">
                                                    <!--<option value="">-Pilih Bank-</option>
                                                    <option value="Mandiri">Mandiri</option>
                                                    <option value="BCA">BCA</option>
                                                    <option value="BRI">BRI</option>
                                                    <option value="BNI">BNI</option>-->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" id="divBayarBank" style="display: none;">
                                            <label class="col-5 col-form-label">Bayar Bank</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control manualNumber" name="penjualan_total_bayar_bank" id="penjualan_total_bayar_bank" value="0" onkeyup="sumBayar()">
                                            </div>
                                        </div>
                                        <div class="form-group row" id="divBayarTunai" style="display: none;">
                                            <label class="col-5 col-form-label">Bayar Tunai</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control manualNumber" name="penjualan_total_bayar_tunai" id="penjualan_total_bayar_tunai" value="0" onkeyup="sumBayar()">
                                            </div>
                                        </div>
                                        <div class="form-group row" id="divJatuhTempo">
                                            <label class="col-5 col-form-label">Jatuh Tempo</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control datepickermt" id="penjualan_jatuh_tempo" name="penjualan_jatuh_tempo">
                                            </div>
                                        </div>

                                    </section>
                                    <section id="bayarKembalian">
                                        <div class="form-group row">
                                            <label for="pajak" class="col-sm-5 col-form-label" style="font-size: var(--label-checkout);">Total Bayar</label>
                                            <div class="col-sm-7  float-left">
                                                <input type="text" class="form-control" name="penjualan_total_bayar" readonly style="background-color: #eaeaea;" value="0" id="penjualan_total_bayar">

                                                <!-- <input type="text" id="penjualan_total_bayar" name="penjualan_total_bayar" readonly value="0" required onchange="countKembalian()" class="form-control mw-8"> -->
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="pajak" class="col-sm-5 col-form-label" style="font-size: var(--label-checkout);">Kembalian</label>
                                            <div class="col-sm-7  float-left">
                                                <input type="text" value="0" id="penjualan_total_kembalian" name="penjualan_total_kembalian" readonly style="background-color: #eaeaea;" class="form-control mw-8">
                                            </div>
                                        </div>
                                    </section>
                                    <!-- <div class="form-group row mb-0" id="divBayarBank">
                                        <label class="col-5 col-form-label" for="penjualan_keterangan">Catatan</label>
                                        <div class="col-sm-7">
                                            <textarea class="form-control" placeholder="Tambahkan catatan..." rows="3" name="penjualan_keterangan" id="penjualan_keterangan"></textarea>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white text-muted rounded-0 border-0 pt-0 pb-5" style="height: 120px;">
                            <div style="font-size: 14px; font-weight: bold;">
                                <div class="row text-primary mb-8 rounded bg-white" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
                                    <input type="text" id="penjualan_total_item" name="penjualan_total_item" hidden>
                                    <input class="number" type="number" id="penjualan_total_qty" name="penjualan_total_qty" hidden>
                                    <h3 class="col-5 col-form-label font-weight-bolder h5 mb-0">Grand Total</h3>
                                    <div class="col-sm-7 col-form-label float-left font-weight-bolder h5">
                                        Rp. <span id="pembelian_total_bayar_display">0</span>,-
                                        <input class="number" type="text" id="penjualan_total_harga" name="penjualan_total_harga" hidden>
                                        <input type="hidden" id="penjualan_total_grand" name="penjualan_total_grand">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="font-weight-normal print-checkbox d-flex align-items-center mb-0">
                                        <input type="checkbox" class="cetak" id="cetak" name="cetak" checked="checked" value="1" onchange="setChecked(this)">
                                        <span class="btn btn-sm btn-reset font-weight-bold d-inline-flex align-items-center" style="gap: 10px;font-size: 1.2rem;">
                                            <span class="material-icons" style="font-size: 22px;">print</span>
                                            Print
                                        </span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <button type="button" onclick="savingKasirBaru()" class="btn btn-primary font-weight-bold" style="width: 30%">Bayar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- Modal Section -->
    <div class="modal bd-example-modal-xl fade" id="modal-penjualan" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="width: 1250px">
            <div class="modal-content" style="max-width: 96vw;">
                <div class="modal-header">
                    <h5 class="modal-title" style="padding-top: 10px;" id="exampleModalCenterTitle">Daftar Penjualan</h5>
                    <button type="button" class="btn btn-default close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times" style="font-size: 15px;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" style="max-width: 85vw;">
                        <table class="table table-striped table-checkable table-condensed" id="table-penjualanbarang">
                            <thead>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Kode</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th>Potongan</th>
                                    <th>Grand Total</th>
                                    <th>Platform</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Kode</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th>Potongan</th>
                                    <th>Grand Total</th>
                                    <th>Platform</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal bd-example-modal-xl fade" id="modal-rental" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="width: 1250px">
            <div class="modal-content" style="max-width: 96vw;">
                <div class="modal-header">
                    <h5 class="modal-title" style="padding-top: 10px;" id="exampleModalCenterTitle">Daftar Rental</h5>
                    <button type="button" class="btn btn-default close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times" style="font-size: 15px;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" style="max-width: 85vw;">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col">
                                    <label style="font-size: 12px;">Bulan :</label>
                                    <input type="month" class="form-control" name="bulan" id="bulan" value="<?php echo date('Y-m') ?>">
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="belum-lunas-tab" data-toggle="tab" data-target="#belum_lunas" type="button" role="tab" aria-controls="profile" aria-selected="false">Belum Lunas</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="lunas-tab" data-toggle="tab" data-target="#lunas" type="button" role="tab" aria-controls="lunas" aria-selected="true">Lunas</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-5" id="myTabContent">
                            <div class="tab-pane fade show active" id="belum_lunas" role="tabpanel" aria-labelledby="belum-lunas-tab">
                                <table class="table table-striped table-checkable table-condensed" id="table-rental-belum-lunas">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;">No.</th>
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th>Produk</th>
                                            <th>Customer</th>
                                            <th>Potongan</th>
                                            <th>Grand Total</th>
                                            <th>Platform</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="width:5%;">No.</th>
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th>Produk</th>
                                            <th>Customer</th>
                                            <th>Potongan</th>
                                            <th>Grand Total</th>
                                            <th>Platform</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="lunas" role="tabpanel" aria-labelledby="lunas-tab">
                                <table class="table table-striped table-checkable table-condensed" id="table-rental-lunas">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;">No.</th>
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th>Produk</th>
                                            <th>Customer</th>
                                            <th>Potongan</th>
                                            <th>Grand Total</th>
                                            <th>Platform</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="width:5%;">No.</th>
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th>Produk</th>
                                            <th>Customer</th>
                                            <th>Potongan</th>
                                            <th>Grand Total</th>
                                            <th>Platform</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-pengaturan-cetak" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="pengaturan_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="padding-top: 10px;" id="pengaturan_title">Pengaturan Cetak</h5>
                    <button type="button" class="btn btn-default close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times" style="font-size: 15px;"></i>
                    </button>
                </div>
                <form action="javascript:;" id="form-pegaturan-cetak">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tampilkan :</label>
                            <div class="checkbox-list">
                                <label class="checkbox">
                                    <input type="checkbox" name="settings_show" id="settings_show_pajak" value="pajak" />
                                    <span></span>
                                    Pajak
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="settings_show" id="settings_show_jasa" value="jasa" />
                                    <span></span>
                                    Jasa
                                </label>
                            </div>
                            <span class="form-text text-muted">Centang jika ingin menampilkan bidang ini pada struk</span>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button onclick="onSimpanPengaturanCetak()" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal jasa -->
    <div class="modal fade" id="modal-pengaturan-jasa" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="pengaturan_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document" style="width: 1250px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="padding-top: 10px;" id="pengaturan_title">Setup Jasa</h5>
                    <button type="button" class="btn btn-default close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times" style="font-size: 15px;"></i>
                    </button>
                </div>
                <form action="javascript:;" id="form-pegaturan-cetak">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Presentase jasa :</label>
                            <input type="text" class="form-control" id="setup_jasa">
                            <span class="form-text text-muted">Data presentase jasa yang disimpan akan otomasti ter-set saat aplikasi di jalankan</span>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button onclick="onSimpanPengaturanJasa()" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-add-customer" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="addcustomer_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="padding-top: 10px;" id="addcustomer_title">Tambah Customer</h5>
                    <button type="button" class="btn btn-default close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times" style="font-size: 15px;"></i>
                    </button>
                </div>
                <form action="javascript:onSaveCustomer()" method="post" id="form-customer" name="form-customer" autocomplete="off">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-left" for="customer_kode">Kode <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" name="customer_kode" class="form-control customer_kode" placeholder="Kode Customer" required minlength="2" maxlength="20" onchange="onChangeInvalid(this)">
                                <div class="invalid-feedback" id="alert_customer_kode">Bidang ini wajib diisi, mohon periksa kembali dan coba lagi.</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-left" for="customer_nama">Nama <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" name="customer_nama" class="form-control customer_nama" placeholder="Nama Customer" required minlength="2" maxlength="150" onchange="onChangeInvalid(this)">
                                <div class="invalid-feedback" id="alert_customer_nama">Bidang ini wajib diisi, mohon periksa kembali dan coba lagi.</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" onclick="onSaveCustomer()" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-pdf" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="nota_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="padding-top: 10px;" id="nota_title">Nota</h5>
                    <button type="button" class="btn btn-default close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times" style="font-size: 15px;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="kt-portlet__body" id="pdf-laporan">
                        <object data="" type="application/pdf" width="100%" height="500px"></object>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="printArea" style="display: none;"></div>

    <!-- Sec JS Library -->
    <script src="<?= base_url(); ?>assets/plugins/custom/jqueryNumber/jquery.number.min.js"></script>
    <!-- End Sec JS Library -->


    <?php load_view('javascript-rental') ?>
</body>

</html>