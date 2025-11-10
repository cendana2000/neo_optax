<div class="modal fade" tabindex="-1" role="dialog" id="modalData">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-title">Form User</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form action="javascript:save('form-user')" method="post" id="form-user" autocomplete="off">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<input type="hidden" name="pegawai_id" id="pegawai_id">
							<div class="form-group row">
								<label class="col-lg-3 col-form-label text-left pr-0" for="pegawai_role_access_id">Select Permission</label>
								<div class="col-lg-9">
									<select class="form-select" id="pegawai_role_access_id" name="pegawai_role_access_id"></select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label text-left" for="pegawai_nama">Name</label>
								<div class="col-lg-9">
									<input type="text" id="pegawai_nama" name="pegawai_nama" class="form-control pegawai_nama" placeholder="Name" required data-fv-not-empty___message="This field is required" minlength="3" maxlength="150">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label text-left" for="pegawai_alamat">Address</label>
								<div class="col-lg-9">
									<textarea type="textarea" id="pegawai_alamat" name="pegawai_alamat" class="form-control pegawai_alamat" placeholder="Address" required data-fv-not-empty___message="This field is required"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label text-left" for="pegawai_hp">Phone</label>
								<div class="col-lg-9">
									<input type="number" id="pegawai_hp" min="0" name="pegawai_hp" class="form-control pegawai_hp" placeholder="Phone" required data-fv-not-empty___message="This field is required">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label text-left" for="pegawai_email">Email</label>
								<div class="col-lg-9">
									<input type="email" id="pegawai_email" name="pegawai_email" class="form-control pegawai_email" placeholder="Email" required data-fv-not-empty___message="This field is required" data-fv-email-address___message=" ">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-lg-3 col-form-label text-left" for="pegawai_foto">Photo</label>
								<div class="col-lg-9">
									<div class="image-input image-input-empty image-input-outline" id="pegawai_foto" style="background-image: url(./assets/media/noimage.png)">
										<div class="image-input-wrapper"></div>
										<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
											<i class="fa fa-pen icon-sm text-muted"></i>
											<input type="file" name="pegawai_foto" accept=".png, .jpg, .jpeg" />
											<input type="hidden" name="profile_avatar_remove" />
										</label>

										<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
											<i class="ki ki-bold-close icon-xs text-muted"></i>
										</span>

										<!-- <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
											<i class="ki ki-bold-close icon-xs text-muted"></i>
										</span> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-12 text-right">
							<button type="button" class="btn btn-sm btn-danger mx-1" data-bs-dismiss="modal" aria-label="Close"> Close</button>
							<button type="submit" class="btn btn-sm btn-success mx-1"><i class="fas fa-save"></i> Save</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Modal-->
<div class="modal fade" id="modal-user" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">User Detail</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<div class="tab-content">
							<div class="tab-pane fade show active" id="tab_data_user">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group row my-2">
											<label class="col-3 col-form-label">User Photo</label>
											<div class="col-9">
												<a class="example-image-link detail-foto-user-full" href="" data-lightbox="">
													<img src="./assets/media/noimage.png" class="symbol example-image border border-secondary detail-foto-user w-10">
												</a>
											</div>
										</div>
										<div class="form-group row my-2">
											<label class="col-3 col-form-label">Name</label>
											<div class="col-9">
												<span class="form-control-plaintext font-weight-bolder detail-pegawai_nama"></span>
											</div>
										</div>
										<div class="form-group row my-2">
											<label class="col-3 col-form-label">Address</label>
											<div class="col-9">
												<span class="form-control-plaintext font-weight-bolder detail-pegawai_alamat"></span>
											</div>
										</div>
										<div class="form-group row my-2">
											<label class="col-3 col-form-label">Phone</label>
											<div class="col-9">
												<span class="form-control-plaintext font-weight-bolder detail-pegawai_hp"></span>
											</div>
										</div>
										<div class="form-group row my-2">
											<label class="col-3 col-form-label">Email</label>
											<div class="col-9">
												<span class="form-control-plaintext font-weight-bolder detail-pegawai_email"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-danger font-weight-bold" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-user-project" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">User Project Detail</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 col-md-6">
						<form action="javascript:saveProject()" id="form-user-project" method="post" accept-charset="utf-8">
							<input type="hidden" name="user_project_user_id" id="user_project_user_id">
							<div class="form-group">
								<label class="col-form-label" for="project_code">Project</label>
								<select class="form-control" name="user_project_project_id" id="user_project_project_id" required data-fv-not-empty___message="This field is required"></select>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Save</button>
							</div>
						</form>
					</div>
					<div class="col-12 col-md-6 table-responsive">
						<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-user-project">
							<thead>
								<tr>
									<th>No</th>
									<th>Project</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-danger font-weight-bold" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>