<div class="row form_data" style="display:none">
	<div class="col-12 col-md-12 mb-3" data-roleable="false" data-role="customer-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DETAIL AKUN WAJIB PAJAK</h3>
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
							<h5 class="font-weight-bold mb-6">Akun:</h5>
						</div>
					</div>
					<!--begin::Form Group-->
					<div class="form-group row mb-0">
						<label class="col-xl-2 col-lg-2 col-form-label">Status Akun</label>
						<div class="col-lg-9 col-xl-6">
							<select class="form-control" name="wajibpajak_status" id="wajibpajak_status">
								<option value=""> Pilih </option>
								<option value="2"> Aktif </option>
								<option value="0"> Tidak Aktif </option>
								<option value="5"> Dummy </option>
							</select>
							<!-- <button type="button" class="btn btn-light-primary font-weight-bold btn-sm">Setup login verification</button> -->
							<p class="form-text text-muted pt-2">Silahkan pilih status verifikasi untuk mengizinkan/menolak user(wajib pajak) akses ke portal wajib pajak.
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
					<h3 class="card-label">DATA WAJIB PAJAK</h3>
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-info btn-sm" onclick="onSyncOAPI()"><i class="flaticon-refresh"></i> Sinkronkan OAPI</button>
						<!-- <button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button> -->
					</div>
				</div>
			</div>
			<div class="p-5">
				<ul class="nav nav-pills nav-fill border border-primary sort-by-status rounded">
					<li class="nav-item">
						<a class="nav-link active" href="javascript:void(0)" data="all">Semua</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="javascript:void(0)" data="1">Permohonan</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="javascript:void(0)" data="2">Disetujui</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="javascript:void(0)" data="3">Ditolak</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="javascript:void(0)" data="0">Tidak Aktif</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="javascript:void(0)" data="5">Dummy</a>
					</li>
				</ul>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-striped table-checkable table-condensed table-hover" id="table-wajibpajak">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>NPWPD</th>
							<th>Kode Toko</th>
							<th>Nama</th>
							<th>Jenis</th>
							<th>Penanggung Jawab</th>
							<th>Tanggal Pendaftaran</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>NPWPD</th>
							<th>Kode Toko</th>
							<th>Nama</th>
							<th>Jenis</th>
							<th>Penanggung Jawab</th>
							<th>Tanggal Pendaftaran</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="row form_oapi" style="display:none">
	<div class="col-12 col-md-12 mb-3" data-roleable="false" data-role="customer-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">AKTIVASI WAJIBPAJAK OAPI</h3>
				</div>
			</div>
			<form action="" method="post" id="form-wajibpajak" name="form-wajibpajak-oapi" autocomplete="off">
				<div class="card-body ">
					<div class="row">
						<input class="form-control" type="hidden" name="wajibpajak_id" id="wajibpajak_id" autocomplete="off" />
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
										<div class="form-control form-control-solid" style="height:200px">

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
							<h5 class="font-weight-bold mb-6">Akun:</h5>
						</div>
					</div>
					<!--begin::Form Group-->
					<div class="form-group row mb-0">
						<label class="col-xl-2 col-lg-2 col-form-label" for="wajibpajak_preset">Pilih Preset</label>
						<div class="col-lg-9 col-xl-6">
							<select class="form-control" name="wajibpajak_preset" id="wajibpajak_preset"></select>
							<!-- <button type="button" class="btn btn-light-primary font-weight-bold btn-sm">Setup login verification</button> -->
							<p class="form-text text-muted pt-2">Silahkan pilih preset API yang telah terdaftar.
							</p>
						</div>
					</div>
					<div class="form-group row mb-0">
						<label class="col-xl-2 col-lg-2 col-form-label" for="wajibpajak_schedule_before">Jadwal waktu sinkron setelah</label>
						<div class="col-lg-9 col-xl-6">
							<div class="input-group">
								<input type="number" class="form-control" name="wajibpajak_schedule_before" id="wajibpajak_schedule_before" />
								<div class="input-group-append">
									<span class="input-group-text" id="basic-addon2">Hari</span>
								</div>
							</div>
							<span class="form-text text-muted pt-2">Masukkan angka setelah hari pengambilan data</span>
						</div>
					</div>
					<div class="form-group row mb-0">
						<label class="col-xl-2 col-lg-2 col-form-label" for="wajibpajak_endpoint">Masukan Endpoint</label>
						<div class="col-lg-9 col-xl-6">
							<input type="text" class="form-control" id="wajibpajak_endpoint">
							<p class="form-text text-muted pt-2">Silahkan pilih masukan endpoint API untuk proses integrasi.</p>
							<p class="text-dark pt-0">
								Catatan:<br />
								1. Timpa dan pasangkan parameter tanggal dengan: {{startdate}}<br />
								2. Opsional jika terdapat tanggal akhir tempel: {{enddate}}
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
							<button type="button" onclick="saveOAPI()" id="btnSave" class="btn btn-sm btn-success ml-4"><i class="fas fa-save"></i> Simpan</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<?php load_view('javascript') ?>