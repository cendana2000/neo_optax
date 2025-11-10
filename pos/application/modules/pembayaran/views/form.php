<div class="row form_data" style="display: none">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM PEMBAYARAN HUTANG
				</div>
			</div>
			<form class="kt-form" action="javascript:save('form-pembayaran', '1')" name="form-pembayaran" id="form-pembayaran">
				<div class="card-body">
					<input type="hidden" id="pembayaran_id" name="pembayaran_id">
					<input type="hidden" id="pembayaran_status" name="pembayaran_status">
					<div class="form-group row">
						<label for="pembayaran_kode" class="col-md-2">No Transaksi</label>
						<div class="col-md-3">
							<input class="form-control" type="text" id="pembayaran_kode" name="pembayaran_kode" readonly="" style="background-color: #eaeaea;" placeholder="##.AUTO">
						</div>
						<div class="col-1"></div>
						<label for="pembayaran_tanggal" class="col-md-2">Tgl Transaksi</label>
						<div class="col-4">
							<input class="form-control" type="date" id="pembayaran_tanggal" name="pembayaran_tanggal" value="<?php echo date('Y-m-d') ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="pembayaran_supplier_id" class="col-md-2">Supplier</label>
						<div class="col-3">
							<select name="pembayaran_supplier_id" id="pembayaran_supplier_id" class="form-control" style="width: 100%" onchange="getSales()"></select>
						</div>
						<div class="col-1"></div>
						<label for="pembayaran_invoice" class="col-md-2">No. Invoice</label>
						<div class="col-md-4">
							<input type="text" class="form-control" type="text" id="pembayaran_invoice" name="pembayaran_invoice">
						</div>
					</div>
					<div class="form-group row">

						<label for="pembayaran_keterangan" class="col-md-2">Keterangan</label>
						<div class="col-md-3">
							<textarea class="form-control" type="text" id="pembayaran_keterangan" name="pembayaran_keterangan"></textarea>
						</div>
						<div class="col-md-1"></div>
					</div>



					<div class="form-group row">
						<div class="col-md-8">
							<button type="button" class="btn btn-primary" onclick="listBeli()"><i class="flaticon-add"></i> Daftar Faktur Pembelian</button>
							<button type="button" id="btnPembayaran" class="btn btn-primary ml-1" onclick="addPembayaran()"><i class="flaticon-add"></i> Tambah Pembayaran</button>
						</div>
					</div>

					<div class="table-responsive mt-3 py-5 px-5" style="background: #cae3f9;">
						<button onclick="event.preventDefault();pilihan('listProduk')" class="btn btn-sm btn-default">List Pembayaran</button>
						<button onclick="event.preventDefault();pilihan('pembayaran')" id="btnTunai" class="btn ml-3 btn-sm btn-default">Pembayaran</button>
					</div>

					<div class="table-responsive mt-3">
						<table class="table table-bordered table-hover" id="table-detail_beli">
							<thead style="background: #cae3f9;">
								<tr>
									<th style="width: 18%!important">Faktur</th>
									<th style="width: 5%!important">Tanggal</th>
									<th style="width: 5%!important">Jatuh Tempo</th>
									<th style="width: 14%">Total</th>
									<th style="width: 12%">Retur</th>
									<th style="width: 12%">Potongan</th>
									<th style="width: 14%">Sisa</th>
									<th style="width: 14%">Bayar</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody id="tbody-barang">
								<tr class="no-list">
									<td colspan="8" class="text-center">Silahkan Pilih Faktur Pembelian Barang</td>
								</tr>
							</tbody>
							<tfoot style="display: none" class="table-borderless">
								<tr>
									<td class="no-border text-right" colspan="3" style="vertical-align: middle;">Total</td>
									<td class="no-border"><input class="form-control number" style="vertical-align: middle;" type="text" id="pembayaran_tagihan" name="pembayaran_tagihan" readonly=""></td>
									<td class="no-border"><input class="form-control number" style="vertical-align: middle;" type="text" id="pembayaran_retur" name="pembayaran_retur"></td>
									<td class="no-border"><input class="form-control number" style="vertical-align: middle;" type="text" id="pembayaran_potongan" name="pembayaran_potongan" readonly=""></td>
									<td class="no-border"><input class="form-control number" style="vertical-align: middle;" type="text" id="pembayaran_sisa" name="pembayaran_sisa" readonly=""></td>
									<td class="no-border"><input class="form-control number" style="vertical-align: middle;" type="text" id="pembayaran_bayar" name="pembayaran_bayar" readonly=""></td>
									<td class="no-border"></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="table-responsive mt-3">
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
								<tr class="pembayaran_1">
									<td scope="row">
										<input type="hidden" class="form-control" name="pembayaran_detail_pembayaran_id[1]" id="pembayaran_detail_pembayaran_id_1">
										<input type="date" class="form-control" name="pembayaran_detail_pembayaran_tanggal[1]" id="pembayaran_detail_pembayaran_tanggal_1" value="<?= date('Y-m-d'); ?>" style="width: 100%;">

									</td>
									<td>
										<select class="form-control caraBayar" name="pembayaran_detail_pembayaran_cara_bayar[1]" id="pembayaran_detail_pembayaran_cara_bayar_1" style="width: 100%" onchange="setBayar()">
											<option value="">-Pilih Cara Bayar-</option>
											<option value="Transfer Bank">Transfer Bank</option>
											<option value="Cash">Cash</option>
										</select>
									</td>
									<td>
										<input class="form-control number jumlahNow" type="text" name="pembayaran_detail_pembayaran_total[1]" id="pembayaran_detail_pembayaran_total_1">
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
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-md-4 text-left">
							<!-- <button type="button" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-arrow-left"></i> Back</button> -->
							<button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>

						</div>
						<div class="col-md-8 text-right">
							<!-- <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Save</button> -->

							<!-- <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
								<input type="checkbox" name="cetak_checkbox" id="cetak_checkbox" value="cetak" checked="checked"> <i class="flaticon2-print"></i> Cetak
								<span></span>
							</label> -->

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
				<h5 class="modal-title" id="exampleModalCenterTitle">Daftar Pembelian Barang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-checkable table-condensed" id="list_beli">
					<thead>
						<tr>
							<td>No</td>
							<td>No Transaksi</td>
							<td>Faktur</td>
							<td>Tgl Pembelian</td>
							<td>Jatuh Tempo</td>
							<td>Jumlah</td>
							<td>Retur</td>
							<td>Bayar</td>
							<td>Sisa</td>
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


<div class="card card-custom cetak_data" style="display: none">
	<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
		<div class="ribbon-target" style="top: 12px;">
			<span class="ribbon-inner bg-primary"></span>CETAK BUKTI PEMBAYRAN
		</div>
		<div class="card-toolbar">
			<button type="button" class="btn btn-secondary" onclick="onBack()"><i class="fas fa-arrow-left"></i> Kembali</button>
		</div>
	</div>
	<div class="card-body" id="pdf-laporan">
		<object data="" type="application/pdf" width="100%" height="500px"></object>
	</div>
</div>