<!-- <div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> DATA SUPPLIER
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<div class="kt-portlet__head-toolbar-wrapper">
							<div class="dropdown dropdown-inline">								
								<button type="button" class="btn btn-success btn-sm btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
								<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
								<button type="button" class="btn btn-info btn-sm btn-elevate btn-elevate-air" onclick="onPrint()">
									<i class="la la-print"></i> Cetak
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-supplier">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode Supplier</th>
							<th>Nama Supplier</th>
							<th>Telp</th>
							<th>Alamat</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Kode Supplier</th>
							<th>Nama Supplier</th>
							<th>Telp</th>
							<th>Alamat</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div> 
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">			
			<div class="modal-header">
				<h4 class="modal-title">Cetak</h4>
				<button type="button" class="close" data-dismiss="modal"></button>
			</div>			
			<div class="modal-body" style="width: 100%">
				<form class="kt-form" name="cetakSupplier" id="cetakSupplier">
					<div class="form-group row">
						<label for="supplier1" class="col-form-label">Supplier</label>
						<div class="col-4">
							<select class="form-control" name="supplier1" id="supplier1" style="width: 100%;"></select>
						</div>

						<label for="supplier2" class="col-form-label">s/d</label>
						<div class="col-4">
							<select class="form-control" name="supplier2" id="supplier2" style="width: 100%;"></select>
						</div>

					</div>
				</form>
			</div>			
			<div class="modal-footer">
				<div class="col-4">
					<button type="button" onclick="loadPreview()" class="btn btn-success" data-dismiss="modal"><i class="la la-print"></i> Cetak</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="kt-portlet kt-portlet--mobile form_data2" style="display:none;">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Daftar Supplier
			</h3>
		</div>
	</div>
	<div class="kt-form">
		<div class="kt-portlet__body form" id="pdf-laporan">
			<object data="" type="application/pdf" width="100%" height="500px"></object>
		</div>
	</div>
</div> -->
<div class="row form_data">
	<div class="col">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">LOG TRANSAKSI</h3>
				</div>
				<input type="hidden" id="sub_wajibpajak_npwpd">
				<div class="card-toolbar">
					<div class="mr-3">
						<div class="input-group input-group-sm">
							<input type="text" class="form-control monthpicker" name="bulan" id="bulan" onchange="filterBulan()" value="" placeholder="Pilih Bulan" />
							<div class="input-group-append"><span class="input-group-text"><i class="la la-calendar-check-o "></i></span></div>
						</div>
					</div>
					<div class="example-tools justify-content-center">
						<div class="btn-group">
							<button class="btn btn-danger btn-sm" onclick="getPdfSubRealisasi()"><i class="far fa-file-pdf"></i> PDF</button>
							<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-logtransaksi">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Tanggal</th>
							<th>Sub Total</th>
							<th>Jasa</th>
							<th>Pajak</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Tanggal</th>
							<th>Sub Total</th>
							<th>Jasa</th>
							<th>Pajak</th>
							<th>Total</th>
						</tr>
						<!-- tambahan -->
						<tr>
							<th class="table-primary" colspan="2">Total</th>
							<th class="table-primary" id="subrealisasi_total_omzet">Rp.0</th>
							<th class="table-primary" id="subrealisasi_total_jasa">Rp.0</th>
							<th class="table-primary" id="subrealisasi_total_pajak">Rp.0</th>
							<th class="table-primary" id="subrealisasi_total_total">Rp.0</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('form'); ?>
<?php $this->load->view('javascript'); ?>