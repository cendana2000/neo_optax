<link rel="stylesheet" href="<?= base_url('application/modules/dashboard/views/custom_dashboard.css'); ?>">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">

<!-- Header Dashboard -->
<div class="card card-custom card-stretch gutter-b shadow-sm">
	<!--begin::Header-->
	<div class="card-body p-5 h-auto border-0 row">
		<div class="col-md-6 header-left pl-4">
			<h3 class="m-0 d-flex align-items-center">
				<span class="material-icons-outlined">
					filter_list
				</span>
				<span class="d-block text-dark font-weight-bolder pl-4" id="dashboardTitle">Dashboard
					Pajak
				</span>
			</h3>
		</div>
		<div class="col-md-6 d-flex align-items-center justify-content-end pr-4 header-right" id="toolbar-pajak">
			<div class="dropdown dropdown-inline dd-filter" data-toggle="tooltip" title="Quick actions" data-placement="left">
				<a href="#" class="btn btn-blue btn-filter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fa fa-filter text-white px-4"></i>
					<span class="pr-4">Filter By Date</span>
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
		</div>
	</div>
</div>

<!-- Start Card Section -->
<div class="row">
	<div class="col-lg-4">
		<div class="card card-custom  card-stretch gutter-b bg-warning text-white rounded-big card-pajak" style="background-image: url(<?= base_url('dokumen/dashboard_rzl/oren.svg'); ?>); ">
			<!--begin::Body-->
			<div class="card-body card-pajak d-flex flex-column align-items-start justify-content-between">
				<div class="w-100 d-flex justify-content-between">
					<div class="d-flex align-item-center">
						<span class=" font-weight-bold">Total Pajak Masuk</span>
					</div>
					<span class="material-icons-outlined" role="button">
						more_vert
					</span>
				</div>
				<div class=" d-flex align-items-center">
					<span id="total_pajak_masuk" class="card-title font-weight-bolder font-size-h2 mb-0 mr-3 h1">0</span>
				</div>
				<!-- <span class="font-weight-bold ">+2 Barang Baru Dalam 7 Hari</span> -->
			</div>
			<!--end::Body-->
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card card-custom card-stretch gutter-b bg-info text-white rounded-big card-pajak" style="background-image: url(<?= base_url('dokumen/dashboard_rzl/purple.svg'); ?>);">
			<!--begin::Body-->
			<div class="card-body card-pajak d-flex flex-column align-items-start justify-content-between">
				<div class="w-100 d-flex justify-content-between">
					<div class="d-flex align-item-center">
						<span class=" font-weight-bold d-block">Total Realisasi Wajib Pajak</span>
					</div>
					<span class="material-icons-outlined curso" role="button">
						more_vert
					</span>
				</div>
				<div class="d-flex align-items-center">
					<span id="total_realisasi_wajib_pajak" class="card-title font-weight-bolder font-size-h2 mb-0 mr-3 h1">0</span>
				</div>
				<!-- <span class="font-weight-bold ">+4 Data Baru Dalam 7 Hari</span> -->
			</div>
			<!--end::Body-->
		</div>
	</div>

	<div class="col-lg-4">
		<div class="card card-custom card-stretch gutter-b bg-primary text-white rounded-big card-pajak" style="background-image: url(<?= base_url('dokumen/dashboard_rzl/blue.svg'); ?>); ">
			<!--begin::Body-->
			<div class="card-body card-pajak d-flex flex-column align-items-start justify-content-between">
				<div class="w-100 d-flex justify-content-between">
					<div class="d-flex align-item-center">
						<span class=" font-weight-bold d-block">Total Wajib Pajak</span>
					</div>
					<span class="material-icons-outlined" role="button">
						more_vert
					</span>
				</div>
				<div class=" d-flex align-items-center">
					<span id="total_wajib_pajak" class="card-title font-weight-bolder  font-size-h2 mb-0 mr-3 h1">0</span>
				</div>
				<!-- <span class="font-weight-bold ">+2 Data Baru Dalam 7 Hari</span> -->
			</div>
			<!--end::Body-->
		</div>
	</div>
</div>
<!-- End Card Section -->

