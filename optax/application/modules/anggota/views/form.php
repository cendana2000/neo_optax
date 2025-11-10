<div class="row form_data" style="display: none">
	<div class="col-md-12">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Form Anggota/Nasabah
					</h3>
				</div>
			</div>
			<form class="kt-form" action="javascript:save('form-anggota')" name="form-anggota" id="form-anggota">
				<input type="hidden" id="anggota_id" name="anggota_id">
				<div class="kt-portlet__body">
					<div class="form-group row">
						<label for="anggota_kode" class="col-2 col-form-label">Kode Anggota</label>
						<div class="col-4">
							<input type="text" class="form-control" id="anggota_kode" name="anggota_kode" onchange="isRedundant()">
						</div>
					</div>
					<div class="form-group row">
						<label for="anggota_nip" class="col-2 col-form-label">NIP</label>
						<div class="col-4">
							<input type="text" class="form-control" id="anggota_nip" name="anggota_nip">
						</div>
						<label for="anggota_nama" class="col-2 col-form-label">Nama Anggota</label>
						<div class="col-4">
							<input type="text" class="form-control" id="anggota_nama" name="anggota_nama">
						</div>
					</div>
					<div class="form-group row">
						<label for="anggota_jk" class="col-2 col-form-label">Jenis Kelamin</label>
						<div class="col-4">
							<select class="form-control" id="anggota_jk" name="anggota_jk" style="width: 100%">
								<option value="" selected="">Pilih</option>
								<option value="L">Laki-laki</option>
								<option value="P">Perempuan</option>
							</select>
						</div>
						<label for="anggota_tgl_lahir" class="col-2 col-form-label">Tanggal Lahir</label>
						<div class="col-4">
							<input type="date" class="form-control" id="anggota_tgl_lahir" name="anggota_tgl_lahir">
						</div>
					</div>
					<div class="form-group row">
						<label for="anggota_grup_gaji" class="col-2 col-form-label">Grup Gaji</label>
						<div class="col-4">
							<select class="form-control" id="anggota_grup_gaji" name="anggota_grup_gaji" style="width: 100%"></select>
						</div>
						<label for="anggota_kelompok" class="col-2 col-form-label">Kelompok Anggota</label>
						<div class="col-4">
							<select class="form-control" id="anggota_kelompok" name="anggota_kelompok" style="width: 100%"></select>
						</div>
					</div>
					<div class="form-group row">
						<label for="anggota_pekerjaan" class="col-2 col-form-label">Pekerjaan</label>
						<div class="col-4">
							<!-- <select class="form-control" id="anggota_pekerjaan" name="anggota_pekerjaan" style="width: 100%">
							</select> -->
							<input type="text" class="form-control" id="anggota_pekerjaan" name="anggota_pekerjaan">
						</div>
						<label for="anggota_telp" class="col-2 col-form-label">No Telp</label>
						<div class="col-4">
							<input type="text" class="form-control" id="anggota_telp" name="anggota_telp">
						</div>
					</div>
					<div class="form-group row">
						<label for="anggota_tgl_gabung" class="col-2 col-form-label">Tanggal Masuk</label>
						<div class="col-4">
							<input type="date" class="form-control" id="anggota_tgl_gabung" name="anggota_tgl_gabung">
						</div>
						<label for="anggota_tgl_keluar" class="col-2 col-form-label">Tanggal Keluar</label>
						<div class="col-4">
							<input type="date" class="form-control" id="anggota_tgl_keluar" name="anggota_tgl_keluar">
						</div>
					</div>
					<div class="form-group row">
						<label for="anggota_kota" class="col-2 col-form-label">Kota/Kabupaten</label>
						<div class="col-4">
							<select class="form-control" id="anggota_kota" name="anggota_kota" style="width: 100%" onchange="onKecamatan(this)"></select>
						</div>
						<label for="anggota_kecamatan" class="col-2 col-form-label">Kecamatan</label>
						<div class="col-4">
							<select class="form-control" id="anggota_kecamatan" name="anggota_kecamatan" style="width: 100%" onchange="onKelurahan(this)"></select>
						</div>
					</div>
					<div class="form-group row">
						<label for="anggota_kelurahan" class="col-2 col-form-label">Desa/Kelurahan</label>
						<div class="col-4">
							<select class="form-control" id="anggota_kelurahan" name="anggota_kelurahan" style="width: 100%"></select>
						</div>
						<label for="anggota_alamat" class="col-2 col-form-label">Alamat</label>
						<div class="col-4">
							<textarea class="form-control" id="anggota_alamat" name="anggota_alamat"></textarea>
						</div>
					</div>
					<div class="form-group row" style="display:none;" id="updated">
						
					</div>
					<div class="form-group row">
						<label for="anggota_is_aktif" class="col-2 col-form-label">Status Aktif</label>
						<div class="col-4">
							<select class="form-control" id="anggota_is_aktif" name="anggota_is_aktif" style="width: 100%">
								<option value="" selected="">Aktif</option>
								<option value="Y">Tidak Aktif</option>
							</select>
						</div>
						<label for="anggota_is_proteksi" class="col-2 col-form-label">Proteksi</label>
						<div class="col-4">
							<select class="form-control" id="anggota_is_proteksi" name="anggota_is_proteksi" style="width: 100%">
								<option value="0" selected="">Tidak</option>
								<option value="1">Ya</option>
							</select>
						</div>
					</div>
					<!-- <div class="form-group row">
						<label for="anggota_user" class="col-2 col-form-label">Email</label>
						<div class="col-4">
							<input type="text" class="form-control" id="anggota_user" name="anggota_user">
						</div>
						<label for="anggota_password" class="col-2 col-form-label">Password</label>
						<div class="col-4">
							<input type="password" class="form-control" id="anggota_password" name="anggota_password">
						</div>
					</div> -->
					<div class="form-group row" style="margin-top: 30px">
						<h5>Tagihan Simpanan Bulanan</h5>
					</div>
					<div class="table-responsive">
						<table class="table table-striped- table-bordered table-hover" id="table-tagihansimpanan" style="width: 80%; margin-left: 80px">
							<thead>
								<tr>
									<th style="width: 30%!important">Jenis Simpanan</th>
									<th style="width: 50%!important">Jumlah Tagihan</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Simpanan Pokok</td>
									<td><input type="text" class="form-control number simpanan" name="anggota_simp_pokok" id="anggota_simp_pokok" onkeyup="subTotal()"></td>
								</tr>
								<tr>
									<td>Simpanan Wajib</td>
									<td><input type="text" class="form-control number simpanan" name="anggota_simp_wajib" id="anggota_simp_wajib" onkeyup="subTotal()"></td>
								</tr>
								<tr>
									<td>Manasuka</td>
									<td><input type="text" class="form-control number simpanan" name="anggota_simp_manasuka" id="anggota_simp_manasuka" onkeyup="subTotal()"></td>
								</tr>
								<tr>
									<td>Simpanan Wajib Khusus</td>
									<td><input type="text" class="form-control number simpanan" name="anggota_simp_wajib_khusus" id="anggota_simp_wajib_khusus" onkeyup="subTotal()"></td>
								</tr>
								<tr>
									<td>Tabungan Hari Tua</td>
									<td><input type="text" class="form-control number simpanan" name="anggota_simp_tabungan_hari_tua" id="anggota_simp_tabungan_hari_tua" onkeyup="subTotal()"></td>
								</tr>
								<tr>
									<td>Titipan Belanja</td>
									<td><input type="text" class="form-control number simpanan" name="anggota_simp_titipan_belanja" id="anggota_simp_titipan_belanja" onkeyup="subTotal()"></td>
								</tr>
								<tr>
									<td>Total</td>
									<td><input type="text" class="form-control number" name="total_simpanan" id="total_simpanan"></td>
								</tr>
							</tbody>
						</table>
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
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- konten modal-->
		<div class="modal-content">
			<!-- heading modal -->
			<div class="modal-header">
				<h4 class="modal-title">Cari</h4>
				<button type="button" class="close" data-dismiss="modal"></button>
			</div>
			<!-- body modal -->
		<div class="modal-body" style="width: 100%">
			<form class="kt-form" name="filterAnggota" id="filterAnggota">
				<div class="form-group row">
					<div class="col-3">
						<label for="periode" class="col-form-label" style="width: 100px;">Anggota</label>
						<select class="form-control" style="width: 100%;" name="jenis_nasabah" id="jenis_nasabah">
							<option value="Baru">Baru</option>
							<option value="Keluar">Keluar</option>
						</select>
					</div>
					<div class="col-4">
						<label for="tanggal_awal" class="col-form-label" style="width: 100px;">Dari Tanggal</label>
						<input type="date" class="form-control" name="tanggal_awal" id="tanggal_awal" value="<?=date('Y-m-d') ?>" >
					</div>

					<div class="col-4">
						<label for="tanggal_akhir" class="col-form-label">Sampai tanggal</label>
						<input type="date" class="form-control" name="tanggal_akhir" id="tanggal_akhir" value="<?=date('Y-m-d') ?>">
					</div>
				
				</div>
			</form>
		</div>
			<!-- footer modal -->
			<div class="modal-footer row">
				<div class="col-4">
					<button type="button" onclick="loadPreview()" class="btn btn-success" data-dismiss="modal"><i class="la la-print"></i> PDF</button>
				</div>
				<div class="col-4">
					<button type="button" onclick="loadPrint()" class="btn btn-warning" data-dismiss="modal"><i class="la la-print"></i> Excel</button>
				</div>
			</div>
		</div>	
	</div>
</div>

<div class="kt-portlet kt-portlet--mobile form_data2" style="display:none;">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Daftar Nasabah
			</h3>
		</div>
	</div>
	<div class="kt-form">
		<div class="kt-portlet__body form" id="pdf-laporan">
            <object data="" type="application/pdf" width="100%" height="500px"></object>
		</div>
	</div>
</div>