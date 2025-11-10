<div class="row">
	<div class="col-12 mb-3 " data-roleable="false" data-role="upload-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header mb-5">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM UPLOAD LAPORAN</h3>
				</div>
			</div>
			<!--
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM UPLOAD LAPORAN
				</div>
			</div>
			-->
			<!-- <marquee class="mt-3">Periode(masa pajak) secara default terisi pada bulan sebelumnya. Pastikan omzet yang akan dilaporkan sesuai dengan masa pajak terpilih</marquee> -->
			<!-- <marquee class="py-3">JIKA ADA KENDALA TERKAIT APLIKASI BISA HUBUNGI CS PERSADA DI NOMOR 0811-3200-0187</marquee> -->
			<input type="hidden" id="sub_wajibpajak_npwpd">
			<form action="javascript:save('form-realisasi')" method="post" id="form-realisasi" name="form-realisasi" autocomplete="off" enctype="multipart/form-data">
				<div class="card-body pt-0">
					<div class="row">
						<div class="col-12">
							<div class="form-group row">
								<label class="col-lg-2 col-sm-3 col-form-label">Periode (Masa Pajak)</label>
								<div class="col-lg-3 col-sm-10">
									<div class="input-group input-group-sm">
										<input class="form-control monthpicker_custom" value="<?= date("Y-m-d", strtotime("last day of last month")); ?>" type="text" value="" id="periode_upload" name="periode_upload" />
										<div class="input-group-append"><label class="input-group-text cursor-pointer" for="periode_upload"><i class="la la-calendar-check-o "></i></label></div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-2 col-sm-3 col-form-label">Upload File</label>
								<div class="col-lg-3 col-sm-10">
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="laporan_realisasi" name="laporan_realisasi" />
										<label class="custom-file-label" for="customFile">Choose file</label>
									</div>
								</div>
								<div class="col-lg-6 col-sm-10 mt-5 mt-lg-0 ml-lg-2">
									<button type="submit" class="btn btn-success" id="btn_save"><span class="fas fa-paper-plane"></span> Proses</button>
									<a href="<?= base_url('assets/laporan/template_pajak/template_upload_pajak.xlsx'); ?>" class="btn btn-primary"><span class="fas fa-file-excel"></span> Download Template</a>
									<button type="reset" style="display: none;" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-redo" id="btnReset"></i>Reset</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="row form_data">
	<div class="col-12 mb-3 " data-roleable="false" data-role="upload-Create" data-action="hide">
		<!--search periode  -->

		<!-- </div> -->
		<div class="col">
			<div class="card card-custom">
				<div class="card-header">
					<div class="card-title">
						<span class="card-icon">
							<i class="fas fa-table text-primary"></i>
						</span>
						<h3 class="card-label">FORM UPLOAD LAPORAN</h3>
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
				<div class="card-body table-responsive">
					<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-upload">
						<thead>
							<tr>
								<th style="width:5%;">No.</th>
								<th>Masa Pajak</th>
								<th>Tanggal Upload</th>
								<th>Omzet</th>
								<th>Jasa</th>
								<th>Pajak</th>
								<th>Total</th>
								<th>Detail</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr>
								<th>No.</th>
								<th>Masa Pajak</th>
								<th>Tanggal Upload</th>
								<th>Omzet</th>
								<th>Jasa</th>
								<th>Pajak</th>
								<th colspan="2">Total</th>
								<!-- <th>Detail</th> -->
							</tr>
							<!-- tambahan -->
							<tr class="lg">
								<th class="table-primary" colspan="3">Total</th>
								<th class="table-primary" id="subrealisasi_total_omzet">Rp.0</th>
								<th class="table-primary" id="subrealisasi_total_jasa">Rp.0</th>
								<th class="table-primary" id="subrealisasi_total_pajak">Rp.0</th>
								<th class="table-primary" colspan="2" id="subrealisasi_total_total">Rp.0</th>
								<!-- <th class="table-primary"></th> -->
							</tr>
							<!-- <tr>
								<th class="table-primary" colspan="5">Total Nominal + Total pajak</th>
								<th class="table-primary" colspan="2" id="subrealisasi_total_sum">Rp.0</th>
							</tr> -->
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row sub_rinci" style="display: none">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label"> DETAIL REALISASI PAJAK</h3>
				</div>

				<div class="card-toolbar">
					<div class="btn-group">
						<button class="btn btn-success btn-sm" onclick="getSpreadsheetRinciRealisasi()"><i class="far fa-file-excel"></i> Excel</button>
						<button class="btn btn-danger btn-sm" onclick="getPdfRinciRealisasi()"><i class="far fa-file-pdf"></i> PDF</button>
						<button class="btn btn-warning btn-sm" onclick="onRefresh(3)"><i class="flaticon-refresh"></i> Muat Ulang</button>
						<button type="reset" class="btn btn-sm btn-secondary" onclick="onBackCard(3)"><i class="fa fa-arrow-left"></i> Kembali</button>
					</div>
				</div>
			</div>
			<form action="" class="card-body">
				<div>
					<h3 class="mb-5">Tanggal Upload : <span id="realisasi_tanggal"></span></h3>
					<input type="hidden" name="rinci_realisasi_tanggal" id="rinci_realisasi_tanggal" />
					<div class="row">
						<div class="col-12">
							<div class="table-responsive">
								<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-realisasi-detail">
									<thead>
										<tr>
											<th style="width:5%;">No.</th>
											<th>Waktu</th>
											<th>Kode Penjualan</th>
											<th>Omzet</th>
											<th>Jasa</th>
											<th>Pajak</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th class="table-primary" colspan="3">Total</th>
											<th class="table-primary" id="subrealisasi_detail_total_omzet">Rp.0</th>
											<th class="table-primary" id="subrealisasi_detail_total_jasa">Rp.0</th>
											<th class="table-primary" id="subrealisasi_detail_total_pajak">Rp.0</th>
											<th class="table-primary" id="subrealisasi_detail_total_total">Rp.0</th>
										</tr>
										<!-- <tr>
											<th class="table-primary" colspan="5">Total Nominal + Total pajak</th>
											<th class="table-primary" colspan="2" id="subrealisasi_detail_total_sum">Rp.0</th>
										</tr> -->
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<div class="separator separator-dashed my-5"></div>
				</div>
			</form>

		</div>
	</div>
</div>
<!-- search periode -->

<?php load_view('form') ?>
<?php load_view('javascript') ?>