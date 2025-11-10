<div class="kt-portlet kt-portlet--mobile table_data" style="display: none;">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Data Transaksi Penjualan
			</h3>
		</div>
		<div class="kt-portlet__head-toolbar">
			<div class="kt-portlet__head-toolbar-wrapper">
				<div class="dropdown dropdown-inline">
					<button type="button" class="btn btn-success btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
					<button type="button" class="btn btn-info btn-elevate btn-elevate-air" onclick="onRefresh()">
						<i class="la la-refresh"></i> Refresh
					</button>
					<button type="button" class="btn btn-focus btn-elevate btn-elevate-air" onclick="onPrint()">
						<i class="la la-print"></i> Cetak
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="kt-portlet__body">
		<!--begin: Datatable -->
		<table class="table table-striped table-checkable table-condensed" id="table-penjualanbarang">
			<thead>
				<tr>
					<th style="width:5%;">No.</th>
					<th>Kode</th>
					<th>Tanggal</th>
					<th>Nasabah</th>
					<th>No Nasabah</th>
					<!-- <th>Jenis Penjualan</th> -->
					<th>Sub Total</th>
					<th>Potongan</th>
					<th>Grand Total</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot>
				<tr>
					<th style="width:5%;">No.</th>
					<th>Kode</th>
					<th>Tanggal</th>
					<th>Nasabah</th>
					<th>No Nasabah</th>
					<!-- <th>Jenis Penjualan</th> -->
					<th>Sub Total</th>
					<th>Potongan</th>
					<th>Grand Total</th>
					<th>Aksi</th>
				</tr>
			</tfoot>
		</table>

		<!--end: Datatable -->
	</div>
</div>