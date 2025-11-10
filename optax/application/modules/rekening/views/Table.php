<div class="row">
	<div class="col-12 col-md-5 mb-3 " data-roleable="false" data-role="rekening-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM REKENING
				</div>
			</div>
			<form action="javascript:save('form-rekening')" method="post" id="form-rekening" name="form-rekening" autocomplete="off">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<input type="hidden" name="rekening_id">
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="rekening_nama">Nama</label>
								<div class="col-lg-8">
									<input type="text" name="rekening_nama" class="form-control rekening_nama" placeholder="Nama" required minlength="2" maxlength="150">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="rekening_no">No.Rekening</label>
								<div class="col-lg-8">
									<input type="text" name="rekening_no" class="form-control rekening_no" placeholder="Nomor Rekening" required minlength="5" maxlength="150">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="rekening_nama">Bank</label>
								<div class="col-lg-8">
									<select name="rekening_bank" required class="form-control rekening_bank" style="width: 100%;">
										<option value="">-- Pilih Bank --</option>
										<option value="Mandiri">Mandiri</option>
										<option value="BCA">BCA</option>
										<option value="BNI">BNI</option>
										<option value="BRI">BRI</option>
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
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>DATA REKENING
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-rekening">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Nama</th>
							<th>No.Rekening</th>
							<th>Bank</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Nama</th>
							<th>No.Rekening</th>
							<th>Bank</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript') ?>