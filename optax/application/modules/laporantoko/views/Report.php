<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">LAPORAN TEMPAT USAHA</h3>
				</div>
			</div>
			<div class="card-body table-responsive">
				<div class="kt-form">
					<div class="kt-portlet__body">
						<div class="form-group row">
							<label for="reportMode" class="col-lg-2 col-sm-12 col-form-label">Jenis Laporan</label>
							<div class="col-lg-10 col-sm-12">
								<select onchange="setFilter()" class="form-control" name="reportMode" id="reportMode">
									<option value="Rekap" selected>Rekap</option>
									<option value="Single">Single</option>
								</select>
							</div>
						</div>
						<div class="form-group row" id="singleMode" style="display: none;">
							<label for="toko_select" class="col-2 col-form-label">Tempat Usaha</label>
							<div class="col-10">
								<select class="form-control" name="toko_select" id="toko_select">
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-lg-2"></div>
							<div class="col-10">
								<button onclick="getLaporanRekap()" id="btnRekap" class="btn btn-danger"><i class="far fa-file-alt"></i> Cetak</button>
								<button onclick="getLaporan()" id="btnSingle" style="display: none;" class="btn btn-danger"><i class="far fa-file-alt"></i> Cetak</button>
								<button onclick="getLaporanRekapExcel()" id="btnRekapExcel" class="btn btn-success"><i class="far fa-file-excel"></i> Excel</button>
							</div>
						</div>
					</div>
				</div>
				<div class="kt-portlet kt-portlet--mobile"></div>
			</div>
		</div>
	</div>
</div>
<div class="row mt-3 kt-laporan" style="display: none;">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DATA WAJIB PAJAK</h3>
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




<?php $this->load->view('Javascript'); ?>