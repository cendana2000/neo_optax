<!DOCTYPE html>
<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 9 & VueJS Admin Dashboard Theme
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: https://1.envato.market/EA4JP
Renew Support: https://1.envato.market/EA4JP
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
	<base href="../../../../">
	<meta charset="utf-8" />
	<title>POS | Bypass</title>
	<meta name="description" content="POS Management System" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Page Custom Styles(used by this page)-->
	<link href="<?php echo base_url(); ?>assets/css/pages/login/login-4.css" rel="stylesheet" type="text/css" />
	<!--end::Page Custom Styles-->
	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/plugins/custom/aos/aos.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css" />
	<!--end::Global Theme Styles-->
	<!--end::Layout Themes-->
	<link rel="shortcut icon" href="<?= base_url(); ?>/assets/media/logo.png" />
	<style type="text/css" media="screen">
		.login.login-4 .login-aside {
			background: linear-gradient(147.04deg, #6b84dc 0.74%, #0d1a49 99.61%);
			width: 700px;
		}
	</style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Login-->
		<div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid wizard" id="kt_login">
			<!--begin::Content-->
			<div class="login-container d-flex flex-center flex-row flex-row-fluid order-2 order-lg-1 flex-row-fluid bg-white py-lg-0 pb-lg-0 pt-15 pb-12">
				<!--begin::Container-->
				<div class="login-content login-content-signup d-flex flex-column">
					<!--begin::Aside Top-->
					<div class="d-flex flex-column-auto flex-column px-10">
						<!--begin::Aside header-->
						<!-- <a href="#" class="login-logo pb-lg-4 pb-10">
							<img src="assets/media/logos/logo-4.png" class="max-h-70px" alt="" />
						</a> -->
						<!--end::Aside header-->
						<!--begin: Wizard Nav-->
						<div class="wizard-nav pt-5 pt-lg-10 pb-5">
							<!--begin::Wizard Steps-->
							<div class="wizard-steps d-flex flex-column flex-sm-row">
								<!--begin::Wizard Step 1 Nav-->
							</div>
							<!--end::Wizard Steps-->
						</div>
						<!--end: Wizard Nav-->
					</div>
					<!--end::Aside Top-->
					<!--begin::Signin-->
					<div class="login-form">
						<!--begin::Form-->
						<form class="form px-10" action="javascript:void(0)" novalidate="novalidate" id="kt_login_signin_form" autocomplete='off'>
							<!--begin: Wizard Step 1-->
							<div class="" data-wizard-type="step-content" data-wizard-state="current">
								<!--begin::Title-->
								<div class="pb-5">
									<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Welcome to POS <span id="toko_nama"></span></h3>
									<!-- <h4 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg_">Please login and start your journey</h4> -->
								</div>
								<!--begin::Title-->
								<!--begin::Form Group-->
								<div class="form-group">
									<label class="font-size-h6 font-weight-bolder text-dark">User ID</label>
									<input type="text" class="form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" name="user_id" id="user_id" placeholder="Type user ID" autocomplete="off" required />
								</div>
								<!--end::Form Group-->
							</div>
							<!--end: Wizard Step 1-->
							<!--begin: Wizard Actions-->
							<div class="d-flex justify-content-between pt-7">
								<button type="button" class="btn btn-primary btn-lg font-weight-bolder font-size-h6 my-3 btn-next" onclick="doLogin()">Login</button>
								<div>
									<button type="button" class="btn btn-secondary btn-lg font-weight-bolder font-size-h6 px-4 py-4 btn-icon my-3" id="btn-settings" data-toggle="modal" data-target="#modal-pengaturan"><i class="fa fa-cog"></i></button>
								</div>
							</div>
							<!--end: Wizard Actions-->
						</form>
						<!--end::Form-->
					</div>
					<!--end::Signin-->
				</div>
				<!--end::Container-->
			</div>
			<!--begin::Content-->
			<!--begin::Aside-->
			<div class="login-aside order-1 order-lg-2 bgi-no-repeat bgi-position-x-right">
				<div class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom" style="background-image: url(<?= base_url(); ?>assets/media/svg/illustrations/login-visual-1.svg);">
					<!--begin::Aside title-->
					<h3 class="pt-lg-30 pl-lg-20 pb-lg-0 pl-10 pt-20 m-0 d-flex justify-content-lg-start font-weight-boldest display5 display1-lg text-white">POS Management System</h3>
					<span class="pt-lg-5 pl-lg-20 pb-lg-0 pl-10 m-0 font-weight-boldest display5 display5-lg text-white d-block" id="info-nama_toko"></span>
					<div class="my-lg-5 mx-10 mx-lg-20 alert alert-custom alert-secondary d-none" role="alert" id="info-alert">
						<div class="alert-icon"><i class="flaticon-warning"></i></div>
						<div class="alert-text">Silahkan setup kode toko di pengaturan terlebih dahulu, atau setup <a href="javascript:void();" role="button" data-toggle="modal" data-target="#modal-pengaturan">disini</a></div>
					</div>

					<!--end::Aside title-->
				</div>
			</div>
			<!--end::Aside-->
		</div>
		<!--end::Login-->
	</div>
	<!--end::Main-->

	<!--begin::Modal Pengaturan-->
	<!-- Modal-->
	<div class="modal fade" id="modal-pengaturan" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Pengaturan Login</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i aria-hidden="true" class="ki ki-close"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>Code Store</label>
						<input type="text" class="form-control form-control-lg" name="pengaturan_code_store" id="pengaturan_code_store" placeholder="Masukan Code Store" />
					</div>
					<div class="form-group d-none" id="form-pengaturan_hapus">
						<label class="d-block">Hapus Pengaturan</label>
						<span class="text-muted d-block mb-2">Semua pengaturan dan kode toko yang tersimpan akan terhapus.</span>
						<button class="btn btn-light-danger" onclick="onDelPengaturan()">Hapus Pengaturan</button>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Tutup</button>
					<button type="button" class="btn btn-primary font-weight-bold" onclick="onSavePengaturan()">Simpan</button>
				</div>
			</div>
		</div>
	</div>
	<!--end::Modal Pengaturan-->

	<!--begin::Global Config(global config for global JS scripts)-->
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
	<!-- <script src='https://www.google.com/recaptcha/api.js'></script> -->
	<script src="<?php echo base_url(); ?>assets/helper/js.cookie.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/scripts.bundle.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/custom/blockui/jquery.blockui.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/custom/aos/aos.js"></script>
	<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/id_ID_FormValidation.js"></script> -->
	<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/helper/jquery.cookie.js"></script> -->

	<script>
		BASE_URL = "<?php echo base_url() ?>index.php/";
		BASE_URL_NO_INDEX = "<?php echo base_url() ?>";
		BASE_ASSETS = "<?php echo base_url() ?>assets/";
		BASE_CONTENT = "<?= base_url('Content/get/') ?>";
	</script>
	<script src="<?php echo base_url(); ?>assets/helper/helper.js?v=1.0.13"></script>
	<!--end::Global Theme Bundle-->
	<!--end::Page Scripts-->
	<!--end::Body-->

	<?php load_view('Form') ?>
	<?php load_view('Javascript') ?>
</body>

</html>