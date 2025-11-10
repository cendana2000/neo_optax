<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DATA RETUR PENJUALAN</h3>
				</div>
				<div class="kt-portlet__head-toolbar-wrapper mt-3">
					<div class="dropdown dropdown-inline">
						<button type="button" class="btn btn-success btn-sm btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
						<button type="button" class="btn btn-warning btn-sm btn-elevate btn-elevate-air" onclick="onRefresh()">
							<i class="la la-refresh"></i> Muat Ulang
						</button>
						<div class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions" data-placement="left">
							<a href="#" class="btn btn-outline-success btn-icon btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-calendar-day"></i>
							</a>
							<div class="dropdown-menu mt-1 p-0 m-0 dropdown-menu-md dropdown-menu-right">
								<!--begin::Navigation-->
								<ul class="navi navi-hover">
									<li class="navi-header font-weight-bold py-4">
										<span class="font-size-lg">Tampilkan berdasarkan:</span>
									</li>
									<li class="navi-separator mb-3 opacity-70"></li>
									<li class="navi-item p-2">
										<form id="tanggal" name="tanggal" action="javascript:filterDateTable()">
											<div class="form-group">
												<label style="font-size: 12px;">Tanggal :</label>
												<input type="date" name="awal_tanggal" id="awal_tanggal" class="form-control" value="<?php echo date('Y-m') . '-01'; ?>">
											</div>
											<div class="form-group">
												<label style="font-size: 12px;">Sampai Dengan :</label>
												<input type="date" name="akhir_tanggal" id="akhir_tanggal" class="form-control" value="<?php echo date('Y-m-t') ?>">
												<button type="submit" class="btn btn-success btn-sm mt-2 mb-2 float-right"><span class="fas fa-search"></span> Tampilkan</button>
											</div>
										</form>
									</li>
								</ul>
								<!--end::Navigation-->
							</div>
						</div>
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
							<th>Kode Penjualan</th>
							<th>Customer</th>
							<th>Item Retur</th>
							<th>Qty Retur</th>
							<th>Nilai Retur</th>
							<th>Status</th>
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
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="row table_data mt-3 cetak_data" style="display: none">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> CETAK RETUR PENJUALAN
				</div>
				<div class="card-toolbar">
					<button type="reset" class="btn btn-secondary btn-sm m-" onclick="onBack()"><i class="fa fa-arrow-left"></i> Kembali</button>
				</div>
			</div>
			<div class="card-body table-responsive">
				<div class="kt-portlet kt-portlet--mobile ">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">

							</h3>
						</div>
					</div>
					<div class="kt-portlet__body" id="pdf-laporan">
						<object data="" type="application/pdf" width="100%" height="500px"></object>
					</div>
				</div>
				<div class="kt-portlet kt-portlet--mobile"></div>
			</div>
		</div>
	</div>
</div>

<div id="printArea" style="display: none;"></div>
<?php $this->load->view('form'); ?>
<?php $this->load->view('javascript'); ?>