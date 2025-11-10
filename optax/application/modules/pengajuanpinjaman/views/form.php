<style type="text/css">
	.kt-section .kt-section__desc {
		font-size: 1.1rem;
	}
</style>
<div class="kt-portlet kt-portlet--mobile form_data" style="display: none">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Form Pengajuan Pinjaman
			</h3>
		</div>
	</div>
	<form class="kt-form" action="javascript:save('form-pengajuan')" name="form-pengajuan" id="form-pengajuan">
		<input type="hidden" id="pengajuan_id" name="pengajuan_id">
		<div class="kt-portlet__body">
			<div class="kt-section">
				<div class="kt-section__desc">Data Pengajuan Pinjaman:</div>
				<div class="kt-section__content kt-section__content--border">					
					<div class="form-group row">
						<label for="pengajuan_no" class="col-2 col-form-label">Tanggal/Nomor</label>
						<div class="col-4">
							<div class="input-group">
								<input type="date" name="pengajuan_tgl" id="pengajuan_tgl" class="form-control" value="<?php echo date('Y-m-d') ?>">
								<input type="text" name="pengajuan_no" id="pengajuan_no" class="form-control" readonly="" placeholder="#AUTO">
							</div>
						</div>
						<label for="pengajuan_jenis" class="col-2 col-form-label">Jenis Pinjaman</label>
						<div class="col-4">
							<select name="pengajuan_jenis" id="pengajuan_jenis" class="form-control">
								<option value="U">Uang</option>
								<option value="R">Rumah</option>
							</select>
						</div>
					</div>	
					<div class="form-group row">
						<label for="pengajuan_jumlah_pinjaman" class="col-2 col-form-label">Jumlah Pinjaman</label>
						<div class="col-4">
							<input type="text" id="pengajuan_jumlah_pinjaman" name="pengajuan_jumlah_pinjaman" onchange="perhitunganAngsuran()" class="form-control number">
						</div>
						
						<label for="pengajuan_tenor" class="col-2 col-form-label">Periode Pinjaman</label>
						<div class="col-4">
							<div class="kt-input-icon kt-input-icon--right" style="width: 100%;margin-right: 10px;">
								<input type="text" class="form-control disc" id="pengajuan_tenor" name="pengajuan_tenor" style="" onchange="perhitunganAngsuran()">
								<span class="kt-input-icon__icon kt-input-icon__icon--right">
									<span> Bulan</span>
								</span>
								<input type="hidden" id="pengajuan_jasa" name="pengajuan_jasa">
								<input type="hidden" id="pengajuan_proteksi" name="pengajuan_proteksi">
								<input type="hidden" id="pengajuan_pokok_bulanan" name="pengajuan_pokok_bulanan">
								<input type="hidden" id="pengajuan_jasa_bulanan" name="pengajuan_jasa_bulanan">
							</div>	
						</div>
					</div>
					<div class="form-group row">
						<label for="pengajuan_keperluan_tunai" class="col-2 col-form-label">Keperluan</label>
						<div class="col-10">
							<input type="text" id="pengajuan_keperluan_tunai" name="pengajuan_keperluan_tunai" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="kt-section">
				<div class="kt-section__desc" style="margin-top: 10px">Data Nasabah:</div>
				<div class="kt-section__content kt-section__content--border">					
					<div class="form-group row">
						<label for="pengajuan_anggota" class="col-2 col-form-label">Anggota</label>
						<div class="col-4">
							<select class="form-control" name="pengajuan_anggota" id="pengajuan_anggota" style="width: 100%" onchange="showDataNasabah()"></select>
						</div>
						<label for="grup_gaji_nama" class="col-2 col-form-label">Grup Gaji</label>
						<div class="col-4">
							<input type="text" id="grup_gaji_nama" name="grup_gaji_nama" readonly="" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label for="anggota_nip" class="col-2 col-form-label">NIP</label>
						<div class="col-4">
							<input type="text" id="anggota_nip" name="anggota_nip" class="form-control">
						</div>
						<label for="anggota_tgl_lahir" class="col-2 col-form-label">Tgl Lahir</label>
						<div class="col-4">
							<input type="date" id="anggota_tgl_lahir" name="anggota_tgl_lahir" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label for="anggota_pekerjaan" class="col-2 col-form-label">Pekerjaan/Jabatan</label>
						<div class="col-4">
							<input type="text" id="anggota_pekerjaan" name="anggota_pekerjaan" class="form-control">
						</div>
						<label for="anggota_tgl_pensiun" class="col-2 col-form-label">Masa Pensiun</label>
						<div class="col-4">
							<input type="date" id="anggota_tgl_pensiun" name="anggota_tgl_pensiun" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label for="pengajuan_gaji_bersih" class="col-2 col-form-label">Gaji Bersih /Bulan</label>
						<div class="col-4">
							<input type="text" id="pengajuan_gaji_bersih" name="pengajuan_gaji_bersih" class="form-control number">
						</div>
						<label for="pengajuan_sisa_pinjaman_kpri" class="col-2 col-form-label">Sisa pinjaman di KPRI</label>
						<div class="col-4">
							<input type="text" id="pengajuan_sisa_pinjaman_kpri" name="pengajuan_sisa_pinjaman_kpri" class="form-control number">
						</div>
					</div>
					<div class="form-group row">
						<label for="anggota_alamat" class="col-2 col-form-label">Alamat</label>
						<div class="col-10">				
							<div class="input-group">
								<div class="kt-input-icon kt-input-icon--right" style="width: 70%;margin-right: 5px;">
									<input type="text" id="anggota_alamat" name="anggota_alamat" class="form-control" >
								</div>	
								<div class="kt-input-icon kt-input-icon--left" style="width: 28%;margin-right: 5px;">
									<input type="text" class="form-control disc" id="pengajuan_telp" name="pengajuan_telp" style="padding-left: 3.8rem">																	
									<span class="kt-input-icon__icon kt-input-icon__icon--left">
										<span>Telp : </span>
									</span>
								</div>				
								<input type="hidden" class="form-control number" id="penjualan_total_cicilan" name="penjualan_total_cicilan">
							</div>
						</div>
					</div>
				</div>
			</div>		
			<div class="kt-section acceptance">
				<div class="kt-section__desc" style="margin-top: 10px">Status Pinjaman:</div>
				<div class="kt-section__content kt-section__content--border">				
					<div class="form-group row">
						<label for="pengajuan_tgl_realisasi" class="col-2 col-form-label">Tgl Proses/Realisasi</label>
						<div class="col-4">
							<input type="date" name="pengajuan_tgl_realisasi" id="pengajuan_tgl_realisasi" class="form-control">
						</div>
						<label for="pengajuan_status" class="col-2 col-form-label">Disetujui</label>
						<div class="col-2">
							<select name="pengajuan_status" id="pengajuan_status" class="form-control">
								<!-- <option>Pilih</option> -->
								<option selected value="1">Ya</option>
								<option value="0">Tidak</option>
							</select>
						</div>
					</div>	
					<div class="form-group row">
						<!-- <label for="pengajuan_juru_bayar_id" class="col-2 col-form-label">Jurubayar</label>
						<div class="col-4">				
							<select name="pengajuan_juru_bayar_id" id="pengajuan_juru_bayar_id" class="form-control" style="width:100%"></select>
						</div> -->
						<label for="pengajuan_keterangan" class="col-2 col-form-label">Keterangan</label>
						<div class="col-4">				
							<textarea name="pengajuan_keterangan" id="pengajuan_keterangan" class="form-control"></textarea>
						</div>
					</div>	
				</div>
			</div>	
		</div>
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<div class="row">
					<div class="col-10">
						<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Simpan</button>
						<button type="reset" class="btn btn-secondary" onclick="onBack()"><i class="flaticon2-cancel-music"></i> Batal</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="kt-portlet kt-portlet--mobile form_data2" style="display:none;">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Lembar Permohonan Kredit
			</h3>
		</div>
	</div>
	<div class="kt-form">
		<div class="kt-portlet__body form" id="pdf-laporan">
            <object data="" type="application/pdf" width="100%" height="500px"></object>
		</div>
	</div>
</div>