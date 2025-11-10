<div class="row form_data" style="display: none">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>Form Retur Penjualan Barang
				</div>
			</div>
			<div class="card-body">
				<form class="kt-form" action="javascript:save('form-returpenjualan')" name="form-returpenjualan" id="form-returpenjualan">
					<input type="hidden" id="retur_penjualan_id" name="retur_penjualan_id">
					<div class="kt-portlet__body">			
						<div class="form-group row">
							<label for="retur_penjualan_tanggal" class="col-2">Tanggal Transaksi</label>
							<div class="col-3">
								<input class="form-control" type="date" id="retur_penjualan_tanggal" name="retur_penjualan_tanggal" value="<?php echo date('Y-m-d') ?>">
							</div>
							<div class="col-1"></div>
							<label for="retur_penjualan_kode" class="col-2">No Transaksi</label>
							<div class="col-4">
								<input class="form-control" type="text" id="retur_penjualan_kode" name="retur_penjualan_kode" readonly="" placeholder="##.AUTO">
							</div>
						</div>
						<div class="form-group row">
							<label for="retur_penjualan_anggota_id" class="col-2">No Penjualan</label>
							<div class="col-3">
								<input type="hidden" name="retur_penjualan_anggota_id" id="retur_penjualan_anggota_id">
								<select class="form-control" name="retur_penjualan_penjualan_id" id="retur_penjualan_penjualan_id" style="width: 100%" onchange="getPenjualan()"></select>
							</div>
							<div class="col-1"></div>
							<label for="customer_nama" class="col-2">Customer</label>
							<div class="col-4">
								<input class="form-control" type="text" id="customer_nama" name="customer_nama" disabled="">
							</div>
						</div>
						<div class="form-group row">
							<label for="penjualan_tanggal" class="col-2" >Tanggal Penjualan</label>
							<div class="col-4">
								<div class="input-group">
									<input class="form-control" type="text" id="penjualan_tanggal" name="penjualan_tanggal" disabled="">
								</div>
							</div>
							<label for="penjualan_total_grand" class="col-2">Nilai Penjualan</label>
							<div class="col-4">
								<div class="input-group">
									<input class="form-control number" type="text" id="penjualan_total_grand" name="penjualan_total_grand" disabled="">
									<div class="input-group-append"><span class="input-group-text" id="status" >Status (T/K)</span></div>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-3">
								<button type="button" class="btn btn-primary" onclick="listBarang()"><i class="flaticon-add"></i> Daftar Penjualan Barang</button>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" id="table-detail_barang">
								<thead style="background: #cae3f9;">
									<tr>
										<th style="width: 25%!important">Barang</th>
										<th style="width: 10%">Satuan</th>
										<th style="width: 15%">Harga</th>
										<th style="width: 8%">Qty Jual</th>
										<th style="width: 8%">Retur</th>
										<th style="width: 8%">Sisa</th>
										<th>Nilai</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<tr class="no-list">
										<td colspan="8" class="text-center">Silahkan Pilih Data Penjualan Barang</td>
									</tr>						
								</tbody>
								<tfoot >
									<tr style="display: none">
										<td class="no-border text-right">Total Item</td>
										<td class="no-border"><input class="form-control" type="text" id="retur_penjualan_jumlah_item" name="retur_penjualan_jumlah_item" readonly=""></td>
										<td class="no-border text-right" colspan="2">Total Retur</td>
										<td class="no-border"><input class="form-control number" type="text" id="retur_penjualan_jumlah_qty" name="retur_penjualan_jumlah_qty" readonly=""></td>
										<td class="no-border text-right">Total</td>
										<td class="no-border"><input class="form-control number" type="text" id="retur_penjualan_total" name="retur_penjualan_total" readonly=""></td>
										<td class="no-border"></td>
									</tr>
								</tfoot>
							</table>
						</div>

					</div>
					<div class="kt-portlet__foot">
						<div class="kt-form__actions">
							<div class="row">
								<div class="col-2"></div>
								<div class="col-10">
									<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Simpan</button>
									<button type="reset" class="btn btn-secondary" onclick="onBack()"><i class="flaticon2-cancel-music"></i> Batal</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal bd-example-modal-xl fade" id="daftar_barang" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Daftar Penjualan Barang</h5>
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
				<button type="button" class="btn btn-outline-brand" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>