<div class="row form_data" style="display: none">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM PEGAWAI</h3>
				</div>
			</div>
			<div class="card-body">
				<form class="kt-form" action="javascript:save('form-pegawai')" name="form-pegawai" id="form-pegawai">
					<input type="hidden" id="pegawai_id" name="pegawai_id">
					<div class="form-group row">
						<label for="pegawai_nik" class="col-md-2 col-form-label">NIK Pegawai</label>
						<div class="col-md-4">
							<input type="number" class="form-control" id="pegawai_nik" name="pegawai_nik">
						</div>
						<label for="pegawai_nama" class="col-md-2 col-form-label">Nama Pegawai</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="pegawai_nama" name="pegawai_nama">
						</div>
					</div>
					<div class="form-group row">
						<label for="pegawai_jk" class="col-md-2 col-form-label">Jenis Kelamin</label>
						<div class="col-md-4">
							<select class="form-control" id="pegawai_jk" name="pegawai_jk" style="width: 100%">
								<option value="" selected="">-Pilih Jenis Kelamin-</option>
								<option value="L">Laki-laki</option>
								<option value="P">Perempuan</option>
							</select>
						</div>
						<label for="pegawai_alamat" class="col-md-2 col-form-label">Alamat</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="pegawai_alamat" name="pegawai_alamat">
						</div>

					</div>
					<div class="form-group row">
						<label for="pegawai_hp" class="col-md-2 col-form-label">No Handphone</label>
						<div class="col-md-4">
							<input type="number" class="form-control" id="pegawai_hp" name="pegawai_hp">
						</div>
					</div>
					<div class="form-group row">
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-md-4 text-left">
								<!-- <button type="button" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-arrow-left"></i> Back</button> -->
								<button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>

							</div>
							<div class="col-8 text-right">
								<button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Simpan</button>
								<!-- <button type="submit" class="btn btn-sm btn-success"><i class="flaticon2-cancel-music"></i> Save</button> -->
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>