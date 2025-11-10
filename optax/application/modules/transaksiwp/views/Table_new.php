<div class="row table_data">
	<!-- Table Custom -->
	<div class="col-xl-12">
		<div class="card card-flush h-xl-100">
			<div class="card-header pt-7">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Objek Pajak</span>
				</h3>
				<div class="card-toolbar">
					<div class="export-dropdown">
						<div class="btn-group">
							<button type="button" id="button-tool" style="display: none;" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="fas fa-download"></i>Export Data
							</button>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="javascript:void(0)" onclick="getSpreadsheetTransaksiWp()">
										<i class="fas fa-file-excel text-success"></i> Export ke Excel
									</a></li>
								<li><a class="dropdown-item" href="javascript:void(0)" onclick="getPdfTransaksiWp()">
										<i class="fas fa-file-pdf text-danger"></i> Export ke PDF
									</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="periode" class="col-lg-4 col-sm-12 col-md-12 col-form-label text-dark">Periode</label>
					<div class="col-lg-8 col-sm-12 col-md-12">
						<div class="input-group input-group-sm">
							<input type="text" class="form-control daterange" name="periode" id="periode" value="" placeholder="Pilih Tanggal Transaksi" />
							<div class="input-group-append"><span class="input-group-text"><i class="la la-calendar-check-o "></i></span></div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body">
				<table class="table align-middle table-row-dashed fs-6 gy-3" id="table-listwp">
					<thead>
						<tr
							class="text-start text-gray-500 fw-bold fs-6 gs-0">
							<th class="min-w-100px">
								NPWPD
							</th>
							<th class="min-w-150px">Objek Pajak</th>
							<th class="text-end pe-3 min-w-150px">
								Alamat
							</th>
							<th class="text-end pe-3 min-w-100px">Transaksi Terakhir</th>
							<th class="text-end pe-3 min-w-100px">Total</th>
							<th class="text-end pe-3 min-w-100px">Source Data</th>
						</tr>
					</thead>
					<!-- <tbody class="text-gray-600">
						</tbody> -->
					<tbody>
						<tr class="no-list">
							<td colspan="9" class="text-center">Tidak Ada Transaksi</td>
						</tr>
					</tbody>
				</table>
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
					<h3 class="card-label">HASIL LAPORAN TRANSAKSI WAJIB PAJAK</h3>
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
						<div class="kt-portlet__body form" id="pdf-laporan-transaksiwp">
							<object data="" type="application/pdf" width="100%" height="500px"></object>
						</div>
					</div>
				</div>
				<div class="kt-portlet kt-portlet--mobile"></div>
			</div>
		</div>
	</div>
</div>

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
									<td style="max-width: 100px;">Sub Total</td>
									<td>:</td>
									<td id="sub_total"></td>
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

				<div class="card-footer d-flex justify-content-end">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php load_view('javascript.php'); ?>