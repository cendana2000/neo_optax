<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header ">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">LAPORAN FAKTUR PEMBELIAN</h3>
				</div>
			</div>
			<div class="card-body table-responsive">
				<form action="javascript:getLaporan('lap-pembelian')" name="lap-pembelian" id="lap-pembelian">
					<div class="kt-form">
						<div class="kt-portlet__body">
							<div class="form-group row">
								<label for="periode" class="col-2 col-form-label">Periode</label>
								<div class="col-3">
									<select class="form-control" name="periode" id="periode" onchange="setPeriode(this)">
										<option value="tanggal">Tanggal</option>
										<option value="bulan">Bulan</option>
									</select>
								</div>
								<label for="tanggal" class="col-1 col-form-label tanggal">Tanggal</label>
								<div class="col-3 tanggal">
									<input type="date" class="form-control" name="tanggal" id="tanggal" value="<?php echo date('Y-m-d'); ?>">
								</div>
								<label for="bulan" class="col-1 col-form-label bulan">Bulan</label>
								<div class="col-2 bulan">
									<input type="month" class="form-control monthpicker" name="bulan" id="bulan" value="<?php echo date('Y-m') ?>">
								</div>
								<label for="bulan_akhir" class="col-1 col-form-label bulan">Hingga</label>
								<div class="col-2 bulan">
									<input type="month" class="form-control monthpicker" name="bulan_akhir" id="bulan_akhir" value="<?php echo date('Y-m') ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="laporan" class="col-2 col-form-label">Jenis Laporan</label>
								<div class="col-3">
									<select class="form-control" name="laporan" onclick="jenisLaporan()" id="laporan">
										<option value="">Global</option>
										<option value="_supplier">Supplier</option>
										<option value="_rekap">Rekap</option>
									</select>
								</div>
								<label for="supplier_id" class="col-1 col-form-label supplier ">Supplier</label>
								<div class="col-5" id="supplierForm">
									<select class="form-control supplier " name="supplier_id" id="supplier_id">
									</select>
								</div>
							</div>

							<div class="form-group row">
								<div class="col-2"></div>
								<div class="col-4">
									<button type="submit" class="btn btn-danger"><i class="fa fa-file-pdf"></i> PDF</button>
									<span onclick="getLaporanExcel()" id="btn-excel" class="btn btn-success"><i class="fa fa-file"></i> Excel</span>
								</div>
							</div>
						</div>
					</div>
				</form>
				<div class="kt-portlet kt-portlet--mobile"></div>
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






<?php load_view('javascript.php'); ?>