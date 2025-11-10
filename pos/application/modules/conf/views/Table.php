<div class="row">
	<div class="col-12 col-md-4 mb-3">
		<!--begin::Profile Card-->
		<div class="card card-custom card-stretch h-auto">
			<!--begin::Body-->
			<div class="card-body pt-4">
				<!--begin::Nav-->
				<div class="navi navi-bold navi-hover navi-active navi-link-rounded">
					<div class="navi-item mb-2">
						<a href="javascript:void(0)" onclick="openTab(this, 'sosial-media')" class="navi-link nav-conf py-4 active">
							<span class="navi-icon mr-2">
								<i class="fas fa-file-invoice"></i>
							</span>
							<span class="navi-text font-size-lg">Struk Kasir</span>
						</a>
					</div>
				</div>
				<!--end::Nav-->
			</div>
			<!--end::Body-->
		</div>
		<!--end::Profile Card-->
	</div>
	<div class="col">

		<div class="card card-custom tab-conf tab-sosial-media">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">PENGATURAN STRUK KASIR</h3>
				</div>
			</div>
			<form action="javascript:save('form-sosial-media')" method="post" id="form-sosial-media">
				<div class="card-body">
					<div class="accordion accordion-solid accordion-toggle-plus" id="accordionExample7">
						<div class="card">
							<div class="card-header" id="headingOne7">
								<div class="card-title" data-toggle="collapse" data-target="#collapseOne7">
									<i class="flaticon2-list-1"></i> Header Struk
								</div>
							</div>
							<div id="collapseOne7" class="collapse show" data-parent="#accordionExample7">
								<div class="card-body pl-12">
									<div class="form-group d-flex flex-column">
										<label for="struk_logo">Logo</label>
										<div>
											<div class="image-input image-input-empty image-input-outline" id="struk_logo_img" style="background-image: url(assets/media/users/blank.png)">
												<div class="image-input-wrapper"></div>

												<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
													<i class="fa fa-pen icon-sm text-muted"></i>
													<input type="file" name="struk_logo" accept=".png, .jpg, .jpeg" />
													<!-- <input type="hidden" name="struk_logo_remove"/> -->
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
									<div class="form-group row">
										<label class="col-3 col-form-label" for="struk_is_logo">Tampilkan Logo</label>
										<div class="col-3">
											<span class="switch switch-icon">
												<label>
													<input type="checkbox" name="struk_is_logo" id="struk_is_logo" value="true" />
													<span></span>
												</label>
											</span>
										</div>
									</div>
									<div class="form-group">
										<label for="struk_header">Title</label>
										<input type="text" name="struk_header" class="form-control" id="struk_header" placeholder="Title">
									</div>
									<div class="form-group row">
										<label class="col-3 col-form-label" for="struk_is_title_show">Nama Toko</label>
										<div class="col-3">
											<span class="switch switch-icon">
												<label>
													<input type="checkbox" name="struk_is_title_show" id="struk_is_title_show" value="true" />
													<span></span>
												</label>
											</span>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-3 col-form-label" for="struk_is_antrian">No Antrian</label>
										<div class="col-3">
											<span class="switch switch-icon">
												<label>
													<input type="checkbox" name="struk_is_antrian" id="struk_is_antrian" value="true" />
													<span></span>
												</label>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header" id="headingTwo7">
								<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo7">
									<i class="flaticon2-list-1"></i> Footer Struk
								</div>
							</div>
							<div id="collapseTwo7" class="collapse hide" data-parent="#accordionExample7">
								<div class="card-body pl-12">
									<div class="form-group">
										<label for="struk_footer">Notes</label>
										<input type="text" name="struk_footer" class="form-control" id="struk_footer" placeholder="Notes">
									</div>
								</div>
								<div class="card-body pl-12">
									<div class="form-group">
										<label for="struk_ig">Instagram</label>
										<input type="text" name="struk_ig" class="form-control" id="struk_ig" placeholder="Instagram">
									</div>
									<div class="form-group">
										<label for="struk_fb">Facebook</label>
										<input type="text" name="struk_fb" class="form-control" id="struk_fb" placeholder="Facebook">
									</div>
									<div class="form-group">
										<label for="struk_wa">WhatsApp</label>
										<input type="text" name="struk_wa" class="form-control" id="struk_wa" placeholder="WhatsApp">
									</div>
									<div class="form-group">
										<label for="struk_tw">Twitter</label>
										<input type="text" name="struk_tw" class="form-control" id="struk_tw" placeholder="Twitter">
									</div>
									<div class="form-group">
										<label for="struk_yt">Youtube</label>
										<input type="text" name="struk_yt" class="form-control" id="struk_yt" placeholder="Twitter">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-12 text-right">
							<button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i>Save</button>
						</div>
					</div>
				</div>
			</form>
		</div>

	</div>
</div>

<?php load_view('Javascript') ?>