<div class="row form_data" style="display: none">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM RETUR PENJUALAN</h3>
				</div>
			</div>
			<form class="kt-form" action="javascript:save()" name="form-returpenjualan" id="form-returpenjualan">
				<div class="card-body">
					<input type="hidden" id="retur_penjualan_id" name="retur_penjualan_id">
					<div class="kt-portlet__body">
						<div class="form-group row">
							<label for="retur_penjualan_tanggal" class="col-md-2 col-form-label">Tanggal Transaksi</label>
							<div class="col-md-3">
								<div class="input-group date">
									<input class="form-control" type="text" id="retur_penjualan_tanggal" name="retur_penjualan_tanggal" readonly value="<?php echo date('d/m/Y') ?>">
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="la la-calendar-check-o"></i>
										</span>
									</div>
								</div>
							</div>
							<div class="col-1"></div>
							<label for="retur_penjualan_kode" class="col-md-2 col-form-label">No Transaksi</label>
							<div class="col-md-4">
								<input class="form-control" style="background-color: #f3f6f9;" type="text" id="retur_penjualan_kode" name="retur_penjualan_kode" readonly="" placeholder="##.AUTO">
							</div>
						</div>
						<div class="form-group row">
							<label for="retur_penjualan_anggota_id" class="col-md-2 col-form-label">No Penjualan</label>
							<div class="col-md-3">
								<input type="hidden" name="retur_penjualan_anggota_id" id="retur_penjualan_anggota_id">
								<select class="form-control" name="retur_penjualan_penjualan_id" id="retur_penjualan_penjualan_id" style="width: 100%" onchange="getPenjualan()"></select>
							</div>
							<div class="col-1"></div>
							<label for="customer_nama" class="col-md-2 col-form-label">Customer</label>
							<div class="col-md-4">
								<input class="form-control" type="text" id="retur_penjualan_customer_id" name="retur_penjualan_customer_id" hidden="">
								<input class="form-control" type="text" id="customer_nama" name="customer_nama" disabled="">
							</div>
						</div>
						<div class="form-group row">
							<label for="penjualan_tanggal" class="col-md-2 col-form-label">Tanggal Penjualan</label>
							<div class="col-md-3">
								<div class="input-group">
									<input class="form-control" type="text" id="penjualan_tanggal" name="penjualan_tanggal" disabled="">
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="la la-calendar-check-o"></i>
										</span>
									</div>
								</div>
							</div>
							<div class="col-1"></div>
							<label for="penjualan_total_grand" class="col-md-2 col-form-label">Nilai Penjualan</label>
							<div class="col-md-4">
								<div class="input-group">
									<input class="form-control number" type="text" id="penjualan_total_grand" name="penjualan_total_grand" disabled="">
									<!-- <div class="input-group-append"><span class="input-group-text" id="status" >Status (T/K)</span></div> -->
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-3">
								<button type="button" class="btn btn-primary" onclick="listBarang()"><i class="flaticon-add"></i> Daftar Penjualan Barang</button>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" id="table-detail_barang">
								<thead style="background: #cae3f9;">
									<tr class="d-flex d-md-table-row">
										<th class="col-5 col-md-auto" style="background: #cae3f9;">Barang</th>
										<th class="col-3 col-md-auto" style="background: #cae3f9;">Satuan</th>
										<th class="col-4 col-md-auto" style="background: #cae3f9;">Harga</th>
										<th class="col-3 col-md-auto" style="background: #cae3f9;">Qty Jual</th>
										<th class="col-3 col-md-auto" style="background: #cae3f9;">Retur</th>
										<th class="col-3 col-md-auto" style="background: #cae3f9;">Sisa</th>
										<th class="col-2 col-md-auto" style="background: #cae3f9;">Nilai</th>
										<th class="col-1 col-md-auto" style="background: #cae3f9;">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<tr class="no-list">
										<td colspan="8" class="text-center">Silahkan Pilih Data Penjualan Barang</td>
									</tr>
								</tbody>
								<!-- <tfoot class="table-borderless">
									<tr style="display: none" class="d-flex d-md-table-row">
										<td class="col-5 col-md-auto no-border text-right align-middle">Total Item</td>
										<td class="col-3 col-md-auto no-border"><input class="form-control" type="text" id="retur_penjualan_total_item" name="retur_penjualan_total_item" readonly=""></td>
										<td class="col-7 col-md-auto no-border text-right align-middle" colspan="2">Total Retur</td>
										<td class="col-3 col-md-auto no-border"><input class="form-control number" type="text" id="retur_penjualan_total_qty" name="retur_penjualan_total_qty" readonly=""></td>
										<td class="col-3 col-md-auto no-border text-right align-middle">Total</td>
										<td class="col-2 col-md-auto no-border"><input class="form-control number" type="text" id="retur_penjualan_total" name="retur_penjualan_total" readonly=""></td>
										<td class="col-1 col-md-auto no-border"></td>
									</tr>
								</tfoot> -->
							</table>
						</div>
						<div class="row mt-3">
							<div class="col-4">
								<div class="form-grub">
									<label for="">Total Item</label>
									<input class="form-control" type="text" id="retur_penjualan_total_item" name="retur_penjualan_total_item" readonly="">
								</div>
							</div>
							<div class="col-4">
								<div class="form-grub">
									<label for="">Total Retur</label>
									<input class="form-control number" type="text" id="retur_penjualan_total_qty" name="retur_penjualan_total_qty" readonly="">
								</div>
							</div>
							<div class="col-4">
								<div class="form-grub">
									<label for="">Total</label>
									<input class="form-control number" type="text" id="retur_penjualan_total" name="retur_penjualan_total" readonly="">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer d-flex justify-content-between">
					<button type="reset" class="btn btn-secondary btn-sm" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>
					<button type="submit" class="btn btn-success btn-sm"><i class="flaticon-paper-plane-1"></i> Simpan</button>
				</div>
			</form>
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
				<div class="table-responsive">
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
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-brand" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>