<link rel="stylesheet" href="<?= base_url('assets/css/custom_statusdevice.css'); ?>">
<div class="row form_data" style="display: none;">
	<div class="card card-custom gutter-b mb-5">
		<div class="card-body">
			<div class="text-center mb-6">
				<h3 class="font-weight-bolder mb-1">
					<i class="fa fa-chart-column text-primary mr-2"></i>
					Transaksi Objek Pajak
				</h3>
				<span class="text-muted">Rincian transaksi berdasarkan periode</span>
				<div class="position-absolute" style="right:15px; top:10%;">
					<button type="button"
						class="btn btn-sm btn-secondary"
						onclick="onBack()">
						<i class="fa fa-arrow-left mr-1"></i> Kembali
					</button>
				</div>
			</div>
			<div class="row mt-5">
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-6 mb-4">
							<div class="text-muted small">NPWPD</div>
							<div class="font-weight-bolder" id="sub_wajibpajak_npwpd" name="sub_wajibpajak_npwpd">-</div>
						</div>
						<div class="col-md-6 mb-4">
							<div class="text-muted small">Nama Perusahaan</div>
							<div class="font-weight-bolder" id="sub_wajibpajak_nama" name="sub_wajibpajak_nama">-</div>
						</div>
						<div class="col-md-6 mb-4">
							<div class="text-muted small">Alamat</div>
							<div class="font-weight-bolder" id="sub_wajibpajak_alamat" name="sub_wajibpajak_alamat">-</div>
						</div>
						<div class="col-md-6 mb-4">
							<div class="text-muted small">Penanggung Jawab</div>
							<div class="font-weight-bolder" id="sub_wajibpajak_nama_penanggungjawab" name="sub_wajibpajak_nama_penanggungjawab">-</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="bg-light-success rounded p-4 h-100">
						<div class="text-muted small mb-1">Periode Transaksi</div>
						<button type="button"
							id="btnPeriode"
							class="btn btn-success btn-block text-left">
							<i class="la la-calendar mr-2"></i>
							<span id="label-periode">Pilih Periode</span>
						</button>
						<input type="hidden" id="periode" name="periode">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<h5 class="card-label">
						<i class="fa fa-table text-primary mr-2"></i>
						Daftar Transaksi
					</h5>
				</div>
				<div class="card-toolbar">
					<div class="btn-group">
						<div class="ml-auto dropdown">
							<button class="btn btn-sm btn-light dropdown-toggle" type="button" id="exportDropdown"
								data-bs-toggle="dropdown" aria-expanded="false">
								<i class="fas fa-file-export me-1"></i> Export
							</button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li><a class="dropdown-item" href="javascript:getExcelRinciRekap()"><i class="far fa-file-excel text-success me-2"></i> Excel</a></li>
								<li><a class="dropdown-item" href="javascript:getPdfRinciRekap()"><i class="far fa-file-pdf text-danger me-2"></i> PDF</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-rincirekappajak">
						<thead>
							<tr>
								<th style="width:5%;">No.</th>
								<th>Objek Pajak</th>
								<th>Tanggal Penjualan</th>
								<th>Waktu</th>
								<th>Nominal Penjualan</th>
								<th>Kode Penjualan</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<th class="table-primary" colspan="4">Total</th>
								<th class="table-primary" id="transaksiwp_total_nominal_penjualan">Rp. 0</th>
								<th class="table-primary" colspan="3"></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Filter Tanggal -->
<div class="modal fade" id="modalPeriode" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<i class="la la-calendar mr-2 text-primary"></i> Filter Periode
				</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-4 border-right">
						<h6 class="mb-3 font-weight-bold">Quick Range</h6>
						<ul class="list-group list-group-flush">
							<li class="list-group-item list-range" data-range="today">Today</li>
							<li class="list-group-item list-range" data-range="yesterday">Yesterday</li>
							<li class="list-group-item list-range" data-range="7">Last 7 days</li>
							<li class="list-group-item list-range" data-range="30">Last 30 days</li>
							<li class="list-group-item list-range" data-range="90">Last 90 days</li>
							<li class="list-group-item list-range" data-range="365">Last 365 days</li>
						</ul>
					</div>
					<div class="col-md-8">
						<h6 class="mb-3 font-weight-bold">Custom Range</h6>
						<input type="text" class="form-control" id="customRange" placeholder="Pilih range tanggal">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
				<button class="btn btn-sm btn-success" id="btnApplyPeriode" type="button">
					<i class="la la-check mr-1"></i> Apply
				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Struk -->
<div class="modal fade" id="modal-detail-transaksi" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="pengaturan_title" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-body" style="border-bottom: 2px dotted grey;">
				<div class="container">
					<h4 class="d-flex justify-content-center" style="padding-top: 10px;" id="pengaturan_title"></h4>
					<p class="d-flex justify-content-center text-muted" style="padding-top: 5px; margin-bottom:0px;" id="alamat_wp"></p>
				</div>
			</div>
			<form action="javascript:;" id="form-detail-transaksi">
				<div class="modal-body">
					<div class="container">
						<table class="table table-borderless">
							<tbody>
								<tr>
									<td style="max-width: 100px;">Kode Penjualan</td>
									<td>:</td>
									<td id="kode_penjualan"></td>
								</tr>
								<tr>
									<td style="max-width: 100px;">Tanggal</td>
									<td>:</td>
									<td id="tanggal"></td>
								</tr>
								<tr>
									<td style="max-width: 100px;">Waktu</td>
									<td>:</td>
									<td id="waktu"></td>
								</tr>
								<tr>
									<td style="max-width: 100px;">Sub Total(DPP)</td>
									<td>:</td>
									<td id="sub_total"></td>
								</tr>
								<tr>
									<td style="max-width: 100px;">Service Charge</td>
									<td>:</td>
									<td id="service"></td>
								</tr>
								<tr>
									<td style="max-width: 100px;">Diskon</td>
									<td>:</td>
									<td id="diskon"></td>
								</tr>
								<tr>
									<td style="max-width: 100px;">Pajak</td>
									<td>:</td>
									<td id="pajak"></td>
								</tr>
								<tr>
									<td style="max-width: 100px;">Grand Total</td>
									<td>:</td>
									<td id="grand_total"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer border-0 pt-0 d-flex justify-content-end">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row mt-3 subreport_data_pdf" style="display: none;">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fa fa-table text-primary"></i>
					</span>
					<h3 class="card-label">LAPORAN RINCIAN REKAP PAJAK</h3>
				</div>
				<div class="card-toolbar">
					<button type="button" class="btn btn-sm btn-secondary" onclick="onBackCard(4)"><i class="fa fa-arrow-left"></i> Kembali</button>
				</div>
			</div>
			<div class="card-body table-responsive">
				<div class="kt-portlet kt-portlet--mobile ">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
							</h3>
						</div>
					</div>
					<div class="kt-form">
						<div class="kt-portlet__body form" id="subpdf-laporan">
							<object data="" type="application/pdf" width="100%" height="500px"></object>
						</div>
					</div>
				</div>
				<div class="kt-portlet kt-portlet--mobile"></div>
			</div>
		</div>
	</div>
</div>