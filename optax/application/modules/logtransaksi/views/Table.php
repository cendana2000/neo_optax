<div class="row form_data">
	<div class="col">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">LOG TRANSAKSI</h3>
				</div>
				<input type="hidden" id="sub_wajibpajak_npwpd" value="<?= $this->session->userdata(["wajibpajak_npwpd"]); ?>">
				<div class="card-toolbar">
					<div class="mr-3">
						<div class="input-group input-group-sm">
							<input type="text" class="form-control monthpicker" name="bulan" id="bulan" onchange="filterBulan()" value="" placeholder="Pilih Bulan" />
							<div class="input-group-append"><span class="input-group-text"><i class="la la-calendar-check-o "></i></span></div>
						</div>
					</div>
					<div class="example-tools justify-content-center">
						<div class="btn-group">
							<button class="btn btn-danger btn-sm" onclick="getPdfSubRealisasi()"><i class="far fa-file-pdf"></i> PDF</button>
							<!-- <button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button> -->
						</div>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-logtransaksi">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Tanggal</th>
							<th>Sub Total</th>
							<th>Jasa</th>
							<th>Pajak</th>
							<th>Total</th>
							<th>Kode Penjualan</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Tanggal</th>
							<th>Sub Total</th>
							<th>Jasa</th>
							<th>Pajak</th>
							<th>Total</th>
							<th>Kode Penjualan</th>
						</tr>
						<!-- tambahan -->
						<tr>
							<th class="table-primary" colspan="2">Total</th>
							<th class="table-primary" id="subrealisasi_total_omzet">Rp.0</th>
							<th class="table-primary" id="subrealisasi_total_jasa">Rp.0</th>
							<th class="table-primary" id="subrealisasi_total_pajak">Rp.0</th>
							<th class="table-primary" id="subrealisasi_total_total">Rp.0</th>
							<th class="table-primary" id="kode_penjualan"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('form'); ?>
<?php $this->load->view('javascript'); ?>