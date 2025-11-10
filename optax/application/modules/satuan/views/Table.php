<div class="row">
	<div class="col-12 col-md-5 mb-3 " data-roleable="false" data-role="satuan-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM SATUAN
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
									<input type="text" name="satuan_kode" class="form-control satuan_kode" placeholder="Satuan" minlength="2" maxlength="150">
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
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>DATA SATUAN
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
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
<?php load_view('javascript') ?>