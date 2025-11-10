<div class="kt-portlet kt-portlet--mobile table_data">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Kartu Stok
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body" style="background: #a2b2ff;border-radius: 0;">
		<form class="kt-form kt-form--fit kt-margin-b-10" name="filter-kartu_stok" id="filter-kartu_stok" action="javascript:init_table()">
			<div class="row kt-margin-b-20">
					<label class="text-white col-md-1" for="kartu_tanggal">Periode</label>
					<div class="col-3">
						<select name="periode" id="periode" class="form-control" onchange="setPeriode(this)">
							<option value="tanggal">Rentang Tanggal</option>
							<option value="bulan">Rentang Bulan</option>
						</select>
					</div>
					<label class="text-white col-md-2  tanggal" for="kartu_tanggal">Dari Tanggal</label>
					<div class="col-2 tanggal">
						<input class="form-control" type="date" id="kartu_tanggal" name="kartu_tanggal" value="<?php echo date('Y-m-01') ?>">
					</div>
					<label class="text-white col-md-1 tanggal" for="kartu_tanggal_akhir">s/d</label>
					<div class="col-2 tanggal">
						<input class="form-control" type="date" id="kartu_tanggal_akhir" name="kartu_tanggal_akhir" value="<?php echo date('Y-m-t') ?>">
					</div>
					<label class="text-white col-md-2  bulan" for="kartu_bulan">Dari Bulan</label>
					<div class="col-2 bulan">
						<input class="form-control" type="month" id="kartu_bulan" name="kartu_bulan" value="<?php echo date('Y-m') ?>">
					</div>
					<label class="text-white col-md-1 bulan" for="kartu_bulan_akhir">s/d</label>
					<div class="col-2 bulan">
						<input class="form-control" type="month" id="kartu_bulan_akhir" name="kartu_bulan_akhir" value="<?php echo date('Y-m') ?>">
					</div>
			</div>
			<div class="row kt-margin-b-20">
				<label class="text-white col-md-1" for="kartu_transaksi">Jenis Transaksi</label>
				<div class="col-3">
					<select class="form-control" name="kartu_transaksi" id="kartu_transaksi" style="width: 100%">
						<option value="">All</option>
						<option value="Pembelian">Pembelian</option>
						<option value="Retur Pembelian">Retur Pembelian</option>
						<option value="Penjualan">Penjualan</option>
						<option value="Retur Penjualan">Retur Penjualan</option>
						<option value="Opname">Stock Opname</option>
					</select>
				</div>
				<label class="text-white col-md-2" for="kartu_barang_id" >Barang</label>
				<div class="col-5">
					<select class="form-control" id="kartu_barang_id" name="kartu_barang_id">					
					</select>
				</div>
			</div>
			<div class="row kt-margin-b-10">
				<div class="col-1"></div>
				<div class="col-5">
					<button class="btn btn-brand kt-btn kt-btn--icon" id="kt_search" onclick="init_table(this)">
						<span>
							<i class="la la-search"></i>
							<span>Tampilkan</span>
						</span>
					</button>
					&nbsp;&nbsp;
					<button class="btn btn-secondary kt-btn kt-btn--icon" id="kt_reset" style="background: #fff">
						<span>
							<i class="la la-close"></i>
							<span>Reset</span>
						</span>
					</button>
				</div>
			</div>
			<div class="kt-separator kt-separator--md kt-separator--dashed"></div>
		</form>
		
	</div>
	<div class="kt-portlet__body table-data" style="display: none;">
		<!--begin: Datatable -->
		<table class="table table-striped table-checkable table-condensed" id="table-kartustok">
			<thead>
				<tr>
					<th style="width:5%;">No.</th>
					<th>Tanggal</th>
					<th>Barang</th>
					<th>S. Awal</th>
					<th>Masuk</th>
					<th>Keluar</th>
					<th>S. Akhir</th>
					<th>Harga</th>
					<th>Nomial</th>
					<th>H. Pokok</th>
					<th>Jenis Transaksi</th>
					<th>Kode</th>
					<th>Kode Referensi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot>
				<tr>
					<th style="width:5%;">No.</th>
					<th>Tanggal</th>
					<th>Barang</th>
					<th>S. Awal</th>
					<th>Masuk</th>
					<th>Keluar</th>
					<th>S. Akhir</th>
					<th>Harga</th>
					<th>Nomial</th>
					<th>H. Pokok</th>
					<th>Jenis Transaksi</th>
					<th>Kode</th>
					<th>Kode Referensi</th>
				</tr>
			</tfoot>
		</table>

		<!--end: Datatable -->
	</div>
</div>

<?php view('javascript') ?>