<div class="row">
	<div class="col-12 col-md-5 mb-3 " data-roleable="false" data-role="target-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM TARGET PAJAK</h3>
				</div>
			</div>
			<form action="javascript:save('form-target')" method="post" id="form-target" name="form-target" autocomplete="off">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<input type="hidden" name="target_id">
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="target_tahun">Tahun</label>
								<div class="col-lg-8">
									<input type="text" name="target_tahun" class="form-control target_tahun" placeholder="Tahun Target" required minlength="4" maxlength="4">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="target_nominal">Nominal</label>
								<div class="col-lg-8">
									<input type="text" name="target_nominal" class="form-control target_nominal" placeholder="Nominal Target">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="target_keterangan">Keterangan</label>
								<div class="col-lg-8">
									<input type="text" name="target_keterangan" class="form-control target_keterangan" placeholder="Keterangan" minlength="2" maxlength="150">
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
					<h3 class="card-label">DATA TARGET PAJAK</h3>
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-target">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Tahun</th>
							<th>Nominal</th>
							<th>Keterangan</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Tahun</th>
							<th>Nominal</th>
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