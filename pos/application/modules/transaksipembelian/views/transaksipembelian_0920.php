<div class="kt-portlet kt-portlet--mobile table_data">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Data Transaksi Pembelian
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
					<a href="#modal" data-toggle="modal">
						<button type="button" class="btn btn-outline-success btn-icon"><i class="fa fa-calendar-day"></i></button>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="kt-portlet__body">
		<!--begin: Datatable -->
		<table class="table table-striped table-checkable table-condensed" id="table-pembelianbarang" style="width:100%">
			<thead>
				<tr>
					<th style="width:5%;">No.</th>
					<th></th>
					<th>Kode</th>
					<th>Tanggal</th>
					<th>Supplier</th>
					<th>Faktur</th>
					<!-- <th>Jenis Pembelian</th> -->
					<th>Total</th>
					<th>Bayar</th>
					<th>Sisa</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot>
				<tr>
					<th style="width:5%;">No.</th>
					<th></th>
					<th>Kode</th>
					<th>Tanggal</th>
					<th>Supplier</th>
					<th>Faktur</th>
					<!-- <th>Jenis Pembelian</th> -->
					<th>Total</th>
					<th>Bayar</th>
					<th>Sisa</th>
					<th>Aksi</th>
				</tr>
			</tfoot>
		</table>

		<!--end: Datatable -->
	</div>
</div>
<div class="modal fade" id="modal" role="dialog" style="width: ">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>Tampilkan Berdasarkan</h4>
			</div>
			<div class="modal-body">
				<form id="tanggal" name="tanggal" action="javascript:init_table()">
					<div class="kt-portlet__body">
						<div class="form-group row">
							<label class="col-2 col-form-label">Tanggal</label>
							<input type="date" name="awal_tanggal" id="awal_tanggal" class="col-4 form-control" value="<?php echo date('Y-m-d') ?>">
							<label class="col-2 col-form-label">S/d</label>
							<input type="date" name="akhir_tanggal" id="akhir_tanggal" class="col-4 form-control" value="<?php echo date('Y-m-d') ?>">
						</div>
					</div>
					<div class="kt-portlet__foot">
						<div class="form-group row">
							<div class="col-12 col-form-label">
								<center>
									<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i>Tampilkan</button>
									<button type="button" data-dismiss="modal" class="btn btn-secondary"><i class="flaticon2-cancel-music"></i>Batal</button>
								</center>
							</div>

						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('form'); ?>
<?php $this->load->view('javascript'); ?>