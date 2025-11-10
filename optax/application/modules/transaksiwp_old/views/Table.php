<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">Transaksi Wajib Pajak</h3>
				</div>
				<div class="card-toolbar">
					<div class="card-toolbar">
						<div class="btn-group" id="button-tool" style="display: none;">
							<button class="btn btn-success btn-sm" onclick="getSpreadsheetTransaksiWp()"><i class="far fa-file-excel"></i> Excel</button>
							<button class="btn btn-danger btn-sm" onclick="getPdfTransaksiWp()"><i class="far fa-file-pdf"></i> PDF</button>
							<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive border-bottom">
				<form action="javascript:init_table()" name="pricelist-form" id="pricelist-form">
					<div class="form-group">
						<label for="example-search-input" class="form-label text-dark">Pilih Toko</label>
						<select class="form-control select2" id="select_toko" onchange="onChangeToko(this)">
						</select>
					</div>
					<div class="form-group">
						<label for="example-search-input" class="form-label text-dark">Pilih Periode</label>
						<div class="input-group input-group-sm">
							<input type="text" class="form-control daterange" name="periode" id="periode" value="" placeholder="Pilih Bulan" />
							<div class="input-group-append"><span class="input-group-text"><i class="la la-calendar-check-o "></i></span></div>
						</div>
					</div>
					<div id="next-action" style="display: none;">
						<div class="row">
							<div class="col-6" style="margin-top: 25px;" align="left">
								<button type="button" class="btn btn-primary btn-elevate" id="btn-prosess" onclick="init_table(this)">
									<span>
										<i class="la la-check"></i>
										<span>Proses</span>
									</span>
								</button>
							</div>
						</div>
						<div class="kt-separator kt-separator--md kt-separator--dashed"></div>
					</div>
				</form>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-checkable table-condensed" id="table-transaksiwp" style="width: 100%">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode Toko</th>
							<th>Nama WP</th>
							<th>Penjualan Tanggal</th>
							<th>Waktu</th>
							<th>Nominal Penjualan</th>
							<th>Kode Penjualan</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr class="no-list">
							<td colspan="9" class="text-center">Price List Tidak Tersedia</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th class="table-primary" colspan="5">Total</th>
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
<?php load_view('javascript.php'); ?>