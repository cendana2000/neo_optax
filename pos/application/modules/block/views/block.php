<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLOCK PAGE | POS-PTPIS</title>
    <meta name="description" content="No subheader example" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
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
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/custom/bootstrap-star-rating/css/star-rating.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/custom/bootstrap-star-rating/themes/krajee-fas/theme.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/custom/bootstrap-colorpicker/package/dist/css/bootstrap-colorpicker.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/leaflet/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/leaflet/leaflet.fullscreen/Control.FullScreen.css" />
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/media/logo.png" />


    <!-- End Sec CSS Library -->

    <!-- Custom CSS -->
    <style>
        :root {
            /* Color */
            --color2: #0e9f68;
            --color2-darken: #17845a;
            --color1: #5d78ff;
            --color1-darken: #3c50b5;

            /* Height */
            --container-height: 100%;

            /* Font Size */
            --label-checkout: 13px;
        }

        html,
        body {
            height: 100%;
            overflow: auto;
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

        /* .btn-circle {
            width: 30px !important;
            height: 30px !important;
            text-align: center !important;
            padding: 6px 0 !important;
            font-size: 12px !important;
            line-height: 1.42 !important;
            border-radius: 15px !important;
        } */

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
<script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-star-rating/js/star-rating.js"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-star-rating/themes/krajee-fas/theme.js"></script>
<script src="<?= base_url(); ?>assets/plugins/custom/bootstrap-colorpicker/package/dist/js/bootstrap-colorpicker.js"></script>
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

<body class="error error-6 d-flex flex-row-fluid bgi-size-cover bgi-position-center" style="background-image: url(<?= base_url('assets/media/error/bg6.jpg'); ?>)">
    <h1 class=" error-title font-weight-boldest text-white mb-12" style="margin-top: 12rem; text-align: center; font-size: 100px;">Oops...</h1>
    <p class="display-4 font-weight-bold text-white" style="text-align: center;">Aplikasi POS tidak dapat dibuka di dua tab sekaligus</p>
</body>

</html>