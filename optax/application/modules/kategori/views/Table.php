<div class="row">
	<div class="col-12 col-md-5 mb-3 " data-roleable="false" data-role="kategori-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM KATEGORI
				</div>
			</div>
			<form action="javascript:save('form-kategori')" method="post" id="form-kategori" name="form-kategori" autocomplete="off">
				<input type="hidden" id="kategori_barang_id" name="kategori_barang_id">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<input type="hidden" name="kategori_id">
							<!-- <div class="form-group row">
								<label for="kategori_barang_kode" class="col-4 col-form-label">Kode Kategori</label>
								<div class="col-8">
									<input class="form-control" type="text" id="kategori_barang_kode" name="kategori_barang_kode">
								</div>
							</div> -->
							<div class="form-group row">
								<label for="kategori_barang_nama" class="col-4 col-form-label">Nama Kategori</label>
								<div class="col-8">
									<input type="text" class="form-control" type="text" id="kategori_barang_nama" name="kategori_barang_nama">
								</div>
							</div>
							<div class="form-group row">
								<label for="kategori_barang_tipe" class="col-4 col-form-label">
									Kategori Tipe
								</label>
								<div class="col-8" id="parent">
									<select name="kategori_barang_tipe" class="form-control kategori_barang_tipe" id="kategori_barang_tipe" onchange="handleHide()" style="width: 100%;">
										<option value="">-Pilih Kategori Tipe-</option>
										<option value="parent">Induk</option>
										<option value="detail">Detail</option>
									</select>
								</div>
							</div>
							<div class="form-group row" id="child">
								<label for="kategori_barang_parent" class="col-4 col-form-label">Induk</label>
								<div class="col-8">
									<select name="kategori_barang_parent" id="kategori_barang_parent" class="form-control kategori_barang_parent" style="width: 100%;">
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
					<span class="ribbon-inner bg-primary"></span>DATA KATEGORI
				</div>
				<!-- <div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div> -->
				<div class="dropdown dropdown-inline mt-3">
					<button type="button" class="btn btn-primary btn-elevate btn-sm" onclick="onEdit()"><i class="la la-pencil"></i>Edit</button>
					<button type="button" class="btn btn-danger btn-elevate btn-sm" onclick="onDestroy()"><i class="la la-trash"></i> Hapus</button>
					<button type="button" class="btn btn-warning btn-elevate btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i>Muat Ulang</button>
				</div>
			</div>
			<div class="card-body table-responsive">
				<!-- JS Tree -->
				<div id="tree1">
				</div>
				<!-- End JS Tree -->
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript') ?>