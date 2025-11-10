<style type="text/css">
	.btn-detail {
		height: 2.3rem !important;
		width: 1.6rem !important;
	}

	.btn-detail:hover,
	.btn-detail:visited,
	.btn-detail:focus {
		background: none !important;
		border: 1px solid #ffcfa9;
	}

	#table-detail_satuan thead th {
		text-align: center;
		vertical-align: middle;
	}

	.number {
		direction: ltr;
	}

	.bigdrop {
		width: 600px !important;
	}
</style>

<div class="row form_data" style="display: none">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM PEMBELIAN BARANG
				</div>
			</div>
			<form class="kt-form" action="javascript:save('form-pembelianbarang')" name="form-pembelianbarang" id="form-pembelianbarang">
				<input type="hidden" id="pembelian_id" name="pembelian_id">
				<input type="hidden" id="pembelian_is_konsinyasi" name="pembelian_is_konsinyasi">
				<input type="hidden" id="detail_id" name="detail_id">
				<div class="card-body">
					<div class="form-group row">
						<label for="pembelian_tanggal" class="col-md-2 col-form-label">Tgl Transaksi</label>
						<div class="col-md-3">
							<input class="form-control" type="date" id="pembelian_tanggal" name="pembelian_tanggal" value="<?php echo date('Y-m-d') ?>">
						</div>
						<div class="col-1"></div>
						<label for="pembelian_kode" class="col-md-2 col-form-label">No Transaksi</label>
						<div class="col-md-4">
							<input class="form-control" type="text" style="background-color: #eaeaea;" id="pembelian_kode" name="pembelian_kode" readonly="" placeholder="##.AUTO">
						</div>
					</div>
					<div class="form-group row">
						<label for="pembelian_faktur" class="col-md-2 col-form-label">No Faktur</label>
						<div class="col-md-3">
							<input class="form-control" maxlength="12" type="text" id="pembelian_faktur" name="pembelian_faktur">
						</div>

						<!-- <label for="pembelian_order_id" class="col-md-2 col-form-label">No Order</label>
						<div class="col-md-3">
							<select class="form-control" name="pembelian_order_id" id="pembelian_order_id" style="width: 100%" onchange="getOrder()"></select>
						</div> -->
						<div class="col-1"></div>
						<label for="pembelian_supplier_id" class="col-md-2 col-form-label">Supplier</label>
						<div class="col-md-4" id="supplierDiv">
							<select class="form-control" name="pembelian_supplier_id" id="pembelian_supplier_id" style="width: 100%" onchange="getSupplier()"></select>
						</div>
					</div>
					<div class="form-group row">
						<label for="pembelian_bayar_opsi" class="col-md-2 col-form-label">Jenis Pembelian</label>
						<div class="col-md-3">
							<div class="input-group">
								<select id="pembelian_bayar_opsi" name="pembelian_bayar_opsi" class="form-control" onchange="jenisBayar()">
									<option value="K">Kredit</option>
									<option value="T">Tunai</option>
								</select>
							</div>
						</div>
						<div class="col-md-1"></div>
						<label for="pembelian_jatuh_tempo" class="col-md-2 col-form-label" id="label_jatuh_tempo">Jatuh Tempo (hari)</label>
						<div class="col-md-4" id="jatuh_tempo">
							<div class="input-group">
								<div class="input-group-prepend" style="width: 28%">
									<input class="form-control" type="number" id="pembelian_jatuh_tempo_hari" name="pembelian_jatuh_tempo_hari" onkeyup="setJT()">
								</div>
								<input class="form-control" type="date" id="pembelian_jatuh_tempo" name="pembelian_jatuh_tempo" value="<?php echo date('Y-m-d', strtotime('+1 month')) ?>" style="width: 72%" onkeyup="countJT()">
							</div>
						</div>
					</div>
					<!-- <div class="form-group row">
						<div class="col-6">
							<button type="button" class="btn btn-primary" onclick="addBarang()"><i class="flaticon-add"></i> Tambah Baris</button>
							<button type="button" class="btn btn-success" onclick="newBarang()"><i class="flaticon-add"></i> Barang Baru</button> -->
					<!-- <button type="button" class="btn btn-secondary" disabled="" style="background-color: #fff"><code style="font-size: 100%">F4</code> Potongan/Pajak </button> -->
					<!-- </div>
					</div> -->

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

					<!-- Button Tunai -->
					<div class="table-responsive mt-3 py-5 px-5" style="background: #cae3f9;">
						<button onclick="event.preventDefault();pilihan('listProduk')" class="btn btn-sm btn-default">List Produk</button>
						<button onclick="event.preventDefault();pilihan('pembayaran')" id="btnTunai" class="btn ml-3 btn-sm btn-default">Pembayaran</button>
					</div>

					<!-- Table Barang -->
					<div class="table-responsive">
						<table class="table table-bordered table-hover mt-3" id="table-detail_barang">
							<thead style="background: #cae3f9;">
								<tr>
									<th style="width: 32%!important">Barang</th>
									<th style="width: 8%">Satuan</th>
									<th style="width: 10%">Harga</th>
									<th style="width: 7%">Qty</th>
									<th style="width: 9%">Disc</th>
									<th style="width: 19%">Jumlah</th>
									<th style="width: 3%">Aksi</th>
								</tr>
							</thead>
							<tbody id="barangHandler">
								<tr class="barang_1">
									<td scope="row">
										<input type="hidden" class="form-control" name="pembelian_detail_id[1]" id="pembelian_detail_id_1">
										<div class="row">
											<!-- <div class="col-md-4">
												<input type="text" class="form-control barcode-scan" name="barang_kode[1]" id="barang_kode_1" style="display:inline;" data-id="1" placeholder="Barcode">
											</div> -->
											<div class="col-md-12">
												<select class="form-control barang_id" name="pembelian_detail_barang_id[1]" id="pembelian_detail_barang_id_1" data-id="1" onchange="setSatuan('1')" style="white-space: nowrap"></select>
											</div>
										</div>
									</td>
									<td><select class="form-control" name="pembelian_detail_satuan[1]" id="pembelian_detail_satuan_1" style="width: 100%" onchange="getHarga('1')"></select></td>
									<td><input class="form-control number" type="text" name="pembelian_detail_harga[1]" id="pembelian_detail_harga_1" onkeyup="countRow('1')"></td>
									<td>
										<input class="form-control number qty" type="text" name="pembelian_detail_qty[1]" id="pembelian_detail_qty_1" onkeyup="countRow('1')" value="1">
										<input type="hidden" name="pembelian_detail_qty_barang[1]" id="pembelian_detail_qty_barang_1" value="1">
										<input type="hidden" name="pembelian_detail_hpp[1]" id="pembelian_detail_hpp_1">
										<input type="hidden" name="pembelian_detail_harga_barang[1]" id="pembelian_detail_harga_barang_1">
										<input type="hidden" name="pembelian_detail_konversi[1]" id="pembelian_detail_konversi_1">
									</td>
									<td>
										<div class="kt-input-icon kt-input-icon--right">
											<input type="text" class="form-control disc" id="pembelian_detail_diskon_1" name="pembelian_detail_diskon[1]" onkeyup="countRow('1')">
											<span class="kt-input-icon__icon kt-input-icon__icon--right">
												<span>%</span>
											</span>
										</div>
									</td>
									<td><input class="form-control number jumlah" type="text" name="pembelian_detail_jumlah[1]" id="pembelian_detail_jumlah_1" onchange="setHarga('1')"></td>
									<!-- <td>
											<div class="kt-input-icon kt-input-icon--right">
												<input type="text" class="form-control disc" id="pembelian_detail_laba_1" name="pembelian_detail_laba[1]">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>%</span>
												</span>
											</div>	
										</td> -->
									<td>
										<a href="javascript:;" data-id="1" class="btn btn-icon btn-light-primary mr-2" onclick="remRow(this)" title="Hapus"><span class="la la-trash"></span></a>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td class="no-border text-right">Total Item</td>
									<td class="no-border"><input class="form-control" type="text" id="pembelian_jumlah_item" name="pembelian_jumlah_item" readonly=""></td>
									<td class="no-border text-right">Total Qty</td>
									<td class="no-border"><input class="form-control number" type="text" id="pembelian_jumlah_qty" name="pembelian_jumlah_qty" readonly=""></td>
									<td class="no-border text-right">Sub Total</td>
									<td class="no-border"><input class="form-control number" type="text" id="pembelian_total" name="pembelian_total" readonly=""></td>
									<td class="no-border"></td>
								</tr>
								<tr>
									<td colspan="5" class="no-border text-right">Diskon</td>
									<td class="no-border">
										<div class="input-group">
											<div class="kt-input-icon kt-input-icon--right" style="width: 38%;margin-right: 5px;">
												<input type="text" class="form-control disc" id="pembelian_diskon_persen" name="pembelian_diskon_persen" value="0" onchange="countDiskon()">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>%</span>
												</span>
											</div>
											<input type="text" class="form-control number" id="pembelian_diskon" name="pembelian_diskon" onchange="countTotal()">
										</div>
									</td>
									<td class="no-border"></td>
								</tr>
								<tr>
									<td colspan="5" class="no-border text-right">Pajak</td>
									<!-- <td class="no-border"><input class="form-control number" type="text" id="pembelian_pajak" name="pembelian_pajak"></td> -->
									<td class="no-border">
										<div class="input-group">
											<div class="kt-input-icon kt-input-icon--right" style="width: 38%;margin-right: 5px;">
												<input type="text" class="form-control disc" value="0" id="pembelian_pajak_persen" name="pembelian_pajak_persen" onchange="countPajak()">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>%</span>
												</span>
											</div>
											<input class="form-control number" type="text" id="pembelian_pajak" name="pembelian_pajak" readonly>
											<!-- <input type="text" class="form-control number" id="pembelian_diskon" name="pembelian_diskon" onkeyup="countDiskon()"> -->
										</div>
									</td>
									<td class="no-border"></td>
								</tr>
								<tr>
									<td colspan="5" class="no-border text-right">Grand Total</td>
									<td class="no-border"><input class="form-control number" type="text" id="pembelian_bayar_grand_total" name="pembelian_bayar_grand_total"></td>
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
									<th style="width: 30%">Total</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody id="tbody-pembayaran">
								<tr class="pembayaran_1">
									<td scope="row">
										<input type="hidden" class="form-control" name="order_detail_pembayaran_id[1]" id="order_detail_pembayaran_id_1">
										<input type="date" class="form-control" name="order_detail_pembayaran_tanggal[1]" id="order_detail_pembayaran_tanggal_1" value="<?= date('Y-m-d'); ?>" style="width: 100%;">

									</td>
									<td>
										<select class="form-control caraBayar" name="order_detail_pembayaran_cara_bayar[1]" id="order_detail_pembayaran_cara_bayar_1" style="width: 100%" onchange="setBayar()">
											<option value="">-Pilih Cara Bayar-</option>
											<option value="Transfer Bank">Transfer Bank</option>
											<option value="Cash">Cash</option>
										</select>
									</td>
									<td>
										<input class="form-control number jumlahNow" type="text" name="order_detail_pembayaran_total[1]" id="order_detail_pembayaran_total_1">
									</td>
									<td><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRowPembayaran(this, 1)" title="Hapus">
											<span class="la la-trash"></span> Hapus</a></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2" class="no-border text-right">Bayar</td>
									<td colspan="1" value="0" class="no-border"><input readonly class="form-control number" type="text" id="totalAppend"></td>
								</tr>
								<tr>
									<td colspan="2" class="no-border text-right">Total</td>
									<td colspan="1" class="no-border"><input readonly class="form-control number" type="text" id="totalbayar"></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-md-4 text-left">
							<button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>
						</div>
						<div class="col-8 text-right">
							<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
								<input type="checkbox" name="cetak_checkbox" id="cetak_checkbox" value="cetak" checked="checked"> <i class="flaticon2-print"></i> Cetak
								<span></span>
							</label>
							<button type="submit" class="btn btn-sm btn-success ml-4"><i class="fas fa-save"></i> Simpan</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>






