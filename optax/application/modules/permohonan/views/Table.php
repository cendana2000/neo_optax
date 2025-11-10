<div class="row form_data" style="display:none">
	<div class="col-12 col-md-12 mb-3" data-roleable="false" data-role="customer-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
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
								<option value="2"> Disetujui </option>
								<option value="3"> Ditolak Dapat Direvisi </option>
								<option value="4"> Ditolak Tidak Dapat Direvisi </option>
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
<!--begin::Page title-->
<!-- <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
	<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
		Daftar Verifikasi Akun
	</h1>
	<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
		<li class="breadcrumb-item text-muted">
			<a href="#" class="text-muted text-hover-primary">Wajib Pajak </a>
		</li>
		<li class="breadcrumb-item">
			<span class="bullet bg-gray-500 w-5px h-2px"></span>
		</li>
		<li class="breadcrumb-item text-muted">
			Verifikasi Akun </li>
		<li class="breadcrumb-item">
			<span class="bullet bg-gray-500 w-5px h-2px"></span>
		</li>		
	</ul>
</div> -->
<!--end::Page title-->
<!--begin::Content-->
<!-- <div id="kt_app_content" class="app-content  flex-column-fluid "> -->
<!--begin::Content container-->
<!-- <div id="kt_app_content_container" class="app-container  container-xxl "> -->
<!--begin::Products-->
<div class="row table_data">
	<div class="col-12 col-md-12 mb-3">
		<!-- table custom -->
		<!-- <div class="card card-flush">
					<div class="card-header align-items-center py-5 gap-2 gap-md-5">
						<div class="card-title">
							<div class="d-flex align-items-center position-relative my-1">
								<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i> <input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Product" />
							</div>
						</div>
						<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
							<div class="w-100 mw-150px">
								<select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-product-filter="status">
									<option></option>
									<option value="all">All</option>
									<option value="published">Published</option>
									<option value="scheduled">Scheduled</option>
									<option value="inactive">Inactive</option>
								</select>
							</div>
							<button class="btn btn-warning" onclick="onRefresh()">
								Reload
							</button>
						</div>
					</div>
					<div class="card-body pt-0">
						<table class="table align-middle table-row-dashed fs-6 gy-5" id="table-wajibpajak-new">
							<thead>
								<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
									<th class="w-10px pe-2">
										#
									</th>
									<th class="min-w-200px">Objek Pajak</th>
									<th class="text-end min-w-100px">NPWPD</th>
									<th class="text-end min-w-70px">Jenis Usaha</th>
									<th class="text-end min-w-100px">Penanggung Jawab</th>
									<th class="text-end min-w-100px">Status</th>
									<th class="text-end min-w-70px">Actions</th>
								</tr>
							</thead>
							<tbody class="fw-semibold text-gray-600"></tbody>
						</table>
					</div>
				</div> -->
		<!-- end of table custom -->
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DAFTAR VERIFIKASI AKUN</h3>
				</div>
				<!-- <div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-secondary btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div> -->
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
<!-- </div> -->
<!-- </div> -->
<?php load_view('javascript') ?>