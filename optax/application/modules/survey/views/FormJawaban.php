<!DOCTYPE html>
<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 11 & VueJS Admin Dashboard Theme
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
	<base href="">
	<meta charset="utf-8" />
	<title>Survey - <?= $this->config->item('app_title') ?></title>
	<meta name="description" content="Tax Monitoring System" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Page Vendors Styles(used by this page)-->
	<link href="<?php echo base_url() ?>assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	<!--end::Page Vendors Styles-->
	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="<?php echo base_url() ?>assets/plugins/global/plugins.bundle.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url() ?>assets/plugins/custom/prismjs/prismjs.bundle.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url() ?>assets/css/style.bundle.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<!--end::Global Theme Styles-->
	<!--begin::Layout Themes(used by all pages)-->
	<link href="<?php echo base_url() ?>assets/plugins/custom/lightbox2/lightbox.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url() ?>assets/css/themes/layout/header/base/light.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url() ?>assets/css/themes/layout/header/menu/light.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url() ?>assets/css/themes/layout/brand/dark.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url() ?>assets/css/themes/layout/aside/dark.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url() ?>assets/plugins/custom/jstree/jstree.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url() ?>assets/css/pages/wizard/wizard-3.css?v=7.2.8" rel="stylesheet" type="text/css" />
	<!--end::Layout Themes-->
	<link href="<?php echo base_url() ?>assets/css/custom.css?v=1.1" rel="stylesheet" type="text/css" />
	<style>

	</style>
	<link rel="shortcut icon" href="<?php echo base_url() ?>assets/media/logo.png" />
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="d-flex flex-row flex-column-fluid page">
			<!--begin::Wrapper-->
			<div class="d-flex flex-column flex-row-fluid" id="kt_wrapper">
				<!--begin::Content-->
				<div class="content d-flex flex-column flex-column-fluid mt-0 py-0" id="kt_content">
          <div class="py-5 py-lg-10" id="kt_subheader" style="height:150px;background-color: #663259; background-position: right bottom; background-size: auto 100%; background-repeat: no-repeat; background-image: url(<?php echo base_url() ?>assets/media/svg/patterns/taieri.svg)">
          </div>
          <div class="container pt-10" style="margin-top: -125px" id="kt_content_container">
            <div class="row justify-content-center">
              <div class="col-lg-8 col-sm-12">
                <div class="card card-custom">
									<div class="card-body p-0">
										<!--begin: Wizard-->
										<div class="wizard wizard-3" id="kt_wizard_v1" data-wizard-state="first" data-wizard-clickable="true">
											<!--begin: Wizard Nav-->
											<div class="wizard-nav">
												<div class="wizard-steps px-8 py-8 py-lg-3">
													<!--begin::Wizard Step 1 Nav-->
													<div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
														<div class="wizard-label">
															<h3 class="wizard-title">
															<span>1.</span>Responden</h3>
															<div class="wizard-bar"></div>
														</div>
													</div>
													<!--end::Wizard Step 1 Nav-->
													<!--begin::Wizard Step 2 Nav-->
													<div class="wizard-step" data-wizard-type="step" data-wizard-state="pending">
														<div class="wizard-label">
															<h3 class="wizard-title">
															<span>2.</span>Form Survey</h3>
															<div class="wizard-bar"></div>
														</div>
													</div>
													<!--end::Wizard Step 2 Nav-->
												</div>
											</div>
											<!--end: Wizard Nav-->
											<!--begin: Wizard Body-->
											<div class="row justify-content-center pt-0 pb-10 px-8 pb-lg-12 px-lg-10">
												<div class="col-xl-12">
													<!-- begin: Alert -->
													<div class="alert alert-custom alert-light-danger fade show mb-8 d-none" id="alert_tidak_aktif" role="alert">
															<div class="alert-icon"><i class="flaticon-warning"></i></div>
															<div class="alert-text">Survey tidak tersedia.</div>
													</div>
													<!-- end: Alert -->
													<!-- begin: Banner -->
													<div class="mb-5" id="banner" style="display: none;">
														<img src="<?= base_url() ?>" onerror="imgError(this);" class="img-fluid rounded" style="width: 100%; height: 200px; object-fit: cover;" alt="banner">
													</div>
													<!-- end: Banner -->
													<!--begin: Wizard Form-->
													<form class="form fv-plugins-bootstrap fv-plugins-framework" action="javascript:save('form-survey')" method="post" id="form-survey" name="form-survey" autocomplete="off">
														<!--begin: Wizard Step 1-->
														<div class="pb-0" data-wizard-type="step-content" data-wizard-state="current">
															<span class="badge badge-success mb-2" id="step_1_toko">Toko tidak ditemukan</span>
                              <div class="d-flex flex-column mb-5">
                                <h2 class="h1 font-weight-bolder" id="step_1_judul">-</h2>
                                <span class="font-weight-bold text-muted" id="step_1_deskripsi">-</span>
                              </div>
                              <div class="form-group border-top pt-5 d-none">
                                <label>Nama</label>
																<input type="hidden" name="survey_id" id="survey_id"/>
                                <input type="text" class="form-control" name="survey_responden_nama" id="survey_responden_nama" placeholder="Masukkan Nama Anda"/>
                              </div>
                              <div class="form-group d-none">
                                <label>Email</label>
                                <input type="email" class="form-control" name="survey_responden_email" id="survey_responden_email" placeholder="contoh: example@domain.com"/>
                              </div>
                              <div class="form-group d-none">
                                <label>Alamat</label>
                                <textarea class="form-control" rows="3" name="survey_responden_alamat" id="survey_responden_alamat" placeholder="Masukkan alamat Anda"></textarea>
                              </div>
														</div>
														<!--end: Wizard Step 1-->
														<!--begin: Wizard Step 2-->
														<div class="pb-0" data-wizard-type="step-content">
															<span class="badge badge-success mb-2" id="step_2_toko">Toko tidak ditemukan</span>
                              <div class="d-flex flex-column">
                                <h2 class="h1 font-weight-bolder" id="step_2_judul">-</h2>
                                <span class="font-weight-bold text-muted" id="step_2_deskripsi">-</span>
                              </div>															
                              <div class="mt-5" id="place_pertanyaan">
                                <!-- <div class="form-group border-top pt-5">
                                  <label class="font-weight-bold h6" id="judul_pertanyaan_0"><span class="text-danger">*</span> Pertanyaan 1</label>
																	<div id="answer_input_0">
																		<div class="radio-list mt-5">
																			<label class="radio radio-success">
																				<input type="radio" name="radios1"/>
																				<span></span>
																				Default
																			</label>
																		</div>
																	</div>
                                </div>
                                <div class="form-group border-top pt-5">
                                  <label class="font-weight-bold h6">Pertanyaan 2</label>
																	<div id="answer_input">
																		<textarea class="form-control mt-5" rows="5" placeholder="Masukan Jawaban"></textarea>
																	</div>
                                </div> -->
                              </div>
														</div>
														<!--begin: Wizard Actions-->
														<div class="d-flex justify-content-between border-top mt-5 pt-10">
															<div class="mr-2">
																<button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-prev">Previous</button>
															</div>
															<div>
																<button type="button" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit">Submit</button>
																<button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-next">Next</button>
															</div>
														</div>
														<!--end: Wizard Actions-->
													<div></div><div></div><div></div><div></div></form>
													<!--end: Wizard Form-->
												</div>
											</div>
											<!--end: Wizard Body-->
										</div>
										<!--end: Wizard-->
									</div>
								</div>
              </div>
            </div>
          </div>
				</div>
				<!--end::Content-->
				<!--begin::Footer-->
				<div class="footer py-10 d-flex flex-lg-column" id="kt_footer">
					<!--begin::Container-->
					<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-center">
						<!--begin::Copyright-->
						<div class="text-dark order-2 order-md-1">
							<a href="#" target="_blank" class="text-dark-75 font-weight-bolder text-muted text-hover-primary" style="font-size:35px">PIS Survey</a>
						</div>
						<!--end::Copyright-->
						<!--begin::Nav-->
						<div class="nav nav-dark">
						</div>
						<!--end::Nav-->
					</div>
					<!--end::Container-->
				</div>
				<!--end::Footer-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::Main-->

	<!--begin::Scrolltop-->
	<div id="kt_scrolltop" class="scrolltop">
		<span class="svg-icon">
			<!--begin::Svg Icon | path:<?php echo base_url() ?>assets/media/svg/icons/Navigation/Up-2.svg-->
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
	<!--end::Scrolltop-->

	<script>
		var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";
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
	<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/global/plugins.bundle.js?v=1.0.12"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/custom/prismjs/prismjs.bundle.js?v=1.0.12"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/js/scripts.bundle.js?v=1.0.12"></script>
	<!--end::Global Theme Bundle-->
	<!--begin::Page Vendors(used by this page)-->
	<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/custom/jstree/jstree.bundle.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/custom/lightbox2/lightbox.js"></script>
	<!-- <script type="text/javascript" src="<?php echo base_url() ?>assets/js/pages/id_ID_FormValidation.js"></script> -->
	<script type="text/javascript" src="<?php echo base_url() ?>assets/helper/js.cookie.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/custom/moment/moment-with-locale.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/custom/blockui/jquery.blockui.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/custom/inputmask/jquery.inputmask.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/custom/clock/jquery-clock-timepicker.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/helper/fnReloadAjax.js"></script>
	<!-- <script src="<?php echo $this->config->item('base_theme') ?>plugins/jquery.number.min.js" type="text/javascript"></script> -->
	<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/custom/jqueryNumber/jquery.number.min.js"></script>

	<!--end::Page Vendors-->
	<script>
		BASE_URL = "<?php echo base_url() ?>index.php/";
		BASE_URL_NO_INDEX = "<?php echo base_url() ?>";
		BASE_ASSETS = "<?php echo base_url() ?>assets/";
		BASE_CONTENT = "<?= base_url('dokumen/') ?>"
	</script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/helper/helper.js?v=1.0.13"></script>

	<?php load_view('survey/JavascriptJawaban') ?>
</body>
<!--end::Body-->

</html>