<div class="kt-portlet kt-portlet--mobile form_harga" style="display: none">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Detail Harga Barang Faktur No. <span id="label_faktur"></span>
			</h3>
		</div>
	</div>
	<form class="kt-form" action="javascript:saveHarga('form-detailharga')" name="form-detailharga" id="form-detailharga">
		<div class="kt-portlet__body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="table-detail_satuan" style="width:160%">
					<thead style="background: #cae3f9;">
						<tr>
							<th class="sticky-col scol-1" style="width: 15%!important;" rowspan="2">Barang</th>
							<th class="sticky-col scol-md-2" style="width: 4%;" rowspan="2">Satuan Beli</th>
							<th class="sticky-col scol-md-3" style="width: 7%" rowspan="2">Harga</th>
							<th style="" colspan="3">Satuan I</th>
							<th style="" colspan="3">Satuan II</th>
							<th style="" colspan="3">Satuan III</th>
						</tr>
						<tr>
							<th style="width: 6%">Satuan</th>
							<th style="width: 3%;">Isi</th>
							<th style="width: 10%;">Laba/Harga</th>
							<th style="width: 6%">Satuan</th>
							<th style="width: 3%;">Isi</th>
							<th style="width: 10%;">Laba/Harga</th>
							<th style="width: 6%">Satuan</th>
							<th style="width: 3%">Isi</th>
							<th style="width: 10%">Laba/Harga</th>
						</tr>
					</thead>
					<tbody>
						<tr class="barang_1">
							<td class="sticky-col scol-1" scope="row">
								<input type="hidden" class="form-control" name="hg_barang_id[1]" id="hg_barang_id_1" data-id="1">
								<input type="text" class="form-control" name="hg_barang_nama[1]" id="hg_barang_nama_1" disabled="">
							<td class="sticky-col scol-md-2">
								<input type="hidden" class="form-control" name="hg_barang_satuan_beli[1]" id="hg_barang_satuan_beli_1">
								<input type="text" class="form-control" name="hg_barang_kode[1]" id="hg_barang_kode_1" disabled="">
							</td>
							<td class="sticky-col scol-md-3">
								<input class="form-control number" type="text" name="hg_barang_harga_beli[1]" id="hg_barang_harga_beli_1" disabled="">
								<input class="form-control number" type="hidden" name="hg_barang_harga_barang[1]" id="hg_barang_harga_barang_1" disabled="">
							</td>
							<td>
								<input type="hidden" name="hg_detail_barang_satuan_id[1][1]" id="hg_detail_barang_satuan_id_1" data-id="1">
								<input type="hidden" name="hg_detail_barang_satuan_kode[1][1]" id="hg_detail_barang_satuan_kode_1" data-id="1">
								<select class="form-control" name="hg_detail_barang_satuan_satuan_id[1][1]" id="hg_detail_barang_satuan_satuan_id_1" data-id="1" onchange="setSatuanHarga('1')" style="width: 100%"></select>
							</td>
							<td>
								<input class="form-control number" type="text" name="hg_detail_barang_satuan_konversi[1][1]" id="hg_detail_barang_satuan_konversi_1" onkeyup="setHargaDetail('')">
								<input class="form-control number" type="hidden" name="hg_detail_barang_satuan_harga_beli[1][1]" id="hg_detail_barang_satuan_harga_beli_1">
							</td>
							<td>
								<div class="input-group">
									<div class="kt-input-icon kt-input-icon--right" style="width:40%;margin-right: 5px;">
										<input type="text" class="form-control disc" id="hg_detail_barang_satuan_keuntungan_1" name="hg_detail_barang_satuan_keuntungan[1][1]" onkeyup="countHarga()">
										<span class="kt-input-icon__icon kt-input-icon__icon--right">
											<span>%</span>
										</span>
									</div>
									<input type="text" class="form-control number" id="hg_detail_barang_satuan_harga_jual[1][1]" name="hg_detail_barang_satuan_harga_jual_1" onkeyup="countLaba()">
								</div>
							</td>
							<td>
								<input type="hidden" name="hg_detail_barang_satuan_id[1][2]" id="hg_detail_barang_satuan_id_2" data-id="2">
								<input type="hidden" name="hg_detail_barang_satuan_kode[1][2]" id="hg_detail_barang_satuan_kode_2" data-id="2">
								<select class="form-control" name="hg_detail_barang_satuan_satuan_id[1][2]" id="hg_detail_barang_satuan_satuan_id_2" data-id="2" onchange="setSatuanHarga('2')" style="width: 100%"></select>
							</td>
							<td>
								<input class="form-control number" type="text" name="hg_detail_barang_satuan_konversi[1][2]" id="hg_detail_barang_satuan_konversi_2">
								<input class="form-control number" type="hidden" name="hg_detail_barang_satuan_harga_beli[1][2]" id="hg_detail_barang_satuan_harga_beli_2">
							</td>
							<td>
								<div class="input-group">
									<div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
										<input type="text" class="form-control disc" id="hg_detail_barang_satuan_keuntungan_2" name="hg_detail_barang_satuan_keuntungan[1][2]" onkeyup="countHarga()">
										<span class="kt-input-icon__icon kt-input-icon__icon--right">
											<span>%</span>
										</span>
									</div>
									<input type="text" class="form-control number" id="hg_detail_barang_satuan_harga_jual[1][2]" name="hg_detail_barang_satuan_harga_jual_2" onkeyup="countLaba()">
								</div>
							</td>
							<td>
								<input type="hidden" name="hg_detail_barang_satuan_id[1][3]" id="hg_detail_barang_satuan_id_3" data-id="3">
								<input type="hidden" name="hg_detail_barang_satuan_kode[1][3]" id="hg_detail_barang_satuan_kode_3" data-id="3">
								<select class="form-control" name="hg_detail_barang_satuan_satuan_id[1][3]" id="hg_detail_barang_satuan_satuan_id_3" data-id="3" onchange="setSatuanHarga('3')" style="width: 100%"></select>
							</td>
							<td>
								<input class="form-control number" type="text" name="hg_detail_barang_satuan_konversi[1][3]" id="hg_detail_barang_satuan_konversi_3">
								<input class="form-control number" type="hidden" name="hg_detail_barang_satuan_harga_beli[1][3]" id="hg_detail_barang_satuan_harga_beli_3">
							</td>
							<td>
								<div class="input-group">
									<div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
										<input type="text" class="form-control disc" id="hg_detail_barang_satuan_keuntungan_3" name="hg_detail_barang_satuan_keuntungan[1][3]" onkeyup="countHarga()">
										<span class="kt-input-icon__icon kt-input-icon__icon--right">
											<span>%</span>
										</span>
									</div>
									<input type="text" class="form-control number" id="hg_detail_barang_satuan_harga_jual[1][3]" name="hg_detail_barang_satuan_harga_jual_3" onkeyup="countLaba()">
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<div class="row">
					<div class="col-md-2" style="padding-top: 8px;text-align: right;">
						<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
							<input type="hidden" name="print_id" id="print_id">
							<input type="checkbox" name="print_checkbox" id="print_checkbox" value="cetak" checked="checked"> <i class="flaticon2-print"></i> Cetak
							<span></span>
						</label>
					</div>
					<div class="col-10">
						<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Simpan</button>
						<button type="reset" class="btn btn-secondary" onclick="onBack()"><i class="flaticon2-cancel-music"></i> Batal</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<!-- Cetak Section -->
