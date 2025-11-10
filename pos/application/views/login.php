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
	<title>FMS | Login</title>
	<meta name="description" content="Login page example" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Page Custom Styles(used by this page)-->
	<link href="<?php echo base_url(); ?>assets/css/pages/login/login-1.css?v=7.0.5" rel="stylesheet" type="text/css" />
	<!--end::Page Custom Styles-->
	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.css?v=7.0.5" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/plugins/custom/aos/aos.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/plugins/custom/swal/swal.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/css/style.bundle.css?v=7.0.5" rel="stylesheet" type="text/css" />
	<!--end::Global Theme Styles-->
	<!--begin::Layout Themes(used by all pages)-->
	<!--end::Layout Themes-->
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/media/logos/favicon-timah.ico" />
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">

		<!--begin::Login-->
		<div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
			<!--begin::Aside-->
			<div class="login-aside d-flex flex-column flex-row-auto" style="background-color: #E1F0FF;">
				<!--begin::Aside Top-->
				<div class="d-flex flex-column-auto flex-column pt-lg-20 pt-15">
					<!--begin::Aside header-->
					<a data-aos="fade-left" data-aos-duration="1000" href="/" class="text-center mb-10">
						<img src="<?php echo base_url(); ?>assets/media/logos/timah/logotimah_new_big.png" class="max-h-70px" alt="" />
					</a>
					<!--end::Aside header-->
					<!--begin::Aside title-->
					<h3 data-aos="fade-left" data-aos-duration="1500" class="font-weight-bolder text-center font-size-h4 font-size-h1-lg" style="color: #3e5c73">Fleet Management System
						<br />PT. Timah
					</h3>
					<div class="d-none d-sm-none d-lg-block d-md-block">
						<a data-aos="fade-left" data-aos-duration="2000" href="https://play.google.com/store/apps/details?id=id.co.pttimah.fms.user" target="_blank" class="btn btn-primary mx-auto btn-air btn-pill w-50 mt-10 d-block align-self-center"><i class="fa fa-download"></i> Unduh Aplikasi Penumpang</a>
						<a data-aos="fade-left" data-aos-duration="2500" href="https://play.google.com/store/apps/details?id=id.co.pttimah.fms.driver" target="_blank" class="btn btn-primary mx-auto btn-air btn-pill w-50 mt-5 d-block align-self-center"><i class="fa fa-download"></i> Unduh Aplikasi Driver</a>
						<!-- <a data-aos="fade-left" data-aos-duration="2000" href="<?= base_url() ?>/dokumen/apk/user.apk" class="btn btn-primary mx-auto btn-air btn-pill w-50 mt-10 d-block align-self-center"><i class="fa fa-download"></i> Unduh Aplikasi Penumpang</a>
							<a data-aos="fade-left" data-aos-duration="2500" href="<?= base_url() ?>/dokumen/apk/driver.apk" class="btn btn-primary mx-auto btn-air btn-pill w-50 mt-5 d-block align-self-center"><i class="fa fa-download"></i> Unduh Aplikasi Driver</a> -->
					</div>
					<div class="d-md-none d-lg-none d-sm-block">
						<a data-aos="fade-left" data-aos-duration="2000" href="https://play.google.com/store/apps/details?id=id.co.pttimah.fms.user" target="_blank" class="btn btn-primary mx-auto btn-air btn-pill w-75 mt-10 d-block align-self-center"><i class="fa fa-download"></i> Unduh Aplikasi Penumpang</a>
						<a data-aos="fade-left" data-aos-duration="2500" href="https://play.google.com/store/apps/details?id=id.co.pttimah.fms.driver" target="_blank" class="btn btn-primary mx-auto btn-air btn-pill w-75 mt-5 d-block align-self-center"><i class="fa fa-download"></i> Unduh Aplikasi Driver</a>
						<!-- <a data-aos="fade-left" data-aos-duration="2000" href="<?= base_url() ?>/dokumen/apk/user.apk" class="btn btn-primary mx-auto btn-air btn-pill w-75 mt-10 d-block align-self-center"><i class="fa fa-download"></i> Unduh Aplikasi Penumpang</a>
							<a data-aos="fade-left" data-aos-duration="2500" href="<?= base_url() ?>/dokumen/apk/driver.apk" class="btn btn-primary mx-auto btn-air btn-pill w-75 mt-5 d-block align-self-center"><i class="fa fa-download"></i> Unduh Aplikasi Driver</a> -->
					</div>
					<!--end::Aside title-->
				</div>
				<!--end::Aside Top-->
				<!--begin::Aside Bottom-->
				<div data-aos="fade-left" data-aos-duration="3000" class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/bg/bg-login-new.svg);min-height: unset;"></div>
				<!--end::Aside Bottom-->
			</div>
			<!--begin::Aside-->
			<!--begin::Content-->
			<div data-aos="fade-right" data-aos-duration="3000" class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
				<!--begin::Content body-->
				<div class="d-flex flex-column-fluid flex-center">
					<!--begin::Signin-->
					<div class="login-form login-signin">
						<!--begin::Form-->
						<form class="form" action="javascript:doLogin();" id="kt_login_signin_form">
							<!--begin::Title-->
							<div class="pb-13 pt-lg-0 pt-5">
								<h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Selamat Datang !</h3>
							</div>
							<!--begin::Title-->
							<input type="hidden" name="token" id="token">
							<div class="form-group">
								<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="text" placeholder="Username" name="username" id="username" autocomplete="off" />
							</div>
							<div class="form-group">
								<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="password" placeholder="Password" name="password" id="password" />
							</div>
							<div class="form-group text-center mt-10">
								<button id="kt_login_signin_submit" class="btn btn-primary  opacity-90 px-15 py-3" style="width:100%;">Masuk</button>
							</div>
						</form>
						<!--end::Form-->
					</div>
					<!--end::Signin-->
				</div>
				<!--end::Content body-->
			</div>
			<!--end::Content-->
		</div>
		<!--end::Login-->

	</div>
	<!--end::Main-->
	<script>
		var HOST_URL = "https://keenthemes.com/metronic/tools/preview";
	</script>
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
	<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js?v=7.0.5"></script>
	<!-- <script src="<?php echo base_url(); ?>assets/plugins/custom/jquery/jquery.min.js"></script> -->
	<script src="<?php echo base_url(); ?>assets/plugins/custom/jquery/jquery-blockui.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/custom/aos/aos.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/custom/swal/swal.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/scripts.bundle.min.js?v=7.0.5"></script>

	<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-messaging.js"></script>
	<script src="<?php echo base_url(); ?>assets/helper/helper.js?v=1.0.9"></script>
	<script src="<?php echo base_url(); ?>assets/helper/FCM.js?v=1.0.3"></script>
	<!--end::Global Theme Bundle-->
	<!--begin::Page Scripts(used by this page)-->
	<!-- <script src="<?php echo base_url(); ?>assets/js/pages/custom/login/login-general.js?v=7.0.5"></script> -->
	<!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>

