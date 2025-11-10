<div class="kt-portlet kt-portlet--mobile form_data">
	<form action="javascript:save('form-penjualanbarang')" name="form-penjualanbarang" id="form-penjualanbarang">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Form Penjualan Barang
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-toolbar-wrapper">
					<div class="dropdown dropdown-inline">
						<button type="button" class="btn btn-outline-success" onclick="onTable()"><i class="flaticon-interface-3"></i> Daftar Penjualan</button>
					</div>
				</div>
			</div>
		</div>
		<div class="kt-form">
			<input type="hidden" id="penjualan_id" name="penjualan_id">
			<input type="hidden" id="penjualan_kasir" name="penjualan_kasir" value="ADM">
			<div class="kt-portlet__body">
				<div class="form-group row">
					<div class="col-6">
						<div class="form-group row">
							<label for="penjualan_tanggal" class="col-3 col-form-label">Tanggal</label>
							<div class="col-6">
								<input class="form-control" type="date" id="penjualan_tanggal" name="penjualan_tanggal" value="<?php echo date('Y-m-d') ?>">
							</div>
						</div>
						<div class="form-group row">
							<label for="penjualan_tanggal" class="col-3 col-form-label">No Transaksi</label>
							<div class="col-6">
								<input class="form-control" type="text" id="penjualan_kode" name="penjualan_kode" readonly="" placeholder="##.AUTO">		
							</div>
						</div>
						<div class="form-group row">
							<label for="penjualan_anggota_id" class="col-3 col-form-label">Nasabah</label>
							<div class="col-8">
								<select class="form-control" name="penjualan_anggota_id" id="penjualan_anggota_id" style="width: 100%" onchange="getNasabah()"></select>
							</div>
						</div>
						<div class="form-group row">
							<label for="anggota_nip" class="col-3 col-form-label">NIP</label>
							<div class="col-8">
								<input type="text" class="form-control" name="anggota_nip" id="anggota_nip" disabled="">
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group row">
							<div class="col-12">
								<div class="col-12" style="height: 75px;">
									<label for="v_penjualan_total_grand" style="position: absolute;padding: 20px;">Total Harga</label>
									<input class="form-control number total_harga" type="text" id="v_penjualan_total_grand" name="v_penjualan_total_grand" value="" style="height: 120%;font-size: 26px;background-color: #d4ebff" readonly="">
								</div>
							</div>
						</div>
						<div class="form-group row">
						</div>
						<div class="form-group row">
							<label for="anggota_saldo_simp_titipan_belanja" class="col-4 col-form-label" style="padding-left: 20px;">Saldo Titipan Belanja</label>
							<div class="col-4">
								<input class="form-control number voucher" type="text" id="anggota_saldo_simp_titipan_belanja" name="anggota_saldo_simp_titipan_belanja" disabled="">
							</div>
						</div>
					</div>					
				</div>
				<div class="form-group row">
					<div class="col-1">
						<input class="form-control number" type="text" id="qty" name="qty" value="1" placeholder="Qty">
					</div>
					<div class="col-4">
						<input class="form-control use_barcode" data-id="input" type="text" id="barang" name="barang" placeholder="Scan Barang Disini." autocomplete="off">
					</div>
					<div class="col-3">
						<button type="button" class="btn btn-primary" onclick="addBarang()"><i class="flaticon-add"></i> Tambah Barang</button>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover" id="table-detail_barang">
						<thead style="background: #cae3f9;">
							<tr>
								<th style="width: 25%!important">Barang</th>
								<th style="width: 10%">Satuan</th>
								<th style="width: 15%">Harga</th>
								<th style="width: 8%">Qty</th>
								<th style="width: 10%">Disc %</th>
								<th>Jumlah</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr class="barang_1">
								<td scope="row">
									<input type="hidden" class="form-control" name="penjualan_detail_id[1]" id="penjualan_detail_id_1">
									<select class="form-control barang_id" name="penjualan_detail_barang_id[1]" id="penjualan_detail_barang_id_1" data-id="1" onchange="setSatuan('1')" style="width: 100%;white-space: nowrap"></select></td>
								<td>
									<select class="form-control" name="penjualan_detail_satuan[1]" id="penjualan_detail_satuan_1" style="width: 100%" onchange="getHarga('1')"></select>
									<input type="hidden" class="form-control" name="penjualan_detail_satuan_kode[1]" id="penjualan_detail_satuan_kode_1">					
								</td>
								<td><input class="form-control number" type="text" name="penjualan_detail_harga[1]" id="penjualan_detail_harga_1" readonly=""></td>
								<td>
									<input class="form-control number qty" type="text" name="penjualan_detail_qty[1]" id="penjualan_detail_qty_1" onkeyup="countRow('1')" value="1">
									<input class="form-control number" type="hidden" name="penjualan_detail_qty_barang[1]" id="penjualan_detail_qty_barang_1">
								</td>
								<td>
									<input class="form-control disc" type="text" name="penjualan_detail_potongan_persen[1]" id="penjualan_detail_potongan_persen_1" onkeyup="countRow('1')">
									<input class="form-control number" type="hidden" name="penjualan_detail_potongan[1]" id="penjualan_detail_potongan_1">
								</td>
								<td><input class="form-control number jumlah" type="text" name="penjualan_detail_subtotal[1]" id="penjualan_detail_subtotal_1" readonly=""></td>
								<td><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus">
										<span class="la la-trash"></span> Hapus</a></td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td class="no-border text-right">Total Item</td>
								<td class="no-border"><input class="form-control" type="text" id="penjualan_total_item" name="penjualan_total_item" readonly=""></td>
								<td class="no-border text-right">Total Qty</td>
								<td class="no-border"><input class="form-control number" type="text" id="penjualan_total_qty" name="penjualan_total_qty" readonly=""></td>
								<td class="no-border text-right">Sub Total</td>
								<td class="no-border"><input class="form-control number" type="text" id="penjualan_total_harga" name="penjualan_total_harga" readonly=""></td>
								<td class="no-border"></td>
							</tr>
							<tr>
								<td colspan="5" class="no-border text-right">Potongan</td>
								<td class="no-border">
									<div class="input-group">
										<div class="kt-input-icon kt-input-icon--right" style="width: 38%;margin-right: 5px;">
											<input type="text" class="form-control number" id="penjualan_total_potongan_persen" name="penjualan_total_potongan_persen" onkeyup="countDiskon()">
											<span class="kt-input-icon__icon kt-input-icon__icon--right">
												<span>%</span>
											</span>
										</div>										
										<input type="text" class="form-control number" id="penjualan_total_potongan" name="penjualan_total_potongan" onkeyup="countDiskon()">
									</div>
								</td>
								<td class="no-border"></td>
							</tr>
							<tr>
								<td colspan="5" class="no-border text-right">Total Harga</td>
								<td class="no-border"><input class="form-control number total_harga" type="text" id="penjualan_total_grand" name="penjualan_total_grand" readonly=""></td>
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
							<!-- <button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Simpan</button> -->
							<button type="button" onclick="onBayar()" class="btn btn-primary"><i class="flaticon2-check-mark"></i> Bayar <code>End</code></button>
							<button type="reset" class="btn btn-secondary" onclick="onBack()"><i class="flaticon2-cancel-music"></i> Batal</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal bd-example-modal-xl fade" id="modal-penjualan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Data Transaksi Penjualan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-checkable table-condensed" id="modal-penjualanbarang">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Tanggal</th>
							<th>Nasabah</th>
							<th>No Nasabah</th>
							<!-- <th>Jenis Penjualan</th> -->
							<th>Sub Total</th>
							<th>Potongan</th>
							<th>Grand Total</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Tanggal</th>
							<th>Nasabah</th>
							<th>No Nasabah</th>
							<!-- <th>Jenis Penjualan</th> -->
							<th>Sub Total</th>
							<th>Potongan</th>
							<th>Grand Total</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal bd-example-modal-xl fade" id="modal-barang" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Pencarian Barang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-checkable table-condensed" id="table-barang">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Nama Barang</th>
							<th>Kelompok Barang</th>
							<th>Sat. 1</th>
							<th>Harga 1</th>
							<th>Sat. 2</th>
							<th>Harga 2</th>
							<th>Stok</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Nama Barang</th>
							<th>Kelompok Barang</th>
							<th>Sat. 1</th>
							<th>Harga 1</th>
							<th>Sat. 2</th>
							<th>Harga 2</th>
							<th>Stok</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal bd-example-modal-xl fade" id="modal-bayar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Pembayaran</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="kt-form" action="javascript:save('form-bayar')" name="form-bayar" id="form-bayar">
					<div class="kt-portlet__body">
						<div class="form-group row">
							<div class="col-6">								
								<div class="form-group row">
									<label for="barang_kategori_barang" class="col-4 col-form-label">Total Harga</label>
									<div class="col-7">
										<input class="form-control number total_harga" type="text" id="bayar_penjualan_total_grand" name="bayar_penjualan_total_grand" disabled="">
										<input class="form-control number total_harga" type="hidden" id="penjualan_total_bayar" name="penjualan_total_bayar" >
									</div>
								</div>
								<div class="form-group row">
									<label for="barang_kategori_barang" class="col-4 col-form-label">Bayar Tunai</label>
									<div class="col-7">
										<input class="form-control number" type="text" id="penjualan_total_bayar_tunai" name="penjualan_total_bayar_tunai" onkeyup="countDiskon()">
									</div>
								</div>
							</div>
							<div class="col-6">								
								<div class="form-group row">
									<div class="col-12" style="height: 75px;">
										<label for="penjualan_total_kembalian" style="position: absolute;padding: 20px;">Total Uang Kembali</label>
										<input class="form-control number" type="text" id="penjualan_total_kembalian" name="penjualan_total_kembalian" value="" style="height: 120%;font-size: 26px;background-color: #d4ebff" readonly="">
									</div>
								</div>
							</div>
						</div>
						
						<!-- 	<div class="col-1"></div>
						<label for="barang_kategori_barang" class="col-2 col-form-label">Uang Kembalian</label>
						<div class="col-4">
							<input class="form-control number" type="text" id="penjualan_total_kembalian" name="penjualan_total_kembalian" readonly="">
						</div>							
												</div> -->
						<div class="form-group row nasabah" style="display: none">
							<div class="col-4"><i class="flaticon-medal" style="font-size: 14px"></i> Nasabah</div>
						</div>

						<div class="form-group row nasabah" style="display: none">
							<div class="col-6">	
								<div class="form-group row">
									<label for="bayar" class="col-4 col-form-label">Titipan Belanja</label>
									<div class="col-7">
										<input class="form-control number" type="text" id="penjualan_total_bayar_voucher" name="penjualan_total_bayar_voucher" onkeyup="countDiskon()"> 
									</div>
								</div>	
								<div class="form-group row">
										<label for="barang_kategori_barang" class="col-4 col-form-label">Jumlah Kredit</label>
										<div class="col-7">
											<input class="form-control number" type="text" id="penjualan_total_kredit" name="penjualan_total_kredit" readonly="">
										</div>
								</div>
								<div class="form-group row">
									<label for="bayar" class="col-4 col-form-label">Jatuh Tempo</label>
									<div class="col-7">
										<input class="form-control" type="date" id="penjualan_jatuh_tempo" name="penjualan_jatuh_tempo">
									</div>
								</div>	
							</div>
							<div class="col-6">	
								<div class="form-group row">	
									<label for="bayar" class="col-12 col-form-label voucher" id="sisa_saldo">Sisa saldo : </label>
								</div>												
								<div class="form-group row">													
									<label for="barang_kategori_barang" class="col-3 col-form-label">Cicilan</label>
									<div class="col-9">				
										<div class="input-group">
											<div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
												<input type="text" class="form-control number" id="penjualan_total_cicilan_qty" name="penjualan_total_cicilan_qty" onkeyup="countCicilan()" value="1">
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>x</span>
												</span>
											</div>	
												<!-- <input type="text" class="form-control number" id="penjualan_total_cicilan_qty" name="penjualan_total_cicilan_qty" onkeyup="countCicilan()" value="1"> -->
											<div class="kt-input-icon kt-input-icon--right" style="width: 48%;margin-right: 5px;">
												<input type="text" class="form-control disc" id="penjualan_total_jasa" name="penjualan_total_jasa" onkeyup="countCicilan()">																	
												<span class="kt-input-icon__icon kt-input-icon__icon--right">
													<span>%</span>
												</span>
											</div>				
											<input type="hidden" class="form-control number" id="penjualan_total_cicilan" name="penjualan_total_cicilan">
										</div>
									</div>
								</div>													
								<div class="form-group row">													
									<label for="barang_kategori_barang" class="col-3 col-form-label">Potongan</label>
									<div class="col-4">				
										<select name="penjualan_jenis_potongan" id="penjualan_jenis_potongan" class="form-control">
											<option value="0">Tunai</option>
											<option value="1">Potongan Gaji</option>
										</select>
									</div>
									<label for="bayar" class="col-5 col-form-label" id="bulan_cicil"></label>
								</div>	
							</div>
						</div>
					</div>
					<hr>
					<div class="kt-portlet__foot">
						<div class="kt-form__actions">
							<div class="row">
								<div class="col-2" style="padding-top: 8px;">					
									<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
										<input type="checkbox" name="cetak" id="cetak" checked="checked" value="1"> <i class="flaticon2-print"></i> Cetak
										<span></span>
									</label>
								</div>
								<!-- <div class="col-2"></div> -->
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
<div id="printArea" style="display: none;"></div>

<?php view(['table', 'javascript']); ?>