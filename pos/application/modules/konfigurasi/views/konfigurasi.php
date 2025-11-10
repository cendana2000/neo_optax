<div class="kt-portlet kt-portlet--mobile form_data">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">Form Konfigurasi Aplikasi</h3>
		</div>
	</div>

	<!--begin::Form-->
	<form class="kt-form" name="form-konfigurasi" id="form-konfigurasi" action="javascript:save()">
		<div class="kt-portlet__body">
			<div class="kt-section kt-section--first">
				<h3 class="kt-section__title">1. Informasi Perusahaan:</h3>
				<div class="kt-section__body">
					<div class="form-group row">
						<label class="col-lg-3 col-form-label" for="konfigurasi_perusahaan_nama">Nama Perusahaan/Cabang:</label>
						<div class="col-lg-6">
							<input type="hidden" class="form-control" name="konfigurasi_id" id="konfigurasi_id">
							<input type="text" class="form-control" name="konfigurasi_perusahaan_nama" id="konfigurasi_perusahaan_nama">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label" for="konfigurasi_perusahaan_alamat">Alamat</label>
						<div class="col-lg-6">
							<textarea class="form-control" name="konfigurasi_perusahaan_alamat" id="konfigurasi_perusahaan_alamat"></textarea>
						</div>
						<div class="col-lg-3">
							<div class="kt-input-icon kt-input-icon--left">
								<input type="text" class="form-control" name="konfigurasi_perusahaan_telp" id="konfigurasi_perusahaan_telp">
								<span class="kt-input-icon__icon kt-input-icon__icon--left">
									<span>Telp:</span>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>
				<h3 class="kt-section__title">2. Setting USP:</h3>
				<div class="kt-section__body">
					<div class="form-group row">
						<label class="col-lg-3 col-form-label" for="konfigurasi_jasa_msk">Jasa Simp. Manasuka :</label>
						<div class="col-lg-3">
							<div class="kt-input-icon kt-input-icon--right">
								<input type="text" class="form-control disc" name="konfigurasi_jasa_msk" id="konfigurasi_jasa_msk">
								<span class="kt-input-icon__icon kt-input-icon__icon--right">
									<span>%</span>
								</span>
							</div>
						</div>
						<label class="col-lg-3 col-form-label" for="konfigurasi_jasa_swk">Jasa Simp. SWK :</label>
						<div class="col-lg-3">
							<div class="kt-input-icon kt-input-icon--right">
								<input type="text" class="form-control disc" name="konfigurasi_jasa_swk" id="konfigurasi_jasa_swk">
								<span class="kt-input-icon__icon kt-input-icon__icon--right">
									<span>%</span>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label" for="konfigurasi_jasa_tht">Jasa Simp. THT</label>
						<div class="col-lg-3">
							<div class="kt-input-icon kt-input-icon--right">
								<input type="text" class="form-control disc" name="konfigurasi_jasa_tht" id="konfigurasi_jasa_tht">
								<span class="kt-input-icon__icon kt-input-icon__icon--right">
									<span>%</span>
								</span>
							</div>
						</div>
						<label class="col-lg-3 col-form-label" for="konfigurasi_jasa_simp_khusus">Jasa Simp. Khusus</label>
						<div class="col-lg-3">
							<div class="kt-input-icon kt-input-icon--right">
								<input type="text" class="form-control disc" name="konfigurasi_jasa_simp_khusus" id="konfigurasi_jasa_simp_khusus">
								<span class="kt-input-icon__icon kt-input-icon__icon--right">
									<span>%</span>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label" for="konfigurasi_jasa_pinjaman">Jasa Pinjaman :</label>
						<div class="col-lg-3">
							<div class="kt-input-icon kt-input-icon--right">
								<input type="text" class="form-control disc" name="konfigurasi_jasa_pinjaman" id="konfigurasi_jasa_pinjaman">
								<span class="kt-input-icon__icon kt-input-icon__icon--right">
									<span>%</span>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>
				<h3 class="kt-section__title">3. Setting Talangan Haji:</h3>
				<div class="kt-section__body">
					<div class="form-group row">
						<label class="col-lg-3 col-form-label" for="konfigurasi_jml_talangan">Maksimal Pinjaman:</label>
						<div class="col-lg-3">
							<input type="text" class="form-control number" name="konfigurasi_jml_talangan" id="konfigurasi_jml_talangan">
						</div>
						<label class="col-lg-3 col-form-label" for="konfigurasi_jml_porsi">Maksimal Porsi :</label>
						<div class="col-lg-3">
							<div class="kt-input-icon kt-input-icon--right">
								<input type="number" class="form-control" name="konfigurasi_jml_porsi" id="konfigurasi_jml_porsi">
								<span class="kt-input-icon__icon kt-input-icon__icon--right">
									<span>orang</span>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label" for="konfigurasi_jml_tenor">Tenor Maksimal :</label>
						<div class="col-lg-3">
							<div class="kt-input-icon kt-input-icon--right">
								<input type="number" class="form-control" name="konfigurasi_jml_tenor" id="konfigurasi_jml_tenor">
								<span class="kt-input-icon__icon kt-input-icon__icon--right">
									<span>bulan</span>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>
				<h3 class="kt-section__title">4. Setting POS:</h3>
				<div class="kt-section__body">
					<div class="form-group row">
						<label class="col-lg-3 col-form-label" for="konfigurasi_gudang_id">Gudang :</label>
						<div class="col-lg-3">
							<select class="form-control" name="konfigurasi_gudang_id" id="konfigurasi_gudang_id" disabled=""></select>
						</div>
					</div>
				</div>
				<div class="kt-section__body">
					<div class="form-group row">
						<div class="col-lg-3">
							<label for="kasir" style="display: block;">Kasir :</label>
						</div>
						<div class="col-lg-9">
							<div class="form-group">
								<button type="button" class="btn btn-outline-info" onclick="addKasir()"><i class="fa fa-plus" style="text-align: center;"></i>Tambah</button>
								
							</div>
							<table class="table table-bordered" id="table-kasir">
								<thead>
									<tr>
										<th>Alamat IP</th>
										<th>Nama Kasir</th>
										<th style="width: 20%">Kode</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<tr id="kasir_0">
										<td><input type="text" class="form-control kasir_ip" id="kasir_ip_0" name="kasir_ip[0]"></td>
										<td><input type="text" class="form-control" id="kasir_nama_0" name="kasir_nama[0]"></td>
										<td><input type="text" class="form-control" id="kasir_kode_0" name="kasir_kode[0]"></td>
										<td><a href="javascript:;" data-id="0" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus">
			                                            <span class="la la-trash"></span> Hapus</a></td>
									</tr>
								</tbody>
							</table>

						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<div class="row">
					<div class="col-lg-3"></div>
					<div class="col-lg-6">
						<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Simpan</button>
						<button type="reset" class="btn btn-secondary" onclick="onBack()"><i class="flaticon2-cancel-music"></i> Batal</button>
					</div>
				</div>
			</div>
		</div>
	</form>

		<!--end::Form-->
</div>


<?php view(['javascript']); ?>