<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> Data Retur Penjualan
				</div>
				<div class="kt-portlet__head-toolbar-wrapper mt-3">
					<div class="dropdown dropdown-inline">
						<button type="button" class="btn btn-success btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
						<button type="button" class="btn btn-info btn-elevate btn-elevate-air" onclick="onRefresh()">
							<i class="la la-refresh"></i> Refresh
						</button>
						<button type="button" class="btn btn-focus btn-light-facebook" onclick="onPrint()">
							<i class="la la-print"></i> Cetak
						</button>
						<a href="#modal" data-toggle="modal">
							<button type="button" class="btn btn-sm btn-outline-success btn-icon"><i class="fa fa-calendar-day"></i></button>
						</a>

					</div>
				</div>
			</div>
			<div class="card-body table-responsive">	
				<table class="table table-striped table-checkable table-condensed" id="table-returpenjualan">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Tanggal</th>
							<th>No Penjualan</th>
							<th>Customer</th>
							<th>Item Retur</th>
							<th>Qty Retur</th>
							<th>Nilai Retur</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Tanggal</th>
							<th>Kode Penjualan</th>
							<th>Customer</th>
							<th>Item Retur</th>
							<th>Qty Retur</th>
							<th>Nilai Retur</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="printArea" style="display: none;"></div>
<?php $this->load->view('form'); ?>
<?php $this->load->view('javascript'); ?>
