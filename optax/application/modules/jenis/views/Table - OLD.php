<div class="row">
	<div class="col-12 col-md-5 mb-3 " data-roleable="false" data-role="jenis-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM JENIS PAJAK</h3>
				</div>
			</div>
			<form action="javascript:save('form-jenis')" method="post" id="form-jenis" name="form-jenis" autocomplete="off">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<input type="hidden" name="jenis_id">
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="jenis_nama">Jenis</label>
								<div class="col-lg-8">
									<input type="text" name="jenis_nama" class="form-control jenis_nama" placeholder="jenis" required minlength="2" maxlength="150">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="jenis_tipe">Tipe</label>
								<div class="col-lg-8">
									<select name="jenis_tipe" class="form-control jenis_tipe" onchange="showInduk()">
										<option value="" disabled> Pilih Tipe</option>
										<option value="parent"> Induk</option>
										<option value="detail"> Detail</option>
									</select>
								</div>
							</div>
							<div class="form-group row induk" style="display:none">
								<label class="col-lg-4 col-form-label text-left" for="jenis_parent">Induk</label>
								<div class="col-lg-8">
									<select name="jenis_parent" id="jenis_parent" class="form-control jenis_parent">
										<option value="" disabled> Pilih Induk</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="jenis_tarif">Tarif</label>
								<div class="col-lg-8">
									<input type="text" name="jenis_tarif" class="form-control jenis_tarif" placeholder="%">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="jenis_keterangan">Keterangan</label>
								<div class="col-lg-8">
									<input type="text" name="jenis_keterangan" class="form-control jenis_keterangan" placeholder="Keterangan" minlength="2" maxlength="150">
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
					<h3 class="card-label">DATA JENIS PAJAK</h3>
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-jenis">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Jenis</th>
							<th>Tarif</th>
							<th>Keterangan</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Jenis</th>
							<th>Tarif</th>
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