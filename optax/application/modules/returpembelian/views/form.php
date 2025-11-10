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

	.bigdrop {
		width: 550px !important;
	}
</style>
<div class="row form_data" style="display: none">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>Form Retur Pembelian Barang
				</div>
			</div>
			<form class="kt-form" action="javascript:save('form-returpembelianbarang')" name="form-returpembelianbarang" id="form-returpembelianbarang">
				<div class="card-body">
					<input type="hidden" id="retur_pembelian_id" name="retur_pembelian_id">
					<div class="form-group row">
						<label for="retur_pembelian_tanggal" class="col-md-2">Tgl Retur</label>
						<div class="col-md-3">
							<input class="form-control" type="date" id="retur_pembelian_tanggal" name="retur_pembelian_tanggal" value="<?php echo date('Y-m-d') ?>">
						</div>
						<div class="col-md-1"></div>
						<label for="retur_pembelian_kode" class="col-md-2">No Transaksi</label>
						<div class="col-md-4">
							<input class="form-control" readonly type="text" id="retur_pembelian_kode" name="retur_pembelian_kode" placeholder="##.AUTO">
						</div>
					</div>
					<div class="form-group row">
						<label for="supplier_alamat" class="col-md-2">Supplier</label>
						<div class="col-md-4">
							<select class="form-control" name="retur_pembelian_supplier_id" id="retur_pembelian_supplier_id" style="width: 100%" onchange="getSupplier()"></select>
						</div>
						<label for="retur_pembelian_pembelian_id" class="col-md-2">No Pembelian</label>
						<div class="col-md-4">
							<select class="form-control" name="retur_pembelian_pembelian_id" id="retur_pembelian_pembelian_id" style="width: 100%" onchange="getPembelian()"></select>
						</div>
					</div>
					<div class="form-group row">
						<label for="pembelian_tanggal" class="col-md-2">Tgl Pembelian</label>
						<div class="col-md-3">
							<input class="form-control" type="date" id="pembelian_tanggal" name="pembelian_tanggal" disabled>
						</div>
						<div class="col-md-1"></div>
						<label for="retur_pembelian_kode" class="col-md-2">Nilai Pembelian</label>
						<div class="col-md-4">
							<input type="hidden" name="bayar-lunas" id="bayar-lunas">
							<div class="input-group">
								<input class="form-control number" type="text" id="pembelian_bayar_grand_total" name="pembelian_bayar_grand_total" disabled="" style="padding-left:6rem">
								<div class="input-group-append"><span class="input-group-text" id="pembelian_jatuh_tempo">JT : dd/mm/yyyy</span></div>
							</div>
						</div>
					</div>
					<div class="form-group row alert-lunas " style="display: none">
						<div class="col-md-12 alert alert-solid-warning" role="alert">
							<div class="alert-text">
								<h4 class="alert-heading">Informasi!</h4>
								<p style="font-weight: 400">Untuk penyesuaian nilai pembelian, silahkan lakukan pemotongan nilai beli di faktur pembelian mendatang!.</p>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-4">
							<button type="button" class="btn btn-sm btn-primary" onclick="listBarang()"><i class="flaticon-add"></i> Daftar Pembelian Barang</button>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="table-detail_barang">
							<thead style="background: #cae3f9;">
								<tr>
									<th style="width: 25%!important">Barang</th>
									<th style="width: 7%">Satuan</th>
									<th style="width: 15%">Harga</th>
									<th style="width: 10%">Stok</th>
									<th style="width: 10%">Retur</th>
									<th style="width: 10%">Sisa</th>
									<th>Nilai</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr class="barang barang_1" data-id="1">
									<td scope="row">
										<input type="hidden" value="" class="form-control" name="retur_pembelian_detail_id[1]" id="retur_pembelian_detail_id_1">
										<input type="hidden" value="" class="form-control" name="retur_pembelian_detail_detail_id[1]" id="retur_pembelian_detail_detail_id_1">
										<select class="form-control" name="retur_pembelian_detail_barang_id[1]" id="retur_pembelian_detail_barang_id_1" style="width: 100%" data-id="1"></select>
									</td>
									<td>
										<input type="hidden" class="form-control" name="retur_pembelian_detail_satuan[1]" id="retur_pembelian_detail_satuan_1">
										<input type="text" class="form-control" name="retur_pembelian_detail_satuan_kode[1]" id="retur_pembelian_detail_satuan_kode_1" readonly="">
									</td>
									<td><input class="form-control number" value="" type="text" name="retur_pembelian_detail_harga[1]" id="retur_pembelian_detail_harga_1" onkeyup="countRow('1')"></td>
									<td><input class="form-control number" type="text" disabled="" value="" name="barang_stok[1]" id="barang_stok_1" onchange="countRow('1')"></td>
									<td>
										<input class="form-control number qty" type="text" name="retur_pembelian_detail_retur_qty[1]" id="retur_pembelian_detail_retur_qty_1" value="" onkeyup="countRow('1')">
										<input class="form-control number" type="hidden" value="" name="retur_pembelian_detail_retur_qty_barang[1]" id="retur_pembelian_detail_retur_qty_barang_1">
									</td>
									<td><input class="form-control number" disabled="" type="text" name="barang_stok_sisa[1]" id="barang_stok_sisa_1" value=""></td>
									<td><input class="form-control number jumlah" type="text" name="retur_pembelian_detail_jumlah[1]" id="retur_pembelian_detail_jumlah_1" value="" onchange="countJumlah()"></td>
									<td><button type="button" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" title="Edit" onclick="remRow('1')">
											<span class="la la-trash"></span>
										</button></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td class="no-border text-right">Total Item</td>
									<td class="no-border"><input class="form-control" type="text" id="retur_pembelian_jumlah_item" name="retur_pembelian_jumlah_item" readonly=""></td>
									<td class="no-border text-right" colspan="2">Total Retur</td>
									<td class="no-border"><input class="form-control number" type="text" id="retur_pembelian_jumlah_qty" name="retur_pembelian_jumlah_qty" readonly=""></td>
									<td class="no-border text-right">Total</td>
									<td class="no-border"><input class="form-control number" type="text" id="retur_pembelian_total" name="retur_pembelian_total" readonly=""></td>
									<td class="no-border"></td>
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
						<div class="col-md-8 text-right">
							<button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Simpan</button>

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
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Daftar Pembelian Barang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-checkable table-condensed" id="list_barang">
					<thead>
						<tr>
							<td>No</td>
							<td>Kode</td>
							<td>Nama Barang</td>
							<td>Satuan</td>
							<td>Harga</td>
							<td>Qty</td>
							<td>Jumlah</td>
							<td>Aksi</td>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="kt-portlet kt-portlet--mobile cetak_data" style="display: none">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Cetak Retur Pembelian
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