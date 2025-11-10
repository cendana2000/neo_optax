<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DATA SURVEY</h3>
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh('table-data-survey')"><i class="flaticon-refresh icon-md"></i> Muat Ulang</button>
						<button class="btn btn-success btn-sm" onclick="onAdd()"><i class="fa fa-plus icon-md"></i> Tambah</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-data-survey">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Nama Survey</th>
							<th>Tgl Publish</th>
							<th>Selesai</th>
							<th>Deskripsi</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Nama Survey</th>
							<th>Tgl Publish</th>
							<th>Selesai</th>
							<th>Deskripsi</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('Form'); ?>
<?php $this->load->view('Javascript'); ?>