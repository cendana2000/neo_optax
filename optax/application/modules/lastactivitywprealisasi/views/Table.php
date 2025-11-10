<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">Last Activity Wajib Pajak PERSADA</h3>
				</div>

				<div class="card-toolbar">
					<div class="card-toolbar">
						<div class="btn-group">
							<!-- <button class="btn btn-success btn-sm" onclick="getSpreadsheetLastActivityWp()"><i class="far fa-file-excel"></i> Excel</button>
							<button class="btn btn-danger btn-sm" onclick="getPdfLastActivityWp()"><i class="far fa-file-pdf"></i> PDF</button> -->
							<!-- <button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button> -->
						</div>
					</div>
				</div>
			</div>
			<div class="card-body">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="active-tab" data-toggle="tab" data-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">Active <span class="badge badge-success ml-2 active-counter">0</span></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="inactive-tab" data-toggle="tab" data-target="#inactive" type="button" role="tab" aria-controls="inactive" aria-selected="false">Inactive <span class="badge badge-warning ml-2 inactive-counter">0</span></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="offline-tab" data-toggle="tab" data-target="#offline" type="button" role="tab" aria-controls="offline" aria-selected="false">Offline <span class="badge badge-danger ml-2 offline-counter">0</span></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="all-tab" data-toggle="tab" data-target="#all" type="button" role="tab" aria-controls="all" aria-selected="false">All <span class="badge badge-primary ml-2 all-counter">0</span></button>
					</li>
				</ul>
				<div class="tab-content mt-5" id="myTabContent">
					<div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
						<div class="table-responsive">
							<table class="table table-checkable table-condensed" id="table-lastactivitywprealisasi-active" style="width: 100%">
								<thead>
									<tr>
										<th style="width:5%;">No.</th>
										<th>Nama WP</th>
										<th>NPWPD</th>
										<th>Tanggal Transaksi Terakhir</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<tr class="no-list">
										<td colspan="4" class="text-center">Price List Tidak Tersedia</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<th style="width:5%;">No.</th>
										<th>Nama WP</th>
										<th>NPWPD</th>
										<th>Tanggal Transaksi Terakhir</th>
										<th>Status</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="inactive" role="tabpanel" aria-labelledby="inactive-tab">
						<div class="table-responsive">
							<table class="table table-checkable table-condensed" id="table-lastactivitywprealisasi-inactive" style="width: 100%">
								<thead>
									<tr>
										<th style="width:5%;">No.</th>
										<th>Nama WP</th>
										<th>NPWPD</th>
										<th>Tanggal Transaksi Terakhir</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<tr class="no-list">
										<td colspan="4" class="text-center">Price List Tidak Tersedia</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<th style="width:5%;">No.</th>
										<th>Nama WP</th>
										<th>NPWPD</th>
										<th>Tanggal Transaksi Terakhir</th>
										<th>Status</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="offline" role="tabpanel" aria-labelledby="offline-tab">
						<div class="table-responsive">
							<table class="table table-checkable table-condensed" id="table-lastactivitywprealisasi-offline" style="width: 100%">
								<thead>
									<tr>
										<th style="width:5%;">No.</th>
										<th>Nama WP</th>
										<th>NPWPD</th>
										<th>Tanggal Transaksi Terakhir</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<tr class="no-list">
										<td colspan="4" class="text-center">Price List Tidak Tersedia</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<th style="width:5%;">No.</th>
										<th>Nama WP</th>
										<th>NPWPD</th>
										<th>Tanggal Transaksi Terakhir</th>
										<th>Status</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
						<div class="table-responsive">
							<table class="table table-checkable table-condensed" id="table-lastactivitywprealisasi-all" style="width: 100%">
								<thead>
									<tr>
										<th style="width:5%;">No.</th>
										<th>Nama WP</th>
										<th>NPWPD</th>
										<th>Tanggal Transaksi Terakhir</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<tr class="no-list">
										<td colspan="4" class="text-center">Price List Tidak Tersedia</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<th style="width:5%;">No.</th>
										<th>Nama WP</th>
										<th>NPWPD</th>
										<th>Tanggal Transaksi Terakhir</th>
										<th>Status</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__body print_table" style="display: none">
		<div class="kt-form">
			<div class="kt-portlet__body form" id="pdf-laporan">
				<object data="" type="application/pdf" width="100%" height="500px"></object>
			</div>
		</div>
	</div>
</div>

<div class="row mt-3 report_data_pdf" style="display: none;">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">HASIL LAPORAN LAST ACTIVITY WAJIB PAJAK</h3>
				</div>
				<div class="card-toolbar">
					<button type="button" class="btn btn-sm btn-secondary" onclick="onBackCard(1)"><i class="fa fa-arrow-left"></i> Kembali</button>
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
						<div class="kt-portlet__body form" id="pdf-laporan-lastactivitywp_realisasi">
							<object data="" type="application/pdf" width="100%" height="500px"></object>
						</div>
					</div>
				</div>
				<div class="kt-portlet kt-portlet--mobile"></div>
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript.php'); ?>