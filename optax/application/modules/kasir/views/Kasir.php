<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
	<base href="">
	<meta charset="utf-8" />
	<title><?php echo $this->config->item('app_title') ?></title>
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


	<!--end::Layout Themes-->
	<!-- <link rel="shortcut icon" href="<?= base_url(); ?>assets/media/logos/favicon-timah.ico" /> -->
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
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->


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



<body id="kt_body" class="header-mobile-fixed subheader-enabled aside-enabled aside-fixed aside-secondary-enabled page-loading">
	<!--begin::Main-->
	<!--begin::Header Mobile-->
	<div id="kt_header_mobile" class="header-mobile">
		<!--begin::Logo-->
		<a href="index.html">
			<img alt="Logo" src="<?= base_url(); ?>assets/media/logos/logo-letter-2.png" class="logo-default max-h-30px" />
		</a>
		<!--end::Logo-->
		<!--begin::Toolbar-->
		<div class="d-flex align-items-center">
			<button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
				<span></span>
			</button>
		</div>
		<!--end::Toolbar-->
	</div>
	<!--end::Header Mobile-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="d-flex flex-row flex-column-fluid page">
			<!--begin::Wrapper-->
			<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
				<!--begin::Subheader-->
				<div class="subheader py-3 py-lg-8 subheader-transparent bg-dark" id="kt_subheader">
					<div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap" style="max-width: 1300px;">
						<!--begin::Info-->
						<div class="d-flex align-items-center">
							<div class="d-flex align-items-baseline flex-wrap">
								<h2 class="d-flex font-weight-bold my-1 text-light">POS PTPIS - KASIR</h2>
							</div>
						</div>
						<div class="d-flex align-items-center flex-wrap">
							<!-- User Bar -->
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
						</div>
					</div>
				</div>
				<form class="kt-form" action="javascript:save('form-penjualanbarang')" name="form-penjualanbarang" id="form-penjualanbarang">
					<!--begin::Container-->
					<div class="container mt-3" style="max-width: 1300px;">
						<div class="row">
							<div class="col-xl-8">
								<div class="card card-custom gutter-b" style="height: 96.5%;">
									<div class="card-header border-0 pt-5">
										<!-- <button type="button" class="btn btn-outline-danger" style="margin-left: 10px; height: 10%; margin-top: 7px;"><i class="fa fa-plus" onclick="addBarang()" style="text-align: center;"></i>Tambah</button> -->

										<button type="button" class="btn btn-outline-info" onclick="addBarang()" style="margin-left: 10px; height: 60%; margin-top: 7px;"><i class="fa fa-plus" style="text-align: center;"></i>Tambah</button>

										<div class="card-toolbar" style="margin-bottom: 20px; width: 85%;">
											<div class="input-group" style="width: 100%;">
												<div class="input-group-prepend"><span class="input-group-text"><i class="la la-barcode"></i></span></div>
												<input type="text" class="form-control" placeholder="Scan barcode disini!!" />
											</div>
										</div>
									</div>
									<div class="card-body pt-2 pb-0 mt-n3">
										<div class="overflow-auto " style="display: inline-block; height: 90%;">
											<table class="table table-bordered table-hover text-light" id="table-detail_barang" style="border: 1;">
												<thead style="background-color: #8950fc;">
													<tr>
														<th style="border:0;">Barang</th>
														<th style="border: 0">Satuan</th>
														<th style="border: 0">Harga</th>
														<th style="border: 0">Qty</th>
														<th style="border: 0">Disc %</th>
														<th style="border: 0">Jumlah</th>
														<th style="border: 0">Aksi</th>
													</tr>
												</thead>
												<tbody>
													<tr class="barang_1">
														<td scope="row">
															<input type="text" style="display:none" class="form-control" name="penjualan_detail_id[1]" id="penjualan_detail_id_1">
															<input type="text" style="display:none" class="form-control" name="penjualan_detail_jenis_barang[1]" id="penjualan_detail_jenis_barang_1">
															<select class="form-control barang_id" name="penjualan_detail_barang_id[1]" id="penjualan_detail_barang_id_1" data-id="1" onchange="setSatuan('1')" style="white-space: nowrap"></select>
														</td>
														<td>
															<select class="form-control" name="penjualan_detail_satuan[1]" id="penjualan_detail_satuan_1" onchange="getHarga('1')"></select>
															<input type="text" style="display:none" class="form-control" name="penjualan_detail_satuan_kode[1]" id="penjualan_detail_satuan_kode_1">
														</td>
														<td><input class="form-control number" type="text" name="penjualan_detail_harga[1]" id="penjualan_detail_harga_1" readonly=""></td>
														<td>
															<input class="form-control qty" type="number" name="penjualan_detail_qty[1]" id="penjualan_detail_qty_1" onkeyup="countRow('1')" onchange="countRow('1')" value="1">
															<input class="form-control number" type="text" style="display:none" name="penjualan_detail_qty_barang[1]" id="penjualan_detail_qty_barang_1">
															<!-- tambahan -->
															<input class="form-control number" type="text" style="display:none" name="penjualan_detail_harga_beli[1]" id="penjualan_detail_harga_beli_1">
															<input class="form-control number" type="text" style="display:none" name="penjualan_detail_hpp[1]" id="penjualan_detail_hpp_1">
															<!--end tambahan -->
														</td>
														<td>
															<input class="form-control disc" type="text" name="penjualan_detail_potongan_persen[1]" id="penjualan_detail_potongan_persen_1" onkeyup="countRow('1')">
															<input class="form-control number" type="text" style="display:none" name="penjualan_detail_potongan[1]" id="penjualan_detail_potongan_1">
														</td>
														<td><input class="form-control number jumlah" type="text" name="penjualan_detail_subtotal[1]" id="penjualan_detail_subtotal_1" readonly=""></td>
														<td style="text-align: center;"><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus">
																<span class="la la-trash"></span> Hapus</a></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="card-footer">
										<table class="table">
											<tbody>
												<tr>
													<td class="text-right" style="border: 0;vertical-align: middle; width: 15%;">Total Item</td>
													<td class="" style="border: 0;vertical-align: middle;"><input class="form-control" type="text" id="penjualan_total_item" name="penjualan_total_item" readonly=""></td>
													<td class="text-right" style="border: 0;vertical-align: middle; width: 15%;">Total Qty</td>
													<td class="" style="border: 0;vertical-align: middle;"><input class="form-control number" type="number" id="penjualan_total_qty" name="penjualan_total_qty" readonly=""></td>
													<td class="text-right" style="border: 0;vertical-align: middle;  width: 15%;">Sub Total</td>
													<td style="border: 0;vertical-align: middle;"><input class="form-control number" type="text" id="penjualan_total_harga" name="penjualan_total_harga" style="background: #eaeaea;" readonly=""></td>
													<td style="border: 0;vertical-align: middle;"></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-xl-4">
								<div class="card card-custom bg-radial-gradient-danger gutter-b card-stretch" style="height: 96.5%;">
									<div class="card-header border-0 py-5">
										<div class="kt-aside-secondary__content-head" style="font-size: 16px; color: #eaeaea;">
											Total<i style="font-size: 10px;left: 25px;position: absolute;top: 45px; color: #eaeaea">*Yang dibayar</i> <span id="v_penjualan_total_grand" style="position: absolute; right: 26px; font-size: 2.5rem;">0</span>
										</div>
									</div>
									<div class="card-body d-flex bg-white card-rounded flex-grow-1">
										<div class="form-group">
											<label>No. Transaksi:</label>
											<!-- <a href="javascript:;" onclick="clearAnggota()" class="badge badge-warning pull-right" id="clear-anggota" style="display: none"><i class="la la-close"></i> Clear Anggota</a> -->
											<div class="input-group">
												<input type="text" style="display:none" name="penjualan_id" id="penjualan_id" class="form-control" placeholder="AUTO.#00" readonly="">
												<input type="text" style="display:none" id="penjualan_kasir" name="penjualan_kasir" value="<?php echo $kasir['kasir_kode']; ?>">
												<!-- <input type="text" style="width:65%" id="kode_anggota" placeholder="Masukkan kode anggota" class="form-control" autocomplete="off"> -->
											</div>
											<input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" style="background-color: #eaeaea;" placeholder="AUTO.#00" readonly="">
											<div class="form-group mt-3">
												<label>Customer:</label>
												<select onchange="console.log($(this).val())" class="form-control" id="pos_penjualan_customer_id" name="pos_penjualan_customer_id">
													<option value="">-Pilih Customer-</option>
												</select>
											</div>
											<hr>
											<div class="form-group">
												<div class="form-group">
													<label>Disc./Potongan</label>
													<div class="input-group">
														<div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
															<input type="text" class="form-control number" id="penjualan_total_potongan_persen" name="penjualan_total_potongan_persen" onkeyup="countDiskon()">
															<span class="kt-input-icon__icon kt-input-icon__icon--right">
																<span>%</span>
															</span>
														</div>
														<input type="text" class="form-control number" id="penjualan_total_potongan" name="penjualan_total_potongan" onkeyup="countDiskon()">
													</div>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-4 col-form-label">Metode</label>
												<div class="col-8">
													<select name="penjualan_metode" id="penjualan_metode" class="form-control" onchange="handlePembayaran()">
														<option value="" selected>-Pilih Metode-</option>
														<option value="C">Tunai</option>
														<option value="B">Bank</option>
													</select>
												</div>
											</div>
											<div class="form-group row" id="divBank">
												<label class="col-4 col-form-label">Bank</label>
												<div class="col-8">
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
												<label class="col-4 col-form-label">Jumlah Bayar</label>
												<div class="col-8">
													<input class="form-control bayar-tunai" id="penjualan_total_bayar_tunai" name="penjualan_total_bayar_tunai" onkeyup="setBayar(this)" type="number" />
												</div>
											</div>
											<div class="form-group row">
												<label class="col-4 col-form-label">Kembalian</label>
												<div class="col-8">
													<input class="form-control number" id="penjualan_total_kembalian" name="penjualan_total_kembalian" readonly type="number" />
												</div>
											</div>
											<hr>
											<div class="form-group">
												<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
													<input type="checkbox" class="cetak" id="cetak" name="cetak" checked="checked" value="1" onchange="setChecked(this)"> <i class="flaticon2-print"></i> Cetak
													<span></span>
												</label>
												<button style="margin-left: 37px;" type="button" onclick="save()" class="btn btn-primary"><i class="flaticon2-check-mark"></i> Bayar</button>
												<!-- <code>End</code> -->
												<button type="reset" class="btn btn-secondary" onclick="setReset()"><i class="flaticon2-cancel-music"></i> Batal</button>
											</div>
											<!--end::Stats-->
										</div>
										<!--end::Body-->
									</div>
									<!--end::Mixed Widget 4-->
								</div>
							</div>
						</div>


						<!-- Toast Section -->
						<div class="toast toast-custom toast-fill fade hide toast-bottom toast-right" role="alert" aria-live="assertive" aria-atomic="true" id="kt_toast_4">
							<div class="toast-header" style="background: #ff995cfa;font-size: 1.2em;font-weight: bold; ">
								<i class="toast-icon flaticon2-attention kt-font-success"></i>
								<span class="toast-title">Warning!</span>
								<button type="button" class="toast-close" data-dismiss="toast" aria-label="Close">
									<i class="la la-close"></i>
								</button>
							</div>
							<div class="toast-body" style="font-size: 1.2em; padding: 10px 15px">
								Barang yang dijual melebihi stok yang tersedia, stok <b id="stok-warning">0</b> .
							</div>
						</div>

						<!-- Modal Section -->
						<div class="modal bd-example-modal-xl fade" id="modal-penjualan" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="width: 1250px">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" style="padding-top: 10px;" id="exampleModalCenterTitle">Faktur Penjualan</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<form id="tanggal" name="tanggal" action="javascript:init_table()">
											<div class="kt-portlet__body">
												<div class="form-group row col-12" style="margin-bottom: 0">
													<label class="col-1 col-form-label">Tanggal</label>
													<input type="date" name="awal_tanggal" id="awal_tanggal" class="col-3 form-control" value="<?php echo date('Y-m-d') ?>">
													<label class="col-1 col-form-label" style="text-align: center;">S/d</label>
													<input type="date" name="akhir_tanggal" id="akhir_tanggal" class="col-3 form-control" value="<?php echo date('Y-m-d') ?>">
													<p style="padding-left: 10px;"><button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i>Tampilkan</button></p>
												</div>
											</div>
										</form>
									</div>
									<div class="modal-body">
										<table class="table table-striped table-checkable table-condensed" id="table-penjualanbarang">
											<thead>
												<tr>
													<th style="width:5%;">No.</th>
													<th>Kode</th>
													<th>Tanggal</th>
													<th>Nasabah</th>
													<th>No Nasabah</th>
													<th>Sub Total</th>
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
													<th>Nasabah</th>
													<th>No Nasabah</th>
													<th>Sub Total</th>
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

						<div class="d-flex flex-column-fluid mt-3"></div>
					</div>
				</form>
			</div>
		</div>

		<div id="printArea" style="display: none;"></div>

		<div id="kt_scrolltop" class="scrolltop">
			<span class="svg-icon">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
						<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>
		</div>
		<script src="<?= base_url(); ?>assets/plugins/custom/jqueryNumber/jquery.number.min.js"></script>
		<?php load_view('javascript') ?>
</body>


<!--end::Body-->

</html>