<!-- Script For Fcm -->
<script type="text/javascript">
	FCM.setConfig('<?= $this->config->item('config_fcm') ?>');

	$(function() {
		setTimeout(function() {
			AOS.init();
		}, 1000)

		if (Notification.permission === 'granted') {
			reqPermission()
		} else {
			HELPER.showMessage({
				success: 'info',
				title: 'Information !',
				message: 'Silahkan klik allow pada popup browser untuk mengijinkan notifikasi.',
				allowOutsideClick: false,
				callback: function(res) {
					reqPermission()
				}
			})
		}

	})

	function reqPermission() {
		FCM.reqPermission({
			callback: function(response) {

				if (response) {

					FCM.getToken({
						callback: function(result) {
							if (result.success) {
								$('#token').val(result.token)
							}
						}
					});

				} else {

					HELPER.confirm({
						success: 'warning',
						title: 'Peringatan',
						message: 'Anda tidak dapat menerima notifikasi. Klik "Ya" dan pilih "Izinkan" jika Anda ingin menerima notifikasi.',
						callback: function(result) {
							if (result) {
								FCM.reqPermission()
							}
						}
					})

				}
				HELPER.unblock(100)
			}
		});
	}
</script>
<!-- End Script For Fcm -->
<script type="text/javascript">
	var BASE_URL = '<?php echo base_url(); ?>';

	function doLogin() {
		HELPER.block()
		var username = $('#username').val();
		var password = $('#password').val();
		var token = $('#token').val();
		$.ajax({
			type: "POST",
			url: BASE_URL + "index.php/Login/doLogin",
			data: {
				username: username,
				password: password,
				token: token,
			},
			success: function(response) {
				if (response.success) {
					window.location.reload();
				} else {
					HELPER.unblock()
					$('#password').val('')
					Swal.fire("Login gagal", "Username atau password salah", "error");
				}
			}
		});
	}
</script>