<div class="kt-portlet kt-portlet--mobile cetak_data" style="display: none">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Cetak Pembelian
			</h3>
		</div>
		<div class="kt-portlet__head-toolbar">
			<div class="kt-portlet__head-group">
				<button type="reset" class="btn btn-outline-brand btn-square" onclick="onBack()"><i class="flaticon2-reply"></i> Kembali</button>
			</div>
		</div>
	</div>
	<div class="kt-portlet__body" id="pdf-laporan">
		<object data="" type="application/pdf" width="100%" height="500px"></object>
	</div>

</div>

<div class="kt-portlet kt-portlet--mobile cetak_kwitansi" style="display: none">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Cetak Kwitansi
			</h3>
		</div>
		<div class="kt-portlet__head-toolbar">
			<div class="kt-portlet__head-group">
				<button type="reset" class="btn btn-outline-brand btn-square" onclick="onBack()"><i class="flaticon2-reply"></i> Kembali</button>
			</div>
		</div>
	</div>
	<div class="kt-portlet__body" id="pdf-laporan_kwitansi">
		<object data="" type="application/pdf" width="100%" height="500px"></object>
	</div>

</div>

<div class="modal bd-example-modal-xl fade" id="daftar_barang" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Tambah Barang Baru</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="kt-form" action="javascript:saveBarang('form-barang')" name="form-barang" id="form-barang">
					<input type="hidden" id="barang_id" name="barang_id">
					<div class="kt-portlet__body">
						<div class="form-group row">
							<label for="barang_kategori_barang" class="col-md-3 col-form-label">Kelompok Barang</label>
							<div class="col-md-4">
								<select class="form-control" name="barang_kategori_barang" id="barang_kategori_barang" style="width: 100%" required="required"></select>
							</div>
						</div>
						<div class="form-group row">
							<label for="barang_kode" class="col-md-3 col-form-label">Kode Barang</label>
							<div class="col-md-2">
								<input class="form-control" type="text" id="barang_kode" name="barang_kode" placeholder=".###" onblur="checkKode(this)">
							</div>
							<label for="barang_nama" class="col-md-2 col-form-label">Nama Barang</label>
							<div class="col-5">
								<input class="form-control" type="text" id="barang_nama" name="barang_nama" required="required">
							</div>
						</div>
						<div class="form-group row">
							<label for="barang_satuan_satuan_id_1" class="col-md-3 col-form-label">Satuan Utama</label>
							<div class="col-md-2">
								<select class="form-control" name="barang_satuan_satuan_id[1]" id="barang_satuan_satuan_id_1" onchange="showIsi(1)" style="width: 100%" required="required"></select>
								<input type="hidden" class="form-control" name="barang_satuan_kode[1]" id="barang_satuan_kode_1">
							</div>
							<label for="barang_satuan_harga_beli_1" class="col-md-2 col-form-label">Harga</label>
							<div class="col-md-2">
								<input class="form-control number" type="text" name="barang_satuan_harga_beli[1]" id="barang_satuan_harga_beli_1">
								<input class="form-control number" type="hidden" name="barang_satuan_konversi[1]" id="barang_satuan_konversi_1" value="1">
							</div>
						</div>
						<div class="form-group row">
							<label for="barang_satuan_satuan_id_2" class="col-md-3 col-form-label">Satuan Tambahan</label>
							<div class="col-md-2">
								<select class="form-control" name="barang_satuan_satuan_id[2]" id="barang_satuan_satuan_id_2" style="width: 100%" onchange="showIsi(2)"></select>
								<input type="hidden" class="form-control" name="barang_satuan_kode[2]" id="barang_satuan_kode_2">
							</div>
							<label for="barang_satuan_konversi_2" class="col-md-2 col-form-label">Isi</label>
							<div class="col-md-2">
								<input class="form-control number" type="text" id="barang_satuan_konversi_2" name="barang_satuan_konversi[2]">
							</div>
							<label class="col-md-2 col-form-label lbl_barang_satuan"></label>
						</div>
						<div class="form-group row">
							<label for="barang_barcode" class="col-md-3 col-form-label">Barcode Barang</label>
							<div class="col-6">
								<input class="form-control" type="text" id="barang_barcode" name="barang_barcode">
							</div>
						</div>
					</div>
					<hr>
					<div class="kt-portlet__foot">
						<div class="kt-form__actions">
							<div class="row">
								<div class="col-md-2"></div>
								<div class="col-10">
									<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Simpan</button>
									<button type="reset" class="btn btn-secondary"><i class="flaticon2-cancel-music"></i> Batal</button>
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

