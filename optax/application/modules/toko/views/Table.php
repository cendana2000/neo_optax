<div class="row form_data" style="display:none">
	<div class="col-12 col-md-12 mb-3" data-roleable="false" data-role="customer-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DATA AKUN POS</h3>
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button type="button" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Kembali</button>
						<button type="button" class="btn btn-danger btn-sm" id="close-toko-btn"><i class="flaticon2-cancel"></i> Close Toko</button>
					</div>
				</div>
			</div>
			<form action="javascript:save('form-toko')" method="post" id="form-toko" name="form-toko" autocomplete="off">
				<div class="card-body">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home">
								<span class="nav-icon">
									<i class="flaticon2-information"></i>
								</span>
								<span class="nav-text">Informasi</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile">
								<span class="nav-icon">
									<i class="flaticon2-user-outline-symbol"></i>
								</span>
								<span class="nav-text">Pengguna</span>
							</a>
						</li>
					</ul>
					<div class="tab-content mt-5" id="myTabContent">
						<div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
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
									<div class="row">
										<div class="col-xl-12">
											<div class="form-group">
												<label class="text-dark">Kode Usaha</label>
												<input class="form-control" type="text" id="toko_kode" name="toko_kode" autocomplete="off" style="background: ghostwhite;" />
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-4">
									<div class="row">
										<div class="col-xl-12">
											<div class="form-group">
												<label class="font-size-h6 font-weight-bolder text-dark">Logo Tempat Usaha</label>
												<div class="form-control-solid" style="height:200px;">
													<img src="<?php ?>assets/media/noimage.png" class="img-thumbnail w-50" onerror="imgError(this)" id="logo_toko" alt="Logo">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
							<!-- <div class="w-100 d-flex justify-content-end">
								<button type="button" class="btn btn-warning btn-sm m-3 radius-5" onclick="onRefresh()"><i class="flaticon-refresh"></i>Muat Ulang</button>
							</div> -->
							<table class="table table-head-custom table-head-bg table-striped table-checkable table-condensed table-hover" id="table-user">
								<thead>
									<tr>
										<th>No</th>
										<th>Name</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<!-- <div class="separator separator-dashed my-5"></div> -->
				</div>
				<!-- <div class="card-footer">
					<div class="row">
						<div class="col-md-4 text-left">
							<button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>
						</div>
						<div class="col-8 text-right">
							<button type="button" onclick="save()" id="btnSave" class="btn btn-sm btn-success ml-4"><i class="fas fa-save"></i> Simpan</button>
						</div>
					</div>
				</div> -->
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
					<h3 class="card-label">DATA AKUN POS</h3>
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
							<th>Nama Tempat Usaha</th>
							<th>Code Store</th>
							<th>Tanggal Permohonan</th>
							<th>Status Permohonan</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>NPWPD</th>
							<th>Nama Tempat Usaha</th>
							<th>Code Store</th>
							<th>Tanggal Permohonan</th>
							<th>Status Permohonan</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript') ?>