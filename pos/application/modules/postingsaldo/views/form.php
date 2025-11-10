<div class="row form_data" style="display: none;">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span> FORM POSTING PERSEDIAAN
				</div>
			</div>
			<div class="card-body table-responsive">
				<form class="kt-form" action="javascript:save('form-postingsaldo')" name="form-postingsaldo" id="form-postingsaldo">
					<input type="hidden" id="posting_id" name="posting_id">
					<div class="kt-portlet__body">
						<div class="form-group row">
							<label for="posting_bulan" class="col-2">Periode</label>
							<div class="col-3">
								<input class="form-control" type="month" id="posting_bulan" name="posting_bulan" value="<?php echo date('Y-m'); ?>">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-2"></div>
							<div class="col-10">
								<div class="alert alert-warning" role="alert">
									<div class="alert-text">
										<h4 class="alert-heading">Perhatian !</h4>
										<hr>
										<p>Transaksi yang sudah dilakukan posting tidak dapat untuk melakukan edit dan delete data. pastikan tidak ada transaksi yang tertinggal.</p>
									</div>
								</div>
							</div>
						</div>
						<div class="kt-separator kt-separator--border-dashed kt-separator--space-md" style="display: none;"></div>
						<div class="form-group row" style="display: none;">
							<label for="posting_persediaan_photocopy" class="col-2">Persediaan Akhir Fotocopy</label>
							<div class="col-1"></div>
							<div class="col-3">
								<input class="form-control number" type="text" id="posting_persediaan_photocopy" name="posting_persediaan_photocopy">
							</div>
						</div>
						<div class="form-group row" style="display: none;">
							<label for="posting_persediaan_photobox" class="col-2">Persediaan Akhir Photobox</label>
							<div class="col-1"></div>
							<div class="col-3">
								<input class="form-control number" type="text" id="posting_persediaan_photobox" name="posting_persediaan_photobox">
							</div>
						</div>
					</div>
					<!-- <div class="kt-portlet__foot">
						<div class="kt-form__actions">
							<div class="row">
								<div class="col-2" style="padding-top: 8px;text-align: right;">
									<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
										<input type="checkbox" name="cetak_checkbox" id="cetak_checkbox" value="cetak" checked="checked"> <i class="flaticon2-print"></i> Cetak
										<span></span>
									</label>
								</div>
								<div class="col-10">
									<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Simpan</button>
									<button type="reset" class="btn btn-secondary" onclick="onBack()"><i class="flaticon2-cancel-music"></i> Batal</button>
								</div>
							</div>
						</div>
					</div> -->
					<div class="card-footer">
						<div class="row">
							<div class="col-4 text-left">
								<!-- <button type="button" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-arrow-left"></i> Back</button> -->
								<button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>
								<button type="reset" style="display: none;" id="triggerReset"><i class="fa fa-arrow-left"></i> Batal</button>
							</div>
							<div class="col-8 text-right">
								<input type="checkbox" name="cetak_checkbox" id="cetak_checkbox" value="cetak" checked="checked"> <i class="flaticon2-print"></i> Cetak
								<button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Simpan</button>
								<!-- <button type="submit" class="btn btn-sm btn-success"><i class="flaticon2-cancel-music"></i> Save</button> -->
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="card card-custom cetak_data" style="display: none">
	<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
		<div class="ribbon-target" style="top: 12px;">
			<span class="ribbon-inner bg-primary"></span> CETAK POSTING PERSEDIAAN
		</div>
		<div class="card-toolbar">
			<button type="reset" class="btn btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Kembali</button>
		</div>
	</div>
	<div class="card-body table-responsive" id="pdf-laporan">
		<object data="" type="application/pdf" width="100%" height="500px"></object>
	</div>
</div>