<div class="modal bd-example-modal-sm fade" id="detail_potongan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Potongan/Pajak</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="kt-form" action="javascript:setPotongan('form-potongan')" name="form-potongan" id="form-potongan">
					<div class="kt-portlet__body">
						<div class="form-group row">
							<label for="barang_kategori_barang" class="col-md-2 col-form-label">Potongan</label>
							<div class="col-12">
								<div class="input-group">
									<div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
										<input type="text" class="form-control disc" id="hg_detail_barang_satuan_keuntungan_3" name="hg_detail_barang_satuan_keuntungan[1][3]" onkeyup="countHarga()">
										<span class="kt-input-icon__icon kt-input-icon__icon--right">
											<span>%</span>
										</span>
									</div>
									<input type="text" class="form-control number" id="hg_detail_barang_satuan_harga_jual[1][3]" name="hg_detail_barang_satuan_harga_jual_3" onkeyup="countLaba()">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label for="barang_kategori_barang" class="col-md-2 col-form-label">Pajak</label>
							<div class="col-12">
								<div class="input-group">
									<div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
										<input type="text" class="form-control disc" id="hg_detail_barang_satuan_keuntungan_3" name="hg_detail_barang_satuan_keuntungan[1][3]" onkeyup="countHarga()">
										<span class="kt-input-icon__icon kt-input-icon__icon--right">
											<span>%</span>
										</span>
									</div>
									<input type="text" class="form-control number" id="hg_detail_barang_satuan_harga_jual[1][3]" name="hg_detail_barang_satuan_harga_jual_3" onkeyup="countLaba()">
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal bd-example-modal-xl fade" id="detail_barang" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Detail Harga Barang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="kt-form" action="javascript:saveDetail('form-detail')" name="form-detail" id="form-detail">
					<input type="text" id="barang_satuan_parent" name="barang_satuan_parent" style="display: none;">
					<div class="kt-portlet__body">
						<div class="form-group row">
							<label for="barang_kategori_barang" class="col-md-2 col-form-label">Nama Barang</label>
							<div class="col-md-4">
								<input class="form-control " type="text" id="txtbarang_nama" name="txtbarang_nama" disabled>
							</div>
						</div>

						<div class="table-responsive">
							<table class="table table-bordered table-hover" id="table-sales">
								<thead style="background: #cae3f9;">
									<tr>
										<th style="width: 20%!important">Satuan</th>
										<th style="width: 10%">Konversi</th>
										<th style="width: 20%">Harga Beli</th>
										<th style="width: 10%">Keuntungan</th>
										<th style="width: 20%">Harga Jual</th>
										<th style="width: 10%">Disc %</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<tr class="detail_1">
										<td scope="row">
											<input type="hidden" class="form-control" name="detail_barang_satuan_id[1]" id="detail_barang_satuan_id_1">
											<input type="hidden" class="form-control" name="detail_barang_satuan_kode[1]" id="detail_barang_satuan_kode_1">
											<select class="form-control" name="detail_barang_satuan_satuan_id[1]" id="detail_barang_satuan_satuan_id_1" style="width: 100%" onchange="showIsiDetail('1')"></select>
										</td>
										<td><input class="form-control number" type="text" name="detail_barang_satuan_konversi[1]" id="detail_barang_satuan_konversi_1" readonly="" value="1"></td>
										<td><input class="form-control number" type="text" name="detail_barang_satuan_harga_beli[1]" id="detail_barang_satuan_harga_beli_1" onkeyup="setUntung('1')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" onkeyup="setUntung('1')" name="detail_barang_satuan_keuntungan[1]" id="detail_barang_satuan_keuntungan_1">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>%</span>
												</span>
											</div>
										</td>
										<td><input class="form-control number" type="text" name="detail_barang_satuan_harga_jual[1]" id="detail_barang_satuan_harga_jual_1" onkeyup="setUntungRp('1')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" name="detail_barang_satuan_disc[1]" id="detail_barang_satuan_disc_1">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>%</span>
												</span>
											</div>
										</td>
										<td>
											<a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow('1')" title="Reset">
												<span class="la la-rotate-right"></span> Reset</a>
										</td>
									</tr>
									<tr class="detail_2">
										<td scope="row">
											<input type="hidden" class="form-control" name="detail_barang_satuan_id[2]" id="detail_barang_satuan_id_2">
											<input type="hidden" class="form-control" name="detail_barang_satuan_kode[2]" id="detail_barang_satuan_kode_2">
											<select class="form-control" name="detail_barang_satuan_satuan_id[2]" id="detail_barang_satuan_satuan_id_2" style="width: 100%" onchange="showIsiDetail('2')"></select>
										</td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control number" type="text" name="detail_barang_satuan_konversi[2]" id="detail_barang_satuan_konversi_2">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span class="lbl_barang_satuan_detail"></span>
												</span>
											</div>
										</td>
										<td><input class="form-control number" type="text" name="detail_barang_satuan_harga_beli[2]" id="detail_barang_satuan_harga_beli_2" onkeyup="setUntung('2')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" onkeyup="setUntung('2')" name="detail_barang_satuan_keuntungan[2]" id="detail_barang_satuan_keuntungan_2">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>%</span>
												</span>
											</div>
										</td>
										<td><input class="form-control number" type="text" name="detail_barang_satuan_harga_jual[2]" id="detail_barang_satuan_harga_jual_2" onkeyup="setUntungRp('2')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" name="detail_barang_satuan_disc[2]" id="detail_barang_satuan_disc_2">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>%</span>
												</span>
											</div>
										</td>
										<td>
											<a href="javascript:;" data-id="2" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow('2')" title="Reset">
												<span class="la la-rotate-right"></span> Reset</a>
										</td>
									</tr>
									<tr class="detail_3">
										<td scope="row">
											<input type="hidden" class="form-control" name="detail_barang_satuan_id[3]" id="detail_barang_satuan_id_3">
											<input type="hidden" class="form-control" name="detail_barang_satuan_kode[3]" id="detail_barang_satuan_kode_3">
											<select class="form-control" name="detail_barang_satuan_satuan_id[3]" id="detail_barang_satuan_satuan_id_3" style="width: 100%" onchange="showIsiDetail('3')"></select>
										</td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control number" type="text" name="detail_barang_satuan_konversi[3]" id="detail_barang_satuan_konversi_3">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span class="lbl_barang_satuan_detail"></span>
												</span>
											</div>
										</td>
										<td><input class="form-control number" type="text" name="detail_barang_satuan_harga_beli[3]" id="detail_barang_satuan_harga_beli_3" onkeyup="setUntung('3')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" onkeyup="setUntung('3')" name="detail_barang_satuan_keuntungan[3]" id="detail_barang_satuan_keuntungan_3">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>%</span>
												</span>
											</div>
										</td>
										<td><input class="form-control number" type="text" name="detail_barang_satuan_harga_jual[3]" id="detail_barang_satuan_harga_jual_3" onkeyup="setUntungRp('3')"></td>
										<td>
											<div class="kt-input-icon kt-input-icon--right">
												<input class="form-control disc" type="text" name="detail_barang_satuan_disc[3]" id="detail_barang_satuan_disc_3">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>%</span>
												</span>
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
					<hr>
					<div class="kt-portlet__foot">
						<div class="kt-form__actions">
							<div class="row">
								<div class="col-md-2"></div>
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