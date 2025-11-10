<div class="row form_data">
	<div class="col-12 mb-3 " data-roleable="false" data-role="rekaptransaksi-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM REKAP TRANSAKSI</h3>
				</div>
			</div>
			<!--
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM REKAP TRANSAKSI
				</div>
			</div>
			-->
			<marquee class="py-3">JIKA ADA KENDALA TERKAIT APLIKASI BISA HUBUNGI CS PERSADA DI NOMOR 0811-3200-0187</marquee>
			<input type="hidden" id="sub_wajibpajak_npwpd">
			<form action="javascript:save('form-realisasi')" method="post" id="form-realisasi" name="form-realisasi" autocomplete="off" enctype="multipart/form-data">
				<div class="card-body pt-0">
					<div class="row">
						<div class="col-12">
							<div class="form-group row">
								<label class="form-label">Tanggal Transaksi</label>
								<!-- <div class="col-5"> -->
								<input class="form-control" value="<?= date('Y-m-d') ?>" type="date" value="" id="periode_upload" name="periode_upload" />
								<!-- </div> -->
							</div>
							<div class="form-group">
								<div class="d-flex justify-content-between">
									<label class="form-label">Rekap Transaksi</label>
									<div class="d-flex flex-lg-row flex-column mb-3">
										<label style="font-size: 12px;" class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary mb-0">
											<input type="checkbox" id="termasuk_pajak">
											<span></span>&nbsp;
											Sudah Termasuk Pajak?
										</label>
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered" id="table-rekap-form">
										<thead>
											<tr class="d-flex d-md-table-row">
												<th class="col-1 col-md-auto">No</th>
												<th class="col-4 col-md-auto">
													<div class="d-flex justify-content-between">
														<div class="thtitle">
															Waktu
														</div>
														<button type="button" class="thinfo" data-bs-toggle="tooltip" data-bs-placement="top">
															<i class="fas fa-question" style="font-size:10px;"></i>
															<span class="tooltip-wrapper">Kolom ini adalah waktu saat anda melakukan rekap data omzet pajak, berisi keterangan jam dan menit</span>
														</button>
													</div>
												</th>
												<th class="col-3 col-md-auto">
													<div class="d-flex justify-content-between">
														<div class="thtitle">
															Nomor Resi
														</div>
														<button type="button" class="thinfo" data-bs-toggle="tooltip" data-bs-placement="top">
															<i class="fas fa-question" style="font-size:10px;"></i>
															<span class="tooltip-wrapper">Kolom ini berisi nomor nota bonbill, untuk format nomor urut resi menyesuaikan seperti yang ada ditempat usaha anda</span>
														</button>
													</div>
												</th>
												<th class="col-3 col-md-auto">
													<div class="d-flex justify-content-between">
														<div class="thtitle">
															Sub Total
														</div>
														<button type="button" class="thinfo" data-bs-toggle="tooltip" data-bs-placement="top">
															<i class="fas fa-question" style="font-size:10px;"></i>
															<span class="tooltip-wrapper">Kolom ini berisi omzet murni sebelum dikenakan pajak</span>
														</button>
													</div>
												</th>
												<th class="col-3 col-md-auto">
													<div class="d-flex justify-content-between">
														<div class="thtitle">
															Jasa
														</div>
														<button type="button" class="thinfo" data-bs-toggle="tooltip" data-bs-placement="top">
															<i class="fas fa-question" style="font-size:10px;"></i>
															<span class="tooltip-wrapper">Kolom ini berisi nominal jasa yang anda kenakan di tempat usaha anda jika ada, jika tidak ada diisikan 0 saja</span>
														</button>
													</div>
												</th>
												<th class="col-3 col-md-auto">
													<div class="d-flex justify-content-between">
														<div class="thtitle">
															Pajak <span class="tarif"></span>
														</div>
														<button type="button" class="thinfo" data-bs-toggle="tooltip" data-bs-placement="top">
															<i class="fas fa-question" style="font-size:10px;"></i>
															<span class="tooltip-wrapper">Kolom ini berisi nominal pajak yang dikenakan dari omzet murni ditambah biaya jasa kemudian dikalikan dengan persentase tarif pajak yang dikenakan sesuai dengan jenis pajak anda</span>
														</button>
													</div>
												</th>
												<th class="col-3 col-md-auto">
													<div class="d-flex justify-content-between">
														<div class="thtitle">
															Total
														</div>
														<button type="button" class="thinfo" data-bs-toggle="tooltip" data-bs-placement="top">
															<i class="fas fa-question" style="font-size:10px;"></i>
															<span class="tooltip-wrapper">Kolom ini berisi nominal yang dibayarkan oleh customer</span>
														</button>
													</div>
												</th>
												<th class="col-2 col-md-auto">Aksi</th>
											</tr>
										</thead>
										<tbody>
											<tr class="d-flex d-md-table-row">
												<td class="col-1 col-md-auto">1</td>
												<td class="col-4 col-md-auto"><input type="time" class="form-control" name="time[]" required /></td>
												<td class="col-3 col-md-auto">
													<div class="row">
														<!-- <div class="col-lg-10 pr-lg-3"> -->
														<div class="col-lg-12">
															<input type="text" class="form-control" name="receiptno[]" maxlength="18" required />
														</div>
														<!-- <div class="col-lg-2 text-center">
															<button type="button" onclick="generateReceiptNO(this)" class="btn btn-primary btn-icon mt-3 mt-lg-0 mx-sm-0 w-100"><i class="fas fa-sync-alt"></i></button>
														</div> -->
													</div>
												</td>
												<td class="col-3 col-md-auto"><input type="text" class="form-control" name="subtotal[]" value="0" maxlength="11" required /></td>
												<td class="col-3 col-md-auto"><input type="text" class="form-control" name="service[]" value="0" maxlength="11" required /></td>
												<td class="col-3 col-md-auto"><input type="text" class="form-control" name="tax[]" value="0" style="background-color: #eaeaea;" tabindex="-1" required readonly /></td>
												<td class="col-3 col-md-auto"><input type="text" class="form-control" name="total[]" value="0" style="background-color: #eaeaea;" tabindex="-1" readonly /></td>
												<td class="col-2 col-md-auto">
													<button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-icon mr-2" tabindex="-1"><i class="fa fa-trash"></i></button>
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr class="d-flex d-md-table-row">
												<th class="col-8 col-md-auto" style="vertical-align: middle;" colspan="3">Total</th>
												<th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_subtotal" style="background-color: #eaeaea;" readonly /></th>
												<th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_service" style="background-color: #eaeaea;" readonly /></th>
												<th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_tax" style="background-color: #eaeaea;" readonly /></th>
												<th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_total" style="background-color: #eaeaea;" readonly /></th>
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
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DATA LAPORAN</h3>
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
						<div class="btn-group">
							<!-- <button class="btn btn-success btn-sm" onclick="getSpreadsheetSubRealisasi()"><i class="far fa-file-excel"></i> Excel</button> -->
							<button class="btn btn-danger btn-sm" onclick="getPdfSubRealisasi()"><i class="far fa-file-pdf"></i> PDF</button>
							<!-- <button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button> -->
						</div>
						<!-- <button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button> -->
					</div>
				</div>
			</div>
			<!--
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>DATA LAPORAN
				</div>
			</div>
			-->
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
							<!-- <th class="table-primary"></th> -->
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>


<?php load_view('form') ?>
<?php load_view('javascript') ?>