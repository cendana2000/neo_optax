<div class="row form_data" style="display: none">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>Form Project
				</div>
			</div>
			<form action="javascript:save('form-project')" method="post" id="form-project" autocomplete="off">
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="form-group request_list">
								<label class="text-left pr-0" for="project_request_id">requested project</label>
								<select class="form-control" id="project_request_id" name="project_request_id">
								</select>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<input type="hidden" name="project_id" id="project_id">
							<div class="form-group ">
								<label class="text-left" for="project_name">Project Name</label>
								<input type="text" name="project_name" class="form-control project_name" placeholder="Project name" required data-fv-not-empty___message="This field is required" minlength="3">
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_code">Project Code</label>
								<input type="text" name="project_code" class="form-control project_code" placeholder="Project Code" required data-fv-not-empty___message="This field is required" minlength="3" maxlength="20">
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_location">Project Location</label>
								<input type="text" name="project_location" class="form-control project_location" placeholder="Project Location" required data-fv-not-empty___message="This field is required" minlength="3" maxlength="150">
							</div>

							<div class="form-group ">
								<label class="text-left" for="project_hole_plan">Open Hole Plan (m)</label>
								<input type="text" name="project_hole_plan" class="form-control project_hole_plan mask-int" placeholder="Open Hole Plan (m)" required data-fv-not-empty___message="This field is required" min="1" step=".01">
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_core_plan">Coring Plan (m)</label>
								<input type="text" name="project_core_plan" class="form-control project_core_plan mask-int" placeholder="Coring Plan (m)" required data-fv-not-empty___message="This field is required" min="1">
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_description">Project Description</label>
								<textarea name="project_description" class="form-control project_description" placeholder="Project Description" minlength="3" maxlength="150"></textarea>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group ">
								<label class="text-left" for="project_borehole_plan">Number of Borehole</label>
								<input type="texr" name="project_borehole_plan" class="form-control project_borehole_plan mask-int" placeholder="Number of Borehole" required data-fv-not-empty___message="This field is required" min="1">
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_total_sample_plan">Total Sample</label>
								<input type="text" name="project_total_sample_plan" class="form-control project_total_sample_plan mask-int" placeholder="Total Sample" required data-fv-not-empty___message="This field is required" min="1">
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_start_date">Project Start</label>
								<div class="input-group date">
									<input type="text" name="project_start_date" class="form-control datepicker project_start_date" placeholder="DD-MM-YYYY" readonly>
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="la la-calendar-check-o"></i>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_end_date">Project End</label>
								<div class="input-group date">
									<input type="text" name="project_end_date" class="form-control datepicker project_end_date" placeholder="DD-MM-YYYY" readonly>
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="la la-calendar-check-o"></i>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_map_link">Map Link</label>
								<input type="text" name="project_map_link" class="form-control project_map_link" placeholder="Map Link">
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