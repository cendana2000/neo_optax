<style type="text/css">
	th {
		position: sticky;
		top: 0;
		z-index: 2;
	}
</style>


<div class="row form_data" id="form_toogle" style="display: none">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="kt-portlet kt-portlet--mobile form_data" style="display: none">
				<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
					<div class="ribbon-target" style="top: 12px;">
						<span class="ribbon-inner bg-primary"></span>FORM STOCK OPNAME
					</div>
				</div>
				<form class="kt-form" action="javascript:save('form-stokopname')" name="form-stokopname" id="form-stokopname">
					<div class="card-body">
						<input type="hidden" id="opname_id" name="opname_id">
						<div class="kt-portlet__body">
							<div class="form-group row">
								<label for="opname_tanggal" class="col-md-2">Tgl Transaksi</label>
								<div class="col-md-3">
									<input class="form-control" type="date" id="opname_tanggal" name="opname_tanggal" value="<?php echo date('Y-m-d') ?>">
								</div>
								<div class="col-md-1">
								</div>
								<label for="opname_kode" class="col-md-2">Kode Opname</label>
								<div class="col-md-3">
									<input class="form-control" type="text" id="opname_kode" name="opname_kode" readonly="" placeholder="##.AUTO">
								</div>
							</div>
							<div class="form-group row">
								<label for="opname_kategori_barang" class="col-md-2">Kelompok Barang</label>
								<div class="col-md-4">
									<div class="row d-flex justify-content-start">
										<div class="col-md-6">
											<select class="form-control" id="opname_kategori_barang" name="opname_kategori_barang" style="width: 30%" onchange="changeKategori()"></select>
										</div>
										<div class="col-sm-6">
											<button class="btn btn-primary" type="button" onclick="allBarang()">Tampilkan</button>
										</div>
									</div>
								</div>
								<label for="opname_operator" class="col-md-2">Petugas</label>
								<div class="col-md-4">
									<select class="form-control" id="opname_operator" name="opname_operator" style="width: 100%"></select>
								</div>
							</div>
							<div class="form-group row">
								<label for="opname_keterangan" class="col-md-2">Keterangan</label>
								<div class="col-md-10">
									<textarea class="form-control" type="text" id="opname_keterangan" name="opname_keterangan"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<!-- <div class="col-md-3"> -->
								<button type="button" class="btn btn-primary" onclick="addBarang()"><i class="flaticon-add"></i> Tambah Barang</button>
								<!-- </div> -->
								<label class="col-md-2" for="cari_barang" style="margin-top: 8px; text-align: right;">Pencarian Barang</label>
								<div class="col-md-3">
									<input type="text" name="cari_barang" id="cari_barang" class="form-control" onkeyup="cariBarang()">
								</div>

							</div>
							<div class="table-responsive">
								<table class="table table-opname table-hover table-responsive tableBodyScroll" id="table-detail_barang">
									<thead style="background: #cae3f9;">
										<tr>
											<th style="width: 30%!important">Barang</th>
											<th style="width: 10%">Satuan</th>
											<th style="width: 10%">Harga</th>
											<th style="width: 10%">Qty Data</th>
											<th style="width: 10%">Qty Fisik</th>
											<th style="width: 10%">Selisih</th>
											<th style="width: 15%">Nilai Koreksi</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
										<tr class="barang_1">
											<td scope="row" style="width: 30%!important">
												<input type="hidden" class="txt-search" name="txt_search[1]" id="txt_search_1" value="">
												<input type="hidden" class="form-control" name="opname_detail_id[1]" id="opname_detail_id_1">
												<select class="form-control barang_id" name="opname_detail_barang_id[1]" id="opname_detail_barang_id_1" data-id="1" style="width: 100%;white-space: nowrap" onchange="setSatuan('1')"></select>
											</td>
											<td style="width: 10%">
												<input class="form-control" type="hidden" name="opname_detail_satuan_id[1]" id="opname_detail_satuan_id_1">
												<input class="form-control" type="text" name="opname_detail_satuan_kode[1]" id="opname_detail_satuan_kode_1" readonly="">
											</td>
											<td style="width: 10%"><input class="form-control nominal" type="text" name="opname_detail_harga[1]" id="opname_detail_harga_1" readonly=""></td>
											<td style="width: 10%"><input class="form-control number data" type="text" name="opname_detail_qty_data[1]" id="opname_detail_qty_data_1" readonly=""></td>
											<td style="width: 10%"><input class="form-control number qty" type="text" name="opname_detail_qty_fisik[1]" id="opname_detail_qty_fisik_1" onkeyup="countRow('1')"></td>
											<td style="width: 10%"><input class="form-control number koreksi" type="text" name="opname_detail_qty_koreksi[1]" id="opname_detail_qty_koreksi_1" readonly=""></td>
											<td style="width: 15%"><input class="form-control nominal nilai" type="text" name="opname_detail_nilai[1]" id="opname_detail_nilai_1" readonly=""></td>
											<td><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus">
													<span class="la la-trash"></span> Hapus</a></td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td class="no-border text-right" style="vertical-align: middle;">Item</td>
											<td class="no-border"><input class="form-control" type="text" id="opname_total_item" name="opname_total_item" readonly=""></td>
											<td class="no-border text-right" style="vertical-align: middle;">Total</td>
											<td class="no-border"><input class="form-control number" type="text" id="opname_total_qty_data" name="opname_total_qty_data" readonly=""></td>
											<td class="no-border"><input class="form-control number" type="text" id="opname_total_qty_fisik" name="opname_total_qty_fisik" readonly=""></td>
											<td class="no-border"><input class="form-control number" type="text" id="opname_total_qty_koreksi" name="opname_total_qty_koreksi" readonly=""></td>
											<td class="no-border"><input class="form-control nominal" type="text" id="opname_total_nilai" name="opname_total_nilai" readonly=""></td>
											<td class="no-border"></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-md-4 text-left">
								<button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Back</button>
							</div>
							<div class="col-8 text-right">

								<div class="btn btn-sm btn-light">
									<input type="checkbox" name="cetak_checkbox" id="cetak_checkbox" value="cetak"> <i class="la la-print"></i> Cetak
								</div>
								<button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Save</button>
							</div>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>




