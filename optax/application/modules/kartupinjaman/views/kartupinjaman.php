<div class="row">
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Kartu Pinjaman
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
		<form class="kt-form" action="javascript:filter()" id="form-kartu" name="form-kartu">
			<div class="kt-portlet__body">			
				<div class="form-group row">
					<div class="col-4">
						<label for="anggota_id" class="col-form-label">No Nasabah</label>
						<select class="form-control" id="anggota_id" name="anggota_id" onchange="pilihNasabah()"></select>
					</div>
					<div class="col-8">
						<label for="anggota_alamat" class="col-form-label">Alamat Nasabah</label>
						<input class="form-control" type="text" id="anggota_alamat" name="anggota_alamat" disabled="">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-6">
						<label for="grup_gaji_nama" class="col-form-label">Grup Gaji</label>
						<input class="form-control" type="text" id="grup_gaji_nama" name="grup_gaji_nama" disabled="">
					</div>
					<div class="col-6">
						<label for="grup_gaji_keterangan" class="col-form-label">Kelompok Anggota</label>
						<input class="form-control" type="text" id="kelompok_anggota_nama" name="kelompok_anggota_nama" disabled="">
					</div>
				</div>	
				<div class="form-group row">
					<div class="col-4">
						<label for="grup_gaji_nama" class="col-form-label">Jenis Pinjaman</label>
						<select name="kartu_pinjaman_jenis" id="kartu_pinjaman_jenis" class="form-control" onchange="pilihNasabah()">
							<option value="U">Uang</option>
							<option value="B">Barang</option>
						</select>
					</div>
					<div class="col-4">
						<label for="grup_gaji_keterangan" class="col-form-label">Nomor Pengajuan</label>
						<select class="form-control" id="kartu_pinjaman_referensi_id" name="kartu_pinjaman_referensi_id" disabled="" class="form-control"></select>
					</div>
					<div class="col-4" style="margin-top: 35px;">
						<button class="btn btn-brand kt-btn kt-btn--icon" id="kt_search" onclick="filter(this)">
							<span>
								<i class="la la-search"></i><span>Search</span>
							</span>
						</button>
						<button type="button" onclick="onPrint()" id="btn-print"  class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Cetak</button>
					</div>
				</div>	
			</div>
		</form>		
		<div class="kt-portlet__body">
			<!--begin: Datatable -->
			<table class="table table-striped table-checkable table-condensed" id="table_grup">
				<thead>
					<tr>
						<th style="width:5%;">No.</th>
						<th>Tanggal</th>
						<th>Tenor</th>
						<th>Angs Ke</th>
						<th>S.Awal</th>
						<th>Pinjam</th>
						<th>Bayar</th>
						<th>S.Akhir</th>
						<th>Kode</th>
						<th>Jenis</th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th style="width:5%;">No.</th>
						<th>Tanggal</th>
						<th>Tenor</th>
						<th>Angs Ke</th>
						<th>S.Awal</th>
						<th>Pinjam</th>
						<th>Bayar</th>
						<th>S.Akhir</th>
						<th>Kode</th>
						<th>Jenis</th>
					</tr>
				</tfoot>
			</table>
			<!--end: Datatable -->

			<table class="table table-striped table-checkable table-condensed" id="table_pengajuan" style="display: none;">
				<thead>
					<tr>
						<th style="width:5%;">No.</th>
						<th>Tgl Pinjam</th>
						<th>Nomor Pinjam</th>
						<th>Jumlah Pinjam</th>
						<th>Tenor</th>
						<th>Pokok</th>
						<th>Jasa</th>
						<th>Sisa Angsuran</th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th style="width:5%;">No.</th>
						<th>Tgl Pinjam</th>
						<th>Nomor Pinjam</th>
						<th>Jumlah Pinjam</th>
						<th>Tenor</th>
						<th>Pokok</th>
						<th>Jasa</th>
						<th>Sisa Angsuran</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<div class="kt-portlet kt-portlet--mobile kt-laporan" style="display:none;">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Kartu Pinjaman
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