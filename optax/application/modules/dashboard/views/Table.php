<link rel="stylesheet" href="<?= base_url('assets/css/custom_dashboard.css'); ?>">

<!--begin::Main-->
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
	<!--begin::Content wrapper-->
	<div class="d-flex flex-column flex-column-fluid">
		<!--begin::Toolbar-->
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<!--begin::Toolbar container-->
			<div
				id="kt_app_toolbar_container"
				class="app-container container-xxl d-flex flex-stack">
				<!--begin::Page title-->
				<div
					class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<!--begin::Title-->
					<h1
						class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
						Dashboard Pemerintah Daerah
					</h1>
					<!--end::Title-->

					<!--begin::Breadcrumb-->
					<ul
						class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">
							<a
								href="<?= base_url(); ?>">
								Home
							</a>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item">
							<span class="bullet bg-gray-500 w-5px h-2px"></span>
						</li>
						<!--end::Item-->

						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">Dashboards</li>
						<!--end::Item-->
					</ul>
					<!--end::Breadcrumb-->
				</div>
				<!-- Custom Filters -->
				<!-- <div class="menu-item p-0 m-0">					
					<a href="https://preview.keenthemes.com/metronic8/demo1/asides/aside-1.html" class="menu-link ">
						<span class="menu-bullet"><span class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span></span>
						<span class="menu-title">Filters</span>
					</a>					
				</div> -->
				<div class="dropdown dropdown-inline dd-filter ms-auto" data-toggle="tooltip" title="Quick actions" data-placement="right">
					<a href="#" class="btn btn-danger fw-semibold mb-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-calendar text-white px-4"></i>
						<span class="pr-4">Filter</span>
					</a>
					<div class="dropdown-menu mt-1 p-0 m-0 dropdown-menu-md dropdown-menu-right">
						<!--begin::Navigation-->
						<ul class="navi navi-hover">
							<li class="navi-header font-weight-bold py-4">
								<span class="font-size-lg">Tampilkan berdasarkan:</span>
							</li>
							<li class="navi-separator mb-3 opacity-70"></li>
							<!-- Date Picker Start -->
							<li class="navi-item p-2">
								<form id="tanggal" name="tanggal" action="javascript:filter()">
									<div class="form-group" id='weekly'>
										<label style="font-size: 12px;">Tanggal :</label>
										<input type="date" name="awal_tanggal" id="awal_tanggal" class="form-control m" value="<?php echo date_format((new DateTime(date('Y-m-d')))->modify('-30 day'), 'Y-m-d'); ?>">
									</div>
									<div class="form-group">
										<label style="font-size: 12px;">Sampai Dengan :</label>
										<input type="date" name="akhir_tanggal" id="akhir_tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>">
									</div>
									<!-- <div class="form-group">
									<label style="font-size: 12px;">Bulan :</label>
									<input type="month" class="form-control" name="bulan" id="bulan"
										value="<?php echo date('Y-m') ?>">
								</div> -->
									<div class="form-group">
										<button type="submit" id="submit-btn" class="btn btn-blue btn-sm my-0 w-100">
											<span class="fas fa-search" style="margin-right: 15px;"></span>
											Tampilkan
										</button>
									</div>
								</form>
							</li>
							<!-- Date Picker Ebd -->
						</ul>
						<!--end::Navigation-->
					</div>
				</div>
				<!--end::Actions-->
			</div>
			<!--end::Toolbar container-->
		</div>
		<!--end::Toolbar-->

		<!--begin::Content-->
		<div id="kt_app_content" class="app-content flex-column-fluid">
			<!--begin::Content container-->
			<div
				id="kt_app_content_container"
				class="app-container container-xxl">
				<!--begin::Row-->
				<div class="row gx-5 gx-xl-10 mb-xl-10">
					<!--begin::Col-->
					<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-10">
						<!--begin::Card widget 7-->
						<div class="card card-flush h-md-50 mb-xl-10">
							<!--begin::Header-->
							<div class="card-header pt-5">
								<!--begin::Title-->
								<div class="card-title d-flex flex-column">
									<!--begin::Amount-->
									<span id="total_wajib_pajak"
										class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">0</span>
									<!--end::Amount-->

									<!--begin::Subtitle-->
									<span class="text-gray-500 pt-1 fw-semibold fs-6">Objek Pajak Terkoneksi</span>
									<!--end::Subtitle-->
								</div>
								<!--end::Title-->
							</div>
							<!--end::Header-->

							<!--begin::Card body-->
							<div
								class="card-body d-flex flex-column justify-content-end pe-0">

								<div class="symbol-group symbol-hover flex-nowrap">
									<div
										class="symbol symbol-35px symbol-circle"
										data-bs-toggle="tooltip"
										title="Alan Warden">
										<span
											class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
									</div>
									<div
										class="symbol symbol-35px symbol-circle"
										data-bs-toggle="tooltip"
										title="Michael Eberon">
										<img src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>" alt="" style="style= width: 10px; border-radius: 999px; background-color: #003A97;" ; />
									</div>
									<div
										class="symbol symbol-35px symbol-circle"
										data-bs-toggle="tooltip"
										title="Susan Redwood">
										<span
											class="symbol-label bg-primary text-inverse-primary fw-bold">S</span>
									</div>
									<div
										class="symbol symbol-35px symbol-circle"
										data-bs-toggle="tooltip"
										title="Melody Macy">
										<img src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>" alt="" style="style= width: 10px; border-radius: 999px; background-color: #003A97;" ; />
									</div>
									<div
										class="symbol symbol-35px symbol-circle"
										data-bs-toggle="tooltip"
										title="Perry Matthew">
										<span
											class="symbol-label bg-danger text-inverse-danger fw-bold">P</span>
									</div>
									<div
										class="symbol symbol-35px symbol-circle"
										data-bs-toggle="tooltip"
										title="Barry Walter">
										<img src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>" alt="" style="style= width: 8px; border-radius: 999px; background-color: #003A97;" ; />
									</div>
									<a
										href="#"
										class="symbol symbol-35px symbol-circle"
										data-bs-toggle="modal"
										data-bs-target="#kt_modal_view_users">
										<span
											class="symbol-label bg-light text-gray-400 fs-8 fw-bold">+519</span>
									</a>
								</div>
							</div>
						</div>
						<!--end::Card widget 7-->

						<!--begin::Card widget 4-->
						<div class="card card-flush h-md-50 mb-5 mb-xl-10">
							<!--begin::Header-->
							<div class="card-header pt-5">
								<!--begin::Title-->
								<div class="card-title d-flex flex-column">
									<!--begin::Info-->
									<div class="d-flex align-items-center">

										<!--begin::Amount-->
										<span id="total_transaksi"
											class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">0</span>
										<!--end::Amount-->

										<!--begin::Badge-->
										<!-- <span class="badge badge-light-success fs-base">
											<i
												class="ki-duotone ki-arrow-up fs-5 text-success ms-n1"><span class="path1"></span><span class="path2"></span></i>
											2.2%
										</span> -->
										<!--end::Badge-->
									</div>
									<!--end::Info-->

									<!--begin::Subtitle-->
									<span class="text-gray-500 pt-1 fw-semibold fs-6">Total Transaksi</span>
									<span class="text-gray-100 pt-1 fs-8">(Dasar Pengenaan Pajak)</span>
									<!--end::Subtitle-->
								</div>
								<!--end::Title-->
							</div>
							<!--end::Header-->

							<!--begin::Card body-->
							<div
								class="card-body pt-2 pb-4 d-flex align-items-center">
								<!--begin::Chart-->
								<div class="d-flex flex-center me-5 pt-2">
									<div
										id="kt_card_widget_4_chart"
										style="min-width: 70px; min-height: 70px"
										data-kt-size="70"
										data-kt-line="11"></div>
								</div>
								<!--end::Chart-->

								<!--begin::Labels-->
								<div
									class="d-flex flex-column content-justify-center w-100">
									<!--begin::Label-->
									<div
										class="d-flex fs-6 fw-semibold align-items-center">
										<!--begin::Bullet-->
										<div
											class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
										<!--end::Bullet-->

										<!--begin::Label-->
										<div class="text-gray-500 flex-grow-1 me-4">
											Restoran
										</div>
										<!--end::Label-->

										<!--begin::Stats-->
										<div class="fw-bolder text-gray-700 text-xxl-end">
											<span id="total_transaksi_resto">0</span>
										</div>
										<!--end::Stats-->
									</div>
									<!--end::Label-->

									<!--begin::Label-->
									<div
										class="d-flex fs-6 fw-semibold align-items-center my-3">
										<!--begin::Bullet-->
										<div
											class="bullet w-8px h-6px rounded-2 bg-warning me-3"></div>
										<!--end::Bullet-->

										<!--begin::Label-->
										<div class="text-gray-500 flex-grow-1 me-4">
											Hotel
										</div>
										<!--end::Label-->

										<!--begin::Stats-->
										<div class="fw-bolder text-gray-700 text-xxl-end">
											0
										</div>
										<!--end::Stats-->
									</div>
									<!--end::Label-->

									<!--begin::Label-->
									<div
										class="d-flex fs-6 fw-semibold align-items-center">
										<!--begin::Bullet-->
										<div
											class="bullet w-8px h-6px rounded-2 me-3"
											style="background-color: #e4e6ef"></div>
										<!--end::Bullet-->

										<!--begin::Label-->
										<div class="text-gray-500 flex-grow-1 me-4">
											Lainnya
										</div>
										<!--end::Label-->

										<!--begin::Stats-->
										<div class="fw-bolder text-gray-700 text-xxl-end">
											0
										</div>
										<!--end::Stats-->
									</div>
									<!--end::Label-->
								</div>
								<!--end::Labels-->
							</div>
							<!--end::Card body-->
						</div>
						<!--end::Card widget 4-->

					</div>
					<!--end::Col-->

					<!--begin::Col-->
					<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-10">
						<!--begin::List widget 25-->
						<div class="card card-flush h-lg-50 mb-xl-10">
							<!--begin::Header-->
							<div class="card-header pt-5">
								<!--begin::Title-->
								<h3 class="card-title text-gray-800">Total Objek Pajak</h3>
								<!--end::Title-->

								<!--begin::Toolbar-->
								<div class="card-toolbar d-none">
									<!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
									<div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left" class="btn btn-sm btn-light d-flex align-items-center px-4">
										<!--begin::Display range-->
										<div class="text-gray-600 fw-bold">
											Loading date range...
										</div>
										<!--end::Display range-->

										<i class="ki-duotone ki-calendar-8 fs-1 ms-2 me-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
									</div>
									<!--end::Daterangepicker-->
								</div>
								<!--end::Toolbar-->
							</div>
							<!--end::Header-->

							<!--begin::Body-->
							<div class="card-body pt-5">
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Section-->
									<div class="text-gray-700 fw-semibold fs-6 me-2">Restoran</div>
									<!--end::Section-->

									<!--begin::Statistics-->
									<div class="d-flex align-items-senter">
										<i class="ki-duotone ki-arrow-up-right fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i>

										<!--begin::Number-->
										<span id="total_wp_resto" class="text-gray-900 fw-bolder fs-6">0</span>
										<!--end::Number-->

									</div>
									<!--end::Statistics-->
								</div>
								<!--end::Item-->

								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->

								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Section-->
									<div class="text-gray-700 fw-semibold fs-6 me-2">Hotel</div>
									<!--end::Section-->

									<!--begin::Statistics-->
									<div class="d-flex align-items-senter">
										<i class="ki-duotone ki-arrow-down-right fs-2 text-danger me-2"><span class="path1"></span><span class="path2"></span></i>

										<!--begin::Number-->
										<span id="total_wp_hotel" class="text-gray-900 fw-bolder fs-6">0</span>
										<!--end::Number-->


									</div>
									<!--end::Statistics-->
								</div>
								<!--end::Item-->

								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->

								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Section-->
									<div class="text-gray-700 fw-semibold fs-6 me-2">Lainnya</div>
									<!--end::Section-->

									<!--begin::Statistics-->
									<div class="d-flex align-items-senter">
										<i class="ki-duotone ki-arrow-up-right fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i>

										<!--begin::Number-->
										<span class="text-gray-900 fw-bolder fs-6">0</span>
										<!--end::Number-->


									</div>
									<!--end::Statistics-->
								</div>
								<!--end::Item-->



							</div>
							<!--end::Body-->
						</div>
						<!--end::LIst widget 25-->

						<!--begin::Card widget 5-->
						<div class="card card-flush h-md-50 mb-xl-10">
							<!--begin::Header-->
							<div class="card-header pt-5">
								<!--begin::Title-->
								<div class="card-title d-flex flex-column">
									<!--begin::Info-->
									<div class="d-flex align-items-center">
										<!--begin::Amount-->
										<span id="total_pajak_masuk"
											class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">0</span>
										<!--end::Amount-->

									</div>
									<!--end::Info-->

									<!--begin::Subtitle-->
									<span class="text-gray-500 pt-1 fw-semibold fs-6">Total Pajak Masuk</span>
									<!--end::Subtitle-->
								</div>
								<!--end::Title-->
							</div>
							<!--end::Header-->

							<!--begin::Card body-->
							<div
								class="card-body pt-2 pb-4 d-flex align-items-center">
								<!--begin::Chart-->
								<div class="d-flex flex-center me-5 pt-2">
									<div
										id="kt_card_widget_4_chart"
										style="min-width: 70px; min-height: 70px"
										data-kt-size="70"
										data-kt-line="11"></div>
								</div>
								<!--end::Chart-->

								<!--begin::Labels-->
								<div
									class="d-flex flex-column content-justify-center w-100">
									<!--begin::Label-->
									<div
										class="d-flex fs-6 fw-semibold align-items-center">
										<!--begin::Bullet-->
										<div
											class="bullet w-8px h-6px rounded-2 bg-danger me-3"></div>
										<!--end::Bullet-->

										<!--begin::Label-->
										<div class="text-gray-500 flex-grow-1 me-4">
											Restoran
										</div>
										<!--end::Label-->

										<!--begin::Stats-->
										<div class="fw-bolder text-gray-700 text-xxl-end">
											<span id="total_pajak_masuk_resto">0</span>
										</div>
										<!--end::Stats-->
									</div>
									<!--end::Label-->

									<!--begin::Label-->
									<div
										class="d-flex fs-6 fw-semibold align-items-center my-3">
										<!--begin::Bullet-->
										<div
											class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
										<!--end::Bullet-->

										<!--begin::Label-->
										<div class="text-gray-500 flex-grow-1 me-4">
											Hotel
										</div>
										<!--end::Label-->

										<!--begin::Stats-->
										<div class="fw-bolder text-gray-700 text-xxl-end">
											0
										</div>
										<!--end::Stats-->
									</div>
									<!--end::Label-->

									<!--begin::Label-->
									<div
										class="d-flex fs-6 fw-semibold align-items-center">
										<!--begin::Bullet-->
										<div
											class="bullet w-8px h-6px rounded-2 me-3"
											style="background-color: #e4e6ef"></div>
										<!--end::Bullet-->

										<!--begin::Label-->
										<div class="text-gray-500 flex-grow-1 me-4">
											Lainnya
										</div>
										<!--end::Label-->

										<!--begin::Stats-->
										<div class="fw-bolder text-gray-700 text-xxl-end">
											0
										</div>
										<!--end::Stats-->
									</div>
									<!--end::Label-->
								</div>
								<!--end::Labels-->
							</div>
							<!--end::Card body-->
						</div>
						<!--end::Card widget 5-->
					</div>
					<!--end::Col-->

					<!-- Chart custom -->
					<!-- <div class="col-lg-12 col-xl-12 col-xxl-6 mb-5 mb-xl-0">						
						<div class="card card-flush overflow-hidden h-md-100">							
							<div class="card-header py-5">								
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label fw-bold text-gray-900">Grafik Transaksi Objek Pajak</span>
									<span class="text-gray-500 mt-1 fw-semibold fs-5">Pertumbuhan grafik beberapa bulan terakhir</span>
								</h3>								
								
								<div class="card-toolbar">									
									<button class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
										<i class="ki-duotone ki-dots-square fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
									</button>
								</div>								
							</div>							
							
							<div class="card-body d-flex justify-content-between flex-column pb-1 px-0">								
								<div class="px-9 mb-5">									
									<div class="d-flex mb-2">
										<span id="total_pajak_masuk" class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">0</span>
									</div>									
								</div>								
								
								<div id="kt_charts_widget_3" class="min-h-auto ps-4 pe-6" style="height: 300px; min-height: 315px;">
									<div id="apexchartsokxz9egx" class="apexcharts-canvas apexchartsokxz9egx apexcharts-theme-light" style="width: 991.5px; height: 300px;"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="991.5" height="300">
											<foreignObject x="0" y="0" width="991.5" height="300">
											</foreignObject>
											<g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
											<g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
											<rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
											<g class="apexcharts-yaxis" rel="0" transform="translate(37.765625, 0)">
												<g class="apexcharts-yaxis-texts-g">
													<text x="20" y="34" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-yaxis-label " style="font-family: inherit;">
														<tspan>$24K</tspan>
														<title>$24K</title>
													</text>
												</g>
											</g>
											<g class="apexcharts-inner apexcharts-graphical" transform="translate(67.765625, 30)">
												<defs>
													<clipPath id="gridRectMaskokxz9egx">
														<rect width="920.734375" height="228.82" x="-3.5" y="-3.5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
													</clipPath>
													<clipPath id="gridRectBarMaskokxz9egx">
														<rect width="920.734375" height="228.82" x="-3.5" y="-3.5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
													</clipPath>
													<clipPath id="gridRectMarkerMaskokxz9egx">
														<rect width="920.734375" height="221.82" x="-3.5" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
													</clipPath>
													<clipPath id="forecastMaskokxz9egx"></clipPath>
													<clipPath id="nonForecastMaskokxz9egx"></clipPath>
													<linearGradient x1="0" y1="0" x2="0" y2="1" id="SvgjsLinearGradient1004">
														<stop stop-opacity="0.4" stop-color="rgba(23,198,83,0.4)" offset="0"></stop>
														<stop stop-opacity="0" stop-color="rgba(255,255,255,0)" offset="0.8"></stop>
														<stop stop-opacity="0" stop-color="rgba(255,255,255,0)" offset="1"></stop>
													</linearGradient>
												</defs>
												<g class="apexcharts-grid">
													<g class="apexcharts-gridlines-horizontal">
														<line x1="0" y1="55.455" x2="913.734375" y2="55.455" stroke="#dbdfe9" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line>
														<line x1="0" y1="110.91" x2="913.734375" y2="110.91" stroke="#dbdfe9" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line>
														<line x1="0" y1="166.365" x2="913.734375" y2="166.365" stroke="#dbdfe9" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line>
													</g>
													<g class="apexcharts-gridlines-vertical"></g>
													<line x1="0" y1="221.82" x2="913.734375" y2="221.82" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
													<line x1="0" y1="1" x2="0" y2="221.82" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
												</g>
												<g class="apexcharts-grid-borders">
													<line x1="0" y1="0" x2="913.734375" y2="0" stroke="#dbdfe9" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line>
													<line x1="0" y1="221.82" x2="913.734375" y2="221.82" stroke="#dbdfe9" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line>
												</g>
												<g class="apexcharts-area-series apexcharts-plot-series">
													<g class="apexcharts-series" zIndex="0" seriesName="Sales" data:longestSeries="true" rel="1" data:realIndex="0">
														<path d="M 0 95.06571428571425C 17.767057291666667 95.06571428571425 32.99596354166667 95.06571428571425 50.763020833333336 95.06571428571425C 68.530078125 95.06571428571425 83.75898437500001 63.377142857142815 101.52604166666667 63.377142857142815C 119.29309895833333 63.377142857142815 134.52200520833333 63.377142857142815 152.2890625 63.377142857142815C 170.05611979166667 63.377142857142815 185.28502604166667 95.06571428571425 203.05208333333334 95.06571428571425C 220.81914062500002 95.06571428571425 236.048046875 95.06571428571425 253.81510416666669 95.06571428571425C 271.58216145833336 95.06571428571425 286.8110677083333 31.68857142857138 304.578125 31.68857142857138C 322.3451822916667 31.68857142857138 337.5740885416667 31.68857142857138 355.34114583333337 31.68857142857138C 373.10820312500005 31.68857142857138 388.337109375 63.377142857142815 406.1041666666667 63.377142857142815C 423.87122395833336 63.377142857142815 439.1001302083334 63.377142857142815 456.86718750000006 63.377142857142815C 474.63424479166673 63.377142857142815 489.8631510416667 95.06571428571425 507.63020833333337 95.06571428571425C 525.397265625 95.06571428571425 540.6261718750001 95.06571428571425 558.3932291666667 95.06571428571425C 576.1602864583334 95.06571428571425 591.3891927083333 63.377142857142815 609.15625 63.377142857142815C 626.9233072916667 63.377142857142815 642.1522135416667 63.377142857142815 659.9192708333334 63.377142857142815C 677.686328125 63.377142857142815 692.9152343750001 95.06571428571425 710.6822916666667 95.06571428571425C 728.4493489583334 95.06571428571425 743.6782552083333 95.06571428571425 761.4453125 95.06571428571425C 779.2123697916667 95.06571428571425 794.4412760416667 63.377142857142815 812.2083333333334 63.377142857142815C 829.975390625 63.377142857142815 845.2042968750001 63.377142857142815 862.9713541666667 63.377142857142815C 880.7384114583334 63.377142857142815 895.9673177083334 31.68857142857138 913.7343750000001 31.68857142857138C 913.7343750000001 31.68857142857138 913.7343750000001 31.68857142857138 913.7343750000001 221.82 L 0 221.82z" fill="url(#SvgjsLinearGradient1004)" fill-opacity="1" stroke="none" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskokxz9egx)" pathTo="M 0 95.06571428571425C 17.767057291666667 95.06571428571425 32.99596354166667 95.06571428571425 50.763020833333336 95.06571428571425C 68.530078125 95.06571428571425 83.75898437500001 63.377142857142815 101.52604166666667 63.377142857142815C 119.29309895833333 63.377142857142815 134.52200520833333 63.377142857142815 152.2890625 63.377142857142815C 170.05611979166667 63.377142857142815 185.28502604166667 95.06571428571425 203.05208333333334 95.06571428571425C 220.81914062500002 95.06571428571425 236.048046875 95.06571428571425 253.81510416666669 95.06571428571425C 271.58216145833336 95.06571428571425 286.8110677083333 31.68857142857138 304.578125 31.68857142857138C 322.3451822916667 31.68857142857138 337.5740885416667 31.68857142857138 355.34114583333337 31.68857142857138C 373.10820312500005 31.68857142857138 388.337109375 63.377142857142815 406.1041666666667 63.377142857142815C 423.87122395833336 63.377142857142815 439.1001302083334 63.377142857142815 456.86718750000006 63.377142857142815C 474.63424479166673 63.377142857142815 489.8631510416667 95.06571428571425 507.63020833333337 95.06571428571425C 525.397265625 95.06571428571425 540.6261718750001 95.06571428571425 558.3932291666667 95.06571428571425C 576.1602864583334 95.06571428571425 591.3891927083333 63.377142857142815 609.15625 63.377142857142815C 626.9233072916667 63.377142857142815 642.1522135416667 63.377142857142815 659.9192708333334 63.377142857142815C 677.686328125 63.377142857142815 692.9152343750001 95.06571428571425 710.6822916666667 95.06571428571425C 728.4493489583334 95.06571428571425 743.6782552083333 95.06571428571425 761.4453125 95.06571428571425C 779.2123697916667 95.06571428571425 794.4412760416667 63.377142857142815 812.2083333333334 63.377142857142815C 829.975390625 63.377142857142815 845.2042968750001 63.377142857142815 862.9713541666667 63.377142857142815C 880.7384114583334 63.377142857142815 895.9673177083334 31.68857142857138 913.7343750000001 31.68857142857138C 913.7343750000001 31.68857142857138 913.7343750000001 31.68857142857138 913.7343750000001 221.82 L 0 221.82z" pathFrom="M 0 221.82 L 0 221.82 L 50.763020833333336 221.82 L 101.52604166666667 221.82 L 152.2890625 221.82 L 203.05208333333334 221.82 L 253.81510416666669 221.82 L 304.578125 221.82 L 355.34114583333337 221.82 L 406.1041666666667 221.82 L 456.86718750000006 221.82 L 507.63020833333337 221.82 L 558.3932291666667 221.82 L 609.15625 221.82 L 659.9192708333334 221.82 L 710.6822916666667 221.82 L 761.4453125 221.82 L 812.2083333333334 221.82 L 862.9713541666667 221.82 L 913.7343750000001 221.82z"></path>
														<path d="M 0 95.06571428571425C 17.767057291666667 95.06571428571425 32.99596354166667 95.06571428571425 50.763020833333336 95.06571428571425C 68.530078125 95.06571428571425 83.75898437500001 63.377142857142815 101.52604166666667 63.377142857142815C 119.29309895833333 63.377142857142815 134.52200520833333 63.377142857142815 152.2890625 63.377142857142815C 170.05611979166667 63.377142857142815 185.28502604166667 95.06571428571425 203.05208333333334 95.06571428571425C 220.81914062500002 95.06571428571425 236.048046875 95.06571428571425 253.81510416666669 95.06571428571425C 271.58216145833336 95.06571428571425 286.8110677083333 31.68857142857138 304.578125 31.68857142857138C 322.3451822916667 31.68857142857138 337.5740885416667 31.68857142857138 355.34114583333337 31.68857142857138C 373.10820312500005 31.68857142857138 388.337109375 63.377142857142815 406.1041666666667 63.377142857142815C 423.87122395833336 63.377142857142815 439.1001302083334 63.377142857142815 456.86718750000006 63.377142857142815C 474.63424479166673 63.377142857142815 489.8631510416667 95.06571428571425 507.63020833333337 95.06571428571425C 525.397265625 95.06571428571425 540.6261718750001 95.06571428571425 558.3932291666667 95.06571428571425C 576.1602864583334 95.06571428571425 591.3891927083333 63.377142857142815 609.15625 63.377142857142815C 626.9233072916667 63.377142857142815 642.1522135416667 63.377142857142815 659.9192708333334 63.377142857142815C 677.686328125 63.377142857142815 692.9152343750001 95.06571428571425 710.6822916666667 95.06571428571425C 728.4493489583334 95.06571428571425 743.6782552083333 95.06571428571425 761.4453125 95.06571428571425C 779.2123697916667 95.06571428571425 794.4412760416667 63.377142857142815 812.2083333333334 63.377142857142815C 829.975390625 63.377142857142815 845.2042968750001 63.377142857142815 862.9713541666667 63.377142857142815C 880.7384114583334 63.377142857142815 895.9673177083334 31.68857142857138 913.7343750000001 31.68857142857138" fill="none" fill-opacity="1" stroke="#17c653" stroke-opacity="1" stroke-linecap="butt" stroke-width="3" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskokxz9egx)" pathTo="M 0 95.06571428571425C 17.767057291666667 95.06571428571425 32.99596354166667 95.06571428571425 50.763020833333336 95.06571428571425C 68.530078125 95.06571428571425 83.75898437500001 63.377142857142815 101.52604166666667 63.377142857142815C 119.29309895833333 63.377142857142815 134.52200520833333 63.377142857142815 152.2890625 63.377142857142815C 170.05611979166667 63.377142857142815 185.28502604166667 95.06571428571425 203.05208333333334 95.06571428571425C 220.81914062500002 95.06571428571425 236.048046875 95.06571428571425 253.81510416666669 95.06571428571425C 271.58216145833336 95.06571428571425 286.8110677083333 31.68857142857138 304.578125 31.68857142857138C 322.3451822916667 31.68857142857138 337.5740885416667 31.68857142857138 355.34114583333337 31.68857142857138C 373.10820312500005 31.68857142857138 388.337109375 63.377142857142815 406.1041666666667 63.377142857142815C 423.87122395833336 63.377142857142815 439.1001302083334 63.377142857142815 456.86718750000006 63.377142857142815C 474.63424479166673 63.377142857142815 489.8631510416667 95.06571428571425 507.63020833333337 95.06571428571425C 525.397265625 95.06571428571425 540.6261718750001 95.06571428571425 558.3932291666667 95.06571428571425C 576.1602864583334 95.06571428571425 591.3891927083333 63.377142857142815 609.15625 63.377142857142815C 626.9233072916667 63.377142857142815 642.1522135416667 63.377142857142815 659.9192708333334 63.377142857142815C 677.686328125 63.377142857142815 692.9152343750001 95.06571428571425 710.6822916666667 95.06571428571425C 728.4493489583334 95.06571428571425 743.6782552083333 95.06571428571425 761.4453125 95.06571428571425C 779.2123697916667 95.06571428571425 794.4412760416667 63.377142857142815 812.2083333333334 63.377142857142815C 829.975390625 63.377142857142815 845.2042968750001 63.377142857142815 862.9713541666667 63.377142857142815C 880.7384114583334 63.377142857142815 895.9673177083334 31.68857142857138 913.7343750000001 31.68857142857138" pathFrom="M 0 221.82 L 0 221.82 L 50.763020833333336 221.82 L 101.52604166666667 221.82 L 152.2890625 221.82 L 203.05208333333334 221.82 L 253.81510416666669 221.82 L 304.578125 221.82 L 355.34114583333337 221.82 L 406.1041666666667 221.82 L 456.86718750000006 221.82 L 507.63020833333337 221.82 L 558.3932291666667 221.82 L 609.15625 221.82 L 659.9192708333334 221.82 L 710.6822916666667 221.82 L 761.4453125 221.82 L 812.2083333333334 221.82 L 862.9713541666667 221.82 L 913.7343750000001 221.82" fill-rule="evenodd"></path>
														<g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0">
															<g class="apexcharts-series-markers">
																<path d="M 0, 0 
																	m -0, 0 
																	a 0,0 0 1,0 0,0 
																	a 0,0 0 1,0 -0,0" fill="#17c653" fill-opacity="1" stroke="#17c653" stroke-opacity="0.9" stroke-linecap="butt" stroke-width="3" stroke-dasharray="0" cx="0" cy="0" shape="circle" class="apexcharts-marker wl1zeh2z7 no-pointer-events" default-marker-size="0"></path>
															</g>
														</g>
													</g>
													<g class="apexcharts-datalabels" data:realIndex="0"></g>
												</g>
												<line x1="0" y1="0" x2="0" y2="221.82" stroke="#17c653" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="221.82" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line>
												<line x1="0" y1="0" x2="913.734375" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
												<line x1="0" y1="0" x2="913.734375" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
												<g class="apexcharts-xaxis" transform="translate(0, 0)">
													<g class="apexcharts-xaxis-texts-g" transform="translate(0, -10)">
														<text x="0" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
															<tspan></tspan>
															<title></title>
														</text><text x="50.76302083333333" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
															<tspan></tspan>
															<title></title>
														</text><text x="101.52604166666667" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
															<tspan></tspan>
															<title></title>
														</text><text x="152.28906250000003" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 153.412109375 238.32000732421875)" style="font-family: inherit;">
															<tspan>Apr 04</tspan>
															<title>Apr 04</title>
														</text>
													</g>
												</g>
												<g class="apexcharts-yaxis-annotations"></g>
												<g class="apexcharts-xaxis-annotations"></g>
												<g class="apexcharts-point-annotations"></g>
											</g>
											<rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect>
											<rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect>
										</svg>
										<div class="apexcharts-legend" style="max-height: 150px;"></div>
										<div class="apexcharts-tooltip apexcharts-theme-light">
											<div class="apexcharts-tooltip-title" style="font-family: inherit; font-size: 12px;"></div>
											<div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0" style="order: 1;"><span class="apexcharts-tooltip-marker" shape="circle" style="color: rgb(23, 198, 83);"></span>
												<div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;">
													<div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
													<div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
													<div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
												</div>
											</div>
										</div>
										<div class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light">
											<div class="apexcharts-xaxistooltip-text" style="font-family: inherit; font-size: 12px;"></div>
										</div>
										<div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
											<div class="apexcharts-yaxistooltip-text"></div>
										</div>
									</div>
								</div>								
							</div>							
						</div>						
					</div> -->
					<!-- Custom Chart Contoh -->
					<div class="col-lg-12 col-xl-12 col-xxl-6 mb-5 mb-xl-0">
						<div class="card card-custom card-stretch gutter-b card_chart">
							<div class="card-header border-0">
								<div class="card-title py-5" id="filter-by">
									<button class="btn-filter-by-period btn" data-filter="daily" id="daily-filter" onclick="filterByPeriod(this)">Daily</button>
									<button class="btn-filter-by-period btn active" data-filter="weekly" id="weekly-filter" onclick="filterByPeriod(this)">Weekly</button>
									<button class="btn-filter-by-period btn" data-filter="monthly" id="monthly-filter" onclick="filterByPeriod(this)">Monthly</button>
								</div>
								<div class="card-toolbar">
									<!-- <div>
										<select class="form-control select2" id="filter-jenis_usaha" onchange="filterJenisUsaha('tanggal',this)">
										</select>
									</div> -->
									<div id="spinner-statistik-nominal d-none" class="spinner-border text-primary d-none mx-5" role="status">
										<span class="sr-only">Loading...</span>
									</div>
								</div>
							</div>
							<div class="card-body" style="position: relative;">
								<div id="chartrealisasipajak" style="min-height: 365px;"></div>
							</div>
						</div>
					</div>
				</div>
				<!--end::Row-->


				<!--begin::Row-->
				<div class="row gy-5 g-xl-10">
					<!--begin::Col-->
					<div class="col-xl-4">
						<!--begin::List widget 5-->
						<div class="card card-flush h-xl-100">
							<!--begin::Header-->
							<div class="card-header pt-7">
								<!--begin::Title-->
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label fw-bold text-gray-900">Activity Users</span>
									<span class="text-gray-500 mt-1 fw-semibold fs-6">Status online users</span>
								</h3>
								<!--end::Title-->

								<!--begin::Toolbar-->
								<div class="card-toolbar">
									<a
										href="#"
										class="btn btn-sm btn-light">Details</a>
								</div>
								<!--end::Toolbar-->
							</div>
							<!--end::Header-->

							<!--begin::Body-->
							<div class="card-body">
								<!--begin::Scroll-->
								<div
									class="hover-scroll-overlay-y pe-6 me-n6"
									style="height: 415px">
									<!--begin::Item-->

									<div class="grid-user" id="online-user">
										<!-- <div class="d-flex flex-stack mb-3">
											<div class="me-3">
												<img
													src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>"
													class="w-50px ms-n1 me-1"
													alt="" />
												<span class="text-gray-500 fw-bold">Nama_WP</span>
											</div>
										</div>
										<div class="d-flex flex-stack">
											<span class="text-gray-500 fw-bold">Status:
											</span>
											<span class="badge badge-light-success">Delivered</span>
										</div> -->
									</div>

									<!--end::Item-->
								</div>
								<!--end::Scroll-->
							</div>
							<!--end::Body-->
						</div>
						<!--end::List widget 5-->
					</div>
					<!--end::Col-->

					<!--begin::Col-->
					<div class="col-xl-8">
						<!--begin::Table Widget 5-->
						<div class="card card-flush h-xl-100">
							<!--begin::Card header-->
							<div class="card-header pt-7">
								<!--begin::Title-->
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label fw-bold text-gray-900">Transaksi Terakhir</span>
								</h3>
								<!--end::Title-->
							</div>
							<!--end::Card header-->

							<!--begin::Card body-->
							<div class="card-body">
								<!--begin::Table-->
								<table
									class="table align-middle table-row-dashed fs-6 gy-3"
									id="table-transaksi-terakhir">
									<!--begin::Table head-->
									<thead>
										<!--begin::Table row-->
										<tr
											class="text-start text-gray-500 fw-bold fs-6 gs-0">
											<th class="min-w-150px">Objek Pajak</th>
											<th class="min-w-100px">
												NPWPD
											</th>
											<th class="text-end pe-3 min-w-150px">
												DPP
											</th>
											<th class="text-end pe-3 min-w-100px">Pajak</th>
											<th class="text-end pe-3 min-w-100px">Tanggal Transaksi</th>
										</tr>
										<!--end::Table row-->
									</thead>
									<!--end::Table head-->

									<!--begin::Table body-->
									<tbody class="text-gray-600">
									</tbody>
									<!--end::Table body-->
								</table>
								<!--end::Table-->
							</div>
							<!--end::Card body-->
						</div>
						<!--end::Table Widget 5-->
					</div>
					<!--end::Col-->
				</div>
				<!--end::Row-->
			</div>
			<!--end::Content container-->
		</div>
		<!--end::Content-->
	</div>
	<!--end::Content wrapper-->

	<!--begin::Footer-->
	<div id="kt_app_footer" class="app-footer">
		<!--begin::Footer container-->
		<div
			class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
		</div>
		<!--end::Footer container-->
	</div>
	<!--end::Footer-->
</div>
<!--end:::Main-->

<!-- Data old: Table_bckp12092025 -->

<?php $this->load->view('javascript'); ?>