<div class="row form_data" style="display: none;">
	<div class="col">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>DATA SUB REALISASI PAJAK
				</div>
				<div class="card-toolbar">
					<div class="mr-3">
							<div class="input-group input-group-sm">
									<input type="text" class="form-control monthpicker" name="sub_bulan" id="sub_bulan" onchange="filterSubBulan()" value="" placeholder="Pilih Bulan" />
									<div class="input-group-append"><span class="input-group-text"><i class="la la-calendar-check-o "></i></span></div>
							</div>
					</div>
					<div class="btn-group">
						<button class="btn btn-success btn-sm" onclick="getSpreadsheetSubRealisasi()"><i class="far fa-file-excel"></i> Excel</button>
						<button class="btn btn-danger btn-sm" onclick="getPdfSubRealisasi()"><i class="far fa-file-pdf"></i> PDF</button>
						<button class="btn btn-warning btn-sm" onclick="onRefresh(2)"><i class="flaticon-refresh"></i> Muat Ulang</button>
						<button type="reset" class="btn btn-sm btn-secondary" onclick="onBackCard(2)"><i class="fa fa-arrow-left"></i> Kembali</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<div class="row">
					<div class="col-12">
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group">
									<label class="text-dark">NPWPD</label>
									<input class="form-control" type="text" readonly name="sub_wajibpajak_npwpd" id="sub_wajibpajak_npwpd" autocomplete="off" style="background: ghostwhite;" />
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group">
									<label class="text-dark">Nama Perusahaan</label>
									<input class="form-control" type="text" readonly name="sub_wajibpajak_nama" id="sub_wajibpajak_nama" autocomplete="off" readonly="" style="background: ghostwhite;" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group">
									<label class="text-dark">Alamat</label>
									<input class="form-control" type="text" readonly name="sub_wajibpajak_alamat" id="sub_wajibpajak_alamat" autocomplete="off" readonly="" style="background: ghostwhite;" />
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group">
									<label class="text-dark">Nama Penangung Jawab</label>
									<input class="form-control" type="text" readonly name="sub_wajibpajak_nama_penanggungjawab" id="sub_wajibpajak_nama_penanggungjawab" autocomplete="off" style="background: ghostwhite;" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-sub-realisasi">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Tanggal</th>
							<th>NPWP</th>
							<th>Wajib Pajak</th>
							<th>Omzet</th>
							<th>Jasa</th>
							<th>Pajak</th>
							<th>Total</th>
							<th>Detail</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<!-- <tr>
							<th>No.</th>
							<th>Tanggal</th>
							<th>NPWP</th>
							<th>Wajib Pajak</th>
							<th>Omzet</th>
							<th>Jasa</th>
							<th>Pajak</th>
							<th>Total</th>
							<th>Detail</th>
						</tr> -->
						<tr>
							<th class="table-primary" colspan="4">Total</th>
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

<!-- Sub Rinci -->
<div class="row sub_rinci" style="display: none">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>DETAIL REALISASI PAJAK
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
			<form action="">
				<div class="card-body ">
					<h3 class="mb-5">Tanggal Upload : <span id="realisasi_tanggal"></span></h3>
					<input type="hidden" name="rinci_realisasi_tanggal" id="rinci_realisasi_tanggal"/>
					<div class="row">
						<div class="col-12">
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">NPWPD</label>
										<input type="hidden" name="rinci_realisasi_id" id="rinci_realisasi_id" />
										<input class="form-control" type="text" readonly name="rinci_wajibpajak_npwpd" id="rinci_wajibpajak_npwpd" autocomplete="off" style="background: ghostwhite;" />
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">Nama Perusahaan</label>
										<input class="form-control" type="text" readonly name="rinci_wajibpajak_nama" id="rinci_wajibpajak_nama" autocomplete="off" readonly="" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">Alamat</label>
										<input class="form-control" type="text" readonly name="rinci_wajibpajak_alamat" id="rinci_wajibpajak_alamat" autocomplete="off" readonly="" style="background: ghostwhite;" />
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label class="text-dark">Nama Penangung Jawab</label>
										<input class="form-control" type="text" readonly name="rinci_wajibpajak_nama_penanggungjawab" id="rinci_wajibpajak_nama_penanggungjawab" autocomplete="off" style="background: ghostwhite;" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
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
								</tfoot>
							</table>
						</div>
					</div>
					<div class="separator separator-dashed my-5"></div>
				</div>
			</form>

		</div>
	</div>
</div>

<div class="row mt-3 report_data_pdf" style="display: none;">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> HASIL LAPORAN REALISASI PAJAK
				</div>
				<div class="card-toolbar">
					<button type="button" class="btn btn-sm btn-secondary" onclick="onBackCard(1)"><i class="fa fa-arrow-left"></i> Kembali</button>
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
					<div class="kt-form">
						<div class="kt-portlet__body form" id="pdf-laporan">
							<object data="" type="application/pdf" width="100%" height="500px"></object>
						</div>
					</div>
				</div>
				<div class="kt-portlet kt-portlet--mobile"></div>
			</div>
		</div>
	</div>
</div>

<div class="row mt-3 subreport_data_pdf" style="display: none;">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> HASIL LAPORAN SUB REALISASI PAJAK
				</div>
				<div class="card-toolbar">
					<button type="button" class="btn btn-sm btn-secondary" onclick="onBackCard(4)"><i class="fa fa-arrow-left"></i> Kembali</button>
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
					<div class="kt-form">
						<div class="kt-portlet__body form" id="subpdf-laporan">
							<object data="" type="application/pdf" width="100%" height="500px"></object>
						</div>
					</div>
				</div>
				<div class="kt-portlet kt-portlet--mobile"></div>
			</div>
		</div>
	</div>
</div>

<div class="row mt-3 rincireport_data_pdf" style="display: none;">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> HASIL LAPORAN RINCI REALISASI PAJAK
				</div>
				<div class="card-toolbar">
					<button type="button" class="btn btn-sm btn-secondary" onclick="onBackCard(5)"><i class="fa fa-arrow-left"></i> Kembali</button>
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
					<div class="kt-form">
						<div class="kt-portlet__body form" id="rincipdf-laporan">
							<object data="" type="application/pdf" width="100%" height="500px"></object>
						</div>
					</div>
				</div>
				<div class="kt-portlet kt-portlet--mobile"></div>
			</div>
		</div>
	</div>
</div>