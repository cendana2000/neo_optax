<div class="row">
	<div class="col-12 col-md-5 mb-3 " data-roleable="false" data-role="satuan-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM SATUAN</h3>
				</div>
			</div>
			<form action="javascript:save('form-satuan')" method="post" id="form-satuan" name="form-satuan" autocomplete="off">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<input type="hidden" name="satuan_id">
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="satuan_kode">Satuan</label>
								<div class="col-lg-8">
									<input type="text" name="satuan_kode" class="form-control satuan_kode" placeholder="Satuan" minlength="2" maxlength="10">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="satuan_nama">Keterangan</label>
								<div class="col-lg-8">
									<input type="text" name="satuan_nama" class="form-control satuan_nama" placeholder="Keterangan" minlength="2" maxlength="150">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-6 text-left">
							<button type="reset" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-redo" id="btnReset"></i>Reset</button>
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
					<h3 class="card-label">DATA SATUAN</h3>
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#importModal"><i class="flaticon-upload"></i> Import Data</button>
					</div>
					<div class="example-tools justify-content-center ml-1">
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-satuan">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Satuan</th>
							<th>Keterangan</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>satuan</th>
							<th>Keterangan</th>
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
				<h5 class="modal-title">Import Satuan</h5>
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
					<a href="<?= base_url('assets/laporan/satuan/satuan_template.xlsx'); ?>" class="btn btn-success"><span class="fas fa-download"></span> Download Template</a>
					<button type="submit" class="btn btn-success" id="btn_save"><span class="fas fa-paper-plane"></span> Proses</button>
				</div>
			</form>
		</div>
	</div>
</div>




<?php load_view('javascript') ?>