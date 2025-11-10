<div class="row">
	<div class="col-12 col-md-5 mb-3 " data-roleable="false" data-role="kasir-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM PEGAWAI
				</div>
			</div>
			<form action="javascript:save('form-kasir')" method="post" id="form-kasir" name="form-kasir" autocomplete="off">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<input type="hidden" name="kasir_id">
							<!-- <div class="form-group">
								<div>
									<label class="text-left pr-0" for="kasir_avatar">Photo</label>
								</div>
								<div class="image-input image-input-empty image-input-outline" id="kasir_avatar" style="background-image: url(./assets/media/noimage.png)">
									<div class="image-input-wrapper kasir_avatar"></div>
									<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
										<i class="fa fa-pen icon-sm text-muted"></i>
										<input type="file" name="kasir_avatar" accept=".png, .jpg, .jpeg" />
										<input type="hidden" name="profile_avatar_remove" class="profile_avatar_remove" />
									</label>

									<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
										<i class="ki ki-bold-close icon-xs text-muted"></i>
									</span>
									<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
										<i class="ki ki-bold-close icon-xs text-muted"></i>
									</span>
								</div>
							</div> -->
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="kasir_nama">Nama</label>
								<div class="col-lg-8">
									<input type="text" name="kasir_nama" class="form-control kasir_nama" placeholder="Nama" required minlength="2" maxlength="150">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="kasir_kode">Kode</label>
								<div class="col-lg-8">
									<input type="text" name="kasir_kode" class="form-control kasir_kode" placeholder="Kode" required minlength="2" maxlength="150">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="kasir_ip">IP</label>
								<div class="col-lg-8">
									<input type="text" name="kasir_ip" class="form-control kasir_ip" placeholder="IP Kasir" required minlength="2" maxlength="150">
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
					<span class="ribbon-inner bg-primary"></span>DATA PEGAWAI
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-kasir">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Nama</th>
							<th>Kode</th>
							<th>IP</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Nama</th>
							<th>Kode</th>
							<th>IP</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript') ?>