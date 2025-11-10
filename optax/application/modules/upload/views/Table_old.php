<div class="row">
	<div class="col-12 mb-3 " data-roleable="false" data-role="upload-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM UPLOAD LAPORAN
				</div>
			</div>
			<form action="javascript:save('form-realisasi')" method="post" id="form-realisasi" name="form-realisasi" autocomplete="off" enctype="multipart/form-data">
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="form-group row">
								<label class="col-2 col-form-label">Periode</label>
								<div class="col-5">
									<input class="form-control" value="<?= date('Y-m-d') ?>" type="date" value="" id="periode_upload" name="periode_upload"/>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-2 col-form-label">Upload File</label>
								<div class="col-5">
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="laporan_realisasi" name="laporan_realisasi" />
										<label class="custom-file-label" for="customFile">Choose file</label>
									</div>
								</div>
								<div class="col-5">
									<button type="submit" class="btn btn-success" id="btn_save"><span class="fas fa-paper-plane"></span> Proses</button>
									<a href="<?= base_url('assets/laporan/template_pajak/template_realisasi_pajak.xlsx'); ?>" class="btn btn-primary"><span class="fas fa-file-excel"></span> Download Template</a>
									<button type="reset" style="display: none;" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-redo" id="btnReset"></i>Reset</button>

								</div>
							</div>
						</div>
					</div>
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
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-upload">
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
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript') ?>