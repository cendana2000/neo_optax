<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container">
		<!--begin::Profile Overview-->
		<div class="d-flex flex-row">
			<!--begin::Aside-->
			<div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px" id="kt_profile_aside">
				<!--begin::Profile Card-->
				<div class="card card-custom">
					<!--begin::Body-->
					<div class="card-body pt-4">
						<!--begin::User-->
						<div class="d-flex align-items-center">
							<div class="symbol symbol-60 symbol-xxl-100 mr-5 mb-5 align-self-start align-self-xxl-center">
								<div class="symbol-label show-foto" style="background-image:url('assets/media/users/300_21.jpg')"></div>
								<i class="symbol-badge bg-success"></i>
							</div>
							<div>
								<span class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary show-nama" id="name">-</span>
								<div class="text-muted show-hak_akses" id="hak_akses">-</div>
							</div>
						</div>
						<!--end::User-->
						<!--begin::Contact-->

						<!--end::Contact-->
						<!--begin::Nav-->
						<div class="navi navi-bold navi-hover navi-active navi-link-rounded">
							<ul class="nav flex-column nav-pills nav-light-primary nav-bold nav-pills">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#tab_biodata">
										<span class="nav-icon">
											<i class="fa fa-user fa-lg"></i>
										</span>
										<span class="nav-text">Biodata</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#tab_password">
										<span class="nav-icon">
											<i class="fa fa-lock fa-lg"></i>
										</span>
										<span class="nav-text">Change Password</span>
									</a>
								</li>
							</ul>
						</div>
						<!--end::Nav-->
					</div>
					<!--end::Body-->
				</div>
				<!--end::Profile Card-->
			</div>
			<!--end::Aside-->
			<!--begin::Content-->
			<div class="flex-row-fluid ml-lg-8">
				<!--begin::Row-->
				<div class="row">
					<div class="col-lg-12">
						<div class="tab-content">
							<div class="tab-pane fade active show" id="tab_biodata" role="tabpanel" aria-labelledby="tab_biodata">
								<div class="card card-custom card-stretch gutter-b">
									<!--begin::Header-->
									<div class="card-header">
										<h3 class="card-title font-weight-bolder text-dark">Biodata</h3>
										<button type="button" class="btn btn-primary float-right my-5 btn-edit" onclick="onEdit()">Edit Profile</button>
									</div>
									<!--end::Header-->
									<!--begin::Body-->
									<div class="card-body pt-2">
										<div class="detail-profile">
											<div class="row">
												<div class="col-md-12">
													<input type="hidden" name="profile_id" id="profile_id">
													<div class="form-group row my-2">
														<label class="col-4 col-form-label">Permission</label>
														<div class="col-8">
															<span class="form-control-plaintext font-weight-bolder detail-hak_akses_nama"></span>
														</div>
													</div>
													<div class="form-group row my-2">
														<label class="col-4 col-form-label">Name </label>
														<div class="col-8">
															<span class="form-control-plaintext font-weight-bolder detail-user_nama"></span>
														</div>
													</div>
													<div class="form-group row my-2">
														<label class="col-4 col-form-label">Address </label>
														<div class="col-8">
															<span class="form-control-plaintext font-weight-bolder detail-user_alamat"></span>
														</div>
													</div>
													<div class="form-group row my-2">
														<label class="col-4 col-form-label">Phone </label>
														<div class="col-8">
															<span class="form-control-plaintext font-weight-bolder detail-user_telepon"></span>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group row my-2">
														<label class="col-4 col-form-label">Email </label>
														<div class="col-8">
															<span class="form-control-plaintext font-weight-bolder detail-user_email"></span>
														</div>
													</div>
													<div class="form-group row my-2">
														<label class="col-4 col-form-label pr-0">Last Change Password</label>
														<div class="col-8">
															<span class="form-control-plaintext font-weight-bolder detail-last_change_password"></span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="edit-profile" style="display: none;">
											<form action="javascript:save('form-profile')" method="post" id="form-profile" autocomplete="off">
												<div class="card-body">
													<div class="row">
														<div class="col-md-12">
															<input type="hidden" name="user_id" id="user_id">
															<div class="form-group row">
																<label class="col-lg-3 col-form-label text-left" for="user_nama">Name</label>
																<div class="col-lg-9">
																	<input type="text" id="user_nama" name="user_nama" class="form-control user_nama" placeholder="Name" required data-fv-not-empty___message="This field is required" minlength="3" maxlength="150">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-lg-3 col-form-label text-left" for="user_alamat">Address</label>
																<div class="col-lg-9">
																	<textarea type="textarea" id="user_alamat" name="user_alamat" class="form-control user_alamat" placeholder="Address" required data-fv-not-empty___message="This field is required"></textarea>
																</div>
															</div>
															<div class="form-group row">
																<label class="col-lg-3 col-form-label text-left" for="user_telepon">Phone</label>
																<div class="col-lg-9">
																	<input type="number" id="user_telepon" min="0" name="user_telepon" class="form-control user_telepon" placeholder="Phone" required data-fv-not-empty___message="This field is required">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-lg-3 col-form-label text-left" for="user_email">Email</label>
																<div class="col-lg-9">
																	<input type="email" id="user_email" name="user_email" class="form-control user_email" placeholder="Email" required data-fv-not-empty___message="This field is required" data-fv-email-address___message=" ">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-lg-3 col-form-label text-left" for="user_foto">Photo</label>
																<div class="col-lg-9">
																	<div class="image-input image-input-empty image-input-outline" id="user_foto" style="background-image: url(./assets/media/noimage.png)">
																		<div class="image-input-wrapper"></div>
																		<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
																			<i class="fa fa-pen icon-sm text-muted"></i>
																			<input type="file" id="user_foto_input" name="user_foto" accept=".png, .jpg, .jpeg" />
																			<input type="hidden" name="profile_avatar_remove" />
																		</label>

																		<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
																			<i class="ki ki-bold-close icon-xs text-muted"></i>
																		</span>

																		<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
																			<i class="ki ki-bold-close icon-xs text-muted"></i>
																		</span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-12 text-right">
															<button type="button" class="btn btn-sm btn-danger mx-1" onclick="onBack()"> Back</button>
															<button type="submit" class="btn btn-sm btn-success mx-1"><i class="fas fa-save"></i> Save</button>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
									<!--end::Body-->
								</div>
							</div>
							<div class="tab-pane fade" id="tab_password" role="tabpanel" aria-labelledby="tab_password">
								<div class="card card-custom card-stretch gutter-b">
									<!--begin::Header-->
									<div class="card-header border-0">
										<h3 class="card-title font-weight-bolder text-dark">Change Password</h3>
									</div>
									<!--end::Header-->
									<!--begin::Body-->
									<div class="card-body pt-2">
										<form action="javascript:savePassword('form-edit-password')" method="post" id="form-edit-password" autocomplete="off">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group row">
														<label class="col-lg-3 col-form-label text-left" for="password_old">Old Password</label>
														<div class="col-lg-8">
															<div class="input-group">
																<input type="password" class="form-control" id="password_old" name="password_old" placeholder="Old Password" required data-fv-not-empty___message="This field is required">
																<div class="input-group-append">
																	<button class="btn btn-secondary" onclick="onOldPassSee('password_old')" type="button"><i id='icon-password_old' class="fa fa-eye"></i></button>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-lg-3 col-form-label text-left" for="password_new" id="password_new_label">New Password</label>
														<div class="col-lg-8">
															<div class="input-group">
																<input type="password" class="form-control" id="password_new" name="password_new" aria-describedby="password_new" placeholder="New Password" autocomplete="off" minlength="8" required data-fv-not-empty___message="This field is required">
																<div class="input-group-append">
																	<button class="btn btn-secondary" onclick="onOldPassSee('password_new')" type="button"><i id='icon-password_new' class="fa fa-eye "></i></button>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-lg-3 col-form-label text-left" for="password_repeat" id="password_repeat_label">Repeat New Password</label>
														<div class="col-lg-8">
															<div class="input-group">
																<input type="password" class="form-control m-input" id="password_repeat" name="password_repeat" aria-describedby="password_repeat" placeholder="Repeat New Password" autocomplete="off" minlength="8" required data-fv-not-empty___message="This field is required">
																<div class="input-group-append">
																	<button class="btn btn-secondary" onclick="onOldPassSee('password_repeat')" type="button"><i id='icon-password_repeat' class="fa fa-eye "></i></button>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="card-footer pb-0">
												<div class="row">
													<div class="col-12 text-right">
														<button type="submit" class="btn btn-sm btn-success btn-simpan"><i class="fas fa-save"></i> Save</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<!--end::Body-->
								</div>
							</div>
						</div>

					</div>
				</div>
				<!--end::Row-->
			</div>
			<!--end::Content-->
		</div>
		<!--end::Profile Overview-->
	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->

<?php load_view('Javascript') ?>