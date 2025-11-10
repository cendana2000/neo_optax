<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> DATA PRODUK
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<div class="kt-portlet__head-toolbar-wrapper">
							<div class="dropdown dropdown-inline">
								<button type="button" class="btn btn-info btn-elevate" onclick="scanBarcode()"><i class="fa fa-barcode"></i>Scan Barcode</button>
								<button type="button" class="btn btn-success btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
								<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-barang">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Nama Produk</th>
							<th>Kelompok Produk</th>
							<th>Supplier</th>
							<th>Sat.</th>
							<th>Harga</th>
							<th>Stok</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>



<?php $this->load->view('form'); ?>
<?php $this->load->view('barcode'); ?>
<?php $this->load->view('javascript'); ?>