<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> DATA TRANSAKSI PEMBELIAN
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<div class="kt-portlet__head-toolbar-wrapper">
							<div class="dropdown dropdown-inline">
								<button type="button" class="btn btn-sm btn-success btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
								<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
								<!-- <a href="#modal" data-toggle="modal">
									<button type="button" class="btn btn-outline-success btn-icon btn-sm"><i class="fa fa-calendar-day"></i></button>
								</a> -->
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
												<form id="tanggal" name="tanggal" action="javascript:loadTable2()">
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
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-striped table-checkable table-condensed" id="table-pembelianbarang" style="width:100%">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th></th>
							<th>Kode</th>
							<th>Tanggal</th>
							<th>Supplier</th>
							<th>Faktur</th>
							<th>Total</th>
							<th>Status</th>
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
							<th>Total</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal" role="dialog" style="width: ">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>Tampilkan Berdasarkan</h4>
			</div>
			<div class="modal-body">
				<form id="tanggal" name="tanggal" action="javascript:loadTable2()">
					<div class="kt-portlet__body">
						<div class="form-group row">
							<label class="col-2 col-form-label">Tanggal</label>
							<input type="date" name="awal_tanggal" id="awal_tanggal" class="col-4 form-control" value="<?php echo date('Y-m') . '-01'; ?>">
							<label class="col-2 col-form-label">S/d</label>
							<input type="date" name="akhir_tanggal" id="akhir_tanggal" class="col-4 form-control" value="<?php echo date('Y-m-t') ?>">
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
<?php $this->load->view('form_detail'); ?>
<?php $this->load->view('barang'); ?>
<?php $this->load->view('javascript'); ?>