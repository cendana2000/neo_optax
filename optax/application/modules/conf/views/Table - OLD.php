<div class="row">
    <div class="col-12 col-md-4 mb-3">
        <!--begin::Profile Card-->
        <div class="card card-custom card-stretch h-auto">
            <!--begin::Body-->
            <div class="card-body pt-4">
                <!--begin::Nav-->
                <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                    <div class="navi-item mb-2">
                        <a href="javascript:void(0)" onclick="openTab(this, 'conf-app')" class="navi-link nav-conf py-4 active">
                            <span class="navi-icon mr-2">
                                <i class="fa fa-home"></i>
                            </span>
                            <span class="navi-text font-size-lg">Conf Site</span>
                        </a>
                    </div>
                    <div class="navi-item mb-2">
                        <a href="javascript:void(0)" onclick="openTab(this, 'email')" class="navi-link nav-conf py-4">
                            <span class="navi-icon mr-2">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <span class="navi-text font-size-lg">Email</span>
                        </a>
                    </div>
                    <div class="navi-item mb-2">
                        <a href="javascript:void(0)" onclick="openTab(this, 'kontak')" class="navi-link nav-conf py-4">
                            <span class="navi-icon mr-2">
                                <i class="fas fa-phone"></i>
                            </span>
                            <span class="navi-text font-size-lg">Contact</span>
                        </a>
                    </div>
                    <div class="navi-item mb-2 d-none">
                        <a href="javascript:void(0)" onclick="openTab(this, 'sosial-media')" class="navi-link nav-conf py-4">
                            <span class="navi-icon mr-2">
                                <i class="fas fa-globe"></i>
                            </span>
                            <span class="navi-text font-size-lg">Sosial Media</span>
                        </a>
                    </div>
                    <div class="navi-item mb-2 d-none">
                        <a href="javascript:void(0)" onclick="openTab(this, 'jadwal')" class="navi-link nav-conf py-4">
                            <span class="navi-icon mr-2">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <span class="navi-text font-size-lg">Jadwal Cek Kendaraan</span>
                        </a>
                    </div>
                    <div class="navi-item mb-2 d-none">
                        <a href="javascript:void(0)" onclick="openTab(this, 'jam-kerja')" class="navi-link nav-conf py-4">
                            <span class="navi-icon mr-2">
                                <i class="fa fa-clock"></i>
                            </span>
                            <span class="navi-text font-size-lg">Jam Kerja</span>
                        </a>
                    </div>
                    <div class="navi-item mb-2 d-none">
                        <a href="javascript:void(0)" onclick="openTab(this, 'jarak')" class="navi-link nav-conf py-4">
                            <span class="navi-icon mr-2">
                                <i class="fa fa-map-marked-alt"></i>
                            </span>
                            <span class="navi-text font-size-lg">Jarak Absensi Driver</span>
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

        <div class="card card-custom tab-conf tab-conf-app">
            <div class="card-header">
            <div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">Setting Site</h3>
				</div>      
            </div>
            <form action="javascript:save('form-conf-app')" method="post" id="form-conf-app">
                <div class="card-body">
                    <div class="form-group">
                        <label for="app_title">App Title</label>
                        <input type="tel" name="app_title" class="form-control" id="app_title" placeholder="Application Title" required>
                    </div>
                    <div class="form-group">
                        <label for="password_default">Password Default</label>
                        <input type="text" name="password_default" class="form-control" id="password_default" placeholder="Password Default" required>
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

        <div class="card card-custom tab-conf tab-email" style="display: none;">
            <div class="card-header">
            <div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">Email</h3>
				</div>           
            </div>
            <form action="javascript:save('form-email')" method="post" id="form-email">
                <div class="m-portlet__body">
                    <div class="mx-auto col-11 m-alert m-alert--icon alert alert-primary" role="alert">
                        <div class="m-alert__icon">
                            <i class="flaticon-info"></i>
                        </div>
                        <div class="m-alert__text">
                            <ol>
                                <li> To change the email, leave the new password and repeat password blank</li>
                                <li> If you change your email, the email will not be changed immediately but must be confirmed to a new email with a confirmation period of 24 hours after changing from the system.</li>
                                <li> To change the password, you must enter a new password and repeat the password</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group m-form__group row">
                        <label class="col-form-label control-label col-md-3 col-sm-12">SMTP Host</label>
                        <div class="col-md-9 col-sm-12">
                            <input type="text" name="app_email_smtp_host" class="form-control" id="app_email_smtp_host" placeholder="SMTP Host" />
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-form-label control-label col-md-3 col-sm-12">SMTP Port</label>
                        <div class="col-md-9 col-sm-12">
                            <input type="text" name="app_email_smtp_port" class="form-control" id="app_email_smtp_port" placeholder="SMTP Port" />
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-form-label control-label col-md-3 col-sm-12">Email Name</label>
                        <div class="col-md-9 col-sm-12">
                            <input type="text" name="app_email_name" class="form-control" id="app_email_name" placeholder="Nama Email" required>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-form-label control-label col-md-3 col-sm-12">Email</label>
                        <div class="col-md-9 col-sm-12">
                            <input type="email" class="form-control" name="app_email" id="app_email" placeholder="Email" />
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-form-label control-label col-md-3 col-sm-12">Password</label>
                        <div class="col-md-9 col-sm-12">
                            <input type="password" class="form-control m-input" name="password_old" id="password_old" placeholder="Password" />
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-3 col-form-label control-label" id="password_new_label">New Password</label>
                        <div class="col-md-9 col-sm-12">
                            <input type="Password" class="form-control m-input" id="password_new" name="password_new" aria-describedby="password_new" placeholder="New Password" minlength="8" autocomplete="off" onkeyup="onPasswordNew(this.value)">
                            <span class="m-form__help">Password is at least 8 characters long.</span>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-3 col-form-label control-label" id="password_repeat_label">Repeat Password</label>
                        <div class="col-md-9 col-sm-12">
                            <input type="Password" class="form-control m-input" id="password_repeat" name="app_email_password" aria-describedby="password_repeat" placeholder="Repeat Password" minlength="8" autocomplete="off" oninput="cek_ulangi_password(this.value);">
                            <div class="col-sm-10 m-form__help" id="u_pass">
                                <span class="m-form__help control-label text-success" for="inputSuccess" id="u_sep" style="display: none;"><strong style="color: red">*</strong> Password benar. &nbsp&nbsp&nbsp&nbsp<i style="color: green" class="fa fa-check"></i> </span>
                                <span class="m-form__help control-label text-danger" for="inputError" id="u_eep" style="display: none;"><strong style="color: red">*</strong> Password tidak sama. &nbsp&nbsp&nbsp&nbsp<i style="color: red" class="fa fa-times "></i> </span>
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

        <div class="card card-custom tab-conf tab-kontak" style="display: none;">
            <div class="card-header">
            <div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">Contact</h3>
				</div>             
            </div>
            <form action="javascript:save('form-kontak')" method="post" id="form-kontak">
                <div class="card-body">
                    <div class="form-group">
                        <label for="app_telp">Phone Number</label>
                        <input type="text" name="app_telp" class="form-control" id="app_telp" placeholder="Telepon" required>
                    </div>
                    <div class="form-group">
                        <label for="app_alamat">Address</label>
                        <input type="text" name="app_alamat" class="form-control" id="app_alamat" placeholder="Alamat" required>
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