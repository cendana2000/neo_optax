
<div class="kt-portlet kt-portlet--mobile form_data" style="display: none">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Form Barang
			</h3>
		</div>
	</div>
	<form class="kt-form" action="javascript:save('form-barang')" name="form-barang" id="form-barang">
		<input type="hidden" id="barang_id" name="barang_id">
		<div class="kt-portlet__body">			
			<div class="form-group row">
				<label for="barang_kategori_barang" class="col-2 col-form-label">Kelompok Barang</label>
				<div class="col-4">
					<select class="form-control" name="barang_kategori_barang" id="barang_kategori_barang" style="width: 100%"></select>
				</div>
			</div>
			<div class="form-group row">
				<label for="barang_kode" class="col-2 col-form-label">Kode Barang</label>
				<div class="col-4">
					<input class="form-control" type="text" id="barang_kode" name="barang_kode" placeholder=".###">
				</div>
			</div>
			<div class="form-group row">
				<label for="barang_nama" class="col-2 col-form-label">Nama Barang</label>
				<div class="col-6">
					<input class="form-control" type="text" id="barang_nama" name="barang_nama">
				</div>
			</div>
			<div class="form-group row">
				<label for="barang_barcode" class="col-2 col-form-label">Barcode Barang</label>
				<div class="col-4">
					<input class="form-control" type="text" id="barang_barcode" name="barang_barcode">
				</div>
			</div>	
			<div class="form-group row">
				<label for="barang_harga_beli" class="col-2 col-form-label">Harga Beli</label>
				<div class="col-2">
					<input type="text" class="form-control number" name="barang_harga_beli" id="barang_harga_beli" disabled="">
				</div>
				<label for="barang_harga_pokok" class="col-2 col-form-label">Harga Pokok</label>
				<div class="col-2">
					<input type="text" class="form-control number" name="barang_harga_pokok" id="barang_harga_pokok">
				</div>
				<label class="col-2 col-form-label">Keuntungan</label>
				<div class="col-2">
					<div class="kt-input-icon kt-input-icon--right">
						<input class="form-control number" type="text" id="barang_persen_untung" name="barang_persen_untung" onkeyup="setHarga()">
						<span class="kt-input-icon__icon kt-input-icon__icon--right">
							<span>%</span>
						</span>
					</div>
				</div>	
			</div>
			<div class="form-group row">
				<label for="barang_satuan" class="col-2 col-form-label">Satuan Utama</label>
				<div class="col-2">
					<select class="form-control" name="barang_satuan" id="barang_satuan" onchange="showIsi()" style="width: 100%"></select>
				</div>
				<label for="barang_stok_min" class="col-2 col-form-label">Minimal Stok</label>
				<div class="col-2">			
					<div class="kt-input-icon kt-input-icon--right">
						<input class="form-control number" type="text" id="barang_stok_min" name="barang_stok_min">
						<span class="kt-input-icon__icon kt-input-icon__icon--right">
							<span class="lbl_barang_satuan"></span>
						</span>
					</div>
				</div>
				<label for="barang_harga" class="col-2 col-form-label">Harga Jual</label>
				<div class="col-2">
					<input class="form-control number" type="text" id="barang_harga" name="barang_harga">
				</div>
			</div>
			<div class="form-group row">
				<label for="barang_satuan_opt" class="col-2 col-form-label">Satuan Tambahan</label>
				<div class="col-2">
					<select class="form-control" name="barang_satuan_opt" id="barang_satuan_opt" style="width: 100%"></select>
				</div>
				<label for="barang_isi" class="col-2 col-form-label">Jumlah Isi/Konversi</label>
				<div class="col-2">
					<div class="kt-input-icon kt-input-icon--right">
						<input class="form-control number" type="text" id="barang_isi" name="barang_isi" onkeyup="setHarga()"> 
						<span class="kt-input-icon__icon kt-input-icon__icon--right">
							<span class="lbl_barang_satuan"></span>
						</span>
					</div>
				</div>
				<label for="barang_harga_opt" class="col-2 col-form-label">Harga Jual 2</label>
				<div class="col-2">
					<input class="form-control number" type="text" id="barang_harga_opt" name="barang_harga_opt">
				</div>
			</div>	
			<div class="form-group row">
				<label for="barang_stok" class="col-2 col-form-label">Stok Saat Ini</label>
				<div class="col-2">					
					<div class="kt-input-icon kt-input-icon--right">
						<input class="form-control number" type="text" id="barang_stok" name="barang_stok" disabled="">
						<span class="kt-input-icon__icon kt-input-icon__icon--right">
							<span class="lbl_barang_satuan"></span>
						</span>
					</div>
				</div>
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