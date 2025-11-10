<script type="text/javascript">
	var avatar5 = new KTImageInput('kasir_avatar');
	$(function() {
		HELPER.fields = [
			'kasir_id',
			'kasir_kode',
			'kasir_nama',
			'kasir_ip',
			'kasir_avatar',
		];
		HELPER.setRequired([
			'kasir_id',
			'kasir_kode',
			'kasir_nama',
			'kasir_ip',
		]);
		HELPER.api = {
			table: BASE_URL + 'pegawai/',
			read: BASE_URL + 'pegawai/read',
			store: BASE_URL + 'pegawai/store',
			update: BASE_URL + 'pegawai/update',
			destroy: BASE_URL + 'pegawai/destroy',
		}
		/*HELPER.initTable({
			el : 'table-kasir',
			url: HELPER.api.table,
		})*/
		loadTable();
	});

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('kasir-Update') || HELPER.get_role_access('kasir-Delete'));
		HELPER.initTable({
			el: "table-kasir",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: true,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['kasir_nama'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['kasir_ip'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['kasir_kode'];
					},
				},
				{
					targets: 4,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// if (HELPER.get_role_access('kasir-Update')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['kasir_id'] + `')">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-pen"></i>
											</span>
										</a>`;
						// }
						// if (HELPER.get_role_access('kasir-Delete')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['kasir_id'] + `')"">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-trash"></i>
											</span>
										</a>`;
						// }
						return btn_aksi;
					},
				},

			],
		});
	}

	function onCreate() {
		comboboxProject()
		$('#kasir_avatar').css('background-image', 'url(./assets/media/noimage.png)');
		// $('#kasir_avatar').css('background-image', '');
		$('#btn-remove-img').hide()
		HELPER.toggleForm({
			tohide: 'table_data',
			toshow: 'form_data'
		});
		fv.resetForm(true)
		$.each(HELPER.fields, function(i, v) {
			$('[name="' + v + '"]').val('').trigger('change');
		});
		onReset();

	}

	function onDelete(kasir_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'pegawai/delete',
						data: {
							id: kasir_id
						},
						complete: function(res) {
							console.log(res);
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								HELPER.refresh({
									table: 'table-kasir'
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


	function onEdit(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				kasir_id: id
			},
			callback: function(res) {
				// console.log(res)
			}
		})
	}

	function onEdit2(el) {
		HELPER.loadData({
			table: 'table-kasir',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				// console.log(res)
			}
		})
	}

	function onBack() {
		HELPER.back2();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-kasir'
		})
	}

	// function save(name) {
	// 	var form = $('#' + name)[0];
	// 	var formData = new FormData(form);
	// 	HELPER.save({
	// 		cache: false,
	// 		data: formData,
	// 		contentType: false,
	// 		processData: false,
	// 		form: "form-drilling-company",
	// 		confirm: true,
	// 		callback: function(success, id, record, message) {
	// 			if (success) {
	// 				HELPER.showMessage({
	// 					success: true,
	// 					title: "Success",
	// 					message: "successfully saved data"
	// 				});
	// 				onReset();
	// 				loadTable();
	// 				onBack();
	// 			} else {
	// 				HELPER.showMessage({
	// 					success: false,
	// 				})
	// 			}
	// 			HELPER.unblock(100);
	// 		},
	// 		oncancel: function(result) {
	// 			HELPER.unblock(100);
	// 		}
	// 	});
	// }

	function save() {
		HELPER.save({
			form: 'form-kasir',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					onRefresh();
				}
			}
		})
	}

	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-kasir',
			inline: el,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success == true) {
					onRefresh()
				}
			}
		})
	}
</script>