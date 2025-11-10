<div class="row form_data" style="display: none" data-responden="0">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM SURVEY</h3>
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
					</div>
				</div>
			</div>
			<form action="javascript:save('form-survey')" method="post" id="form-survey" name="form-survey" autocomplete="off">
			<!--BODY FORM SURVEY  -->
			<div class="card-body">
				<div class="row justify-content-between">
					<div class="col-sm-12 col-md-5">
						<div class="form-group row">
							<label for="survey_judul" class="col-3 col-form-label">Judul</label>
							<div class="col-9">
								<input type="hidden" id="survey_id" name="survey_id"/>
								<input class="form-control" type="text" id="survey_judul" onchange="fieldChange(this)" name="survey_judul" oninvalid="fieldInvalid(this)" placeholder="Ketikan data"/>
								<div class="invalid-feedback">Bidang ini wajib disi</div>
							</div>
						</div>
						<div class="form-group row">
							<label for="survey_tgl_publish" class="col-3 col-form-label">Tgl Publish</label>
							<div class="col-9">
								<div class="input-group date">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="la la-calendar-check-o"></i>
										</span>
									</div>
									<input type="text" class="form-control datepicker" id="survey_tgl_publish" name="survey_tgl_publish" readonly placeholder="Pilih tanggal"/>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label for="survey_tgl_selesai" class="col-3 col-form-label">Tgl Selesai</label>
							<div class="col-9">
								<div class="input-group date">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="la la-calendar-check-o"></i>
										</span>
									</div>
									<input type="text" class="form-control datepicker" id="survey_tgl_selesai" name="survey_tgl_selesai" readonly  placeholder="Pilih tanggal"/>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-3 col-form-label">Pengaturan Responden</label>
							<div class="col-9">
								<div class="checkbox-list py-3">
									<label class="checkbox checkbox-success">
										<input type="checkbox" name="survey_pengaturan_nama" id="survey_pengaturan_nama"/>
										<span></span>
										Tampilkan Nama
									</label>
									<label class="checkbox checkbox-success">
										<input type="checkbox" name="survey_pengaturan_email" id="survey_pengaturan_email"/>
										<span></span>
										Tampilkan Email
									</label>
									<label class="checkbox checkbox-success">
										<input type="checkbox" name="survey_pengaturan_alamat" id="survey_pengaturan_alamat"/>
										<span></span>
										Tampilkan Alamat
									</label>
								</div>
								<span class="text-muted font-size-sm">Centang jika ingin ditampilkan.</span>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6">
						<div class="form-group row">
							<label for="survey_status" class="col-3 col-form-label">Status</label>
							<div class="col-9">
								<select class="form-control select2" name="survey_status" id="survey_status">
									<option value="1">Aktif</option>
									<option value="0">Tidak Aktif</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="survey_banner" class="col-3 col-form-label">Banner</label>
							<div class="col-9">
								<div class="d-flex flex-row">
									<div class="custom-file mr-2">
										<input type="file" class="custom-file-input" accept=".png, .jpg, .jpeg" id="survey_banner" name="survey_banner" onchange="onChangeBanner(this)">
										<label id="title-banner" class="custom-file-label" for="banner">Choose file</label>
									</div>
									<div class="btn btn-light border" style="color: #3F4254;" data-toggle="modal" data-target="#modal-preview">Preview</div>
								</div>
								<span class="font-size-sm text-muted">Max 2 MB, Allowed file types: png, jpg, jpeg.</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="survey_deskripsi" class="col-3 col-form-label">Deskripsi</label>
							<div class="col-9">
								<textarea class="form-control" rows="7" id="survey_deskripsi" name="survey_deskripsi" placeholder="Masukkan Deskripsi"></textarea>
							</div>
						</div>
					</div>
				</div>
      	</div>
	  		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">INPUT PERTANYAAN</h3>
				</div>
			</div>
			</div>
			<!--BODY INPUT PERTANYAAN  -->
			<div class="card-body pt-0" id="place_pertanyaan">
				<div class="card card-custom border mt-8" id="item_pertanyaan_0">
					<div class="card-body">
						<!-- FIELD PERTANYAAN JUDUL & TYPE -->
						<div class="row align-items-center">
							<div class="col-1 text-center">
								<span class="badge badge-success font-size-lg" id="no_pertanyaan_0">1</span>
							</div>
							<div class="col-11">
								<span class="font-weight-bold font-size-lg">Pertanyaan</span>
							</div>
							<div class="col-11 offset-1 row mt-3">
							<input type="hidden" name="row[]" value="0"/>
							<input type="hidden" name="id_pertanyaan[]" id="id_pertanyaan_0" value=""/>
								<input class="form-control col-8" name="judul_pertanyaan[]" id="judul_pertanyaan_0" placeholder="Ketikan Pertanyaan"/>
								<div class="col-4 pr-0">
									<select class="form-control select2" name="tipe_pertanyaan[]" id="tipe_pertanyaan_0" onchange="changeTipePertanyaan(0, 0, this)">
										<option value="0">Pilihan Tunggal</option>
										<option value="1">Pilihan Ganda</option>
										<option value="2">Paragraf</option>
									</select> 
								</div>
							</div>
						</div>
						<!-- END FIELD PERTANYAAN JUDUL & TYPE -->
						<!-- FIELD JAWABAN -->
						<div class="row mt-3" id="jawaban_0">
							<div class="col-11 offset-1 row mb-3">
								<div class="col-8 d-flex flex-row justify-content-center px-0">
									<span class="jawaban_number border rounded mr-3 text-muted" style="padding:8px 12px;">A.</span>
									<input class="form-control mr-3" style="width:60px;	" type="text" name="nilai[0][]" id="nilai_0_0" onkeyup="numberOnly(this)" placeholder="Nilai"/>
									<input type="hidden" name="id_opsi[0][]" id="id_opsi_0_0" value=""/>
									<div class="input-group">
										<input class="form-control" name="jawaban[row_0][]" id="jawaban_0_0" placeholder="Masukan Jawaban"/>
										<div class="input-group-append">
											<span class="input-group-text bg-white" role="button" id="delopsi_0_0" onclick="delOpsi(0, 0, this)">
												<i class="la la-close text-danger"></i>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- END JAWABAN -->
						<!-- FIELD ADD PILIHAN -->
						<div class="row" id="bawah_jawaban_0">
							<div class="col-11 offset-1 row">
								<div class="col-8 d-flex flex-row justify-content-center px-0">
									<span role="button" onclick="addOpsi(0 , this)" data-rows="1" id="addopsi_icon_0" class="rounded mr-3 text-muted" style="padding:8px 12px;border: 1px dashed #1BC5BD;"><i class="fas fa-plus icon-sm text-success"></i></span>
									<span role="button" onclick="addOpsi(0 , this)" data-rows="1" id="addopsi_0" class="form-control text-success" readonly style="padding:8px 12px;border: 1px dashed #1BC5BD;">Tambah opsi</span>
								</div>
							</div>
						</div>
						<!-- END ADD PILIHAN -->
					</div>
					<div class="card-footer d-flex flex-row justify-content-end">
						<span class="far fa-copy icon-lg mr-5" role="button" onclick="handleDuplicate(0, this)" id="duplicate_pertanyaan_0" data-toggle="tooltip" data-placement="top" title="Duplicate"></span>
						<span class="fa fa-trash icon-lg mr-5" role="button" onclick="delPertanyaan(0, this)" id="hapus_pertanyaan_0" data-toggle="tooltip" data-placement="top" title="Hapus"></span>
						<span class="text-muted mr-5 font-size-lg">|</span>
						<label class="checkbox checkbox-success">
							<input type="hidden" name="checkwajib[]" value="N" id="checkhide_0"/>
							<input type="checkbox" name="checkwajib[]" value="Y" id="checkshow_0" onchange="changeWajibCheck(0, this)"/>
							<span></span>&nbsp;&nbsp;
							Wajib diisi
						</label>
					</div>
				</div>
			</div>
			<div class="card-body d-flex justify-content-center pt-0">
				<span role="button" class="form-control text-success" onclick="addPertanyaan(this)" id="add_pertanyaan" data-rows="1" style="padding:8px 12px;border: 1px dashed #1BC5BD;"><i class="fa fa-plus icon-sm text-success"></i> Tambah Pertanyaan</span>
			</div>
			<div class="card-footer d-flex justify-content-end">
				<button type="button" onclick="onReset(this)" class="btn btn-light-warning mr-3"><i class="la la-close"></i> Batal</button>
				<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane"></i> Simpan</button>
			</div>
			</form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-preview" tabindex="-1" aria-labelledby="modal-previewLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-previewLabel">Preview Thumbnail</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center">
				<img src="assets/media/noimage.png" id="preview-image" class="img-fluid" alt="thumbnail preview" onerror="imgError(this);">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>