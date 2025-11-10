<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">LOG AKTIVITAS</h3>
				</div>

				<!-- <div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh('table-log')"><i class="flaticon-refresh icon-md"></i> Muat Ulang</button>
					</div>
				</div> -->
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-striped table-checkable table-condensed table-hover" id="table-log">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Tanggal</th>
							<th>Nama Wajib Pajak</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Tanggal</th>
							<th>Nama Wajib Pajak</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="row table_subdata" style="display:none;">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DETAIL LOG AKTIFITAS</h3>
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-secondary btn-sm" onclick="onBack()"><i class="flaticon2-left-arrow-1 icon-md"></i> Kembali</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<div class="form-group row">
					<label class="col-2 col-form-label">Nama Wajib Pajak</label>
					<div class="col-10 d-flex align-items-center">
						: <span id="sublog_namawp" class="ml-2"></span>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-2 col-form-label">Tanggal Aktifitas</label>
					<div class="col-10 d-flex align-items-center">
						: <span id="sublog_tanggal" class="ml-2"></span>
					</div>
				</div>
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-sublog">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Aktifitas</th>
							<th>Tanggal</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Aktifitas</th>
							<th>Tanggal</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('Javascript'); ?>