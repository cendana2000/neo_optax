<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header ">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">LAPORAN PENJUALAN</h3>
				</div>
			</div>
			<div class="card-body table-responsive">
				<form action="javascript:getLaporan('lap-penjualan')" name="lap-penjualan" id="lap-penjualan">
					<div class="kt-form">
						<div class="kt-portlet__body">
							<div class="form-group row">
								<label for="periode" class="col-1 col-form-label">Periode</label>
								<div class="col-3">
									<select class="form-control" name="periode" id="periode" onchange="setPeriode(this)">
										<option value="tanggal">Harian</option>
										<option value="bulan">Bulanan</option>
									</select>
								</div>
								<label for="tanggal" class="col-1 col-form-label tanggal">Tanggal</label>
								<div class="col-2 tanggal">
									<input type="date" class="form-control" name="tanggal" id="tanggal" onchange="onNota()" value="<?php echo date('Y-m-d'); ?>">
								</div>
								<label for="bulan" class="col-1 col-form-label bulan">Bulan</label>
								<div class="col-2 bulan">
									<input type="month" class="form-control" name="bulan" id="bulan" value="<?php echo date('Y-m') ?>">
								</div>
								<div class="col-2">
								</div>
							</div>

							<!-- <div class="form-group row">
								<label for="nota_awal" class="col-1 col-form-label nota">Invoice</label>
								<div class="col-3">
									<select class="form-control nota" name="nota_awal" id="nota_awal">
									</select>
								</div>
								<label for="nota_akhir" class="col-1 col-form-label nota">s/d</label>
								<div class="col-2">
									<select class="form-control nota" name="nota_akhir" id="nota_akhir">
									</select>
								</div>
							</div> -->
							<div class="form-group row">
								<div class="col-1"></div>
								<div class="col-4">
									<button type="submit" class="btn btn-danger"><i class="fa fa-file-pdf"></i>PDF</button>
									<span onclick="tprintRekap()" id="btn-rekap" class="btn btn-primary"><i class="flaticon-paper-plane-1"></i>Rekap Harian</span>
									<!-- <span onclick="getLaporanExcel()" id="btn-excel" class="btn btn-success"><i class="fa fa-file"></i> Excel</span> -->
								</div>
							</div>
						</div>
					</div>
			</div>
			</form>
		</div>
	</div>
</div>
</div>

<div class="row table_data mt-3 kt-laporan" style="display: none;">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header ">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">HASIL</h3>
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
						<div class="kt-portlet__body form" id="pdf-laporan">
							<object data="" type="application/pdf" width="100%" height="500px"></object>
						</div>
					</div>
				</div>
				<div class="kt-portlet kt-portlet--mobile"></div>
			</div>
		</div>
	</div>
</div>

<div id="printArea" style="display: none;"></div>

<?php load_view('javascript.php'); ?>