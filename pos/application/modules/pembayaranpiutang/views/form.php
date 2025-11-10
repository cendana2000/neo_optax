<div class="row form_data" style="display: none">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM PEMBAYARAN PIUTANG</h3>
				</div>
			</div>
			<form class="kt-form" action="javascript:save('form-pembayaran', '1')" name="form-pembayaran" id="form-pembayaran">
				<div class="card-body">
					<input type="hidden" id="pembayaran_piutang_id" name="pembayaran_piutang_id">
					<input type="hidden" id="pembayaran_piutang_status" name="pembayaran_piutang_status">
					<div class="form-group row">
						<label for="pembayaran_piutang_kode" class="col-md-2">No Transaksi</label>
						<div class="col-md-3">
							<input class="form-control" type="text" id="pembayaran_piutang_kode" name="pembayaran_piutang_kode" readonly="" style="background-color: #eaeaea;" placeholder="(Otomatis) PY.YMD.###">
						</div>
						<div class="col-1"></div>
						<label for="pembayaran_piutang_tanggal" class="col-md-2">Tgl Transaksi</label>
						<div class="col-md-4">
							<input class="form-control" type="date" id="pembayaran_piutang_tanggal" name="pembayaran_piutang_tanggal" value="<?php echo date('Y-m-d') ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="pembayaran_piutang_customer_id" class="col-md-2">Customer</label>
						<div class="col-md-3">
							<select name="pembayaran_piutang_customer_id" id="pembayaran_piutang_customer_id" class="form-control" style="width: 100%" onchange="getCustomer()"></select>
						</div>
						<div class="col-1"></div>
						<label for="pembayaran_piutang_invoice" class="col-md-2">No. Invoice</label>
						<div class="col-md-4">
							<input type="text" class="form-control" type="text" id="pembayaran_piutang_invoice" name="pembayaran_piutang_invoice" readonly="" style="background-color: #eaeaea;" placeholder="(Otomatis) INV.YMD.###">
						</div>
					</div>
					<div class="form-group row">

						<label for="pembayaran_piutang_keterangan" class="col-md-2">Keterangan</label>
						<div class="col-md-3">
							<textarea class="form-control" type="text" id="pembayaran_piutang_keterangan" name="pembayaran_piutang_keterangan"></textarea>
						</div>
						<div class="col-md-1"></div>
					</div>



					<div class="form-group row">
						<div class="col-md-8">
							<button type="button" class="btn btn-primary" onclick="listBeli()"><i class="flaticon-add"></i> Daftar Faktur Penjualan</button>
							<!-- <button type="button" id="btnPembayaran" class="btn btn-primary ml-1" onclick="addPembayaran()"><i class="flaticon-add"></i> Tambah Pembayaran</button> -->
						</div>
					</div>

					<div class="table-responsive mt-3 py-5 px-5" style="background: #cae3f9; display:none;">
						<button onclick="event.preventDefault();pilihan('listProduk')" class="btn btn-sm btn-default">List Pembayaran</button>
						<button onclick="event.preventDefault();pilihan('pembayaran')" id="btnTunai" class="btn ml-3 btn-sm btn-default">Pembayaran</button>
					</div>

					<div class="table-responsive mt-3">
						<table class="table table-bordered table-hover" id="table-detail_beli">
							<thead style="background: #cae3f9;">
								<tr>
									<th style="width: 18%!important">Kode</th>
									<th style="width: 18%!important">Item Pertama</th>
									<th style="width: 5%!important">Tanggal</th>
									<th style="width: 5%!important">Jatuh Tempo</th>
									<th style="width: 10%">Total</th>
									<th style="width: 10%">Retur</th>
									<th style="width: 10%">Potongan</th>
									<th style="width: 12%">Sisa</th>
									<th style="width: 14%!important">Bayar</th>
									<th style="width: 1%">Aksi</th>
								</tr>
							</thead>
							<tbody id="tbody-barang">
								<tr class="no-list">
									<td colspan="10" class="text-center">Silahkan Pilih Faktur Pembelian Barang</td>
								</tr>
							</tbody>
							<tfoot style="display: none">
								<tr>
									<td class="no-border text-right" colspan="3">Total</td>
									<td class="no-border"><input class="form-control number" type="text" id="pembayaran_piutang_tagihan" name="pembayaran_piutang_tagihan" readonly=""></td>
									<td class="no-border"><input class="form-control number" type="text" id="pembayaran_piutang_retur" name="pembayaran_piutang_retur" readonly=""></td>
									<td class="no-border"><input class="form-control number" type="text" id="pembayaran_piutang_potongan" name="pembayaran_piutang_potongan" readonly=""></td>
									<td class="no-border"><input class="form-control number" type="text" id="pembayaran_piutang_sisa" name="pembayaran_piutang_sisa" readonly=""></td>
									<td class="no-border"><input class="form-control number" type="text" id="pembayaran_piutang_bayar" name="pembayaran_piutang_bayar" readonly=""></td>
									<td class="no-border"></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="mt-3 row">
						<div class="col-4">
							<div class="form-group">
								<label>Tanggal</label>
								<input type="hidden" class="form-control" name="pembayaran_piutang_detail_pembayaran_id[1]" id="pembayaran_piutang_detail_pembayaran_id_1">
								<input type="date" class="form-control" name="pembayaran_piutang_detail_pembayaran_tanggal[1]" id="pembayaran_piutang_detail_pembayaran_tanggal_1" value="<?= date('Y-m-d'); ?>" style="width: 100%;">
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Cara Bayar</label>
								<select class="form-control caraBayar" name="pembayaran_piutang_detail_pembayaran_cara_bayar[1]" id="pembayaran_piutang_detail_pembayaran_cara_bayar_1" style="width: 100%" onchange="setBayar()">
									<option value="">-Pilih Cara Bayar-</option>
									<option value="Transfer Bank">Transfer Bank</option>
									<option value="Cash">Cash</option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Total</label>
								<input class="form-control number jumlahNow" type="text" name="pembayaran_piutang_detail_pembayaran_total[1]" id="pembayaran_piutang_detail_pembayaran_total_1">
							</div>
						</div>
					</div>
					<div class="mt-3 row">
						<div class="col-4 offset-8">
							<div class="form-group">
								<label>Bayar</label>
								<input readonly class="form-control number" type="text" id="totalAppend">
							</div>
						</div>
					</div>
					<div class="row border-top pt-4">
						<div class="col-4 offset-8">
							<div class="form-group">
								<label>Total</label>
								<input readonly class="form-control number" type="text" id="totalbayar">
							</div>
						</div>
					</div>
					<!--<div class="table-responsive mt-3">
						<table class="table table-bordered table-hover" id="table-opsi-pembayaran">
							<thead style="background: #cae3f9;">
								<tr>
									<th style="width: 30%!important">Tanggal</th>
									<th style="width: 15%">Cara Bayar</th>
									<th style="width: 30%">Total</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody id="tbody-pembayaran">
								<tr class="pembayaran_piutang_1">
									<td scope="row">
										<input type="hidden" class="form-control" name="pembayaran_piutang_detail_pembayaran_id[1]" id="pembayaran_piutang_detail_pembayaran_id_1">
										<input type="date" class="form-control" name="pembayaran_piutang_detail_pembayaran_tanggal[1]" id="pembayaran_piutang_detail_pembayaran_tanggal_1" value="<?= date('Y-m-d'); ?>" style="width: 100%;">
									</td>
									<td>
										<select class="form-control caraBayar" name="pembayaran_piutang_detail_pembayaran_cara_bayar[1]" id="pembayaran_piutang_detail_pembayaran_cara_bayar_1" style="width: 100%" onchange="setBayar()">
											<option value="">-Pilih Cara Bayar-</option>
											<option value="Transfer Bank">Transfer Bank</option>
											<option value="Cash">Cash</option>
										</select>
									</td>
									<td>
										<input class="form-control number jumlahNow" type="text" name="pembayaran_piutang_detail_pembayaran_total[1]" id="pembayaran_piutang_detail_pembayaran_total_1">
									</td>
									<td><a href="javascript:;" data-id="1" class="btn btn-light-warning btn-sm" onclick="remRowPembayaran(this, 1)" title="Hapus">
											<span class="la la-trash"></span></a></td>
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
					</div>-->
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-md-4 text-left">
							<!-- <button type="button" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-arrow-left"></i> Back</button> -->
							<button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>

						</div>
						<div class="col-md-8 text-right">
							<!-- <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Save</button> -->

							<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
								<input type="checkbox" name="cetak_checkbox" id="cetak_checkbox" value="cetak" checked="checked"> <i class="flaticon2-print"></i> Cetak
								<span></span>
							</label>

							<button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Simpan</button>
						</div>
					</div>
				</div>
				</forml>
		</div>
	</div>
</div>

<div class="modal bd-example-modal-xl fade" id="daftar_beli" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Daftar Penjualan Barang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-checkable table-condensed table-responsive" id="list_beli">
					<thead>
						<tr>
							<td>No</td>
							<td>Kode Transaksi</td>
							<td>Produk</td>
							<td>Customer</td>
							<td>Tgl Pembelian</td>
							<td>Jatuh Tempo</td>
							<td>Jumlah</td>
							<td>Retur</td>
							<td>Aksi</td>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-brand" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="kt-portlet kt-portlet--mobile cetak_data" style="display: none">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Cetak Bukti Pembayaran
			</h3>
		</div>
		<div class="kt-portlet__head-toolbar">
			<div class="kt-portlet__head-group">
				<button type="reset" class="btn btn-outline-brand btn-square" onclick="onBack()"><i class="flaticon2-reply"></i> Kembali</button>
				<!-- <button type="button" class="btn btn-outline-focus btn-elevate btn-elevate-air" onclick="onPrintTT()">
					<i class="la la-print"></i> Tanda Terima
				</button> -->
			</div>
		</div>
	</div>
	<div class="kt-portlet__body" id="pdf-laporan">
		<object data="" type="application/pdf" width="100%" height="500px"></object>
	</div>
</div>