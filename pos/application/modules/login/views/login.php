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

<head>
	<base href="../../../../">
	<meta charset="utf-8" />
	<title>POS OPTAX | Login</title>
	<meta name="description" content="POS Management System" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<link href="<?php echo base_url(); ?>assets/css/pages/login/login-4.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/plugins/custom/aos/aos.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="<?= base_url(); ?>/assets/media/icon_title.png" />
	<style type="text/css" media="screen">
		.login.login-4 .login-aside {
			background: url("<?= base_url(); ?>assets/media/bg/bg-2.jpg") no-repeat center center;
			background-size: cover;
			width: 700px;
		}
	</style>
</head>

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
	<div class="d-flex flex-column flex-root">
		<div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid wizard" id="kt_login">
			<div class="login-container d-flex flex-center flex-row flex-row-fluid order-2 order-lg-2 flex-row-fluid bg-white py-lg-0 pb-lg-0 pt-15 pb-12">
				<div class="login-content login-content-signup d-flex flex-column">
					<div class="d-flex flex-column-auto flex-column px-10">
						<!-- <a href="#" class="login-logo pb-lg-4 pb-10">
							<img src="assets/media/logos/logo-4.png" class="max-h-70px" alt="" />
						</a> -->
						<div class="wizard-nav pt-5 pt-lg-10 pb-5">
							<div class="wizard-steps d-flex flex-column flex-sm-row">
								<a href="<?php echo base_url() ?>" class="button wizard-step flex-grow-1 flex-basis-0 mr-10 p-5" data-wizard-type="step" data-wizard-state="current" style="border: 1px solid #fefefe;border-radius: 5px;box-shadow: 0px 5px 20px 0px rgb(82 63 105 / 8%);}">
									<div class="wizard-wrapper pr-7">
										<div class="wizard-icon">
											<i class="wizard-check ki ki-check"></i>
											<span class="wizard-number">1</span>
										</div>
										<div class="wizard-label">
											<h3 class="wizard-title">Login</h3>
											<div class="wizard-desc">Login Details</div>
										</div>
										<span class="svg-icon pl-6">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24" />
													<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)" x="7.5" y="7.5" width="2" height="9" rx="1" />
													<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
												</g>
											</svg>
										</span>
									</div>
								</a>
								<a href="<?= $_ENV['PAJAK_URL'] ?>" class="button wizard-step flex-grow-1 flex-basis-0 p-5" data-wizard-type="step" data-wizard-type="step" style="border: 1px solid #fefefe;border-radius: 5px;box-shadow: 0px 5px 20px 0px rgb(82 63 105 / 8%);}">
									<div class="wizard-wrapper pr-7">
										<div class="wizard-icon">
											<i class="wizard-check ki ki-check"></i>
											<span class="wizard-number">2</span>
										</div>
										<div class="wizard-label">
											<h3 class="wizard-title">Register </h3>
											<div class="wizard-desc">Click Details</div>
										</div>
										<span class="svg-icon pl-6">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24" />
													<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)" x="7.5" y="7.5" width="2" height="9" rx="1" />
													<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
												</g>
											</svg>
										</span>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="login-form">
						<form class="form px-10" novalidate="novalidate" id="kt_login_signin_form" autocomplete='off'>
							<div class="" data-wizard-type="step-content" data-wizard-state="current">
								<div class="pb-5">
									<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Welcome to POS <span id="toko_nama"></span></h3>
								</div>
								<div class="form-group">
									<label class="font-size-h6 font-weight-bolder text-dark">Email</label>
									<input type="email" class="form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" name="email" id="email" placeholder="Type your email" autocomplete="off" required data-fv-email-address___message="The input is not a valid email address" />
								</div>
								<div class="form-group">
									<label class="font-size-h6 font-weight-bolder text-dark">Password</label>
									<div class="input-icon input-icon-right">
										<input type="password" class="form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" name="password" id="password" placeholder="Type your password" required />
										<span id="btn-show-password" onclick="passwordShow()"><i class="hover-icon far fa-eye icon-md"></i></span>
									</div>
								</div>
							</div>
							<div class="d-flex justify-content-between pt-7">
								<button type="button" class="btn btn-primary btn-lg font-weight-bolder font-size-h6 my-3 btn-next" onclick="doLogin()">Login</button>
								<div>
									<a href="<?= $_ENV['PORTAL_URL'] ?>" title="Kembali ke <?= $_ENV['PORTAL_URL'] ?>" class="btn btn-secondary btn-lg font-weight-bolder font-size-h6 px-4 py-4 btn-icon my-3"><i class="fa fa-home"></i></a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="login-aside order-1 order-lg-1 bgi-no-repeat bgi-position-x-right">
				<div class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom" style="background-image: url(<?= base_url(); ?>assets/media/svg/illustrations/data-points.svg);">
					<h3 class="pt-lg-30 pl-lg-20 pb-lg-0 pl-10 pt-20 m-0 d-flex justify-content-lg-start font-weight-boldest display5 display1-lg text-white">Point of Sales(POS)</h3>
					<span class="pt-lg-5 pl-lg-20 pb-lg-0 pl-10 m-0 font-weight-boldest display5 display5-lg text-white d-none" id="info-nama_toko"></span>
					<div class="my-lg-5 mx-10 mx-lg-20 alert alert-custom alert-secondary d-none" role="alert" id="info-alert">
						<div class="alert-icon"><i class="flaticon-warning"></i></div>
						<div class="alert-text">Silahkan setup kode toko di pengaturan terlebih dahulu, atau setup <a href="javascript:void();" role="button" data-toggle="modal" data-target="#modal-pengaturan">disini</a></div>
					</div>
					<!-- <div class="my-lg-5 mx-10 mx-lg-20">
						<h3 class="text-white" style="display: inline; vertical-align: middle;">Manual book : </h3>
						<button style="vertical-align: middle;" onclick="downloadMB('Manual_Book_POS_14_Februari_2023.pdf')" class="btn btn-light-primary btn-sm font-weight-bold mr-2">POS <span class="fas fa-download fa-sm"></span></button>
						<button style="vertical-align: middle;" onclick="downloadMB('Manualbook_Mobile.pdf')" class="btn btn-light-primary btn-sm font-weight-bold mr-2">POS Mobile <span class="fas fa-download fa-sm"></span></button>
					</div> -->
				</div>
			</div>
		</div>
	</div>
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
	<script src="<?php echo base_url(); ?>assets/helper/js.cookie.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/scripts.bundle.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/custom/blockui/jquery.blockui.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/custom/aos/aos.js"></script>
	<script>
		BASE_URL = "<?php echo base_url() ?>index.php/";
		BASE_URL_NO_INDEX = "<?php echo base_url() ?>";
		BASE_ASSETS = "<?php echo base_url() ?>assets/";
		BASE_CONTENT = "<?= base_url('Content/get/') ?>";
	</script>
	<script src="<?php echo base_url(); ?>assets/helper/helper.js?v=1.0.13"></script>
	<?php load_view('Form') ?>
	<?php load_view('Javascript') ?>
</body>

</html>