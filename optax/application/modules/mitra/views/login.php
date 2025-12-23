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
	<title>OPTAX | Login </title>
	<meta name="description" content="POS Management System" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<link href="<?php echo base_url('assets/css/pages/login/login-4.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/global/plugins.bundle.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/custom/aos/aos.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/css/style.bundle.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="<?= base_url('assets/media/icon_title.png'); ?>" />
	<style type="text/css" media="screen">
		.login.login-4 .login-aside {
			background: url("<?= base_url('assets/media/bg/bg-2.jpg'); ?>") no-repeat center center;
			background-size: cover;
			width: 700px;
		}

		.required {
			color: red;
			margin-left: 5px;
			/* Jarak antara bintang dan teks label */
		}

		/* sembunyikan badge reCAPTCHA v3 / pesan error di pojok */
		.grecaptcha-badge {
			display: none !important;
			visibility: hidden !important;
			opacity: 0 !important;
			pointer-events: none !important;
		}
	</style>
</head>

<body id="kt_body" class="cek header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
	<div class="d-flex flex-column bg-success flex-root ">
		<div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid wizard" id="kt_login">
			<div class="login-container d-flex flex-center flex-row flex-row-fluid order-1 order-lg-2 flex-row-fluid bg-white py-lg-0 pb-lg-0 pt-15 pb-12">
				<div class="login-content login-content-signup d-flex flex-column">
					<div class="d-flex flex-column-auto flex-column px-10 ">
						<div class="pt-lg-4 pt-5 header-login mb-5">
							<h3 class="font-weight-bolder text-dark font-size-h5 font-size-h3-lg" style="font-size: 1.6rem!important;font-weight: normal!important;text-transform: uppercase;">Selamat Datang Di</h3>
							<h2 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg mb-12" style="font-size: 2.2rem!important;">SISTEM OPTIMALISASI MONITORING PAJAK</h2>
							<div class="d-flex justify-content-between">
								<span class="text-muted font-weight-bold font-size-h4">Masuk Sebagai : <b class="text-dark" id="text-login"></b></span>
							</div>
							<input type="hidden" name="token2" id="token2" value="<?= $this->session->userdata('fcmtoken'); ?>">
						</div>
						<div id="wizard-body">
							<div class="wizard-nav pt-5">
								<div class="wizard-steps d-flex flex-column flex-sm-row">
									<a href="javascript:;" onclick="getLogin(this)" data-id="wajib_pajak" class="button wizard-step flex-grow-1 flex-basis-0 mr-10 p-10" style="border: 1px solid #fefefe;border-radius: 5px;box-shadow: 0px 5px 20px 0px rgb(82 63 105 / 8%);">
										<div class="wizard-wrapper pr-7">
											<div class="wizard-icon">
												<i class="wizard-check ki ki-check"></i>
												<span class="wizard-number">1</span>
											</div>
											<div class="wizard-label">
												<h3 class="wizard-title">Wajib Pajak</h3>
												<div class="wizard-desc">Click For Details</div>
											</div>
											<span class="svg-icon pl-6" style="margin-left: auto;">
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
							<div class="wizard-nav pt-5">
								<div class="wizard-steps d-flex flex-column flex-sm-row">
									<a href="javascript:;" onclick=" getLogin(this)" data-id="pemerintah_daerah" class="button wizard-step flex-grow-1 flex-basis-0 p-10 mr-10" style="border: 1px solid #fefefe;border-radius: 5px;box-shadow: 0px 5px 20px 0px rgb(82 63 105 / 8%);">
										<div class="wizard-wrapper pr-7">
											<div class="wizard-icon">
												<i class="wizard-check ki ki-check"></i>
												<span class="wizard-number">2</span>
											</div>
											<div class="wizard-label">
												<h3 class="wizard-title">Pemerintah Daerah </h3>
												<div class="wizard-desc">Click For Details</div>
											</div>
											<span class="svg-icon pl-6" style="margin-left: auto;">
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
								<!-- <div class="row">
									<div class="col-md-12 d-flex flex-row-reverse">
										<a href="<?= $_ENV['PORTAL_URL'] ?>" title="Kembali ke homepage" class="btn btn-secondary btn-lg font-weight-bolder font-size-h6 px-4 py-4 btn-icon my-3"><i class="fa fa-home"></i></a>
									</div>
								</div> -->
							</div>
							<div class="wizard-nav pt-5">
								<div class="wizard-steps d-flex flex-column flex-sm-row">
									<a href="javascript:;" onclick=" getLogin(this)" data-id="bank_jatim" class="button wizard-step flex-grow-1 flex-basis-0 p-10 mr-10" style="border: 1px solid #fefefe;border-radius: 5px;box-shadow: 0px 5px 20px 0px rgb(82 63 105 / 8%);">
										<div class="wizard-wrapper pr-7">
											<div class="wizard-icon">
												<i class="wizard-check ki ki-check"></i>
												<span class="wizard-number">3</span>
											</div>
											<div class="wizard-label">
												<h3 class="wizard-title">Bank Jatim </h3>
												<div class="wizard-desc">Click For Details</div>
											</div>
											<span class="svg-icon pl-6" style="margin-left: auto;">
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
							<div class="wizard-nav pt-5">
								<div class="wizard-steps d-flex flex-column flex-sm-row">
									<a href="javascript:;" onclick=" getLogin(this)" data-id="kpk" class="button wizard-step flex-grow-1 flex-basis-0 p-10 mr-10" style="border: 1px solid #fefefe;border-radius: 5px;box-shadow: 0px 5px 20px 0px rgb(82 63 105 / 8%);">
										<div class="wizard-wrapper pr-7">
											<div class="wizard-icon">
												<i class="wizard-check ki ki-check"></i>
												<span class="wizard-number">4</span>
											</div>
											<div class="wizard-label">
												<h3 class="wizard-title">KPK </h3>
												<div class="wizard-desc">Click For Details</div>
											</div>
											<span class="svg-icon pl-6" style="margin-left: auto;">
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
						<div class="alert alert-custom alert-outline-2x alert-outline-warning fade" role="alert">
							<div class="alert-icon"><i class="flaticon-warning"></i></div>
							<div class="alert-text">Akun google anda belum terdaftar, silahkan lakukan pendaftaran terlebih dahulu dan tunggu email konfirmasi!.</div>
							<div class="alert-close">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="ki ki-close"></i></span>
								</button>
							</div>
						</div>
						<div id="wizard-wajib_pajak" class="wizard-opt" style="display: none;">
							<div class="login-form login-signin login-signin-wp">
								<form class="form" novalidate="novalidate" id="kt_login_signin_form_wp">
									<div class="form-group">
										<label class="font-size-h6 font-weight-bolder text-dark">Email</label>
										<input type="email" class="form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" placeholder="Type your email" name="email" id="email" autocomplete="off" />
									</div>
									<div class="form-group">
										<label class="font-size-h6 font-weight-bolder text-dark">Password</label>
										<div class="input-icon input-icon-right">
											<input type="password" class="form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" name="password" id="password" placeholder="Type your password" required />
											<span id="btn-show-password" onclick="passwordShowUser()"><i class="hover-icon far fa-eye icon-md"></i></span>
										</div>
									</div>
									<div class="d-flex justify-content-center pb-lg-0 pb-5">
										<button type="button" class="btn btn-outline-primary font-size-h6 font-weight-bolder mr-3 my-3 pr-4 pr-8 py-4" onclick="backLogin()">
											<span class="svg-icon svg-icon-md ml-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"></polygon>
														<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) "></path>
														<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) "></path>
													</g>
												</svg>
											</span>
										</button>
										<button type="button" id="kt_login_signin_mitra" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3 flex-grow-1" onclick="doLogin()">Sign In</button>
										<!-- <a href="<?= $this->session->userdata('gurl') ?>" class="btn btn-light-primary font-weight-bolder px-8 py-4 my-3 font-size-lg flex-grow-1">
											<span class="svg-icon svg-icon-md">
												<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
													<path d="M19.9895 10.1871C19.9895 9.36767 19.9214 8.76973 19.7742 8.14966H10.1992V11.848H15.8195C15.7062 12.7671 15.0943 14.1512 13.7346 15.0813L13.7155 15.2051L16.7429 17.4969L16.9527 17.5174C18.879 15.7789 19.9895 13.221 19.9895 10.1871Z" fill="#4285F4" />
													<path d="M10.1993 19.9313C12.9527 19.9313 15.2643 19.0454 16.9527 17.5174L13.7346 15.0813C12.8734 15.6682 11.7176 16.0779 10.1993 16.0779C7.50243 16.0779 5.21352 14.3395 4.39759 11.9366L4.27799 11.9466L1.13003 14.3273L1.08887 14.4391C2.76588 17.6945 6.21061 19.9313 10.1993 19.9313Z" fill="#34A853" />
													<path d="M4.39748 11.9366C4.18219 11.3166 4.05759 10.6521 4.05759 9.96565C4.05759 9.27909 4.18219 8.61473 4.38615 7.99466L4.38045 7.8626L1.19304 5.44366L1.08875 5.49214C0.397576 6.84305 0.000976562 8.36008 0.000976562 9.96565C0.000976562 11.5712 0.397576 13.0882 1.08875 14.4391L4.39748 11.9366Z" fill="#FBBC05" />
													<path d="M10.1993 3.85336C12.1142 3.85336 13.406 4.66168 14.1425 5.33717L17.0207 2.59107C15.253 0.985496 12.9527 0 10.1993 0C6.2106 0 2.76588 2.23672 1.08887 5.49214L4.38626 7.99466C5.21352 5.59183 7.50242 3.85336 10.1993 3.85336Z" fill="#EB4335" />
												</svg>
												end::Svg Icon
											</span>Sign in with Google</a> -->
									</div>
									<span class="text-muted font-weight-bold font-size-h5 d-block text-center mt-5">Belum Punya Akun?
										<a href="javascript:;" id="kt_login_signup" class="text-primary font-weight-bolder">Daftar Akun</a>
									</span>
								</form>
							</div>
							<div class="login-form login-signup login-signup-wp" style="display: none;">
								<form class="form" novalidate="novalidate" name="kt_login_signup_form" id="kt_login_signup_form">
									<div class="pb-8 pt-10 ">
										<h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Daftar Akun</h3>
										<p class="text-muted font-weight-bold font-size-h4">Lengkapi form berikut sebagai pengajuan pendaftaran akun</p>
									</div>
									<div class="row">
										<div class="col-xl-6">
											<div class="form-group">
												<label class="font-size-h6 font-weight-bolder text-dark">NPWPD</label>
												<input class="form-control h-auto py-3 px-4 rounded-lg font-size-h6" type="text" name="wajibpajak_npwpd" id="wajibpajak_npwpd" autocomplete="off" />
											</div>
										</div>
										<div class="col-xl-6">
											<div class="form-group">
												<label class="font-size-h6 font-weight-bolder text-dark">Jenis Usaha</label>
												<select class="form-control h-auto py-3 px-4 rounded-lg font-size-h6" name="wajibpajak_sektor_nama">
													<option value="">-- Pilih Jenis Usaha --</option>
													<?php if (!empty($jenis_pajak)): ?>
														<?php foreach ($jenis_pajak as $row): ?>
															<option value="<?= $row['jenis_kode']; ?>">
																<?= $row['jenis_nama']; ?>
															</option>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
										</div>
										<div class="col-xl-6" style="display: none;">
											<div class="form-group">
												<label class="font-size-h6 font-weight-bolder text-dark">Kode Jenis Usaha</label>
												<input class="form-control h-auto py-3 px-4 rounded-lg font-size-h6" type="text" name="jenis_kode" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xl-12">
											<div class="form-group">
												<label class="font-size-h6 font-weight-bolder text-dark">Nama Perusahaan</label>
												<input class="form-control h-auto py-3 px-4 rounded-lg font-size-h6" type="text" name="wajibpajak_nama" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xl-12">
											<div class="form-group">
												<label class="font-size-h6 font-weight-bolder text-dark">Alamat</label>
												<input class="form-control h-auto py-3 px-4 rounded-lg font-size-h6" type="text" name="wajibpajak_alamat" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xl-12">
											<div class="form-group">
												<label class="font-size-h6 font-weight-bolder text-dark">Nama Penangung Jawab</label>
												<input class="form-control h-auto py-3 px-4 rounded-lg font-size-h6" type="text" name="wajibpajak_nama_penanggungjawab" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xl-12">
											<div class="form-group">
												<label class="font-size-h6 font-weight-bolder text-dark">No Telp Perusahaan</label>
												<input class="form-control h-auto py-3 px-4 rounded-lg font-size-h6" type="text" name="wajibpajak_telp" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xl-6">
											<div class="form-group">
												<label class="font-size-h6 font-weight-bolder text-dark">Email Perusahaan</label>
												<input class="form-control h-auto py-3 px-4 rounded-lg font-size-h6" type="text" onchange="checkEmail()" name="wajibpajak_email" id="wajibpajak_email" autocomplete="off" />
											</div>
										</div>
										<div class="col-xl-6">
											<div class="form-group">
												<label class="font-size-h6 font-weight-bolder text-dark">Password</label>
												<input class="form-control h-auto py-3 px-4 rounded-lg font-size-h6" type="password" name="wajibpajak_password" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xl-12">
											<p><b>NB :</b></p>
											<p><span class="required">*</span>Pastikan data yang Anda isikan sudah benar dan sesuai</p>
											<p><span class="required">*</span>Silahkan konfirmasi ke petugas atau ke nomor 081xxxxxxxxxx(CS Monitoring Pajak) untuk verifikasi pendaftaran akun.</p>
										</div>
									</div>
									<div class="form-group d-flex flex-wrap pb-lg-0 pb-3">
										<button type="submit" id="kt_login_signup_mitra" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4" onclick="doSignup()">Submit</button>
										<button type="button" id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3" onclick="cancelSignup()">Cancel</button>
									</div>
								</form>
							</div>
							<div class="login-form login-forgot" style="display: none;">
								<form class="form" novalidate="novalidate" id="kt_login_forgot_form">
									<div class="pb-13 pt-lg-0 pt-5">
										<h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Forgotten Password ?</h3>
										<p class="text-muted font-weight-bold font-size-h4">Enter your email to reset your password</p>
									</div>
									<div class="form-group">
										<input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg font-size-h6" type="email" placeholder="Email" name="email_forgot" id="email_forgot" autocomplete="off" />
									</div>
									<div class="form-group d-flex flex-wrap pb-lg-0">
										<button type="button" id="kt_login_forgot_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Submit</button>
										<button type="button" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Cancel</button>
									</div>
								</form>
							</div>
						</div>
						<div id="wizard-pemerintah_daerah" class="wizard-opt" style="display: none;">
							<div class="login-form login-signin login-signin">
								<form class="form" novalidate="novalidate" id="kt_login_signin_form">
									<div class="form-group">
										<label class="font-size-h6 font-weight-bolder text-dark">Email</label>
										<input class="user_email form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" type="text" name="user_email" autocomplete="off" />
									</div>
									<div class="form-group">
										<div class="d-flex justify-content-between mt-5">
											<label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
										</div>
										<div class="input-icon input-icon-right">
											<input type="password" class="user_password form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" name="user_password" placeholder="Type your password" required />
											<span id="btn-show-user-password" onclick="passwordShow()"><i class="hover-icon far fa-eye icon-md"></i></span>
										</div>
									</div>
									<div class="d-flex justify-content-center pb-lg-0 pb-5">
										<button type="button" class="btn btn-outline-primary font-size-h6 font-weight-bolder mr-3 my-3 pr-4 pr-8 py-4" onclick="backLogin()">
											<span class="svg-icon svg-icon-md ml-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"></polygon>
														<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) "></path>
														<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) "></path>
													</g>
												</svg>
											</span>
										</button>
										<button type="button" id="kt_login_signin" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3 flex-grow-1" onclick="doLoginPemda('pemda')">Sign In</button>
									</div>
								</form>
							</div>
						</div>
						<div id="wizard-bank_jatim" class="wizard-opt" style="display: none;">
							<div class="login-form login-signin login-signin">
								<form class="form" novalidate="novalidate" id="kt_login_signin_form">
									<div class="form-group">
										<label class="font-size-h6 font-weight-bolder text-dark">Email</label>
										<input class="user_email form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" type="text" name="user_email" autocomplete="off" />
									</div>
									<div class="form-group">
										<div class="d-flex justify-content-between mt-5">
											<label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
										</div>
										<div class="input-icon input-icon-right">
											<input type="password" class="user_password form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" name="user_password" placeholder="Type your password" required />
											<span id="btn-show-user-password" onclick="passwordShow()"><i class="hover-icon far fa-eye icon-md"></i></span>
										</div>
									</div>
									<div class="d-flex justify-content-center pb-lg-0 pb-5">
										<button type="button" class="btn btn-outline-primary font-size-h6 font-weight-bolder mr-3 my-3 pr-4 pr-8 py-4" onclick="backLogin()">
											<span class="svg-icon svg-icon-md ml-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"></polygon>
														<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) "></path>
														<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) "></path>
													</g>
												</svg>
											</span>
										</button>
										<button type="button" id="kt_login_signin" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3 flex-grow-1" onclick="doLoginPemda('bankjatim')">Sign In</button>
									</div>
								</form>
							</div>
						</div>
						<div id="wizard-kpk" class="wizard-opt" style="display: none;">
							<div class="login-form login-signin login-signin">
								<form class="form" novalidate="novalidate" id="kt_login_signin_form">
									<div class="form-group">
										<label class="font-size-h6 font-weight-bolder text-dark">Email</label>
										<input class="user_email form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" type="text" name="user_email" autocomplete="off" />
									</div>
									<div class="form-group">
										<div class="d-flex justify-content-between mt-5">
											<label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
										</div>
										<div class="input-icon input-icon-right">
											<input type="password" class="user_password form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" name="user_password" placeholder="Type your password" required />
											<span id="btn-show-user-password" onclick="passwordShow()"><i class="hover-icon far fa-eye icon-md"></i></span>
										</div>
									</div>
									<div class="d-flex justify-content-center pb-lg-0 pb-5">
										<button type="button" class="btn btn-outline-primary font-size-h6 font-weight-bolder mr-3 my-3 pr-4 pr-8 py-4" onclick="backLogin()">
											<span class="svg-icon svg-icon-md ml-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"></polygon>
														<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) "></path>
														<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) "></path>
													</g>
												</svg>
											</span>
										</button>
										<button type="button" id="kt_login_signin" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3 flex-grow-1" onclick="doLoginPemda('kpk')">Sign In</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="login-aside order-2 order-lg-1 bgi-no-repeat bgi-position-x-right">
				<div class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom" style="background-image: url(<?= base_url('assets/media/svg/illustrations/login-visual-5.svg'); ?>); width:100%;">
					<h3 class="pt-lg-30 pl-lg-20 pb-lg-0 pl-10 pt-20 m-0 d-flex justify-content-lg-start font-weight-boldest display5 display1-lg text-white">OPTAX</h3>
					<h3 class="pl-lg-20 pb-lg-0 pl-10 pr-0 text-white" style="width: 50%;">APLIKASI OPTIMALISASI MONITORING PAJAK</h3>
					<!-- <div class="row" style="width: 90%;">
						<div class="container mt-4 ml-18" id="mb_download" style="display: none;">
							<h3 class="text-white" style="display: inline; vertical-align: middle;">Manual book : </h3>
							<button id="mb_bp" style="vertical-align: middle; display: none;" onclick="downloadMB('Manual_Book_Aplikasi_Tax_System_Bapenda_15_Februari_2023.pdf')" class="btn btn-light-primary btn-sm font-weight-bold mr-2">Bapenda <span class="fas fa-download fa-sm"></span></button>
							<button id="mb_wp" style="vertical-align: middle; display: none;" onclick="downloadMB('Manual_Book_Aplikasi_Tax_System_(Wajib_Pajak)_25_Jan_2023.pdf')" class="btn btn-light-primary btn-sm font-weight-bold mr-2">Wajib Pajak <span class="fas fa-download fa-sm"></span></button>
						</div>
					</div> -->
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
	<script src="<?php echo base_url('assets/helper/js.cookie.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/global/plugins.bundle.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/scripts.bundle.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/custom/blockui/jquery.blockui.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/custom/aos/aos.js'); ?>"></script>
	<script src="<?= base_url('assets/js/pages/custom/login/login-general.js') ?>"></script>
	<?php if (!empty($_ENV['GAPI_CAPTCHA_SITE_KEY'])): ?>
		<script src="https://www.google.com/recaptcha/api.js?render=<?= $_ENV['GAPI_CAPTCHA_SITE_KEY'] ?>"></script>
	<?php endif; ?>
	<script>
		BASE_URL = "<?php echo base_url() ?>index.php/";
		BASE_URL_NO_INDEX = "<?php echo base_url() ?>";
		BASE_ASSETS = "<?php echo base_url() ?>assets/";
		BASE_CONTENT = "<?= base_url('Content/get/') ?>";
	</script>
	<script src="https://www.gstatic.com/firebasejs/8.7.0/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.7.0/firebase-messaging.js"></script>
	<script src="<?php echo base_url(); ?>assets/helper/FCM.js"></script>
	<script src="<?php echo base_url(); ?>assets/helper/helper.js?v=1.0.13"></script>
	<?php load_view('Javascript') ?>
	<?php if ($this->session->flashdata('data_wp')) : ?>
		<?php
		$npwpd 		= $this->session->flashdata("data_wp")["npwpd"];
		$nama_wp 	= $this->session->flashdata("data_wp")["nama_wp"];
		$masa_pajak = $this->session->flashdata("data_wp")["masa_pajak"];
		$konfirmasi_result = json_decode($this->session->flashdata("data_wp")["konfirmasi_result"], true);
		$masa_pajak = date("M Y", strtotime($masa_pajak));
		?>
		<script>
			var stat = <?= ($konfirmasi_result["status"]) ? "true" : "false" ?>;
			console.log(stat);
			var pesan = (stat) ? "Nama WP : <?= $nama_wp ?><br>NPWPD : <?= $npwpd ?><br>Masa Pajak : <?= $masa_pajak ?><br></br><b>Konfirmasi</b> pelaporan omzet berhasil dilakukan. Mohon ditunggu untuk pembuatan SURAT PEMBERITAHUAN PAJAK DAERAH (SPTPD) dari petugas pajak daerah OPTAX<br><br><b>Virtual Account</b> untuk pembayaran pajak daerah dapat dilihat di menu <b>History Pelaporan</b> aplikasi <a href='https://persada.malangkota.go.id/backoffice/'>PERSADA</a>" : "Nama WP : <?= $nama_wp ?><br>NPWPD : <?= $npwpd ?><br>Masa Pajak : <?= $masa_pajak ?><br></br>Pelaporan anda sudah terkonfirmasi cek SPTPD anda pada menu <b>History Pelaporan</b> aplikasi PERSADA, Terimakasih.";
			var title = (stat) ? "Berhasil" : "Mohon Maaf.";
			HELPER.showMessage({
				success: stat,
				title: title,
				message: " ",
			});
			var timeoutId = setTimeout(function(e) {
				if ($("#swal2-content").length > 0) {
					$("#swal2-content").html(pesan);
					$("#swal2-content").css("max-height", "unset");
					clearTimeout(timeoutId);
				}
			}, 500);
		</script>
	<?php endif; ?>
</body>

</html>