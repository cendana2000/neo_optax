<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Check if app open duplicately -->
	<script>
		// Broadcast that you're opening a page.
		localStorage.openpageskasir = Date.now();
		var onLocalStorageEvent = function(e) {
			if (e.key == "openpageskasir") {
				// Listen if anybody else is opening the same page!
				localStorage.page_available = Date.now();
			}
			if (e.key == "page_available") {
				window.location.href = "<?= base_url() ?>index.php/block";
			}
		};
		window.addEventListener('storage', onLocalStorageEvent, false);
	</script>

	<base href="">
	<meta charset="utf-8" />
	<title>POS | <?= $this->session->userdata('toko_nama'); ?></title>
	<meta name="description" content="Drilling Management System" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
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
	<link rel="shortcut icon" href="<?= base_url(); ?>/assets/media/icon_title.png" />
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
	</style>
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
			<h2 class="text-white">POS</h2>
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
						<img alt="Logo" style="margin-top:20px; max-width: 550px; max-height: 100px; vertical-align: middle;" src="<?= base_url('assets/media/logo_optax_1/logo_optax_1.png'); ?>" />
					</a>
					<!--end::Logo-->
					<!--begin::Toggle-->
					<button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
						<span class="svg-icon svg-icon svg-icon-xl svg-icon-white">
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
							<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default align-items-center" style="gap: 10px;">
								<div class="dropdown dropdown-inline" data-toggle="tooltip" data-placement="left">
									<h3 class="menu-text"><?= $this->session->userdata('toko_nama'); ?></h3>

								</div>

								<!-- <span class="text-dark font-weight-bolder font-size-base d-none d-md-inline mt-7 mr-3 h5 project_header"><?= $this->session->userdata('toko_nama'); ?></span> -->
								<!-- <?php if (!check_superadmin()) : ?> -->
								<!-- <span class="text-dark font-weight-bolder font-size-base d-none d-md-inline mt-7 mr-3 h5 project_header"></span> -->
								<!-- <?php endif ?> -->
							</div>
							<!--end::Header Menu-->
						</div>
						<!--end::Header Menu Wrapper-->
						<!--begin::Topbar-->
						<div class="topbar">
							<!--begin::Notifications-->
							<!-- <div class="dropdown">								
								<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
									<div class="btn btn-notification btn-icon btn-dropdown mr-5 pulse pulse-primary rounded-circle">
										<span class="material-icons text-primary">
											notifications
										</span>
										<span class="label label-sm label-light-danger rounded-circle font-weight-bolder position-absolute d-none" id="badge-notif-top" style="top: -4px; right: -4px;">0</span>
									</div>
								</div>
								<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
									<form>
										<div class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top" style="background-image: url(<?= base_url('assets/media/misc/bg-1.jpg') ?>)">
											<h4 class="d-flex flex-center rounded-top">
												<span class="text-white">Notifications</span>
												<span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">0 new</span>
											</h4>
											<ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3 px-8" role="tablist">
												<li class="nav-item">
													<a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_logs">Recent</a>
												</li>
											</ul>
										</div>
										<div class="tab-content">
											<div class="tab-pane active show" id="topbar_notifications_logs" role="tabpanel">
												<div class="d-flex flex-center text-center text-muted min-h-200px">All caught up!
													<br />No new notifications.
												</div>
											</div>
										</div>
									</form>
								</div>
							</div> -->
							<!--end::Notifications-->
							<!-- begin::QR -->
							<!-- <div class="dropdown">
								<div class="topbar-item mr-3" data-toggle="dropdown" data-offset="10px,0px" aria-expanded="false">
									<div class="btn btn-icon btn-clean btn-dropdown btn-lg pulse pulse-primary">
										<i class="fas fa-qrcode"></i>
									</div>
								</div>
								<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg" style="">
									<div class="py-8 text-center">
										<span class="font-weight-bolder d-block">Scan disini</span>
										<span class="text-muted font-weight-bold">untuk masuk menggunakan mobile</span>
										<div id="qrcode" class="d-flex justify-content-center mt-8"></div>
									</div>
								</div>
							</div> -->
							<!-- end::QR -->
							<!--begin::User-->
							<div class="topbar-item">
								<div class="btn btn-icon btn-icon-mobile w-auto d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
									<span class="font-size-base d-none d-md-inline mr-1">Welcome, </span>
									<span class="font-weight-bolder font-size-base d-none d-md-inline mr-3"><?= $this->session->userdata('user_nama') ?></span>
									<span class="symbol symbol-lg-35 symbol-25">
										<span class="symbol-label font-size-h5 font-weight-bold rounded-circle"><i class="fa fa-user-alt icon-md"></i></span>
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
				<div class="content d-flex flex-column flex-column-fluid mx-10 position-relative" id="kt_content">

				</div>
				<!--end::Content-->
				<!--begin::Footer-->
				<div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
					<!--begin::Container-->
					<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
						<!--begin::Copyright-->
						<div class="text-dark order-2 order-md-1">
							<span class="text-muted font-weight-bold">2025&copy;</span>
							<a href="javascript:void();" class="text-dark-75 text-hover-primary">PT. Cendana Teknika Utama</a>
						</div>
						<!--end::Copyright-->
						<!--begin::Nav-->
						<div class="nav nav-dark">
							<span class="text-muted font-weight-bold mr-2">V 2.0.0</span>
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
					$user_foto = $this->session->userdata('user_foto');
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
					<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary"><?= $this->session->userdata('user_nama') ?></a>
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
						<button class="btn btn-sm btn-light-danger font-weight-bolder py-2 px-5" onclick="onLogout()">Sign Out</button>
						<div class="row mt-3">
							<!-- <div class="col-8">
								<a target="_blank" href="<?= $_ENV['PAJAK_URL'] ?>" class="btn btn-sm btn-light-success font-weight-bolder py-2 px-5">Monitoring Pajak</a>
							</div> -->
						</div>
					</div>
				</div>
			</div>
			<div class="separator separator-dashed my-5"></div>
			<div class="navi navi-spacer-x-0 p-0">
				<!-- <a target="_blank" href="<?= base_url('/assets/manualbook/Manual_Book_POS_14_Februari_2023.pdf') ?>" class="navi-item" download="manualbook">
					<div class="navi-link">
						<div class="symbol symbol-40 bg-light mr-3">
							<div class="symbol-label">
								<span class="svg-icon svg-icon-primary svg-icon-2x">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
											<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1" />
											<path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) " />
										</g>
									</svg>
								</span>
							</div>
						</div>
						<div class="navi-text">
							<div class="font-weight-bold">Manual Book</div>
						</div>
					</div>
				</a> -->
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
			<div style="display: none">
				<h4 class="card-label">
					Project Detail
				</h4>
				<div class="navi navi-spacer-x-0 p-0">
					<!--begin::Item-->
					<div class="navi-item">
						<div class="navi-link">
							<div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<!--begin::Svg Icon | path:assets/media/svg/icons/General/Notification2.svg-->
										<!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Design\Rectangle.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<rect fill="#000000" x="4" y="4" width="16" height="16" rx="2" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
									<!--end::Svg Icon-->
									</span>
								</div>
							</div>
							<div class="navi-text">
								<div class="font-weight-bold">Project Code</div>
								<div class="text-muted main-project_code">Account settings and more</div>
							</div>
						</div>
					</div>
					<!--end:Item-->
					<!--begin::Item-->
					<div href="custom/apps/user/profile-3.html" class="navi-item">
						<div class="navi-link">
							<div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-warning">
										<!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Chart-bar1.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</div>
							</div>
							<div class="navi-text">
								<div class="font-weight-bold">Project Location</div>
								<div class="text-muted main-project_location">Inbox and tasks</div>
							</div>
						</div>
					</div>
					<!--end:Item-->
					<!--begin::Item-->
					<div href="custom/apps/user/profile-2.html" class="navi-item">
						<div class="navi-link">
							<div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-primary">
										<!--begin::Svg Icon | path:assets/media/svg/icons/Files/Selected-file.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3" />
												<path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000" />
												<rect fill="#000000" opacity="0.3" x="7" y="10" width="5" height="2" rx="1" />
												<rect fill="#000000" opacity="0.3" x="7" y="14" width="9" height="2" rx="1" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</div>
							</div>
							<div class="navi-text">
								<div class="font-weight-bold">Project Start Date</div>
								<div class="text-muted main-project_start_date">Logs and notifications</div>
							</div>
						</div>
					</div>
					<!--end:Item-->
					<!--begin::Item-->
					<div href="custom/apps/userprofile-1/overview.html" class="navi-item">
						<div class="navi-link">
							<div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-danger">
										<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-opened.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3" />
												<path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000" />
												<rect fill="#000000" opacity="0.3" x="7" y="10" width="5" height="2" rx="1" />
												<rect fill="#000000" opacity="0.3" x="7" y="14" width="9" height="2" rx="1" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</div>
							</div>
							<div class="navi-text">
								<div class="font-weight-bold">Project Finish Date</div>
								<div class="text-muted main-project_end_date">latest tasks and projects</div>
							</div>
						</div>
					</div>
					<!--end:Item-->
				</div>
				<div class="separator separator-dashed my-5"></div>
				<h4 class="card-label">
					Switch to another project
				</h4>
				<div id="main_list_project"></div>
			</div>
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
	<script type="text/javascript" src="assets/plugins/custom/qrcodejs/qrcode.min.js"></script>
	<!-- <script src="<?php echo $this->config->item('base_theme') ?>plugins/jquery.number.min.js" type="text/javascript"></script> -->
	<script type="text/javascript" src="assets/plugins/custom/jqueryNumber/jquery.number.min.js"></script>

	<!--end::Page Vendors-->
	<script>
		BASE_URL = "<?php echo base_url() ?>index.php/";
		BASE_URL_NO_INDEX = "<?php echo base_url() ?>";
		BASE_ASSETS = "<?php echo base_url() ?>assets/";
		BASE_CONTENT = "<?= base_url('dokumen/') ?>"
		let countOnline = 0;
	</script>
	<!-- <script type="module">
		import {
			io
		} from "<?= base_url() ?>socketserver/node_modules/socket.io/client-dist/socket.io.esm.min.js";

		var socket = io("<?= $_ENV['SOCKET_CONNECT']; ?>"); //Server Sekawan
		// var socket = io("wss://192.168.100.59:3000"); //IP Sena

		// Web socket
		const userdata = <?= json_encode($this->session->userdata()); ?>;
		window.socket = socket;

		let dataDiri = {
			'user_id': userdata.user_id,
			'toko_id': userdata.toko.toko_id,
			'toko_nama': userdata.toko.toko_nama,
			'user_nama': userdata.user_nama,
		};
		window.userdata = dataDiri;
		$(document).ready(function() {
			socket.emit('user_data', dataDiri);
		});

		socket.on("hello", (arg) => {
			console.log(arg);
		});
	</script> -->

	<script type="text/javascript" src="assets/helper/helper.js?v=1.0.14"></script>

	<?php load_view('main/JavascriptMain') ?>
</body>
<!--end::Body-->

</html>