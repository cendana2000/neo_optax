<div class="row table_data">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> DATA PEGAWAI
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<div class="kt-portlet__head-toolbar-wrapper">
							<div class="dropdown dropdown-inline">
								<!-- <button type="button" class="btn btn-warning btn-elevate" onclick="scanBarcode()"><i class="fa fa-barcode"></i>Scan Barcode</button> -->
								<button type="button" class="btn btn-success btn-sm btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
								<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<!--begin: Datatable -->
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-pegawai">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>NIK Pegawai</th>
							<th>Nama Pegawai</th>
							<th>Jenis Kelamin</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>NIK Pegawai</th>
							<th>Nama Pegawai</th>
							<th>Jenis Kelamin</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>

				<!--end: Datatable -->
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('javascript'); ?>
<?php $this->load->view('form'); ?>