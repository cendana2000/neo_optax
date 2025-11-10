<div class="row form_data" style="display: none">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>Form Data Status
				</div>
			</div>
			<form action="javascript:save('form-data-status')" method="post" id="form-data-status" autocomplete="off">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<input type="hidden" name="data_status_id">
							<div class="form-group row">
								<label class="col-lg-3 col-form-label text-left" for="data_status_code">Code</label>
								<div class="col-lg-9">
									<input type="text" name="data_status_code" class="form-control data_status_code" placeholder="Data Status Code" required data-fv-not-empty___message="This field is required" minlength="1" maxlength="20">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label text-left" for="data_status_description">Description</label>
								<div class="col-lg-9">
									<input type="text" name="data_status_description" class="form-control data_status_description" placeholder="Data Status Description" minlength="3" maxlength="150">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-4 text-left">
							<button type="button" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-arrow-left"></i> Back</button>
						</div>
						<div class="col-8 text-right">
							<button type="submit" id="btnSave" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Save</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>