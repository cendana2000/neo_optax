<div class="card card-custom mb-8">
	<div class="card-header ">
		<div class="card-title">
			<span class="card-icon">
				<i class="fas fa-table text-primary"></i>
			</span>
			<h3 class="card-label">LAPORAN SALDO</h3>
		</div>
	</div>
	<div class="card-body">
		<form action="javascript:getLaporan('lap-saldo')" name="lap-saldo" id="lap-saldo">
			<div class="form-group row">
				<label for="bulan" class="col-2 col-form-label">Bulan</label>
				<div class="col-3">
					<input type="month" class="form-control" name="bulan" id="bulan" value="<?php echo date('Y-m') ?>">
				</div>
				<!-- <div class="col-2">
					<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Proses</button>
				</div> -->
			</div>
			<div class="form-group row">
				<label for="jenis" class="col-2 col-form-label">Jenis Laporan</label>
				<div class="col-3">
					<select class="form-control select2" name="jenis" id="jenis" onchange="setLaporan(this)">
						<option value="rekap">Rekap</option>
						<option value="detail">Perincian</option>
					</select>
				</div>
				<label for="posting_detail_kategori_id" class="col-1 col-form-label" style="display:none;">Kategori</label>
				<div class="col-3" style="display:none;">
					<select class="form-control" name="posting_detail_kategori_id" id="posting_detail_kategori_id" style="width: 100%">
					</select>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-2"></div>
				<div class="col-5">

					<button type="button" id="tampil" onclick="getTable()" class="btn btn-success" hidden=""><i class="flaticon-paper-plane-1"></i> Tampilkan</button>
					<button type="submit" id="cetak" class="btn btn-info"><i class="flaticon2-print"></i> Cetak</button>
					<span onclick="getLaporanExcel()" id="btn-excel" class="btn btn-success"><i class="fa fa-file"></i> Excel</span>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="card card-custom kt-laporan" style="display:none;">
	<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
		<div class="ribbon-target" style="top: 12px;">
			<span class="ribbon-inner bg-primary"></span> Cetak Laporan Nilai Saldo
		</div>
	</div>
	<div class="card-body">
		<div class="form" id="pdf-laporan">
			<object data="" type="application/pdf" width="100%" height="500px"></object>
		</div>
	</div>
</div>

<div class="card card-custom mb-8 table_data" style="display:none;">
	<div class="card-header ">
		<div class="card-title">
			<span class="card-icon">
				<i class="fas fa-table text-primary"></i>
			</span>
			<h3 class="card-label">HASIL</h3>
		</div>
	</div>
	<div class="card-body">
		<table class="table table-striped table-checkable table-condensed" id="table-saldodetail">
			<thead>
				<tr>
					<th style="width:5%;">No.</th>
					<th>Kode</th>
					<th>Barang</th>
					<th>Awal</th>
					<th>Masuk</th>
					<th>Keluar</th>
					<th>Koreksi</th>
					<th>Stok</th>
					<th>HPP</th>
					<th>Nilai</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot>
				<tr>
					<th style="width:5%;">No.</th>
					<th>Kode</th>
					<th>Barang</th>
					<th>Awal</th>
					<th>Masuk</th>
					<th>Keluar</th>
					<th>Koreksi</th>
					<th>Stok</th>
					<th>HPP</th>
					<th>Nilai</th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<?php load_view('javascript.php'); ?>