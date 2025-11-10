<link rel="stylesheet" href="<?= base_url('application/modules/dashboard/views/dashboardv2.css'); ?>">

<div class="bg-white header header-fixed position-lg-static py-5"
	style="height: fit-content; top: 65px; border-top: 1px solid #EFF2F5; z-index: 96;">
	<!--begin::Container-->
	<div class="container-fluid d-flex align-items-center" style="gap: 10px;">
		<!--begin::Title-->
		<h1 class="d-flex align-items-center text-dark font-weight-bolder h3 mb-0">
			Dashboard
		</h1>
		<!--end::Title-->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active text-body" aria-current="page">

				</li>
			</ol>
		</nav>
	</div>
	<!--end::Container-->
</div>
<div class="card card-custom card-stretch gutter-b mt-8 mt-lg-17">
	<!--begin::Header-->
	<div class="card-header h-auto border-0 py-5" style="gap: 10px;">
		<h2 class="text-dark-gray h4 d-flex align-items-center mb-0" style="gap: 10px;">
			<span class="material-icons text-primary">
				filter_list
			</span>
			Filter Data Dashboard
		</h2>
		<button class="btn btn-danger d-flex align-items-center" data-toggle="modal" data-target="#filterDataModal"
			style="gap: 10px;">
			<span class="material-icons-outlined">filter_alt</span>
			Filter By Date
		</button>
		<!-- Modal -->
		<div class="modal fade" id="filterDataModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
			aria-labelledby="filterDataModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="filterDataModalLabel">Filter By Date</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body w-100">
						<div>
							<span class="text-muted font-weight-bold mr-2">Tampilkan Berdasarkan :</span>
							<button id="btn-show-tanggal" class="btn btn-primary btn-sm mr-1"
								data-value="tanggal" onclick="changeBerdasarkan(this)">Tanggal</button>
							<button id="btn-show-bulan" class="btn btn-default btn-sm mr-1" data-value="bulan"
								onclick="changeBerdasarkan(this)">Bulan</button>
						</div>
						<div class=" mt-5 w-100" data-toggle="tooltip" title="Quick actions">
							<ul class="navi navi-hover">
								<li class="navi-separator mb-3 opacity-70"></li>
								<li class="navi-item p-2">
									<form id="tanggal" name="tanggal" action="javascript:filter()">
										<div class="form-group">
											<label style="font-size: 12px;">Tanggal :</label>
											<input type="date" name="awal_tanggal" id="awal_tanggal"
												class="form-control"
												value="<?php echo date_format((new DateTime(date('Y-m-d')))->modify('-7 day'), 'Y-m-d'); ?>">
										</div>
										<div class="form-group">
											<label style="font-size: 12px;">Sampai Dengan :</label>
											<input type="date" name="akhir_tanggal" id="akhir_tanggal"
												class="form-control" value="<?php echo date('Y-m-d'); ?>">
										</div>
										<div class="form-group">
											<label style="font-size: 12px;">Bulan :</label>
											<input type="month" class="form-control" name="bulan" id="bulan"
												value="<?php echo date('Y-m') ?>">
										</div>
										<div class="form-group">
											<button type="button" class="btn  btn-secondary" style="width:40%;" data-dismiss="modal">Close</button>
											<button type="submit"
												class="btn btn-blue float-right" style="width:40%" ;"><span
													class="fas fa-search"></span> Tampilkan</button>
										</div>
									</form>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-4">
		<div class="card card-custom rounded-lg bgi-no-repeat shadow-sm card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body">
				<div class="d-flex align-items-center mb-3">
					<span class="svg-icon svg-icon-3x svg-icon-primary mb-auto mt-auto mr-3">
						<svg width="18" height="18" viewBox="0 0 18 18" fill="none"
							xmlns="http://www.w3.org/2000/svg">
							<path d="M16.0587 5.47057L8.99986 1.94116L1.94104 5.47057V12.5294L8.99986 16.0588L16.0587 12.5294V5.47057Z"
								stroke="#003A97" stroke-width="1.5" stroke-linejoin="round" />
							<path d="M1.94104 5.47058L8.99986 8.99999" stroke="#003A97" stroke-width="1.5"
								stroke-linecap="round" stroke-linejoin="round" />
							<path d="M8.99988 16.0588V9" stroke="#003A97" stroke-width="1.5"
								stroke-linecap="round" stroke-linejoin="round" />
							<path d="M16.0587 5.47058L8.99988 8.99999" stroke="#003A97" stroke-width="1.5"
								stroke-linecap="round" stroke-linejoin="round" />
							<path d="M12.5293 3.70581L5.47046 7.23522" stroke="#003A97" stroke-width="1.5"
								stroke-linecap="round" stroke-linejoin="round" />
						</svg>

						<!-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<rect x="0" y="0" width="24" height="24" />
								<path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" fill="#000000" />
								<path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" fill="#000000" opacity="0.3" />
							</g>
						</svg> -->
						<!--end::Svg Icon-->
					</span>
					<span id="total_stok_barang"
						class="card-title font-weight-bolder text-dark font-size-h2 mb-0 mr-3 h1">0</span>
					<span class="font-weight-bold text-muted d-none">+0%</span>
				</div>
				<span class="font-weight-bold text-muted d-block">Total Stok Barang</span>
			</div>
			<!--end::Body-->
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card card-custom rounded-lg bgi-no-repeat shadow-sm card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body">
				<div class="d-flex align-items-center mb-3">
					<span class="material-icons-outlined"
						style="font-size: 40px; color:#003A97; margin-right: 10px;">
						shopping_cart
					</span>
					<span id="total_pembelian_barang"
						class="card-title font-weight-bolder text-dark font-size-h2 mb-0 mr-3 h1">0</span>
					<span class="font-weight-bold text-muted d-none">+0%</span>
				</div>
				<span class="font-weight-bold text-muted d-block">Total Pembelian Barang</span>
			</div>
			<!--end::Body-->
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card card-custom rounded-lg bgi-no-repeat shadow-sm card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body">
				<div class="d-flex align-items-center mb-3">
					<span class="material-icons-outlined"
						style="font-size: 40px; color:#003A97; margin-right: 10px;">
						shopping_bag
					</span>
					<span id="total_penjualan_barang"
						class="card-title font-weight-bolder text-dark font-size-h2 mb-0 mr-3 h1">0</span>
					<span class="font-weight-bold text-muted d-none">+0%</span>
				</div>
				<span class="font-weight-bold text-muted d-block">Total Penjualan Barang</span>
			</div>
			<!--end::Body-->
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">
		<div class="card card-custom card-stretch gutter-b py-5 bgi">
			<!--begin::Header-->
			<div class="card-header h-auto border-0">
				<div class="card-title py-5">
					<h3 class="card-label">
						<span class="d-block text-dark font-weight-bolder">Statistik Penjualan</span>
						<div class="d-flex flex-row">
							<div class="d-flex flex-row mt-7 align-items-center mr-7">
								<span style="height: .4rem; width:30px;" class="rounded bg-success mr-3"></span>
								<span class="font-weight-bold text-dark font-size-sm">Tunai</span>
							</div>
							<div class="d-flex flex-row mt-7 align-items-center">
								<span style="height: .4rem; width:30px;" class="rounded bg-warning mr-3"></span>
								<span class="font-weight-bold text-dark font-size-sm">Piutang</span>
							</div>
						</div>
					</h3>
				</div>
				<div class="card-toolbar">
					<div id="spinner-statistik-penjualan" class="spinner-border text-primary d-none" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
			</div>
			<!--end::Header-->
			<!--begin::Body-->
			<div class="card-body" style="position: relative;">
				<div id="chartpenjualan" style="min-height: 365px;"></div>
			</div>
			<!--end::Body-->
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card card-custom card-stretch gutter-b">
			<div class="card-header h-auto border-0">
				<div class="card-title py-5">
					<h3 class="card-label">
						<span class="d-block text-dark font-weight-bolder">Barang Terlaris</span>
						<span class="text-muted font-weight-bold font-size-sm">10 Barang Terlaris</span>
					</h3>
				</div>
			</div>
			<div class="card-body pt-0 ">
				<div id="barang_terlaris" class="d-flex flex-column"
					style="height:430px;overflow-y:auto;overflow-x:hidden;">
					<!-- LEAVE EMPTY -->
					<!-- appended in function getBarangTerlaris -->
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-4">
		<div class="card card-custom bgi-no-repeat rounded-lg shadow-sm gutter-b text-white bgi-no-repeat" style="background-position: center; background-size: cover; background-image: url(<?= base_url('dokumen/dashboard_rzl/purple.svg'); ?>);">
			<div class="card-header h-auto border-0">
				<div class="card-title py-5">
					<h3 class="card-label">
						<span class="d-block font-weight-bolder text-white">Pendapatan Bersih</span>
					</h3>
				</div>
			</div>
			<!--begin::Body-->
			<div class="card-body pt-0">
				<div class="d-flex align-items-center">
					<span class="font-size-h2 h1 font-weight-bold">Rp. </span>
					<span id="pendapatan_bersih"
						class="card-title font-weight-bolder font-size-h2 mb-0 mr-3 h1">0</span>
					<span class="font-weight-bold d-none">+0%</span>
				</div>
				<span id="range_pendapatan_bersih" class="font-weight-bold d-block">--/-- - --/--</span>
			</div>
			<!--end::Body-->
		</div>
		<div class="card card-custom gutter-b rounded-lg shadow-sm d-flex flex-column text-white bgi-no-repeat" style="background-position:center; background-size: cover; background-image: url(<?= base_url('dokumen/dashboard_rzl/oren.svg'); ?>);">
			<div class="card-header h-auto border-0">
				<div class="card-title py-5">
					<h3 class="card-label">
						<span class="d-block font-weight-bolder text-white">Total Hutang</span>
					</h3>
				</div>
			</div>
			<div class="card-body pt-0">
				<span id="total_hutang" class="font-weight-bolder h1 d-block">0</span>
				<div class="progress progress-md mt-4 gradient-bar ">
					<div id="total_hutang_progress" class="progress-bar bar-line" role="progressbar"
						style="width: 0%; height: 20px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
					</div>
				</div>
				<span class="font-weight-bold d-block mt-4">Terbayar: <span
						id="total_hutang_terbayar">0</span></span>
			</div>
		</div>
		<div class="card card-custom gutter-b rounded-lg shadow-sm d-flex flex-column text-white bgi-no-repeat" style="background-position:center; background-size: cover; background-image: url(<?= base_url('dokumen/dashboard_rzl/blue.svg'); ?>);">
			<div class="card-header h-auto border-0">
				<div class="card-title py-5">
					<h3 class="card-label">
						<span class="d-block font-weight-bolder text-white">Total Piutang</span>
					</h3>
				</div>
			</div>
			<div class="card-body pt-0">
				<span id="total_piutang" class="font-weight-bolder h1 d-block">0</span>
				<div class="progress progress-md mt-4 gradient-bar">
					<div id="total_piutang_progress" class="progress-bar bg-warning" role="progressbar"
						style="width: 0%; height: 20px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
					</div>
				</div>
				<span class="font-weight-bold d-block mt-4">Terbayar: <span
						id="total_piutang_terbayar">0</span></span>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card card-custom card-stretch gutter-b">
			<!--begin::Header-->
			<div class="card-header h-auto border-0">
				<div class="card-title py-5">
					<h3 class="card-label">
						<span class="d-block text-dark font-weight-bolder">Statistik Pembelian</span>
						<div class="d-flex flex-row">
							<div class="d-flex flex-row mt-7 align-items-center mr-7">
								<span style="height: .4rem; width:30px;" class="rounded bg-success mr-3"></span>
								<span class="font-weight-bold text-dark font-size-sm">Tunai</span>
							</div>
							<div class="d-flex flex-row mt-7 align-items-center">
								<span style="height: .4rem; width:30px;" class="rounded bg-warning mr-3"></span>
								<span class="font-weight-bold text-dark font-size-sm">Hutang</span>
							</div>
						</div>
					</h3>
				</div>
				<div class="card-toolbar">
					<div id="spinner-statistik-pembelian" class="spinner-border text-primary d-none" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
			</div>
			<!--end::Header-->
			<!--begin::Body-->
			<div class="card-body" style="position: relative;">
				<div id="chartpembelian" style="min-height: 365px;"></div>
			</div>
			<!--end::Body-->
		</div>
	</div>
</div>

<?php $this->load->view('Javascript'); ?>