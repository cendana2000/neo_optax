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
											<span id="total_transaksi_hotel">0</span>
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
											<span id="total_pajak_resto">0</span>
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
											<span id="total_pajak_hotel">0</span>
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

<?php $this->load->view('javascript'); ?>