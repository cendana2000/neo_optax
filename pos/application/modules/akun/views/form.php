<div class="row form_data" style="display: none">	
	<div class="col-md-12">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Form Akun
					</h3>
				</div>
			</div>
			<form class="kt-form" action="javascript:save('form-akun')" name="form-akun" id="form-akun">
				<input type="hidden" id="akun_id" name="akun_id">
				<div class="kt-portlet__body">
					<div class="form-group row">						
						<label for="akun_parent" class="col-2 col-form-label">Akun Induk</label>
						<div class="col-3">
							<select type="text" id="akun_parent" name="akun_parent" style="width: 100%" onchange="setInduk()"></select>
						</div>
						<div class="col-1"></div>
					</div>
					<div class="form-group row">						
						<label for="akun_tipe" class="col-2 col-form-label">Akun Tipe</label>
						<div class="col-3">
							<select type="text" id="akun_tipe" name="akun_tipe" style="width: 100%">
								<option value="parent">Induk</option>
								<option value="detail">Detail</option>
							</select>
						</div>
						<div class="col-1"></div>	
					</div>
					<div class="form-group row">
						<label for="akun_kode" class="col-2 col-form-label">Kode Akun</label>
						<div class="col-3">
							<input class="form-control" type="text" id="akun_kode" name="akun_kode">
						</div>
					</div>					
					<div class="form-group row">
						<label for="akun_nama" class="col-2 col-form-label nama_akun" style="display: none;">Nama Akun</label>
						<div class="col-3 nama_akun" style="display: none;">
							<input class="form-control" type="text" id="akun_nama" name="akun_nama">
						</div>
						<label for="akun_bank_jenis_id" class="col-2 col-form-label bank_akun">Nama Bank</label>
						<div class="col-3 bank_akun">
							<select class="form-control" type="text" id="akun_bank_jenis_id" name="akun_bank_jenis_id" style="width: 100%" onchange="setNama()"></select>
						</div>
						<div class="col-1"></div>
						<label for="akun_bank_rekening" class="col-2 col-form-label bank_akun">No Rekening</label>
						<div class="col-3 bank_akun">
							<input type="text" class="form-control" type="text" id="akun_bank_rekening" name="akun_bank_rekening" onkeyup="setNama()">
						</div>
					</div>
					<!-- <div class="form-group row">
						<label class="col-2 col-form-label">Akun Kas/Bank</label>
						<div class="col-3">
							<span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--accent">
								<label>
									<input type="checkbox" name="akun_is_bank" id="akun_is_bank" onchange="setBank()" value="1">
									<span></span>
								</label>
							</span>
						</div>
					</div> -->
					<div class="form-group row">						
						<label for="akun_unit" class="col-2 col-form-label">Jenis Akun</label>
						<div class="col-3">
							<select type="text" id="akun_unit" name="akun_unit" style="width: 100%" class="form-control">
								<option value="pusat">Pusat</option>
								<option value="usp">USP</option>
							</select>
						</div>
						<div class="col-1"></div>	
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">Set As Kas/Bank</label>
						<div class="col-3">
							<span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--accent">
								<label>
									<input type="checkbox" name="akun_is_kas_bank" id="akun_is_kas_bank" value="1">
									<span></span>
								</label>
							</span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">Set As Payment</label>
						<div class="col-3">
							<span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--accent">
								<label>
									<input type="checkbox" name="akun_is_pembayaran" id="akun_is_pembayaran" value="1">
									<span></span>
								</label>
							</span>
						</div>
					</div>
				</div>

				<div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-12">
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
