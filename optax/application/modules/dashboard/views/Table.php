<link rel="stylesheet" href="<?= base_url('assets/css/custom_dashboard.css'); ?>">
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
	<div class="row dashboard_data">
		<div class="d-flex flex-column flex-column-fluid">
			<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
				<div
					id="kt_app_toolbar_container"
					class="app-container container-xxl d-flex flex-stack">
					<div
						class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
						<h1
							class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
							Dashboard Pemerintah Daerah
						</h1>
						<ul
							class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
							<li class="breadcrumb-item text-muted">
								<a
									href="<?= base_url(); ?>">
									Home
								</a>
							</li>
							<li class="breadcrumb-item">
								<span class="bullet bg-gray-500 w-5px h-2px"></span>
							</li>
							<li class="breadcrumb-item text-muted">Dashboards</li>
						</ul>
					</div>
					<div class="dropdown dropdown-inline dd-filter ms-auto" data-toggle="tooltip" title="Quick actions" data-placement="right">
						<a href="#" class="btn btn-danger fw-semibold mb-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-calendar text-white px-4"></i>
							<span class="pr-4">Filter</span>
						</a>
						<div class="dropdown-menu mt-1 p-0 m-0 dropdown-menu-md dropdown-menu-right">
							<ul class="navi navi-hover">
								<li class="navi-header font-weight-bold py-4">
									<span class="font-size-lg">Tampilkan berdasarkan:</span>
								</li>
								<li class="navi-separator mb-3 opacity-70"></li>
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
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div id="kt_app_content" class="app-content flex-column-fluid">
				<div
					id="kt_app_content_container"
					class="app-container container-xxl">
					<div class="row gx-5 gx-xl-10 mb-xl-10">
						<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-10">
							<div class="card card-flush h-md-50 mb-xl-10">
								<div class="card-header pt-5">
									<div class="card-title d-flex flex-column">
										<span id="total_wajib_pajak"
											class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">0</span>
										<span class="text-gray-500 pt-1 fw-semibold fs-6">Objek Pajak Terkoneksi</span>
									</div>
								</div>
								<div
									class="card-body d-flex flex-column justify-content-end pe-0">
									<div id="toko_baru" class="symbol-group symbol-hover flex-nowrap">
									</div>
								</div>
							</div>
							<div class="card card-flush h-md-50 mb-5 mb-xl-10">
								<div class="card-header pt-5">
									<div class="card-title d-flex flex-column">
										<div class="d-flex align-items-center">
											<span id="total_transaksi" class="card-number text-gray-900">0</span>
										</div>
										<span class="text-gray-500 pt-1 fw-semibold fs-6">Total Transaksi(DPP)</span>
									</div>
								</div>
								<div
									class="card-body pt-2 pb-4 d-flex align-items-center">
									<!-- <div class="d-flex flex-center me-5 pt-2">
									<div
										id="kt_card_widget_4_chart"
										style="min-width: 70px; min-height: 70px"
										data-kt-size="70"
										data-kt-line="11"></div>
								</div> -->
									<div
										class="d-flex flex-column content-justify-center w-100">
										<div
											class="d-flex fs-6 fw-semibold align-items-center number-row">
											<div
												class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
											<div class="text-gray-500 flex-grow-1 me-4">
												Restoran
											</div>
											<div class="fw-bolder text-gray-700 text-xxl-end">
												<span id="total_transaksi_resto" class="card-number-small">0</span>
											</div>
										</div>
										<div
											class="d-flex fs-6 fw-semibold align-items-center my-3 number-row">
											<div
												class="bullet w-8px h-6px rounded-2 bg-warning me-3"></div>
											<div class="text-gray-500 flex-grow-1 me-4">
												Hotel
											</div>
											<div class="fw-bolder text-gray-700 text-xxl-end">
												<span id="total_transaksi_hotel" class="card-number-small">0</span>
											</div>
										</div>
										<div
											class="d-flex fs-6 fw-semibold align-items-center">
											<div
												class="bullet w-8px h-6px rounded-2 me-3"
												style="background-color: #e4e6ef"></div>
											<div class="text-gray-500 flex-grow-1 me-4">
												Lainnya
											</div>
											<div class="fw-bolder text-gray-700 text-xxl-end">
												0
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-10">
							<div class="card card-flush h-lg-50 mb-xl-10">
								<div class="card-header pt-5">
									<h3 class="card-title text-gray-800">Total Objek Pajak</h3>
									<div class="card-toolbar d-none">
										<div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left" class="btn btn-sm btn-light d-flex align-items-center px-4">
											<div class="text-gray-600 fw-bold">
												Loading date range...
											</div>
											<i class="ki-duotone ki-calendar-8 fs-1 ms-2 me-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
										</div>
									</div>
								</div>
								<div class="card-body pt-5">
									<div class="d-flex flex-stack">
										<div class="text-gray-700 fw-semibold fs-6 me-2">Restoran</div>
										<div class="d-flex align-items-senter">
											<i class="ki-duotone ki-arrow-up-right fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i>
											<span id="total_wp_resto" class="text-gray-900 fw-bolder fs-6">0</span>
										</div>
									</div>
									<div class="separator separator-dashed my-3"></div>
									<div class="d-flex flex-stack">
										<div class="text-gray-700 fw-semibold fs-6 me-2">Hotel</div>
										<div class="d-flex align-items-senter">
											<i class="ki-duotone ki-arrow-down-right fs-2 text-danger me-2"><span class="path1"></span><span class="path2"></span></i>
											<span id="total_wp_hotel" class="text-gray-900 fw-bolder fs-6">0</span>
										</div>
									</div>
									<div class="separator separator-dashed my-3"></div>
									<div class="d-flex flex-stack">
										<div class="text-gray-700 fw-semibold fs-6 me-2">Lainnya</div>
										<div class="d-flex align-items-senter">
											<i class="ki-duotone ki-arrow-up-right fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i>
											<span class="text-gray-900 fw-bolder fs-6">0</span>
										</div>
									</div>
								</div>
							</div>
							<div class="card card-flush h-md-50 mb-xl-10">
								<div class="card-header pt-5">
									<div class="card-title d-flex flex-column">
										<div class="d-flex align-items-center">
											<span id="total_pajak_masuk" class="card-number text-gray-900">0</span>
										</div>
										<span class="text-gray-500 pt-1 fw-semibold fs-6">Total Pajak Masuk</span>
									</div>
								</div>
								<div
									class="card-body pt-2 pb-4 d-flex align-items-center">
									<!-- <div class="d-flex flex-center me-5 pt-2">
									<div
										id="kt_card_widget_4_chart"
										style="min-width: 70px; min-height: 70px"
										data-kt-size="70"
										data-kt-line="11"></div>
								</div> -->
									<div
										class="d-flex flex-column content-justify-center w-100">
										<div
											class="d-flex fs-6 fw-semibold align-items-center">
											<div
												class="bullet w-8px h-6px rounded-2 bg-danger me-3"></div>
											<div class="text-gray-500 flex-grow-1 me-4">
												Restoran
											</div>
											<div class="fw-bolder text-gray-700 text-xxl-end">
												<span id="total_pajak_resto" class="card-number-small">0</span>
											</div>
										</div>
										<div
											class="d-flex fs-6 fw-semibold align-items-center my-3">
											<div
												class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
											<div class="text-gray-500 flex-grow-1 me-4">
												Hotel
											</div>
											<div class="fw-bolder text-gray-700 text-xxl-end">
												<span id="total_pajak_hotel" class="card-number-small">0</span>
											</div>
										</div>
										<div
											class="d-flex fs-6 fw-semibold align-items-center">
											<div
												class="bullet w-8px h-6px rounded-2 me-3"
												style="background-color: #e4e6ef"></div>
											<div class="text-gray-500 flex-grow-1 me-4">
												Lainnya
											</div>
											<div class="fw-bolder text-gray-700 text-xxl-end">
												0
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
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
					<div class="row gy-5 g-xl-10">
						<div class="col-xl-4">
							<div class="card card-flush h-xl-100">
								<div class="card-header pt-7">
									<div class="d-flex justify-content-between align-items-center w-100">
										<h3 class="card-title mb-0">
											<span class="card-label fw-bold text-gray-900 fs-4">Activity Users</span>
										</h3>
									</div>
									<div>
										<span class="text-gray-600 fw-semibold fs-6">Status online users</span>
									</div>
								</div>
								<div class="card-body">
									<div class="hover-scroll-overlay-y pe-6 me-n6" style="height: 415px">
										<div class="hover-scroll-overlay-y-wrapper">
											<div class="grid-user" id="online-user"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-8">
							<div class="card card-flush h-xl-100">
								<div class="card-header pt-7 d-flex justify-content-between align-items-center">
									<h3 class="card-title align-items-start flex-column">
										<span class="card-label fw-bold text-gray-900">Transaksi Terakhir</span>
									</h3>
									<button
										type="button"
										class="btn btn-secondary btn-sm"
										id="btn-detail-transaksi"
										onclick="onDetailTransaksiTerakhir()">
										<i class="fas fa-list me-1"></i> Detail
									</button>
								</div>
								<div class="card-body table-responsive">
									<table
										class="table align-middle table-row-dashed fs-6 gy-3"
										id="table-transaksi-terakhir">
										<thead>
											<tr
												class="text-start text-gray-500 fw-bold fs-6 gs-0">
												<th class="min-w-150px">Objek Pajak</th>
												<th style="width:5%;">
													NPWPD
												</th>
												<th>No. Transaksi</th>
												<th class="text-end pe-3 min-w-100px">
													DPP
												</th>
												<th class="text-end pe-3 min-w-100px">Pajak</th>
												<th class="text-end pe-3 min-w-150px text-nowrap">Tanggal Transaksi</th>
											</tr>
										</thead>
										<tbody class="text-gray-600">
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row transaksi_detail_data" style="display:none">
		<div class="col-12 mb-3">
			<div class="card card-custom">
				<div class="card-header d-flex justify-content-between align-items-center">
					<div class="card-title">
						<span class="card-icon">
							<i class="fas fa-receipt text-primary"></i>
						</span>
						<h3 class="card-label">DETAIL TRANSAKSI TERAKHIR</h3>
					</div>

					<button
						type="button"
						class="btn btn-light btn-sm"
						onclick="backToDashboard()">
						<i class="fas fa-arrow-left me-1"></i> Kembali
					</button>
				</div>

				<div class="card-body table-responsive">
					<table class="table align-middle table-row-dashed fs-6 gy-3"
						id="table-transaksi-terakhir-detail">
						<thead>
							<tr class="text-start text-gray-500 fw-bold fs-6 gs-0">
								<th>No</th>
								<th>Objek Pajak</th>
								<th>NPWPD</th>
								<th>No. Transaksi</th>
								<th class="text-end">DPP</th>
								<th class="text-end">Pajak</th>
								<th class="text-end">Tanggal Transaksi</th>
							</tr>
						</thead>
						<tbody class="text-gray-600">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div id="kt_app_footer" class="app-footer">
		<div
			class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
		</div>
	</div>
</div>
<?php $this->load->view('javascript'); ?>