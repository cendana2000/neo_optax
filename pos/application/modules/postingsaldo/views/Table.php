<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> DATA POSTING PERSEDIAAN
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<div class="kt-portlet__head-toolbar-wrapper">
							<div class="dropdown dropdown-inline">
								<!-- <button type="button" class="btn btn-warning btn-elevate" onclick="scanBarcode()"><i class="fa fa-barcode"></i>Scan Barcode</button> -->
								<button type="button" class="btn btn-success btn-sm btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
								<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-striped table-checkable table-condensed" id="table-postingsaldo">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Bulan</th>
							<th>Awal</th>
							<th>Masuk</th>
							<th>Keluar</th>
							<th>Nilai/Akhir</th>
							<!-- <th>Laba Kotor</th> -->
							<th>Aksi</th>
							<!-- <th>Stok</th> -->
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Bulan</th>
							<th>Awal</th>
							<th>Masuk</th>
							<th>Keluar</th>
							<th>Nilai/Akhir</th>
							<!-- <th>Laba Kotor</th> -->
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>

			</div>
		</div>
	</div>
</div>
<?php view(['javascript', 'form']) ?>