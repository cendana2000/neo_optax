<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">Transaksi Objek Pajak</h3>
				</div>
				<div class="card-toolbar gap-2" id="button-tool" style="display: none;">
					<div class="dropdown">
						<button class="btn btn-sm btn-light dropdown-toggle" type="button" id="exportDropdown"
							data-bs-toggle="dropdown" aria-expanded="false">
							<i class="fas fa-file-export me-1"></i> Export
						</button>
						<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
							<li>
								<a class="dropdown-item" href="javascript:void(0)" onclick="getSpreadsheetTransaksiWp()">
									<i class="far fa-file-excel text-success me-2"></i> Excel
								</a>
							</li>
							<li>
								<a class="dropdown-item" href="javascript:void(0)" onclick="getPdfTransaksiWp()">
									<i class="far fa-file-pdf text-danger me-2"></i> PDF
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive border-bottom">
				<form action="javascript:init_table()" name="pricelist-form" id="pricelist-form">
					<div class="form-group row">
						<label for="select_toko" class="col-lg-2 col-sm-12 col-md-12 col-form-label text-dark">Pilih Toko</label>
						<div class="col-lg-4 col-sm-12 col-md-12">
							<select class="form-control select2" id="select_toko" onchange="onChangeToko(this)">
							</select>
						</div>
						<label for="periode" class="col-lg-2 col-sm-12 col-md-12 col-form-label text-dark">Pilih Periode</label>
						<div class="col-lg-4 col-sm-12 col-md-12">
							<div class="input-group input-group-sm">
								<input type="text" class="form-control daterange" name="periode" id="periode" value="" placeholder="Pilih Bulan" />
								<div class="input-group-append"><span class="input-group-text"><i class="la la-calendar-check-o "></i></span></div>
							</div>
						</div>
					</div>

					<div id="next-action" style="display: none;">
						<div class="row">
							<div class="col-12" align="right">
								<button type="button" class="btn btn-sm btn-primary btn-elevate" id="btn-prosess" onclick="init_table(this)">
									<span>
										<i class="la la-check"></i>
										<span>Proses</span>
									</span>
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>


			<div class="card-body table-responsive">
				<table class="table table-checkable table-condensed" id="table-transaksiwp" style="width: 100%">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Nama WP</th>
							<th>Tanggal Penjualan</th>
							<th>Waktu</th>
							<th>Nominal Penjualan</th>
							<th>Kode Penjualan</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr class="no-list">
							<td colspan="9" class="text-center">Tidak Ada Data</td>
						</tr>
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
								<!-- <tr>
									<td style="max-width: 500px;">Status Penjualan :</td>
									<td id="status"></td>
								</tr> -->
							</tbody>
						</table>
						<!-- <span class="form-text text-muted">Centang jika ingin menampilkan bidang ini pada struk</span> -->
					</div>
				</div>
				<div class="modal-footer border-0 pt-0 d-flex justify-content-end">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php load_view('javascript.php'); ?>