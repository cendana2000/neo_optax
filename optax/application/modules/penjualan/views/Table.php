<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> DATA PENJUALAN
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<div class="kt-portlet__head-toolbar-wrapper">
							<div class="dropdown dropdown-inline">
								<!-- <button type="button" class="btn btn-warning btn-elevate" onclick="scanBarcode()"><i class="fa fa-barcode"></i>Scan Barcode</button> -->
								<button type="button" class="btn btn-success btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
								<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-penjualan">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>No Transaksi</th>
							<th>Tanggal</th>
							<th>Nasabah</th>
							<th>No Nasabah</th>
							<th>Sub Total</th>
							<th>Potongan</th>
							<th>Grant Total</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>No Transaksi</th>
							<th>Tanggal</th>
							<th>Nasabah</th>
							<th>No Nasabah</th>
							<th>Sub Total</th>
							<th>Potongan</th>
							<th>Grant Total</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('form'); ?>
<?php $this->load->view('javascript'); ?>