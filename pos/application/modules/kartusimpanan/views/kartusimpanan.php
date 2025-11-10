<div class="row">
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Kartu Simpanan
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-toolbar-wrapper">
					<div class="dropdown dropdown-inline">
						<button type="button" class="btn btn-info btn-elevate btn-elevate-air" onclick="onRefresh()">
							<i class="la la-refresh"></i> Refresh
						</button>
					</div>
				</div>
			</div>
		</div>
		<form class="kt-form" name="form-kartu" id="form-kartu" action="javascript:init_table()">
			<div class="kt-portlet__body">
				<div class="form-group row">
					<label class="col-2 col-form-label">Periode</label>
					<div class="col-4">
						<select class="form-control" name="periode" id="periode" onchange="pilihPeriode()">
							<option value="1">Semua</option>
							<option value="2">Bulan</option>
							<option value="3">Tahun</option>
						</select>
					</div>
					<label class="col-2 col-form-label bulan_p" style="display: none">Bulan</label>
					<div class="col-4 bulan_p" style="display: none">
						<input type="month" name="bulan" class="form-control" value="<?php echo date('Y-m') ?>">
					</div>
					<label class="col-2 col-form-label tahun_p" style="display: none">Tahun</label>
					<div class="col-4 tahun_p" style="display: none">
						<input type="year" name="tahun" class="form-control" value="<?php echo date('Y') ?>">
					</div>
				</div>			
				<div class="form-group row">
					<label class="col-2 col-form-label">Jenis Transaksi</label>
					<div class="col-4">
						<select class="form-control" name="jenis_transaksi">
							<option value="Simpanan Manasuka">Simpanan Manasuka</option>
							<option value="Simpanan Wajib">Simpanan Wajib</option>
							<option value="Simpanan Wajib Khusus">Simpanan Wajib Khusus</option>
							<option value="Simpanan Tabungan Hari Tua">Tabungan Hari Tua</option>
							<option value="Titipan Belanja">Titipan Belanja</option>
							<option value="Simpanan Pokok">Simpanan Pokok</option>
							<option value="Voucher BHR">Voucher BHR</option>
							<option value="Voucher Giveaway">Voucher Giveaway</option>
						</select>
					</div>
					<label for="anggota_id" class=" col-2 col-form-label">No Nasabah</label>
					<div class="col-4">
						<select class="form-control" id="anggota_id" name="anggota_id" onchange="pilihNasabah()"></select>
					</div>
				</div>
				<div class="form-group row">
					<label for="anggota_alamat" class="col-2 col-form-label">Alamat Nasabah</label>
					<div class="col-4">
						<input class="form-control" type="text" id="anggota_alamat" name="anggota_alamat">
					</div>
					<label for="grup_gaji_nama" class="col-2 col-form-label">Grup Gaji</label>
					<div class="col-4">
						<input class="form-control" type="text" id="grup_gaji_nama" name="grup_gaji_nama">
					</div>
					<!-- <label for="kelompok_anggota_nama" class="col-2 col-form-label">Kelompok Anggota</label>
					<div class="col-4">
						<input class="form-control" type="text" id="kelompok_anggota_nama" name="kelompok_anggota_nama">
					</div> -->
					<div class="col" style="margin-top: 35px;" align="right">
						<button class="btn btn-brand kt-btn kt-btn--icon" id="kt_search" onclick="init_table(this)">
							<span>
								<i class="la la-search"></i>
								<span>Cari</span>
							</span>
						</button>
						<button class="btn btn-focus kt-btn kt-btn--icon" id="kt_search" onclick="cetakKartu()">
							<span>
								<i class="la la-print"></i>
								<span>Cetak</span>
							</span>
						</button>
					</div>
				</div>	
			</div>
		</form>		
		<div class="kt-portlet__body tabel_simpanan">
			<!--begin: Datatable -->
			<table class="table table-striped table-checkable table-condensed" id="table_kartu">
				<thead>
					<tr>
						<th style="width:5%;">No.</th>
						<th style="width:5%;">Kode</th>
						<th>Tanggal</th>
						<th>S. Awal</th>
						<th>Masuk</th>
						<th>Keluar</th>
						<th>S.Akhir</th>
						<th>Kode Referensi</th>
						<th>Jenis Transaksi</th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th style="width:5%;">No.</th>
						<th style="width:5%;">Urut</th>
						<th>Tanggal</th>
						<th>S. Awal</th>
						<th>Masuk</th>
						<th>Keluar</th>
						<th>S.Akhir</th>
						<th>Kode Referensi</th>
						<th>Jenis Transaksi</th>
					</tr>
				</tfoot>
			</table>
			<!--end: Datatable -->
		</div>
	</div>
</div>
<div class="kt-portlet kt-portlet--mobile kt-laporan" style="display:none;">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Kartu Simpanan
			</h3>
		</div>
	</div>
	<div class="kt-form">
		<div class="kt-portlet__body form" id="pdf-laporan">
            <object data="" type="application/pdf" width="100%" height="500px"></object>
		</div>
	</div>
</div>
<?php $this->load->view('javascript'); ?>