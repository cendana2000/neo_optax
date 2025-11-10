<div class="row table_data">
	<div class="col-md-12">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Data Anggota/Nasabah
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-toolbar-wrapper">
						<div class="dropdown dropdown-inline">
							<button type="button" class="btn btn-success btn-elevate btn-elevate-air" onclick="onAdd()">
								<i class="la la-plus"></i> Tambah
							</button>
							<button type="button" class="btn btn-info btn-elevate btn-elevate-air" onclick="onRefresh()">
								<i class="la la-refresh"></i> Refresh
							</button>
							<!-- tombol untuk mencetak -->
							<button type="button" class="btn btn-focus btn-elevate btn-elevate-air" onclick="onPrint()">
								<i class="la la-print"></i> Cetak
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin: Datatable -->
				<table class="table table-striped table-checkable table-condensed" id="table-anggota">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Nama</th>
							<th>Tgl Masuk</th>
							<th>Grup Gaji</th>
							<th>Alamat</th>
							<th>Status Anggota</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Nama</th>
							<th>Tgl Masuk</th>
							<th>Grup Gaji</th>
							<th>Alamat Anggota</th>
							<th>Status Anggota</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
				<!--end: Datatable -->
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('javascript'); ?>
<?php $this->load->view('form'); ?>