<div class="row table_data">
	<div class="col-12 col-md-12 mb-3">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fa fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DAFTAR VERIFIKASI AKUN</h3>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-striped table-checkable table-condensed table-hover" id="table-wajibpajak">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Nama</th>
							<th>NPWPD</th>
							<th>Jenis</th>
							<th>Penanggung Jawab</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody class="fw-semibold text-gray-800"></tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Nama</th>
							<th>NPWPD</th>
							<th>Jenis</th>
							<th>Penanggung Jawab</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row form_data" style="display:none">
	<div class="col-12 col-md-12 mb-3" data-roleable="false" data-role="customer-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fa fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DETAIL VERIFIKASI AKUN</h3>
				</div>
			</div>
			<form action="javascript:save('form-wajibpajak')" method="post" id="form-wajibpajak" name="form-wajibpajak" autocomplete="off">
				<div class="card-body ">
					<div class="row">
						<input class="form-control" type="hidden" name="wajibpajak_id" autocomplete="off" />
						<div class="col-xl-8">
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">NPWPD</label>
										<input class="form-control" type="text" name="wajibpajak_npwpd" autocomplete="off" style="background: ghostwhite;" />
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">Sektor Usaha</label>
										<input class="form-control" type="text" name="wajibpajak_usaha_nama" id="wajibpajak_usaha_nama" autocomplete="off" readonly="" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label class="text-dark">Nama Perusahaan</label>
										<input class="form-control" type="text" name="wajibpajak_nama" autocomplete="off" readonly="" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label class="text-dark">Alamat</label>
										<input class="form-control" type="text" name="wajibpajak_alamat" autocomplete="off" readonly="" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label class="text-dark">Nama Penangung Jawab</label>
										<input class="form-control" type="text" name="wajibpajak_nama_penanggungjawab" autocomplete="off" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">No Telp Perusahaan</label>
										<input class="form-control" type="text" name="wajibpajak_telp" autocomplete="off" style="background: ghostwhite;" />
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">Email Perusahaan</label>
										<input class="form-control" type="text" name="wajibpajak_email" autocomplete="off" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-4">
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label class="font-size-h6 font-weight-bolder text-dark">Berkas NPWPD</label>
										<div class="image-input image-input-outline img-fluid w-100" id="wajibpajak_berkas_npwp" style="height:200px;background-size:cover;background-image: url(assets/media/users/blank.png)">
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="separator separator-dashed my-5"></div>
					<!--begin::Form Group-->
					<div class="row">
						<div class="col-lg-9 col-xl-6">
							<h5 class="font-weight-bold mb-6">Akun Verifikasi:</h5>
						</div>
					</div>
					<!--begin::Form Group-->
					<div class="form-group row mb-0">
						<label class="col-xl-2 col-lg-2 col-form-label">Status Verifikasi</label>
						<div class="col-lg-9 col-xl-6">
							<select class="form-control" name="wajibpajak_status" id="wajibpajak_status">
								<option value=""> Pilih </option>
								<option value="2"> Aktif </option>
								<option value="0"> Tidak Aktif</option>
							</select>
							<p class="form-text text-muted pt-2">Silahkan pilih status verifikasi untuk mengaktifkan/menonaktifkan user(wajib pajak) akses ke portal wajib pajak.
							</p>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4 text-left">
							<button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>
						</div>
						<div class="col-8 text-right">
							<button type="button" onclick="save()" id="btnSave" class="btn btn-sm btn-success ml-4"><i class="fas fa-save"></i> Simpan</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- </div> -->
<!-- </div> -->
<?php load_view('javascript') ?>