<!-- Dashboard Pajak -->
<div id="dashboardPajak">

	<!-- Start Panel Section-->
	<div class="row h-auto container-panel">
		<div class="col-lg-4 gutter-b gutter-x px-0 dashboard">
			<div class="card card-custom gutter-b d-flex flex-column justify-content-between py-4 card_dashboard">
				<div class="h-auto d-flex flex-column ">
					<div class="card-header border-b-2 h-25 pt-4">
						<p class='font-weight-bolder text-dark h1 d-block'>Dashboard</p>
						<div class="w-100 d-flex justify-content-between p-0 m-0">
							<span class='text-muted'>Data Realisasi Pajak</span>
						</div>
					</div>
					<div class="card card-custom gutter-b d-flex flex-column rounded-big" style="height: 465px;">
						<div class="card-header h-auto border-0 mx-0">
							<div class="card-title py-5 w-100 px-0 mx-0">
								<h5 class="card-label d-flex flex-row w-100 justify-content-center align-items-center m-0 p-0">
									<div>
										<span class="d-block text-dark font-weight-bolder mb-4">Online User</span>
										<span class="text-muted font-size-h6 mt-4" id="count-online-user-activity">6 Users</span>
									</div>
									<div class="ml-auto">
										<i role="button" class="far fa-question-circle" data-toggle="popover" title="Status Information" data-content='
										<div class="d-flex flex-row align-items-center">
											<span class="material-icons status text-success">
												lens
											</span>
											<span class="ml-auto">Active</span>
										</div>
										<div class="d-flex flex-row align-items-center">
											<span class="material-icons status text-warning">
												lens
											</span>
											<span class="ml-auto">Inactive</span>
										</div>
										<div class="d-flex flex-row align-items-center">
											<span class="material-icons status text-danger">
												lens
											</span>
											<span class="ml-auto">Offline</span>
										</div>
										<div class="d-flex flex-row align-items-center">
											<span class="material-icons status text-dark">
												lens
											</span>
											<span class="ml-auto">Close</span>
										</div>
										'></i>
									</div>
								</h5>
							</div>
							<div class="w-100 pb-10 d-flex align-items-center">
								<p class="w-25 pl-3">Foto</p>
								<p class="w-75">Nama</p>
							</div>
						</div>
						<div class="card-body pt-0" style="height:465px;overflow-y:auto;overflow-x:hidden;">
							<div class="grid-user" id="online-user-activity">
								<!-- <div class="card-user">
									<img src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>" alt="" />
									<span class="nama">Dialoogi</span>
									<span class="material-icons status text-success">
										lens
									</span>
								</div> -->
							</div>
						</div>
					</div>
					<!-- <div class="card-header shadow-sm side-chart">
						<div class="w-100 d-flex justify-content-between p-0 m-0">
							<span class="text-black font-weight-bold">Target Pajak</span>
							<b id="target_pajak_tahun"></b>
						</div>
						<span id="target_pajak" class="font-weight-bolder text-dark h1 d-block">0</span>
					</div> -->
					<!-- <div class="card-header shadow-sm side-chart">
						<div class="p-0 m-0">
							<span class="d-block text-black font-weight-bold">Pajak Belum dibayar</span>
						</div>
						<span id="pajak_belum_bayar" class="font-weight-bolder text-dark h1 d-block">0</span>
					</div> -->
				</div>
				<!-- <button class="btn btn-blue mb-4 align-self-center monitoring-btn">Monitoring Pajak</button> -->
			</div>
		</div>
		<div class="col-lg-8 px-0 card_chart">
			<div class="card card-custom card-stretch gutter-b card_chart">
				<!--begin::Header-->
				<div class="card-header border-0">
					<!-- Daily, Weekly Monthly -->
					<div class="card-title py-5" id="filter-by">
						<button class="btn-filter-by-period btn" data-filter="daily" id="daily-filter" onclick="filterByPeriod(this)">Daily</button>
						<button class="btn-filter-by-period btn" data-filter="weekly" id="weekly-filter" onclick="filterByPeriod(this)">Weekly</button>
						<button class="btn-filter-by-period btn active" data-filter="monthly" id="monthly-filter" onclick="filterByPeriod(this)">Monthly</button>
					</div>
					<div class="card-toolbar">

						<div>

							<select class="form-control select2" id="filter-jenis_usaha" onchange="filterJenisUsaha('tanggal',this)">
							</select>
						</div>
						<div id="spinner-statistik-nominal d-none" class="spinner-border text-primary d-none mx-5" role="status">
							<span class="sr-only">Loading...</span>
						</div>
					</div>
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body" style="position: relative;">
					<div id="chartrealisasipajak" style="min-height: 365px;"></div>
				</div>
				<!--end::Body-->
			</div>
		</div>
	</div>
	<!-- End Panel Section -->

	<!-- Start Panel User dan Toko -->
	<div class="row">
		<div class="col-lg-12">
			<div class="card card-custom gutter-b d-flex flex-column pb-4 rounded-big" style="height: 500px;">
				<div class="card-header h-auto border-0">
					<div class="card-title py-5">
						<h5 class="card-label">
							<span class="d-block text-dark font-weight-bolder mb-4">Tempat Usaha</span>
							<span class="text-muted font-size-h6 mt-4">5 Tempat Usaha</span>
						</h5>
					</div>
				</div>
				<div class="pt-0 card-body" style="max-height:430px; overflow-x: hidden; overflow-y: auto;">
					<div id="toko_baru" class="grid-toko">

					</div>
				</div>
			</div>
		</div>

	</div>
	<!-- End Panel User dan toko -->
</div>

<?php $this->load->view('javascript'); ?>