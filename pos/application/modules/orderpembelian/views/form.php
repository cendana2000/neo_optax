<div class="row form_data" style="display: none">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM ORDER PEMBELIAN BARANG
				</div>
			</div>
			<form class="kt-form" action="javascript:save('form-orderpembelian')" name="form-orderpembelian" id="form-orderpembelian">
				<div class="card-body">
					<input type="hidden" id="order_id" name="order_id">

					<div class="kt-prtlet__body">
						<div class="form-group row">
							<label for="order_no_transaksi" class="col-2 col-form-label">Nomor Transaksi</label>
							<div class="col-3">
								<input class="form-control" type="text" id="order_no_transaksi" name="order_no_transaksi">
							</div>
							<div class="col-1"></div>
							<label for="order_tanggal_transaksi" class="col-2 col-form-label">Tanggal Transaksi</label>
							<div class="col-4">
								<input class="form-control" type="date" id="order_tanggal_transaksi" name="order_tanggal_transaksi" value="<?php echo date('Y-m-d') ?>">
							</div>
						</div>
						<div class="form-group row">
							<label for="order_supplier_id" class="col-2 col-form-label">Supplier</label>
							<div class="col-3">
								<select class="form-control" name="order_supplier_id" id="order_supplier_id" style="width: 100%" onchange="getSupplier()"></select>
							</div>
							<div class="col-1"></div>
							<label for="order_jenis_pembayaran" class="col-2 col-form-label">Jenis Pembayaran</label>
							<div class="col-4">
								<select class="form-control" onchange="jenisBayar()" name="order_jenis_pembayaran" id="order_jenis_pembayaran" style="width: 100%">
									<option value="">-Pilih Jenis Pembayaran-</option>
									<option value="Kredit">Kredit</option>
									<option value="Tunai">Tunai</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="order_no_faktur" class="col-2 col-form-label">No Faktur</label>
							<div class="col-3">
								<input type="date" class="form-control" name="order_no_faktur" id="order_no_faktur" value="<?php echo date('Y-m-d') ?>">
							</div>
							<div class="col-1"></div>
							<label for="order_jatuh_tempo" id="label_jatuh_tempo" class="col-2 col-form-label">Jatuh Tempo Pembayaran</label>
							<div class="col-4">
								<input type="date" class="form-control" name="order_jatuh_tempo" id="order_jatuh_tempo" value="<?php echo date('Y-m-d') ?>">
							</div>
						</div>
						<div class="form-group row" id="btnBarang">
							<div class="col-4">
								<button type="button" class="btn btn-primary" onclick="addBarang()"><i class="flaticon-add"></i> Tambah Barang</button>
								<button type="button" class="btn btn-success" onclick="newBarang()"><i class="flaticon-add"></i> Barang Baru</button>
							</div>
						</div>
						<div class="form-group row" id="btnPembayaran">
							<div class="col-4">
								<button type="button" class="btn btn-primary" onclick="addPembayaran()"><i class="flaticon-add"></i> Tambah Pembayaran</button>
							</div>
						</div>
						zzz
						<div class="table-responsive mt-3 py-5 px-5" id="btnTunai" style="background: #cae3f9;">
							<button onclick="event.preventDefault();pilihan('listProduk')" class="btn btn-sm btn-default">List Produk</button>
							<button onclick="event.preventDefault();pilihan('pembayaran')" class="btn ml-3 btn-sm btn-default">Pembayaran</button>
						</div>

						<!-- Table Barang -->
						<div class="table-responsive mt-3">
							<table class="table table-bordered table-hover" id="table-detail_barang">
								<thead style="background: #cae3f9;">
									<tr>
										<th style="width: 30%!important">Barang</th>
										<th style="width: 15%">Satuan</th>
										<th style="width: 20%">Harga</th>
										<th style="width: 10%">Qty</th>
										<th style="width: 20%">Jumlah</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody id="tbody-barang">
									<tr class="barang_1">
										<td scope="row">
											<input type="text" class="form-control" name="order_detail_id[]" id="order_detail_id_1">
											<select class="form-control barang_id" name="order_detail_barang_id[]" id="order_detail_barang_id_1" data-id="1" style="width: 100%;white-space: nowrap" onchange="setSatuan('1')"></select>
										</td>
										<td><select class="form-control" name="order_detail_satuan[]" id="order_detail_satuan_1" style="width: 100%" onchange="getHarga('1')"></select></td>
										<td>
											<input class="form-control number" type="text" name="order_detail_harga[]" id="order_detail_harga_1" onkeyup="countRow('1')">
											<input class="form-control number" type="hidden" name="order_detail_harga_barang[]" id="order_detail_harga_barang_1">
										</td>
										<td>
											<input class="form-control number qty" type="text" name="order_detail_qty[]" id="order_detail_qty_1" onkeyup="countRow('1')">
											<input class="form-control number" type="hidden" name="order_detail_qty_barang[]" id="order_detail_qty_barang_1">
										</td>
										<td><input class="form-control number jumlah" type="text" name="order_detail_jumlah[]" id="order_detail_jumlah_1" readonly=""></td>
										<td><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRowBarang(this)" title="Hapus">
												<span class="la la-trash"></span> Hapus</a></td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2" class="no-border text-right">Total Qty</td>
										<td class="no-border"><input class="form-control number" type="text" id="order_total_qty" name="order_total_qty"></td>
										<td class="no-border text-right">Total</td>
										<td class="no-border"><input class="form-control number" type="text" id="order_total" name="order_total"></td>
										<td class="no-border"></td>
									</tr>
									<tr>
										<td colspan="2" class="no-border text-right">Total Item</td>
										<td class="no-border"><input class="form-control number" type="text" id="order_total_item" name="order_total_item"></td>
										<td class="no-border"></td>
									</tr>
								</tfoot>
							</table>
						</div>

						<!-- Table Pembayaran -->
						<div class="table-responsive mt-3">
							<table class="table table-bordered table-hover" id="table-pembayaran">
								<thead style="background: #cae3f9;">
									<tr>
										<th style="width: 30%!important">Tanggal</th>
										<th style="width: 15%">Cara Bayar</th>
										<th style="width: 20%">Akun</th>
										<th style="width: 30%">Total</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody id="tbody-pembayaran">
									<tr class="pembayaran_1">
										<td scope="row">
											<input type="hidden" class="form-control" name="order_detail_id[1]" id="order_detail_id_1">
											<input type="date" class="form-control" name="order_detail_pembayaran_tanggal[1]" id="order_detail_pembayaran_tanggal_1" style="width: 100%;">
										</td>
										<td>
											<select class="form-control" name="order_detail_pembayaran_cara_bayar[1]" id="order_detail_pembayaran_cara_bayar_1" style="width: 100%" onchange="getHarga('1')">
												<option value="">-Pilih Cara Bayar-</option>
												<option value="Transfer Bank">Transfer Bank</option>
												<option value="Cash">Cash</option>
											</select>
										</td>
										<td>
											<input type="text" class="form-control" name="order_detail_pembayaran_akun[1]" id="order_detail_pembayaran_akun_1" style="width: 100%;">
										</td>
										<td>
											<input class="form-control number jumlah" type="text" name="order_detail_pembayaran_total[1]" id="order_detail_pembayaran_total_1" readonly="">
										</td>
										<td><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRowBarang(this)" title="Hapus">
												<span class="la la-trash"></span> Hapus</a></td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="1" class="no-border text-right">Total Qty</td>
										<td class="no-border"><input class="form-control number" type="text" id="order_total_qty_pembayaran" name="order_total_qty_pembayaran"></td>
										<td class="no-border text-right">Total</td>
										<td class="no-border"><input class="form-control number" type="text" id="order_total_pembayaran" name="order_total_pembayaran"></td>
										<td class="no-border"></td>
									</tr>
									<tr>
										<td colspan="1" class="no-border text-right">Total Item</td>
										<td class="no-border"><input class="form-control number" type="text" id="order_total_item_pembayaran" name="order_total_item_pembayaran"></td>
										<td class="no-border"></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-4 text-left">
							<button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Back</button>
						</div>
						<div class="col-8 text-right">
							<input type="checkbox" name="cetak_checkbox" id="cetak_checkbox" value="cetak" checked="checked"> <i class="flaticon2-print"></i> Cetak
							<button type="submit" class="btn btn-sm btn-success ml-4"><i class="fas fa-save"></i> Save</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal bd-example-modal-xl fade" id="daftar_barang" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class"modal-header">
				<h5 class="modal-title mx-auto mt-3 pl-3" id="exampleModalCenterTitle">Tambah Barang Baru</h5>
				<button type="button" class="close  mt-1 " data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="kt-form" action="javascript:saveBarang('form-barang')" name="form-barang" id="form-barang">
					<input type="hidden" id="barang_id" name="barang_id">
					<div class="kt-portlet__body">
						<div class="form-group row">
							<label for="barang_kategori_barang" class="col-3 col-form-label">Kelompok Barang</label>
							<div class="col-4">
								<select class="form-control" name="barang_kategori_barang" id="barang_kategori_barang" style="width: 100%"></select>
							</div>
						</div>
						<div class="form-group row">
							<label for="barang_kode" class="col-3 col-form-label">Kode Barang</label>
							<div class="col-2">
								<input class="form-control" type="text" id="barang_kode" name="barang_kode" placeholder=".###">
							</div>
							<label for="barang_nama" class="col-2 col-form-label">Nama Barang</label>
							<div class="col-5">
								<input class="form-control" type="text" id="barang_nama" name="barang_nama">
							</div>
						</div>
						<div class="form-group row">
							<label for="barang_satuan" class="col-3 col-form-label">Satuan Utama</label>
							<div class="col-2">
								<select class="form-control" name="barang_satuan" id="barang_satuan" onchange="showIsi()" style="width: 100%"></select>
							</div>
							<label for="barang_harga" class="col-2 col-form-label">Harga</label>
							<div class="col-2">
								<input class="form-control number" type="text" id="barang_harga" name="barang_harga">
							</div>
						</div>
						<div class="form-group row">
							<label for="barang_satuan_opt" class="col-3 col-form-label">Satuan Tambahan</label>
							<div class="col-2">
								<select class="form-control" name="barang_satuan_opt" id="barang_satuan_opt" style="width: 100%"></select>
							</div>
							<label for="barang_isi" class="col-2 col-form-label">Isi</label>
							<div class="col-2">
								<input class="form-control number" type="text" id="barang_isi" name="barang_isi">
							</div>
							<label class="col-2 col-form-label lbl_barang_satuan"></label>
						</div>
						<div class="form-group row">
							<label for="barang_barcode" class="col-3 col-form-label">Barcode Barang</label>
							<div class="col-6">
								<input class="form-control" type="text" id="barang_barcode" name="barang_barcode">
							</div>
						</div>
					</div>
					<hr>
					<div class="kt-portlet__foot">
						<div class="kt-form__actions">
							<div class="row">
								<div class="col-2"></div>
								<div class="col-10">
									<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Simpan</button>
									<button type="reset" class="btn btn-secondary" data-dismiss="modal"><i class="flaticon2-cancel-music"></i> Batal</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>