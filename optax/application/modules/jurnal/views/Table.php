<div class="row table_data">
	<div class="col-md-12">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Data Jurnal
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-toolbar-wrapper">
						<div class="dropdown dropdown-inline">
							<button type="button" class="btn btn-outline-success btn-elevate btn-elevate-air btn-icon" onclick="onAdd()" title="Klik untuk menambah data"><i class="la la-plus"></i></button>
							<button type="button" class="btn btn-outline-warning btn-elevate btn-elevate-air btn-icon" onclick="onEdit()" title="Klik untuk mengedit data"><i class="la la-pencil"></i></button>
							<button type="button" class="btn btn-outline-info btn-elevate btn-elevate-air btn-icon" onclick="onPrint()" title="Klik untuk mencetak nota"><i class="la la-print"></i></button>
							<button type="button" class="btn btn-outline-danger btn-elevate btn-elevate-air btn-icon" onclick="onDestroy()" title="Klik untuk menambah data"><i class="la la-trash"></i></button>
							<button type="button" class="btn btn-outline-primary btn-elevate btn-elevate-air btn-icon" onclick="onRefresh()" title="Klik untuk merefresh data"><i class="la la-refresh"></i></button>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin: Datatable -->
				<table class="table table-striped table-checkable table-condensed" id="table-jurnal">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Tanggal</th>
							<th>No Bukti</th>
							<th>Keterangan</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Tanggal</th>
							<th>No Bukti</th>
							<th>Keterangan</th>
							<th>Total</th>
						</tr>
					</tfoot>
				</table>

				<!--end: Datatable -->
			</div>
		</div>
	</div>
</div>

<div class="kt-portlet kt-portlet--mobile cetak_data" style="display: none">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Cetak Nota
			</h3>
		</div>
		<div class="kt-portlet__head-toolbar">
			<div class="kt-portlet__head-group">
				<button type="reset" class="btn btn-outline-success btn-square" onclick="onPrintJurnal()"><i class="flaticon2-print"></i> Tampilan Jurnal</button>
				<button type="reset" class="btn btn-outline-brand btn-square" onclick="onBack()"><i class="flaticon2-reply"></i> Kembali</button>
			</div>
		</div>
	</div>
	<div class="kt-portlet__body" id="pdf-laporan">
		<object data="" type="application/pdf" width="100%" height="500px"></object>
	</div>

</div>
<?php $this->load->view('javascript'); ?>
<?php $this->load->view('form'); ?>