<div class="row form_data" style="display: none">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM PRODUK
				</div>
			</div>
			<form class="kt-form" action="javascript:save('form-barang')" name="form-barang" id="form-barang">
				<div class="card-body">
					<input type="hidden" id="barang_id" name="barang_id">
					<div class="kt-portlet__body">
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="form-group row">
									<label for="barang_kode" class="col-4 col-form-label">Kode Produk</label>
									<div class="col-8 col-md-6">
										<input class="form-control" readonly style="background-color: #eaeaea;" type="text" id="barang_kode" name="barang_kode" placeholder=".###">
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group row">
									<label for="barang_nama" class="col-4 col-form-label">Nama Produk</label>
									<div class="col-8">
										<input class="form-control" type="text" id="barang_nama" name="barang_nama" placeholder="Nama Produk" oninvalid="fieldInvalid(this)" onchange="fieldChange(this)">
										<div class="invalid-feedback">Bidang ini wajib disi</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="form-group row">
									<label for="barang_kategori_barang" class="col-4 col-form-label">Kategori Produk</label>
									<div class="col-8 col-md-6" id="kategori_div">
										<select class="form-control" name="barang_kategori_barang" id="barang_kategori_barang" style="width: 100%" oninvalid="fieldInvalid(this)" onchange="fieldChange(this)"></select>
										<div class="invalid-feedback">Bidang ini wajib disi</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group row">
									<label for="barang_stok" class="col-4 col-form-label ">Stok Min</label>
									<div class="col-8">
										<div class="row">
											<div class="col-6">
												<input class="form-control" placeholder="min" type="text" id="barang_stok" name="barang_stok_min">
											</div>
											<div class="col-6">
												<input class="form-control" style="background-color: #eaeaea;" placeholder="satuan" readonly type="text" id="barang_stok_satuan" name="barang_stok_satuan">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="form-group row">
									<label for="barang_jenis_barang" class="col-4 col-form-label">Jenis Produk</label>
									<div class="col-8 col-md-6" id=jenisbayar_div>
										<select onchange="onJenis()" class="form-control" name="barang_jenis_barang" id="barang_jenis_barang" style="width: 100%"></select>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group row">
									<label for="barang_harga_pokok" class="col-4 col-form-label">Harga Pokok</label>
									<div class="col-8">
										<input type="text" class="form-control tnumber" name="barang_harga_pokok" id="barang_harga_pokok">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="form-group row">
									<label class="col-4 col-form-label">Thumbnail</label>
									<div class="col-8 col-md-6">
										<div class="d-flex flex-row">
											<div class="custom-file mr-2">
												<input type="file" class="custom-file-input" accept=".png, .jpg, .jpeg" id="thumbnail" name="thumbnail" onchange="onChangeThumbnail(this)">
												<label id="title-thumbnail" class="custom-file-label" for="thumbnail">Choose file</label>
											</div>
											<div class="btn btn-light border" style="color: #3F4254;" data-toggle="modal" data-target="#modal-preview">Preview</div>
										</div>
										<span class="font-size-sm text-muted">Max 2 MB, Allowed file types: png, jpg, jpeg.</span>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group row">
									<label for="barang_barcode" class="col-4 col-form-label">Barcode</label>
									<div class="col-8">
										<div class="input-group">
											<input type="text" class="form-control" name="barang_barcode" id="barang_barcode" onchange="getBarcode(false, false, this)">
											<div class="input-group-append">
												<button id="btn_simpan" class="btn btn-success" type="button" onclick="saveBarcode('barang_barcode')"><i class="fa fa-save icon-sm"></i> Simpan</button>
												<button id="btn_daftar" class="btn btn-primary" type="button" onclick="tampilModal()"><i class="fa fa-check icon-sm"></i> Terdaftar</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-6"></div>
							<div class="col-sm-12 col-md-6 d-flex flex-row-reverse">
								<!-- <button onclick="excludePajak()" class="btn btn-sm btn-light">
									<i class="fas fa-money-bill"></i> Potong Pajak
								</button> -->
							</div>
						</div>
						<div class="table-responsive" id="tableDetailSatuan">
							<table class="table table-bordered table-hover" id="table-sales">
								<thead style="background: #cae3f9;">
									<tr>
										<th style="width: 20%!important">Satuan</th>
										<th style="width: 10%">Konversi</th>
										<th style="width: 20%">Harga Beli</th>
										<th style="width: 15%">Keuntungan(%)</th>
										<th style="width: 20%">Harga Jual</th>
										<th style="width: 10%">Disc(%)</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<tr class="detail_1">
										<td scope="row" id="satuan1_div">
											<input type="hidden" class="form-control" name="barang_satuan_id[1]" id="barang_satuan_id_1">
											<input type="hidden" class="form-control" name="barang_satuan_kode[1]" id="barang_satuan_kode_1">
											<select class="form-control" name="barang_satuan_satuan_id[1]" id="barang_satuan_satuan_id_1" style="width: 100%" onchange="showIsi('1')"></select>
										</td>
										<td><input class="form-control tnumber" type="text" name="barang_satuan_konversi[1]" id="barang_satuan_konversi_1" readonly="" value="1"></td>
										<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_beli[1]" id="barang_satuan_harga_beli_1" value="0" onkeyup="setUntung('1')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" onkeyup="setUntung('1')" name="barang_satuan_keuntungan[1]" value="0" id="barang_satuan_keuntungan_1">
											</div>
										</td>
										<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_jual[1]" id="barang_satuan_harga_jual_1" onkeyup="setUntungRp('1')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" name="barang_satuan_disc[1]" value="0" id="barang_satuan_disc_1">
											</div>
										</td>
										<td>
											<a href="javascript:;" id="btn_reset1" data-id="1" class="btn btn-light-warning btn-sm" onclick="remRow(1)" title="Reset">
												<span class="la la-rotate-right"></span> Reset</a>
										</td>
									</tr>
									<tr class="detail_2">
										<td scope="row">
											<input type="hidden" class="form-control" name="barang_satuan_id[2]" id="barang_satuan_id_2">
											<input type="hidden" class="form-control" name="barang_satuan_kode[2]" id="barang_satuan_kode_2">
											<select class="form-control" name="barang_satuan_satuan_id[2]" id="barang_satuan_satuan_id_2" style="width: 100%" onchange="showIsi('2')"></select>
										</td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control tnumber" type="text" name="barang_satuan_konversi[2]" id="barang_satuan_konversi_2">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span class="lbl_barang_satuan"></span>
												</span>
											</div>
										</td>
										<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_beli[2]" id="barang_satuan_harga_beli_2" onkeyup="setUntung('2')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" onkeyup="setUntung('2')" name="barang_satuan_keuntungan[2]" id="barang_satuan_keuntungan_2">
											</div>
										</td>
										<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_jual[2]" id="barang_satuan_harga_jual_2" onkeyup="setUntungRp('2')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" value="0" name="barang_satuan_disc[2]" id="barang_satuan_disc_2">
											</div>
										</td>
										<td>
											<a href="javascript:;" id="btn_reset2" data-id="2" class="btn btn-light-warning btn-sm" onclick="remRow('2')" title="Reset">
												<span class="la la-rotate-right"></span> Reset</a>
										</td>
									</tr>
									<tr class="detail_3">
										<td scope="row">
											<input type="hidden" class="form-control" name="barang_satuan_id[3]" id="barang_satuan_id_3">
											<input type="hidden" class="form-control" name="barang_satuan_kode[3]" id="barang_satuan_kode_3">
											<select class="form-control" name="barang_satuan_satuan_id[3]" id="barang_satuan_satuan_id_3" style="width: 100%" onchange="showIsi('3')"></select>
										</td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control tnumber" type="text" name="barang_satuan_konversi[3]" id="barang_satuan_konversi_3">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span class="lbl_barang_satuan"></span>
												</span>
											</div>
										</td>
										<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_beli[3]" id="barang_satuan_harga_beli_3" onkeyup="setUntung('3')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" onkeyup="setUntung('3')" name="barang_satuan_keuntungan[3]" id="barang_satuan_keuntungan_3">
											</div>
										</td>
										<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_jual[3]" id="barang_satuan_harga_jual_3" onkeyup="setUntungRp('3')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" value="0" name="barang_satuan_disc[3]" id="barang_satuan_disc_3">
											</div>
										</td>
										<td>
											<a href="javascript:;" data-id="3" id="btn_reset3" class="btn btn-light-warning btn-sm" onclick="remRow('3')" title="Reset">
												<span class="la la-rotate-right"></span> Reset</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-4 text-left">
							<button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>
						</div>
						<div class="col-8 text-right">
							<!-- <button type="reset" onclick="onClear()" class="btn btn-sm btn-danger"><i class="fas fa-sync-alt"></i> Reset</button> -->
							<button id="btn_form" type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Simpan</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-preview" tabindex="-1" aria-labelledby="modal-previewLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-previewLabel">Preview Thumbnail</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center">
				<img src="assets/media/noimage.png" id="preview-image" class="img-fluid" alt="thumbnail preview" onerror="imgError(this);">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="m_createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="textModalLabel">Detail</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body form_data">
				<!--begin::Form-->
				<form class="m-form m-form--fit m-form--label-align-right" name="kode_form" id="kode_form" enctype="multipart/form-data" method="post">
					<div class="form-group row align-items-center py-2" style="background: #a7d1de;">
						<label for="barang_barcode_kode" class="col-2 col-form-label">Barcode Barang</label>
						<div class="col-8">
							<input class="form-control" type="text" id="barang_barcode_kode" name="barang_barcode_kode" placeholder="Scan Barcode Disini">
						</div>
						<div class="col-2">
							<button type="button" onclick="saveBarcode('barang_barcode_kode')" id="btn_barcode" class="btn btn-success"><i class="la la-check"></i> Simpan</button>
						</div>
					</div>
					<table class="table table-bordered table-checkable table-condensed" id="table-barcode">
						<thead>
							<tr>
								<th style="width:5%;">No.</th>
								<th>Tanggal</th>
								<th>Kode</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr>
								<th style="width:5%;">No.</th>
								<th>Tanggal</th>
								<th>Kode</th>
								<th>Aksi</th>
							</tr>
						</tfoot>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>