<div class="kt-portlet kt-portlet--mobile">
	<form action="javascript:getLaporan('lap-konsinyasi')" name="lap-konsinyasi" id="lap-konsinyasi">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Laporan Konsinyasi
				</h3>
			</div>
		</div>
		<div class="kt-form">
			<div class="kt-portlet__body">
				<div class="form-group row">
					<label for="jenis_retur" class="col-2 col-form-label">Jenis Laporan</label>
					<div class="col-3">
						<select class="form-control" name="jenis_laporan" id="jenis_laporan">
							<option value="">Pilih</option>
							<option value="laporansaldo/">Saldo</option>
							<option value="laporanpembelian/">Pembelian</option>
							<option value="laporanpenjualan/">Penjualan</option>
							<option value="laporanretur/returbeli_">Retur Pembelian</option>
							<option value="laporanretur/returjual_">Retur Penjualan</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="periode" class="col-2 col-form-label">Periode</label>
					<div class="col-3">
						<select class="form-control" name="periode" id="periode" onchange="setPeriode(this)">
							<option value="tanggal">Tanggal</option>
							<option value="bulan">Bulan</option>
						</select>
					</div>
					<label for="tanggal" class="col-1 col-form-label tanggal">Tanggal</label>
					<div class="col-2 tanggal">
						<input type="date" class="form-control" name="tanggal" id="tanggal" value="<?php echo date('d/m/Y'); ?>">
					</div>
					<label for="tanggal_sampai" class="col-1 col-form-label tanggal">Sampai</label>
					<div class="col-2 tanggal">
						<input type="date" class="form-control" name="tanggal_sampai" id="tanggal_sampai" value="<?php echo date('d/m/Y'); ?>">
					</div>
					<label for="bulan" class="col-1 col-form-label bulan">Bulan</label>
					<div class="col-3 bulan">
						<input type="month" class="form-control" name="bulan" id="bulan" value="<?php echo date('m-Y') ?>">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-2">
						<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Proses</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="kt-portlet kt-portlet--mobile kt-laporan" style="display:none;">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Hasil Laporan Konsinyasi
			</h3>
		</div>
	</div>
	<div class="kt-form">
		<div class="kt-portlet__body form" id="pdf-laporan">
            <object data="" type="application/pdf" width="100%" height="500px"></object>
		</div>
	</div>
</div>

<?php load_view('javascript.php'); ?>