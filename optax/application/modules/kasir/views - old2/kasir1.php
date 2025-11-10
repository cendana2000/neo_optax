<!DOCTYPE html>

<html lang="en">

	<!-- begin::Head -->
	<head>
		<base href="../">
		<meta charset="utf-8" />
		<title>EKA MART | KPRI EKO KAPTI KEMENKEMENAG KAB. MALANG</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
		<!-- <link rel="stylesheet" href="<?php echo $this->config->item('base_theme') ?>/fonts/poppins/style.css"> -->

		<!--end::Fonts -->

		<!--begin::Page Vendors Styles(used by this page) -->
		<link href="<?php echo $this->config->item('base_theme') ?>plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->item('base_theme') ?>/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Page Vendors Styles -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="<?php echo $this->config->item('base_theme') ?>/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->item('base_theme') ?>/css/style.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="<?php echo $this->config->item('base_theme') ?>/css/skins/header/base/navy.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->item('base_theme') ?>/css/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->item('base_theme') ?>/css/skins/brand/navy.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->item('base_theme') ?>/css/skins/aside/light.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url() ?>assets/css/custom.css" rel="stylesheet" type="text/css" />

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="<?php echo $this->config->item('base_theme') ?>/media/logos/logo-eka1.png" />
		<style type="text/css">
			.kt-header .kt-header-menu .kt-menu__nav>.kt-menu__item>.kt-menu__link .kt-menu__link-text {
			    color: #ffffff;
			}
			.kt-aside-secondary--expanded.kt-aside-secondary--enabled .kt-content,.kt-aside-secondary--expanded.kt-aside-secondary--enabled .kt-footer  {
			    margin-right: 361px;
			}
			.kt-footer-payment{
				display: block;
				position: static;
				bottom: 0;
				min-height: 300px;
			}
			.kt-aside-secondary__content-body.kt-scroll{
				min-height: 400px!important;
			}
			.kt-aside-secondary .kt-aside-secondary__content .kt-aside-secondary__content-head {
			    padding: 0.5rem 2rem;
    			min-height: 70px;
			}
			.kt-portlet__body{
				padding-left: 25;
				padding-right: 25;
			}
			.kt-footer-payment .form-group{
				margin-bottom: .5rem;
			}
			.kt-trans>.form-group{
				margin-bottom: 1.3rem;
			}
			#modal-bayar .form-group{
				margin-bottom: 1rem;
			}
			.btn-primary.focus, .btn-primary:focus, .btn-primary:visited {
    			background: #384ad7;
    		}
    		input[type="checkbox"]:before {
			     content: '';
			     margin-right: 10px;
			     display: inline-block;
			     margin-top: -2px;
			     width: 20px;
			     height: 20px;
			     background: #fcfcfc;
			     border: 1px solid #aaa;
			     border-radius: 2px;
			}
			.kt-checkbox:focus-within {
		  	    border-bottom: 1px dashed #b7b7b7;
			    padding-bottom: 5px;
			    box-shadow: 0 9px 13px 0 rgba(31, 17, 49, 0.05);
			    margin-bottom: 3px;
			}
			.kt-header .kt-header__topbar .kt-header__topbar-item.show.kt-header__topbar-item--user .kt-header__topbar-user, .kt-header .kt-header__topbar .kt-header__topbar-item:hover.kt-header__topbar-item--user .kt-header__topbar-user {
			    background-color: #fff;
			}
			.kt-header .kt-header__topbar .kt-header__topbar-item.show.kt-header__topbar-item--user .kt-header__topbar-user .kt-header__topbar-username, 
			.kt-header .kt-header__topbar .kt-header__topbar-item:hover.kt-header__topbar-item--user .kt-header__topbar-user .kt-header__topbar-username {
			    color: #6b7594!important;
			}

			.kt-header .kt-header__topbar .kt-header__topbar-item.kt-header__topbar-item--user .kt-header__topbar-welcome {
			    color: #6b7594!important;
			}
			.kt-header__topbar-item--user:hover .kt-header__topbar-wrapper .kt-header__topbar-user .kt-hidden-mobile {
			    color: #6b7594!important;
			}
			.number{
				text-align: right;
			}
			.bigdrop{
    			width: 600px!important;
    		} 
    		.detail-barang-select{
    			display: inline-block;
    		}
		</style>
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-footer--fixed  kt-aside-secondary--enabled kt-page--loading kt-aside-secondary--expanded">

		<!-- end:: Header Mobile -->

		<!-- begin:: Root -->
		<div class="kt-grid kt-grid--hor kt-grid--root" style="background: white;">

			<!-- begin:: Page -->
			<form class="kt-form" action="javascript:save('form-penjualanbarang')" name="form-penjualanbarang" id="form-penjualanbarang">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				
				<!-- end:: Aside -->
				<!-- begin:: Wrapper -->
					<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper" style="padding-left: 0">
						
						<!-- begin:: Header -->
						<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed " style="left: 0;background: #5d78ff " >
							<!-- 5d78ff  1dc9b7-->
							
						<!-- begin::Aside Brand -->
							<div class="kt-aside__brand kt-grid__item " id="kt_aside_brand" style="background: #fff">
								<div class="kt-aside__brand-logo">
									<a href="index.html" style="color: #5d78ff;font-size: 1.3rem;font-weight: 600;">EKA MART
										
									</a>
								</div>
							</div>
							<!-- begin:: Header Menu -->
							<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
							<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
								<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout- ">
									<ul class="kt-menu__nav ">
										<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle" style="background: #fff"><span class="kt-menu__link-text text-uppercase" style="color: #5d78ff"><?php echo $kasir['kasir_nama']; ?></span></a>
											
										</li>
										<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
											<a href="javascript:void(0)" class="btn btn-sm btn-elevate btn-brand btn-elevate" id="kt_dashboard_daterangepicker" data-toggle="kt-tooltip" title="" data-placement="left">
												<span class="kt-opacity-7" id="kt_dashboard_daterangepicker_title">Today:</span>&nbsp;
												<span class="kt-font-bold" id="kt_dashboard_daterangepicker_date">Jan 11</span>
												<i class="flaticon-calendar-with-a-clock-time-tools kt-padding-l-5 kt-padding-r-0"></i>
												<input type="text" style="display:none"  id="penjualan_tanggal" name="penjualan_tanggal" onchange="setDate()" /> 
											</a>
											
										</li>
									</ul>

								</div>
							</div>

							<!-- end:: Header Menu -->

							<!-- begin:: Header Topbar -->
							<div class="kt-header__topbar">

								<!--begin: User Bar -->
								<div class="kt-header__topbar-item kt-header__topbar-item--user" style="margin-right: 10px">
									<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">

										<!--use "kt-rounded" class for rounded avatar style-->
										<div class="kt-header__topbar-user kt-rounded-">
											<span class="kt-header__topbar-username kt-hidden-mobile" style="color: #fff;">Hi,</span>
											<span class="kt-header__topbar-username kt-hidden-mobile" style="color: #fff"><?php echo $this->session->userdata('user_alias') ?></span>
											<img alt="Pic" src="<?php echo $this->config->item('base_theme') ?>/media/users/300_25.jpg" class="kt-rounded-" />

											<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
											<span class="kt-badge kt-badge--username kt-badge--lg kt-badge--brand kt-hidden kt-badge--bold">S</span>
										</div>
									</div>
									<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-sm">
										<div class="kt-user-card kt-margin-b-40 kt-margin-b-30-tablet-and-mobile" style="background-image: url(<?php echo $this->config->item('base_theme') ?>/media/misc/head_bg_sm.jpg)">
											<div class="kt-user-card__wrapper">
												<div class="kt-user-card__pic">

													<!--use "kt-rounded" class for rounded avatar style-->
													<img alt="Pic" src="<?php echo $this->config->item('base_theme') ?>/media/users/300_21.jpg" class="kt-rounded-" />
												</div>
												<div class="kt-user-card__details">
													<div class="kt-user-card__name"><?php echo $this->session->userdata('pegawai_nama') ?></div>
													<div class="kt-user-card__position">CTO, Loop Inc.</div>
												</div>
											</div>
										</div>
										<ul class="kt-nav kt-margin-b-10">
											<li class="kt-nav__item">
												<a href="javascript:void(0)" class="kt-nav__link">
													<span class="kt-nav__link-icon"><i class="flaticon2-calendar-3"></i></span>
													<span class="kt-nav__link-text">Ubah Password</span>
												</a>
											</li>
											<li class="kt-nav__item">
												<a href="javascript:void(0)" onclick="onTables()" class="kt-nav__link">
													<span class="kt-nav__link-icon"><i class="flaticon2-browser-2"></i></span>
													<span class="kt-nav__link-text">Data Penjualan</span>
												</a>
											</li>
											<li class="kt-nav__item">
												<a href="<?php echo base_url() ?>" target="_blank" class="kt-nav__link">
													<span class="kt-nav__link-icon"><i class="flaticon2-browser-1"></i></span>
													<span class="kt-nav__link-text">Halaman Administrator</span>
												</a>
											</li>
											<li class="kt-nav__separator kt-nav__separator--fit"></li>
											<li class="kt-nav__custom kt-space-between">
												<a href="javascript:void(0)" onclick="HELPER.logout()" class="btn btn-label-brand btn-upper btn-sm btn-bold">Sign Out</a>
												<i class="flaticon2-information kt-label-font-color-2" data-toggle="kt-tooltip" data-placement="right" title="" data-original-title="Click to learn more..."></i>
											</li>
										</ul>
									</div>
								</div>

								<!--end: User Bar -->

								<!--begin:: Quick Panel Toggler -->
								<!-- <div class="kt-header__topbar-item kt-header__topbar-item--quick-panel" data-toggle="kt-tooltip" title="Quick panel" data-placement="right">
									<span class="kt-header__topbar-icon" id="kt_quick_panel_toggler_btn">
										<i class="flaticon-grid-menu"></i>
									</span>
								</div>
								 -->
								<!--end:: Quick Panel Toggler -->
							</div>

							<!-- end:: Header Topbar -->
						</div>

						<!-- end:: Header -->
						<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content" style="background: #fff;min-height: 560px">

							<!-- begin:: Content -->
							<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid kt-scroll" style="padding: 0;">

								<!--begin::Row-->
								<div class="row">
									<!--begin::Portlet-->
									<div class="kt-portlet">
										<div class="kt-portlet__body" style="background: #f7f8fa; border-bottom: 1px solid #ebedf2; margin-bottom: 0;padding-bottom: 15px;padding-top: 16px;border-radius: 0;box-shadow: none;--webkit-box-shadow: none">
											<div class="form-group row" style="margin-bottom: 0;">
						                        <div class="col-4 row">
						                            <button type="button" class="btn btn-outline-info" onclick="addBarang()" style="margin-left: 10px;width: 44%;"><i class="fa fa-plus" style="text-align: center;"></i>Tambah</button>&nbsp;
						                            <div class="kt-input-icon kt-input-icon--left" style="width: 51%;">
						                                <input type="text" class="form-control number" id="qty" autocomplete="off" style="padding-left: 6.2rem;" value="1">
						                                <span class="kt-input-icon__icon kt-input-icon__icon--left" style="padding-right: 2px; width: 73px">
						                                    <span style="margin-left: 4px;">Qty Scan :</span>
						                                </span>
						                            </div>
						                        </div>
						                        <div class="col-8" style="padding: 0;padding-left: 5px;">
						                            <div class="kt-input-icon kt-input-icon--left">
						                                <input type="text" class="form-control use_barcode" id="barang" placeholder="Scan barcode disini!" autocomplete="off">
						                                <span class="kt-input-icon__icon kt-input-icon__icon--left" style="padding-right: 10px;">
						                                    <span><i class="fa fa-barcode"></i></span>
						                                </span>
						                            </div>
						                        </div>
						                    </div>
										</div>
									</div>

									<!--end::Portlet-->
								</div>

								<div class="row barang_jual" style="overflow-y: scroll;vertical-align: top">								
									<table class="table table-bordered table-hover" id="table-detail_barang" style="border: none; margin: 0 25px">
										<thead>
											<tr>
												<th style="width: 25%!important;border:0">Barang</th>
												<th style="width: 10%;border: 0">Satuan</th>
												<th style="width: 15%;border: 0">Harga</th>
												<th style="width: 8%;border: 0">Qty</th>
												<th style="width: 10%;border: 0">Disc %</th>
												<th style="width: 22%;border: 0">Jumlah</th>
												<th style="border: 0">Aksi</th>
											</tr>
										</thead>
										<tbody>
				                                <tr class="barang_1">
				                                    <td scope="row">
				                                        <input type="text" style="display:none"  class="form-control" name="penjualan_detail_id[1]" id="penjualan_detail_id_1">
				                                        <input type="text" style="display:none"  class="form-control" name="penjualan_detail_jenis_barang[1]" id="penjualan_detail_jenis_barang_1">
				                                        <select class="form-control barang_id" name="penjualan_detail_barang_id[1]" id="penjualan_detail_barang_id_1" data-id="1" onchange="setSatuan('1')" style="width: 260px;white-space: nowrap"></select></td>
				                                    <td>
				                                        <select class="form-control" name="penjualan_detail_satuan[1]" id="penjualan_detail_satuan_1" style="width: 100%" onchange="getHarga('1')"></select>
				                                        <input type="text" style="display:none"  class="form-control" name="penjualan_detail_satuan_kode[1]" id="penjualan_detail_satuan_kode_1">					
				                                    </td>
				                                    <td><input class="form-control number" type="text" name="penjualan_detail_harga[1]" id="penjualan_detail_harga_1" readonly=""></td>
				                                    <td>
				                                        <input class="form-control number qty" type="text" name="penjualan_detail_qty[1]" id="penjualan_detail_qty_1" onkeyup="countRow('1')" value="1">
				                                        <input class="form-control number" type="text" style="display:none"  name="penjualan_detail_qty_barang[1]" id="penjualan_detail_qty_barang_1">
				                                    </td>
				                                    <td>
				                                        <input class="form-control disc" type="text" name="penjualan_detail_potongan_persen[1]" id="penjualan_detail_potongan_persen_1" onkeyup="countRow('1')">
				                                        <input class="form-control number" type="text" style="display:none"  name="penjualan_detail_potongan[1]" id="penjualan_detail_potongan_1">
				                                    </td>
				                                    <td><input class="form-control number jumlah" type="text" name="penjualan_detail_subtotal[1]" id="penjualan_detail_subtotal_1" readonly=""></td>
				                                    <td style="text-align: center;"><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus">
				                                            <span class="la la-trash"></span> Hapus</a></td>
				                                </tr>
										</tbody>
									</table>
								</div>
								<!--end::Row-->

							</div>

							<!-- end:: Content -->
						</div>

						<!-- begin:: Footer -->
						<div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" style="left: 0;height:87px; padding: 0; border-top: 1px solid #ebedf2;-webkit-box-shadow: 0 0 4px 0 rgba(82,63,105,.13); box-shadow: 0 0 1px 0 rgba(82,63,105,.13);flex-direction: column;">
							<div class="kt-container  kt-container--fluid ">							
								<table class="table" style="margin-bottom: 0">
									<tbody>
										<tr>
											<td class="text-right" style="width: 30%!important;border: 0;vertical-align: middle;">Total Item</td>
											<td class="" style="width: 10%;border: 0;vertical-align: middle;"><input class="form-control" type="text" id="penjualan_total_item" name="penjualan_total_item" readonly=""></td>
											<td class="text-right" style="width: 15%;border: 0;vertical-align: middle;">Total Qty</td>
											<td class="" style="width: 8%;border: 0;vertical-align: middle;"><input class="form-control number" type="text" id="penjualan_total_qty" name="penjualan_total_qty" readonly=""></td>
											<td class="text-right" style="width: 10%;border: 0;vertical-align: middle; background: #b6bac3">Sub Total</td>
											<td style="width: 22%;border: 0;vertical-align: middle; background: #b6bac3"><input class="form-control number" type="text" id="penjualan_total_harga" name="penjualan_total_harga" readonly=""></td>	
											<td style="border: 0;vertical-align: middle;"></td>
										</tr>
									</tbody>
								</table>
							</div>	
							<div class="kt-container  kt-container--fluid " style="background: #f6f7fd; border-top: 1px solid #ebedf2; min-height: 30px;justify-content: normal;">							
								<div class="kt-widget-14__foot-info" >
									<div class="kt-widget-14__foot-label btn btn-sm btn-label-brand btn-bold" style="width: 100px!important">
										<i class="la la-clock-o"></i> <span id="Timestamp" >00:00:00</span>
									</div>
									<!-- <div class="kt-widget-14__foot-desc"  style="display: inline;">08:00:10</div> -->
								</div>
								<div class="kt-widget-14__foot-info">
									<div class="kt-widget-14__foot-label btn btn-sm btn-label btn-bold" style="background: none;padding: .5rem .5rem;">
										<code style="font-size: 100%">F2</code>
									</div>
									<div class="kt-widget-14__foot-desc" style="display: inline;">: Qty dan Barcode Barang</div>
								</div>
								<div class="kt-widget-14__foot-info">
									<div class="kt-widget-14__foot-label btn btn-sm btn-label btn-bold" style="background: none;padding: .5rem .5rem;">
										<code style="font-size: 100%">F4</code>
									</div>
									<div class="kt-widget-14__foot-desc" style="display: inline;">: Pencarian Barang</div>
								</div>
								<div class="kt-widget-14__foot-info">
									<div class="kt-widget-14__foot-label btn btn-sm btn-label btn-bold" style="background: none;padding: .5rem .5rem;">
										<code style="font-size: 100%">F8</code>
									</div>
									<div class="kt-widget-14__foot-desc" style="display: inline;">: Anggota</div>
								</div>
								<div class="kt-widget-14__foot-info">
									<div class="kt-widget-14__foot-label btn btn-sm btn-label btn-bold" style="background: none;padding: .5rem .5rem;">
										<code style="font-size: 100%">F9</code>
									</div>
									<div class="kt-widget-14__foot-desc" style="display: inline;">: Disc./Potongan</div>
								</div>
								<div class="kt-widget-14__foot-info">
									<div class="kt-widget-14__foot-label btn btn-sm btn-label btn-bold" style="background: none;padding: .5rem .5rem;">
										<code style="font-size: 100%">Ctrl + B</code>
									</div>
									<div class="kt-widget-14__foot-desc" style="display: inline;">: Baris Terakhir </div>
								</div>
								<div class="kt-widget-14__foot-info">
									<div class="kt-widget-14__foot-label btn btn-sm btn-label btn-bold" style="background: none;padding: .5rem .5rem;">
										<code style="font-size: 100%">End</code>
									</div>
									<div class="kt-widget-14__foot-desc" style="display: inline;">: Jumlah Bayar</div>
								</div>
							</div>					
						</div>			
						<!-- end:: Footer -->
					</div>

					<!-- end:: Wrapper -->

					<!-- begin:: Aside Secondary -->

					<div class="kt-aside-secondary" id="kt_aside_secondary">
						<div class="kt-aside-secondary__toggle" id="kt_aside_secondary_toggler"></div>
						<button class="kt-aside-secondary__mobile-nav-toggler" id="kt_aside_secondary_mobile_nav_toggler" data-toggle="kt-tooltip" title="Aside Secondary" data-placement="left"></button>
						<div class="kt-aside-secondary__content" style="right: 0px;width: 360px; box-shadow: none;-webkit-box-shadow: none">
							<div class="tab-content">
								<div class="tab-pane fade active show" id="kt_aside_secondary_tab_1" role="tabpanel">
									<div class="kt-aside-secondary__content-head">
										Total<i style="font-size: 10px;left: 25px;position: absolute;top: 45px; color: #e85b25">*Yang dibayar</i> <span id="v_penjualan_total_grand" style="position: absolute; right: 26px; font-size: 2.5rem;">0</span>
									</div>
									<div class="kt-aside-secondary__content-body kt-scroll kt-payment" style="padding-bottom: 280px;">
										<div class="kt-form">
											<div class="kt-section kt-section--first kt-trans">
												<div class="form-group">
													<label>No. Transaksi:</label>
													<a href="javascript:;" onclick="clearAnggota()" class="badge badge-warning pull-right" id="clear-anggota" style="display: none"><i class="la la-close"></i> Clear Anggota</a>
													<div class="input-group">
														<input type="text" style="display:none"  name="penjualan_id" id="penjualan_id" class="form-control" placeholder="AUTO.#00" readonly="">
														<input type="text" style="display:none"  id="penjualan_kasir" name="penjualan_kasir" value="<?php echo $kasir['kasir_kode']; ?>">
														<input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" placeholder="AUTO.#00" readonly="" style="width:35%">
														<select name="penjualan_anggota_id" id="penjualan_anggota_id" class="form-control" placeholder="Anggota" style="width:65%" onchange="getNasabah()"></select>
														<!-- <input type="text" class="form-control number" id="penjualan_total_potongan" name="penjualan_total_potongan" onkeyup="countDiskon()"> -->
													</div>
												</div>
												<!-- <div class="form-group">
													<label>Nasabah:</label>
													<select name="penjualan_anggota_id" id="penjualan_anggota_id" class="form-control"></select>
												</div> -->
												<div class="form-group">
													<label>NIP/Jabatan:</label>
													<input type="text" name="anggota_nip" id="anggota_nip" class="form-control" disabled="">
												</div>
												<div class="form-group row">
													<div class="col-6">
														<label>Saldo Titipan Belanja</label>
														<input type="text" name="anggota_saldo_simp_titipan_belanja" id="anggota_saldo_simp_titipan_belanja" class="form-control number voucher" disabled="">
													</div>
													<div class="col-6">
														<label>Voucher Belanja</label>
														<input type="text" name="anggota_saldo_voucher" id="anggota_saldo_voucher" class="form-control number" disabled="">
														<span class="badge badge-secondary pull-right" id="saldo-voucher-anggota" style="display: none; margin-top: 3px;background: #e8d3b6;"><i class="la la-close"></i> Exp </span>
													</div>
												</div>
												<!-- <div class="form-group">
													<label>Saldo Voucher Belanja</label>
													<span class="badge badge-secondary pull-right" id="saldo-voucher-anggota" style="display: none"><i class="la la-close"></i> Exp </span>
													<input type="text" name="anggota_saldo_voucher" id="anggota_saldo_voucher" class="form-control number" disabled="">
												</div> -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="kt-footer-payment" style="display: block;">
								<div class="kt-form" style="border-top: 1px solid #ebedf2;position: fixed;background: #f7f8fa;bottom: 0;min-height: 230px!important;padding: 10px 26px">
		                            <div class="form-group">
										<label>Disc./Potongan</label>
										<div class="input-group">
											<div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
												<input type="text" class="form-control number" id="penjualan_total_potongan_persen" name="penjualan_total_potongan_persen" onkeyup="countDiskon()">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>%</span>
												</span>
											</div>										
											<input type="text" class="form-control number" id="penjualan_total_potongan" name="penjualan_total_potongan" onkeyup="countDiskon()">
										</div>
									</div>
		                            <div class="form-group row">
										<label class="col-5 col-form-label">Titipan Belanja</label>
										<div class="col-7" style="padding-left: 0;">									
											<input type="text" class="form-control number bayar-voucher" id="v_penjualan_total_bayar_voucher" name="v_penjualan_total_bayar_voucher" disabled="" onkeyup="setVoucher(this)" onfocus="fillVoucher(this)">
										</div>
									</div>
		                            <div class="form-group row">
										<label class="col-5 col-form-label">Voucher Belanja</label>
										<div class="col-7" style="padding-left: 0;">									
											<input type="text" class="form-control number bayar-voucher-anggota" id="v_penjualan_total_bayar_voucher_khusus" name="v_penjualan_total_bayar_voucher_khusus" disabled="" onkeyup="setVoucherKhusus(this)"onfocus="fillVoucherKhusus(this)">
										</div>
									</div>
		                            <!-- <div class="form-group row">
		                            	<div class="col-12">	                            	
		                            										<div class="input-group">
		                            											<select name="penjualan_anggota_id" id="penjualan_anggota_id" class="form-control" placeholder="Anggota" style="width:35%">
		                            												<option value="T">Tunai</option>
		                            												<option value="K">Kredit</option>
		                            												<option value="B">Bank</option>
		                            											</select>
		                            											<input type="text" class="form-control number" id="penjualan_total_potongan" name="penjualan_total_potongan" onkeyup="countDiskon()" style="width:52%">
		                            										</div>
		                            									</div>
		                            								</div> -->
		                            <div class="form-group row">
		                            	<label class="col-5 col-form-label">Metode</label>
		                            	<div class="col-7" style="padding-left: 0;">	                            	
												<select name="penjualan_metode" id="penjualan_metode" class="form-control" onchange="getMetode()">
													<option value="C">Tunai</option>
													<option value="K">Kredit</option>
													<option value="B">Bank</option>
												</select>
										</div>
									</div>
		                            <div class="form-group row bank" style="display: none">
		                            	<label class="col-5 col-form-label"></label>
		                            	<div class="col-7" style="padding-left: 0;">	                            	
												<select name="penjualan_bank_id" id="penjualan_bank_id" class="form-control" placeholder="Bank" style="width: 100%">
													<option value="">Pilih Bank</option>
												</select>
											<!-- <div class="input-group">
											</div> -->
										</div>
									</div>
		                            <div class="form-group row bank" style="display: none">
		                            	<label class="col-5 col-form-label"></label>
		                            	<div class="col-7" style="padding-left: 0;">	                            	
											<input type="text" class="form-control" id="penjualan_bank_ref" name="penjualan_bank_ref" placeholder="No. Referensi">
										</div>
									</div>
		                            <div class="form-group row">
		                            	<label class="col-5 col-form-label">Jumlah Bayar</label>
		                            	<div class="col-7" style="padding-left: 0;">	                            	
											<input type="text" class="form-control number bayar-tunai" id="penjualan_total_bayar_tunai" name="penjualan_total_bayar_tunai" onkeyup="setBayar(this)">
										</div>
									</div>
		                            <div class="form-group row">
										<label class="col-5 col-form-label">Kembalian</label>
										<div class="col-7" style="padding-left: 0;">									
											<input type="text" class="form-control number" id="penjualan_total_kembalian" name="penjualan_total_kembalian" readonly>
										</div>
									</div>
		                            <div class="form-group">
		                            	<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
											<input type="checkbox" class="cetak" id="cetak" name="cetak" checked="checked" value="1"onchange="setChecked(this)"> <i class="flaticon2-print"></i> Cetak
											<span></span>
										</label>
										<button style="margin-left: 37px;" type="button" onclick="save()" class="btn btn-primary"><i class="flaticon2-check-mark"></i> Bayar</button>
										<!-- <code>End</code> -->
										<button type="reset" class="btn btn-secondary" onclick="setReset()"><i class="flaticon2-cancel-music"></i> Batal</button>
									</div>
								</div>
							</div>
						</div>

					</div>
				<!-- end:: Aside Secondary -->
			</div>
			</form>

			<!-- end:: Page -->
		</div>

		<!-- end:: Root -->
		<!-- begin:: Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="la la-arrow-up"></i>
		</div>
		
		<div class="modal bd-example-modal-xl fade" id="modal-bayar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalCenterTitle">Pembayaran</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form class="kt-form" action="javascript:save('form-bayar')" name="form-bayar" id="form-bayar">
							<div class="kt-portlet__body">
								<div class="form-group row">
									<div class="col-6">								
										<div class="form-group row">
											<label for="penjualan_total_grand" class="col-4 col-form-label">Total Harga</label>
											<div class="col-7">
												<input class="form-control number total_harga" type="text" id="penjualan_total_grand" name="penjualan_total_grand" readonly="">
												<input class="form-control number total_harga" type="text" style="display:none"  id="penjualan_total_bayar" name="penjualan_total_bayar" >
											</div>
										</div>
										<div class="form-group row">
											<label for="v_penjualan_total_bayar_tunai" class="col-4 col-form-label">Bayar Tunai</label>
											<div class="col-7">
												<input class="form-control number bayar-tunai" type="text" id="v_penjualan_total_bayar_tunai" name="v_penjualan_total_bayar_tunai" onkeyup="setBayar(this)">
											</div>
										</div>
									</div>
									<!-- <div class="col-6">								
										<div class="form-group row">
											<div class="col-12" style="height: 75px;">
												<label for="penjualan_total_kembalian" style="position: absolute;padding: 20px;">Total Uang Kembali</label>
												<input class="form-control number" type="text" id="penjualan_total_kembalian" name="penjualan_total_kembalian" value="" style="height: 120%;font-size: 26px;background-color: #d4ebff" readonly="">
											</div>
										</div>
									</div> -->
								</div>
								
								<!-- 	<div class="col-1"></div>
								<label for="barang_kategori_barang" class="col-2 col-form-label">Uang Kembalian</label>
								<div class="col-4">
									<input class="form-control number" type="text" id="penjualan_total_kembalian" name="penjualan_total_kembalian" readonly="">
								</div>							
														</div> -->
								<div class="form-group row nasabah">
									<div class="col-4"><i class="flaticon-medal" style="font-size: 14px"></i> Nasabah</div>
								</div>

								<div class="form-group row nasabah">
									<div class="col-6">	
										<div class="form-group row">
											<label for="bayar" class="col-4 col-form-label">Titipan Belanja</label>
											<div class="col-7">
												<input class="form-control number bayar-voucher" type="text" id="penjualan_total_bayar_voucher" name="penjualan_total_bayar_voucher" disabled="" onkeyup="setVoucher(this)" onfocus="fillVoucher(this)"> 
											</div>
										</div>	
										<div class="form-group row">
											<label for="bayar" class="col-4 col-form-label">Voucher Belanja</label>
											<div class="col-7">
												<input class="form-control number bayar-voucher-anggota" type="text" id="penjualan_total_bayar_voucher_khusus" name="penjualan_total_bayar_voucher_khusus" disabled="" onkeyup="setVoucher(this)" onfocus="fillVoucherKhusus(this)"> 
											</div>
										</div>	
										<div class="form-group row">
											<label for="penjualan_total_kredit" class="col-4 col-form-label">Jumlah Kredit</label>
											<div class="col-7">
												<input class="form-control number tenor" type="text" id="penjualan_total_kredit" name="penjualan_total_kredit" readonly="">
												<input type="text" style="display:none"  class="form-control number tenor" id="pengajuan_jumlah_pinjaman" name="pengajuan_jumlah_pinjaman">
											</div>
										</div>
										<div class="form-group row">
											<label for="bayar" class="col-4 col-form-label">Jatuh Tempo</label>
											<div class="col-7">
												<input class="form-control" type="date" id="penjualan_jatuh_tempo" name="penjualan_jatuh_tempo">
												<input class="form-control" type="text" style="display:none"  id="penjualan_kredit_awal" name="penjualan_kredit_awal">
												<!-- <input class="form-control" type="hidden" style="display:none"  id="pengajuan_tag_bulan" name="pengajuan_tag_bulan"> -->
											</div>
										</div>	
									</div>
									<div class="col-6">	
										<div class="form-group row">	
											<label for="bayar" class="col-12 col-form-label" id="sisa_saldo">Sisa saldo : </label>
										</div>	
										<div class="form-group row">	
											<label for="bayar" class="col-12 col-form-label" id="sisa_saldo_voucher">Sisa saldo : </label>
										</div>												
										<div class="form-group row">													
											<label for="penjualan_total_cicilan_qty" class="col-3 col-form-label">Cicilan</label>
											<div class="col-9">				
												<div class="input-group">
													<div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
														<input type="text" class="form-control number" id="penjualan_total_cicilan_qty" name="penjualan_total_cicilan_qty" onkeyup="countCicilan()" value="1">
														<span class="kt-input-icon__icon kt-input-icon__icon--right">
															<span>x</span>
														</span>
													</div>	
														<!-- <input type="text" class="form-control number" id="penjualan_total_cicilan_qty" name="penjualan_total_cicilan_qty" onkeyup="countCicilan()" value="1"> -->
													<div class="kt-input-icon kt-input-icon--right" style="width: 48%;margin-right: 5px;">
														<input type="text" class="form-control disc" id="penjualan_total_jasa" name="penjualan_total_jasa" onkeyup="countCicilan()">																	
														<span class="kt-input-icon__icon kt-input-icon__icon--right">
															<span>%</span>
														</span>
													</div>				
													<input type="hidden" class="form-control number" id="penjualan_total_cicilan" name="penjualan_total_cicilan">
													<input type="hidden" class="form-control number" id="penjualan_total_jasa_nilai" name="penjualan_total_jasa_nilai">
												</div>
											</div>
										</div>													
										<div class="form-group row">													
											<label for="penjualan_jenis_potongan" class="col-3 col-form-label">Tagihan</label>
											<div class="col-4">				
												<select name="penjualan_jenis_potongan" id="penjualan_jenis_potongan" class="form-control">
													<option value="1">Tunai</option>
													<option value="0">Potongan Gaji</option>
												</select>
											</div>
											<label for="bayar" class="col-5 col-form-label" id="bulan_cicil"></label>
										</div>	
									</div>
								</div>
							</div>
							<hr>
							<div class="kt-portlet__foot">
								<div class="kt-form__actions">
									<div class="row">
										<div class="col-2" style="padding-top: 8px;">					
											<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
												<input type="checkbox" class="cetak" checked="checked" value="1" onchange="setChecked(this)"> <i class="flaticon2-print"></i> Cetak
												<span></span>
											</label>
										</div>
										<!-- <div class="col-2"></div> -->
										<div class="col-10">
											<button type="button" class="btn btn-success" onclick="saving()"><i class="flaticon-paper-plane-1"></i> Simpan</button>
											<button type="reset" class="btn btn-secondary" data-dismiss="modal"><i class="flaticon2-cancel-music"></i> Batal</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="modal bd-example-modal-xl fade" id="modal-barang" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalCenterTitle">Pencarian Barang</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<table class="table table-striped table-checkable table-condensed" id="table-barang">
							<thead>
								<tr>
									<th style="width:5%;">No.</th>
									<th>Kode</th>
									<th>Nama Barang</th>
									<th>Kelompok Barang</th>
									<th>Sat. 1</th>
									<th>Harga 1</th>
									<th>Sat. 2</th>
									<th>Harga 2</th>
									<th>Stok</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<th style="width:5%;">No.</th>
									<th>Kode</th>
									<th>Nama Barang</th>
									<th>Kelompok Barang</th>
									<th>Sat. 1</th>
									<th>Harga 1</th>
									<th>Sat. 2</th>
									<th>Harga 2</th>
									<th>Stok</th>
									<th>Aksi</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="modal bd-example-modal-xl fade" id="modal-penjualan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="width: 1250px">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalCenterTitle">Faktur Penjualan</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<table class="table table-striped table-checkable table-condensed" id="table-penjualanbarang">
							<thead>
								<tr>
									<th style="width:5%;">No.</th>
									<th>Kode</th>
									<th>Tanggal</th>
									<th>Nasabah</th>
									<th>No Nasabah</th>
									<th>Jenis Penjualan</th>
									<th>Sub Total</th>
									<th>Potongan</th>
									<th>Grand Total</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<th style="width:5%;">No.</th>
									<th>Kode</th>
									<th>Tanggal</th>
									<th>Nasabah</th>
									<th>No Nasabah</th>
									<th>Jenis Penjualan</th>
									<th>Sub Total</th>
									<th>Potongan</th>
									<th>Grand Total</th>
									<th>Aksi</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div> -->

		<div class="modal bd-example-modal-xl fade" id="modal-penjualan" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl"  role="document" style="width: 1250px">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" style="padding-top: 10px;" id="exampleModalCenterTitle">Faktur Penjualan</h5>			
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="background: #a2b2ff;color: #fff">						
						<form id="tanggal" name="tanggal" action="javascript:init_table()">
							<div class="kt-portlet__body" >
								<div class="form-group row col-12" style="margin-bottom: 0">
									<label class="col-1 col-form-label">Tanggal</label>
									<input type="date" name="awal_tanggal" id="awal_tanggal" class="col-3 form-control" value="<?php echo date('Y-m-d')?>">
									<label class="col-1 col-form-label" style="text-align: center;">S/d</label>
									<input type="date" name="akhir_tanggal" id="akhir_tanggal" class="col-3 form-control" value="<?php echo date('Y-m-d')?>">
									<p style="padding-left: 10px;"><button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i>Tampilkan</button></p>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-body">						
						<table class="table table-striped table-checkable table-condensed" id="table-penjualanbarang">
							<thead>
								<tr>
									<th style="width:5%;">No.</th>
									<th>Kode</th>
									<th>Tanggal</th>
									<th>Nasabah</th>
									<th>No Nasabah</th>
									<!-- <th>Jenis Penjualan</th> -->
									<th>Sub Total</th>
									<th>Potongan</th>
									<th>Grand Total</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<th style="width:5%;">No.</th>
									<th>Kode</th>
									<th>Tanggal</th>
									<th>Nasabah</th>
									<th>No Nasabah</th>
									<!-- <th>Jenis Penjualan</th> -->
									<th>Sub Total</th>
									<th>Potongan</th>
									<th>Grand Total</th>
									<th>Aksi</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="printArea" style="display: none;"></div>

		<!-- end:: Scrolltop -->

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#5d78ff",
						"metal": "#c4c5d6",
						"light": "#ffffff",
						"accent": "#00c5dc",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995",
						"focus": "#9816f4"
					},
					"base": {
						"label": [
							"#c5cbe3",
							"#a1a8c3",
							"#3d4465",
							"#3e4466"
						],
						"shape": [
							"#f0f3ff",
							"#d9dffa",
							"#afb4d4",
							"#646c9a"
						]
					}
				}
			};
			var BASE_URL = "<?php echo base_url() ?>";
		</script>

		<!-- end::Global Config -->

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="<?php echo $this->config->item('base_theme') ?>/plugins/global/plugins.bundle.js" type="text/javascript"></script>
		<script src="<?php echo $this->config->item('base_theme') ?>/js/scripts.bundle.js" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Vendors(used by this page) -->
		<script src="<?php echo $this->config->item('base_theme') ?>plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
		<script src="<?php echo $this->config->item('base_theme') ?>/plugins/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
		<script src="<?php echo $this->config->item('base_theme') ?>/plugins/arrow-table/dist/arrow-table.js"></script>

		<!--end::Page Vendors -->

		<!--begin::Page Scripts(used by this page) -->
		<!-- <script src="<?php echo $this->config->item('base_theme') ?>/js/pages/dashboard.js" type="text/javascript"></script> -->
		<script src="<?php echo $this->config->item('base_theme') ?>plugins/jquery.number.min.js" type="text/javascript"></script>

		<!--end::Page Vendors -->

		<!--begin::Page Scripts(used by this page) -->
		<!-- <script src="<?php echo $this->config->item('base_theme') ?>js/pages/dashboard.js" type="text/javascript"></script> -->
		<script src="<?php echo base_url(); ?>appjs/js.cookie.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>appjs/jquery.cookie.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>appjs/fnReloadAjax.js" type="text/javascript"></script>

		<script src="<?php echo base_url() ?>appjs/helper.js" type="text/javascript"></script>
		<script src="<?php echo base_url() ?>appjs/emitter.min.js" type="text/javascript"></script>

		<!--end::Page Scripts -->
		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			$.ajaxSetup({
				start : function(){HELPER.block()},
				error : function(){HELPER.unblock()}
			})

			$(function(){
				var mod = window.location.href.split('#!');
				// HELPER.loadPage($('<a data-menuid="'+mod+'"></a>'));


				/*auto trigger click upper menu*/
				$("#kt_header_menu li:first a").trigger('click');
			})
		</script>
		<?php load_view('javascript') ?>

		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>