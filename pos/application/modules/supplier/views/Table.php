<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> DATA SUPPLIER
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<div class="kt-portlet__head-toolbar-wrapper">
							<div class="dropdown dropdown-inline">
								<button type="button" class="btn btn-success btn-sm btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
								<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#importModal"><i class="flaticon-upload"></i> Import Data</button>
								<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
								<button type="button" class="btn btn-info btn-sm btn-elevate btn-elevate-air" onclick="onPrint()">
									<i class="la la-print"></i> Cetak
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-supplier">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode Supplier</th>
							<th>Nama Supplier</th>
							<th>Telp</th>
							<th>Alamat</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Kode Supplier</th>
							<th>Nama Supplier</th>
							<th>Telp</th>
							<th>Alamat</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal laporan -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- konten modal-->
		<div class="modal-content">
			<!-- heading modal -->
			<div class="modal-header">
				<h4 class="modal-title">Cetak</h4>
				<button type="button" class="close" data-dismiss="modal"></button>
			</div>
			<!-- body modal -->
			<div class="modal-body" style="width: 100%">
				<form class="kt-form" name="cetakSupplier" id="cetakSupplier">
					<div class="form-group row">
						<label for="supplier1" class="col-form-label">Supplier</label>
						<div class="col-4">
							<select class="form-control" name="supplier1" id="supplier1" style="width: 100%;"></select>
						</div>

						<label for="supplier2" class="col-form-label">s/d</label>
						<div class="col-4">
							<select class="form-control" name="supplier2" id="supplier2" style="width: 100%;"></select>
						</div>

					</div>
				</form>
			</div>
			<!-- footer modal -->
			<div class="modal-footer">
				<div class="col-4">
					<button type="button" onclick="loadPreview()" class="btn btn-success" data-dismiss="modal"><i class="la la-print"></i> Cetak</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Hodler loadPreview -->
<div class="card card-custom form_data2" style="display: none;">
	<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
		<div class="ribbon-target" style="top: 12px;">
			<span class="ribbon-inner bg-primary"></span> DAFTAR SUPPLIER
		</div>
		<div class="card-toolbar">
			<button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Kembali</button>
		</div>
	</div>
	<div class="card-body">
		<div class="kt-form">
			<div class="kt-portlet__body form" id="pdf-laporan">
				<object data="" type="application/pdf" width="100%" height="500px"></object>
			</div>
		</div>
	</div>
</div>

<!-- Modal-->
<div class="modal fade" id="importModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Import Supplier</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form action="javascript:upload('form-import')" method="post" id="form-import" name="form-import" autocomplete="off" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="form-group row">
									<div class="col-12">
										<div class="custom-file">
											<input type="file" class="custom-file-input" id="file_import" name="file_import" />
											<label class="custom-file-label" for="customFile">Choose file</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<a href="<?= base_url('assets/laporan/supplier/supplier_template.xlsx'); ?>" class="btn btn-success"><span class="fas fa-download"></span> Download Template</a>
					<button type="submit" class="btn btn-success" id="btn_save"><span class="fas fa-paper-plane"></span> Proses</button>
				</div>
			</form>
		</div>
	</div>
</div>



<?php $this->load->view('form'); ?>
<?php $this->load->view('javascript'); ?>