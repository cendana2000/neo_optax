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
	<title>Pemerintah Daerah | <?= $this->config->item('app_title') ?></title>
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
	<!--end::Layout Themes-->
	<link href="assets/css/custom.css?v=1.1" rel="stylesheet" type="text/css" />
	<style>
		:root {
			--color-primary: #003A97;
			--color-danger: #EB5757;
			--color-white: #FFFFFF;
			--color-black: #000000;
			--color-dark-gray: #2B2F38;
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
			background-color: var(--color-primary) !important;
			border-color: var(--color-primary) !important;
		}

		.btn-primary:hover {
			background-color: #003180 !important;
			border-color: #003180 !important;
		}

		.aside,
		.aside-menu,
		.brand {
			background-color: #121212 !important;
		}

		.aside-menu .menu-nav>.menu-item.menu-item-active>.menu-heading,
		.aside-menu .menu-nav>.menu-item.menu-item-active>.menu-link,
		.aside-menu .menu-nav>.menu-item.menu-item-open>.menu-heading,
		.aside-menu .menu-nav>.menu-item.menu-item-open>.menu-link,
		.aside-menu .menu-nav>.menu-item .menu-submenu .menu-item.menu-item-active>.menu-heading,
		.aside-menu .menu-nav>.menu-item .menu-submenu .menu-item.menu-item-active>.menu-link {
			background-color: #2C2C2C !important;
		}

		.aside-menu .menu-nav>.menu-item.menu-item-here>.menu-heading,
		.aside-menu .menu-nav>.menu-item.menu-item-here>.menu-link,
		.aside-menu .menu-nav>.menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover>.menu-heading,
		.aside-menu .menu-nav>.menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover>.menu-link,
		.aside-menu .menu-nav>.menu-item .menu-submenu .menu-item.menu-item-open>.menu-heading,
		.aside-menu .menu-nav>.menu-item .menu-submenu .menu-item.menu-item-open>.menu-link,
		.aside-menu .menu-nav>.menu-item .menu-submenu .menu-item.menu-item-here>.menu-heading,
		.aside-menu .menu-nav>.menu-item .menu-submenu .menu-item.menu-item-here>.menu-link,
		.aside-menu .menu-nav>.menu-item .menu-submenu .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover>.menu-heading,
		.aside-menu .menu-nav>.menu-item .menu-submenu .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover>.menu-link {
			background-color: #2C2C2C !important;
		}

		.btn {
			color: var(--color-black);
		}

		.btn-master,
		.btn-notification {
			background-color: #E7EDF8;
		}

		.btn-master:hover,
		.btn-notification:hover {
			background-color: #dce6f7;
		}

		.label.label-light-danger {
			color: var(--color-white);
			background-color: var(--color-danger);
		}

		.breadcrumb {
			padding: 0;
			margin: 0;
			background-color: transparent;
		}

		.breadcrumb-item {
			align-items: center;
		}

		.breadcrumb .breadcrumb-item:after {
			content: "";
			display: inline-block;
			width: 7px;
			height: 11px;
			background-image: url("data:image/svg+xml,%3Csvg width='7' height='11' viewBox='0 0 7 11' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0.999999 10L5 5.5L1 1' stroke='%23101828' stroke-width='2'/%3E%3C/svg%3E%0A");
			background-repeat: no-repeat;
			background-size: cover;
			padding: 0;
			margin-left: 0.5rem;
		}

		.breadcrumb-item a {
			color: var(--black);
			font-weight: 600;
		}

		.breadcrumb-item a:hover {
			color: #393c41;
		}
	</style>
	<link rel="shortcut icon" href="<?= base_url(); ?>/assets/media/logo.png" />
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
							<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
							<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
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
						<img alt="Logo" style="max-width: 232px; max-height: 35px; vertical-align: middle;" src="<?= base_url('assets/media/LogoBapenda.png'); ?>" />
						<!-- <h2 class="text-white">Pajak</h2> -->
					</a>
					<!--end::Logo-->
					<!--begin::Toggle-->
					<button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
						<span class="svg-icon svg-icon svg-icon-xl">
							<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg-->
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<polygon points="0 0 24 0 24 24 0 24" />
									<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
									<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
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
					<div class="col-12 mt-5 div-input-search-menu">
						<div class="input-icon input-icon-right">
							<input type="text" name="cari" placeholder="Search Menu" id="cari-menu-sidebar" class="form-control form-control-sm" autocomplete="off">
							<span>
								<i class="fa fa-search icon-md"></i>
							</span>
						</div>
					</div>
					<!--begin::Menu Container-->
					<div id="kt_aside_menu" class="aside-menu my-4 scrollbar" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500" style="max-height: 85vh; overflow: auto;">
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
								<div class="dropdown dropdown-inline" data-toggle="tooltip" data-placement="left">
									<span class="menu-text font-weight-bold">PEMERINTAH DAERAH</span>
								</div>
								<?php if (!check_superadmin()) : ?>
									<span class="text-dark font-weight-bolder font-size-base d-none d-md-inline mt-7 mr-3 h5 project_header"></span>
								<?php endif ?>
							</div>
							<!--end::Header Menu-->
						</div>
						<!--end::Header Menu Wrapper-->
						<!--begin::Topbar-->
						<div class="topbar">
							<!-- begin::UserOnline -->
							<div class="topbar-item mr-3" data-toggle="tooltip" title="Toko Online" data-placement="bottom" data-original-title="Toko Online" onclick="showOnlineUser()">
								<div class="btn btn-icon btn-clean btn-lg pulse pulse-primary">
									<i class="fas fa-users"></i>
									<!-- <span class="pulse-ring"></span> -->
								</div>
							</div>
							<!-- end::UserOnline -->
							<!--begin::User-->
							<div class="topbar-item">
								<div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
									<span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Welcome back, </span>
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
							<span class="text-muted font-weight-bold mr-2">2021©</span>
							<a href="#" target="_blank" class="text-dark-75 text-hover-primary">OPTAX</a>
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
						<a href="#" class="navi-item">
							<span class="navi-link p-0 pb-2">
								<span class="navi-icon mr-1">
									<span class="svg-icon svg-icon-lg svg-icon-primary">
										<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-notification.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000" />
												<circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</span>
								<span class="navi-text text-muted text-hover-primary"><?= $this->session->userdata('user_email') ?></span>
							</span>
						</a>
						<a href="javascript:void(0)" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5 menu-link" id="btn-Profile" onclick="HELPER.loadPage(this)" data-menu="profile-Table">Edit Profile</a>
						<a href="<?= base_url('index.php/login/logout') ?>" class="btn btn-sm btn-light-danger font-weight-bolder py-2 px-5">Sign Out</a>
					</div>
				</div>
			</div>
			<div class="separator separator-dashed my-5"></div>
			<div class="navi navi-spacer-x-0 p-0">
				<a target="_blank" href="<?= base_url('/assets/manualbook/Manual_Book_Aplikasi_Tax_System_Bapenda_15_Februari_2023.pdf') ?>" class="navi-item" download="manualbook">
					<div class="navi-link">
						<div class="symbol symbol-40 bg-light mr-3">
							<div class="symbol-label">
								<span class="svg-icon svg-icon-primary svg-icon-2x">
									<!--begin::Svg Icon | \Files\Download.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
											<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1" />
											<path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) " />
										</g>
									</svg>
									<!--end::Svg Icon-->
								</span>
							</div>
						</div>
						<div class="navi-text">
							<div class="font-weight-bold">Manual Book</div>
						</div>
					</div>
				</a>
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
	<script type="text/javascript" src="assets/helper/helper.js?v=1.0.13"></script>

	<?php load_view('main/JavascriptMain') ?>
</body>
<!--end::Body-->

</html>