<div class="kt-portlet kt-portlet--mobile cetak_data" style="display: none">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Cetak Stock Opname
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
								<select class="form-control" name="barang_kategori_barang" id="barang_kategori_barang" style="width: 100%"></select>
							</div>
						</div>
						<div class="form-group row">
							<label for="barang_kode" class="col-md-3 col-form-label">Kode Barang</label>
							<div class="col-md-2">
								<input class="form-control" type="text" id="barang_kode" name="barang_kode" placeholder=".###">
							</div>
							<label for="barang_nama" class="col-md-2 col-form-label">Nama Barang</label>
							<div class="col-5">
								<input class="form-control" type="text" id="barang_nama" name="barang_nama">
							</div>
						</div>
						<div class="form-group row">
							<label for="barang_satuan" class="col-md-3 col-form-label">Satuan Utama</label>
							<div class="col-md-2">
								<select class="form-control" name="barang_satuan" id="barang_satuan" onchange="showIsi()" style="width: 100%"></select>
							</div>
							<label for="barang_harga" class="col-md-2 col-form-label">Harga</label>
							<div class="col-md-2">
								<input class="form-control number" type="text" id="barang_harga" name="barang_harga">
							</div>
						</div>
						<div class="form-group row">
							<label for="barang_satuan_opt" class="col-md-3 col-form-label">Satuan Tambahan</label>
							<div class="col-md-2">
								<select class="form-control" name="barang_satuan_opt" id="barang_satuan_opt" style="width: 100%"></select>
							</div>
							<label for="barang_isi" class="col-md-2 col-form-label">Isi</label>
							<div class="col-md-2">
								<input class="form-control number" type="text" id="barang_isi" name="barang_isi">
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
								<div class="col-md-10">
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