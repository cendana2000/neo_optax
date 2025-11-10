<style>
	.highlight {
		background: #FC0 !important;
	}
</style>
<div class="row table_data">
	<div class="col">
		<div class="card card-custom table_data">
			<div class="card-header ">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM LAPOR PAJAK</h3>
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-success btn-sm" onclick="onAdd()"><i class="fa fa-plus"></i> Tambah</button>
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-upload">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Tanggal Lapor</th>
							<th>Periode</th>
							<th>JML Transaksi</th>
							<th>Omzet</th>
							<th>Pajak</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th class="table-primary" colspan="3">Total</th>
							<th class="table-primary" id="postingpajak_jml_trf">JML Transaksi</th>
							<th class="table-primary" id="postingpajak_omzet">Omzet</th>
							<th class="table-primary" id="postingpajak_pajak">Pajak</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>



<?php $this->load->view('Form') ?>
<?php $this->load->view('javascript') ?>