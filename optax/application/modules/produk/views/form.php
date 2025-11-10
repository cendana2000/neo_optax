<div class="kt-portlet kt-portlet--mobile form_data" style="display: none">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				FORM PRODUK
			</h3>
		</div>
	</div>
	<form class="kt-form" action="javascript:save('form-barang')" name="form-barang" id="form-barang">
		<input type="hidden" id="barang_id" name="barang_id">
		<div class="kt-portlet__body">
			<div class="form-group row">
				<label for="barang_kode" class="col-2 col-form-label">Kode Produk</label>
				<div class="col-3">
					<input class="form-control" type="text" id="barang_kode" name="barang_kode" placeholder=".###">
				</div>
				<div class="col-1"></div>
				<label for="barang_supplier_id" class="col-2 col-form-label">Supplier</label>
				<div class="col-4">
					<select class="form-control" id="barang_supplier_id" name="barang_supplier_id" style="width: 100%"></select>
				</div>
			</div>
			<div class="form-group row">
				<label for="barang_kategori_barang" class="col-2 col-form-label">Kategori Produk</label>
				<div class="col-3">
					<select class="form-control" name="barang_kategori_barang" id="barang_kategori_barang" style="width: 100%"></select>
				</div>
				<div class="col-1"></div>
				<label for="barang_stok" class="col-2 col-form-label">Stok</label>
				<div class="col-4">
					<div class="row">
						<div class="col-6">
							<input class="form-control" type="text" id="barang_stok" name="barang_stok_min">
						</div>
						<div class="col-6">
							<input class="form-control" type="text" id="barang_stok" name="barang_stok">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label for="barang_jenis_barang" class="col-2 col-form-label">Jenis Produk</label>
				<div class="col-3">
					<select class="form-control" name="barang_jenis_barang" id="barang_jenis_barang" style="width: 100%"></select>
				</div>
				<div class="col-1"></div>
				<label for="barang_harga_pokok" class="col-2 col-form-label">Harga Pokok</label>
				<div class="col-4">
					<input type="text" class="form-control tnumber" name="barang_harga_pokok" id="barang_harga_pokok">
				</div>
			</div>
			<div class="form-group row">
				<label for="barang_nama" class="col-2 col-form-label">Nama Produk</label>
				<div class="col-3">
					<input class="form-control" type="text" id="barang_nama" name="barang_nama" placeholder=".###">
				</div>

				<div class="col-1"></div>

				<label for="barang_barcode" class="col-2 col-form-label">Barcode Produk</label>
				<div class="col-4">
					<div class="input-group">
						<input class="form-control" type="text" id="barang_barcode" name="barang_barcode" onchange="getBarcode(false,false,this)">
						<div class="input-group-append">
							<button class="btn btn-success" id="btn_simpan" type="button" onclick="saveBarcode('barang_barcode')"><i class="la la-save" style="color: #fff"></i> Simpan</button>
							<button class="btn btn-primary" id="btn_daftar" type="button" onclick="tampilModal()"><i class="la la-check" style="color: #fff"></i> Terdaftar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="table-sales">
					<thead style="background: #cae3f9;">
						<tr>
							<th style="width: 20%!important">Satuan</th>
							<th style="width: 10%">Konversi</th>
							<th style="width: 20%">Harga Beli</th>
							<th style="width: 10%">Keuntungan %</th>
							<th style="width: 20%">Harga Jual</th>
							<th style="width: 10%">Disc %</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<tr class="detail_1">
							<td scope="row">
								<input type="hidden" class="form-control" name="barang_satuan_id[1]" id="barang_satuan_id_1">
								<input type="hidden" class="form-control" name="barang_satuan_kode[1]" id="barang_satuan_kode_1">
								<select class="form-control" name="barang_satuan_satuan_id[1]" id="barang_satuan_satuan_id_1" style="width: 100%" onchange="showIsi('1')"></select>
							</td>
							<td><input class="form-control tnumber" type="text" name="barang_satuan_konversi[1]" id="barang_satuan_konversi_1" readonly="" value="1"></td>
							<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_beli[1]" id="barang_satuan_harga_beli_1" onkeyup="setUntung('1')"></td>
							<td>
								<div class="kt-input-icon kt-input-icon--right">
									<input class="form-control disc" type="text" onkeyup="setUntung('1')" name="barang_satuan_keuntungan[1]" id="barang_satuan_keuntungan_1">
									<!-- <span class="kt-input-icon__icon kt-input-icon__icon--right">
										<span>%</span>
									</span> -->
								</div>
							</td>
							<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_jual[1]" id="barang_satuan_harga_jual_1" onkeyup="setUntungRp('1')"></td>
							<td>
								<div class="kt-input-icon kt-input-icon--right">
									<input class="form-control disc" type="text" name="barang_satuan_disc[1]" id="barang_satuan_disc_1">
									<!-- <span class="kt-input-icon__icon kt-input-icon__icon--right">
										<span>%</span>
									</span> -->
								</div>
							</td>
							<td>
								<a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow('1')" title="Reset">
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
									<!-- <span class="kt-input-icon__icon kt-input-icon__icon--right">
										<span>%</span>
									</span> -->
								</div>
							</td>
							<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_jual[2]" id="barang_satuan_harga_jual_2" onkeyup="setUntungRp('2')"></td>
							<td>
								<div class="kt-input-icon kt-input-icon--right">
									<input class="form-control disc" type="text" name="barang_satuan_disc[2]" id="barang_satuan_disc_2">
									<!-- <span class="kt-input-icon__icon kt-input-icon__icon--right">
										<span>%</span>
									</span> -->
								</div>
							</td>
							<td>
								<a href="javascript:;" data-id="2" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow('2')" title="Reset">
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
									<!-- <span class="kt-input-icon__icon kt-input-icon__icon--right">
										<span>%</span>
									</span> -->
								</div>
							</td>
							<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_jual[3]" id="barang_satuan_harga_jual_3" onkeyup="setUntungRp('3')"></td>
							<td>
								<div class="kt-input-icon kt-input-icon--right">
									<input class="form-control disc" type="text" name="barang_satuan_disc[3]" id="barang_satuan_disc_3">
									<!-- <span class="kt-input-icon__icon kt-input-icon__icon--right">
										<span>%</span>
									</span> -->
								</div>
							</td>
							<td>
								<a href="javascript:;" data-id="3" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow('3')" title="Reset">
									<span class="la la-rotate-right"></span> Reset</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<div class="row">
					<div class="col-10">
						<button type="submit" id="btn_form" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Simpan</button>
						<button type="reset" class="btn btn-secondary" onclick="onBack()"><i class="flaticon2-cancel-music"></i> Batal</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="modal fade" id="m_createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="textModalLabel">Detail Barcode</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body form_data">
				<!--begin::Form-->
				<form class="m-form m-form--fit m-form--label-align-right" name="kode_form" id="kode_form" enctype="multipart/form-data" method="post">
					<div class="form-group row" style="background: #a7d1de;padding: 15px 0;">
						<label for="barang_barcode_kode" class="col-2 col-form-label">Barcode Produk</label>
						<div class="col-6">
							<input class="form-control" type="text" id="barang_barcode_kode" name="barang_barcode_kode" placeholder="Scan Barcode Disini">
						</div>
						<div class="col-2">
							<button type="button" onclick="saveBarcode('barang_barcode_kode')" id="btn_barcode" class="btn btn-success"><i class="la la-check"></i> Simpan</button>
						</div>
					</div>
					<table id="table-barcode" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th width="5px">No</th>
								<th width="30%">Tanggal</th>
								<th>Kode</th>
								<th width="10%">Aksi</th>
							</tr>
						</thead>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>