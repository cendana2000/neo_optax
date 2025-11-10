<div class="row">
	<div class="col-12 mb-3 " data-roleable="false" data-role="rekaptransaksi-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM REKAP TRANSAKSI
				</div>
			</div>
			<form action="javascript:save('form-realisasi')" method="post" id="form-realisasi" name="form-realisasi" autocomplete="off" enctype="multipart/form-data">
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="form-group row">
								<label class="form-label">Periode</label>
								<!-- <div class="col-5"> -->
									<input class="form-control" value="<?= date('Y-m-d') ?>" type="date" value="" id="periode_upload" name="periode_upload"/>
								<!-- </div> -->
							</div>
							<div class="form-group">
								<label class="form-label">Rekap Transaksi</label>
								<div class="table-responsive">
									<table class="table table-bordered" id="table-rekap-form">
										<thead>
											<tr class="d-flex d-md-table-row">
												<th class="col-1 col-md-auto">No</th>
												<th class="col-4 col-md-auto">Time</th>
												<th class="col-3 col-md-auto">Receipt No</th>
												<th class="col-3 col-md-auto">Sub Total</th>
												<th class="col-3 col-md-auto">Jasa</th>
												<th class="col-3 col-md-auto">Pajak</th>
												<th class="col-3 col-md-auto">Total</th>
												<th class="col-2 col-md-auto">Aksi</th>
											</tr>
										</thead>
										<tbody>
											<tr class="d-flex d-md-table-row">
												<td class="col-1 col-md-auto">1</td>
												<td class="col-4 col-md-auto"><input type="time" class="form-control" name="time[]" required/></td>
												<td class="col-3 col-md-auto"><input type="text" class="form-control" name="receiptno[]" required/></td>
												<td class="col-3 col-md-auto"><input type="text" class="form-control" name="subtotal[]" required/></td>
												<td class="col-3 col-md-auto"><input type="text" class="form-control" name="service[]" required/></td>
												<td class="col-3 col-md-auto"><input type="text" class="form-control" name="tax[]" required/></td>
												<td class="col-3 col-md-auto"><input type="text" class="form-control" name="total[]" style="background-color: #eaeaea;" readonly/></td>
												<td class="col-2 col-md-auto">
													<button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-icon mr-2"><i class="fa fa-trash"></i></button>
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr class="d-flex d-md-table-row">
												<th class="col-8 col-md-auto" style="vertical-align: middle;" colspan="3">Total</th>
												<th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_subtotal" style="background-color: #eaeaea;" readonly/></th>
												<th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_service" style="background-color: #eaeaea;" readonly/></th>
												<th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_tax" style="background-color: #eaeaea;" readonly/></th>
												<th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_total" style="background-color: #eaeaea;" readonly/></th>
												<th class="col-2 col-md-auto">
													<button type="button" onclick="addRow()" class="btn btn-primary btn-icon"><i class="fa fa-plus"></i></button>
												</th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success" id="btn_save"><span class="fas fa-paper-plane"></span> Simpan</button>
        </div>
			</form>
		</div>
	</div>
	<div class="col">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>DATA LAPORAN
				</div>
				<div class="card-toolbar">
					<!-- tambahan -->
					<div class="mr-3">
							<div class="input-group input-group-sm">
									<input type="text" class="form-control monthpicker" name="bulan" id="bulan" onchange="filterBulan()" value="" placeholder="Pilih Bulan" />
									<div class="input-group-append"><span class="input-group-text"><i class="la la-calendar-check-o "></i></span></div>
							</div>
					</div>
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-rekaptransaksi">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Tanggal</th>
							<th>Omzet</th>
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
							<th>Omzet</th>
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
							<th class="table-primary"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript') ?>