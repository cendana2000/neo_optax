<div class="row form_data" style="display:none">
	<div class="col-12 col-md-12 mb-3" data-roleable="false" data-role="customer-Create" data-action="hide">
		<div class="card card-custom">
			<!--
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>DETAIL PERMOHONAN TEMPAT USAHA
				</div>
			</div>
			-->
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DETAIL VERIFIKASI AKUN POS</h3>
				</div>
			</div>
			<form action="javascript:save('form-toko')" method="post" id="form-toko" name="form-toko" autocomplete="off">
				<div class="card-body ">
					<div class="row">
						<input class="form-control" type="hidden" name="toko_id" autocomplete="off" />
						<div class="col-xl-8">
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label class="text-dark">Nama Perusahaan</label>
										<input class="form-control" type="text" id="wajibpajak_nama" name="wajibpajak_nama" autocomplete="off" readonly="" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">NPWPD</label>
										<input class="form-control" type="text" id="wajibpajak_npwpd" name="wajibpajak_npwpd" autocomplete="off" style="background: ghostwhite;" />
									</div>
								</div>
								<div class="col-xl-6" style="display: none;">
									<div class="form-group">
										<label class="text-dark">Sektor Usaha</label>
										<input class="form-control" type="text" id="wajibpajak_sektor_nama" name="wajibpajak_sektor_nama" id="wajibpajak_sektor_nama" autocomplete="off" readonly="" style="background: ghostwhite;" />
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">Sektor Usaha</label>
										<input class="form-control" type="text" id="jenis_nama" name="jenis_nama" id="jenis_nama" autocomplete="off" readonly="" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label class="text-dark">Alamat</label>
										<input class="form-control" type="text" id="wajibpajak_alamat" name="wajibpajak_alamat" autocomplete="off" readonly="" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label class="text-dark">Nama Penangung Jawab</label>
										<input class="form-control" type="text" id="wajibpajak_nama_penanggungjawab" name="wajibpajak_nama_penanggungjawab" autocomplete="off" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">No Telp Perusahaan</label>
										<input class="form-control" type="text" id="wajibpajak_telp" name="wajibpajak_telp" autocomplete="off" style="background: ghostwhite;" />
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">Email Perusahaan</label>
										<input class="form-control" type="text" id="wajibpajak_email" name="wajibpajak_email" autocomplete="off" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-4">
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label class="font-size-h6 font-weight-bolder text-dark">Logo Toko</label>
										<div class="form-control-solid" style="height:200px">
											<img src="<?php ?>assets/media/noimage.png" class="img-thumbnail w-50" onerror="imgError(this)" id="logo_toko" alt="Logo">
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
							<h5 class="font-weight-bold mb-6">Verifikasi Tempat Usaha:</h5>
						</div>
					</div>
					<!--begin::Form Group-->
					<div class="form-group row mb-0">
						<label class="col-xl-2 col-lg-2 col-form-label">Status Verifikasi</label>
						<div class="col-lg-9 col-xl-6">
							<select class="form-control select2" name="toko_status" id="toko_status" placeholder="pilih">
								<option value="2"> Disetujui </option>
								<option value="3"> Tidak Disetujui </option>
							</select>
							<!-- <button type="button" class="btn btn-light-primary font-weight-bold btn-sm">Setup login verification</button> -->
							<p class="form-text text-muted pt-2">Silahkan pilih status verifikasi untuk mengizinkan/menolak user(wajib pajak) akses ke aplikasi eToko.
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
<div class="row table_data">
	<div class="col-12 col-md-12 mb-3">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DAFTAR VERIFIKASI AKUN POS</h3>
				</div>
				<!-- <div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div> -->
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-striped table-checkable table-condensed table-hover" id="table-toko">
					<thead>
						<tr>
							<th>No.</th>
							<th>NPWPD</th>
							<th>Nama</th>
							<th>Tanggal Permohonan</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>NPWPD</th>
							<th>Nama</th>
							<th>Tanggal Permohonan</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript') ?>