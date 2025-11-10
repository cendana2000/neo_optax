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
	<title>Pemerintah Daerah | OPTAX</title>
	<meta name="description" content="Tax Monitoring System" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Page Vendors Styles(used by this page)-->
	<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	<!--end::Page Vendors Styles-->
	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="assets/plugins/global/plugins.bundle.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/custom/prismjs/prismjs.bundle.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="assets/css/style.bundle.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<!--end::Global Theme Styles-->
	<!--begin::Layout Themes(used by all pages)-->
	<link href="assets/plugins/custom/lightbox2/lightbox.css" rel="stylesheet" type="text/css">
	<link href="assets/css/themes/layout/header/base/light.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="assets/css/themes/layout/header/menu/light.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="assets/css/themes/layout/brand/dark.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="assets/css/themes/layout/aside/dark.css?v=1.0.15" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/custom/jstree/jstree.bundle.css" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

	<!--begin::Fonts(mandatory for all pages)-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->

	<!--begin::Vendor Stylesheets(used for this page only)-->
	<link href="assets/assets_custom/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/assets_custom/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css" />
	<!--end::Vendor Stylesheets-->


	<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
	<!-- <link href="assets/assets_custom/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" /> -->
	<link href="assets/assets_custom/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<!--end::Global Stylesheets Bundle-->

	<!-- Google tag (gtag.js) -->

	<!--end::Layout Themes-->
	<link href="assets/css/custom.css?v=1.1" rel="stylesheet" type="text/css" />

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
	<style>
		:root {
			--color-primary: #003A97;
			--color-danger: #EB5757;
			--color-white: #FFFFFF;
			--color-black: #000000;
			/* --color-dark-gray: #2B2F38; */
		}

		body {
			color: var(--color-black);
		}

		.text-primary {
			color: var(--color-primary) !important;
		}

		.text-body {
			color: var(--color-black) !important;
		}

		.text-dark-gray {
			color: var(--color-dark-gray) !important;
		}

		.btn-primary {
			background-color: #0c3f9eff !important;
			border-color: #0c3f9eff !important;
		}
	</style>
	<link rel="shortcut icon" href="<?= base_url(); ?>/assets/media/icon_title.png" />
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
	<!--begin::Main-->
	<!--begin::Header Mobile-->
	<div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">
		<!--begin::Logo-->
		<a href="<?= base_url(); ?>">
			<!-- <img alt="Logo" src="assets/media/logos/logo-light.png" /> -->
			<h2 class="text-white">PEMERINTAH DAERAH</h2>
		</a>
		<!--end::Logo-->
		<!--begin::Toolbar-->
		<div class="d-flex align-items-center">
			<!--begin::Aside Mobile Toggle-->
			<button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
				<span></span>
			</button>
			<!--end::Aside Mobile Toggle-->
			<!--begin::Header Menu Mobile Toggle-->
			<button class="btn p-0 burger-icon ml-4 d-none" id="kt_header_mobile_toggle">
				<span></span>
			</button>
			<!--end::Header Menu Mobile Toggle-->
			<!--begin::Topbar Mobile Toggle-->
			<button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
				<span class="svg-icon svg-icon-xl">
					<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<polygon points="0 0 24 0 24 24 0 24" />
							<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#ffffff" fill-rule="nonzero" />
							<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#ffffff" fill-rule="nonzero" />
						</g>
					</svg>
					<!--end::Svg Icon-->
				</span>
			</button>
			<!--end::Topbar Mobile Toggle-->
		</div>
		<!--end::Toolbar-->
	</div>
	<!--end::Header Mobile-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="d-flex flex-row flex-column-fluid page">
			<!--begin::Aside-->
			<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
				<!--begin::Brand-->
				<div class="brand flex-column-auto" id="kt_brand">
					<!--begin::Logo-->
					<a href="<?= base_url(); ?>" class="brand-logo align-items-center">
						<!-- <img alt="Logo" src="assets/media/logos/logo-light.png" /> -->
						<img alt="Logo" style="margin-top:20px; max-width: 550px; max-height: 100px; vertical-align: middle;" src="<?= base_url('assets/media/logo_optax_1/logo_optax_1.png'); ?>" />
						<!-- <h2 class="text-white">Pajak</h2> -->
					</a>
					<!--end::Logo-->
					<!--begin::Toggle-->
					<button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
						<span class="svg-icon svg-icon svg-icon-xl svg-icon-white">
							<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg-->
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<polygon points="0 0 24 0 24 24 0 24" />
									<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#ffffffff" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
									<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#ffffffff" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
								</g>
							</svg>
							<!--end::Svg Icon-->
						</span>
					</button>
					<!--end::Toolbar-->
				</div>
				<!--end::Brand-->
				<!--begin::Aside Menu-->
				<div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
					<!-- <div class="col-12 mt-5 div-input-search-menu">
						<div class="input-icon input-icon-right">
							<input type="text" name="cari" placeholder="Search Menu" id="cari-menu-sidebar" class="form-control form-control-sm" autocomplete="off">
							<span>
								<i class="fa fa-search icon-md"></i>
							</span>
						</div>
					</div> -->
					<!--begin::Menu Container-->
					<div id="kt_aside_menu" class="aside-menu my-4 scrollbar" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500" style="max-height: 85vh; overflow: auto; color: white;">
						<!--begin::Menu Nav-->
						<ul class="menu-nav">
							<?= $menu ?>
						</ul>
						<!--end::Menu Nav-->
					</div>
					<!--end::Menu Container-->
				</div>
				<!--end::Aside Menu-->
			</div>
			<!--end::Aside-->
			<!--begin::Wrapper-->
			<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
				<!--begin::Header-->
				<div id="kt_header" class="header header-fixed">
					<!--begin::Container-->
					<div class="container-fluid d-flex align-items-stretch justify-content-between">
						<!--begin::Header Menu Wrapper-->
						<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
							<!--begin::Header Menu-->
							<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default align-items-center">
								<!-- <div class="dropdown dropdown-inline" data-toggle="tooltip" data-placement="left">
									<span class="menu-text font-weight-bold">DASHBOARD PEMERINTAH DAERAH</span>
								</div> -->
								<?php if (!check_superadmin()) : ?>
									<span class="text-dark font-weight-bolder font-size-base d-none d-md-inline mt-7 mr-3 h5 project_header"></span>
								<?php endif ?>
							</div>
							<!--end::Header Menu-->
						</div>
						<!--end::Header Menu Wrapper-->
						<!--begin::Topbar-->
						<div class="topbar">
							<!--begin::Notifications-->
							<!-- <div class="dropdown" id="nav-notification">
								<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
									<div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1 pulse pulse-primary">
										<span class="svg-icon svg-icon-xl svg-icon-primary">
											<i class="fa fa-bell"></i>
										</span>
										<span class="pulse-ring d-none"></span>
										<span class="label label-sm label-light-danger rounded-circle font-weight-bolder position-absolute d-none" id="badge-notif-top" style="top: -4px; right: -4px;">0</span>
									</div>
								</div>
								<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
									<form>
										<div class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top" style="background-color: #2f46ac;background-image: url(assets/media/misc/bg-1.jpg)">
											<h4 class="d-flex flex-center rounded-top">
												<span class="text-white">Notifikasi</span>
											</h4>
											<a href="javascript:void(0)" style="position: absolute;top: 10px;right: 10px;" onclick="showAllNotif()" title="Refresh Notifikasi"><i class="fa fa-sync text-white"></i></a>
											<ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3 px-8" role="tablist">
												<li class="nav-item">
													<a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_unread">
														Baru <span class="label label-sm label-light-danger label-rounded font-weight-bolder " id="badge-notif-unread" style="display: none;">0</span>
													</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" data-toggle="tab" href="#topbar_notifications_read">
														Dibaca
													</a>
												</li>
												<li class="nav-item d-none">
													<a class="nav-link" data-toggle="tab" href="#topbar_notifications_confirm">
														Konfirmasi <span class="label label-sm label-light-danger label-rounded font-weight-bolder " id="badge-notif-confirm" style="display: none;">0</span>
													</a>
												</li>
											</ul>
										</div>
										<div class="tab-content">
											<div class="tab-pane active show p-8" id="topbar_notifications_unread" role="tabpanel">
												<div class="scroll pr-7 mr-n7" data-scroll="true" data-height="300" data-mobile-height="200">
													<div id="div_notif_unread">

													</div>
													<button type="button" class="btn btn-sm btn-primary" id="btn_notif_unread" style="display: none;">Load More <i class="fa fa-angle-double-down"></i></button>
												</div>
											</div>
											<div class="tab-pane p-8" id="topbar_notifications_read" role="tabpanel">
												<div class="scroll pr-7 mr-n7" data-scroll="true" data-height="300" data-mobile-height="200">
													<div id="div_notif_read">

													</div>
													<button type="button" class="btn btn-sm btn-primary" id="btn_notif_read" style="display: none;">Load More <i class="fa fa-angle-double-down"></i></button>
												</div>
											</div>
											<div class="tab-pane p-8" id="topbar_notifications_confirm" role="tabpanel">
												<div class="scroll pr-7 mr-n7" data-scroll="true" data-height="300" data-mobile-height="200">
													<div id="div_notif_confirm">

													</div>
													<button type="button" class="btn btn-sm btn-primary" id="btn_notif_confirm" style="display: none;">Load More <i class="fa fa-angle-double-down"></i></button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div> -->
							<div class="topbar-item">
								<div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
									<!-- <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Welcome back, </span> -->
									<span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3"><?= $this->session->userdata('pegawai_nama') ?></span>
									<span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
										<span class="symbol-label font-size-h5 font-weight-bold"><i class="fa fa-user-alt icon-md"></i></span>
									</span>
								</div>
							</div>
							<!--end::User-->
						</div>
						<!--end::Topbar-->
					</div>
					<!--end::Container-->
				</div>
				<!--end::Header-->
				<!--begin::Content-->
				<div class="content d-flex flex-column flex-column-fluid mx-10 mt-5" id="kt_content">

				</div>
				<!--end::Content-->
				<!--begin::Footer-->
				<div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
					<!--begin::Container-->
					<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
						<!--begin::Copyright-->
						<div class="text-dark order-2 order-md-1">
							<span class="text-muted font-weight-bold mr-2">2025©</span>
							<a href="#" target="_blank" class="text-dark-75 text-hover-primary">PT. Cendana Teknika Utama</a>
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
	<!-- begin::User Panel-->
	<div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
		<!--begin::Header-->
		<div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
			<h3 class="font-weight-bold m-0">User Profile
			</h3>
			<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
				<i class="ki ki-close icon-xs text-muted"></i>
			</a>
		</div>
		<!--end::Header-->
		<!--begin::Content-->
		<div class="offcanvas-content pr-5 mr-n5">
			<!--begin::Header-->
			<div class="d-flex align-items-center mt-5">
				<div class="symbol symbol-100 mr-5">
					<?php
					$user_foto = $this->session->userdata('pegawai_foto');
					if ($user_foto) {
						$user_foto = base_url('./dokumen/user/' . $user_foto);
					} else {
						$user_foto = base_url('./assets/media/noimage.png');
					}
					?>
					<div class="symbol-label" style="background-image:url(<?= $user_foto ?>)"></div>
					<i class="symbol-badge bg-success"></i>
				</div>
				<div class="d-flex flex-column">
					<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary"><?= $this->session->userdata('pegawai_nama') ?></a>
					<div class="navi mt-2">
						<a href="javascript:void(0)" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5 menu-link" id="btn-Profile" onclick="HELPER.loadPage(this)" data-menu="profile-Table">Edit Profile</a>
						<a href="<?= base_url('index.php/login/logout') ?>" class="btn btn-sm btn-light-danger font-weight-bolder py-2 px-5">Sign Out</a>
					</div>
				</div>
			</div>
			<div class="separator separator-dashed my-5"></div>
			<div class="navi navi-spacer-x-0 p-0">
				<a href="javascript:void(0)" class="navi-item" onclick="onChangeLog()">
					<div class="navi-link">
						<div class="symbol symbol-40 bg-light mr-3">
							<div class="symbol-label">
								<span class="svg-icon svg-icon-primary svg-icon-2x">
									<!--begin::Svg Icon | \Shopping\Chart-bar1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5" />
											<rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5" />
											<path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero" />
											<rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5" />
										</g>
									</svg>
									<!--end::Svg Icon-->
								</span>
							</div>
						</div>
						<div class="navi-text">
							<div class="font-weight-bold">Change Log</div>
						</div>
					</div>
				</a>
			</div>
			<div class="separator separator-dashed my-5"></div>
			<!-- <div style="display: <?= check_superadmin() ? "none" : "" ?>;"> -->
			<!-- <div style="">
				<h4 class="card-label">
					Toko Online
				</h4>
				<div class="d-flex flex-column" id="user_active">
				</div>
			</div> -->
		</div>
		<!--end::Content-->
	</div>
	<!-- end::User Panel-->

	<!--begin::Scrolltop-->
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
	<!--end::Scrolltop-->

	<div class="modal fade" tabindex="-1" role="dialog" id="modalChangelog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modal-title">Changelog Aplikasi</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i aria-hidden="true" class="ki ki-close"></i>
					</button>
				</div>
				<div class="card-body">
					<div class="timeline timeline-justified timeline-4">
						<div class="timeline-bar"></div>
						<div class="timeline-items" id="list-changelog">
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-12 text-right">
							<button type="button" class="btn btn-sm btn-danger mx-1" data-dismiss="modal" aria-label="Close"> Tutup</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div style="width: 46px;
		position: fixed;
		bottom: 40px; 
		right: 56px;
		list-style: none;
		margin: 0;
		z-index: 95;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
		border-radius: 0.42rem;
	">
		<!--begin::Item-->
		<!-- <div id="kt_sticky_toolbar_chat_toggler" data-toggle="tooltip" title="" data-placement="left" data-original-title="User Online">
			<a class="btn btn-success btn-icon" role="button" href="#" onclick="showOnlineUser()">
				<i class="fa fa-users"></i>
			</a>
		</div> -->
		<!--end::Item-->
	</div>

	<script>
		var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";
	</script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
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

		document.addEventListener("DOMContentLoaded", function() {
			var options = {
				chart: {
					type: 'line',
					height: 300
				},
				series: [{
					name: 'Sales',
					data: [10, 20, 30, 40, 50, 60, 70]
				}],
				xaxis: {
					categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']
				}
			};

			var chart = new ApexCharts(document.querySelector("#kt_charts_widget_3"), options);
			chart.render();
		});
	</script>
	<!--end::Global Config-->

	<!--begin::Global Theme Bundle(used by all pages)-->
	<script type="text/javascript" src="assets/plugins/global/plugins.bundle.js?v=1.0.12"></script>
	<script type="text/javascript" src="assets/plugins/custom/prismjs/prismjs.bundle.js?v=1.0.12"></script>
	<script type="text/javascript" src="assets/js/scripts.bundle.js?v=1.0.12"></script>
	<!--end::Global Theme Bundle-->
	<!--begin::Page Vendors(used by this page)-->
	<script type="text/javascript" src="assets/plugins/custom/jstree/jstree.bundle.js"></script>
	<script type="text/javascript" src="assets/plugins/custom/lightbox2/lightbox.js"></script>
	<!-- <script type="text/javascript" src="assets/js/pages/id_ID_FormValidation.js"></script> -->
	<script type="text/javascript" src="assets/helper/js.cookie.min.js"></script>
	<script type="text/javascript" src="assets/plugins/custom/moment/moment-with-locale.js"></script>
	<script type="text/javascript" src="assets/plugins/custom/blockui/jquery.blockui.js"></script>
	<script type="text/javascript" src="assets/plugins/custom/inputmask/jquery.inputmask.min.js"></script>
	<script type="text/javascript" src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
	<script type="text/javascript" src="assets/plugins/custom/clock/jquery-clock-timepicker.min.js"></script>
	<script type="text/javascript" src="assets/helper/fnReloadAjax.js"></script>
	<!-- <script src="<?php echo $this->config->item('base_theme') ?>plugins/jquery.number.min.js" type="text/javascript"></script> -->
	<script type="text/javascript" src="assets/plugins/custom/jqueryNumber/jquery.number.min.js"></script>

	<!--end::Page Vendors-->
	<script>
		BASE_URL = "<?php echo base_url() ?>index.php/";
		BASE_URL_NO_INDEX = "<?php echo base_url() ?>";
		BASE_ASSETS = "<?php echo base_url() ?>assets/";
		BASE_CONTENT = "<?= base_url('dokumen/') ?>"
	</script>


	<script src="<?php echo base_url(); ?>assets/helper/FCM.js"></script>

	<script src="https://www.gstatic.com/firebasejs/8.7.0/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.7.0/firebase-messaging.js"></script>
	<script type="text/javascript" src="assets/helper/helper.js?v=1.0.13"></script>


	<!--begin::Javascript-->
	<script>
		var hostUrl = "https://preview.keenthemes.com/metronic8/demo1/assets/";
	</script>

	<!--end::Custom Javascript-->

	<!-- Script For Fcm -->
	<script type="text/javascript">
		// /*
		FCM.setConfig('<?= $this->config->item('config_fcm') ?>');

		$(function() {
			if (Notification.permission === 'granted') {
				reqPermission()
			} else {
				if (parseInt(window.localStorage.getItem('denied_fcm')) != 1) {
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
			}
		})
		// */

		function reqPermission() {
			FCM.reqPermission({
				callback: function(response) {
					if (response) {
						FCM.getToken({
							callback: function(res) {
								if (window.localStorage.getItem('fcm_token') != res.token) {
									updateTokenLogin(res.token)
								}
							}
						});
					} else {
						if (parseInt(window.localStorage.getItem('denied_fcm')) != 1) {
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
						window.localStorage.setItem('denied_fcm', 1)
					}
					HELPER.unblock(100)
				}
			});
		}

		function updateTokenLogin(token) {
			let token_old = window.localStorage.getItem('fcm_token')
			HELPER.ajax({
				url: BASE_URL + 'login/updateTokenLogin',
				data: {
					token_old: token_old,
					token: token,
				},
				success: function(res) {
					window.localStorage.setItem('fcm_token', token)
				}
			})
		}

		document.addEventListener("DOMContentLoaded", function() {
			var toggle = document.getElementById("kt_quick_user_toggle");
			var panel = document.getElementById("kt_quick_user");
			var closeBtn = document.getElementById("kt_quick_user_close");

			function openPanel() {
				panel.classList.add("show");
				panel.style.visibility = "visible";
			}

			function closePanel() {
				panel.classList.remove("show");
				panel.style.visibility = "hidden";
			}

			if (toggle && panel) {
				toggle.addEventListener("click", function(e) {
					e.preventDefault();
					openPanel();
				});
			}

			if (closeBtn && panel) {
				closeBtn.addEventListener("click", function(e) {
					e.preventDefault();
					closePanel();
				});
			}

			// ✅ Close kalau klik di luar panel
			document.addEventListener("click", function(e) {
				if (panel.classList.contains("show")) {
					if (!panel.contains(e.target) && !toggle.contains(e.target)) {
						closePanel();
					}
				}
			});
		});
	</script>
	<!-- End Script For Fcm -->

	<?php load_view('main/JavascriptMain') ?>
	<?php load_view('main/JavascriptNotif') ?>
</body>
<!--end::Body-->

</html>