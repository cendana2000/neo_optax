<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KASIR POS-PTPIS</title>
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

<body>
    <form class="kt-form" action="javascript:savingKasirBaru('form-penjualanbarang')" name="form-penjualanbarang" id="form-penjualanbarang">
        <div class="m-4">
            <div class="row d-flex justify-content-center">
                <div class="col-8">
                    <!-- Card Menu -->
                    <div class="card mt-3" style="height: var(--container-height) !important;">
                        <!-- Card Header -->
                        <div class="p-3 m-2" style="background-color: white;">
                            <div class="row">
                                <div class="col-6">
                                    <div class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                        <a href="#" class="btn btn-primary btn-fixed-height font-weight-bold px-2 px-lg-5 mr-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <spa class="fas fa-bars"></spa>
                                        </a>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                            <!--begin::Navigation-->
                                            <ul class="navi navi-hover">
                                                <li class="navi-header font-weight-bold py-4">
                                                    <span class="font-size-lg">Administarator:</span>
                                                </li>
                                                <li class="navi-separator mb-3 opacity-70"></li>
                                                <li class="navi-item">
                                                    <a href="javascript:void(0)" onclick="onTables()" class="navi-link">
                                                        <span class="navi-text text-dark">
                                                            <span class=" fas fa-money-check pr-3"></span>Data Penjualan
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="https://pos-ptpis.sekawanmedia.co.id/dev/pos/" class="navi-link">
                                                        <span class="navi-text text-dark">
                                                            <span class="flaticon2-browser-1 pr-3"></span>Halaman Administrator
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>

                                    <img alt="Logo" src="assets/media/logo.png" />

                                </div>
                                <!-- <div class="col-6">
                                    <button class="btn btn-icon btn-circle btn-success float-right ml-3" style="background-color: var(--color1);"><i class="fas fa-search"></i></button>
                                    <input type="text" class="form-control form-rounded col-5 h-75 float-right h-100" placeholder="Search item here..." style="background-color: #eaeaea; color: #636060; font-size: 12px;">
                                </div> -->
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body " style="max-height: 100%;">
                            <div class="row d-flex justify-content-start" style="overflow-y: auto; height: 230px;" id="menuHandler"></div>
                        </div>
                        <div class="card-body " style="max-height: 100%;">
                            <div class="row d-flex justify-content-start" style="overflow-y: auto; height: 200px">
                                <table class="table table-borderless borderless" id="tableCheckout" style="border: 0;">
                                    <tr style="background-color: var(--color1); color:white; height: 10px;">
                                        <td style="width: 30% !important;">NAMA</td>
                                        <td style="width: 20% !important;">SATUAN</td>
                                        <td style="width: 20% !important;">KUANTITAS</td>
                                        <td style="width: 25% !important;">HARGA</td>
                                        <td style="width: 20% !important;">AKSI</td>
                                    </tr>
                                    <tbody id="orderHandler" style="font-size: 14px;">
                                        <tr id="orderHolder">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
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
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class=" col-4 mw-100">
                    <div class="card mt-3" style="height: var(--container-height);">
                        <!-- Card Header -->
                        <div class="card-header" style="background: var(--color1); color:white; border-radius: calc(0.42rem - 1px) calc(0.42rem - 1px) 0 0; ">
                            <div class="row">
                                <div class="col-6">
                                    <span class="text-light">Total</span>
                                    <br>
                                    <i class="text-light" style="font-size: 10px;">*Yang dibayar</i>
                                </div>
                                <div class="col-6">
                                    <span class="float-right" style="font-size: 20px; font-weight:100;">
                                        Rp.<span class="text-light" id="pembelian_total_bayar_display">0</span>
                                        <input type="hidden" id="penjualan_total_harga">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class=" form-group row">
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
                                            <div class=" form-group row">
                                                <label for="discount" class="col-5 col-form-label" style="font-size: var(--label-checkout);">Diskon(%)</label>
                                                <div class="col-sm-7  float-left">
                                                    <input type="text" onkeyup="countDiscount()" value="0" class="form-control mw-8" value="0" id="penjualan_total_potongan_persen" placeholder="%">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="pajak" class="col-sm-5 col-form-label" style="font-size: var(--label-checkout);">Pajak</label>
                                                <div class="col-sm-7  float-left">
                                                    <input type="text" class="form-control mw-8" onkeyup="countDiscount()" value="0" placeholder="%" id="penjualan_pajak_persen">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class=" form-group row">
                                                <label for="discount" class="col-sm-5 col-form-label" style="font-size: var(--label-checkout);">Metode</label>
                                                <div class="col-sm-7  float-left">
                                                    <select name="penjualan_metode" id="penjualan_metode" class="form-control h-100" onchange="handlePembayaran()">
                                                        <option value="" selected>-Pilih Metode-</option>
                                                        <option value="C">Tunai</option>
                                                        <option value="B">Bank</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class=" form-group row" id="divBank">
                                                <label for="discount" class="col-sm-5 col-form-label" style="font-size: var(--label-checkout);">Bank</label>
                                                <div class="col-sm-7  float-left">
                                                    <select name="penjualan_bank" id="penjualan_bank" class="form-control">
                                                        <option value="">-Pilih Bank-</option>
                                                        <option value="Mandiri">Mandiri</option>
                                                        <option value="BCA">BCA</option>
                                                        <option value="BRI">BRI</option>
                                                        <option value="BNI">BNI</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="pajak" class="col-sm-5 col-form-label" style="font-size: var(--label-checkout);">Bayar</label>
                                                <div class="col-sm-7  float-left">
                                                    <input type="text" id="penjualan_total_bayar" name="penjualan_total_bayar" value="0" required onkeyup="countKembalian()" class="form-control mw-8" id="pajak">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="pajak" class="col-sm-5 col-form-label" style="font-size: var(--label-checkout);">Kembalian</label>
                                                <div class="col-sm-7  float-left">
                                                    <input type="text" id="pembelian_total_kembalian" readonly style="background-color: #eaeaea;" class="form-control mw-8">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" card-footer text-muted">
                            <div class="mt-3" style="font-size: 14px; font-weight: bold;">
                                <div class="row">
                                    <div class="col-4">
                                        <!-- <button type="reset" class="btn btn-danger btn-lg float-left"><i class="fas fa-sync-alt"></i> Reset</button> -->
                                        <button type="reset" class="btn btn-lg btn-danger float-right" onclick="onReset()"><i class="fa fa-redo" id="btnReset"></i>Reset</button>

                                    </div>
                                    <div class="col-8">
                                        <button type="button" onclick="savingKasirBaru()" class="btn btn-lg btn-success float-right"><i class="fas fa-cash-register"></i> Bayar</button>
                                        <div class="form-group float-right mt-3 pr-3">
                                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                                <input type="checkbox" class="cetak" id="cetak" name="cetak" checked="checked" value="1" onchange="setChecked(this)"> <i class="flaticon2-print"></i> Cetak
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Modal Section -->
    <div class="modal bd-example-modal-xl fade" id="modal-penjualan" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="width: 1250px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="padding-top: 10px;" id="exampleModalCenterTitle">Faktur Penjualan</h5>
                    <button type="button" class="btn btn-default close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times" style="font-size: 15px;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-checkable table-condensed" id="table-penjualanbarang">
                        <thead>
                            <tr>
                                <th style="width:5%;">No.</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th>Potongan</th>
                                <th>Grand Total</th>
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
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="printArea" style="display: none;"></div>


    <!-- <div class="container mw-100 mt-3" style="height: 100%;">
        <div class="row  d-flex justify-content-center">
            <div class="col-8">
                <div class="card">
                    <button class="btn btn-primary btn-lg rounded-0"><i class="fas fa-cash-register"></i></button>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Sec JS Library -->
    <script src="<?= base_url(); ?>assets/plugins/custom/jqueryNumber/jquery.number.min.js"></script>
    <!-- End Sec JS Library -->


    <?php load_view('javascript') ?>
</body>

</html>