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
	<title>Monitoring Pajak | Reset Password</title>
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
	<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css" />

	<!--end::Global Theme Styles-->
	<!--end::Layout Themes-->
	<link rel="shortcut icon" href="<?= base_url(); ?>assets/media/logo.png" />
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
					<!--begin::Aside header-->
					<div class="d-flex flex-column-auto flex-column px-10 ">
						<div class="pt-lg-0 pt-5 header-login mb-5">
              <!--begin::Form-->
              <form class="form" novalidate="novalidate" id="kt_reset_form">
                <!--begin::Title-->
                <div class="pb-5 pt-lg-0 pt-5">
                  <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Reset Password</h3>
                </div>
                <!--end::Title-->
                <div class="alert alert-custom alert-light-danger fade show mb-5 <?= $isvalid ? 'd-none' : '' ?>" role="alert">
                  <div class="alert-icon"><i class="flaticon-warning"></i></div>
                  <div class="alert-text">Tidak dapat melanjutkan reset password, <b>Tautan tidak valid!</b></div>
                </div>
                <!--begin::Form group-->
                <div class="form-group">
                  <input type="hidden" name="wajibpajak_id" value="<?= $data['wajibpajak_id']?>"/>
                  <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg font-size-h6" type="password" placeholder="Password" name="password" id="password" autocomplete="off" />
                </div>
                <!--end::Form group-->
                <!--begin::Form group-->
                <div class="form-group">
                  <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg font-size-h6" type="password" placeholder="Konfirmasi Password" name="confirm-password" id="confirm-password" autocomplete="off" />
                </div>
                <!--end::Form group-->
                <!--begin::Form group-->
                <div class="form-group d-flex flex-wrap justify-content-between pb-lg-0">
                  <button type="button" id="kt_reset_submit" <?= $isvalid ? '' : 'disabled="true"' ?> class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Submit</button>
                  <a href="<?= base_url() ?>" role="button" class="btn btn-secondary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Kembali ke Login</a>
                </div>
                <!--end::Form group-->
              </form>
              <!--end::Form-->
						</div>
						<!--end::Aside header-->
					</div>
					<!--end::Aside Top-->
				</div>
				<!--end::Container-->
			</div>
			<!--begin::Content-->
			<!--begin::Aside-->
			<div class="login-aside order-1 order-lg-2 bgi-no-repeat bgi-position-x-right">
				<div class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom" style="background-image: url(<?= base_url(); ?>assets/media/svg/illustrations/login-visual-2.svg);">
					<!--begin::Aside title-->
					<h3 class="pt-lg-30 pl-lg-20 pb-lg-0 pl-10 py-20 m-0 d-flex justify-content-lg-start font-weight-boldest display5 display1-lg text-white">Tax Monitoring System</h3>
					<!--end::Aside title-->
				</div>
			</div>
			<!--end::Aside-->
		</div>
		<!--end::Login-->
	</div>
	<!--end::Main-->
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
	<script src="<?= base_url() ?>assets/js/pages/custom/login/login-general.js"></script>
	<script src="https://www.google.com/recaptcha/api.js?render=6LcdYmYcAAAAAOCYhh0SSlndJWtIy0ZGJJqQ1Z9X"></script>

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

	<?php load_view('ResetPasswordJavascript') ?>
</body>

</html>