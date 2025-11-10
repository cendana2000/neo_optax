<div class="modal fade" tabindex="-1" role="dialog" id="modalData">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Form Register Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form action="javascript:save('form-user')" method="post" id="form-user" autocomplete="off">
            <div class="card-body">
					<div class="row">
						<div class="col-12 col-md-6">
							<input type="hidden" name="project_request_id" id="project_request_id">
							<div class="form-group ">
								<label class="text-left" for="project_request_location">Project Location</label>
								<input type="text" name="project_request_location" class="form-control project_request_location" placeholder="Project Location" required minlength="3" maxlength="150" data-fv-not-empty___message="This field is required">
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_request_description">Project Description</label>
								<textarea name="project_request_description" class="form-control project_request_description" placeholder="Project Description" required minlength="3" maxlength="150" data-fv-not-empty___message="This field is required"></textarea>
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_request_open_hole">Open Hole Plan (m)</label>
								<input type="number" name="project_request_open_hole" class="form-control project_request_open_hole" placeholder="Open Hole Plan (m)" step=".01">
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_request_coring">Coring Plan (m)</label>
								<input type="number" name="project_request_coring" class="form-control project_request_coring" placeholder="Coring Plan (m)">
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_request_borehole">Number of Borehole</label>
								<input type="number" name="project_request_borehole" class="form-control project_request_borehole" placeholder="Number of Borehole">
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_request_sample">Total Sample</label>
								<input type="number" name="project_request_sample" class="form-control project_request_sample" placeholder="Total Sample">
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group ">
								<label class="text-left" for="project_request_start_date">Project Start</label>
								<div class="input-group date">
									<input type="text" name="project_request_start_date" class="form-control datepicker project_request_start_date" placeholder="DD-MM-YYYY" readonly>
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="la la-calendar-check-o"></i>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group ">
								<label class="text-left" for="project_request_end_date">Project End</label>
								<div class="input-group date">
									<input type="text" name="project_request_end_date" class="form-control datepicker project_request_end_date" placeholder="DD-MM-YYYY" readonly>
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="la la-calendar-check-o"></i>
										</span>
									</div>
								</div>
							</div>
                            <div class="form-group ">
								<label class="text-left" for="project_request_pic_name">PIC Name</label>
								<input type="text" name="project_request_pic_name" class="form-control project_request_pic_name" placeholder="PIC Name" required data-fv-not-empty___message="This field is required"> 
							</div>
                            <div class="form-group ">
								<label class="text-left" for="project_request_pic_address">PIC Address</label>
								<input type="text" name="project_request_pic_address" class="form-control project_request_pic_address" placeholder="PIC Address" required data-fv-not-empty___message="This field is required">
							</div>
                            <div class="form-group ">
								<label class="text-left" for="project_request_pic_phone">PIC Phone</label>
								<input type="number" name="project_request_pic_phone" class="form-control project_request_pic_phone" placeholder="PIC Phone" required data-fv-not-empty___message="This field is required">
							</div>
                            <div class="form-group ">
								<label class="text-left" for="project_request_pic_email">PIC Email</label>
								<input type="email" name="project_request_pic_email" class="form-control project_request_pic_email" placeholder="PIC Email" required data-fv-not-empty___message="This field is required" data-fv-email-address___message="The input is not a valid email address">
							</div>
						</div>
					</div>
				</div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-sm btn-danger mx-1" data-dismiss="modal" aria-label="Close"> Close</button>
                            <button type="submit" class="btn btn-sm btn-success mx-1"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>