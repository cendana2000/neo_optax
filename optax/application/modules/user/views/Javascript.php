<script type="text/javascript">
	var avatar5 = new KTImageInput('pegawai_foto');
	var fv;
	$(function() {

		HELPER.fields = [
			'pegawai_id',
			'pegawai_role_access_id',
			'pegawai_nama',
			'pegawai_alamat',
			'pegawai_hp',
			'pegawai_email',
			'pegawai_status',
			'pegawai_foto',
			'pegawai_lat',
			'pegawai_long',
			'pegawai_password',
		];
		HELPER.api = {
			table: BASE_URL + 'user/loadTable',
			store: BASE_URL + 'user/store',
			update: BASE_URL + 'user/update',
			read: BASE_URL + 'user/read',
			delete: BASE_URL + 'user/delete',
			resetPassword: BASE_URL + 'user/resetPassword',
			cekEmail: BASE_URL + 'user/cekEmail',
			tableProject: BASE_URL + 'user/loadTableProject',
		};
		HELPER.createCombo({
			el: ['pegawai_role_access_id'],
			valueField: 'role_access_id',
			displayField: 'role_access_nama',
			url: BASE_URL + 'user/combobox_role',
			withNull: true,
			grouped: false,
			select2: true, // ubah ini
			callback: function() {}
		});

		fv = HELPER.newHandleValidation({
			el: 'form-user',
			useregex: true,
			declarative: true,
			setting: [{
				name: "Email user",
				selector: ".pegawai_email",
				rule: {
					promise: {
						promise: function(input) {

							return new Promise(function(resolve, reject) {

								var cekValid = FormValidation.validators.emailAddress().validate({
									value: $(input.element).val()
								});

								if (cekValid.valid) {
									HELPER.ajax({
										url: HELPER.api.cekEmail,
										data: {
											email: $(input.element).val()
										},
										success: function(res) {
											if (res.success) {
												resolve({
													valid: true // Required
												});
											} else {
												if ($('#pegawai_id').val() == res.id) {
													resolve({
														valid: true // Required
													});
												} else {
													resolve({
														valid: false, // Required
														message: 'Email must be unique', // Optional
													});
												}
											}
										},
										error: function() {
											resolve({
												valid: false, // Required
												message: 'Check failed.', // Optional
											});
										}
									})
								} else {
									resolve({
										valid: false, // Required
										message: 'The input is not a valid email address.', // Optional
									});
								}

							});

						}
					}
				},
			}]
		});

		loadTable()
	})

	function loadTable(filter = null) {
		show_aksi = (HELPER.get_role_access('User-Read') || HELPER.get_role_access('User-Update') || HELPER.get_role_access('User-Delete') || HELPER.get_role_access('User-Status'))
		show_dropdown = (HELPER.get_role_access('User-Update') || HELPER.get_role_access('User-Delete') || HELPER.get_role_access('User-Status'))
		HELPER.initTable({
			el: "table-user",
			url: HELPER.api.table,
			data: filter,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['pegawai_nama'];
					},
				},
				{
					targets: 2,
					orderable: false,
					render: function(data, type, full, meta) {
						return full['pegawai_hp'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['pegawai_email'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						status = full['pegawai_status'];
						if (status == 1) {
							return '<span class="label label-inline label-success">Active</span>'
						}
						return '<span class="label label-inline label-danger">Not Active</span>'
					},
				},
				{
					targets: 5,
					width: '20px',
					orderable: false,
					render: function(data, type, full, meta) {
						btn_aksi = ""
						if (HELPER.get_role_access('User-Read')) {
							btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary" onclick="onDetail('` + full['pegawai_id'] + `')">\
											Detail
										</a>`;
						}
						menu = ""
						dropdown = ""
						if (HELPER.get_role_access('User-Update')) {
							menu += `<li class="nav-item"><a class="nav-link" href="javascript:;" onclick="onEdit('` + full['pegawai_id'] + `')"><i class="nav-icon fa fa-pen"></i><span class="nav-text">Edit</span></a></li>`;
							// menu += `<li class="nav-item"><a class="nav-link" href="javascript:;" onclick="onSetProject('` + full['pegawai_id'] + `')"><i class="nav-icon fa fa-pen"></i><span class="nav-text">Set Project</span></a></li>`;
						}
						if (HELPER.get_role_access('User-Delete')) {
							menu += `<li class="nav-item"><a class="nav-link" href="javascript:;" onclick="onDelete('` + full['pegawai_id'] + `')"><i class="nav-icon fa fa-trash"></i><span class="nav-text">Delete</span></a></li>`;
						}
						if (HELPER.get_role_access('User-ResetPassword')) {
							menu += `<li class="nav-item"><a class="nav-link" href="javascript:;" onclick="onResetPassword('` + full['pegawai_id'] + `')"><i class="nav-icon fa fa-undo"></i><span class="nav-text">Reset Password</span></a></li>`;
						}
						if (HELPER.get_role_access('User-Status')) {
							status = full['user_status'];
							if (status == 1) {
								menu += `<li class="nav-item"><a class="nav-link" href="javascript:;" onclick="onStatus('` + full['pegawai_id'] + `')"><i class="nav-icon fa fa-times"></i><span class="nav-text">Set Non Active</span></a></li>`;
							} else {
								menu += `<li class="nav-item"><a class="nav-link" href="javascript:;" onclick="onStatus('` + full['pegawai_id'] + `')"><i class="nav-icon fa fa-check"></i><span class="nav-text">Set Active</span></a></li>`;
							}
						}
						if (show_dropdown) {
							dropdown += `<div class="dropdown dropdown-inline">
											<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">
													<i class="fa fa-cog"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
												<ul class="nav nav-hoverable flex-column">
													` + menu + `
												</ul>
											</div>
										</div>`;
						}
						return btn_aksi + dropdown;
					},
				},
			],
		});
	}

	function onCreate() {
		$.each(HELPER.fields, function(i, v) {
			$('[name="' + v + '"]').val('').trigger('change');
		});
		$("[data-action='cancel']").click()
		$('#pegawai_foto').css('background-image', 'url(./assets/media/noimage.png)');
		$("#modal-title").html('Add User')
		$("#modalData").modal('show')
		fv.resetForm(true)
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-user'
		});
	}


	function save(name) {
		var form = $('#' + name)[0];
		var formData = new FormData(form);
		HELPER.save({
			cache: false,
			data: formData,
			contentType: false,
			processData: false,
			form: "user_app-form",
			confirm: true,
			callback: function(success, id, record, message) {
				if (success) {
					HELPER.showMessage({
						success: true,
						title: "Success",
						message: "Successfully saved data"
					});

					$("#modalData").modal('hide')
					loadTable()
				} else {
					HELPER.showMessage({
						success: false
					})
				}
				HELPER.unblock(100);
			},
			oncancel: function(result) {
				HELPER.unblock(100);
			}
		});
	}

	function onEdit(user_id) {
		$("[data-action='cancel']").click()
		HELPER.ajax({
			url: HELPER.api.read,
			data: {
				id: user_id
			},
			complete: function(res) {
				$('#pegawai_foto').css('background-image', 'url(./assets/media/noimage.png)');
				$("#modal-title").html('Update user')
				$("#modalData").modal('show')
				HELPER.populateForm($('#form-user'), res);
				if (res.pegawai_foto) {
					$('#pegawai_foto').css('background-image', 'url(dokumen/user/' + res.pegawai_foto + ')');
				}
				console.log(res.pegawai_role_access_id)
				$("#pegawai_role_access_id").val(res.pegawai_role_access_id).trigger("change")
			}
		})
	}

	function onDelete(user_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete ?',
			callback: function(suc) {
				if (suc) {
					HELPER.block()

					HELPER.ajax({
						url: HELPER.api.delete,
						data: {
							id: user_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								HELPER.refresh({
									table: 'table-user'
								});
							} else {
								HELPER.showMessage()
							}
							HELPER.unblock(100)
						}
					})
				}
			}
		})
	}

	function onDetail(user_id) {
		HELPER.ajax({
			url: HELPER.api.read,
			data: {
				id: user_id
			},
			complete: function(res) {

				HELPER.detailmodal($('#modal-user'), res);
				$("#modal-user").modal('show')
				if (res.pegawai_foto) {
					img_full = BASE_CONTENT + 'user/' + res.pegawai_foto;
					img = BASE_CONTENT + 'user/thumbs/' + res.pegawai_foto;
				} else {
					img = './assets/media/noimage.png';
					img_full = './assets/media/noimage.png';
				}
				$('.detail-foto-user').attr({
					'src': img,
					'data-lightbox': img
				});
				$('.detail-foto-user-full').attr({
					'href': img_full,
					'data-lightbox': img_full
				});
			}
		})
	}

	function onStatus(user_id) {
		HELPER.confirm({
			message: 'Are you sure you want to change status ?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'user/status',
						data: {
							id: user_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully change status.'
								})

								HELPER.refresh({
									table: 'table-user'
								});
							} else {
								HELPER.showMessage()
							}
							HELPER.unblock(100)
						}
					})
				}
			}
		})
	}

	function onResetPassword(user_id) {
		HELPER.confirm({
			message: 'Do you want to reset this user login password?',
			callback: function(has) {
				if (has) {
					HELPER.ajax({
						url: HELPER.api.resetPassword,
						data: {
							pegawai_id: user_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success !',
									message: 'You have successfully reset your password!'
								})
							}
						},
						error: function() {
							HELPER.showMessage()
						}
					})
				}
			}
		})
	}

	function onSetProject(user_id) {
		HELPER.createCombo({
			el: ['user_project_project_id'],
			valueField: 'project_id',
			displayField: 'project_code',
			url: BASE_URL + 'user/combobox_project',
			data: {
				pegawai_id: user_id
			},
			withNull: true,
			grouped: false,
			chosen: true,
		});
		$('#user_project_user_id').val(user_id)
		onTableProject(user_id)
		$('#modal-user-project').modal('show')
	}

	function onTableProject(user_id) {
		HELPER.initTable({
			el: "table-user-project",
			url: HELPER.api.tableProject,
			data: {
				pegawai_id: user_id
			},
			searchAble: false,
			destroyAble: true,
			info: false,
			lengthChange: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['project_code'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						if (moment().isAfter(full['project_start_date'])) {
							if (moment().isAfter(full['project_end_date'])) {
								return '<span class="badge badge-danger">End date expired </span>'
							} else {
								return '<span class="badge badge-success">Active</span>'
							}
						} else {
							return '<span class="badge badge-danger">Not yet started</span>';
						}
					},
				},
				{
					targets: 3,
					width: '20px',
					orderable: false,
					render: function(data, type, full, meta) {
						var btn_aksi = `<a href="javascript:;" class="btn btn-sm btn-danger" onclick="onDeleteProject('` + full['user_project_id'] + `')">\
											Delete
										</a>`;
						return btn_aksi;
					},
				},
			],
		});
	}

	function saveProject() {
		var form = $('#form-user-project')[0];
		var formData = new FormData(form);
		HELPER.save({
			cache: false,
			url: BASE_URL + 'user/saveProject',
			data: formData,
			contentType: false,
			processData: false,
			form: "form-user-project",
			confirm: true,
			callback: function(success, id, record, message) {
				if (success) {
					HELPER.showMessage({
						success: true,
						title: "Success",
						message: "Successfully save data"
					});

					onSetProject($('#user_project_user_id').val())
				} else {
					HELPER.showMessage({
						success: false
					})
				}
				HELPER.unblock(100);
			},
			oncancel: function(result) {
				HELPER.unblock(100);
			}
		});
	}

	function onDeleteProject(user_project_id) {
		HELPER.confirm({
			message: 'Are you sure want to delete ?',
			callback: function(suc) {
				if (suc) {
					HELPER.block()
					HELPER.ajax({
						url: BASE_URL + 'user/deleteProject',
						data: {
							id: user_project_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'Succeed delete.'
								})
								onSetProject($('#user_project_user_id').val())
							} else {
								HELPER.showMessage()
							}
							HELPER.unblock(100)
						}
					})
				}
			}
		})
	}
</script>