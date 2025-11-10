<div class="row">
	<div class="col-12 col-md-5 mb-3 " data-roleable="false" data-role="kategori-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM KATEGORI</h3>
				</div>
			</div>
			<form action="javascript:save('form-kategori')" method="post" id="form-kategori" name="form-kategori" autocomplete="off">
				<input type="hidden" name="kategori_id" id="kategori_id">
				<input type="hidden" id="kategori_barang_id" name="kategori_barang_id">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<div class="form-group row">
								<label for="kategori_barang_nama" class="col-4 col-form-label">Nama Kategori</label>
								<div class="col-8">
									<input type="text" class="form-control" type="text" id="kategori_barang_nama" name="kategori_barang_nama">
								</div>
							</div>
							<div class="form-group row">
								<label for="kategori_barang_tipe" class="col-4 col-form-label">
									Kategori Tipe
								</label>
								<div class="col-8" id="parent">
									<select name="kategori_barang_tipe" class="form-control kategori_barang_tipe" id="kategori_barang_tipe" onchange="handleHide()" style="width: 100%;">
										<option value="">-Pilih Kategori Tipe-</option>
										<option value="parent">Induk</option>
										<option value="detail">Detail</option>
									</select>
								</div>
							</div>
							<div class="form-group row" id="child">
								<label for="kategori_barang_parent" class="col-4 col-form-label">Induk</label>
								<div class="col-8">
									<select name="kategori_barang_parent" id="kategori_barang_parent" class="form-control kategori_barang_parent" style="width: 100%;">
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-6 text-left">
							<button type="reset" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-redo"></i>Reset</button>
						</div>
						<div class="col text-right">
							<button type="submit" id="btnSave" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Simpan</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="col">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DATA KATEGORI</h3>
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button type="button" class="btn btn-primary btn-elevate btn-sm" onclick="onEdit()"><i class="la la-pencil"></i>Edit</button>
					</div>
					<div class="example-tools justify-content-center ml-1">
						<button type="button" class="btn btn-danger btn-elevate btn-sm" onclick="onDestroy()"><i class="la la-trash"></i> Hapus</button>
					</div>
					<div class="example-tools justify-content-center ml-1">
						<button type="button" class="btn btn-warning btn-elevate btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i>Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">

				<!-- DataTables -->
				<!-- <table class="table table-striped table-checkable table-condensed" id="table-kategori">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode Kategori</th>
							<th>Nama Kategori</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Kode Kategori</th>
							<th>Nama Kategori</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table> -->
				<!-- End DataTables -->

				<!-- JS Tree -->
				<div id="tree1">
				</div>
				<!-- End JS Tree -->
			</div>
		</div>
	</div>
</div>

<!-- Modal-->
<div class="modal fade" id="importModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Import Kategori</h5>
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
					<a href="<?= base_url('assets/laporan/kategori/kategori_template.xlsx'); ?>" class="btn btn-success"><span class="fas fa-download"></span> Download Template</a>
					<button type="submit" class="btn btn-success" id="btn_save"><span class="fas fa-paper-plane"></span> Proses</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php load_view('javascript') ?>