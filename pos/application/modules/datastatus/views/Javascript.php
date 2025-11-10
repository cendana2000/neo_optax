<script type="text/javascript">
	var fv;
	$(function() {

		HELPER.fields = [
			'data_status_id',
		];

		HELPER.api = {
			table: BASE_URL + 'datastatus/loadTable',
			store: BASE_URL + 'datastatus/store',
			update: BASE_URL + 'datastatus/update',
			read: BASE_URL + 'datastatus/read',
			delete: BASE_URL + 'datastatus/delete',
		};

		fv = HELPER.newHandleValidation({
			el: 'form-data-status',
			declarative: true,
			setting: [{
				name: 'Data Status',
				selector: '.data_status_code',
				rule: {
					promise: {
						promise: function(input) {
							return new Promise(function(resolve, reject) {
								HELPER.ajax({
									url: BASE_URL + 'datastatus/cekCode',
									data: {
										code: $(input.element).val()
									},
									success: function(res) {
										if (res.success) {
											resolve({
												valid: true // Required
											});
										} else {
											if ($('#data_status_id').val() == res.id) {
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

		loadTable();
	})

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('datastatus-Update') || HELPER.get_role_access('datastatus-Delete'));
		HELPER.initTable({
			el: "table-data-status",
			url: BASE_URL + 'datastatus/loadTable',
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['data_status_code'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['data_status_description'];
					},
				},
				{
					targets: 3,
					width: '20px',
					orderable: false,
					visible: show_aksi,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						if (HELPER.get_role_access('datastatus-Update')) {
							btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['data_status_id'] + `')">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-pen"></i>
											</span>
										</a>`;
						}
						if (HELPER.get_role_access('datastatus-Delete')) {
							btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['data_status_id'] + `')"">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-trash"></i>
											</span>
										</a>`;
						}
						return btn_aksi;
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
			form: "form-data-status",
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
		HELPER.toggleForm({
			tohide: 'table_data',
			toshow: 'form_data'
		});
		onReset();
	}

	function onEdit(data_status_id) {
		HELPER.ajax({
			url: HELPER.api.read,
			data: {
				id: data_status_id
			},
			complete: function(res) {
				$('.rif_type_description').val(res.rif_type_description)
				HELPER.populateForm($('#form-data-status'), res);

				HELPER.toggleForm({
					tohide: 'table_data',
					toshow: 'form_data'
				});
			}
		})
	}

	function onDelete(data_status_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'datastatus/delete',
						data: {
							id: data_status_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'Are you sure you want to delete?'
								})

								HELPER.refresh({
									table: 'table-data-status'
								});
							} else {
								HELPER.showMessage({
									success: 'info',
									title: 'Stop',
									message: res.message
								})
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

				HELPER.detailmodal($('#modal-mitra'), res);
			}
		})
	}

	function onBack() {
		HELPER.back({
			table: "table-data-status",
			loadPage: false,
		});
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-data-status'
		});
	}

	function onReset() {
		$('#form-data-status').trigger('reset');
	}
</script>