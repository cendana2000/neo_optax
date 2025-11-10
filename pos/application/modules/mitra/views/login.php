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
	<title>Monitoring Pajak | Login</title>
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
    <link href= "https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css" />

	<!--end::Global Theme Styles-->
	<!--end::Layout Themes-->
	<link rel="shortcut icon" href="https://tecton-pttsi.sekawanmedia.co.id/assets/media/logo.png" />
	<style type="text/css" media="screen">
		.login.login-4 .login-aside {
		    background: linear-gradient(
		147.04deg
		, #6b84dc 0.74%, #0d1a49 99.61%);
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
                            <h3 class="font-weight-bolder text-dark font-size-h5 font-size-h3-lg" style="font-size: 1.6rem!important;font-weight: normal!important;text-transform: uppercase;">Selamat Datang Di</h3>
                            <h2 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg mb-12" style="font-size: 2.2rem!important;">SISTEM MONITORING PAJAK DAERAH KOTA MALANG</h2>
                            <span class="text-muted font-weight-bold font-size-h4">Masuk Sebagai : <b class="text-dark" id="text-login"></b></span>
                        </div>
						<!--end::Aside header-->
						<!--begin: Wizard Nav-->
                        <div id="wizard-body">
                            <div class="wizard-nav pt-5">
                                <!--begin::Wizard Steps-->
                                <div class="wizard-steps d-flex flex-column flex-sm-row">
                                    <!--begin::Wizard Step 1 Nav-->
                                    <a href="javascript:;" onclick="getLogin(this)" data-id="wajib_pajak" class="button wizard-step flex-grow-1 flex-basis-0 mr-10 p-10" style="border: 1px solid #fefefe;border-radius: 5px;box-shadow: 0px 5px 20px 0px rgb(82 63 105 / 8%);}">
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
                                    <!--end::Wizard Step 1 Nav-->
                                </div>
                                <!--end::Wizard Steps-->
                            </div>
                            <div class="wizard-nav pt-5">
                                <!--begin::Wizard Steps-->
                                <div class="wizard-steps d-flex flex-column flex-sm-row">
                                    <!--begin::Wizard Step 2 Nav-->
                                    <a  href="javascript:;"" onclick="getLogin(this)" data-id="pemerintah_daerah" class="button wizard-step flex-grow-1 flex-basis-0 p-10 mr-10" style="border: 1px solid #fefefe;border-radius: 5px;box-shadow: 0px 5px 20px 0px rgb(82 63 105 / 8%);}">
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
                                    <!--end::Wizard Step 2 Nav-->
                                </div>
                                <!--end::Wizard Steps-->
                            </div>
                        </div>
                        
                        <div id="wizard-wajib_pajak" style="display: none;">
							<!--begin::Login-->
							<!-- <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login_wp"> -->
								<!--begin::Content-->
								<!-- <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden py-7"> -->
									<!--begin::Content body-->
									<div class="">
										<!--begin::Signin-->
										<div class="login-form login-signin login-signin-wp">
											<!--begin::Form-->
											<form class="form" novalidate="novalidate" id="kt_login_signin_form_wp">												
												<!--begin::Form group-->
												<div class="form-group">
													<label class="font-size-h6 font-weight-bolder text-dark">Email</label>
													<input class="form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" type="text" name="email" autocomplete="off" />
												</div>
												<!--end::Form group-->
												<!--begin::Form group-->
												<div class="form-group">
													<div class="d-flex justify-content-between mt-5">
														<label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
														<a href="javascript:;" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5" id="kt_login_forgot">Forgot Password ?</a>
													</div>
													<input class="form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" type="password" name="password" autocomplete="off" />
												</div>
												<!--end::Form group-->
												<!--begin::Action-->
												<div class="d-flex justify-content-center pb-lg-0 pb-5">
													<!-- <div class="test"> -->
														<button type="button" id="kt_login_signin_mitra" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3 flex-grow-1" onclick="doLogin()">Sign In</button>
														<button type="button" class="btn btn-light-primary font-weight-bolder px-8 py-4 my-3 font-size-lg flex-grow-1">
															<span class="svg-icon svg-icon-md">
																<!--begin::Svg Icon | path:<?= base_url() ?>assets/media/svg/social-icons/google.svg-->
																<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
																	<path d="M19.9895 10.1871C19.9895 9.36767 19.9214 8.76973 19.7742 8.14966H10.1992V11.848H15.8195C15.7062 12.7671 15.0943 14.1512 13.7346 15.0813L13.7155 15.2051L16.7429 17.4969L16.9527 17.5174C18.879 15.7789 19.9895 13.221 19.9895 10.1871Z" fill="#4285F4" />
																	<path d="M10.1993 19.9313C12.9527 19.9313 15.2643 19.0454 16.9527 17.5174L13.7346 15.0813C12.8734 15.6682 11.7176 16.0779 10.1993 16.0779C7.50243 16.0779 5.21352 14.3395 4.39759 11.9366L4.27799 11.9466L1.13003 14.3273L1.08887 14.4391C2.76588 17.6945 6.21061 19.9313 10.1993 19.9313Z" fill="#34A853" />
																	<path d="M4.39748 11.9366C4.18219 11.3166 4.05759 10.6521 4.05759 9.96565C4.05759 9.27909 4.18219 8.61473 4.38615 7.99466L4.38045 7.8626L1.19304 5.44366L1.08875 5.49214C0.397576 6.84305 0.000976562 8.36008 0.000976562 9.96565C0.000976562 11.5712 0.397576 13.0882 1.08875 14.4391L4.39748 11.9366Z" fill="#FBBC05" />
																	<path d="M10.1993 3.85336C12.1142 3.85336 13.406 4.66168 14.1425 5.33717L17.0207 2.59107C15.253 0.985496 12.9527 0 10.1993 0C6.2106 0 2.76588 2.23672 1.08887 5.49214L4.38626 7.99466C5.21352 5.59183 7.50242 3.85336 10.1993 3.85336Z" fill="#EB4335" />
																</svg>
																<!--end::Svg Icon-->
															</span>Sign in with Google</button>
													<!-- </div> -->
												</div>
												<span class="text-muted font-weight-bold font-size-h5 d-block text-center">Belum Punya Akun?
													<a href="javascript:;" id="kt_login_signup" class="text-primary font-weight-bolder">Daftar Akun</a>
												</span>
												<!-- <a href="javascript:;" id="kt_login_signup" class="text-primary font-weight-bolder">Daftar Akun</a> -->

												<!--end::Action-->
											</form>
											<!--end::Form-->
										</div>
										<!--end::Signin-->
										<!--begin::Signup-->
										<div class="login-form login-signup login-signup-wp" style="display: none;">
											<!--begin::Form-->
											<form class="form" novalidate="novalidate" name="kt_login_signup_form" id="kt_login_signup_form">
												<!--begin::Title-->
												<div class="pb-8 pt-10 ">
													<h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Daftar Akun</h3>
													<p class="text-muted font-weight-bold font-size-h4">Lengkapi form berikut sebagai pengajuan pendaftaran akun</p>
												</div>
												<!--end::Title-->
												<!--begin::Form group-->
												<div class="row">
													<div class="col-xl-6">
														<div class="form-group">
															<label class="font-size-h6 font-weight-bolder text-dark">NPWPD</label>
															<input class="form-control h-auto rounded-lg" type="text" name="wajibpajak_npwpd" autocomplete="off" onchange="getNPWPD()" />
														</div>
													</div>
													<div class="col-xl-6">
														<div class="form-group">
															<label class="font-size-h6 font-weight-bolder text-dark">Sektor Usaha</label>
															<input class="form-control form-control-solid h-auto rounded-lg" type="text" name="wajibpajak_usaha_nama" autocomplete="off" readonly="" />
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xl-12">
														<div class="form-group">
															<label class="font-size-h6 font-weight-bolder text-dark">Nama Perusahaan</label>
															<input class="form-control form-control-solid h-auto rounded-lg" type="text" name="wajibpajak_nama" autocomplete="off" readonly="" />
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xl-12">
														<div class="form-group">
															<label class="font-size-h6 font-weight-bolder text-dark">Alamat</label>
															<input class="form-control form-control-solid h-auto rounded-lg" type="text" name="wajibpajak_alamat" autocomplete="off" readonly="" />
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xl-12">
														<div class="form-group">
															<label class="font-size-h6 font-weight-bolder text-dark">Nama Penangung Jawab</label>
															<input class="form-control h-auto rounded-lg" type="text" name="wajibpajak_penanggungjawab" autocomplete="off" />
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xl-12">
														<div class="form-group">
															<label class="font-size-h6 font-weight-bolder text-dark">No Telp Perusahaan</label>
															<input class="form-control h-auto rounded-lg" type="text" name="wajibpajak_telp" autocomplete="off" />
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xl-6">
														<div class="form-group">
															<label class="font-size-h6 font-weight-bolder text-dark">Email Perusahaan</label>
															<input class="form-control h-auto rounded-lg" type="text" name="wajibpajak_email" autocomplete="off" />
														</div>
													</div>
													<div class="col-xl-6">
														<div class="form-group">
															<label class="font-size-h6 font-weight-bolder text-dark">Password</label>
															<input class="form-control h-auto rounded-lg" type="password" name="wajibpajak_password" autocomplete="off" />
														</div>
													</div>
												</div>
												<!--begin::Form group-->
												<div class="form-group d-flex flex-wrap pb-lg-0 pb-3">
													<button type="submit" id="kt_login_signup_mitra" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4" onclick="doSignup()">Submit</button>
													<button type="button" id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3" onclick="cancelSignup()">Cancel</button>
												</div>
												<!--end::Form group-->
											</form>
											<!--end::Form-->
										</div>
										<!--end::Signup-->
										<!--begin::Forgot-->
										<div class="login-form login-forgot" style="display: none;">
											<!--begin::Form-->
											<form class="form" novalidate="novalidate" id="kt_login_forgot_form">
												<!--begin::Title-->
												<div class="pb-13 pt-lg-0 pt-5">
													<h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Forgotten Password ?</h3>
													<p class="text-muted font-weight-bold font-size-h4">Enter your email to reset your password</p>
												</div>
												<!--end::Title-->
												<!--begin::Form group-->
												<div class="form-group">
													<input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg font-size-h6" type="email" placeholder="Email" name="email" autocomplete="off" />
												</div>
												<!--end::Form group-->
												<!--begin::Form group-->
												<div class="form-group d-flex flex-wrap pb-lg-0">
													<button type="button" id="kt_login_forgot_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Submit</button>
													<button type="button" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Cancel</button>
												</div>
												<!--end::Form group-->
											</form>
											<!--end::Form-->
										</div>
										<!--end::Forgot-->
									</div>
									<!--end::Content body-->
								<!-- </div> -->
								<!--end::Content-->
							<!-- </div> -->
							<!--end::Login-->
                        </div>
                        
                        <div id="wizard-pemerintah_daerah" style="display: none;">
                        
                        </div>
						<!--end: Wizard Nav-->
					</div>
					<!--end::Aside Top-->
					<!--begin::Signin-->
					<div class="login-form">
						<!--begin::Form-->
						<form class="form px-10" action="javascript:void(0)" novalidate="novalidate" id="kt_login_signin_form" autocomplete='off'>
							<!--begin: Wizard Step 1-->
							<div class="" data-wizard-type="step-content">
								<!--begin::Title-->
								<div class="pb-5">
									<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Welcome to POS </h3>
									<!-- <h4 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg_">Please login and start your journey</h4> -->
								</div>
								<!--begin::Title-->
								<!--begin::Form Group-->
								<div class="form-group">
									<label class="font-size-h6 font-weight-bolder text-dark">Email</label>
									<input type="email" class="form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" name="email" id="email" placeholder="Type your email" autocomplete="off" required data-fv-email-address___message="The input is not a valid email address"/>
								</div>
								<!--end::Form Group-->
								<!--begin::Form Group-->
								<div class="form-group">
									<label class="font-size-h6 font-weight-bolder text-dark">Password</label>
									<input type="password" class="form-control form-control-solid h-auto py-5 px-6 border-0 rounded-lg font-size-h6" name="password" id="password" placeholder="Type your password" required />
								</div>
								<div class="row">
									<!-- <div class="col-md-6">
										<div class="form-group text-center">
											<div class="g-recaptcha mb-3" data-sitekey="6LfAoKoaAAAAAAdy-U45kpSosIrWRZOXD34pZoxX"></div>
										</div>
									</div> -->
									<!-- <div class="col-md-6">
										<a href="javascript:void(0)" id="btn_reg_project" class="text-primary font-weight-bolder float-right" onclick="onRegister()">Have a Project ? Register now !</a></span>
									</div> -->
								</div>
								<!--end::Form Group-->
							</div>
							<!--end: Wizard Step 1-->
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

	<?php load_view('Javascript') ?>
</body>

</html>