<style type="text/css">
	.text-hitam{
		color: #050505
	}
</style>
<div class="row form_data" style="display: none">
	<div class="col-md-12">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Form Jurnal
					</h3>
				</div>
			</div>
			<form class="kt-form" action="javascript:save('form-jurnal')" name="form-jurnal" id="form-jurnal">
				<input type="hidden" id="jurnal_umum_id" name="jurnal_umum_id">
	            <input type="hidden" id="jurnal_umum_details" name="jurnal_umum_details">
	            <!-- <input type="hidden" id="jurnal_umum_total" name="jurnal_umum_total"> -->
	            <input type="hidden" id="jurnal_umum_closed" name="jurnal_umum_closed">
	            <input type="hidden" id="jurnal_umum_is_penyesuaian" name="jurnal_umum_is_penyesuaian">	            
				<div class="kt-portlet__body">
					<div class="form-group row">
						<label for="jurnal_umum_tanggal" class="col-2 col-form-label">Tanggal</label>
						<div class="col-3">
							<input type="date" class="form-control" id="jurnal_umum_tanggal" name="jurnal_umum_tanggal" value="<?php echo date('Y-m-d')?>">
						</div>
						<div class="col-1"></div>
						<label for="jurnal_umum_nobukti" class="col-2 col-form-label">No Transaksi</label>
						<div class="col-4">
							<input class="form-control" id="jurnal_umum_nobukti" name="jurnal_umum_nobukti" placeholder="BU.#" >
						</div>
					</div>
					<div class="form-group row">						
						<!-- <label for="jurnal_umum_unit" class="col-2 col-form-label">Unit</label>
						<div class="col-3">
							<select class="form-control" id="jurnal_umum_unit" name="jurnal_umum_unit" style="width: 100%" onchange="changeUnit()">
							</select>
						</div> -->
						<!-- <label for="jurnal_umum_lawan_transaksi" class="col-2 col-form-label">Penerima/Diterima</label>
						<div class="col-4">
							<select class="form-control" id="jurnal_umum_lawan_transaksi" name="jurnal_umum_lawan_transaksi" onchange="setPenerima()"></select>
							<input type="hidden" name="jurnal_umum_penerima" id="jurnal_umum_penerima">
						</div> -->
					</div>
					<div class="form-group row">						
						<label for="jurnal_umum_keterangan" class="col-2 col-form-label">Keterangan</label>
						<div class="col-10">
							<textarea class="form-control" id="jurnal_umum_keterangan" name="jurnal_umum_keterangan"></textarea>
						</div>
					</div>
					<!-- <div class="form-group row">
						<div class="kt-portlet kt-portlet--solid-success kt-portlet--height-fluid">
							<div class="kt-portlet__head" style="height: 40px">
								<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">Input Detail</h3>
								</div>
							</div>
							<div class="kt-portlet__body" style="background: #e1e1ef">
									<div class="form-group row text-hitam">
										<input name="jurnal_umum_detail_id" id="jurnal_umum_detail_id" type="hidden">
										<label for="jurnal_umum_detail_tipe" class="col-2 col-form-label">Tipe</label>
										<div class="col-4">
											<select class="form-control" id="jurnal_umum_detail_tipe" name="jurnal_umum_detail_tipe">
												<option value="debit">Debit</option>
												<option value="kredit">Kredit</option>
											</select>
										</div>
										<label for="jurnal_umum_detail_akun" class="col-2 col-form-label">Akun</label>
										<div class="col-4">
											<select class="form-control" id="jurnal_umum_detail_akun" name="jurnal_umum_detail_akun"></select>
										</div>
									</div>
									<div class="form-group row text-hitam">
										<label for="jurnal_umum_detail_total" class="col-2 col-form-label">Total</label>
										<div class="col-4">
											<input class="form-control number" type="text" id="jurnal_umum_detail_total" name="jurnal_umum_detail_total">
										</div>
										<label for="jurnal_umum_detail_lawan_transaksi" class="col-2 col-form-label">Lawan Transaksi</label>
										<div class="col-4">
											<select class="form-control" id="jurnal_umum_detail_lawan_transaksi" name="jurnal_umum_detail_lawan_transaksi"></select>
										</div>
									</div>
									<div class="form-group row text-hitam">
										<label for="jurnal_umum_detail_uraian" class="col-2 col-form-label">Uraian</label>
										<div class="col-8">
											<input class="form-control" type="text" id="jurnal_umum_detail_uraian" name="jurnal_umum_detail_uraian">
										</div>
										<button type="button" class="col-2 btn btn-primary" onclick="onBtnTambah_Click()"><i class="flaticon-add"></i> Tambah Detail</button>
									</div>
							</div>
						</div>
					</div> -->
					<div class="form-group row">
						<div class="col-3">
							<button type="button" class="btn btn-outline-info" onclick="addDetail()" style="margin-left: 10px;width: 51%;"><i class="fa fa-plus" style="text-align: center;"></i>Tambah</button>
						</div>
					</div>
					<div class="form-group row">
						<div class="table-responsive">
							<table class="table table-bordered table-hovered" id="table-details">
								<thead>
									<tr>
										<td style="width: 30%">Akun</td>
										<!-- <td style="width: 15%">Lawan Transaksi</td> -->
										<td style="width: 15%">Keterangan</td>
										<td style="width: 13%">Debit</td>
										<td style="width: 13%">Kredit</td>
										<td style="width: 1%"></td>
									</tr>
								</thead>
								<tbody>
									<tr class="detail_0">
										<td><select class="form-control" id="jurnal_umum_detail_akun_0" name="jurnal_umum_detail_akun[0]" data-id="0" style="width: 100%" onchange="getTotal()"></select></td>
										<!-- <td><select class="form-control" id="jurnal_umum_detail_lawan_transaksi_0" name="jurnal_umum_detail_lawan_transaksi[0]"></select></td> -->
										<td><input type="text" class="form-control" id="jurnal_umum_detail_uraian_0" name="jurnal_umum_detail_uraian[0]" data-id="0"></td>
										<td><input type="text" class="form-control number detail_debit" id="jurnal_umum_detail_debit_0" name="jurnal_umum_detail_debit[0]" data-id="0" onkeyup="getTotal()"></td>
										<td><input type="text" class="form-control number detail_kredit" id="jurnal_umum_detail_kredit_0" name="jurnal_umum_detail_kredit[0]" data-id="0" onkeyup="getTotal()"></td>
										<td><a href="javascript:;" data-id="0" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus"><span class="la la-trash"></span></a></td>
									</tr>

									<!-- <tr id="blank">
													                      <td colspan="6" style="text-align: center">
													                        No data available in table
													                      </td>
													                    </tr> -->
								</tbody>
								<tfoot>
				                    <tr>
				                      <td colspan="2" style="text-align: center; padding: 14px 6px; font-weight: 500;">Total <input type="hidden" name="jurnal_umum_total" id="jurnal_umum_total"></td>
				                      <td style="padding: 14px 6px; font-weight: 500;">Rp. <span id="total_jurnal_debit" style="float: right;margin-right: 12px"></span><input type="hidden" name="jurnal_umum_total_debit" id="jurnal_umum_total_debit"></td>
				                      <td style="padding: 14px 6px; font-weight: 500;border-right: none;">Rp. <span id="total_jurnal_kredit" style="float: right;margin-right: 12px"></span><input type="hidden" name="jurnal_umum_total_kredit" id="jurnal_umum_total_kredit"></td>
				                      <td style="border-left: none;"></td>
				                    </tr>
			                  	</tfoot>
							</table>
						</div>
					</div>
				</div>
				<div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-2" style="padding-top: 8px;">					
								<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
									<input type="checkbox" name="cetak" id="cetak" checked="checked" value="1"> <i class="flaticon2-print"></i> Cetak
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
	</div>
</div>