<div class="card card-custom mb-8">
	<div class="card-header">
		<div class="card-title">
			<span class="card-icon">
				<i class="fas fa-table text-primary"></i>
			</span>
			<h3 class="card-label">LAPORAN BARANG LARIS</h3>
		</div>
	</div>
	<div class="card-body">
		<form action="javascript:getLaporan('lap-saldo')" name="lap-saldo" id="lap-saldo">
			<div class="form-group row">
				<label for="bulan" class="col-1 col-form-label">Bulan</label>
				<div class="col-3">
					<input type="month" class="form-control" name="bulan" id="bulan" value="<?php echo date('Y-m') ?>">
				</div>
				<label class="col-2 col-form-label" for="barang_kategori_barang">Kategori</label>
				<div class="col-4 kategori">
					<select class="form-control" name="barang_kategori_barang" id="barang_kategori_barang" style="width: 100%">
					</select>
				</div>
				<div class="col-2">
					<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Proses</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="card card-custom kt-laporan" style="display:none;">
	<div class="card-header ">
		<div class="card-title">
			<span class="card-icon">
				<i class="fas fa-table text-primary"></i>
			</span>
			<h3 class="card-label">HASIL</h3>
		</div>
	</div>
	<div class="card-body">
		<div class="form" id="pdf-laporan">
			<object data="" type="application/pdf" width="100%" height="500px"></object>
		</div>
	</div>
</div>

<?php load_view('javascript.php'); ?>