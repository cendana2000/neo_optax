<script type="text/javascript">
	var fv;
	$(function() {

		HELPER.fields = [
			'jabatan_id',
			'jabatan_nama',
		];

		HELPER.api = {
			table: BASE_URL + 'jabatan/loadTable',
			store: BASE_URL + 'jabatan/store',
			update: BASE_URL + 'jabatan/update',
			read: BASE_URL + 'jabatan/read',
			delete: BASE_URL + 'jabatan/delete',
		};

		fv = HELPER.newHandleValidation({
			el: 'form-jabatan',
			declarative: true
		});

		loadTable();
	})

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('Jabatan-Update') || HELPER.get_role_access('Jabatan-Delete'));
		HELPER.initTable({
			el: "table-jabatan",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive:false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['jabatan_nama'];
					},
				},
				{
					targets: 2,
					width: '10px',
					orderable: false,
					visible: show_aksi,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						if (HELPER.get_role_access('Jabatan-Update')) {
							btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['jabatan_id'] + `')">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-pen"></i>
											</span>
										</a>`;
						}
						if (HELPER.get_role_access('Jabatan-Delete')) {
							btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['jabatan_id'] + `')"">
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
			form: "form-jabatan",
			confirm: true,
			callback: function(success, id, record, message) {
				if (success) {
					HELPER.showMessage({
						success: true,
						title: "Sukses",
						message: "Berhasil menyimpan data"
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



	function onEdit(jabatan_id) {
		HELPER.ajax({
			url: HELPER.api.read,
			data: {
				id: jabatan_id
			},
			complete: function(res) {

				HELPER.populateForm($('#form-jabatan'), res);
			}
		})
	}


	function onDelete(jabatan_id) {
		HELPER.confirm({
			message: 'Apakah Anda yakin ingin menghapus ?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'jabatan/delete',
						data: {
							id: jabatan_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'Anda berhasil menghapus.'
								})

								HELPER.refresh({
									table: 'table-jabatan'
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

				HELPER.detailmodal($('#modal-mitra'), res);

			}
		})
	}

	function onBack() {
		fv.resetForm()
		$.each(HELPER.fields, function(i, v) {
			$('[name="' + v + '"]').val('');
		});
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-jabatan'
		});
	}

	function onReset() {
		$('#form-jabatan').trigger('reset');
	}
</script>