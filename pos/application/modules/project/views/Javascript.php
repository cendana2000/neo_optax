<script type="text/javascript">
	var fv;
	$(function() {
		$('.datepicker').datepicker({
			todayHighlight: 'TRUE',
			autoclose: true,
			format: 'dd-mm-yyyy',
			startDate: '-0d',
		});
		HELPER.fields = [
			'project_id',
			'project_request_id'
		];

		HELPER.api = {
			table: BASE_URL + 'project/loadTable',
			store: BASE_URL + 'project/store',
			update: BASE_URL + 'project/update',
			read: BASE_URL + 'project/read',
			delete: BASE_URL + 'project/delete',
		};

		fv = HELPER.newHandleValidation({
			el: 'form-project',
			declarative: true,
			setting: [{
				name: 'Project Code',
				selector: '.project_code',
				rule: {
					regexp: {
						regexp: /^[A-Z0-9]*$/,
						message: 'Only string alphabetical characters and numbers only'
					},
					promise: {
						promise: function(input) {
							return new Promise(function(resolve, reject) {
								HELPER.ajax({
									url: BASE_URL + 'project/cekCode',
									data: {
										code: $(input.element).val()
									},
									success: function(res) {
										$(input.element).val($(input.element).val().toUpperCase())
										if (res.success) {
											resolve({
												valid: true // Required
											});
										} else {
											if ($('#project_id').val() == res.id) {
												resolve({
													valid: true // Required
												});
											} else {
												resolve({
													valid: false, // Required
													message: 'Code must be unique', // Optional
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
							})
						}
					}
				}
			}]
		});

		$('#project_request_id').on('select2:select', function() {
			HELPER.ajax({
				url: BASE_URL + 'project/readProjectReq',
				data: {
					id: this.value
				},
				complete: function(res) {
					$('.project_location').val(res.project_request_location)
					$('.project_hole_plan').val(res.project_request_open_hole)
					$('.project_core_plan').val(res.project_request_coring)
					$('.project_description').val(res.project_request_description)
					$('.project_borehole_plan').val(res.project_request_borehole)
					$('.project_total_sample_plan').val(res.project_request_sample)
					$('.project_start_date').val(res.project_request_start_date)
					$('.project_end_date').val(res.project_request_end_date)
				}
			})
		})

		$('.mask-int').inputmask({
			'digits': 2,
			'digitsOptional': true,
			'placeholder': '0',
			'rightAlign': false,
			"mask": "9{1,7}",
		});

		loadTable();
	})

	function comboboc_request() {
		HELPER.createCombo({
			el: ['project_request_id'],
			valueField: 'project_request_id',
			displayField: 'project_request_pic_name',
			url: BASE_URL + 'project/combobox_projectRequest',
			withNull: true,
			grouped: false,
			chosen: true,
			callback: function() {}
		});
	}

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('project-Update') || HELPER.get_role_access('project-Delete'));
		let show_dropdown = (HELPER.get_role_access('project-Update') || HELPER.get_role_access('project-Delete') || HELPER.get_role_access('project-Read'));
		HELPER.initTable({
			el: "table-project",
			url: BASE_URL + 'project/loadTable',
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['project_code'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return `<span class="text-wrap">${full['project_location']}</span>`;
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['project_start_date'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['project_end_date'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return `<span class="text-wrap">${full['project_description']}</span>`;
					},
				},
				{
					targets: 6,
					width: '20px',
					orderable: false,
					visible: show_aksi,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						if (HELPER.get_role_access('project-Read')) {
							btn_aksi += '<a href="javascript:void(0)" class="btn btn-sm btn-primary mx-2" onclick="onDetail(\'' + full['project_id'] + '\')"> Detail\
											</a>';
						}

						menu = "";
						dropdown = "";
						if (HELPER.get_role_access('project-Update')) {
							menu += `<li class="nav-item"><a class="nav-link" href="javascript:;" onclick="onEdit('` + full['project_id'] + `')"><i class="nav-icon fa fa-pen"></i><span class="nav-text">Edit</span></a></li>`;
						}
						if (HELPER.get_role_access('project-Delete')) {
							menu += `<li class="nav-item"><a class="nav-link" href="javascript:;" onclick="onDelete('` + full['project_id'] + `')"><i class="nav-icon fa fa-trash"></i><span class="nav-text">Delete</span></a></li>`;
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
										</div>`
						};
						return btn_aksi + dropdown;
					},
				},

			],
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
			form: "form-project",
			confirm: true,
			callback: function(success, id, record, message) {
				if (success) {
					HELPER.showMessage({
						success: true,
						title: "Success",
						message: "successfully saved data"
					});
					onReset();
					loadTable();
					onBack();
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

	function onCreate() {
		$('.request_list').show()
		comboboc_request()
		HELPER.toggleForm({
			tohide: 'table_data',
			toshow: 'form_data'
		});
		onReset();
		$.each(HELPER.fields, function(i, v) {
			$('[name="' + v + '"]').val('').trigger('change');
		});
	}

	function onEdit(project_id) {
		$('.request_list').hide()
		HELPER.ajax({
			url: HELPER.api.read,
			data: {
				id: project_id
			},
			complete: function(res) {
				$('.project_description').val(res.project_description)
				HELPER.populateForm($('#form-project'), res);

				HELPER.toggleForm({
					tohide: 'table_data',
					toshow: 'form_data'
				});
			}
		})
	}

	function onDelete(project_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'project/delete',
						data: {
							id: project_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								HELPER.refresh({
									table: 'table-project'
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

	function onDetail(id) {
		HELPER.ajax({
			url: HELPER.api.read,
			data: {
				id: id
			},
			complete: function(res) {
				$('#modal-detail').modal('show')
				HELPER.detailmodal($('#modal-detail'), res);
			}
		})
	}

	function onBack() {
		HELPER.back({
			table: "table-project",
			loadPage: false,
		});
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-project'
		});
	}

	function onReset() {
		$('#form-project').trigger('reset');
	}
</script>