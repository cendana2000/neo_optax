<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> DATA STOCK OPNAME
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<div class="kt-portlet__head-toolbar-wrapper">
							<div class="dropdown dropdown-inline">
								<!-- <button type="button" class="btn btn-warning btn-elevate" onclick="scanBarcode()"><i class="fa fa-barcode"></i>Scan Barcode</button> -->
								<button type="button" class="btn btn-success btn-sm btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
								<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
								<!-- <button type="button" class="btn btn-info btn-sm btn-elevate btn-elevate-air" onclick="onPrint()">
									<i class="la la-print"></i> Cetak
								</button> -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<!--begin: Datatable -->
				<table class="table table-striped table-checkable table-condensed" id="table-stokopname">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Tanggal</th>
							<th>Item</th>
							<th>Data</th>
							<th>Fisik</th>
							<th>Koreksi</th>
							<th>Nilai</th>
							<th>Keterangan</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Tanggal</th>
							<th>Item</th>
							<th>Data</th>
							<th>Fisik</th>
							<th>Koreksi</th>
							<th>Nilai</th>
							<th>Keterangan</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
				<!--end: Datatable -->
			</div>

		</div>
	</div>

	<!-- Holder loadPreview -->
	<div class="kt-portlet kt-portlet--mobile cetak_data" style="display: none">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Cetak Stock Opname
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-group">
					<button type="reset" class="btn btn-outline-brand btn-square" onclick="onBack()"><i class="flaticon2-reply"></i> Kembali</button>
				</div>
			</div>
		</div>
		<div class="kt-portlet__body" id="pdf-laporan">
			<object data="" type="application/pdf" width="100%" height="500px"></object>
		</div>

	</div>


</div>
<?php $this->load->view('form'); ?>
<?php $this->load->view('javascript'); ?>