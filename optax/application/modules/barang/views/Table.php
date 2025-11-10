<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> DATA PRODUK
				</div>
				<div class="kt-portlet__head-toolbar-wrapper mt-3">
					<div class="dropdown dropdown-inline">
						<button type="button" class="btn btn-info btn-elevate btn-sm" onclick="scanBarcode()"><i class="fa fa-barcode"></i>Scan Barcode</button>
						<button type="button" class="btn btn-success btn-elevate btn-sm" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
						<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#importModal"><i class="flaticon-upload"></i> Import Data</button>
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-striped table-checkable table-condensed" id="table-barang">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Nama Produk</th>
							<th>Kelompok Produk</th>
							<th>Jenis Barang</th>
							<th>Harga </th>
							<th>Stok Min</th>
							<th>Stok</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Nama Produk</th>
							<th>Kelompok Produk</th>
							<th>Jenis Barang</th>
							<th>Harga </th>
							<th>Stok Min</th>
							<th>Stok</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal-->
<div class="modal fade" id="importModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Import Produk</h5>
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
					<button onclick="getTemplate()" class="btn btn-success"><span class="fas fa-download"></span> Download Template</button>
					<button type="submit" class="btn btn-success" id="btn_save"><span class="fas fa-paper-plane"></span> Proses</button>
				</div>
			</form>
		</div>
	</div>
</div>


<?php $this->load->view('form'); ?>
<?php $this->load->view('barcode'); ?>
<?php $this->load->view('javascript'); ?>